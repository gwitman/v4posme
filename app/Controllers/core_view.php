<?php
//posme:2023-02-27
namespace App\Controllers;
class core_view extends _BaseController {
	//Constructor ...
    
    //BUSCAR UNA VISTA POR NOMBRE
	function showviewbyname($componentid="",$fnCallback="",$viewname="",$autoclose="",$filter=""){
		
		$componentid = helper_SegmentsByIndex($this->uri->getSegments(),1,$componentid);	
		$fnCallback = helper_SegmentsByIndex($this->uri->getSegments(),2,$fnCallback);	
		$viewname = helper_SegmentsByIndex($this->uri->getSegments(),3,$viewname);	
		$autoclose = helper_SegmentsByIndex($this->uri->getSegments(),4,$autoclose);	
		$filter = helper_SegmentsByIndex($this->uri->getSegments(),5,$filter);	
		
		try{  
		  
			
  
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$viewname 					= urldecode($viewname);
			$filter 					= urldecode($filter);
			$result 					= $this->core_web_tools->formatParameter($filter);	
			
			
			
			
			//$this->output->cache(100);
			//$this->load->driver('cache',array('adapter'=>'apc','backup'=> 'file'));
			//$nameCache = "SELECCIONAR_ITEM_BILLING";
			//$objCache  = $this->cache->get($nameCache);
			//
			
			
			//Guardar la info en cache
			//if($objCache && $nameCache ==  $viewname){
			//	echo $objCache;
			//	exit;
			//}
			if($result)
			$parameter 					= array_merge($parameter,$result);
			
			$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter); 			
			
			$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			
			$dataView["fnCallback"] 	= $fnCallback;
			$dataView["viewname"] 		= $viewname;
			$dataView["autoclose"] 		= $autoclose;
			
			
			//
			//
			//Guardar la info en cache
			//if($nameCache ==  $viewname){
			//	$this->cache->save($nameCache,$dataSession,100);
			//}
			//Renderizar Resultado
			$dataSession["message"]	= ""; 
			$dataSession["head"]	= /*--inicio view*/ view('core_view/choose_view_serch_head');//--finview 
			$dataSession["body"]	= $dataViewRender;
			$dataSession["script"]	= /*--inicio view*/ view('core_view/choose_view_serch_script',$dataView);//--finview 
			return view("core_masterpage/default_widgetchoose",$dataSession);//--finview-r	
			
			//Guardar la info en cache
			//if(!$objCache && $nameCache ==  $viewname){
			//	$this->cache->save($nameCache,$dataSession,100);
			//}
				
		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
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