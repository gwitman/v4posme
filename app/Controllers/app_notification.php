<?php
//posme:2023-02-27
namespace App\Controllers;
class app_notification extends _BaseController {
	
    
	function currentNotification(){
		
		
		
		
		
		
		
		
		
		$tagName		= "NOTIFICAR OBLIGACION";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		
		//Recorrer Empresas
		if($objListCompany)
		foreach($objListCompany as $i){
			$objListItem		= $this->Remember_Model->getNotificationCompany($i->companyID);			
			//Recorrer las Notificaciones
			if($objListItem){				
				foreach($objListItem as $noti){
					$hoy_			= date_format(date_create(),"Y-m-d");
					$lastNoti 		= date_format(date_create($noti->lastNotificationOn),"Y-m-d");
					
					//Recorrer desde la ultima notificacion, hasta la fecha de hoy
					while ($lastNoti <= $hoy_){						
						//Validar si Ya esta procesado el Dia.
						
						$objListItemDetail		= $this->Remember_Model->getProcessNotification($noti->rememberID,$lastNoti);	
						
						
						if($objListItemDetail)
						if($objListItemDetail->diaProcesado == $noti->day)
						{
	
							//echo $noti;
							//echo $objListItemDetail;
							
							$item 					= $objListItemDetail;
							$mensaje				= "";
							$mensaje				.= "<span class='badge badge-important'>OBLIGACION</span>".$item->title;
							$mensaje				.= " => ".$item->description." => ".$item->Fecha." => <span class='badge badge-important'>ATRAZO</span>";
							
							//Ver si el mensaje ya existe para el administrador
							$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
							$data				= null;
							$errorID 			= 0;
							//tag con notificacion
							if($objTag->sendNotificationApp){
								$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
								//Lista de Usuarios
								if ($objListUsuario)
								foreach($objListUsuario as $usuario){
									
									$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
									if(!$objErrorUser){
										$data					= null;
										$data["tagID"]			= $objTag->tagID;
										$data["notificated"]	= "notificar obligacion";
										$data["message"]		= $mensaje;
										$data["isActive"]		= 1;
										$data["userID"]			= $usuario->userID;
										$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
										$this->Error_Model->insert_app_posme($data);
									}
								}
							}
							
							if(!$objError){
								$data				= null;
								$data["notificated"]= "notificar obligacion";
								$data["tagID"]		= $objTag->tagID;
								$data["message"]	= $mensaje;
								$data["isActive"]	= 1;
								$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
								$errorID			= $this->Error_Model->insert_app_posme($data);
							}
							else 
								$errorID 			= $objError->errorID;
							
							//tag con correo
							if($objTag->sendEmail){
								
								$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
								if ($objListUsuario)
								foreach($objListUsuario as $usuario){
									
									$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
									if(!$objNotificationUser){
										$data						= null;
										$data["errorID"]			= $errorID;
										$data["from"]				= EMAIL_APP;
										$data["to"]					= $usuario->email;
										$data["subject"]			= "notificar obligacion";
										$data["message"]			= $mensaje;
										$data["summary"]			= "notificar obligacion";
										$data["title"]				= "notificar obligacion";
										$data["tagID"]				= $objTag->tagID;
										$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
										$data["isActive"]			= 1;
										$this->Notification_Model->insert_app_posme($data);
									}
								}
							}
						}
						//Actualizar Base de Datos
						$dataRemember						= NULL;
						$dataRemember["lastNotificationOn"]	= $lastNoti;
						$this->Remember_Model->update_app_posme($noti->rememberID,$dataRemember);	
						//Siguiente Fecha
						$lastNoti = date_format(date_add(date_create($lastNoti),date_interval_create_from_date_string("1 days")),"Y-m-d");
					}
				}
			}
		}
		
	}
	
	function sendEmail(){
		//Cargar Libreria
		
		
		
		//Obtener lista de email
		$objListNotification = $this->Notification_Model->get_rows(20);
		if($objListNotification)
		foreach($objListNotification as $i){
			
			//Enviar Email			
			$this->email->setFrom(EMAIL_APP, HELLOW);
			$this->email->setTo($i->to);
			$this->email->setSubject($i->subject);
			$this->email->setMessage($i->message);
			$data["sendOn"]	= date_format(date_create(),"Y-m-d H:i:s");
			$this->Notification_Model->update_app_posme($i->notificationID,$data);
		}
		
	}
	
