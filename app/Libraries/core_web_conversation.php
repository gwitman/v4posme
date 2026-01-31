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
use App\Models\Customer_Conversation_Model;
use App\Models\Company_Component_Relation_Model;


class core_web_conversation{
   
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
   function getAllEmployer(
	$companyID,
	$companyType,
	$phoneNumber,
	$message,
	$conversationIsNew
   )
   {
	    if($companyType == "luciaralstate")
		{
			//Si el mensaje contiene un key
			//Obtener el colaborador asociado a ese key
			//Regreasr el colaborador asociado a ese key			
			$objEmployee_Model 	= new Employee_Model();
			$objListEmployer 	= $objEmployee_Model->get_rowByCompanyID($companyID);
			$arrayListEmployer  = array();
			
			//Si la conversacion es antiguoa no enlazar colaboradores
			if($conversationIsNew == false)
				return $arrayListEmployer;
			
			$arrayListEmployer = [];
			foreach ($objListEmployer as $objEmployer) 
			{
				if (str_contains($string, $objEmployer->clave)) 
				{
					$arrayListEmployer[] = $objEmployer->entityID;
				}
			}
			
			return $arrayListEmployer;
			
		}
		else 
		{
			$core_web_permission 	= new core_web_permission();
			$objEmployee_Model 		= new Employee_Model();
			$objListEmployer 		= $objEmployee_Model->get_rowByCompanyID($companyID);
			$arrayListEmployer  	= array();
			
			//Si la conversacion es antiguoa no enlazar colaboradores
			if($conversationIsNew == false)
				return $arrayListEmployer;
			
			if($objListEmployer)
			{
				foreach($objListEmployer as $employer)
				{
					$validatePermiso 	 = $objEmployee_Model->get_validatePermissionBy_EmployerID_PuedeAtenderWhatsappInCRM($employer->entityID);
					if($validatePermiso)
					{
						$arrayListEmployer[] = $employer->entityID;
					}
					
				}
			}
			return $arrayListEmployer;
		}	
   }

