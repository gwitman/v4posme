<?php
//posme:2023-02-27
//posme:2026-11-09 Actualizado a elFinder 2.1.70 (antes 2.0 rc1) y endurecida la seguridad de subida de archivos
namespace App\Controllers;

$root = __DIR__.'/../';
include_once $root.'Libraries/core_web_elfinder_21/autoload.php';

class core_elfinder extends _BaseController {

	/**
	 * Nombres de archivo NO permitidos: ejecutables del servidor, scripts y archivos
	 * que el navegador interpreta (html/svg/js). La carpeta de documentos está dentro
	 * de public/, así que un .php subido ahí se podría ejecutar.
	 */
	const ELFINDER_EXTENSION_DENY = '/\.(php\d*|phtml|phar|pht|phps|inc|cgi|pl|py|rb|asp|aspx|ashx|asmx|jsp|jspx|exe|com|bat|cmd|msi|sh|ps1|vbs|vbe|wsf|scr|dll|htaccess|htpasswd|ini|user\.ini|html?|xhtml|shtml|svgz?|js|mjs)$/i';

	/**
	 * Tipos MIME que se pueden subir con elFinder (todo lo demás se rechaza).
	 */
	const ELFINDER_MIME_ALLOW = array(
		'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
		'application/pdf',
		'text/plain', 'text/csv', 'text/xml', 'application/xml',
		'application/msword',
		'application/vnd.ms-excel',
		'application/vnd.ms-powerpoint',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'application/vnd.oasis.opendocument.text',
		'application/vnd.oasis.opendocument.spreadsheet',
		'application/zip', 'application/x-zip-compressed'
	);

