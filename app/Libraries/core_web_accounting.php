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


class core_web_accounting {
   
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
   //Ciclos
   function cycleIsCloseByID($companyID,$cycleID){
		$Component_Cycle_Model = new Component_Cycle_Model();
		$Parameter_Model = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		
		$objParameter			= $Parameter_Model->get_rowByName("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);				
		$objCycle				= $Component_Cycle_Model->get_rowByCycleID($cycleID);
		
		if(!$objCycle)
		throw new \Exception("NO EXISTE EL CICLO CONTABLE");
	
		if($objCycle->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function cycleIsEmptyByID($companyID,$cycleID){
		
		$Component_Cycle_Model = new Component_Cycle_Model();
		$objCycle		= $Component_Cycle_Model->get_rowByCycleID($cycleID);
		$countJournal	= $Component_Cycle_Model->countJournalInCycle($cycleID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   function cycleIsCloseByDate($companyID,$dateOn){
		
		$Component_Cycle_Model = new Component_Cycle_Model();
		$Parameter_Model = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		$objParameter			= $Parameter_Model->get_rowByName("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);		
		$objCycle				= $Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		
		if(!$objCycle)
		throw new \Exception("NO EXISTE EL CICLO CONTABLE");
	
		if($objCycle->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function cycleIsEmptyByDate($companyID,$dateOn){	
		
		$Component_Cycle_Model = new Component_Cycle_Model();
		$objCycle		= $Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		$countJournal	= $Component_Cycle_Model->countJournalInCycle($objCycle->cycleID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   //Periodos
   function periodIsCloseByID($companyID,$periodID){
		$Component_Cycle_Model = new Component_Cycle_Model();
		$Component_Period_Model = new Component_Period_Model();
		$Parameter_Model = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		
		$objParameter			= $Parameter_Model->get_rowByName("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);				
		$objPeriod				= $Component_Period_Model->get_rowByPK($periodID);
		
		if(!$objPeriod)
		throw new \Exception("NO EXISTE EL PERIODO CONTABLE");
	
		if($objPeriod->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function periodIsEmptyByID($companyID,$periodID){
		
		$Component_Cycle_Model = new Component_Cycle_Model();
		$Component_Period_Model = new Component_Period_Model();
		$objPeriod		= $Component_Period_Model->get_rowByPK($periodID);
		$countJournal	= $Component_Period_Model->countJournalInPeriod($periodID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   function periodIsCloseByDate($companyID,$dateOn){
		
		$Component_Cycle_Model = new Component_Cycle_Model();
		$Component_Period_Model = new Component_Period_Model();
		$Parameter_Model = new Parameter_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		
		$objParameter			= $Parameter_Model->get_rowByName("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);		
		$objPeriod				= $Component_Period_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		
		if(!$objPeriod)
		throw new \Exception("NO EXISTE EL PERIODO CONTABLE");
	
		if($objPeriod->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function periodIsEmptyByDate($companyID,$dateOn){	
		
		$Component_Cycle_Model = new Component_Cycle_Model();
		$Component_Period_Model = new Component_Period_Model();
		$objPeriod		= $Component_Period_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		$countJournal	= $Component_Period_Model->countJournalInPeriod($objPeriod->componentPeriodID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   //Procesos
   function mayorizateAccount ($companyID,$branchID,$loginID,$accountID,$componentPeriodID,$componentCycleID,$balance_,$debit_,$credit_){
		$Account_Model = new Account_Model();
		$Accounting_Balance_Model = new Accounting_Balance_Model();
		
		$parentAccountID_ 	= 0;
		$objAccount 		= $Account_Model->get_rowByPK($companyID,$accountID);
		
		if ($objAccount->parentAccountID !== null)
		{
			$this->mayorizateAccount ($companyID,$branchID,$loginID,$objAccount->parentAccountID,$componentPeriodID,$componentCycleID,$balance_,$debit_,$credit_);
		}
		
		$Accounting_Balance_Model->updateBalance($companyID,$componentPeriodID,$componentCycleID,$accountID,$balance_,$debit_,$credit_);
		
   }
   function mayorizateCycle($companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID){
	    
		$Journal_Entry_Model = new Journal_Entry_Model();
		$Journal_Entry_Detail_Model = new Journal_Entry_Detail_Model();
		$Accounting_Balance_Model = new Accounting_Balance_Model();
		$Component_Cycle_Model = new Component_Cycle_Model();
		$core_web_parameter = new core_web_parameter();
		
		
		$journalTypeClosed 			= 0;
		$minAccountID 				= 0;
		$maxAccountID 				= 0;
		$debit_ 					= 0;
		$credit_ 					= 0;
		$balance_ 					= 0;
		$componentAccountID 		= 4;
		$workflowStageCycleClosed_ 	= 0;
		
		//Obtener el ciclo
		$objCycle					= $Component_Cycle_Model->get_rowByPK($componentPeriodID,$componentCycleID);
		
		//Obtener el estado cerrado de los ciclos
		$workflowStageCycleClosed_	= $core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$companyID)->value;		
		
		if($objCycle->statusID ==  $workflowStageCycleClosed_)
			return 1;				
		
		//Obtener el comprobante de Cierre	
		$journalTypeClosed 			= $core_web_parameter->getParameter("ACCOUNTING_JOURNALTYPE_CLOSED",$companyID)->value;	
		
		//Limpiar la tabla Temporal
		$Accounting_Balance_Model->deleteJournalEntryDetailSummary($companyID,$branchID,$loginID);
		
		//Obtener los comprobantes resumidos
		$Accounting_Balance_Model->setJournalSummary($companyID,$branchID,$loginID,$componentCycleID,$journalTypeClosed);
		
		//Ingresar las cuentas en la tabla balance
		$Accounting_Balance_Model->setAccountBalance($companyID,$branchID,$loginID,$componentCycleID,$componentPeriodID,$componentAccountID);
		
		//Mayorizar Cuentas
		$Accounting_Balance_Model->clearCycle($companyID,$componentPeriodID,$componentCycleID);
		$minAccountID = $Accounting_Balance_Model->getMinAccount($companyID,$branchID,$loginID);
		$maxAccountID = $Accounting_Balance_Model->getMaxAccount($companyID,$branchID,$loginID);
		
		while (($minAccountID <= $maxAccountID) and ($minAccountID !== null)) {
			$objAccountBalance	= $Accounting_Balance_Model->getInfoAccount($companyID,$branchID,$loginID,$minAccountID);
			$debit_ 			= $objAccountBalance->debit;
			$credit_ 			= $objAccountBalance->credit;
			
			$this->mayorizateAccount(
				$companyID,
				$branchID,
				$loginID,
				$minAccountID,
				$componentPeriodID,
				$componentCycleID,
				$balance_,
				$debit_,
				$credit_
			);
			
			$minAccountID 		= $Accounting_Balance_Model->getMinAccountBy($companyID,$branchID,$loginID,$minAccountID);
		}
		
		
		
		//Limpiar la tabla Temporal
		$Accounting_Balance_Model->deleteJournalEntryDetailSummary($companyID,$branchID,$loginID);
		
		return 1;
   }
}
?>