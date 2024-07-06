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




class core_web_parameter {
   
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
   function getParameter($parameterName,$companyID){
	    $Parameter_Model 		 = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		
		//Obtener el Parametro
		$objParameter = $Parameter_Model->get_rowByName($parameterName);		
		if(!$objParameter)
		throw new \Exception("NO EXISTE EL PARAMETRO ".$parameterName);
		
		//Obtener el CompanyParameter
		$objCompanyParameter =  $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);
		if(!$objCompanyParameter)
		throw new \Exception("NO EXISTE EL PARAMETRO ".$parameterName." PARA LA COMPANY ".$companyID);
		
		return $objCompanyParameter;		
		
   }
   function getParameterValue($parameterName,$companyID )
   {
	    $Parameter_Model 		 = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		
		//Obtener el Parametro
		$objParameter = $Parameter_Model->get_rowByName($parameterName);		
		if(!$objParameter)
		throw new \Exception("NO EXISTE EL PARAMETRO ".$parameterName);
		
		//Obtener el CompanyParameter
		$objCompanyParameter =  $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);
		if(!$objCompanyParameter)
		throw new \Exception("NO EXISTE EL PARAMETRO ".$parameterName." PARA LA COMPANY ".$companyID);
		
		return $objCompanyParameter->value;		
   }
   function getParameterAllToJavaScript($companyID)
   {
	    $Parameter_Model 		 = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		$javascriptResult		 = "";
		
		//Obtener el Parametro
		$objParameterList = $Company_Parameter_Model->get_rowByCompanyID($companyID);	
		
		if($objParameterList)
		{
			foreach($objParameterList as $objParameter)
			{
				$javascriptResult	 = $javascriptResult."var objCompanyParameter_Key_".$objParameter->name." = '".$objParameter->value."';";
			}
		}
		
		$javascriptResult	 = $javascriptResult."\r\n";
		return $javascriptResult;
		
   }
   function getParameterAll($companyID)
   {
	    $Parameter_Model 		 = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		$data					 = array();
		
		//Obtener el Parametro
		$objParameterList = $Parameter_Model->get_all();	
		
		if($objParameterList)
		{
			foreach($objParameterList as $objParameter)
			{
				
				$objCompanyParameter 		=  $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);
				$data[$objParameter->name] 	=  $objCompanyParameter->value;				
			}
		}
		return $data;
		
   }
   function getParameterId($parameterName,$companyID )
   {
	   $Parameter_Model 		 = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		
		//Obtener el Parametro
		$objParameter = $Parameter_Model->get_rowByName($parameterName);		
		if(!$objParameter)
		throw new \Exception("NO EXISTE EL PARAMETRO ".$parameterName);
		
		//Obtener el CompanyParameter
		$objCompanyParameter =  $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);
		if(!$objCompanyParameter)
		throw new \Exception("NO EXISTE EL PARAMETRO ".$parameterName." PARA LA COMPANY ".$companyID);
		
		return $objCompanyParameter->parameterID;		
   }
   function getParameterFiltered($objListCompanyParameter,$parameterName)
   {
	   
		$result = current(array_filter($objListCompanyParameter, function($obj) use ($parameterName) {
			return $obj->name === $parameterName;
		}));
		
		return $result;

   }
   
   
}
?>