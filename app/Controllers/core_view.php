<?php
//posme:2023-02-27
namespace App\Controllers;
class core_view extends _BaseController {
	//Constructor ...
    
    //BUSCAR UNA VISTA POR NOMBRE
	function showviewbyname(
		$componentid="",
		$fnCallback="",
		$viewname="",
		$autoclose="",
		$filter="",
		$multiselect = "" ,
		$urlRedictWhenEmpty = ""
	)
	{
		
		$componentid = helper_SegmentsByIndex($this->uri->getSegments(),1,$componentid);	
		$fnCallback = helper_SegmentsByIndex($this->uri->getSegments(),2,$fnCallback);	
		$viewname = helper_SegmentsByIndex($this->uri->getSegments(),3,$viewname);	
		$autoclose = helper_SegmentsByIndex($this->uri->getSegments(),4,$autoclose);	
		$filter = helper_SegmentsByIndex($this->uri->getSegments(),5,$filter);	
		$multiselect = helper_SegmentsByIndex($this->uri->getSegments(),6,$multiselect);	
		$urlRedictWhenEmpty = helper_SegmentsByIndex($this->uri->getSegments(),7,$urlRedictWhenEmpty);	
		
		
		try
		{  
		  
			
  
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$viewname 					= urldecode($viewname);
			$filter 					= urldecode($filter);
			$result 					= $this->core_web_tools->formatParameter($filter);	
			
			
			
			$objParameterCacheInView 				= $this->core_web_parameter->getParameter("CORE_CACHE_IN_VIEW",$this->session->get('user')->companyID);
			$objParameterCacheInView 				= $objParameterCacheInView->value;
			$view_SELECCIONAR_ITEM_TO_PROVEEDOR 	= "SELECCIONAR_ITEM_TO_PROVIDER";
			$objCache  								= $this->cache->get($viewname);
			
			//Obtener la informacion de la cache
			if($objCache && $view_SELECCIONAR_ITEM_TO_PROVEEDOR ==  $viewname && $objParameterCacheInView == "true")
			{
				return $objCache;
			}
			
			if($result)
			$parameter 					= array_merge($parameter,$result);
			
			$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter); 			
			
			if($multiselect == "true")
			{
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRowMultiSelect");
			}
			else
			{
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			
			$dataView["fnCallback"] 			= $fnCallback;
			$dataView["viewname"] 				= $viewname;
			$dataView["autoclose"] 				= $autoclose;
			$dataView["multiselect"] 			= $multiselect;
			$dataView["urlRedictWhenEmpty"] 	= str_replace("__","/",$urlRedictWhenEmpty);
			log_message("error",print_r($dataView["urlRedictWhenEmpty"],true));
			
			
			//Renderizar Resultado
			$dataSession["message"]	= ""; 
			$dataSession["head"]	= /*--inicio view*/ view('core_view/choose_view_serch_head',$dataView);//--finview 
			$dataSession["body"]	= $dataViewRender;
			$dataSession["script"]	= /*--inicio view*/ view('core_view/choose_view_serch_script',$dataView);//--finview 
			$resultView 			= view("core_masterpage/default_widgetchoose",$dataSession);//--finview-r	
			
			//Guardar la info en cache
			$this->cache->save($viewname,$resultView,100);			
			return $resultView;
				
		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
	}
	
	//BUSCAR UNA VISTA POR NOMBRE
	function showviewbynamepaginate(
		$componentid="",
		$fnCallback="",
		$viewname="",
		$autoclose="",
		$filter="",
		$multiselect = "" ,
		$urlRedictWhenEmpty = "",
		$sEcho = "",
		$iDisplayStart = "",
		$iDisplayLength = "",
		$sSearch = ""
	)
	{
		
		$componentid 						= helper_SegmentsByIndex($this->uri->getSegments(),1,$componentid);	
		$fnCallback 						= helper_SegmentsByIndex($this->uri->getSegments(),2,$fnCallback);	
		$viewname 							= helper_SegmentsByIndex($this->uri->getSegments(),3,$viewname);	
		$autoclose 							= helper_SegmentsByIndex($this->uri->getSegments(),4,$autoclose);	
		$filter 							= helper_SegmentsByIndex($this->uri->getSegments(),5,$filter);	
		$multiselect 						= helper_SegmentsByIndex($this->uri->getSegments(),6,$multiselect);	
		$urlRedictWhenEmpty 				= helper_SegmentsByIndex($this->uri->getSegments(),7,$urlRedictWhenEmpty);	
		
		$sEcho 								= helper_SegmentsByIndex($this->uri->getSegments(),8,$sEcho);	
		$iDisplayStart 						= helper_SegmentsByIndex($this->uri->getSegments(),9,$iDisplayStart);	
		$iDisplayLength 					= helper_SegmentsByIndex($this->uri->getSegments(),10,$iDisplayLength);	
		$sSearch 							= helper_SegmentsByIndex($this->uri->getSegments(),11,$sSearch);	
		
		
		$parameter["{componentid}"]					= $componentid;
		$parameter["{fnCallback}"]					= $fnCallback;
		$parameter["{viewname}"]					= $viewname;
		$parameter["{autoclose}"]					= $autoclose;
		$parameter["{filter}"]						= $filter;
		$parameter["{multiselect}"]					= $multiselect;
		$parameter["{urlRedictWhenEmpty}"]			= $urlRedictWhenEmpty;	
		$parameter["{sEcho}"]						= $sEcho;
		$parameter["{iDisplayStart}"]				= $iDisplayStart;
		$parameter["{iDisplayStartDB}"]				= $iDisplayStart == 1 ? 0 : (($iDisplayStart-1) * $iDisplayLength) - 1 ;
		$parameter["{iDisplayLength}"]				= $iDisplayLength;
		$parameter["{sSearchDB}"]					= urldecode($sSearch);
		$parameter["{sSearch}"]						= $sSearch;
			
			
		try
		{  
		  
			
  
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$parameter["{useMobile}"]	= $this->session->get('user')->useMobile;
			$parameter["{fnCallback}"] 	= $fnCallback;
			$viewname 					= urldecode($viewname);
			$filter 					= urldecode($filter);
			$result 					= $this->core_web_tools->formatParameter($filter);	
			
			
			
			
			//Parsear Parametros
			if($result)
			$parameter 	= array_merge($parameter,$result);
			
			
			
			$dataViewData				= 
										$this->session->get('user')->useMobile == 0 ? 
										$this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter): 				
										$this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname."_MOBILE",CALLERID_SEARCH,null,$parameter);
											
			$dataViewDataTotal			= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname."_TOTAL",CALLERID_SEARCH,null,$parameter); 				
			$dataViewDataDiplay			= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname."_DISPLAY",CALLERID_SEARCH,null,$parameter); 				
			
			$dataViewDataTotal 			= $dataViewDataTotal["view_data"][0]["itemID"];
			$dataViewDataDiplay 		= $dataViewDataDiplay["view_data"][0]["itemID"];
			$parameter["{dataViewDataTotal}"] 	= $dataViewDataTotal;
			$parameter["{dataViewDataDiplay}"] 	= $dataViewDataDiplay;
			
			
			if($multiselect == "true")
			{
				$dataViewRender				= $this->core_web_view->renderGreedPaginate($dataViewData,'ListView',"fnTableSelectedRowMultiSelect",$iDisplayLength,true,$parameter);
			}
			else
			{
				$dataViewRender				= $this->core_web_view->renderGreedPaginate($dataViewData,'ListView',"fnTableSelectedRow",$iDisplayLength,true,$parameter);
			}
			
			$dataView["useMobile"] 				= $this->session->get('user')->useMobile;
			$dataView["fnCallback"] 			= $fnCallback;
			$dataView["viewname"] 				= $viewname;
			$dataView["autoclose"] 				= $autoclose;
			$dataView["multiselect"] 			= $multiselect;
			$dataView["urlRedictWhenEmpty"] 	= str_replace("__","/",$urlRedictWhenEmpty);
				
			//Renderizar Resultado
			$dataSession["message"]	= ""; 
			$dataSession["head"]	= /*--inicio view*/ view('core_view/choose_view_serch_head',$dataView);//--finview 
			$dataSession["body"]	= $dataViewRender;
			$dataSession["script"]	= /*--inicio view*/ view('core_view/choose_view_serch_script',$dataView);//--finview 
			$resultView 			= view("core_masterpage/default_widgetchoose",$dataSession);//--finview-r	
			
			
			return $resultView;
				
		}
		catch(\Exception $ex){
			 exit($ex->getMessage());
		}
	}
	
	//INDEX
	////////////////////////////
	function chooseview($componentIDParameter=""){ 
	
		$componentIDParameter = helper_SegmentsByIndex($this->uri->getSegments(),1,$componentIDParameter);	
		try{  
		
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
		
			//Obtener grid 
			//Obtener el Componente de CompanyComponentItemDataView
			
						
			$parameter["{componentID}"]	= $componentIDParameter;
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$parameter["{callerID}"]	= CALLERID_LIST;
			$componentSearch			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_component_item_dataview"); 				 
			$dataViewData				= $this->core_web_view->getView($this->session->get('user'),$componentSearch->componentID,CALLERID_LIST,null,$parameter); 			
			$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			  
			  
			//Renderizar Resultado
			$dataSession["message"]	= ""; 
			$dataSession["head"]	= /*--inicio view*/ view('core_view/choose_view_head');//--finview 
			$dataSession["body"]	= $dataViewRender;
			$dataSession["script"]	= /*--inicio view*/ view('core_view/choose_view_script');//--finview 
			return view("core_masterpage/default_widgetchoose",$dataSession);//--finview-r
				
		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
	}
}
?>