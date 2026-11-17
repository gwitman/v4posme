<?php
//posme:2023-02-27
namespace App\Libraries;

use App\Models\Core\Bd_Model;
use App\Models\Core\Branch_Model;
use App\Models\Core\Catalog_Item_convertion_Model;
use App\Models\Core\Catalog_Item_Model;
use App\Models\Core\Catalog_Model;
use App\Models\Core\Company_Component_flavor_Model;
use App\Models\Core\Company_Component_Model;
use App\Models\Core\Company_Data_View_Model;
use App\Models\Core\Company_Default_Data_View_Model;
use App\Models\Core\Company_Model;
use App\Models\Core\Company_Parameter_Model;
use App\Models\Core\Company_Subelement_audit_Model;
use App\Models\Core\Component_Audit_detail_Model;
use App\Models\Core\Component_Audit_Model;
use App\Models\Core\Component_Autorization_Model;

use App\Models\Core\Component_Model;
use App\Models\Core\Counter_Model;
use App\Models\Core\Currency_Model;
use App\Models\Core\Data_View_Model;
use App\Models\Core\Element_Model;
use App\Models\Core\Exchangerate_Model;
use App\Models\Core\Log_Model;
use App\Models\Core\Membership_Model;
use App\Models\Core\Menu_Element_Model;
use App\Models\Core\Parameter_Model;
use App\Models\Core\Role_Autorization_Model;
use App\Models\Core\Role_Model;
use App\Models\Core\Sub_Element_Model;
use App\Models\Core\Transaction_Concept_Model;
use App\Models\Core\Transaction_Model;
use App\Models\Core\User_Model;
use App\Models\Core\User_Permission_Model;
use App\Models\Core\Workflow_Model;
use App\Models\Core\Workflow_Stage_Model;
use App\Models\Core\Workflow_Stage_Relation_Model;



use App\Models\Accounting_Balance_Model;
use App\Models\Account_Level_Model;
use App\Models\Account_Model;
use App\Models\Account_Type_Model;
use App\Models\Biblia_Model;
use App\Models\Center_Cost_Model;
use App\Models\Company_Component_Concept_Model;
use App\Models\Company_Currency_Model;
use App\Models\Company_Log_Model;
use App\Models\Component_Cycle_Model;
use App\Models\Component_Period_Model;
use App\Models\Credit_Line_Model;
use App\Models\Customer_Consultas_Sin_Riesgo_Model;
use App\Models\Customer_Credit_Amortization_Model;
use App\Models\Customer_Credit_Document_Endity_Related_Model;
use App\Models\Customer_Credit_Document_Model;
use App\Models\Customer_Credit_Line_Model;
use App\Models\Customer_Credit_Model;
use App\Models\Customer_Model;
use App\Models\Employee_Calendar_Pay_detail_Model;
use App\Models\Employee_Calendar_Pay_Model;
use App\Models\Employee_Model;
use App\Models\Entity_Account_Model;
use App\Models\Entity_Email_Model;
use App\Models\Entity_Model;
use App\Models\Entity_Phone_Model;
use App\Models\Error_Model;
use App\Models\Fixed_Assent_Model;
use App\Models\Itemcategory_Model;
use App\Models\Itemwarehouse_Model;
use App\Models\Item_Data_Sheet_Detail_Model;
use App\Models\Item_Data_Sheet_Model;
use App\Models\Item_Model;
use App\Models\Item_Warehouse_Expired_Model;
use App\Models\Journal_Entry_Detail_Model;
use App\Models\Journal_Entry_Model;
use App\Models\Legal_Model;
use App\Models\List_Price_Model;
use App\Models\Natural_Model;
use App\Models\Notification_Model;
use App\Models\Price_Model;
use App\Models\Provideritem_Model;
use App\Models\Provider_Model;
use App\Models\Relationship_Model;
use App\Models\Remember_Model;
use App\Models\Tag_Model;
use App\Models\Transaction_Causal_Model;

