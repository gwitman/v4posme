<?php
//posme:2023-02-27
//Pantalla para ver el listado de notificaciones
//Pantalla para ver el listado de notificaiones en sistema
namespace App\Controllers;
class core_caching_image extends _BaseController {
	
    
	function get()
	{
		
		 // Ruta de la imagen
        $filepath = PATH_FILE_OF_APP_ROOT ."/". $this->request->getGet('fileName');
		
		
        // Verifica si el archivo existe
        if (file_exists($filepath)) 
		{
			//Configura los encabezados para el caché
            header('Cache-Control: max-age=31536000, public'); // 1 año de caché
            header('Expires: '. gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT'); // Fecha de expiración
            
			
			// Configura los encabezados apropiados para la descarga de una imagen
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
			//header('Expires: 0');
			//header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			// Limpia el búfer de salida
			ob_clean();
			flush();
			// Envía el archivo al navegador
			readfile($filepath);
			
				
        } else 
		{
            // Muestra un error 404 si la imagen no existe
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
	
	}
	
	
}
?>