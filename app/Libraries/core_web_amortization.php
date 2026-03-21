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
use App\Models\Transaction_Master_Detail_References_Model;

use App\Models\Transaction_Profile_Detail_Model;
use App\Models\Userwarehouse_Model;
use App\Models\User_Tag_Model;
use App\Models\Warehouse_Model;


use App\Libraries\financial\financial_amort;
use App\Libraries\core_web_catalog;

class core_web_amortization {
   
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
   function cancelDocument($companyID,$customerCreditDocumentID,$amount){
	    $core_web_parameter = new core_web_parameter();
		$Customer_Credit_Document_Model = new Customer_Credit_Document_Model();
		$Customer_Credit_Amortization_Model = new Customer_Credit_Amortization_Model();
		date_default_timezone_set(APP_TIMEZONE);
		 
		$documentCancel 							= $core_web_parameter->getParameter("SHARE_DOCUMENT_CANCEL",$companyID)->value;
		$amortizationCancel 						= $core_web_parameter->getParameter("SHARE_CANCEL",$companyID)->value;
		$objCustomerCreditDocument					= $Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		
		
		//Cancel Document
		if($amount >= $objCustomerCreditDocument->balance){
			$objCustomerCreditDocumentNew["balance"]	= 0;
			$objCustomerCreditDocumentNew["statusID"]	= $documentCancel;
			$Customer_Credit_Document_Model->update_app_posme($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		}
		else
			throw new \Exception("EL IMPORTE NO ES SUFICIENTE PARA CANCELAR EL DOCUMENTO");
		
		//Cancelar Amortizacion
		$objListCustomerCreditDocumentAmortization 	= $Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		if($objListCustomerCreditDocumentAmortization)
		foreach($objListCustomerCreditDocumentAmortization as $key => $itemAmortization){
			$itemAmortizationNew["remaining"]				= 0;
			$Customer_Credit_Amortization_Model->update_app_posme($itemAmortization->creditAmortizationID,$itemAmortizationNew);		
		}
		
		
   }
   function shareCapital($companyID,$customerCreditDocumentID,$amount){
		$Customer_Credit_Document_Model = new Customer_Credit_Document_Model();
		$Customer_Credit_Amortization_Model = new Customer_Credit_Amortization_Model();
		$Customer_Credit_Line_Model = new Customer_Credit_Line_Model();		
		$Catalog_Item_Model		= new Catalog_Item_Model();
		$financial_amort 	= new financial_amort();
		$core_web_catalog 	= new core_web_catalog();
		
		date_default_timezone_set(APP_TIMEZONE);
		
		
		$objCustomerCreditDocument					= $Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		$objListCustomerCreditDocumentAmortization 	= $Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		$objCustomerCreditLine						= $Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID);
		$periodPay 									= $Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);
		$numCuotas									= count($objListCustomerCreditDocumentAmortization);
		$totalCapital								= $objCustomerCreditDocument->balance - $amount;
		//obtener el primer registro
		//$creditAmortizationIDMin					= array_reduce($objListCustomerCreditDocumentAmortization,function($v,$w){if (!$v)$v = $w->creditAmortizationID;if ($v > $w->creditAmortizationID){ $v = $w->creditAmortizationID;} return $v;});
		$dateApplyFirst 							= $objListCustomerCreditDocumentAmortization[0]->dateApply;
		
		
		$objCatalogItem_DiasNoCobrables 		= $core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES",$companyID);		
		$objCatalogItem_DiasFeriados365 		= $core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_365",$companyID);
		$objCatalogItem_DiasFeriados366 		= $core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_366",$companyID);
			
		$financial_amort->amort(
						$totalCapital, 											/*monto*/
						$objCustomerCreditDocument->interes,					/*interes anual*/
						$numCuotas,												/*numero de pagos*/	
						$periodPay->sequence,									/*frecuencia de pago en dia*/
						$objCustomerCreditDocument->dateOn,						/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 				/*tipo de amortizacion*/,
						$objCatalogItem_DiasNoCobrables,
						$objCatalogItem_DiasFeriados365,
						$objCatalogItem_DiasFeriados366
					);
		