	function fillTipoCambio(){
		
		
		
		
		
		
		
		
		$tagName		= "NOTIFICAR TIPO DE CAMBIO";
		$date_			= date_format(date_create(),"Y-m-d");
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if($objListCompany)
		foreach($objListCompany as $i){
				$defaultCurrencyID	= $this->core_web_currency->getCurrencyDefault($i->companyID)->currencyID;
				$reportCurrencyID	= $this->core_web_currency->getCurrencyExternal($i->companyID)->currencyID;
				
				try {
					$exchangeRate		= $this->core_web_currency->getRatio($i->companyID,$date_,1,$reportCurrencyID,$defaultCurrencyID);					
				} catch (\Exception $e) {
					$mensaje			= $e->getMessage();	
					
					$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
					$data				= null;
					$errorID 			= 0;
					if(!$objError){
						$data["notificated"]= "tipo de cambio...";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);
					}
					else 
						$errorID 			= $objError->errorID;
					
					//tag con correo
					if($objTag->sendEmail){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
							if(!$objNotificationUser){
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "TIPO DE CAMBIO";
								$data["message"]			= $mensaje;
								$data["summary"]			= "TIPO DE CAMBIO NO INGRESADO";
								$data["title"]				= "TIPO DE CAMBIO";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert_app_posme($data);
							}
						}
					}
					
					//tag con notificacion
					if($objTag->sendNotificationApp){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
							if(!$objErrorUser){
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "tasa de cambio";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
								$this->Error_Model->insert_app_posme($data);
							}
						}
					}
					
				}
		}
		
	}
	
	function fillInventarioMinimo(){
		
		
		
		
		
		
		
		
		
		$tagName		= "NOTIFICAR INVENTARIO MINIMO";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if($objListCompany)
		foreach($objListCompany as $i){
			$objListItem		= $this->Itemwarehouse_Model->get_rowLowMinimus($i->companyID);
			if($objListItem){
				foreach($objListItem as $item){
					$mensaje			 = "";
					$mensaje			.= "<span class='badge badge-warning'>PRODUCTO</span>:".$item->itemNumber." ".$item->itemName."<br/>";
					$mensaje			.= "<span class='badge badge-warning'>BODEGA</span>:".$item->warehouseNumber." ".$item->warehouseName."<br/>";
					$mensaje			.= "<span class='badge badge-warning'>CANTIDAD</span>:".$item->quantity.",<span class='badge badge-warning'>CANTIDAD MINIMA</span>:".$item->quantityMin;
					
					$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
					$data				= null;
					$errorID 			= 0;
					if(!$objError){
						$data["notificated"]= "inventario minimo";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);
					}
					else 
						$errorID 			= $objError->errorID;
					
					//tag con correo
					if($objTag->sendEmail){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
							if(!$objNotificationUser){
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "INVENTARIO MINIMO";
								$data["message"]			= $mensaje;
								$data["summary"]			= "INVENTARIO MINIMO";
								$data["title"]				= "INVENTARIO MINIMO";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert_app_posme($data);
							}
						}
					}
					
					//tag con notificacion
					if($objTag->sendNotificationApp){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
							if(!$objErrorUser){
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "inventario minimo";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
								$this->Error_Model->insert_app_posme($data);
							}
						}
					}
					
				}
			}
		}
	}
	
	function fillCumpleayo(){
		
		
		
		
		
		
				
		
		$tagName		= "FELIZ CUMPLE";		
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		
		//Para cada empresa
		$objListCompany = $this->Company_Model->get_rows();		
		if($objListCompany)
		foreach($objListCompany as $i){
			
			//Obtener los cumple de la empresa
			$mensaje			= null;
			$objListItem		= $this->Customer_Model->get_happyBirthDay($i->companyID);
			
			
			if($objListItem)
			foreach($objListItem as $usuario){
				$mensaje					= "<span class='badge badge-info'>FELIZ CUMPLE</span>:".$usuario->firstName." : =>".$usuario->birthDate." AVISO DEL PERIODO = ".date_format(date_create(),"Y");
				
				//Enviar Mensaje por Correo
				/*
				$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
				if(!$objNotificationUser){					
					$data["errorID"]			= NULL;
					$data["from"]				= EMAIL_APP;
					$data["to"]					= $usuario->email;
					$data["subject"]			= "FELIZ CUMPLE";
					$data["message"]			= $mensaje;
					$data["summary"]			= "FELIZ CUMPLE";
					$data["title"]				= "FELIZ CUMPLE";
					$data["tagID"]				= NULL;
					$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
					$data["isActive"]			= 1;
					$this->Notification_Model->insert_app_posme($data);
				}
				*/
				
				//Notificaciones al administrador
				$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
				$data				= null;
				$errorID 			= 0;
					
				if(!$objError){
					$data				= null;
					$data["notificated"]= "cumple";
					$data["tagID"]		= $objTag->tagID;
					$data["message"]	= $mensaje;
					$data["isActive"]	= 1;
					$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
					$errorID			= $this->Error_Model->insert_app_posme($data);
				}
				else 
					$errorID 			= $objError->errorID;
					
				
				//Notificacioin a los usuarios
				if($objTag->sendNotificationApp){
					$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
					//Lista de Usuarios
					if ($objListUsuario)
					foreach($objListUsuario as $usuario){
						$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
						if(!$objErrorUser){
							$data					= null;
							$data["tagID"]			= $objTag->tagID;
							$data["notificated"]	= "FELIZ CUMPLE";
							$data["message"]		= $mensaje;
							$data["isActive"]		= 1;
							$data["userID"]			= $usuario->userID;
							$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
							$this->Error_Model->insert_app_posme($data);
						}
					}
				}
				
				
					
				
			}
			
		}
	}
	
	function fillCuotaAtrasada(){	
		
		
		
		
		
		
		
		
		
		
		$tagName		= "NOTIFICAR CUOTA VENCIDA";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		
		//Lista de empresa
		if($objListCompany)
		foreach($objListCompany as $i){
			$objListItem		= $this->Customer_Credit_Amortization_Model->get_rowShareLate($i->companyID);
			//Lista de Avisos
			if($objListItem){
				foreach($objListItem as $item){
					$objCurrency		= $this->Currency_Model->get_rowByPK($item->currencyID);
					$mensaje			= "";
					$mensaje			.= "<span class='badge badge-success'>CLIENTE</span>:".$item->customerNumber."-".$item->firstName." ".$item->lastName." => ";
					$mensaje			.= "".$item->documentNumber." => ".$item->dateApply." => <span class='badge badge-success'>ATRAZO</span>: ".$objCurrency->simbol." ".sprintf("%01.2f",$item->remaining);
					  
					//Ver si el mensaje ya existe para el administrador
					$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
					$data				= null;
					$errorID 			= 0;
					
					if(!$objError){
						$data				= null;
						$data["notificated"]= "cuota atrasada";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);
					}
					else 
						$errorID 			= $objError->errorID;
					
					
					//tag con correo
					if($objTag->sendEmail){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
							if(!$objNotificationUser){
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "CUOTA ATRASADA";
								$data["message"]			= $mensaje;
								$data["summary"]			= "CUOTA ATRASADA";
								$data["title"]				= "CUOTA ATRASADA";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert_app_posme($data);
							}
						}
					}
					
					
					//tag con notificacion
					if($objTag->sendNotificationApp){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						//Lista de Usuarios
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
							if(!$objErrorUser){
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "cuota atrasada";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
								$this->Error_Model->insert_app_posme($data);
							}
						}
					}
					
				
				}
			}
		}
	}
	
	function file_job_send_report_daly($companyID=""){	
		
		$companyID = helper_SegmentsByIndex($this->uri->getSegments(),1,$companyID);	
	
	
		//Obtener parametros
		$parameterEmail = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL",APP_COMPANY);
		$parameterEmail = $parameterEmail->value;
		
		$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",APP_COMPANY);
		$parameterBalance = $parameterBalance->value;
		
		$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP",$companyID);
		$parameterDaySleep					= $parameterDaySleep->value;
			
		$fechaNow  		= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  			//ahora		
		$fechaNow		= $fechaNow->modify('-'.$parameterDaySleep.' day');		
		$fechaNow		= $fechaNow->format("Y-m-d 00:00:00");	
		$fechaNow		= "2023-01-01";
		
		$fechaBefore  	= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  			//ayer
		$fechaBefore	= $fechaBefore->modify('-'.$parameterDaySleep.' day');	
		$fechaBefore	= $fechaBefore->format("Y-m-d 23:59:59");
		$fechaBefore	= "2023-05-01";
		
		$tocken			= '';
		//Obtener compania
		$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
		//Get Logo
		$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
		
		//Reporte de Venta
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		//Obtener ventas
		$query			= "CALL pr_sales_get_report_sales_detail(?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY,$tocken,APP_USERADMIN,$fechaNow,$fechaBefore]
		);			
		
		
		if(isset($objData)){
			$objDataResult["objDetail"]				= $objData;
		}
		else{
			$objDataResult["objDetail"]				= $objData;
		}
				
		
		
		//parametros de reportes
		$params_["objCompany"]			= $objCompany;
		$params_["objStartOn"]			= str_replace(" 00:00:00","",$fechaNow);		
		$params_["objEndOn"]			= str_replace(" 00:00:00","",$fechaBefore);				
		$params_["objDetail"]			= $objDataResult["objDetail"];		
		
		$params_["message"]			= "VENTAS: ".$objCompany->name." Del ".str_replace(" 00:00:00","",$fechaNow);
		$params_["title1"]			= "Reporte diario: 002";
		$params_["title2"]			= "Reporte diario: 003";
		$params_["titleParrafo"]	= "Reporte diario: 005";
		$params_["cuerpo"]			= "Reporte diario: 005";
		
		$params_["sumaryLeft1"]		= "Reporte diario: 005";
		$params_["sumaryLeft2"]		= "Reporte diario: 005";
		$params_["sumaryRight1"]	= "Reporte diario: 005";
		$params_["sumaryRight2"]	= "Reporte diario: 005";
		
		$params_["sumaryLine001"]	= "Reporte diario: 005";
		$params_["sumaryLine002"]	= "Reporte diario: 004";
		$params_["sumaryLine003"]	= "Reporte diario: 003";
		$params_["sumaryLine004"]	= "Reporte diario: 002";
		$params_["sumaryLine005"]	= "Reporte diario: 001";
		$params_["sumaryLine006"]	= "Reporte diario: 006";
		$params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . ",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
		$params_["objFirmaEncription"] 			= md5 ($params_["objFirma"]);
		
		//vista
		$subject 			= $params_["message"];
		$body  				= /*--inicio view*/ view('app_sales_report/sales_detail/view_a_disemp_email',$params_);//--finview
		$body2 				= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview
		
		
		//enviar al propietario del negocio
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 		
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//Reporte de Buy
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		//Obtener Resument por transaccin
		$query			= "CALL pr_notification_buy(?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY,$tocken,APP_USERADMIN,$fechaNow,$fechaBefore]
		);			
		
		
		if(isset($objData)){
			$objDataResultBy["objDetail"]				= $objData;
		}
		else{
			$objDataResultBy["objDetail"]				= $objData;
		}
				
		
		
		//parametros de reportes
		$params_["objCompany"]			= $objCompany;
		$params_["objStartOn"]			= str_replace(" 00:00:00","",$fechaNow);		
		$params_["objEndOn"]			= str_replace(" 00:00:00","",$fechaBefore);				
		$params_["objDetail"]			= $objDataResultBy["objDetail"];		
		
		$params_["message"]			= "RESUMEN POR TRANSACCION: ".$objCompany->name." Del ".str_replace(" 00:00:00","",$fechaNow);
		$params_["title1"]			= "Reporte diario: 002";
		$params_["title2"]			= "Reporte diario: 003";
		$params_["titleParrafo"]	= "Reporte diario: 005";
		$params_["cuerpo"]			= "Reporte diario: 005";
		
		$params_["sumaryLeft1"]		= "Reporte diario: 005";
		$params_["sumaryLeft2"]		= "Reporte diario: 005";
		$params_["sumaryRight1"]	= "Reporte diario: 005";
		$params_["sumaryRight2"]	= "Reporte diario: 005";
		
		$params_["sumaryLine001"]	= "Reporte diario: 005";
		$params_["sumaryLine002"]	= "Reporte diario: 004";
		$params_["sumaryLine003"]	= "Reporte diario: 003";
		$params_["sumaryLine004"]	= "Reporte diario: 002";
		$params_["sumaryLine005"]	= "Reporte diario: 001";
		$params_["sumaryLine006"]	= "Reporte diario: 006";
		$params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . ",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
		$params_["objFirmaEncription"] 			= md5 ($params_["objFirma"]);
		
		//vista
		$subject 			= $params_["message"];
		$body  				= /*--inicio view*/ view('app_notification/report_buy/view_a_disemp_email',$params_);//--finview
		$body2 				= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview
		
		
		//enviar al propietario del negocio
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 		
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//Reporte de Caja
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////		
		$authorization		= 0;
		$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN,$tocken,APP_COMPANY,$authorization,$fechaNow,$fechaBefore,0]
		);			
		//Get Datos de Facturacion				
		$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?);";
		$objDataSales	= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY,$tocken,APP_USERADMIN,$fechaNow,$fechaBefore,0]
		);			
		
		//Get Datos de Entrada de Efectivo y Salida				
		$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?);";
		$objDataCash	= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN,$tocken,APP_COMPANY,$authorization,$fechaNow,$fechaBefore,0]
		);			
		
		$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?);";
		$objDataCashOut	= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN,$tocken,APP_COMPANY,$authorization,$fechaNow,$fechaBefore,0]
		);	
		
			
		if(isset($objData))
		$objDataResult["objDetail"]					= $objData;
		else
		$objDataResult["objDetail"]					= NULL;
	
	
		if(isset($objDataSales))
		$objDataResult["objSales"]					= $objDataSales;
		else
		$objDataResult["objSales"]					= NULL;
	
		if(isset($objDataCash))				
		$objDataResult["objCash"]					= $objDataCash;
		else
		$objDataResult["objCash"]					= NULL;
	
		if(isset($objDataCashOut))				
		$objDataResult["objCashOut"]				= $objDataCashOut;
		else
		$objDataResult["objCashOut"]				= NULL;
		
		$params_["message"]							= "CAJA : ".$objCompany->name." Del ".str_replace(" 00:00:00","",$fechaNow);
		$subject 									= $params_["message"];
		$objDataResult["objCompany"] 				= $objCompany;
		$objDataResult["objLogo"] 					= $objParameter;
		$objDataResult["startOn"] 					= $fechaNow;
		$objDataResult["endOn"] 					= $fechaBefore;
		$objDataResult["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . ",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
		$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
	
		$body  				= /*--inicio view*/ view('app_box_report/share/view_a_disemp',$objDataResult);
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 		
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//Reporte de Transacciones Anuladas
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////	
		$params_["message"]			= "T-ANULADAS - REGISTRADAS: ".$objCompany->name." Del ".str_replace(" 00:00:00","",$fechaNow);
		$subject 					= $params_["message"];
		
		$query			= "CALL pr_transaction_report_registradas_anuladas(?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY,$tocken,APP_USERADMIN,$fechaNow,$fechaBefore]
		);			
		
		
		if(isset($objData)){
			$objDataResult["objDetail"]				= $objData;
		}
		else{
			$objDataResult["objDetail"]				= $objData;
		}
				
		$params_["objCompany"]			= $objCompany;
		$params_["objStartOn"]			= str_replace(" 00:00:00","",$fechaNow);		
		$params_["objEndOn"]			= str_replace(" 00:00:00","",$fechaBefore);				
		$params_["objDetail"]			= $objDataResult["objDetail"];		
		$params_["objFirma"] 			= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_transaction_report_registradas_anuladas" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . ",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
		$params_["objFirmaEncription"] 	= md5 ($params_["objFirma"]);
		$body  							= /*--inicio view*/ view('app_sales_report/transaction_anuladas/view_a_disemp_email',$params_);//--finview
				
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject);			
		$this->email->setMessage($body); 		
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		
		
		return view('core_template/close');//--finview-r
		
	}
}
?>