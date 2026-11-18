<?php
//posme:2026-11-17
namespace App\Libraries;

use App\Models\Customer_Model;
use App\Models\Transaction_Master_Info_Model;
use App\Models\Transaction_Master_Model;

/**
 * Notas de credito (NC) de CxC: saldo del cliente, creacion de NC aplicadas
 * (devoluciones), consumo FIFO como forma de pago en facturas y su reversion.
 *
 * Convenciones:
 * - Saldo NC del cliente: tb_customer.balanceCor (moneda por defecto) / balanceDol (otra moneda).
 * - En tb_transaction_master de la NC: amount = monto, discount = saldo cliente antes, subAmount = saldo despues.
 * - En tb_transaction_master_info de la NC: receiptAmountNoteCredit = monto, noteCreditBalance = saldo disponible.
 * - En tb_transaction_master_info de la factura: noteCreditDetail = "idNC|numeroNC|montoAplicado|saldoNCnuevo;..."
 *   con prefijo "REVERTIDO:" cuando se revirtio.
 *
 * Ninguna funcion abre ni cierra transaccion de BD: el llamador hace transStart / transCommit / transRollback.
 * Todos los montos se redondean a 2 decimales.
 */
class core_web_note_credit
{
    const COMPONENT_NOTE_CREDIT = "tb_transaction_master_cxc_notacredit";
    const PREFIX_REVERTED       = "REVERTIDO:";
    const MAX_DETAIL_LENGTH     = 990; //noteCreditDetail es varchar(1000) y se reservan 10 para el prefijo REVERTIDO:

    /**
     * transactionID de la transaccion de notas de credito de la compania.
     * @param int $companyID
     * @return int
     */
    function getTransactionIDNoteCredit($companyID)
    {
        $core_web_transaction = new core_web_transaction();
        $transactionID        = $core_web_transaction->getTransactionID($companyID, self::COMPONENT_NOTE_CREDIT, 0);
        if (!$transactionID)
            throw new \Exception("LA TRANSACCION '" . self::COMPONENT_NOTE_CREDIT . "' NO EXISTE...");

        return (int)$transactionID;
    }

    /**
     * Saldo disponible de NC del cliente en una moneda (suma de noteCreditBalance).
     * @param int $companyID
     * @param int $entityID
     * @param int $currencyID
     * @return float
     */
    function getSaldoDisponible($companyID, $entityID, $currencyID)
    {
        if (empty($entityID) || empty($currencyID))
            return 0.0;

        $Transaction_Master_Info_Model = new Transaction_Master_Info_Model();
        $transactionIDNoteCredit       = $this->getTransactionIDNoteCredit($companyID);
        return round((float)$Transaction_Master_Info_Model->get_sumNoteCreditBalanceByCustomer($companyID, $entityID, $currencyID, $transactionIDNoteCredit), 2);
    }

    /**
     * Suma el monto de una NC al saldo NC del cliente y deja su fila info con saldo disponible.
     * Idempotente: si la fila info ya tiene receiptAmountNoteCredit > 0 no hace nada.
     * @param array $dataSession
     * @param int   $transactionID
     * @param int   $transactionMasterID
     * @return void
     */
    function sumarNcAlSaldo($dataSession, $transactionID, $transactionMasterID)
    {
        $Transaction_Master_Model      = new Transaction_Master_Model();
        $Transaction_Master_Info_Model = new Transaction_Master_Info_Model();
        $Customer_Model                = new Customer_Model();
        $companyID                     = $dataSession["user"]->companyID;

        $objNC = $Transaction_Master_Model->get_rowByPKForUpdate($companyID, $transactionID, $transactionMasterID);
        if (!$objNC)
            throw new \Exception("LA NOTA DE CREDITO NO EXISTE O ESTA INACTIVA");

        $objInfo = $Transaction_Master_Info_Model->get_rowByTransactionMasterIDForUpdate($companyID, $transactionID, $transactionMasterID);
        if ($objInfo && round((float)$objInfo->receiptAmountNoteCredit, 2) > 0)
            return;

        $amount = round((float)$objNC->amount, 2);
        if ($amount <= 0)
            throw new \Exception("EL MONTO DE LA NOTA DE CREDITO DEBE SER MAYOR QUE 0");

        $objCustomer = $Customer_Model->get_rowByEntityForUpdate($companyID, $objNC->entityID);
        if (!$objCustomer)
            throw new \Exception("EL CLIENTE DE LA NOTA DE CREDITO NO EXISTE O ESTA INACTIVO");

        $field  = $this->getBalanceField($companyID, $objNC->currencyID);
        $before = round((float)$objCustomer->$field, 2);
        $after  = round($before + $amount, 2);

        $Customer_Model->update_balanceByEntity($companyID, $objNC->entityID, array($field => $after));
        $Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, array(
            "discount"  => $before,
            "subAmount" => $after
        ));

