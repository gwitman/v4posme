<?php 
//posme:2023-02-27
namespace App\Models; 
use CodeIgniter\Model;

class Transaction_Master_Info_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function delete_app_posme($companyID,$transactionID,$transactionMasterID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_info");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_info");
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$transactionID,$transactionMasterID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_info");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_info");    
		
		$sql = "";
		$sql = sprintf("
		select 
				transactionMasterInfoID,				
				companyID,
				transactionID,
				transactionMasterID,
				zoneID,routeID,mesaID,
				referenceClientName,
				referenceClientIdentifier,
				
				changeAmount,
				receiptAmountPoint,
				
				
				receiptAmount,
				receiptAmountDol,
				
				receiptAmountBank,
				receiptAmountBankID,
				receiptAmountBankReference,
				
				receiptAmountBankDol,
				receiptAmountBankDolID,
				receiptAmountBankDolReference,
				
				receiptAmountCard,
				receiptAmountCardBankID,
				receiptAmountCardBankReference,
				
				receiptAmountCardDol,
				receiptAmountCardBankDolID,
				receiptAmountCardBankDolReference,
				
				tm.reference1,
				tm.reference2,
				ci.name as zonaName,
				ci2.name as mesaName
		");
		$sql = $sql.sprintf(" from tb_transaction_master_info tm");		
		$sql = $sql.sprintf(" left join tb_catalog_item ci on  ci.catalogItemID = tm.zoneID ");		
		$sql = $sql.sprintf(" left join tb_catalog_item ci2 on  ci2.catalogItemID = tm.mesaID  ");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowByTransactionNumberAndCreatedBy($companyID,$transactionNumber,$createdBy)
   {
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_info");    
		$sql = "";
		$sql = sprintf("
			select 
				tm.companyID,
				tm.transactionID,
				tm.transactionMasterID,
				tm.transactionNumber,
				tmi.reference2 
			from 
				tb_transaction_master tm 
				inner join tb_transaction_master_info tmi on 
					tm.transactionMasterID = tmi.transactionMasterID 
			where 
				tm.isActive = 1 and 
				tm.companyID = $companyID and 
				tm.createdBy = $createdBy and 
				tmi.reference2 = '$transactionNumber'
				
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }

	/**
	 * Notas de credito del cliente con saldo disponible, en orden FIFO (mas antigua primero).
	 * No filtra estado: una NC solo tiene noteCreditBalance > 0 en su fila info si ya fue aplicada
	 * (sumarNcAlSaldo o carga inicial), por eso basta con el saldo.
	 * @param int  $companyID
	 * @param int  $entityID                Cliente
	 * @param int  $currencyID              Solo NC de esta moneda (sin conversion)
	 * @param int  $transactionIDNoteCredit transactionID de tb_transaction_master_cxc_notacredit
	 * @param bool $forUpdate               true: bloquea las filas info (FOR UPDATE), requiere transaccion abierta
	 * @return array Objetos {transactionID, transactionMasterID, transactionNumber, transactionOn, currencyID, amount, noteCreditBalance, transactionMasterInfoID}
	 */
	function get_noteCreditAvailableByCustomer($companyID,$entityID,$currencyID,$transactionIDNoteCredit,$forUpdate = false){
		$db  = db_connect();
		$sql = "";
		$sql = $sql.sprintf("SELECT ");
		$sql = $sql.sprintf("tm.transactionID, ");
		$sql = $sql.sprintf("tm.transactionMasterID, ");
		$sql = $sql.sprintf("tm.transactionNumber, ");
		$sql = $sql.sprintf("tm.transactionOn, ");
		$sql = $sql.sprintf("tm.currencyID, ");
		$sql = $sql.sprintf("tm.amount, ");
		$sql = $sql.sprintf("i.noteCreditBalance, ");
		$sql = $sql.sprintf("i.transactionMasterInfoID ");
		$sql = $sql.sprintf("FROM tb_transaction_master tm ");
		$sql = $sql.sprintf("INNER JOIN tb_transaction_master_info i ON ");
		$sql = $sql.sprintf("i.companyID = tm.companyID ");
		$sql = $sql.sprintf("AND i.transactionID = tm.transactionID ");
		$sql = $sql.sprintf("AND i.transactionMasterID = tm.transactionMasterID ");
		$sql = $sql.sprintf("WHERE tm.companyID = %d ", $companyID);
		$sql = $sql.sprintf("AND tm.transactionID = %d ", $transactionIDNoteCredit);
		$sql = $sql.sprintf("AND tm.entityID = %d ", $entityID);
		$sql = $sql.sprintf("AND tm.currencyID = %d ", $currencyID);
		$sql = $sql.sprintf("AND tm.isActive = 1 ");
		$sql = $sql.sprintf("AND i.noteCreditBalance > 0 ");
		$sql = $sql.sprintf("ORDER BY tm.transactionOn ASC, tm.transactionMasterID ASC ");
		if($forUpdate)
			$sql = $sql.sprintf("FOR UPDATE");

		return $db->query($sql)->getResult();
	}

	/**
	 * Suma del saldo disponible (noteCreditBalance) de las NC del cliente en una moneda.
	 * @param int $companyID
	 * @param int $entityID
	 * @param int $currencyID
	 * @param int $transactionIDNoteCredit transactionID de tb_transaction_master_cxc_notacredit
	 * @return float Saldo redondeado a 2 decimales
	 */
	function get_sumNoteCreditBalanceByCustomer($companyID,$entityID,$currencyID,$transactionIDNoteCredit){
		$db  = db_connect();
		$sql = "";
		$sql = $sql.sprintf("SELECT ");
		$sql = $sql.sprintf("IFNULL(SUM(i.noteCreditBalance),0) AS balance ");
		$sql = $sql.sprintf("FROM tb_transaction_master tm ");
		$sql = $sql.sprintf("INNER JOIN tb_transaction_master_info i ON ");
		$sql = $sql.sprintf("i.companyID = tm.companyID ");
		$sql = $sql.sprintf("AND i.transactionID = tm.transactionID ");
		$sql = $sql.sprintf("AND i.transactionMasterID = tm.transactionMasterID ");
		$sql = $sql.sprintf("WHERE tm.companyID = %d ", $companyID);
		$sql = $sql.sprintf("AND tm.transactionID = %d ", $transactionIDNoteCredit);
		$sql = $sql.sprintf("AND tm.entityID = %d ", $entityID);
		$sql = $sql.sprintf("AND tm.currencyID = %d ", $currencyID);
		$sql = $sql.sprintf("AND tm.isActive = 1 ");
		$sql = $sql.sprintf("AND i.noteCreditBalance > 0");
		$row = $db->query($sql)->getRow();
		return $row ? round((float)$row->balance,2) : 0.0;
	}

	/**
	 * Campos de nota de credito de la primera fila info de un documento (sin bloqueo).
	 * Se usa al cargar una factura existente; get_rowByPK no incluye estas columnas.
	 * @param int $companyID
	 * @param int $transactionID
	 * @param int $transactionMasterID
	 * @return object|null {receiptAmountNoteCredit, noteCreditBalance, customerBalanceNoteCredit, noteCreditDetail}
	 */
	function get_noteCreditByTransactionMasterID($companyID,$transactionID,$transactionMasterID){
		$db  = db_connect();
		$sql = "";
		$sql = $sql.sprintf("SELECT ");
		$sql = $sql.sprintf("i.receiptAmountNoteCredit, ");
		$sql = $sql.sprintf("i.noteCreditBalance, ");
		$sql = $sql.sprintf("i.customerBalanceNoteCredit, ");
		$sql = $sql.sprintf("i.noteCreditDetail ");
		$sql = $sql.sprintf("FROM tb_transaction_master_info i ");
		$sql = $sql.sprintf("WHERE i.companyID = %d ", $companyID);
		$sql = $sql.sprintf("AND i.transactionID = %d ", $transactionID);
		$sql = $sql.sprintf("AND i.transactionMasterID = %d ", $transactionMasterID);
		$sql = $sql.sprintf("ORDER BY i.transactionMasterInfoID ASC ");
		$sql = $sql.sprintf("LIMIT 1");
		return $db->query($sql)->getRow();
	}

	/**
	 * Fila completa de tb_transaction_master_info de un documento, bloqueandola (FOR UPDATE).
	 * Llamar dentro de una transaccion abierta. Si hay varias filas devuelve la primera.
	 * @param int $companyID
	 * @param int $transactionID
	 * @param int $transactionMasterID
	 * @return object|null
	 */
	function get_rowByTransactionMasterIDForUpdate($companyID,$transactionID,$transactionMasterID){
		$db  = db_connect();
		$sql = "";
		$sql = $sql.sprintf("SELECT i.* ");
		$sql = $sql.sprintf("FROM tb_transaction_master_info i ");
		$sql = $sql.sprintf("WHERE i.companyID = %d ", $companyID);
		$sql = $sql.sprintf("AND i.transactionID = %d ", $transactionID);
		$sql = $sql.sprintf("AND i.transactionMasterID = %d ", $transactionMasterID);
		$sql = $sql.sprintf("ORDER BY i.transactionMasterInfoID ASC ");
		$sql = $sql.sprintf("LIMIT 1 ");
		$sql = $sql.sprintf("FOR UPDATE");
		return $db->query($sql)->getRow();
	}

}
?>