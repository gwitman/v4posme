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


class core_web_transaction_master_detail {
   
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
   
   function getCostCustomer($companyID,$itemID,$unitaryCost,$unitaryPrice)
   {
	   	$Company_Model				= new Company_Model();		
		$Item_Model					= new Item_Model();
		
		$objCompany					= $Company_Model->get_rowByPK($companyID);		
		$objItem					= $Item_Model->get_rowByPK($companyID,$itemID);		
		$unitaryCost				= $unitaryCost;
		
		
		//Obtener el costo de global pro para ciertos productos.
		if
		(
			$objCompany->type == "globalpro" &&
			in_array($objItem->barCode , array('ITT00000217', 'ITT00000218', 'ITT00000219', 'ITT00000220','ITT00000221'  ) ) 
		)
		{
			$unitaryCost = ($unitaryPrice) - ($unitaryPrice * 0.35);
		}
		
		
		return $unitaryCost;
			
   }
   function getPorcentageComision($companyID,$listPriceID,$itemID,$price)
   {
	    $Price_Model				= new Price_Model();
		$Company_Model				= new Company_Model();
		
		$objCompany					= $Company_Model->get_rowByPK($companyID);		
		$objPriceToComision			= $Price_Model->get_rowByItemIDAndAmountAndComission($companyID,$listPriceID,$itemID,$price ); 
		$objPriceToComisionZero		= $Price_Model->get_rowByItemIDAndAmountAndComission($companyID,$listPriceID,$itemID,0 ); 
		$comisionPorcentage			= 0;
		
		//Obtener el porcentaje de comision comportamiento nomral
		if($objPriceToComision)
		{
			$comisionPorcentage = $objPriceToComision->percentageCommision;
		}
		
		//Obtener el porcentaje de comision de global pro, cuando no tiene comision para un precio en especifico
		if(
			$objCompany->type == "globalpro" &&
			$objPriceToComisionZero && 
			$comisionPorcentage == 0 
		)
		{
			$comisionPorcentage = $objPriceToComisionZero->percentageCommision;			
		}
			
		return $comisionPorcentage;
		
    }
  
}
?>