	function createCustomer($dataSession,$customerPhoneNumber,$customerFirstName,$request)
	{
		try
		{
			$core_web_tools				= new core_web_tools();
			$core_web_currency			= new core_web_currency();
			$core_web_auditoria			= new core_web_auditoria();
			$core_web_workflow			= new core_web_workflow();
			$Customer_Model				= new Customer_Model();
			$Entity_Account_Model		= new Entity_Account_Model();
			$Customer_Credit_Model		= new Customer_Credit_Model();
			$Customer_Credit_Line_Model	= new Customer_Credit_Line_Model();
			$User_Model					= new User_Model();
			$Relationship_Model			= new Relationship_Model();
			
			//Ingresar al cliente
			$objComponentCustomer		= $core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			$objDataSet								= null;
			$companyID 								= $dataSession["user"]->companyID;
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;
			$objEntity["branchID"]					= $dataSession["user"]->branchID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$core_web_auditoria->setAuditCreated($objEntity,$dataSession,$request);
			
			date_default_timezone_set(APP_TIMEZONE);
			$objCurrencyDolares						= $core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			$db=db_connect();
			$db->transStart();		
			
			$Entity_Model						= new Entity_Model();
			$Company_Parameter_Model			= new Company_Parameter_Model();
			$entityID 							= $Entity_Model->insert_app_posme($objEntity);
			$objDataSet["entityID"] 			= $entityID;
			$objDataSet["customerCreditLineID"]	= 0;
			$objListComanyParameter			 	= $Company_Parameter_Model->get_rowByCompanyID($companyID);

			$core_web_parameter			= new core_web_parameter();
			$Natural_Model				= new Natural_Model();
			$Legal_Model				= new Legal_Model();
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= $customerFirstName;
			$objNatural["lastName"]		= $customerFirstName;
			$objNatural["address"]		= "";
			$objNatural["statusID"]		= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_STATUS_CIVIL_ID_DEFAULT")->value;
			$objNatural["profesionID"]	= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PROFESION_ID_DEFAULT")->value;
			$result 					= $Natural_Model->insert_app_posme($objNatural);

			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= $customerFirstName;
			$objLegal["legalName"]		= $customerFirstName;
			$objLegal["address"]		= "";
			$result 					= $Legal_Model->insert_app_posme($objLegal);
			
			$paisDefault 				= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_PAIS_DEFAULT")->value;
			$departamentoDefault 		= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DEPARTAMENTO_DEFAULT")->value;
			$municipioDefault 			= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_MUNICIPIO_DEFAULT")->value;
			$plazoDefault 				= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PLAZO_DEFAULT")->value;
			$typeAmortizationDefault 	= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_TYPE_AMORTIZATION")->value;
			$frecuencyDefault 			= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_FRECUENCIA_PAY_DEFAULT")->value;
			$creditLineDefault 			= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_CREDIT_LINE_DEFAULT")->value;
			$validarCedula 				= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_VALIDAR_CEDULA_REPETIDA")->value;
			$interesDefault				= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_INTERES_DEFAULT")->value;
			$dayExcludedDefault 		= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DAY_EXCLUDED_IN_CREDIT")->value;

			$paisID 					= $paisDefault;
			$departamentoId				= $departamentoDefault;
			$municipioId				= $municipioDefault;
			$core_web_counter			= new core_web_counter();
			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objDataSet["customerNumber"] 		= $objCustomer["customerNumber"];
			$objCustomer["identificationType"]	= $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_IDENTIFICATION_TYPE_DEFAULT")->value;
			$objCustomer["identification"]		= $customerPhoneNumber;

			//validar que se permita la omision de la cedula
			if(strcmp($validarCedula,"true") == 0)
			{
				//Validar que ya existe el cliente
				$Customer_Model					= new Customer_Model();
				$objCustomerOld					= $Customer_Model->get_rowByIdentification($companyID,$objCustomer["identification"]);
				if($objCustomerOld)
				{
					throw new \Exception("Error identificacion del cliente ya existe.");
				}
			}
			
			$objCustomer["countryID"]			= $paisID;
			$objCustomer["stateID"]				= $departamentoId;
			$objCustomer["cityID"]				= $municipioId;
			$objCustomer["location"]			= "";
			$objCustomer["address"]				= "";
			$objCustomer["currencyID"]			= $core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CURRENCY_ID_DEFAULT")->value;
			$objCustomer["clasificationID"]		= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CLASIFICATION_ID_DEFAULT")->value;
			$objCustomer["categoryID"]			= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CATEGORY_ID_DEFAULT")->value;
			$objCustomer["subCategoryID"]		= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_SUBCATEGORY_ID_DEFAULT")->value;
			$objCustomer["customerTypeID"]		= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_ID_DEFAULT")->value;
			$objCustomer["birthDate"]			= date("Y-m-d");
			$objCustomer["dateContract"]		= date("Y-m-d");
			$objCustomer["statusID"]			= $core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;; //lo mismo statusid de producto solo cambiar nombre de la tabla
			$objCustomer["typePay"]				= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT")->value;
			$objCustomer["payConditionID"]		= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT")->value;
			$objCustomer["sexoID"]				= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_SEX_ID_DEFAULT")->value;
			$objCustomer["reference1"]			= "";
			$objCustomer["reference2"]			= "";
			$objCustomer["reference3"]			= "";
			$objCustomer["reference4"]			= "";
			$objCustomer["reference5"]			= "";
			$objCustomer["balancePoint"]		= 0;
			$objCustomer["phoneNumber"]			= $customerPhoneNumber;
			$objCustomer["typeFirm"]			= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_TYPE_FIRM_ID_DEFAULT")->value;
			$objCustomer["budget"]				= 0;
			$objCustomer["isActive"]			= true;
			$objCustomer["entityContactID"]		= 0;
			$objCustomer["formContactID"]		= $core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_FORM_CONTACT_ID_DEFAULT")->value;
			$objCustomer["allowWhatsappPromotions"]		= 0;
			$objCustomer["allowWhatsappCollection"]		= 0;
			
			$core_web_auditoria->setAuditCreated($objCustomer,$dataSession,$request);
			$result 							= $Customer_Model->insert_app_posme($objCustomer);
			
			//Ingresar registro en el lector biometrico
			$validateBiometric = $core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_USE_BIOMETRIC");
			if(strcmp(strtolower($validateBiometric->value), "true") == 0)
			{	
				$dataUser["id"]							= $entityID;
				$dataUser["name"]						= "buscar en otra base";
				$dataUser["email"]						= "buscar en otra base";
				$dataUser["email_verified_at"]			= "0000-00-00 00:00:00";
				$dataUser["password"]					= "buscar en otra base";
				$dataUser["remember_token"]				= "buscar en otra base";
				$dataUser["created_at"]					= "0000-00-00 00:00:00";
				$dataUser["updated_at"]					= "0000-00-00 00:00:00";
				$dataUser["image"]						= "";
				$resultUser 							= $Biometric_User_Model->delete_app_posme($dataUser["id"]);
				$resultUser 							= $Biometric_User_Model->insert_app_posme($dataUser);
			}

			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponentCustomer->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";

			$objEntityAccount["accountID"]			= "0";
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= 1;
			$core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession,$request);
			$Entity_Account_Model->insert_app_posme($objEntityAccount);

			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= 900000;
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= 5000;
			$Customer_Credit_Model->insert_app_posme($objCustomerCredit);

			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= array();
			$arrayListCreditLineID			= array();
			$arrayListCreditCurrencyID		= array();
			$arrayListCreditStatusID		= array();
			$arrayListCreditInterestYear	= array();
			$arrayListCreditInterestPay		= array();
			$arrayListCreditTotalPay		= array();
			$arrayListCreditTotalDefeated	= array();
			$arrayListCreditDateOpen		= array();
			$arrayListCreditPeriodPay		= array();
			$arrayListCreditDateLastPay		= array();
			$arrayListCreditTerm			= array();
			$arrayListCreditNote			= array();
			$arrayListCreditLine			= array();
			$arrayListCreditNumber			= array();
			$arrayListCreditLimit			= array();
			$arrayListCreditBalance			= array();
			$arrayListCreditStatus			= array();
			$arrayListTypeAmortization		= array();
			$arrayListDayExcluded			= array();
			$limitCreditLine 				= 0;




			if(empty($arrayListCustomerCreditLineID))
			{
				$arrayListCustomerCreditLineID[0]	= 1;
				$arrayListCreditLineID[0] 			= $creditLineDefault;
				$arrayListCreditCurrencyID[0]		= $core_web_currency->getCurrencyDefault($companyID)->currencyID;
				$arrayListCreditLimit[0]			= 300000;
				$arrayListCreditInterestYear[0]		= $interesDefault;
				$arrayListCreditInterestPay[0]		= 0;
				$arrayListCreditTotalPay[0]			= 0;
				$arrayListCreditTotalDefeated[0]	= 0;
				$arrayListCreditPeriodPay[0]		= $frecuencyDefault;
				$arrayListCreditTerm[0]				= $plazoDefault;
				$arrayListCreditNote[0]				= "-";
				$arrayListTypeAmortization[0]		= $typeAmortizationDefault;
				$arrayListDayExcluded[0]			= $dayExcludedDefault;				
				$arrayListCreditStatusID[0]			= $core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;

			}
			
			

			if(!empty($arrayListCustomerCreditLineID))
			{
				foreach($arrayListCustomerCreditLineID as $key => $value)
				{
					$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
					$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
					$objCustomerCreditLine["entityID"]		= $entityID;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objCustomerCreditLine["dayExcluded"]		= $arrayListDayExcluded[$key];
					$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 								= $core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine["currencyID"],$objCurrencyDolares->currencyID);//cordobas a dolares, o dolares a dolares.
					$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
					$customerCreditLineID 						= $Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					$objDataSet["customerCreditLineID"]			= $customerCreditLineID;




					//sumar los limites en dolares
					if($exchangeRate == 1)
						$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
					//sumar los limite en cordoba
					else
						$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);



				}
			}


			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
				throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			
			//Asociar el cliente al colaborador
			$objUserAdmin					=  $User_Model->get_rowByRoleAdmin($dataSession["user"]->companyID);			
			if($objUserAdmin)
			{
				$objListEmployerID 		= array_map(function($i) { return $i->employeeID; }, $objUserAdmin);
				$objListEmployerID[] 	= $dataSession["user"]->employeeID;
				$objListEmployerID 		= array_unique($objListEmployerID);
				
				foreach ($objListEmployerID as $employerIDT)
				{
					$dataRelationShip				= NULL;
					$dataRelationShip["employeeID"]	= $employerIDT;
					$dataRelationShip["customerID"]	= $entityID;
					$dataRelationShip["isActive"]	= 1;
					$dataRelationShip["startOn"]	= date("Y-m-d");
					$dataRelationShip["endOn"]		= date("Y-m-d");
					$Relationship_Model->insert_app_posme($dataRelationShip);					
				}
			}
			
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			$pathfile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentCustomer->componentID."/component_item_".$entityID;

			if (!file_exists($pathfile))
			{
				mkdir($pathfile, 0700,true);
			}


			
			if($db->transStatus() !== false)
			{
				log_message('error', 'Webhook RAW JSON: Success');
				$db->transCommit();
			}
			else{
				log_message('error', 'Webhook RAW JSON: Error');
				$db->transRollback();
				return [
					'success' => false,
					'message' => "no fue posible crear al cliente",   		// mensaje del error
					'line'    => "0",      	// línea donde ocurrió
					'file'    => ""       	// archivo (opcional pero útil)
				];
				
			}
			
			return $entityID;

		}
		catch(\Exception $ex)
		{
			$objException = [
				'success' => false,
				'message' => $ex->getMessage(),   // mensaje del error
				'line'    => $ex->getLine(),      // línea donde ocurrió
				'file'    => $ex->getFile()       // archivo (opcional pero útil)
			];
			log_message("error",print_r($objException,true));
		}
	}
	
	function createConversation($dataSession,$entityIDCustomer)
	{
		//Ingresar al cliente
		try
		{
			$companyID					= $dataSession["user"]->companyID;
			$branchID					= $dataSession["user"]->branchID;
			$roleID						= $dataSession["role"]->roleID;
			$core_web_tools				= new core_web_tools();
			$core_web_workflow			= new core_web_workflow();			
			$Customer_Conversation_Model= new Customer_Conversation_Model();
			
			$conversationIsNew			= true;
			$objComponentCustomer		= $core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$objWorkflowStageInit							= $core_web_workflow->getWorkflowInitStage("tb_customer_conversation","statusID",$companyID,$branchID,$roleID);			
			if(!$objWorkflowStageInit)
				throw new \Exception("EL COMPONENTE 'Rol sin acceso al estado inicial de la conversacion' ...");
			
			
			
			$objCustomerConversation 						= array();			
			$objCustomerConversation["entityIDSource"] 		= $entityIDCustomer;
			$objCustomerConversation["entityIDTarget"] 		= 0;
			$objCustomerConversation["componentIDSource"] 	= $objComponentCustomer->componentID;
			$objCustomerConversation["componentIDTarget"] 	= 0;
			$objCustomerConversation["createdOn"] 			= helper_getDateTime();
			$objCustomerConversation["statusID"] 			= $objWorkflowStageInit[0]->workflowStageID;
			
			$objCustomerConversation["messageCounter"] 		= 1;
			$objCustomerConversation["messageReceiptOn"] 	= helper_getDateTime();
			$objCustomerConversation["messageSendOn"] 		= NULL;
			$objCustomerConversation["messgeConterNotRead"] = 1;
			$objCustomerConversation["reference1"] 			= "";
			$objCustomerConversation["reference2"] 			= "";
			$objCustomerConversation["reference3"] 			= "";
			$objCustomerConversation["isActive"] 			= 1;
			
			$conversationID			= $Customer_Conversation_Model->insert_app_posme($objCustomerConversation);
			return $conversationID;
		}
		catch(\Exception $ex)
		{
			$objException = [
				'success' => false,
				'message' => $ex->getMessage(),   // mensaje del error
				'line'    => $ex->getLine(),      // línea donde ocurrió
				'file'    => $ex->getFile()       // archivo (opcional pero útil)
			];
			log_message("error",print_r($objException,true));
			return 0;
		}
	}

	function createEmployerInConversation($dataSession,$conversationID,$objListEntityIDEmployer)
	{
		//Afiliar la conversacion a un agente	
		try
		{
			$core_web_tools							= new core_web_tools();
			$Company_Component_Relation_Model		= new Company_Component_Relation_Model();	
			$objComponentCustomerConversation		= $core_web_tools->getComponentIDBy_ComponentName("tb_customer_conversation");
			if(!$objComponentCustomerConversation)
				throw new \Exception("EL COMPONENTE 'tb_customer_conversation' NO EXISTE...");
			
			$objComponentEmployee		= $core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
				throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			if($objListEntityIDEmployer)
			{
				if(count($objListEntityIDEmployer)> 0)
				{
					foreach($objListEntityIDEmployer as $entityIDEmployer)
					{
						$objRelation = $Company_Component_Relation_Model->get_ConversationEmployerBy_entityIDEmployerAnd_ConversationID(
							$entityIDEmployer,
							$conversationID
						);
						if(!$objRelation)
						{
							$objCCRelation							= array();
							$objCCRelation["componentIDSource"] 	= $objComponentCustomerConversation->componentID;
							$objCCRelation["componentIDTarget"] 	= $objComponentEmployee->componentID;
							$objCCRelation["componentItemIDSource"] = $conversationID;
							$objCCRelation["componentItemIDTarget"] = $entityIDEmployer;
							$objCCRelation["isActive"] 				= 1;
							$objCCRelation["note"] 					= "source: conversaionID, target: entityIDEmployer:  colaboradores relacionados a las conversaciones";
							$companyComponentRelationID 			= $Company_Component_Relation_Model->insert_app_posme($objCCRelation);
						}
					}
				}
			}
		}
		catch(\Exception $ex)
		{
			$objException = [
				'success' => false,
				'message' => $ex->getMessage(),   // mensaje del error
				'line'    => $ex->getLine(),      // línea donde ocurrió
				'file'    => $ex->getFile()       // archivo (opcional pero útil)
			];
			log_message("error",print_r($objException,true));
		}
		
	}
	
	function notificationEmployerInConversation($companyID,$branchID,$companyType,$conversationID,$mensaje)
	{
		
		//Afiliar la conversacion a un agente	
		try
		{
			$core_web_whatsap						= new core_web_whatsap();
			$core_web_tools							= new core_web_tools();
			$Company_Component_Relation_Model		= new Company_Component_Relation_Model();
			$Entity_Phone_Model						= new Entity_Phone_Model();
			$objComponentCustomerConversation		= $core_web_tools->getComponentIDBy_ComponentName("tb_customer_conversation");
			if(!$objComponentCustomerConversation)
				throw new \Exception("EL COMPONENTE 'tb_customer_conversation' NO EXISTE...");
			
			$objComponentEmployee		= $core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
				throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			//Obtener la lista de colaboradores de la conversacion
			log_message("error","Obtener colaboradores asignados a la conversacion");
			$objListEmployerConversation = $Company_Component_Relation_Model->get_ConversationEmployerBy_ConversationID($conversationID);
			if(!$objListEmployerConversation)
				return;
			
			//Obtener el tip 5 de las ultimos colaboradores
			log_message("error","Obtener Top 5 colaboradores asignados a la conversacion");
			$objListEmployerConversationTop5 = $Company_Component_Relation_Model->get_ConversationTopEmployerBy_ConversationID(5,$conversationID);
			if(!$objListEmployerConversationTop5)
				return;
			
			//Mandarle mensaje a la ultima persona que converso
			if($objListEmployerConversationTop5)
			{
				log_message("error","Recorrer cada uno de los colaboradores del top 5");
				$phone = $Entity_Phone_Model->get_rowByPrimaryEntity(
					$companyID,$branchID,$objListEmployerConversationTop5[0]->entityID
				);
				
				if(!$phone)
					return;
				
				$phone = clearNumero($phone[0]->number);
				$core_web_whatsap->sendMessageGeneric(
					$companyType,
					$companyID, 
					$mensaje, 
					$phone	
				);			
			
			}
			
			//wg-other-companyType-foreach($objListEmployerConversation as $employer)
			//wg-other-companyType-{
			//wg-other-companyType-	//Obtener el numero de telefono del colaborador
			//wg-other-companyType-	$phone = $Entity_Phone_Model->get_rowByPrimaryEntity($companyID,$branchID,$employer->entityID);				
			//wg-other-companyType-	if(!$phone)
			//wg-other-companyType-		continue;
			//wg-other-companyType-	
			//wg-other-companyType-	$phone = clearNumero($phone[0]->number);
			//wg-other-companyType-	if(
			//wg-other-companyType-		$phone == "50584766457" ||
			//wg-other-companyType-		$phone == "50587125827"
			//wg-other-companyType-	)
			//wg-other-companyType-		continue;
			//wg-other-companyType-	
			//wg-other-companyType-	$core_web_whatsap->sendMessageGeneric(
			//wg-other-companyType-		$companyType,
			//wg-other-companyType-		$companyID, 
			//wg-other-companyType-		$mensaje, 
			//wg-other-companyType-		$phone	
			//wg-other-companyType-	);				
			//wg-other-companyType-}
			
		}
		catch(\Exception $ex)
		{
			$objException = [
				'success' => false,
				'message' => $ex->getMessage(),   // mensaje del error
				'line'    => $ex->getLine(),      // línea donde ocurrió
				'file'    => $ex->getFile()       // archivo (opcional pero útil)
			];
			log_message("error",print_r($objException,true));
		}
		
	}

	function getMessageSignature($companyID,$typeCompany,$firstNameEmployer,$mensaje)
	{	
		// Título en negrita para WhatsApp
		$titulo 		= trim($firstNameEmployer);

		// Mensaje final con salto de línea
		$mensajeFinal 	= trim($mensaje)."\n*Att:".$titulo."*";
		return $mensajeFinal;
	}
}
?>