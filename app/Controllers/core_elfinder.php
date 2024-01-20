<?php
//posme:2023-02-27
namespace App\Controllers;

//use App\Libraries\core_web_elfinder\elFinderVolumeDriver;
//use App\Libraries\core_web_elfinder\elFinderVolumeLocalFileSystem;
//use App\Libraries\core_web_elfinder\elFinderConnector;
//use App\Libraries\core_web_elfinder\elFinder;

$root = __DIR__.'/../';
include_once $root.'Libraries/core_web_elfinder/elFinderConnector.php';
include_once $root.'Libraries/core_web_elfinder/elFinder.php';
include_once $root.'Libraries/core_web_elfinder/elFinderVolumeDriver.php';
include_once $root.'Libraries/core_web_elfinder/elFinderVolumeLocalFileSystem.php';

class core_elfinder extends _BaseController {

    public function index()
    {
		try{ 
		
			////AUTENTICADO
			//if(!$this->core_web_authentication->isAuthenticated())
			//throw new \Exception(USER_NOT_AUTENTICATED);
			//$dataSession		= $this->session->get();			
			//
			//
			////PERMISO SOBRE LA FUNCTION
			//if(APP_NEED_AUTHENTICATION == true){								
			//			$permited = false;
			//			$permited = $this->core_web_permission->urlPermited(get_class($this),"show",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			//			
			//			if(!$permited)
			//			throw new \Exception(NOT_ACCESS_CONTROL);
			//			
			//}	
			
			
			$data["companyID"]			= APP_COMPANY;
			$data["componentID"]		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
			$data["componentItemID"]	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentItemID");//--finuri		
			//Renderizar Resultado
			return view('core_elfinder/default_view',$data);//--finview-r
			
		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
    } 
	
	function createFolder()
    {
		try{ 
		
			$companyID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$componentID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri		
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri		
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri		
		
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID;		
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755);
				chmod($documentoPath, 0755);
			}
			
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID;		
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755);
				chmod($documentoPath, 0755);
			}
			
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID."/component_item_".$transactionMasterID;
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755);
				chmod($documentoPath, 0755);
			}
			
			
			
		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
    } 
	
	
    public function load_elfinder()
    {
		////AUTENTICADO
		//if(!$this->core_web_authentication->isAuthenticated())
		//throw new \Exception(USER_NOT_AUTENTICATED);
		//$dataSession		= $this->session->get();
		//
		////PERMISO SOBRE LA FUNCTION
		//if(APP_NEED_AUTHENTICATION == true){								
		//			$permited = false;
		//			$permited = $this->core_web_permission->urlPermited(get_class($this),"loader",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
		//			
		//			if(!$permited)
		//			throw new \Exception(NOT_ACCESS_CONTROL);	
		//											
		//}	
			
		
		//$companyID			= $dataSession["user"]->companyID;
		$companyID			= APP_COMPANY;		
		$componentID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
		$componentItemID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentItemID");//--finuri		
		$pathDocument       = PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID."/component_item_".$componentItemID;
        
        $opts = array(			
			'roots' => array(
			  array( 
				'driver' 			=> 'LocalFileSystem', 
			    'path'   			=> realpath($pathDocument), 				
				'alias'				=> "Documentos",
				'tmb' 				=> false,
				'defaults' => array(
				   'read'  => true,
				   'write' => true,
				   'rm'    => true
				),
				'attributes' => array(
						array(
							'pattern'	=> '/.tmb/',//Carpeta Temporal de Imagenes, El control siempre la crea , Con este atributo se oculta
							'hidden'	=> true
						),
						array( 
							'pattern' => '/plantilla_de_tasa_de_cambio.csv/',//Archivo que Contiene la Plantilla de Excel para Importar la Tasa de Cambio al Sistema
							'read' => true,
							'write' => false,
							'hidden' => false,
							'locked' => true
						)				
				)			
			  ) 
			)
        );
		
	
	
        $elFinder 	 = new \elFinder($opts);
		$connectorF  = new \elFinderConnector($elFinder);
		$connectorF->run();
		
		
    }
	
	public function upload_elfinder(){		
		
		////AUTENTICADO
		//if(!$this->core_web_authentication->isAuthenticated())
		//throw new \Exception(USER_NOT_AUTENTICATED);
		//$dataSession		= $this->session->get();
		//
		////PERMISO SOBRE LA FUNCTION
		//if(APP_NEED_AUTHENTICATION == true){								
		//			$permited = false;
		//			$permited = $this->core_web_permission->urlPermited(get_class($this),"upload",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
		//			
		//			if(!$permited)
		//			throw new \Exception(NOT_ACCESS_CONTROL);	
		//											
		//}	
			
		
		//$companyID			= $dataSession["user"]->companyID;
		$companyID			= APP_COMPANY;
		$componentID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
		$componentItemID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentItemID");//--finuri
		
		
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//Settings		
		$targetDir 			= PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID."/component_item_".$componentItemID;
		$cleanupTargetDir 	= true; // Remove old files
		$maxFileAge 		= 5 * 3600; // Temp file age in seconds
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		// Uncomment this one to fake upload time
		// usleep(5000);
		// Get parameters
		$chunk 		= isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks 	= isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName 	= isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
		// Clean the fileName for security reasons
		// 
		$fileName 	= preg_replace('/[^\w\._]+/', '_', $fileName);
		
		
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext 		= strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);
			$count 		= 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;
			$fileName 	= $fileName_a . '_' . $count . $fileName_b;
		}
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		// Create target dir
		if (!file_exists($targetDir))
			@mkdir($targetDir);
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
				while (($file = readdir($dir)) !== false) {
					$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
						@unlink($tmpfilePath);
					}
				}
				closedir($dir);
			} else {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
		}	
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = @fopen($_FILES['file']['tmp_name'], "rb");
					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					@fclose($in);
					@fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			// Open temp file
			$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = @fopen("php://input", "rb");
				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				@fclose($in);
				@fclose($out);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}		
}
?>