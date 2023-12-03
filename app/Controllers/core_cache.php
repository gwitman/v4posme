<?php
//posme:2023-02-27
namespace App\Controllers;
class core_cache extends _BaseController {
	//Constructor ...
    
    //BUSCAR UNA VISTA POR NOMBRE
	function delete_by_name($name="")
	{
		$name = helper_SegmentsByIndex($this->uri->getSegments(),1,$name);	
		if( $this->cache->get($name) )
		{			
			$this->cache->delete($name);		
		}		
	}
	
	
}
?>