		//Recalcular Tabla de Amortizacion		
		$tableAmortization 	= $financial_amort->getTable();		
		if($objListCustomerCreditDocumentAmortization)
		foreach($objListCustomerCreditDocumentAmortization as $key => $itemAmortization){
		
			$itemAmortizationNew	= null;
			//si es el primer registro , registrar que realizo un abono al capital
			if($dateApplyFirst == $itemAmortization->dateApply){
					$itemAmortizationNew["shareCapital"]	= $amount;
			}
			
			$itemAmortizationNew["balanceStart"]			= $tableAmortization["detail"][$key+1]["saldoInicial"];
			$itemAmortizationNew["balanceEnd"]				= $tableAmortization["detail"][$key+1]["saldo"];
			$itemAmortizationNew["capital"]					= $tableAmortization["detail"][$key+1]["principal"];
			$itemAmortizationNew["interest"]				= $tableAmortization["detail"][$key+1]["interes"];
			$itemAmortizationNew["share"]					= $itemAmortizationNew["interest"] + $itemAmortizationNew["capital"];
			$itemAmortizationNew["remaining"]				= $itemAmortizationNew["share"];			
			$Customer_Credit_Amortization_Model->update_app_posme($itemAmortization->creditAmortizationID,$itemAmortizationNew);		
		}
		
