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


class core_web_auditoria {
   
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
   
   function setAuditCreated(&$obj,$dataUser,$request){			
   			$obj["createdOn"]			= date("Y-m-d H:i:s");	
			$obj["createdBy"]			= $dataUser["user"]->userID ;
			$obj["createdIn"]			= $request->getIPAddress();
			$obj["createdAt"]			= $dataUser["user"]->branchID;
   }
   function setAuditCreatedAdmin(&$obj,$request){			
   			$obj["createdOn"]			= date("Y-m-d H:i:s");	
			$obj["createdBy"]			= APP_USERADMIN;
			$obj["createdIn"]			= $request->getIPAddress();
			$obj["createdAt"]			= APP_BRANCH;
   }
   function getAuditDetail($companyID,$id,$tableName){
		$Element_Model = new Element_Model();
		$Component_Audit_Detail_Model = new Component_Audit_Detail_Model();
		
		//Obtener Elemento
		$objElement = $Element_Model->get_rowByName($tableName,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$tableName."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		$result = $Component_Audit_Detail_Model->getAuditDetail($companyID,$id,$objElement->elementID);
		return $result;
   }
   function setAudit($tableName,$old,$new,$session,$request){
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Model = new Company_Model();
		$Company_SubElement_Audit_Model = new Company_SubElement_Audit_Model();
		$Component_Audit_Model = new Component_Audit_Model();
		$Component_Audit_Detail_Model = new Component_Audit_Detail_Model();
		
		//Obtener Elemento
		$objElement = $Element_Model->get_rowByName($tableName,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$tableName."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//Obtener subElementos Auditables
		$listSubElementAuditables = $Company_SubElement_Audit_Model->listElementAudit($session["user"]->companyID,$objElement->elementID);
		if(!$listSubElementAuditables)
		return;
		
		if(gettype($old) != gettype($new))
		throw new \Exception("LOS OBJET. EN LA AUDITORIA NO SON DE IGUAL TIPO");
		
		$elementSalvar 		 = array();
		$i 					 = 0;
		$columnAutoIncrement = $objElement->columnAutoIncrement;
		if(!$columnAutoIncrement)
		throw new \Exception("LA TABLA NO TIENE UNA COLUMNA AUTO IDENTIFICADORA");
		
		//Recorrer los elementos auditables
		foreach ($listSubElementAuditables as $elementAuditable){
				$fielName 		= $elementAuditable->name;
				$fielID 		= $elementAuditable->subElementID;
				$fieldValueOld 	= "";
				$fieldValueNew 	= "";
				$auditar  		= false; 			
				if(is_array($old)){
					if($old[$fielName] === $new[$fielName])
					continue;
					$auditar 		= true;
					$fieldValueOld 	= $old[$fielName];
					$fieldValueNew 	= $new[$fielName];
				}
				else if(is_object($old)){
					if($old->$fielName === $new->$fielName)
					continue;					
					$auditar 		= true;
					$fieldValueOld 	= $old->$fielName;
					$fieldValueNew 	= $new->$fielName;
				}
				if($auditar){
					$elementSalvar[$i] = array("subelementid" => $fielID,"oldvalue" => $fieldValueOld,"newvalue" => $fieldValueNew);
					$i++;			
				}
		}
		
		if(!$elementSalvar)
		return;
		
		
		//Guardar el Maestro de la Auditoria
		$data["companyID"] 		= $session["user"]->companyID;
		$data["branchID"] 		= $session["user"]->branchID;
		$data["elementID"] 		= $objElement->elementID;
		$data["elementItemID"] 	= is_array($old) ? $old[$columnAutoIncrement] : $old->$columnAutoIncrement;
		$data["modifiedOn"] 	= date("Y-m-d H-i-s");
		$data["modifiedAt"] 	= $session["user"]->branchID;
		$data["modifiedIn"] 	= $request->getIPAddress();
		$data["modifiedBy"] 	= $session["user"]->userID;
		$componentAuditID 		= $Component_Audit_Model->insert_app_posme($data);
		
		//Guardar el Detalle de la Auditoria
		foreach($elementSalvar as $elem_){
			$data_["companyID"]				= $data["companyID"];
			$data_["branchID"] 				= $data["branchID"];
			$data_["componentAuditID"] 		= $componentAuditID;
			$data_["fieldID"] 				= $elem_["subelementid"];
			$data_["oldValue"]				= $elem_["oldvalue"];
			$data_["newValue"]				= $elem_["newvalue"];
			$Component_Audit_Detail_Model->insert_app_posme($data_);	
		}
		
   }
}
?>