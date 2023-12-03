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


class core_web_transaction {
   
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
   function getCountTransactionBillingAnuladas($companyID){
	    $core_web_tools = new Core_Web_Tools();
		$core_web_parameter = new core_web_parameter();
		$Transaction_Model = new Transaction_Model();		
		
		$invoiceAnuladasStatus 	= $core_web_parameter->getParameter("INVOICE_BILLING_ANULADAS",$companyID);		
		$objComponent			= $core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
		if(!$objComponent)
		throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");		
		
		$transactionID 	= $this->getTransactionID($companyID,"tb_transaction_master_billing",0);
		if(!$transactionID)
		throw new \Exception("LA TRANSACCION  'tb_transaction_master_billing' NO EXISTE...");
		
		$result = $Transaction_Model->getCounterTransactionMaster($companyID,$transactionID,$invoiceAnuladasStatus->value);
		return $result;
		
   }
   function getCountTransactionBillingCancel($companyID){
	    $core_web_parameter = new Core_Web_Parameter();
		$core_web_tools = new Core_Web_Tools();		
		$Transaction_Model = new Transaction_Model();		
		
		$invoiceCancelStatus 	= $core_web_parameter->getParameter("INVOICE_BILLING_CANCEL",$companyID);
		$objComponent			= $core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
		if(!$objComponent)
		throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
		$transactionID 	= $this->getTransactionID($companyID,"tb_transaction_master_billing",0);
		if(!$transactionID)
		throw new \Exception("LA TRANSACCION  'tb_transaction_master_billing' NO EXISTE...");
		
		$result = $Transaction_Model->getCounterTransactionMaster($companyID,$transactionID,$invoiceCancelStatus->value);
		return $result;
   }
   function getDefaultCausalID($companyID,$transactionID){
		$Transaction_Causal_Model = new Transaction_Causal_Model();	
		
		$objCausal = $Transaction_Causal_Model->getCausalDefaultID($companyID,$transactionID);
		if(!$objCausal)
		throw new \Exception("NO HAY UN CAUSAL PORDEFECTO PARA LA TRANSACCION");		
		return $objCausal->transactionCausalID;
   }
   function createInverseDocumentByTransaccion($companyIDOriginal,$transactionIDOriginal,$transactionMasterIDOriginal,$transactionIDRevert,$transactionMasterIDRevert){
			$Bd_Model = new Bd_Model();
			$queryResult = $Bd_Model->executeRender(
				"CALL pr_transaction_revert (".$companyIDOriginal.",".$transactionIDOriginal.",".$transactionMasterIDOriginal.",".$transactionIDRevert.",".$transactionMasterIDRevert."); ",
				null
			);
			return $queryResult;
   }
   function getTransactionID($companyID,$componentName,$componentItemID) {
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();
		$Component_Model = new Component_Model();
		
		//obtener el Componente
		$objComponent = $Component_Model->get_rowByName($componentName);
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE '$componentName' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$componentItemID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//retornar transactionID
		return $objCompanyComponentFlavor->flavorID;
   }
   function getTransaction($companyID,$name){
		$Transaction_Model = new Transaction_Model();
		return $Transaction_Model->get_rowByPK($companyID,$name);
		
   }
   function getConcept($companyID,$transactionName,$conceptName){
   	    $Transaction_Model = new Transaction_Model();
   	    $Transaction_Concept_Model = new Transaction_Concept_Model();
   	    
   	    $objT =  $Transaction_Model->get_rowByPK($companyID,$transactionName);
   	    if(!$objT)
   	    throw new \Exception("NO EXISTE LA TRANSACCION ".$transactionName);
   	    
   	    return $Transaction_Concept_Model->get_rowByPK($companyID,$objT->transactionID,$conceptName);
   	    
   	    
   } 
   
}
?>