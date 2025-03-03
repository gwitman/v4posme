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
use App\Models\Core\Workflow_Stage_Affect_Model;



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


class core_web_workflow {
   
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
   
   //Obtener todos los estados
   function getWorkflowAllStage($table,$field,$companyID,$branchID,$roleID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();		
		$Workflow_Model = new Workflow_Model();
		$Workflow_Stage_Model = new Workflow_Stage_Model();
		$Workflow_Stage_Relation_Model = new Workflow_Stage_Relation_Model();
		$Role_Model = new Role_Model();
		$Role_Autorization_Model = new Role_Autorization_Model();
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new \Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		
		//obtener la lista de workflowStage
		$objWorkflowStage 		= $Workflow_Stage_Model->get_rowByWorkflowIDAndFlavorID($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID);
				
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole = $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		} 
		else if (!$objWorkflowStageRole){
			 //el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
				foreach($objWorkflowStage as &$i){
					$exists = false;
					foreach($objWorkflowStageRole as $ii){									
						if($ii->workflowStageID == $i->workflowStageID)
						$exists = true;
					}		
					if(!$exists)
					$i = null;
				}				
				return $objWorkflowStage; 
		}
		
		
   }
   //Obtener el estado inicial
   function getWorkflowInitStage($table,$field,$companyID,$branchID,$roleID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();		
		$Workflow_Model = new Workflow_Model();
		$Workflow_Stage_Model = new Workflow_Stage_Model();
		$Workflow_Stage_Relation_Model = new Workflow_Stage_Relation_Model();
		$Role_Model = new Role_Model();
		$Role_Autorization_Model = new Role_Autorization_Model();
		
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new \Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflowStage
		$objWorkflowStage 		= $Workflow_Stage_Model->get_rowByWorkflowIDAndFlavorID_Init($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID);
		
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){		
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		}
		else if(!$objWorkflowStageRole){			
			//el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){			
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
			$exists = false;
			foreach($objWorkflowStageRole as $ii){									
				if($ii->workflowStageID == $objWorkflowStage[0]->workflowStageID)
				$exists = true;
			}		
			
			if(!$exists)
			return null;
			
			return $objWorkflowStage; 
		}
	
		
   }
   
   function getWorkflowStageTargetBySource
   (
		$transactionID,
		$companyID,
		$transactionCausalID,
		$componentNameSource,
		$workflowSourceStageID,
		$componentNameTarget 		
   )
   {
		//Obtener la compañia
		$Company_Model 	= new Company_Model();
		$objCompany 	= $Company_Model->get_rowByPK($companyID);
		
		//Obtener el componente Origen
		$Component_Model 		= new Component_Model();
		$objComponentSource 	= $Component_Model->get_rowByName($componentNameSource);
		if(!$objComponentSource)
		throw new \Exception("NO EXISTE EL COMPONENTE '".$componentNameSource."' DENTROS DE LOS REGISTROS DE 'Component' ");
	
		//Obtener el componente Destino
		$objComponentTarget 	= $Component_Model->get_rowByName($componentNameTarget);
		if(!$objComponentTarget)
		throw new \Exception("NO EXISTE EL COMPONENTE '".$componentNameTarget."' DENTROS DE LOS REGISTROS DE 'Component' ");
	
	
		//Obgener la transaccion
		$Transaction_Model 	= new Transaction_Model();
		$objTransaction 	= $Transaction_Model->getByCompanyAndTransaction($companyID,$transactionID);
		
		//Obtener el Causal
		$Transaction_Causal_Model 	= new Transaction_Causal_Model();
		$objTransactionCausal		= $Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$transactionCausalID);
		
		//Obtener el nombre del estado
		$Workflow_Stage_Model		= new Workflow_Stage_Model();
		$objWorkflowStage			= $Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($workflowSourceStageID);
		
		//Obtener el estado destino con sabor
		$Workflow_Stage_Affect_Model 	= new Workflow_Stage_Affect_Model();
		$objWorkflowTarget 				= $Workflow_Stage_Affect_Model->get_rowByTransactionCausalID_And_WorkflowStageID
		(
				$objTransaction->name,
				$objCompany->flavorID,
				$objTransactionCausal->name,
				$componentNameSource,
				$objWorkflowStage[0]->name,
				$componentNameTarget		
		);
		
		if($objWorkflowTarget)
			return $objWorkflowTarget;
		
		//Obtener el estado destino sin sabor
		$objWorkflowTarget 				= $Workflow_Stage_Affect_Model->get_rowByTransactionCausalID_And_WorkflowStageID
		(
				$objTransaction->name,
				0,
				$objTransactionCausal->name,
				$componentNameSource,
				$objWorkflowStage[0]->name,
				$componentNameTarget	
		);
		
		
		if(!$objWorkflowTarget)
			return $objWorkflowTarget;
		
		
		$objWorkflowTarget = $Workflow_Stage_Model->get_rowByWorkflowName_And_WorkflowStageName(
			$objWorkflowTarget[0]->flavorID,
			$objWorkflowTarget[0]->workflowTargetID,
			$objWorkflowTarget[0]->workflowTargetStageID
		);
		return $objWorkflowTarget;
		
   }
   
   //Obtener el primer estado de aplicacion
   function getWorkflowStageApplyFirst($table,$field,$companyID,$branchID,$roleID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();		
		$Workflow_Model = new Workflow_Model();
		$Workflow_Stage_Model = new Workflow_Stage_Model();
		$Workflow_Stage_Relation_Model = new Workflow_Stage_Relation_Model();
		$Role_Model = new Role_Model();
		$Role_Autorization_Model = new Role_Autorization_Model();
		
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new \Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflowStage
		$objWorkflowStage 		= $Workflow_Stage_Model->get_rowByWorkflowIDAndFlavorID_ApplyFirst($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID);
		
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){		
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		}
		else if(!$objWorkflowStageRole){			
			//el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){			
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
			$exists = false;
			foreach($objWorkflowStageRole as $ii){									
				if($ii->workflowStageID == $objWorkflowStage[0]->workflowStageID)
				$exists = true;
			}		
			
			if(!$exists)
			return null;
			
			return $objWorkflowStage; 
		}
	
   }
   
   //Obtener un Estado
   function getWorkflowStage($table,$field,$stageID,$companyID,$branchID,$roleID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();		
		$Workflow_Model = new Workflow_Model();
		$Workflow_Stage_Model = new Workflow_Stage_Model();
		$Workflow_Stage_Relation_Model = new Workflow_Stage_Relation_Model();
		$Role_Model = new Role_Model();
		$Role_Autorization_Model = new Role_Autorization_Model();
		
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new \Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflow
		$objWorkflowStage = $Workflow_Stage_Model->get_rowByWorkflowStageID($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID,$stageID);
   		
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		} 
		else if (!$objWorkflowStageRole){
			 //el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
				foreach($objWorkflowStage as &$i){
					$exists = false;
					foreach($objWorkflowStageRole as $ii){									
						if($ii->workflowStageID == $i->workflowStageID)
						$exists = true;
					}		
					if(!$exists)
					$i = null;
				}				
				return $objWorkflowStage; 
		}
		
   }
  
  //Obtener todos los estados destinos apartir de un estado origen
   function getWorkflowStageByStageInit($table,$field,$startStageID,$companyID,$branchID,$roleID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();		
		$Workflow_Model = new Workflow_Model();
		$Workflow_Stage_Model = new Workflow_Stage_Model();
		$Workflow_Stage_Relation_Model = new Workflow_Stage_Relation_Model();
		$Role_Model = new Role_Model();
		$Role_Autorization_Model = new Role_Autorization_Model();
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new \Exception("NO EXISTE EL WORKFLOW ");
				
		
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);		
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflowStage
		$objWorkflowStage = $Workflow_Stage_Relation_Model->get_rowBySourceWorkflowStageID($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID,$startStageID);
		
   		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		if($objRole->isAdmin){
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		} 
		else if (!$objWorkflowStageRole){
			 //el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
			foreach($objWorkflowStage as &$i){
				$exists = false;
				foreach($objWorkflowStageRole as $ii){									
					if($ii->workflowStageID == $i->workflowStageID)
					$exists = true;
				}		
				if(!$exists)
				$i = null;
			}				
			return $objWorkflowStage; 
		}
		
   }  
   
   //Validar el Estado
   function validateWorkflowStage($table,$field,$stageID,$cmd,$companyID,$branchID,$roleID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();		
		$Workflow_Model = new Workflow_Model();
		$Workflow_Stage_Model = new Workflow_Stage_Model();
		$Workflow_Stage_Relation_Model = new Workflow_Stage_Relation_Model();
		$Role_Model = new Role_Model();
		$Role_Autorization_Model = new Role_Autorization_Model();
		
		//obtener el workflow
		$objWorkflowStage	= $this->getWorkflowStage($table,$field,$stageID,$companyID,$branchID,$roleID);
		if(!$objWorkflowStage)
		throw new \Exception("NO EXISTE EL WORKFLOW STAGE TABLE: $table , FIELD:$field , COMPANY: $companyID, WORKFLOWSTAGEID: $stageID ");
		
		
			
		if($cmd == COMMAND_VINCULATE){
			return $objWorkflowStage[0]->vinculable;
		}
		else if($cmd == COMMAND_EDITABLE){
			return $objWorkflowStage[0]->editableParcial;
		}
		else if($cmd == COMMAND_EDITABLE_TOTAL){
			return $objWorkflowStage[0]->editableTotal;
		}
		else if($cmd == COMMAND_ELIMINABLE){
			return $objWorkflowStage[0]->eliminable;
		}
		else if($cmd == COMMAND_APLICABLE){
			return $objWorkflowStage[0]->aplicable;
		}
		else {
			return 0;
		}
		
   }
}
?>