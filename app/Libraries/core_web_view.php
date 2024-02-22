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


class core_web_view {
   
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
   function getViewByName($user,$componentID,$name,$callerID,$permission=null,$parameter=null){
		$Data_View_Model = new Data_View_Model();
		$Company_Data_View_Model = new Company_Data_View_Model();
		$Company_Model = new Company_Model();
		$Bd_Model = new Bd_Model();  
		
		//Obtener la vista generica
		$companyDataView			= $Data_View_Model->getViewByName($componentID,$name,$callerID);
		if(!$companyDataView)
		return null;
		
		//Obtener la compania
		$objCompany = $Company_Model->get_rowByPK($user->companyID);
		
		//Obtener la vista por el flavor		
		$dataviewID 				= $companyDataView->dataViewID;
		$companyDataView			= $Company_Data_View_Model->get_rowBy_companyIDDataViewIDAndFlavor($user->companyID,$dataviewID,$callerID,$componentID,$objCompany->flavorID);
		if(!$companyDataView)
		{	
			//Obtener la vista unica
			$dataviewID 				= $dataviewID;
			$companyDataView			= $Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$dataviewID,$callerID,$componentID);
			if(!$companyDataView)
			return null;
		}
		
		
		
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID; 
		}
		else{
			$filterPermission	= "";
		}		
		
		$queryFill					= str_replace("{filterPermission}", $filterPermission, $queryFill);
		
		//
		$dataRecordSet				= $Bd_Model->executeRender($queryFill,null);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;
   }
   function getViewBy_DataViewID($user,$componentID,$dataviewID,$callerID,$permission=null,$parameter=null){
		$Data_View_Model = new Data_View_Model();
		$Company_Data_View_Model = new Company_Data_View_Model();
		$Bd_Model = new Bd_Model();  
		
		//Obtener la vista por company
		$companyDataView			= $Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$dataviewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID; 
		}
		else{
			$filterPermission	= "";
		}		
		
		$queryFill					= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $Bd_Model->executeRender($queryFill,null);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;
   }
   function getView($user,$componentID = null,$callerID = null,$permission=null,$parameter=null){
		$Data_View_Model = new Data_View_Model();
		$Company_Data_View_Model = new Company_Data_View_Model();
		$Bd_Model = new Bd_Model();  
		
		//Obtener la vista		
		$objListView				= $Data_View_Model->getListBy_CompanyComponentCaller($componentID,$callerID);				
		if(!$objListView)
		return null; 
		
		//Obtener la vista por company
		$companyDataView			= $Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$objListView->dataViewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID; 
		}
		else{
			$filterPermission	= "";
		}		
		
		$queryFill					= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $Bd_Model->executeRender($queryFill,null);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;		 
   }   
   function getViewDefault($user,$componentID = null,$callerID = null,$targetComponentID = null,$permission = null,$parameter = null){
		$Company_Default_Data_View_Model = new Company_Default_Data_View_Model();
		$Company_Data_View_Model = new Company_Data_View_Model();
		$Bd_Model = new Bd_Model();		
		
		
		
		//Obtener la vista por defecto
		$objCompanyDefaultDataView	= $Company_Default_Data_View_Model->get_rowBy_CCCT($user->companyID,$componentID,$callerID,$targetComponentID);
		if(!$objCompanyDefaultDataView)
		return null;
	
		
		
		//Obtener la vista por company
		$companyDataView			= $Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$objCompanyDefaultDataView->dataViewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		 
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID;
		}
		else{
			$filterPermission	= "";
		}		
		
		//Ejecutar Vista.		
		$queryFill	= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $Bd_Model->executeRender($queryFill,null);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;
		
		
   }
   function renderGreed($data,$idTable = null,$functionSelected = NULL,$displayLength = 350){
		
		$table = "";
		$table = $table."<table  id='".$idTable."' cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered table-hover' style='display:none' >";
			//crear el encabezado
			$table = $table."<thead>";
			$table = $table."<tr>";
			$summaryColumns	= explode(",",$data["view_config"]->summaryColumns);
			$formatColumns	= explode(",",$data["view_config"]->formatColumns);
			
			$cabezera1	= explode(",",$data["view_config"]->nonVisibleColumns);
			$cabezera2	= explode(",",$data["view_config"]->visibleColumns);
			$cabezera3	= array_merge($cabezera1 , $cabezera2);	
			
			
			$agregarLineaSuma 		= false;
			$agregarLineaSumaRow	= $summaryColumns;			
			foreach($agregarLineaSumaRow as $key => $value){
				
				if($value == "true")
				{
					$agregarLineaSumaRow[$key] = 0;					
				}
				else if ($key == 0)
				{
					$agregarLineaSumaRow[$key] = "-TOTAL-";
				}
				else
				{
					$agregarLineaSumaRow[$key] = "-TOTAL-";				
				}
			}
			
			foreach($cabezera3 AS $fieldDisplay){			
				$table = $table."<th>";
				$table = $table.$fieldDisplay;
				$table = $table."</th>";
				
			}
			$table = $table."</tr>";
			$table = $table."</thead>";
			
			
			//crear cuerpo
			$table = $table;
			$body  = "";
			foreach($data["view_data"] AS $row_)
			{		

				$body = $body."<tr>";
				foreach($cabezera3 AS $key => $fieldDisplay)
				{	
					
					$sumaryColumn = "";
					$formatColumn = "";
					
					
					if( count( $summaryColumns ) > 1)
					{
						$sumaryColumn = $summaryColumns[$key];
						if($sumaryColumn == "true")
						{
							$agregarLineaSuma = true;
						}
					}
				
					if( count ($formatColumns ) > 1 )
					{
						$formatColumn = $formatColumns[$key];
					}
					
					
					if($formatColumn != "")
					{
						$body 						= $body."<td style='".$formatColumn."'>";
						$body 						= $body.($row_[$fieldDisplay]);
						$body 						= $body."</td>";
						
						if( $agregarLineaSuma == true && $sumaryColumn == "true")
						$agregarLineaSumaRow[$key] 	= $agregarLineaSumaRow[$key] + $row_[$fieldDisplay];
					}
					else
					{
						$body 						= $body."<td>";
						$body 						= $body.($row_[$fieldDisplay]);
						$body 						= $body."</td>";
						
						
						
						if( $agregarLineaSuma == true && $sumaryColumn == "true")
						$agregarLineaSumaRow[$key] 	= $agregarLineaSumaRow[$key] + $row_[$fieldDisplay];
					}
					
					
					
				}
				$body = $body."</tr>";
			}
			
			//agregar el totale
			$rowSummary = "<tr>";
			if($agregarLineaSuma == true)
			{
				foreach($cabezera3 AS $key => $fieldDisplay)
				{	
					
					
					$formatColumn = "";
					if( count ($formatColumns ) > 1 )
					$formatColumn = $formatColumns[$key];					
					
					
					if($formatColumn != "")
					{
						$rowSummary 						= $rowSummary."<td style='".$formatColumn."'>";
						$rowSummary 						= $rowSummary.($agregarLineaSumaRow[$key]);
						$rowSummary 						= $rowSummary."</td>";
						
						
					}
					else
					{
						$rowSummary 						= $rowSummary."<td>";
						$rowSummary 						= $rowSummary.($agregarLineaSumaRow[$key]);
						$rowSummary 						= $rowSummary."</td>";						
						
					}
					
					
					
				}
			}
			$rowSummary = $rowSummary."</tr>";
			if($rowSummary == "<tr></tr>")
			$rowSummary  = "";
		
			$table = $table."<tbody>".$rowSummary.$body."</tbody>";
		
		$table = $table."</table>";
		
		 
		//Render JS
		$js					= "
		<script>
			var objTable".$idTable.";
			var objRowTable".$idTable.";	
					
			$(document).ready(function() {
						$('#".$idTable."').dataTable({
							'Dom'				: \"<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>\",
							'sPaginationType'	: 'bootstrap',							
							'bJQueryUI'			: false,
							'bAutoWidth'		: false,							
							'iDisplayLength'	: ".$displayLength.",							
							'oLanguage'	: {
								'sSearch'		: '<span>Filtro:</span> _INPUT_',
								'sLengthMenu'	: '<span>_MENU_ elementos</span>',
								//'sLengthMenu'	: '',
								'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }
							}
						});
						
						
						objTable".$idTable." = $('#".$idTable."').dataTable( ); 
		
						$('.dataTables_length select').uniform();
						$('.dataTables_paginate > ul').addClass('pagination');						
						";
						
						foreach($cabezera1 AS $field ){
							$i		= array_search($field, $cabezera3);
							$temp	= "objTable".$idTable.".fnSetColumnVis(".$i.",false); ";
							$js		= $js.$temp; 
							
						}
						
						$js	= $js."	 					
						$(document).on('click','#".$idTable." tr',function(event){ objRowTable".$idTable." = this; ".$functionSelected."(this,event);});  
						$('#".$idTable."').css('display','table');
			});							
		</script>";
		
		//Resultado
		$resultGreed		=	$table;
		$resultGreedMoreJS	=   $resultGreed.$js;
		return $resultGreedMoreJS;
		
   }
   function renderGreedMobile($data,$idTable = null,$functionSelected = NULL,$displayLength = 350){
		
		$table = "";
		$table = $table."<table  id='".$idTable."' cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered table-hover' style='display:none' >";
			//crear el encabezado
			$table = $table."<thead>";
			$table = $table."<tr>";
			$summaryColumns	= explode(",",$data["view_config"]->summaryColumns);
			$formatColumns	= explode(",",$data["view_config"]->formatColumns);
			
			$cabezera1	= explode(",",$data["view_config"]->nonVisibleColumns);
			$cabezera2	= explode(",",$data["view_config"]->visibleColumns);
			$cabezera3	= array_merge($cabezera1 , $cabezera2);	
			
			
			$agregarLineaSuma 		= false;
			$agregarLineaSumaRow	= $summaryColumns;			
			foreach($agregarLineaSumaRow as $key => $value){
				
				if($value == "true")
				{
					$agregarLineaSumaRow[$key] = 0;					
				}
				else if ($key == 0)
				{
					$agregarLineaSumaRow[$key] = "-TOTAL-";
				}
				else
				{
					$agregarLineaSumaRow[$key] = "-TOTAL-";				
				}
			}
			
			foreach($cabezera3 AS $fieldDisplay){			
				$table = $table."<th>";
				$table = $table.$fieldDisplay;
				$table = $table."</th>";
				
			}
			$table = $table."</tr>";
			$table = $table."</thead>";
			
			
			//crear cuerpo
			$table = $table;
			$body  = "";
			foreach($data["view_data"] AS $row_)
			{		

				$body = $body."<tr>";
				foreach($cabezera3 AS $key => $fieldDisplay)
				{	
					
					$sumaryColumn = "";
					$formatColumn = "";
					
					
					if( count( $summaryColumns ) > 1)
					{
						$sumaryColumn = $summaryColumns[$key];
						if($sumaryColumn == "true")
						{
							$agregarLineaSuma = true;
						}
					}
				
					if( count ($formatColumns ) > 1 )
					{
						$formatColumn = $formatColumns[$key];
					}
					
					
					if($formatColumn != "")
					{
						$body 						= $body."<td style='".$formatColumn."'>";
						$body 						= $body.($row_[$fieldDisplay]);
						$body 						= $body."</td>";
						
						if( $agregarLineaSuma == true && $sumaryColumn == "true")
						$agregarLineaSumaRow[$key] 	= $agregarLineaSumaRow[$key] + $row_[$fieldDisplay];
					}
					else
					{
						$body 						= $body."<td>";
						$body 						= $body.($row_[$fieldDisplay]);
						$body 						= $body."</td>";
						
						
						
						if( $agregarLineaSuma == true && $sumaryColumn == "true")
						$agregarLineaSumaRow[$key] 	= $agregarLineaSumaRow[$key] + $row_[$fieldDisplay];
					}
					
					
					
				}
				$body = $body."</tr>";
			}
			
			//agregar el totale
			$rowSummary = "<tr>";
			if($agregarLineaSuma == true)
			{
				foreach($cabezera3 AS $key => $fieldDisplay)
				{	
					
					
					$formatColumn = "";
					if( count ($formatColumns ) > 1 )
					$formatColumn = $formatColumns[$key];					
					
					
					if($formatColumn != "")
					{
						$rowSummary 						= $rowSummary."<td style='".$formatColumn."'>";
						$rowSummary 						= $rowSummary.($agregarLineaSumaRow[$key]);
						$rowSummary 						= $rowSummary."</td>";
						
						
					}
					else
					{
						$rowSummary 						= $rowSummary."<td>";
						$rowSummary 						= $rowSummary.($agregarLineaSumaRow[$key]);
						$rowSummary 						= $rowSummary."</td>";						
						
					}
					
					
					
				}
			}
			$rowSummary = $rowSummary."</tr>";
			if($rowSummary == "<tr></tr>")
			$rowSummary  = "";
		
			$table = $table."<tbody>".$rowSummary.$body."</tbody>";
		
		$table = $table."</table>";
		
		 
		//Render JS
		$js					= "
		<script>
			var objTable".$idTable.";
			var objRowTable".$idTable.";	
					
			$(document).ready(function() {
						$('#".$idTable."').dataTable({
							'Dom'				: \"<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>\",
							'sPaginationType'	: 'bootstrap',
							'bJQueryUI'			: false,
							'bAutoWidth'		: false,							
							'iDisplayLength'	: ".$displayLength.",							
							'oLanguage'	: {
								'sSearch'		: '<span>Filtro:</span> _INPUT_',
								'sLengthMenu'	: '<span>_MENU_ elementos</span>',
								'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }
							}
						});
						
						objTable".$idTable." = $('#".$idTable."').dataTable( ); 
		
						$('.dataTables_length select').uniform();
						$('.dataTables_paginate > ul').addClass('pagination');						
						";
						
						foreach($cabezera1 AS $field ){
							$i		= array_search($field, $cabezera3);
							$temp	= "objTable".$idTable.".fnSetColumnVis(".$i.",false); ";
							$js		= $js.$temp; 
							
						}
						
						$js	= $js."	 					
						$(document).on('click','#".$idTable." tr',function(event){ objRowTable".$idTable." = this; ".$functionSelected."(this,event);});  
						$('#".$idTable."').css('display','table');
			});							
		</script>";
		
		//Resultado
		$resultGreed		=	$table;
		$resultGreedMoreJS	=   $resultGreed.$js;
		return $resultGreedMoreJS;
		
   }
   
   function renderGreedWithHtmlInFild($data,$idTable = null,$functionSelected = NULL,$displayLength = 350){
		$table = "";
		$table = $table."<table  id='".$idTable."' cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered table-hover' style='display:none' >";
			//crear el encabezado
			$table = $table."<thead>";
			$table = $table."<tr>";
			$cabezera1	= explode(",",$data["view_config"]->nonVisibleColumns);
			$cabezera2	= explode(",",$data["view_config"]->visibleColumns);
			$cabezera3	= array_merge($cabezera1 , $cabezera2);				
			
			
			foreach($cabezera3 AS $fieldDisplay){			
				$table = $table."<th>";
				$table = $table.$fieldDisplay;
				$table = $table."</th>";
				
			}
			$table = $table."</tr>";
			$table = $table."</thead>";
			
			
			//crear cuerpo
			$table = $table."<tbody>";
			foreach($data["view_data"] AS $row_){		
			
				$table = $table."<tr>";
				foreach($cabezera3 AS $fieldDisplay){

					
					$table = $table."<td>";
					$table = $table.($row_[$fieldDisplay]);
					$table = $table."</td>";
					
				}
				$table = $table."</tr>";
			}
			$table = $table."</tbody>";
		
		$table = $table."</table>";
		
	 
	
		//Render JS
		$js					= "
		<script>
			var objTable".$idTable.";
			var objRowTable".$idTable.";	
					
			$(document).ready(function() {
						$('#".$idTable."').dataTable({
							'Dom'				: \"<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>\",
							'sPaginationType'	: 'bootstrap',
							'bJQueryUI'			: false,
							'bAutoWidth'		: false,							
							'iDisplayLength'	: ".$displayLength.",							
							'oLanguage'	: {
								'sSearch'		: '<span>Filtro:</span> _INPUT_',
								'sLengthMenu'	: '<span>_MENU_ elementos</span>',
								'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }
							}
						});
						
						objTable".$idTable." = $('#".$idTable."').dataTable( ); 
		
						$('.dataTables_length select').uniform();
						$('.dataTables_paginate > ul').addClass('pagination');						
						";
						
						foreach($cabezera1 AS $field ){
							$i		= array_search($field, $cabezera3);
							$temp	= "objTable".$idTable.".fnSetColumnVis(".$i.",false); ";
							$js		= $js.$temp; 
							
						}
						
						$js	= $js."	 					
						$(document).on('click','#".$idTable." tr',function(event){ objRowTable".$idTable." = this; ".$functionSelected."(this,event);});  
						$('#".$idTable."').css('display','table');
			});							
		</script>";
		
		
		 
		
		
		$resultGreed		=	$table;
		$resultGreedMoreJS	=   $resultGreed.$js;
		return $resultGreedMoreJS;
	
	}
	function renderGreedWithHtmlInFildMobile($data,$idTable = null,$functionSelected = NULL,$displayLength = 350){
		
		$table = "";
		$table = $table."<table  id='".$idTable."'    cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered table-hover' style='display:none' >";
			//crear el encabezado
			$table = $table."<thead>";
			$table = $table."<tr>";
			$cabezera1	= explode(",",$data["view_config"]->nonVisibleColumns);
			$cabezera2	= explode(",",$data["view_config"]->visibleColumns);
			$cabezera3	= array_merge($cabezera1 , $cabezera2);				
			
			
			foreach($cabezera3 AS $fieldDisplay){			
				$table = $table."<th>";
				$table = $table.$fieldDisplay;
				$table = $table."</th>";
				
			}
			$table = $table."</tr>";
			$table = $table."</thead>";
			
			
			//crear cuerpo
			$table = $table."<tbody>";
			foreach($data["view_data"] AS $row_){		
			
				$table = $table."<tr>";
				foreach($cabezera3 AS $fieldDisplay){

					
					$table = $table."<td>";
					$table = $table.($row_[$fieldDisplay]);
					$table = $table."</td>";
					
				}
				$table = $table."</tr>";
			}
			$table = $table."</tbody>";
		
		$table = $table."</table>";
		
	 
	
		//Render JS
		$js					= "
		<script>
			var objTable".$idTable.";
			var objRowTable".$idTable.";	
					
			$(document).ready(function() {
						$('#".$idTable."').dataTable({
							'Dom'				: '',
							'sPaginationType'	: 'bootstrap',
							'bJQueryUI'			: false,
							'bAutoWidth'		: true,							
							'iDisplayLength'	: ".$displayLength.",							
							'oLanguage'	: {
								'sSearch'		: '<span>Filtro:</span> _INPUT_',
								'sLengthMenu'	: '<span>_MENU_ elementos</span>',
								'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }
							}
						});
						
						objTable".$idTable." = $('#".$idTable."').dataTable( ); 
		
						$('.dataTables_length select').uniform();
						$('.dataTables_paginate > ul').addClass('pagination');						
						";
						
						foreach($cabezera1 AS $field ){
							$i		= array_search($field, $cabezera3);
							$temp	= "objTable".$idTable.".fnSetColumnVis(".$i.",false); ";
							$js		= $js.$temp; 
							
						}
						
						$js	= $js."	 					
						$(document).on('click','#".$idTable." tr',function(event){ objRowTable".$idTable." = this; ".$functionSelected."(this,event);});  
						$('#".$idTable."').css('display','table');
						$('#".$idTable."').css('display','table');
						$('#".$idTable."').css('width','100%');
			});							
		</script>";
		
		
		 
		
		
		$resultGreed		=	$table;
		$resultGreedMoreJS	=   $resultGreed.$js;
		return $resultGreedMoreJS;
	
	}
	function renderGreedPaginate($data,$idTable = null,$functionSelected = NULL,$displayLength = 350,$formatAjax = false,$parameterAjax=null){
		
		$table = "";
		$table = $table."<table  id='".$idTable."' cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered table-hover' style='display:none' >";
			//crear el encabezado
			$table = $table."<thead>";
			$table = $table."<tr>";
			$summaryColumns	= explode(",",$data["view_config"]->summaryColumns);
			$formatColumns	= explode(",",$data["view_config"]->formatColumns);
			
			$cabezera1	= explode(",",$data["view_config"]->nonVisibleColumns);
			$cabezera2	= explode(",",$data["view_config"]->visibleColumns);
			$cabezera3	= array_merge($cabezera1 , $cabezera2);	
			
			
			$agregarLineaSuma 		= false;
			$agregarLineaSumaRow	= $summaryColumns;			
			foreach($agregarLineaSumaRow as $key => $value){
				
				if($value == "true")
				{
					$agregarLineaSumaRow[$key] = 0;					
				}
				else if ($key == 0)
				{
					$agregarLineaSumaRow[$key] = "-TOTAL-";
				}
				else
				{
					$agregarLineaSumaRow[$key] = "-TOTAL-";				
				}
			}
			
			foreach($cabezera3 AS $fieldDisplay){			
				$table = $table."<th>";
				$table = $table.$fieldDisplay;
				$table = $table."</th>";
				
			}
			$table = $table."</tr>";
			$table = $table."</thead>";
			
			
			//crear cuerpo
			$table = $table;
			$body  = "";
			foreach($data["view_data"] AS $row_)
			{		

				$body = $body."<tr>";
				foreach($cabezera3 AS $key => $fieldDisplay)
				{	
					
					$sumaryColumn = "";
					$formatColumn = "";
					
					
					if( count( $summaryColumns ) > 1)
					{
						$sumaryColumn = $summaryColumns[$key];
						if($sumaryColumn == "true")
						{
							$agregarLineaSuma = true;
						}
					}
				
					if( count ($formatColumns ) > 1 )
					{
						$formatColumn = $formatColumns[$key];
					}
					
					
					if($formatColumn != "")
					{
						$body 						= $body."<td style='".$formatColumn."'>";
						$body 						= $body.($row_[$fieldDisplay]);
						$body 						= $body."</td>";
						
						if( $agregarLineaSuma == true && $sumaryColumn == "true")
						$agregarLineaSumaRow[$key] 	= $agregarLineaSumaRow[$key] + $row_[$fieldDisplay];
					}
					else
					{
						$body 						= $body."<td>";
						$body 						= $body.($row_[$fieldDisplay]);
						$body 						= $body."</td>";
						
						
						
						if( $agregarLineaSuma == true && $sumaryColumn == "true")
						$agregarLineaSumaRow[$key] 	= $agregarLineaSumaRow[$key] + $row_[$fieldDisplay];
					}
					
					
					
				}
				$body = $body."</tr>";
			}
			
			//agregar el totale
			$rowSummary = "<tr>";
			if($agregarLineaSuma == true)
			{
				foreach($cabezera3 AS $key => $fieldDisplay)
				{	
					
					
					$formatColumn = "";
					if( count ($formatColumns ) > 1 )
					$formatColumn = $formatColumns[$key];					
					
					
					if($formatColumn != "")
					{
						$rowSummary 						= $rowSummary."<td style='".$formatColumn."'>";
						$rowSummary 						= $rowSummary.($agregarLineaSumaRow[$key]);
						$rowSummary 						= $rowSummary."</td>";
						
						
					}
					else
					{
						$rowSummary 						= $rowSummary."<td>";
						$rowSummary 						= $rowSummary.($agregarLineaSumaRow[$key]);
						$rowSummary 						= $rowSummary."</td>";						
						
					}
					
					
					
				}
			}
			$rowSummary = $rowSummary."</tr>";
			if($rowSummary == "<tr></tr>")
			$rowSummary  = "";
		
			$table = $table."<tbody>".$rowSummary.$body."</tbody>";
		
		$table = $table."</table>";
		
		
		
		
		 
		//Render JS
		$js					= "
		<script>
			var objTable".$idTable.";
			var objRowTable".$idTable.";	
					
			$(document).ready(function() {
						$('#".$idTable."').dataTable({
							'Dom'				: \"<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>\",
							'sPaginationType'	: 'bootstrap',							
							'bJQueryUI'			: false,
							'bAutoWidth'		: false,							
							'iDisplayLength'	: ".$displayLength.",							
							'oLanguage'	: {
								'sSearch'		: '<span>Filtro:</span> _INPUT_ <a class=\'btn btn-warning btnSearchServer\' href=\'#\' style=\'padding:7px 20px;font-size:14px;\'  > BUSCAR </a>',
								//'sLengthMenu'	: '<span>_MENU_ elementos</span>',
								'sLengthMenu'	: '',
								//'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' } 
								'oPaginate' : ''
							}
						});
						
						
						objTable".$idTable." = $('#".$idTable."').dataTable( ); 
		
						$('.dataTables_length select').uniform();
						$('.dataTables_paginate > ul').addClass('pagination');		
						$('.dataTables_paginate > ul').addClass('hidden');	
						$('.dataTables_info').remove();							
						$('.dataTables_filter').find('input').val('".$parameterAjax["{sSearchDB}"]."');
						 
						";
						
						foreach($cabezera1 AS $field ){
							$i		= array_search($field, $cabezera3);
							$temp	= "objTable".$idTable.".fnSetColumnVis(".$i.",false); ";
							$js		= $js.$temp; 
							
						}
						
						$js	= $js."	 					
						$(document).on('click','#".$idTable." tr',function(event){ objRowTable".$idTable." = this; ".$functionSelected."(this,event);});  
						$('#".$idTable."').css('display','table');
			});							
		</script>";
		
		
		$tabletPagination 	= "";
		
		//Calcular la cantidad de páginas
		$pagina_actual  = $parameterAjax["{iDisplayStart}"];
		$total_paginas 	= ceil($parameterAjax["{dataViewDataDiplay}"] / $parameterAjax["{iDisplayLength}"]);
		
		
		//calcular primer pagina
		$inteaccion = 3;
		$i 			= 0;
		for($i = $pagina_actual ; $i > 1 ; $i--)
		{
			$inteaccion--;
			if($inteaccion == 0)
				break;
			
		}
		$indice_inicio 	= $i;
		
		//calcular ultim apagina
		$inteaccion = 3;
		$i 			= 0;
		for($i = $pagina_actual ; $i < $total_paginas ; $i++)
		{
			$inteaccion--;
			if($inteaccion == 0)
				break;
			
		}
		$indice_fin 	= $i;
		
		
		// Generar enlaces de paginación
		$tabletPagination = '
			<br/>
			<div class="row">
				
	
				<div class="col col-lg-12">
					<ul class="pagination" >
		';
		for ($i = $indice_inicio; $i <= $indice_fin; $i++) 
		{
			if ($i == $pagina_actual ) 
			{
				$tabletPagination .= '<li class="active"><a class="btnPaginateServer" style="padding:10px 20px;font-size:14px;" data-paginate="'.$i.'" href="#" >' . $i . '</a></li>';
			} else 
			{
				$tabletPagination .= '<li class="" ><a class="btn btn-warning btnPaginateServer " style="padding:10px 20px;font-size:14px;" data-paginate="'.$i.'"   href="#" >' . $i . '</a></li>';
			}
			
		}
		$tabletPagination = $tabletPagination."
					</ul>
				</div>
			</div>
			<script>
				$(document).ready(function() {
						$(document).on('click','.btnSearchServer',function(e)
						{
							var SearchValue 	= $('.dataTables_filter').find('input').val();
							var SeachPaginate 	= 1;
							
							
							var url_request = '".base_url()."/core_view/showviewbynamepaginate/".$parameterAjax["{componentid}"]."/".$parameterAjax["{fnCallback}"]."/".$parameterAjax["{viewname}"]."/".$parameterAjax["{autoclose}"]."/".$parameterAjax["{filter}"]."/".$parameterAjax["{multiselect}"]."/".$parameterAjax["{urlRedictWhenEmpty}"]."/".$parameterAjax["{sEcho}"]."/'+SeachPaginate+'/".$parameterAjax["{iDisplayLength}"]."/'+encodeURIComponent(SearchValue)+'/';
							//window.open(url_request,'MsgWindow','width=900,height=450');							
							window.location.href = url_request;							 
							window.".$parameterAjax["{fnCallback}"]." = ".$parameterAjax["{fnCallback}"]."; 
							
							
						});
						$(document).on('click','.btnPaginateServer',function(e){
							var SearchValue 	= $('.dataTables_filter').find('input').val();
							var SeachPaginate 	= $(this).data('paginate');
							
							var url_request = '".base_url()."/core_view/showviewbynamepaginate/".$parameterAjax["{componentid}"]."/".$parameterAjax["{fnCallback}"]."/".$parameterAjax["{viewname}"]."/".$parameterAjax["{autoclose}"]."/".$parameterAjax["{filter}"]."/".$parameterAjax["{multiselect}"]."/".$parameterAjax["{urlRedictWhenEmpty}"]."/".$parameterAjax["{sEcho}"]."/'+SeachPaginate+'/".$parameterAjax["{iDisplayLength}"]."/'+encodeURIComponent(SearchValue)+'/';
							//window.open(url_request,'MsgWindow','width=900,height=450');							
							window.location.href = url_request;							 
							window.".$parameterAjax["{fnCallback}"]." = ".$parameterAjax["{fnCallback}"]."; 
						});
				});
			</script>
		";


		
		//Resultado
		$resultGreed		=	$table.$tabletPagination;
		$resultGreedMoreJS	=   $resultGreed.$js;
		return $resultGreedMoreJS;
		
   }
}
?>