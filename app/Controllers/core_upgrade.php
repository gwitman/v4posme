<?php
//posme:2023-02-27
namespace App\Controllers;
class core_upgrade extends _BaseController {
	
	
	function byPop3(){
		
		//https://www.toptal.com/php/building-an-imap-email-client-with-php
		//Estructura del mensaje		
		//SUBJECT:	UPGRADE_COMPANIA|.TIPO_DE_ARCHIVO|RUTA_DESTINO|NOMBRE_DEL_ARCHIVO.EXTENSION
		//BODY:		[[posme_email_start]]
		//BODY: 	TEXTO PLANO DEL ARCHIVO A ACTUALIZAR
		//BODY:		[[posme_email_end]]
		
		$objParameterClienteID		= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_USUARIO",APP_COMPANY);
		$objParameterClienteID		= $objParameterClienteID->value;
		$objParameterServerImap 	= 'gmail';
		$objParameterUserImp    	= EMAIL_APP;
		$objParameterPasswordImp    = EMAIL_APP_PASSWORD;
		$subjectFilter 				= "UPGRADE_".$objParameterClienteID;
		
		$inbox    = imap_open(
			'{imap.'.$objParameterServerImap.'.com:993/imap/ssl}INBOX', 
			$objParameterUserImp, $objParameterPasswordImp
		);
		$ids      = imap_search($inbox, 'UNSEEN SUBJECT ' . $subjectFilter);
		
		
		
		
		if(helper_RequestGetValue($ids,0) != 0)
		{
			foreach($ids as $email_number) 
			{

				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message = imap_fetchbody($inbox,$email_number,2);
				$structure = imap_fetchstructure($inbox,$email_number);
		


				$attachments = array();
				if(isset($structure->parts) && count($structure->parts)) {
					for($i = 0; $i < count($structure->parts); $i++) {
						$attachments[$i] = array(
							'is_attachment' => false,
							'filename' => '',
							'name' => '',
							'attachment' => ''
						);

						if($structure->parts[$i]->ifdparameters) {
							foreach($structure->parts[$i]->dparameters as $object) {
								if(strtolower($object->attribute) == 'filename') {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
								}
							}
						}

						if($structure->parts[$i]->ifparameters) {
							foreach($structure->parts[$i]->parameters as $object) {
								if(strtolower($object->attribute) == 'name') {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['name'] = $object->value;
								}
							}
						}

						if($attachments[$i]['is_attachment']) {
							$attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
							if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
								$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
							}
							elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
								$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
							}
						} 
					}
				}


				if(count($attachments)!=0){
					foreach($attachments as $at){
						if($at['is_attachment']==1){
							
							$subject = $overview[0]->subject;
							$type	 = explode("->",$subject)[1];
							$path	 = explode("->",$subject)[2];
							$file	 = explode("->",$subject)[3];
							$texto	 =  $at['attachment'];
							$this->executeAttachment($type,$path,$file,$texto);
							
						
						}
					}
				}

			}
		
			imap_close($inbox);
		}
		
		
	}
		
	function executeAttachment($type,$path,$file,$texto)
	{
			$urllocal 	= PATH_FILE_OF_APP_SYSTEM.$path.$file;
			
			//sobre escribor php
			if(strtoupper($type) == strtoupper(".php")){
				echo "actualizado file:</br>".$urllocal."</br>";
				file_put_contents($urllocal, $texto);				
			}
			
			//ejecutar sql
			if(strtoupper($type) == strtoupper(".sql"))
			{
				echo "ejecutando file:</br>".$urllocal."</br>";
				$query = $texto;
				$query = explode(";",$query);
				
				if(count($query) == 0)
				return;
			
				for($iq = 0 ; $iq < count($query); $iq++){
					$subquery = $query[$iq]."";		
					
					if(empty($subquery)){
					}
					else{					
						$subquery = $subquery.";";
						echo "</br>".$urlServerFull."-->query execute:</br>".$subquery."<br/>";
						$this->Bd_Model->simpleQuery($subquery);
					}
				}
				
			}
	}
	
}