	/**
	 * Pantalla del administrador de archivos de un documento.
	 * La URL (componentID/X/componentItemID/Y) la usan más de 50 pantallas; no cambiarla.
	 */
    public function index()
    {
		try{

			$componentID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
			$componentItemID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentItemID");//--finuri

			if(!$this->isIdValid($componentID) || !$this->isIdValid($componentItemID))
			throw new \Exception("Parámetros inválidos para el administrador de archivos.");

			$data["companyID"]			= $this->getCompanyIDElFinder();
			$data["componentID"]		= $componentID;
			$data["componentItemID"]	= $componentItemID;

			//Renderizar Resultado
			return view('core_elfinder/default_view',$data);//--finview-r

		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
    }

	/**
	 * Crea la carpeta del documento y, opcionalmente, guarda el archivo txtFileDocument.
	 * Lo llaman por cURL (sin sesión) app_cxp_expenses, app_purchase_* cuando hay CORE_FILE_SERVER.
	 */
	function createFolder()
    {
		try{

			$companyID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$componentID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
			$file						=  $this->request->getFile('txtFileDocument');

			//Evitar rutas como ../ en los segmentos
			if(!$this->isIdValid($companyID) || !$this->isIdValid($componentID) || !$this->isIdValid($transactionMasterID))
			throw new \Exception("Parámetros inválidos para crear la carpeta.");

			$documentoPath = $this->getPathDocument($companyID,$componentID,$transactionMasterID,true);

			if($file && $file->isValid() && $file->getSize() > 0 )
			{
					$fileNameOriginal				= basename($file->getClientName());
					if(!$this->isFileNameAllowed($fileNameOriginal))
					throw new \Exception("Tipo de archivo no permitido: ".$fileNameOriginal);

					$filePath 						= $file->store();
					$filePathSource 				=  PATH_FILE_OF_UPLOAD_WRITE."/".$filePath;
					$filePathDetinationOriginal 	=  $documentoPath."/".$fileNameOriginal;
					copy($filePathSource,$filePathDetinationOriginal);
					unlink($filePathSource);
			}

		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
    }

	/**
	 * Conector de elFinder 2.1 (lo invoca el JS de la pantalla index).
	 */
    public function load_elfinder()
    {
		$componentID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
		$componentItemID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentItemID");//--finuri

		if(!$this->isIdValid($componentID) || !$this->isIdValid($componentItemID))
		{
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(array("error" => "Parámetros inválidos para el administrador de archivos."));
			exit;
		}

		$companyID			= $this->getCompanyIDElFinder();
		$pathDocument       = $this->getPathDocument($companyID,$componentID,$componentItemID,true);
		$urlDocument		= rtrim(base_url(),'/')."/resource/file_company/company_".$companyID."/component_".$componentID."/component_item_".$componentItemID."/";
		$extensionDeny		= self::ELFINDER_EXTENSION_DENY;

        $opts = array(
			//Sesión propia sobre la de CodeIgniter (guarda el token CSRF de elFinder)
			'session'	=> new \App\Libraries\core_web_elfinder_session(),
			'roots' 	=> array(
			  array(
				'driver' 			=> 'LocalFileSystem',
			    'path'   			=> $pathDocument,
				'URL'				=> $urlDocument,
				'alias'				=> "Documentos",
				'tmbPath'			=> '.tmb',
				'tmbURL'			=> $urlDocument.'.tmb/',
				'uploadDeny'		=> array('all'),
				'uploadAllow'		=> self::ELFINDER_MIME_ALLOW,
				'uploadOrder'		=> array('deny','allow'),
				'uploadMaxSize'		=> '20M',
				//Rechaza nombres ocultos (.algo) y extensiones peligrosas al subir, renombrar, crear o pegar
				'acceptedName'		=> function($name) use ($extensionDeny) {
					return $name !== '' && $name[0] !== '.' && !preg_match($extensionDeny, $name);
				},
				'acceptedDirname'	=> '/^[^\.].*$/',
				//Comandos deshabilitados: comprimir/descomprimir (podrían extraer .php), editar en línea
				//(carga editores externos), permisos, unidades de red, ocultar y crear archivo vacío
				'disabled'			=> array('archive','extract','edit','chmod','netmount','hide','mkfile'),
				'defaults' => array(
				   'read'  => true,
				   'write' => true,
				   'rm'    => true
				),
				'attributes' => array(
						array(
							'pattern'	=> '#(^|/)\.tmb$#',//Carpeta de miniaturas, el control siempre la crea; se oculta
							'read'		=> true,
							'write'		=> true,
							'hidden'	=> true,
							'locked'	=> true
						),
						array(
							'pattern' 	=> '/plantilla_de_tasa_de_cambio\.csv$/',//Plantilla de Excel para importar la tasa de cambio al sistema
							'read' 		=> true,
							'write' 	=> false,
							'hidden' 	=> false,
							'locked' 	=> true
						),
						array(
							'pattern'	=> $extensionDeny,//Archivos peligrosos que ya existieran en la carpeta: ocultos y bloqueados
							'read'		=> false,
							'write'		=> false,
							'hidden'	=> true,
							'locked'	=> true
						)
				)
			  )
			)
        );

        $elFinder 	 = new \elFinder($opts);
		$connectorF  = new \elFinderConnector($elFinder);
		$connectorF->run();
    }

	/**
	 * Subida con Plupload de la versión anterior de la pantalla. Se conserva por compatibilidad,
	 * validando parámetros y extensión del archivo.
	 */
	public function upload_elfinder(){

		$companyID			= $this->getCompanyIDElFinder();
		$componentID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri
		$componentItemID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentItemID");//--finuri

		if(!$this->isIdValid($componentID) || !$this->isIdValid($componentItemID))
		die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "Invalid parameters."}, "id" : "id"}');

		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//Settings
		$targetDir 			= $this->getPathDocument($companyID,$componentID,$componentItemID,true);
		$cleanupTargetDir 	= true; // Remove old files
		$maxFileAge 		= 5 * 3600; // Temp file age in seconds
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		// Get parameters
		$chunk 		= isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks 	= isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName 	= isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
		// Clean the fileName for security reasons
		$fileName 	= preg_replace('/[^\w\._]+/', '_', $fileName);

		if(!$this->isFileNameAllowed($fileName))
		die('{"jsonrpc" : "2.0", "error" : {"code": 105, "message": "File type not allowed."}, "id" : "id"}');

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
		$contentType = "";
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

	/**
	 * Compañía de la carpeta de documentos: la del usuario si hay sesión; si no
	 * (servidor de archivos remoto CORE_FILE_SERVER), APP_COMPANY como antes.
	 *
	 * @return int
	 */
	private function getCompanyIDElFinder()
	{
		if($this->core_web_authentication->isAuthenticated())
		{
			$dataSession = $this->session->get();
			if(isset($dataSession["user"]) && is_object($dataSession["user"]) && isset($dataSession["user"]->companyID))
			return $dataSession["user"]->companyID;
		}
		return APP_COMPANY;
	}

	/**
	 * Indica si un segmento de la URL es un ID numérico (evita rutas tipo ../).
	 *
	 * @param mixed $value Valor del segmento
	 * @return bool
	 */
	private function isIdValid($value)
	{
		return is_numeric($value) && ctype_digit((string)$value);
	}

	/**
	 * Indica si el nombre de archivo se puede guardar en la carpeta de documentos.
	 *
	 * @param string $fileName Nombre del archivo
	 * @return bool
	 */
	private function isFileNameAllowed($fileName)
	{
		return $fileName !== '' && $fileName[0] !== '.' && !preg_match(self::ELFINDER_EXTENSION_DENY, $fileName);
	}

	/**
	 * Devuelve la ruta física company_X/component_Y/component_item_Z y opcionalmente la crea.
	 *
	 * @param int  $companyID       Compañía
	 * @param int  $componentID     Componente
	 * @param int  $componentItemID Registro (transactionMasterID, etc.)
	 * @param bool $create          Crear la carpeta si no existe
	 * @return string
	 */
	private function getPathDocument($companyID,$componentID,$componentItemID,$create)
	{
		$path = PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID."/component_item_".$componentItemID;
		if ($create && !file_exists($path))
		{
			mkdir($path, 0755, true);
			@chmod($path, 0755);
		}
		return $path;
	}
}
?>