use App\Models\Transaction_Master_Concept_Model;
use App\Models\Transaction_Master_Detail_Credit_Model;
use App\Models\Transaction_Master_Detail_Model;
use App\Models\Transaction_Master_Info_Model;
use App\Models\Transaction_Master_Model;

use App\Models\Transaction_Profile_Detail_Model;
use App\Models\Userwarehouse_Model;
use App\Models\User_Tag_Model;
use App\Models\Warehouse_Model;


class core_web_inventory {
   
   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI; 
	
	
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
         
   }
   //Calcular el Kardex al registrar una entrada
   function calculateKardexNewInput($companyID,$transactionID,$transactionMasterID){
			$Bd_Model = new Bd_Model();
			$queryResult = $Bd_Model->executeRender("CALL pr_inventory_calculate_kardex_new_input (".$companyID.",".$transactionID.",".$transactionMasterID."); ",null);
			return $queryResult;
   }
    //Calcular el Kardex al registrar una salida
   function calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID){
			$Bd_Model = new Bd_Model();
			$queryResult = $Bd_Model->executeRender("CALL pr_inventory_calculate_kardex_new_output (".$companyID.",".$transactionID.",".$transactionMasterID."); ",null);
			return $queryResult;
   }

	/**
	 * Crea y aplica una transaccion de OTRAS ENTRADAS (tb_transaction_master_otherinput)
	 * en la bodega indicada: estado = primer estado aplicable, kardex de entrada y conceptos.
	 * No abre transaccion de BD propia ni oculta errores.
	 * @param array  $dataSession   Sesion del usuario (user, role)
	 * @param int    $warehouseID   Bodega destino de la entrada
	 * @param string $transactionOn Fecha del documento (Y-m-d)
	 * @param string $note          Comentario del documento
	 * @param string $reference1    Referencia 1 del documento
	 * @param string $reference2    Referencia 2 del documento
	 * @param array  $listDetail    Lista de ['itemID','quantity','unitaryCost']
	 * @return array ['transactionID','transactionMasterID','transactionNumber']
	 */
	function createOtherInputApplied($dataSession,$warehouseID,$transactionOn,$note,$reference1,$reference2,$listDetail){
		return $this->createOtherTransactionApplied("tb_transaction_master_otherinput",$dataSession,$warehouseID,$transactionOn,$note,$reference1,$reference2,$listDetail);
	}

	/**
	 * Crea y aplica una transaccion de OTRAS SALIDAS (tb_transaction_master_otheroutput)
	 * desde la bodega indicada: estado = primer estado aplicable, kardex de salida y conceptos.
	 * No abre transaccion de BD propia ni oculta errores.
	 * @param array  $dataSession   Sesion del usuario (user, role)
	 * @param int    $warehouseID   Bodega origen de la salida
	 * @param string $transactionOn Fecha del documento (Y-m-d)
	 * @param string $note          Comentario del documento
	 * @param string $reference1    Referencia 1 del documento
	 * @param string $reference2    Referencia 2 del documento
	 * @param array  $listDetail    Lista de ['itemID','quantity','unitaryCost']
	 * @return array ['transactionID','transactionMasterID','transactionNumber']
	 */
	function createOtherOutputApplied($dataSession,$warehouseID,$transactionOn,$note,$reference1,$reference2,$listDetail){
		return $this->createOtherTransactionApplied("tb_transaction_master_otheroutput",$dataSession,$warehouseID,$transactionOn,$note,$reference1,$reference2,$listDetail);
	}

	/**
	 * Arma cabecera y detalle de una otra entrada / otra salida, la deja en el primer
	 * estado aplicable y ejecuta kardex + conceptos (mismo flujo que
	 * app_inventory_otherinput / app_inventory_otheroutput).
	 * @param string $componentName tb_transaction_master_otherinput | tb_transaction_master_otheroutput
	 * @return array ['transactionID','transactionMasterID','transactionNumber']
	 */
	private function createOtherTransactionApplied($componentName,$dataSession,$warehouseID,$transactionOn,$note,$reference1,$reference2,$listDetail){
		$isInput							= ($componentName == "tb_transaction_master_otherinput");
		$core_web_tools						= new core_web_tools();
		$core_web_transaction				= new core_web_transaction();
		$core_web_counter					= new core_web_counter();
		$core_web_currency					= new core_web_currency();
		$core_web_workflow					= new core_web_workflow();
		$core_web_auditoria					= new core_web_auditoria();
		$core_web_concept					= new core_web_concept();
		$Transaction_Model					= new Transaction_Model();
		$Transaction_Master_Model			= new Transaction_Master_Model();
		$Transaction_Master_Detail_Model	= new Transaction_Master_Detail_Model();
		$Item_Model							= new Item_Model();

		if(empty($listDetail))
		throw new \Exception("NO HAY PRODUCTOS PARA REGISTRAR EN '".$componentName."'");

		if(empty($warehouseID))
		throw new \Exception("NO SE INDICO LA BODEGA PARA '".$componentName."'");

		$companyID							= $dataSession["user"]->companyID;
		$branchID							= $dataSession["user"]->branchID;
		$roleID								= $dataSession["role"]->roleID;

		$objComponent						= $core_web_tools->getComponentIDBy_ComponentName($componentName);
		if(!$objComponent)
		throw new \Exception("EL COMPONENTE '".$componentName."' NO EXISTE...");

		$objComponentItem					= $core_web_tools->getComponentIDBy_ComponentName("tb_item");
		if(!$objComponentItem)
		throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

		$transactionID						= $core_web_transaction->getTransactionID($companyID,$componentName,0);
		if(!$transactionID)
		throw new \Exception("LA TRANSACCION '".$componentName."' NO EXISTE...");

		$objT								= $Transaction_Model->getByCompanyAndTransaction($companyID,$transactionID);
		$objStageApply						= $core_web_workflow->getWorkflowStageApplyFirst($componentName,"statusID",$companyID,$branchID,$roleID);
		if(empty($objStageApply))
		throw new \Exception("NO EXISTE UN ESTADO APLICABLE PARA '".$componentName."'");

		$objCurrency						= $core_web_currency->getCurrencyDefault($companyID);

		$objTM								= array();
		$objTM["companyID"]					= $companyID;
		$objTM["transactionID"]				= $transactionID;
		$objTM["branchID"]					= $branchID;
		$objTM["transactionNumber"]			= $core_web_counter->goNextNumber($companyID,$branchID,$componentName,0);
		$objTM["transactionCausalID"]		= $core_web_transaction->getDefaultCausalID($companyID,$transactionID);
		$objTM["entityID"]					= 0;
		$objTM["transactionOn"]				= $transactionOn;
		$objTM["statusIDChangeOn"]			= helper_getDateTime();
		$objTM["componentID"]				= $objComponent->componentID;
		$objTM["note"]						= $note;
		$objTM["sign"]						= $objT->signInventory;
		$objTM["currencyID"]				= $objCurrency->currencyID;
		$objTM["currencyID2"]				= $objTM["currencyID"];
		$objTM["exchangeRate"]				= 1;
		$objTM["reference1"]				= $reference1;
		$objTM["reference2"]				= $reference2;
		$objTM["reference3"]				= "";
		$objTM["reference4"]				= "";
		$objTM["statusID"]					= $objStageApply[0]->workflowStageID;
		$objTM["amount"]					= 0;
		$objTM["isApplied"]					= 0; //El procedimiento de kardex lo marca en 1
		$objTM["journalEntryID"]			= 0;
		$objTM["classID"]					= NULL;
		$objTM["areaID"]					= NULL;
		$objTM["sourceWarehouseID"]			= $isInput ? NULL : $warehouseID;
		$objTM["targetWarehouseID"]			= $isInput ? $warehouseID : NULL;
		$objTM["tax1"]						= 0;
		$objTM["tax2"]						= 0;
		$objTM["tax3"]						= 0;
		$objTM["tax4"]						= 0;
		$objTM["subAmount"]					= 0;
		$objTM["discount"]					= 0;
		$objTM["isActive"]					= 1;
		$objTM["isTemplate"]				= 0;
		$core_web_auditoria->setAuditCreated($objTM,$dataSession,\Config\Services::request());

		$transactionMasterID				= $Transaction_Master_Model->insert_app_posme($objTM);
		if(!$transactionMasterID)
		throw new \Exception("NO FUE POSIBLE REGISTRAR LA TRANSACCION '".$componentName."'");

		//Carpeta de archivos del documento
		$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;
		if(!file_exists($path_))
		mkdir($path_,0755,true);

		$amount = 0;
		foreach($listDetail as $row){
			$itemID		= (int)$row["itemID"];
			$quantity	= (float)$row["quantity"];
			$unitCost	= (float)$row["unitaryCost"];
			if($quantity <= 0)
			throw new \Exception("LA CANTIDAD DEL PRODUCTO ".$itemID." DEBE SER MAYOR QUE 0");

			$objItem	= $Item_Model->get_rowByPK($companyID,$itemID);
			if(!$objItem)
			throw new \Exception("EL PRODUCTO ".$itemID." NO EXISTE O ESTA INACTIVO");

			$objTMD									= array();
			$objTMD["companyID"]					= $companyID;
			$objTMD["transactionID"]				= $transactionID;
			$objTMD["transactionMasterID"]			= $transactionMasterID;
			$objTMD["componentID"]					= $objComponentItem->componentID;
			$objTMD["componentItemID"]				= $itemID;
			$objTMD["quantity"]						= $quantity;
			$objTMD["unitaryCost"]					= $unitCost;
			$objTMD["cost"]							= $quantity * $unitCost;
			$objTMD["unitaryAmount"]				= 0;
			$objTMD["amount"]						= 0;
			$objTMD["discount"]						= 0;
			$objTMD["unitaryPrice"]					= 0;
			$objTMD["promotionID"]					= 0;
			$objTMD["lote"]							= "";
			$objTMD["expirationDate"]				= '1900-01-01';
			$objTMD["reference1"]					= "";
			$objTMD["reference2"]					= "";
			$objTMD["reference3"]					= "";
			$objTMD["catalogStatusID"]				= 0;
			$objTMD["inventoryStatusID"]			= 0;
			$objTMD["isActive"]						= 1;
			$objTMD["quantityStock"]				= 0;
			$objTMD["quantiryStockInTraffic"]		= 0;
			$objTMD["quantityStockUnaswared"]		= 0;
			$objTMD["remaingStock"]					= 0;
			$objTMD["itemNameLog"]					= $objItem->name;
			$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
			$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
			$Transaction_Master_Detail_Model->insert_app_posme($objTMD);

			$amount += $objTMD["cost"];
		}

		$objTMUpdate				= array();
		$objTMUpdate["amount"]		= $amount;
		$objTMUpdate["subAmount"]	= $amount;
		$Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMUpdate);

		//Kardex y conceptos
		if($isInput){
			$this->calculateKardexNewInput($companyID,$transactionID,$transactionMasterID);
			$core_web_concept->otherinput($companyID,$transactionID,$transactionMasterID);
		}
		else{
			$this->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);
			$core_web_concept->otheroutput($companyID,$transactionID,$transactionMasterID);
		}

		return array(
			"transactionID"			=> $transactionID,
			"transactionMasterID"	=> $transactionMasterID,
			"transactionNumber"		=> $objTM["transactionNumber"]
		);
	}
}
?>