		//Actualizar Balance del Documento
		$objCustomerCreditDocumentNew				= null;
		$objCustomerCreditDocumentNew["balance"]	= $totalCapital;
		$Customer_Credit_Document_Model->update_app_posme($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		
   }
   function changeStatus($companyID,$customerCreditDocumentID){
	    $core_web_parameter = new core_web_parameter();
	    $Customer_Credit_Document_Model = new Customer_Credit_Document_Model();
		$Customer_Credit_Amortization_Model = new Customer_Credit_Amortization_Model();
		date_default_timezone_set(APP_TIMEZONE);
		 
		$documentProvisioned						= $core_web_parameter->getParameter("CREDIT_DOCUMENT_PROVISIONED",$companyID)->value;
		$amortizationProvisioned					= $core_web_parameter->getParameter("CREDIT_AMORTIZATION_PROVISIONED",$companyID)->value;
		$objCustomerCreditDocument					= $Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		$objListCustomerCreditDocumentAmortization 	= $Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		
		//Provisionar Documento
		if($objCustomerCreditDocument->balanceProvicioned >=  $objCustomerCreditDocument->balance){
			$objCustomerCreditDocumentNew				= null;
			$objCustomerCreditDocumentNew["statusID"]	= $documentProvisioned;
			$Customer_Credit_Document_Model->update_app_posme($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		}
	   
   }
   /**
    * Reestructura las fechas de pago (dateApply) de las cuotas pendientes de un documento de crédito.
    * Solo modifica cuotas con isActive=1 y remaining > 0.
    * Los días a sumar se obtienen del campo sequence del catalog_item asociado al periodPay del documento.
    *
    * @param int    $customerCreditDocumentID  ID del documento de crédito
    * @param string $dateApply                 Fecha base desde la cual se recalculan las cuotas (Y-m-d)
    * @return array ["success" => bool, "message" => string]
    */
   function restructureAmortization($customerCreditDocumentID, $dateApply){
		$Customer_Credit_Document_Model    	= new Customer_Credit_Document_Model();
		$Customer_Credit_Amortization_Model = new Customer_Credit_Amortization_Model();
		$Catalog_Item_Model                	= new Catalog_Item_Model();
		$log                               	= new \CodeIgniter\Log\Logger(new \Config\Logger());

		try {

			// PASO 1: Obtener el documento de crédito
			log_message('info', "[restructureAmortization] PASO 1 - Buscando documento customerCreditDocumentID={$customerCreditDocumentID}");
			$objDocument = $Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
			if(!$objDocument)
				throw new \Exception("Documento de crédito no encontrado. customerCreditDocumentID={$customerCreditDocumentID}");

			// PASO 2: Obtener el catalog_item del periodPay para leer sequence (días entre cuotas)
			log_message('info', "[restructureAmortization] PASO 2 - Obteniendo periodPay={$objDocument->periodPay} del catálogo");
			$objPeriodPay = $Catalog_Item_Model->get_rowByCatalogItemID($objDocument->periodPay);
			if(!$objPeriodPay)
				throw new \Exception("No se encontró el catalog_item para periodPay={$objDocument->periodPay}");

			$diasEntreCuotas = (int)$objPeriodPay->sequence;
			log_message('info', "[restructureAmortization] PASO 2 - diasEntreCuotas={$diasEntreCuotas}");

			// PASO 3: Obtener todas las cuotas activas con remanente pendiente
			log_message('info', "[restructureAmortization] PASO 3 - Obteniendo cuotas activas con remaining > 0");
			$objListAmortization = $Customer_Credit_Amortization_Model->get_rowByDocument($customerCreditDocumentID);
			if(!$objListAmortization || count($objListAmortization) == 0)
				throw new \Exception("No se encontraron cuotas para customerCreditDocumentID={$customerCreditDocumentID}");

			// PASO 4: Filtrar solo las cuotas con isActive=1 y remaining > 0, y actualizar dateApply
			log_message('info', "[restructureAmortization] PASO 4 - Procesando cuotas");
			$fechaBase    = new \DateTime($dateApply);
			$actualizadas = 0;

			foreach($objListAmortization as $cuota){

				// Solo procesar cuotas activas con remanente
				if((int)$cuota->isActive !== 1 || (float)$cuota->remaining <= 0)
					continue;

				// Calcular nueva fecha sumando n días a la fecha base acumulada
				$nuevaFecha = clone $fechaBase;
				$nuevaFecha->modify("+{$diasEntreCuotas} days");
				$fechaBase  = clone $nuevaFecha;

				$nuevaFechaStr = $nuevaFecha->format("Y-m-d");
				log_message('info', "[restructureAmortization] PASO 4 - Actualizando creditAmortizationID={$cuota->creditAmortizationID} dateApply={$nuevaFechaStr}");

				$dataUpdate["dateApply"] = $nuevaFechaStr;
				$Customer_Credit_Amortization_Model->update_app_posme($cuota->creditAmortizationID, $dataUpdate);
				$actualizadas++;
			}

			log_message('info', "[restructureAmortization] COMPLETADO - {$actualizadas} cuotas actualizadas para customerCreditDocumentID={$customerCreditDocumentID}");
			return ["success" => true, "message" => "Reestructuración completada. Cuotas actualizadas: {$actualizadas}"];

		} catch(\Exception $ex){
			log_message('error', "[restructureAmortization] ERROR - Línea {$ex->getLine()}: {$ex->getMessage()}");
			return ["success" => false, "message" => $ex->getLine()." ".$ex->getMessage()];
		}
   }

   function applyCuote($companyID,$customerCreditDocumentID,$amount,$amoritizationID,$transactionMasterDetailID){
	    $core_web_parameter 						= new core_web_parameter();
		$Customer_Credit_Document_Model 			= new Customer_Credit_Document_Model();
		$Customer_Credit_Amortization_Model 		= new Customer_Credit_Amortization_Model();
		$Transaction_Master_Detail_Model			= new Transaction_Master_Detail_Model();
		$Transaction_Master_Detail_References_Model = new Transaction_Master_Detail_References_Model();	
		$core_web_tools								= new core_web_tools();
		date_default_timezone_set(APP_TIMEZONE);
		 
		 
		$objComponentAmortization			= $core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_amoritization");
		if(!$objComponentAmortization)
		throw new \Exception("EL COMPONENTE 'tb_customer_credit_amoritization' NO EXISTE...");		
	
	
		$objTransactionMasterDetail 				= $Transaction_Master_Detail_Model->get_rowByTransactionMasterDetailID($transactionMasterDetailID);
		$documentCancel 							= $core_web_parameter->getParameter("SHARE_DOCUMENT_CANCEL",$companyID)->value;
		$amortizationCancel 						= $core_web_parameter->getParameter("SHARE_CANCEL",$companyID)->value;
		$objCustomerCreditDocument					= $Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		$objListCustomerCreditDocumentAmortization 	= $Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		$objConceptos 				= array();
		$objConceptos["capital"] 	= 0;
		$objConceptos["interes"] 	= 0;
		
		
		//Cancel Cuota
		if($objListCustomerCreditDocumentAmortization)
		foreach($objListCustomerCreditDocumentAmortization as $key => $itemAmortization){	
			$interval	= date_diff(date_create($itemAmortization->dateApply),date_create());			
			
			if ($amount >= $itemAmortization->remaining && $amount <> 0){
				$amount									= $amount - $itemAmortization->remaining;				
				$dif									= $itemAmortization->remaining - $amount;
				$itemAmortizationNew					= NULL;
				$itemAmortizationNew["statusID"]		= $amortizationCancel;
				$itemAmortizationNew["remaining"]		= 0; 
				$itemAmortizationNew["dayDelay"]		= $interval->format('%r%a');	
				
				//Abonar a la cuota completa
				if($itemAmortization->remaining == $itemAmortization->share )
				{
					$objConceptos["capital"] 	= $objConceptos["capital"] + $itemAmortization->capital;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->interest;
				}
				else if ($itemAmortization->remaining > $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + ($itemAmortization->remaining - $itemAmortization->interest);
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->interest;
				}
				else if ($itemAmortization->remaining < $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + 0;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->remaining;
				}
				else if ($itemAmortization->remaining = $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + 0;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->interest;
				}
			
				$Customer_Credit_Amortization_Model->update_app_posme($itemAmortization->creditAmortizationID,$itemAmortizationNew);	
				
				$dataTMDR["transactionMasterDetailID"]		= $transactionMasterDetailID;
				$dataTMDR["componentID"]					= $objComponentAmortization->componentID;
				$dataTMDR["componentItemID"]				= $itemAmortization->creditAmortizationID;
				$dataTMDR["quantity"]						= $itemAmortization->remaining;
				$dataTMDR["isActive"]						= 1;
				$dataTMDR["createdOn"]						= helper_getDateTime();	
				$Transaction_Master_Detail_References_Model->insert_app_posme($dataTMDR);
			}
			else if ($amount <> 0){	
				$itemAmortizationNew					= NULL;
				$itemAmortizationNew["remaining"]		= $itemAmortization->remaining - $amount;
				$itemAmortizationNew["dayDelay"]		= $interval->format('%r%a');
				$dif									= $itemAmortization->remaining - $amount;
				
				if ($dif > $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + $amount;
					$objConceptos["interes"] 	= $objConceptos["interes"] + 0;
				}
				else if ($dif == $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + $amount;
					$objConceptos["interes"] 	= $objConceptos["interes"] + 0;
				}
				else if ($dif < $itemAmortization->interest and $itemAmortization->remaining <= $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + 0;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $amount;
				}
				else if ($dif < $itemAmortization->interest and $itemAmortization->remaining > $itemAmortization->interest){
					$capital001 				= $itemAmortization->remaining - $itemAmortization->interest;
					$interes001 				= $amount - $capital001;					
					$objConceptos["capital"] 	= $objConceptos["capital"] + $capital001;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $interes001;
				}
				
				$Customer_Credit_Amortization_Model->update_app_posme($itemAmortization->creditAmortizationID,$itemAmortizationNew);					
				$dataTMDR["transactionMasterDetailID"]		= $transactionMasterDetailID;
				$dataTMDR["componentID"]					= $objComponentAmortization->componentID;
				$dataTMDR["componentItemID"]				= $itemAmortization->creditAmortizationID;
				$dataTMDR["quantity"]						= $amount;
				$dataTMDR["isActive"]						= 1;
				$dataTMDR["createdOn"]						= helper_getDateTime();				
				$Transaction_Master_Detail_References_Model->insert_app_posme($dataTMDR);				
				$amount 								= 0;					
			}
		}
		
		//Actualizar Balance del Documento
		$objCustomerCreditDocumentNew				= null;
		$objCustomerCreditDocumentNew["balance"]	= $objCustomerCreditDocument->balance - $objConceptos["capital"];
		$Customer_Credit_Document_Model->update_app_posme($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		
		//Cancel Document
		$objListCustomerCreditDocumentAmortization 	= $Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);			
		if(!$objListCustomerCreditDocumentAmortization){
			$objCustomerCreditDocumentNew				= null;
			$objCustomerCreditDocumentNew["statusID"]	= $documentCancel;
			$Customer_Credit_Document_Model->update_app_posme($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		}
		
		
   }
  
}
?>