        $dataInfo                              = array();
        $dataInfo["receiptAmountNoteCredit"]   = $amount;
        $dataInfo["noteCreditBalance"]         = $amount;
        $dataInfo["customerBalanceNoteCredit"] = $after;

        if ($objInfo) {
            $Transaction_Master_Info_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $dataInfo);
        } else {
            $dataInfo["companyID"]           = $companyID;
            $dataInfo["transactionID"]       = $transactionID;
            $dataInfo["transactionMasterID"] = $transactionMasterID;
            $Transaction_Master_Info_Model->insert_app_posme($dataInfo);
        }
    }

    /**
     * Crea una NC de CxC directamente en el primer estado aplicable y suma su monto al saldo del cliente.
     * Mismo armado que app_cxc_notecredit::insertElement.
     * @param array  $dataSession
     * @param int    $entityID      Cliente
     * @param int    $currencyID    Moneda de la NC
     * @param float  $exchangeRate  Tasa de cambio del documento origen
     * @param float  $amount        Monto de la NC
     * @param string $transactionOn Fecha (Y-m-d)
     * @param string $note          Descripcion
     * @param string $reference1
     * @param string $reference2
     * @param string $reference3
     * @param string $reference4
     * @return array ['transactionID','transactionMasterID','transactionNumber']
     */
    function crearNcAplicada($dataSession, $entityID, $currencyID, $exchangeRate, $amount, $transactionOn, $note, $reference1, $reference2, $reference3, $reference4)
    {
        $core_web_tools           = new core_web_tools();
        $core_web_transaction     = new core_web_transaction();
        $core_web_counter         = new core_web_counter();
        $core_web_workflow        = new core_web_workflow();
        $core_web_auditoria       = new core_web_auditoria();
        $Transaction_Master_Model = new Transaction_Master_Model();

        $amount = round((float)$amount, 2);
        if ($amount <= 0)
            throw new \Exception("EL MONTO DE LA NOTA DE CREDITO DEBE SER MAYOR QUE 0");

        if (empty($entityID))
            throw new \Exception("NO SE INDICO EL CLIENTE DE LA NOTA DE CREDITO");

        if (empty($currencyID))
            throw new \Exception("NO SE INDICO LA MONEDA DE LA NOTA DE CREDITO");

        $companyID = $dataSession["user"]->companyID;
        $branchID  = $dataSession["user"]->branchID;
        $roleID    = $dataSession["role"]->roleID;

        $objComponent = $core_web_tools->getComponentIDBy_ComponentName(self::COMPONENT_NOTE_CREDIT);
        if (!$objComponent)
            throw new \Exception("EL COMPONENTE '" . self::COMPONENT_NOTE_CREDIT . "' NO EXISTE...");

        $transactionID  = $this->getTransactionIDNoteCredit($companyID);
        $objStageApply  = $core_web_workflow->getWorkflowStageApplyFirst(self::COMPONENT_NOTE_CREDIT, "statusID", $companyID, $branchID, $roleID);
        if (empty($objStageApply))
            throw new \Exception("NO EXISTE UN ESTADO APLICABLE PARA LA NOTA DE CREDITO O EL ROL NO TIENE PERMISO SOBRE EL");

        $objTM                        = array();
        $objTM["companyID"]           = $companyID;
        $objTM["transactionNumber"]   = $core_web_counter->goNextNumber($companyID, $branchID, self::COMPONENT_NOTE_CREDIT, 0);
        $objTM["transactionID"]       = $transactionID;
        $objTM["branchID"]            = $branchID;
        $objTM["transactionCausalID"] = $core_web_transaction->getDefaultCausalID($companyID, $transactionID);
        $objTM["entityID"]            = $entityID;
        $objTM["transactionOn"]       = $transactionOn;
        $objTM["statusIDChangeOn"]    = helper_getDateTime();
        $objTM["componentID"]         = $objComponent->componentID;
        $objTM["note"]                = $note;
        $objTM["currencyID"]          = $currencyID;
        $objTM["exchangeRate"]        = $exchangeRate;
        $objTM["reference1"]          = $reference1;
        $objTM["reference2"]          = $reference2;
        $objTM["reference3"]          = $reference3;
        $objTM["reference4"]          = $reference4;
        $objTM["statusID"]            = $objStageApply[0]->workflowStageID;
        $objTM["amount"]              = $amount;
        $objTM["discount"]            = 0;
        $objTM["subAmount"]           = 0;
        $objTM["isApplied"]           = 1;
        $objTM["isActive"]            = 1;
        $core_web_auditoria->setAuditCreated($objTM, $dataSession, \Config\Services::request());

        $transactionMasterID = $Transaction_Master_Model->insert_app_posme($objTM);
        if (!$transactionMasterID)
            throw new \Exception("NO FUE POSIBLE REGISTRAR LA NOTA DE CREDITO");

        //Carpeta de archivos del documento (igual que app_cxc_notecredit)
        $path_ = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID;
        if (!file_exists($path_))
            mkdir($path_, 0700, true);

        $this->sumarNcAlSaldo($dataSession, $transactionID, $transactionMasterID);

        return array(
            "transactionID"       => $transactionID,
            "transactionMasterID" => $transactionMasterID,
            "transactionNumber"   => $objTM["transactionNumber"]
        );
    }

    /**
     * Consume NC del cliente (FIFO, misma moneda) para pagar una factura y baja el saldo NC del cliente.
     * No escribe la fila info de la factura: el llamador guarda receiptAmountNoteCredit,
     * customerBalanceNoteCredit y noteCreditDetail con lo que devuelve.
     * @param array        $dataSession
     * @param object|array $objInvoice  companyID, transactionID, transactionMasterID, entityID, currencyID, amount (total)
     * @param float        $amount      Monto a pagar con NC
     * @return array ['detail' => string, 'customerBalance' => float, 'amount' => float]
     */
    function consumirNcEnFactura($dataSession, $objInvoice, $amount)
    {
        $Transaction_Master_Info_Model = new Transaction_Master_Info_Model();
        $Customer_Model                = new Customer_Model();

        $objInvoice = (object)$objInvoice;
        $companyID  = $objInvoice->companyID;
        $amount     = round((float)$amount, 2);
        $total      = round((float)$objInvoice->amount, 2);

        if ($amount <= 0)
            throw new \Exception("EL MONTO A PAGAR CON NOTAS DE CREDITO DEBE SER MAYOR QUE 0");

        if ($amount > $total)
            throw new \Exception("EL MONTO PAGADO CON NOTAS DE CREDITO (" . number_format($amount, 2) . ") NO PUEDE SER MAYOR QUE EL TOTAL DE LA FACTURA (" . number_format($total, 2) . ")");

        if (empty($objInvoice->entityID))
            throw new \Exception("LA FACTURA NO TIENE CLIENTE PARA PAGAR CON NOTAS DE CREDITO");

        //Orden de bloqueo: cliente y luego NC (el mismo que usa revertirNcDeFactura)
        $objCustomer = $Customer_Model->get_rowByEntityForUpdate($companyID, $objInvoice->entityID);
        if (!$objCustomer)
            throw new \Exception("EL CLIENTE DE LA FACTURA NO EXISTE O ESTA INACTIVO");

        $transactionIDNoteCredit = $this->getTransactionIDNoteCredit($companyID);
        $listNoteCredit          = $Transaction_Master_Info_Model->get_noteCreditAvailableByCustomer($companyID, $objInvoice->entityID, $objInvoice->currencyID, $transactionIDNoteCredit, true);

        $available = 0;
        foreach ($listNoteCredit as $nc)
            $available = round($available + (float)$nc->noteCreditBalance, 2);

        if ($amount > $available)
            throw new \Exception("SALDO DE NOTAS DE CREDITO INSUFICIENTE: DISPONIBLE " . number_format($available, 2));

        //Consumo FIFO: la NC mas antigua primero
        $pending = $amount;
        $detail  = array();
        foreach ($listNoteCredit as $nc) {
            if ($pending <= 0)
                break;

            $balance = round((float)$nc->noteCreditBalance, 2);
            $apply   = round(min($balance, $pending), 2);
            if ($apply <= 0)
                continue;

            $newBalance = round($balance - $apply, 2);
            $Transaction_Master_Info_Model->update_app_posme($companyID, $nc->transactionID, $nc->transactionMasterID, array("noteCreditBalance" => $newBalance));

            $detail[] = $nc->transactionMasterID . "|" . $nc->transactionNumber . "|" . number_format($apply, 2, ".", "") . "|" . number_format($newBalance, 2, ".", "");
            $pending  = round($pending - $apply, 2);
        }

        if ($pending > 0)
            throw new \Exception("SALDO DE NOTAS DE CREDITO INSUFICIENTE: DISPONIBLE " . number_format($available, 2));

        $detail = implode(";", $detail);
        if (strlen($detail) > self::MAX_DETAIL_LENGTH)
            throw new \Exception("EL PAGO USA DEMASIADAS NOTAS DE CREDITO PARA UNA SOLA FACTURA, REDUZCA EL MONTO");

        //Saldo NC del cliente: nunca negativo
        $field  = $this->getBalanceField($companyID, $objInvoice->currencyID);
        $before = round((float)$objCustomer->$field, 2);
        $after  = round(max(0, $before - $amount), 2);
        $Customer_Model->update_balanceByEntity($companyID, $objInvoice->entityID, array($field => $after));

        return array(
            "detail"          => $detail,
            "customerBalance" => $after,
            "amount"          => $amount
        );
    }

    /**
     * Devuelve a cada NC y al saldo del cliente lo consumido por una factura (anulacion / reversion).
     * Si la factura no uso NC o ya fue revertida no hace nada.
     * @param array $dataSession
     * @param int   $companyID
     * @param int   $transactionID       Transaccion de la factura
     * @param int   $transactionMasterID Factura
     * @return void
     */
    function revertirNcDeFactura($dataSession, $companyID, $transactionID, $transactionMasterID)
    {
        $Transaction_Master_Model      = new Transaction_Master_Model();
        $Transaction_Master_Info_Model = new Transaction_Master_Info_Model();
        $Customer_Model                = new Customer_Model();

        $objInfo = $Transaction_Master_Info_Model->get_rowByTransactionMasterIDForUpdate($companyID, $transactionID, $transactionMasterID);
        if (!$objInfo)
            return;

        $detail = trim((string)$objInfo->noteCreditDetail);
        if ($detail == "" || strpos($detail, self::PREFIX_REVERTED) === 0)
            return;

        $objInvoice = $Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
        if (!$objInvoice)
            throw new \Exception("NO SE ENCONTRO LA FACTURA PARA REVERTIR EL PAGO CON NOTAS DE CREDITO");

        $objCustomer = $Customer_Model->get_rowByEntityForUpdate($companyID, $objInvoice->entityID);
        if (!$objCustomer)
            throw new \Exception("EL CLIENTE DE LA FACTURA NO EXISTE O ESTA INACTIVO, NO SE PUEDEN REVERTIR LAS NOTAS DE CREDITO");

        $transactionIDNoteCredit = $this->getTransactionIDNoteCredit($companyID);
        $totalReturned           = 0;

        foreach (explode(";", $detail) as $part) {
            $part = trim($part);
            if ($part == "")
                continue;

            $cols = explode("|", $part);
            if (count($cols) < 3)
                throw new \Exception("DETALLE DE NOTAS DE CREDITO INVALIDO EN LA FACTURA: " . $part);

            $ncMasterID = (int)$cols[0];
            $applied    = round((float)$cols[2], 2);
            if ($ncMasterID <= 0 || $applied <= 0)
                continue;

            $objNcInfo = $Transaction_Master_Info_Model->get_rowByTransactionMasterIDForUpdate($companyID, $transactionIDNoteCredit, $ncMasterID);
            if (!$objNcInfo)
                throw new \Exception("NO SE ENCONTRO LA NOTA DE CREDITO " . $cols[1] . " PARA REVERTIR EL PAGO");

            //El saldo de la NC nunca supera su monto original
            $oldBalance = round((float)$objNcInfo->noteCreditBalance, 2);
            $newBalance = round(min($oldBalance + $applied, (float)$objNcInfo->receiptAmountNoteCredit), 2);
            $returned   = round($newBalance - $oldBalance, 2);
            if ($returned <= 0)
                continue;

            $Transaction_Master_Info_Model->update_app_posme($companyID, $transactionIDNoteCredit, $ncMasterID, array("noteCreditBalance" => $newBalance));
            $totalReturned = round($totalReturned + $returned, 2);
        }

        $field  = $this->getBalanceField($companyID, $objInvoice->currencyID);
        $before = round((float)$objCustomer->$field, 2);
        $after  = round($before + $totalReturned, 2);
        if ($totalReturned > 0)
            $Customer_Model->update_balanceByEntity($companyID, $objInvoice->entityID, array($field => $after));

        $Transaction_Master_Info_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, array(
            "noteCreditDetail"          => self::PREFIX_REVERTED . $detail,
            "customerBalanceNoteCredit" => $after
        ));
    }

    /**
     * Campo de saldo NC del cliente segun la moneda: balanceCor (moneda por defecto) o balanceDol.
     * @param int $companyID
     * @param int $currencyID
     * @return string
     */
    private function getBalanceField($companyID, $currencyID)
    {
        $core_web_currency  = new core_web_currency();
        $objCurrencyDefault = $core_web_currency->getCurrencyDefault($companyID);
        return ($objCurrencyDefault && (int)$currencyID == (int)$objCurrencyDefault->currencyID) ? "balanceCor" : "balanceDol";
    }
}
