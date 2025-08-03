<?php
//posme:2023-02-27
namespace App\Controllers;

class app_notification extends _BaseController
{


	/********************************************/
	/********************************************/
	/********************************************/
	///INICIO CORE SISTEMA
	/********************************************/
	/********************************************/
	/********************************************/
	
	//procesar las notificaciones
	//tomas las notifiaciones en remember e inserta en la talba error para que luesgo 
	//aparitr de esa tabla sea mostradas las notifcaicones en el sistema o cuando 
	//pase el barrido de envio de wahtap tome de esa tabla
	//o cuando paes el barrido de envioa de email tome de esa tabla los datos
	function fillCurrentNotification()
	{

		$tagName		= "LLENAR NOTI DE OBLIGACION";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		

		//Recorrer Empresas
		if ($objListCompany)
			foreach ($objListCompany as $i) {
				$objListItem		= $this->Remember_Model->getNotificationCompany($i->companyID);
				//Recorrer las Notificaciones
				if ($objListItem) {
					foreach ($objListItem as $noti) {
						$hoy_			= date_format(date_create(), "Y-m-d");
						$lastNoti 		= $hoy_; //date_format(date_create($noti->lastNotificationOn), "Y-m-d");

						//Recorrer desde la ultima notificacion, hasta la fecha de hoy
						while ($lastNoti <= $hoy_) {
							
							//Validar si se debe ejecutar la notificacion
							$objListItemDetail		= $this->Remember_Model->getProcessNotification($noti->rememberID, $lastNoti);
							if ($objListItemDetail)
							{
								if ($objListItemDetail->diaProcesado == $noti->day) 
								{

									
									$item 					= $objListItemDetail;
									$mensaje				= "";
									$mensaje				.= "<span class='badge badge-important'>OBLIGACION</span>" . $item->title;
									$mensaje				.= " => " . $item->description . " => " . $item->Fecha . " => <span class='badge badge-important'>ATRAZO</span>";

									//Ver si el mensaje ya existe para el administrador
									$objError			= false;
									$data				= null;
									$errorID 			= 0;
									
									
									$data				= null;
									$data["notificated"] = "notificar obligacion";
									$data["tagID"]		= $objTag->tagID;
									$data["message"]	= $mensaje;
									$data["isActive"]	= 1;
									$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
									$errorID			= $this->Error_Model->insert_app_posme($data);
								
								
								
									$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
									if ($objListUsuario)
									{
										foreach ($objListUsuario as $usuario) 
										{	
										
											if ($objTag->sendNotificationApp)
											{
												$data					= null;
												$data["tagID"]			= $objTag->tagID;
												$data["notificated"]	= "notificar obligacion";
												$data["message"]		= $mensaje;
												$data["isActive"]		= 1;
												$data["userID"]			= $usuario->userID;
												$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
												$this->Error_Model->insert_app_posme($data);
											}
											if ($objTag->sendEmail)
											{
												$data						= null;
												$data["errorID"]			= $errorID;
												$data["from"]				= EMAIL_APP;
												$data["to"]					= $usuario->email;
												$data["subject"]			= "notificar obligacion";
												$data["message"]			= $mensaje;
												$data["summary"]			= "notificar obligacion";
												$data["title"]				= "notificar obligacion";
												$data["tagID"]				= $objTag->tagID;
												$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
												$data["isActive"]			= 1;
												$this->Notification_Model->insert_app_posme($data);
											}
										}
									}	
								}
							}
								
								
							//Actualizar Base de Datos
							$dataRemember						= NULL;
							$dataRemember["lastNotificationOn"]	= $lastNoti;
							$this->Remember_Model->update_app_posme($noti->rememberID, $dataRemember);
							//Siguiente Fecha
							$lastNoti = date_format(date_add(date_create($lastNoti), date_interval_create_from_date_string("1 days")), "Y-m-d");
						}
					}
				}
			}
	}
	function fillSendWhatsappOrEmail()
	{

		$tagName			= "LLENAR NOTI DE ENVIO DE EMAIL Y ENVIO DE WHATAPP MASIVO";
		$objListCompany 	= $this->Company_Model->get_rows();
		$objTag				= $this->Tag_Model->get_rowByName($tagName);
		$objListUsuario		= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
		$objListCustomer 	= $this->Customer_Model->get_rowByCompany_phoneAndEmail(APP_COMPANY);
		$objListEmail		= $this->Entity_Email_Model->get_rowByCompany(APP_COMPANY);
		$objParameter		= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT", APP_COMPANY);
		$characterSplie 	= $objParameter->value;

		//Recorrer Empresas
		if ($objListCompany)
		{
			foreach ($objListCompany as $i) {
				$objListItem		= $this->Remember_Model->getNotificationCompanyByTagId($i->companyID, $objTag->tagID);
				//Recorrer las Notificaciones
				if ($objListItem) {
					foreach ($objListItem as $noti) {
						//Leer archivo   que esta en remember
						$path 	= PATH_FILE_OF_APP . "/company_" . APP_COMPANY . "/component_76/component_item_" . $noti->rememberID;
						$path 	= $path . '/send.csv';
						$table 			= null;
						$fila 			= 0;

						if (file_exists($path)) {
							$this->csvreader->separator = $characterSplie;
							$table 			= $this->csvreader->parse_file($path);

							if ($table) {
								if (count($table) >= 1) {
									if (!array_key_exists("Destino", $table[0])) {
										$table = null;
									}
									if (!array_key_exists("Mensaje", $table[0])) {
										$table = null;
									}

									if (!is_null($table)) {
										$objListCustomer = array();
										foreach ($table as $row) {
											$rowx 					= array();
											$rowx["firstName"] 		= "";
											$rowx["phoneNumber"] 	= $row["Destino"];
											$rowx["mensaje"] 		= $row["Mensaje"];
											array_push($objListCustomer, $rowx);
										}
									}
								}
							}
						}






						$hoy_			= date_format(date_create(), "Y-m-d");
						$lastNoti 		= $hoy_; //date_format(date_create($noti->lastNotificationOn), "Y-m-d");


						//Recorrer desde la ultima notificacion, hasta la fecha de hoy
						while ($lastNoti <= $hoy_) {

							//Validar si Ya esta procesado el Dia.						
							$objListItemDetail		= $this->Remember_Model->getProcessNotification($noti->rememberID, $lastNoti);
							if ($objListItemDetail)
							{
								if ($objListItemDetail->diaProcesado == $noti->day) 
								{

									
									$item 					= $objListItemDetail;
									$mensaje				=  " ";
									$mensaje				.= " ";
									$mensaje				.= " => " . $item->description;
									$mensaje				.= " ";

									//Ver si el mensaje ya existe para el administrador
									$data				= null;
									$errorID 			= 0;
									$data				= null;
									$data["notificated"] = "mensaje al cliente";
									$data["tagID"]		= $objTag->tagID;
									$data["message"]	= $mensaje;
									$data["isActive"]	= 1;
									$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
									$errorID			= $this->Error_Model->insert_app_posme($data);
									
									//Lista de Usuarios
									if ($objListCustomer)
									{
										foreach ($objListCustomer as $usuarioX) {

											$phoneNumber  	= $item->leerFile == 1 ? $usuarioX["phoneNumber"] : $usuarioX->phoneNumber;
											$firstName  	= $item->leerFile == 1 ? $usuarioX["firstName"] : $usuarioX->firstName;

											if ($objTag->sendSMS == "1") 
											{
												
												$data						= null;
												$data["errorID"]			= $errorID;
												$data["from"]				= PHONE_POSME;
												$data["to"]					= $firstName;
												$data["phoneFrom"]			= PHONE_POSME;
												$data["phoneTo"]			= $phoneNumber;
												$data["programDate"]		= $hoy_;
												$data["programHour"]		= "00:00:00";
												$data["subject"]			= "notificacion";
												$data["message"]			= $mensaje;
												$data["summary"]			= "summary... ";
												$data["title"]				= "title";
												$data["tagID"]				= $objTag->tagID;
												$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
												$data["isActive"]			= 1;
												$this->Notification_Model->insert_app_posme($data);
											}
											
										}
											
									}
									


									//tag con correo
									if ($objListEmail)
									{
										foreach ($objListEmail as $customerX) 
										{
											
											$phoneNumber  	= $item->leerFile == 1 ? $customerX["phoneNumber"] : $customerX->email;
											$firstName  	= $item->leerFile == 1 ? $customerX["firstName"] : $customerX->email;


											if ($objTag->sendEmail == "1") {
												
												$data						= null;
												$data["errorID"]			= $errorID;
												$data["from"]				= EMAIL_APP;
												$data["to"]					= $phoneNumber;
												$data["subject"]			= "notificar obligacion";
												$data["message"]			= $mensaje;
												$data["summary"]			= "notificar obligacion";
												$data["title"]				= "notificar obligacion";
												$data["tagID"]				= $objTag->tagID;
												$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
												$data["isActive"]			= 1;
												$this->Notification_Model->insert_app_posme($data);
											}
										}
									}
								}
							}

							//Actualizar Base de Datos
							$dataRemember						= NULL;
							$dataRemember["lastNotificationOn"]	= $lastNoti;
							$this->Remember_Model->update_app_posme($noti->rememberID, $dataRemember);
							//Siguiente Fecha
							$lastNoti = date_format(date_add(date_create($lastNoti), date_interval_create_from_date_string("1 days")), "Y-m-d");
						}
					}
				}
			}
		}
	}
	//mostrar las notificaciones en sistema, de si falta la tasa de cambio
	function fillTipoCambio()
	{

		$tagName		= "LLENAR NOTI TIPO DE CAMBIO";
		$date_			= date_format(date_create(), "Y-m-d");
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if ($objListCompany)
			foreach ($objListCompany as $i) {
				$defaultCurrencyID	= $this->core_web_currency->getCurrencyDefault($i->companyID)->currencyID;
				$reportCurrencyID	= $this->core_web_currency->getCurrencyExternal($i->companyID)->currencyID;

				try {
					$exchangeRate		= $this->core_web_currency->getRatio($i->companyID, $date_, 1, $reportCurrencyID, $defaultCurrencyID);
					
				} catch (\Exception $e) 
				{
					$mensaje			= $e->getMessage();
					$data				= null;
					$errorID 			= 0;					
					$data["notificated"] = "tipo de cambio...";
					$data["tagID"]		= $objTag->tagID;
					$data["message"]	= $mensaje;
					$data["isActive"]	= 1;
					$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
					$errorID			= $this->Error_Model->insert_app_posme($data);
				
					//tag con correo
					if ($objTag->sendEmail) {
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
						if ($objListUsuario)
							foreach ($objListUsuario as $usuario) {
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "TIPO DE CAMBIO";
								$data["message"]			= $mensaje;
								$data["summary"]			= "TIPO DE CAMBIO NO INGRESADO";
								$data["title"]				= "TIPO DE CAMBIO";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert_app_posme($data);
							
							}
					}

					//tag con notificacion
					if ($objTag->sendNotificationApp) {
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
						if ($objListUsuario)
							foreach ($objListUsuario as $usuario) {
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "tasa de cambio";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
								$this->Error_Model->insert_app_posme($data);
							
							}
					}
				}
			}
	}
	//mostrar las notificaciones en sistema, de inventarios minimos
	function fillInventarioMinimo()
	{

		$tagName		= "LLENAR NOTI INVENTARIO MINIMO";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if ($objListCompany)
			foreach ($objListCompany as $i) {
				$objListItem		= $this->Itemwarehouse_Model->get_rowLowMinimus($i->companyID);
				if ($objListItem) {
					foreach ($objListItem as $item) {
						$mensaje			 = "";
						$mensaje			.= "<span class='badge badge-warning'>PRODUCTO</span>:" . $item->itemNumber . " " . $item->itemName . "<br/>";
						$mensaje			.= "<span class='badge badge-warning'>BODEGA</span>:" .   $item->warehouseNumber . " " . $item->warehouseName . "<br/>";
						$mensaje			.= "<span class='badge badge-warning'>CANTIDAD</span>:" . $item->quantity . ",<span class='badge badge-warning'>CANTIDAD MINIMA</span>:" . $item->quantityMin;

						
						$data				= null;
						$errorID 			= 0;
						$data["notificated"] = "inventario minimo";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);
				

						//tag con correo
						if ($objTag->sendEmail) {
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									$data						= null;
									$data["errorID"]			= $errorID;
									$data["from"]				= EMAIL_APP;
									$data["to"]					= $usuario->email;
									$data["subject"]			= "INVENTARIO MINIMO";
									$data["message"]			= $mensaje;
									$data["summary"]			= "INVENTARIO MINIMO";
									$data["title"]				= "INVENTARIO MINIMO";
									$data["tagID"]				= $objTag->tagID;
									$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
									$data["isActive"]			= 1;
									$this->Notification_Model->insert_app_posme($data);
								
								}
						}

						//tag con notificacion
						if ($objTag->sendNotificationApp) 
						{
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									
									$data					= null;
									$data["tagID"]			= $objTag->tagID;
									$data["notificated"]	= "inventario minimo";
									$data["message"]		= $mensaje;
									$data["isActive"]		= 1;
									$data["userID"]			= $usuario->userID;
									$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
									$this->Error_Model->insert_app_posme($data);
								
								}
						}
					}
				}
			}
	}
	
	//mostrar las notificaciones en sistema, de inventarios minimos no envia email
	function fillInventarioMinimoNotEmailSiApp()
	{

		$tagName		= "LLENAR NOTI INVENTARIO MINIMO";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if ($objListCompany)
			foreach ($objListCompany as $i) {
				$objListItem		= $this->Itemwarehouse_Model->get_rowLowMinimus($i->companyID);
				if ($objListItem) {
					foreach ($objListItem as $item) {
						$mensaje			 = "";
						$mensaje			.= "<span class='badge badge-warning'>PRODUCTO</span>:" . $item->itemNumber . " " . $item->itemName . "<br/>";
						$mensaje			.= "<span class='badge badge-warning'>BODEGA</span>:" .   $item->warehouseNumber . " " . $item->warehouseName . "<br/>";
						$mensaje			.= "<span class='badge badge-warning'>CANTIDAD</span>:" . $item->quantity . ",<span class='badge badge-warning'>CANTIDAD MINIMA</span>:" . $item->quantityMin;

						//tag con notificacion
						if ($objTag->sendNotificationApp) 
						{
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									
									$data					= null;
									$data["tagID"]			= $objTag->tagID;
									$data["notificated"]	= "inventario minimo";
									$data["message"]		= $mensaje;
									$data["isActive"]		= 1;
									$data["userID"]			= $usuario->userID;
									$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
									$this->Error_Model->insert_app_posme($data);
								
								}
						}
					}
				}
			}
	}
	
	
	function fillInventarioFechaVencimiento()
	{
		$tagName		= "LLENAR NOTI DE PRODUCTOS VENCIDOS";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if ($objListCompany) {
			foreach ($objListCompany as $i) {
				$objListItem		= $this->Item_Warehouse_Expired_Model->getBy_ItemIDAproxVencimiento($i->companyID);
				if ($objListItem) {
					foreach ($objListItem as $item) {
						$mensaje			 = "";
						$mensaje			.= "<span class='badge badge-warning'>PRODUCTO</span>:" . $item->itemNumber . " " . $item->itemName . "<br/>";
						$mensaje			.= "<span class='badge badge-warning'>BODEGA</span>:" . $item->warehouseNumber . " " . $item->warehouseName . "<br/>";
						$mensaje			.= "<span class='badge badge-warning'>CANTIDAD</span>:" . $item->quantity . "----(" . $item->dateExpired . ")<span class='badge badge-warning'>VENCIMIENTO</span>:";

						//insertar error
						$data				= null;
						$errorID 			= 0;
						$data["notificated"] = "fecha de vencimiento";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);
				

						//tag con correo
						if ($objTag->sendEmail) {
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									
									$data						= null;
									$data["errorID"]			= $errorID;
									$data["from"]				= EMAIL_APP;
									$data["to"]					= $usuario->email;
									$data["subject"]			= "INVENTARIO VENCIMIENTO";
									$data["message"]			= $mensaje;
									$data["summary"]			= "INVENTARIO VENCIMIENTO";
									$data["title"]				= "INVENTARIO VENCIMIENTO";
									$data["tagID"]				= $objTag->tagID;
									$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
									$data["isActive"]			= 1;
									$this->Notification_Model->insert_app_posme($data);
								
								}
						}

						//tag con notificacion
						if ($objTag->sendNotificationApp) {
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							if ($objListUsuario) {
								foreach ($objListUsuario as $usuario) {
									$data					= null;
									$data["tagID"]			= $objTag->tagID;
									$data["notificated"]	= "fecha de vencimiento";
									$data["message"]		= $mensaje;
									$data["isActive"]		= 1;
									$data["userID"]			= $usuario->userID;
									$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
									$this->Error_Model->insert_app_posme($data);
								
								}
							}
						}
					}
				}
			}
		}
	}
	//mostrar las notificaciones en sistema, de cumple de clientes
	function fillCumpleayo()
	{
		$tagName		= "LLENAR NOTI FELIZ CUMPLE";
		$objTag			= $this->Tag_Model->get_rowByName($tagName);

		//Para cada empresa
		$objListCompany = $this->Company_Model->get_rows();
		if ($objListCompany)
			foreach ($objListCompany as $i) {

				//Obtener los cumple de la empresa
				$mensaje			= null;
				$objListItem		= $this->Customer_Model->get_happyBirthDay($i->companyID);


				if ($objListItem)
					foreach ($objListItem as $usuario) {
						$mensaje					= "<span class='badge badge-info'>FELIZ CUMPLE</span>:" . $usuario->firstName . " : =>" . $usuario->birthDate . " AVISO DEL PERIODO = " . date_format(date_create(), "Y");

						//Enviar Mensaje por Correo
						//Notificaciones al administrador
						$data				= null;
						$errorID 			= 0;
						$data				= null;
						$data["notificated"] = "cumple";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);

						//Notificacioin a los usuarios
						if ($objTag->sendNotificationApp) {
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							//Lista de Usuarios
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									$data					= null;
									$data["tagID"]			= $objTag->tagID;
									$data["notificated"]	= "FELIZ CUMPLE";
									$data["message"]		= $mensaje;
									$data["isActive"]		= 1;
									$data["userID"]			= $usuario->userID;
									$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
									$this->Error_Model->insert_app_posme($data);
								}
						}
					}
			}
	}
	//mostrar las notificaciones en sistema, de cuotas atrazadas
	function fillCuotaAtrasada()
	{

		$tagName		= "LLENAR NOTI CUOTA A TRASADA";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);

		//Lista de empresa
		if ($objListCompany)
			foreach ($objListCompany as $i) {
				$objListItem		= $this->Customer_Credit_Amortization_Model->get_rowShareLate($i->companyID);
				//Lista de Avisos
				if ($objListItem) {
					foreach ($objListItem as $item) {
						$objCurrency		= $this->Currency_Model->get_rowByPK($item->currencyID);
						$mensaje			= "";
						$mensaje			.= "<span class='badge badge-success'>CLIENTE</span>:" . $item->customerNumber . "-" . $item->firstName . " " . $item->lastName . " => ";
						$mensaje			.= "" . $item->documentNumber . " => " . $item->dateApply . " => <span class='badge badge-success'>ATRAZO</span>: " . $objCurrency->simbol . " " . sprintf("%01.2f", $item->remaining);

						//Ver si el mensaje ya existe para el administrador
						$data				= null;
						$errorID 			= 0;
						$data				= null;
						$data["notificated"] = "cuota atrasada";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(), "Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert_app_posme($data);
					

						//tag con correo
						if ($objTag->sendEmail) {
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									$data						= null;
									$data["errorID"]			= $errorID;
									$data["from"]				= EMAIL_APP;
									$data["to"]					= $usuario->email;
									$data["subject"]			= "CUOTA ATRASADA";
									$data["message"]			= $mensaje;
									$data["summary"]			= "CUOTA ATRASADA";
									$data["title"]				= "CUOTA ATRASADA";
									$data["tagID"]				= $objTag->tagID;
									$data["createdOn"]			= date_format(date_create(), "Y-m-d H:i:s");
									$data["isActive"]			= 1;
									$this->Notification_Model->insert_app_posme($data);
								
								}
						}


						//tag con notificacion
						if ($objTag->sendNotificationApp) {
							$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
							//Lista de Usuarios
							if ($objListUsuario)
								foreach ($objListUsuario as $usuario) {
									
									$data					= null;
									$data["tagID"]			= $objTag->tagID;
									$data["notificated"]	= "cuota atrasada";
									$data["message"]		= $mensaje;
									$data["isActive"]		= 1;
									$data["userID"]			= $usuario->userID;
									$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
									$this->Error_Model->insert_app_posme($data);
								
								}
						}
					}
				}
			}
	}
	//crear las notificaciones en la base de datos. para revisar cuales son las siguientes visitas
	function fillNextVisit($companyID = "")
	{
		$tagName		= "LLENAR NOTI PROXIMA VISITA";
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		$companyID 		= APP_COMPANY;
		$objLTM			= $this->Transaction_Master_Model->get_rowByNotification($companyID);

		if ($objLTM) {
			//Recorrer lista de transacciones
			foreach ($objLTM as $objTM) {

				$mensaje			= "";
				$mensaje			.= "<span class='badge badge-success'>TELEFONO</span>:" . $objTM->numberPhone . " => ";
				$mensaje			.= $objTM->transactionNumber . " => " . $objTM->note;

				if ($objTag->sendNotificationApp) {
					//lista de usuarios
					$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);
					if ($objListUsuario) {
						foreach ($objListUsuario as $usuario) {
							$data					= null;
							$data["tagID"]			= $objTag->tagID;
							$data["notificated"]	= "proxima visita";
							$data["message"]		= $mensaje;
							$data["isActive"]		= 1;
							$data["userID"]			= $usuario->userID;
							$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
							$erroID 				= $this->Error_Model->insert_app_posme($data);
						}
					}
				}

				//actualizar notificacion
				$dataTM 					= null;
				$dataTM["notificationID"] 	= 1;
				$this->Transaction_Master_Model->update_app_posme($objTM->companyID, $objTM->transactionID, $objTM->transactionMasterID, $dataTM);
			}
		}


		return view('core_template/close'); //--finview-r

	}
	function generatedTransactionOutputByFormulate($companyID = "")
	{
		try{ 
		
			$companyID			= APP_COMPANY;
			$branchID 			= APP_BRANCH;
			$loginID			= APP_USERADMIN;
			$componentPeriodID	= 0;
			$componentCycleID	= 0;
			
			
						
			$query									= "CALL pr_inventory_create_transaction_output_by_formulated(?,?,?,?,?,@resultMayorization);";
			$resultMayorizate						= $this->Bd_Model->executeRender(
				$query,[$companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID]
			);	
			
			$query									= "SELECT @resultMayorization as codigo";
			$resultMayorizate						= $this->Bd_Model->executeRender($query,null);			
			
			
			$resultMayorizate						= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			$resultMayorizateTransactionID			= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionID');
			$resultMayorizateTransactionMasterIDID	= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionMasterID');
			$resultMayorizateTransactionID 			= $resultMayorizateTransactionID->description;
			$resultMayorizateTransactionMasterIDID	= $resultMayorizateTransactionMasterIDID->description;
			
			//Ingresar en Kardex.
			$this->core_web_inventory->calculateKardexNewOutput($companyID,$resultMayorizateTransactionID,$resultMayorizateTransactionMasterIDID);			
			
			//Crear Conceptos.
			$this->core_web_concept->otheroutput($companyID,$resultMayorizateTransactionID,$resultMayorizateTransactionMasterIDID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $resultMayorizate
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	

	}
	function getNotificationShowInApp($userName)
	{
		if ($userName !== null) {
			$queryResult = $this->Customer_Frecuency_Actuations_Model->get_rowExpiredRegisters($userName);
			return $this->response->setJson([
				"username" => $userName,
				"error" => false,
				"message" => "success",
				"data" => $queryResult
			]);
		} else {
			return $this->response->setJson([
				"error" => true,
				"message" => "Username is required",
				"username" => $userName
			]);
		}
	}
	function sendEmailItemExpired()
	{
		
		$emailProperty  = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$emailProperty  = $emailProperty->value;
		$objCompany  	= $this->Company_Model->get_rowByPK(APP_COMPANY);

		$objNotificar = $this->Item_Model->get_rowByItemExpiredAndDayParameter(APP_COMPANY,"9,10");
		if ($objNotificar)
		{
			$table 	= "";
			$table	= $table."<table style='width:100%'>";
			
			$table	= $table."<thead>";
			$table	= $table."<tr>";
				$table	= $table."<th>";
					$table	= $table."Codigo";
				$table	= $table."</th>";
				$table	= $table."<th>";
					$table	= $table."Nombre";
				$table	= $table."</th>";
				$table	= $table."<th>";
					$table	= $table."Bodega";
				$table	= $table."</th>";
				$table	= $table."<th>";
					$table	= $table."Cantidad";
				$table	= $table."</th>";
				$table	= $table."<th>";
					$table	= $table."Expiracion";
				$table	= $table."</th>";
			$table	= $table."</tr>";
			$table	= $table."<thead>";
				
			$table	= $table."<tbody>";
			foreach ($objNotificar as $i) 
			{
				$table	= $table."<tr>";
					$table	= $table."<td>";
						$table	= $table."".$i->itemNumber.""."";
					$table	= $table."</td>";
					$table	= $table."<td>";
						$table	= $table."".$i->name.""."";
					$table	= $table."</td>";
					$table	= $table."<td>";
						$table	= $table."".$i->warehouseName.""."";
					$table	= $table."</td>";
					$table	= $table."<td>";
						$table	= $table."".$i->quantity.""."";
					$table	= $table."</td>";
					$table	= $table."<td>";
						$table	= $table."".$i->dateExpired.""."";
					$table	= $table."</td>";
				$table	= $table."</tr>";
			}
			$table	= $table."</tbody>";
			$table	= $table."</table>";
			
			$subject 				= "Productos por vencer";
			$params_["objCompany"]  = $objCompany;			
			$params_["mensaje"]  	= $table;				
			$body  					= /*--inicio view*/ view('core_template/email_notificacion', $params_); //--finview
			
			$this->email->setFrom(EMAIL_APP);
			$this->email->setTo( $emailProperty /*"www.witman@gmail.com"*/ );
			$this->email->setSubject($subject);
			$this->email->setMessage($body);
			$resultSend01 = $this->email->send();
			
		}
		echo "SUCCESS";
	}
	//mandar las notificacioneds que estan guardadas, mandarlas por correo
	function sendEmail()
	{
		//Cargar Libreria

		//Obtener lista de email
		$objListNotification = $this->Notification_Model->get_rowsEmail(20);
		if ($objListNotification)
			foreach ($objListNotification as $i) {

				//Enviar Email			
				$this->email->setFrom(EMAIL_APP, HELLOW);
				$this->email->setTo($i->to);
				$this->email->setSubject($i->subject);
				$this->email->setMessage("Hola mi nombre es:" . $i->title . " agende una cita con el objetivo " . $i->message . " (" . $i->phoneFrom . " " . $i->from . ")");
				$data["sendOn"]			= date_format(date_create(), "Y-m-d H:i:s");
				$data["sendEmailOn"]	= date_format(date_create(), "Y-m-d H:i:s");
				$this->Notification_Model->update_app_posme($i->notificationID, $data);
				$resultSend01 			= $this->email->send();
			}

		echo "SUCCESS";
	}
	function sendWhatsappPosMeSendMessage()
	{
		//Cargar Libreria
		//Obtener lista de email
		$objListNotification = $this->Notification_Model->get_rowsWhatsappPosMeSendMessage(20);
		if ($objListNotification)
		{
			foreach ($objListNotification as $i) 
			{


				/////////////////////////////////////////////
				/////////////////////////////////////////////
				/////////////////////////////////////////////
				//Enviar Whatsapp
				/////////////////////////////////////////////
				/////////////////////////////////////////////
				/////////////////////////////////////////////
				if ($this->core_web_whatsap->validSendMessage(APP_COMPANY)) 
				{
					$this->core_web_whatsap->sendMessageUltramsg(APP_COMPANY,"Hola " . $i->to . " " . $i->message,$i->phoneTo);
					$data["sendOn"]			= date_format(date_create(), "Y-m-d H:i:s");
					$data["sendWhatsappOn"]	= date_format(date_create(), "Y-m-d H:i:s");
					$this->Notification_Model->update_app_posme($i->notificationID, $data);
				}
			}
		}
		echo "SUCCESS";
	}
	function sendWhatsappPosMeCalendar()
	{

		//Obtener lista de email
		$objListNotification = $this->Notification_Model->get_rowsWhatsappPosMeCalendar(20);
		if ($objListNotification)
		{
			foreach ($objListNotification as $i) 
			{


				/////////////////////////////////////////////
				/////////////////////////////////////////////
				/////////////////////////////////////////////
				//Enviar Whatsapp
				/////////////////////////////////////////////
				/////////////////////////////////////////////
				/////////////////////////////////////////////
				if ($this->core_web_whatsap->validSendMessage(APP_COMPANY)) {
					$this->core_web_whatsap->sendMessageUltramsg(APP_COMPANY,"Hola mi nombre es:" . $i->title . " agende una cita con el objetivo " . $i->message . " (" . $i->phoneFrom . " " . $i->from . ")");
					$data["sendOn"]			= date_format(date_create(), "Y-m-d H:i:s");
					$data["sendWhatsappOn"]	= date_format(date_create(), "Y-m-d H:i:s");
					$this->Notification_Model->update_app_posme($i->notificationID, $data);
				}
			}
		}
		echo "SUCCESS";
	}
	
	
	
	/********************************************/
	/********************************************/
	/********************************************/
	///FIN CORE SISTEMA
	/********************************************/
	/********************************************/
	/********************************************/
	
	
	
	
	
	
	//mandar reporte de caja
	function file_job_send_report_daly_reprote_de_caja($companyID = "")
	{
		ini_set('max_execution_time', 0);
		$companyID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
		$versionTest 	= "";

		//Obtener parametros
		$parameterEmail = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$parameterEmail = $parameterEmail->value;

		$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE", APP_COMPANY);
		$parameterBalance = $parameterBalance->value;

		$parameterLastNotification 		= $this->core_web_parameter->getParameter("CORE_LAST_NOTIFICACION", APP_COMPANY);
		$parameterLastNotificationId 	= $parameterLastNotification->parameterID;
		$parameterLastNotification 		= $parameterLastNotification->value;


		$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP", $companyID);
		$parameterDaySleep					= $parameterDaySleep->value;

		$tocken			= '';
		//Obtener compania
		$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
		//Get Logo
		$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);



		$fechaNowWile  		= \DateTime::createFromFormat('Y-m-d', $parameterLastNotification);  			//ahora		
		$fechaNowWile		= $fechaNowWile->modify('-' . $parameterDaySleep . ' day');


		$fechaBeforeWile  	= \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
		$fechaBeforeWile	= $fechaBeforeWile->modify('-' . $parameterDaySleep . ' day');





		$fechaNow  		= $fechaNowWile->format("Y-m-d");
		$fechaBefore	= "";
		if (intval($parameterDaySleep) == 0) {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d 23:59:59");
		} else {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d");
		}

		echo "Procesando Envio: " . $fechaNow . ", Al " . $fechaBefore . "<br/>";


		//Reporte de Caja
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////		
		$authorization		= 0;
		$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN, $tocken, APP_COMPANY, $authorization, $fechaNow, $fechaBefore, 0,0]
		);
		//Get Datos de Facturacion				
		$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?,?);";
		$objDataSales	= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY, $tocken, APP_USERADMIN, $fechaNow, $fechaBefore, 0, "-1", 0,0,0,0]
		);

		$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
		$objDataSalesCredito	= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY, $tocken, APP_USERADMIN, $fechaNow, $fechaBefore, 0, "-1",0]
		);

		//Get Datos de Entrada de Efectivo y Salida				
		$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
		$objDataCash	= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN, $tocken, APP_COMPANY, $authorization, $fechaNow, $fechaBefore, 0, "-1",0]
		);

		$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
		$objDataCashOut	= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN, $tocken, APP_COMPANY, $authorization, $fechaNow, $fechaBefore, 0, "-1",0]
		);


		if (isset($objData))
			$objDataResult["objDetail"]					= $objData;
		else
			$objDataResult["objDetail"]					= NULL;


		if (isset($objDataSales))
			$objDataResult["objSales"]					= $objDataSales;
		else
			$objDataResult["objSales"]					= NULL;

		if (isset($objDataSalesCredito))
			$objDataResult["objSalesCredito"]			= $objDataSalesCredito;
		else
			$objDataResult["objSalesCredito"]			= NULL;


		if (isset($objDataCash))
			$objDataResult["objCash"]					= $objDataCash;
		else
			$objDataResult["objCash"]					= NULL;

		if (isset($objDataCashOut))
			$objDataResult["objCashOut"]				= $objDataCashOut;
		else
			$objDataResult["objCashOut"]				= NULL;

		$params_["message"]							= str_replace(" 00:00:00", "", $fechaNow) . " " . $versionTest . " CAJA : " . $objCompany->name . " 3/4";
		$objDataResult["objCompany"] 				= $objCompany;
		$objDataResult["objLogo"] 					= $objParameter;
		$objDataResult["startOn"] 					= $fechaNow;
		$objDataResult["endOn"] 					= $fechaBefore;
		$objDataResult["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
		$objDataResult["objFirmaEncription"] 		= md5($objDataResult["objFirma"]);

		$body3  				= /*--inicio view*/ view('app_box_report/share/view_a_disemp', $objDataResult);
		$subject3 				= $params_["message"];


		//Calcular el monto total
		$montoTotal = 0;
		//Abonos
		if ($objDataResult["objDetail"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objDetail"], function ($var) {
				if (strtoupper($var["moneda"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'montoTotal'));
		}

		//Ventas de Contado
		if ($objDataResult["objSales"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objSales"], function ($var) {
				if (strtoupper($var["currencyName"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'totalDocument'));
		}

		//Ventas de Credito Prima
		if ($objDataResult["objSalesCredito"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objSalesCredito"], function ($var) {
				if (strtoupper($var["currencyName"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'receiptAmount'));
		}

		//Ingreos de Caja
		if ($objDataResult["objCash"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objCash"], function ($var) {
				if (strtoupper($var["moneda"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'montoTransaccion'));
		}

		//Salida de Caja
		if ($objDataResult["objCashOut"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objCashOut"], function ($var) {
				if (strtoupper($var["moneda"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal - array_sum(array_column($objTempoDetail, 'montoTransaccion'));
		}
		$objDataResult["mensaje"] 		= "Monto en caja: " . $montoTotal;





		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		//Enviar Correos
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject3);
		$this->email->setMessage($body3);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject3);
		$this->email->setMessage($body3);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);



		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		//Enviar Whatsapp
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		if ($this->core_web_whatsap->validSendMessage(APP_COMPANY)) {
			$this->core_web_whatsap->sendMessage(APP_COMPANY, $objDataResult["mensaje"]);
		}

		return view('core_template/close'); //--finview-r

	}
	//mandar reporte de detalle de ventas
	function file_job_send_report_daly_reprote_de_detalle_de_ventas($companyID = "")
	{
		ini_set('max_execution_time', 0);
		$companyID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
		$versionTest 	= ":007:";

		//Obtener parametros
		$parameterEmail = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$parameterEmail = $parameterEmail->value;

		$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE", APP_COMPANY);
		$parameterBalance = $parameterBalance->value;

		$parameterLastNotification 		= $this->core_web_parameter->getParameter("CORE_LAST_NOTIFICACION", APP_COMPANY);
		$parameterLastNotificationId 	= $parameterLastNotification->parameterID;
		$parameterLastNotification 		= $parameterLastNotification->value;


		$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP", $companyID);
		$parameterDaySleep					= $parameterDaySleep->value;

		$tocken			= '';
		//Obtener compania
		$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
		//Get Logo
		$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);



		$fechaNowWile  		= \DateTime::createFromFormat('Y-m-d', $parameterLastNotification);  			//ahora		
		$fechaNowWile		= $fechaNowWile->modify('-' . $parameterDaySleep . ' day');


		$fechaBeforeWile  	= \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
		$fechaBeforeWile	= $fechaBeforeWile->modify('-' . $parameterDaySleep . ' day');





		$fechaNow  		= $fechaNowWile->format("Y-m-d");
		$fechaBefore	= "";
		if (intval($parameterDaySleep) == 0) {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d 23:59:59");
		} else {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d");
		}
		echo "Procesando Envio: " . $fechaNow . ", Al " . $fechaBefore . "<br/>";

		//Reporte de Venta
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		//Obtener ventas
		$query			= "CALL pr_sales_get_report_sales_detail(?,?,?,?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY, $tocken, APP_USERADMIN, $fechaNow, $fechaBefore, 0, 0,0]
		);


		if (isset($objData)) {
			$objDataResult["objDetail"]				= $objData;
		} else {
			$objDataResult["objDetail"]				= $objData;
		}



		//parametros de reportes
		$params_["objCompany"]			= $objCompany;
		$params_["objStartOn"]			= str_replace(" 00:00:00", "", $fechaNow);
		$params_["objEndOn"]			= str_replace(" 00:00:00", "", $fechaBefore);
		$params_["objDetail"]			= $objDataResult["objDetail"];

		$params_["message"]			= str_replace(" 00:00:00", "", $fechaNow) . " " . $versionTest . " VENTAS: " . $objCompany->name . " 1/4";
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
		$params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
		$params_["objFirmaEncription"] 			= md5($params_["objFirma"]);

		//vista
		$subject1 				= $params_["message"];
		$body1  				= /*--inicio view*/ view('app_sales_report/sales_detail/view_a_disemp_email', $params_); //--finview







		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		//Enviar Correos
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject1);
		$this->email->setMessage($body1);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject1);
		$this->email->setMessage($body1);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);

		return view('core_template/close'); //--finview-r

	}
	//mandar reporte de transacciones regitradas
	function file_job_send_report_daly_reprote_de_tran_registradas($companyID = "")
	{
		ini_set('max_execution_time', 0);
		$companyID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
		$versionTest 	= ":007:";

		//Obtener parametros
		$parameterEmail = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$parameterEmail = $parameterEmail->value;

		$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE", APP_COMPANY);
		$parameterBalance = $parameterBalance->value;

		$parameterLastNotification 		= $this->core_web_parameter->getParameter("CORE_LAST_NOTIFICACION", APP_COMPANY);
		$parameterLastNotificationId 	= $parameterLastNotification->parameterID;
		$parameterLastNotification 		= $parameterLastNotification->value;


		$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP", $companyID);
		$parameterDaySleep					= $parameterDaySleep->value;

		$tocken			= '';
		//Obtener compania
		$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
		//Get Logo
		$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);



		$fechaNowWile  		= \DateTime::createFromFormat('Y-m-d', $parameterLastNotification);  			//ahora		
		$fechaNowWile		= $fechaNowWile->modify('-' . $parameterDaySleep . ' day');


		$fechaBeforeWile  	= \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
		$fechaBeforeWile	= $fechaBeforeWile->modify('-' . $parameterDaySleep . ' day');





		$fechaNow  		= $fechaNowWile->format("Y-m-d");
		$fechaBefore	= "";
		if (intval($parameterDaySleep) == 0) {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d 23:59:59");
		} else {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d");
		}
		echo "Procesando Envio: " . $fechaNow . ", Al " . $fechaBefore . "<br/>";



		//Reporte de Transacciones Anuladas
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////	
		$params_["message"]			= str_replace(" 00:00:00", "", $fechaNow) . " " . $versionTest . " T-ANULADAS - REGISTRADAS: " . $objCompany->name . " 4/4";

		$query			= "CALL pr_transaction_report_registradas_anuladas(?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY, $tocken, APP_USERADMIN, $fechaNow, $fechaBefore]
		);


		if (isset($objData)) {
			$objDataResult["objDetail"]				= $objData;
		} else {
			$objDataResult["objDetail"]				= $objData;
		}

		$params_["objCompany"]			= $objCompany;
		$params_["objStartOn"]			= str_replace(" 00:00:00", "", $fechaNow);
		$params_["objEndOn"]			= str_replace(" 00:00:00", "", $fechaBefore);
		$params_["objDetail"]			= $objDataResult["objDetail"];
		$params_["objFirma"] 			= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_transaction_report_registradas_anuladas" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
		$params_["objFirmaEncription"] 	= md5($params_["objFirma"]);


		$body4 							= /*--inicio view*/ view('app_sales_report/transaction_anuladas/view_a_disemp_email', $params_); //--finview
		$subject4 						= $params_["message"];






		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		//Enviar Correos
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject4);
		$this->email->setMessage($body4);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject4);
		$this->email->setMessage($body4);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";







		return view('core_template/close'); //--finview-r

	}
	//mandar reporte de compras
	function file_job_send_report_daly_reprote_de_compras($companyID = "")
	{
		ini_set('max_execution_time', 0);
		$companyID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
		$versionTest 	= ":007:";

		//Obtener parametros
		$parameterEmail = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$parameterEmail = $parameterEmail->value;

		$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE", APP_COMPANY);
		$parameterBalance = $parameterBalance->value;

		$parameterLastNotification 		= $this->core_web_parameter->getParameter("CORE_LAST_NOTIFICACION", APP_COMPANY);
		$parameterLastNotificationId 	= $parameterLastNotification->parameterID;
		$parameterLastNotification 		= $parameterLastNotification->value;


		$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP", $companyID);
		$parameterDaySleep					= $parameterDaySleep->value;

		$tocken			= '';
		//Obtener compania
		$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
		//Get Logo
		$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);



		$fechaNowWile  		= \DateTime::createFromFormat('Y-m-d', $parameterLastNotification);  			//ahora		
		$fechaNowWile		= $fechaNowWile->modify('-' . $parameterDaySleep . ' day');


		$fechaBeforeWile  	= \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
		$fechaBeforeWile	= $fechaBeforeWile->modify('-' . $parameterDaySleep . ' day');





		$fechaNow  		= $fechaNowWile->format("Y-m-d");
		$fechaBefore	= "";
		if (intval($parameterDaySleep) == 0) {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d 23:59:59");
		} else {
			$fechaBefore	= $fechaBeforeWile->format("Y-m-d");
		}
		echo "Procesando Envio: " . $fechaNow . ", Al " . $fechaBefore . "<br/>";



		//Reporte de Buy
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		//Obtener Resument por transaccin
		$query			= "CALL pr_notification_buy(?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY, $tocken, APP_USERADMIN, $fechaNow, $fechaBefore]
		);


		if (isset($objData)) {
			$objDataResultBy["objDetail"]				= $objData;
		} else {
			$objDataResultBy["objDetail"]				= $objData;
		}



		//parametros de reportes
		$params_["objCompany"]			= $objCompany;
		$params_["objStartOn"]			= str_replace(" 00:00:00", "", $fechaNow);
		$params_["objEndOn"]			= str_replace(" 00:00:00", "", $fechaBefore);
		$params_["objDetail"]			= $objDataResultBy["objDetail"];

		$params_["message"]						= str_replace(" 00:00:00", "", $fechaNow) . " " . $versionTest . " RESUMEN DE TRANSACCION: " . $objCompany->name . " 2/4";
		$params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
		$params_["objFirmaEncription"] 			= md5($params_["objFirma"]);

		//vista
		$subject2 			= $params_["message"];
		$body2  			= /*--inicio view*/ view('app_notification/report_buy/view_a_disemp_email', $params_); //--finview		






		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		//Enviar Correos
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		/////////////////////////////////////////////
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo($parameterEmail);
		$this->email->setSubject($subject2);
		$this->email->setMessage($body2);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);
		//enviar al administrador de posme
		$this->email->setFrom(EMAIL_APP);
		$this->email->setTo(EMAIL_APP_COPY);
		$this->email->setSubject($subject2);
		$this->email->setMessage($body2);
		$resultSend01 = $this->email->send();
		$resultSend02 = $this->email->printDebugger();
		echo "*****************************<br/>";
		echo print_r($resultSend02, true);
		echo "*****************************<br/>";
		sleep(60);

		return view('core_template/close'); //--finview-r

	}
	//mandar informe de moniotores de caja
	function file_monitory_cash_box($companyID = "")
	{
		ini_set('max_execution_time', 0);
		$companyID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
		$versionTest 	= ":007:";

		//Obtener parametros
		$parameterEmail 		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$parameterEmail 		= $parameterEmail->value;

		$parameterAmountCash 	= $this->core_web_parameter->getParameter("INVOICE_BILLING_BOX_MAX_AMOUNT", APP_COMPANY);
		$parameterAmountCash 	= $parameterAmountCash->value;

		$tocken			= '';
		//Obtener compania
		$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
		//Get Logo
		$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);


		$fechaStart 	= \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
		$fechaStart		= $fechaStart->modify('-0 day');
		$fechaStart		= $fechaStart->format("Y-m-d 00:00:00");

		$fechaEnd  		= \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
		$fechaEnd		= $fechaEnd->modify('-0 day');
		$fechaEnd		= $fechaEnd->format("Y-m-d 23:59:59");


		echo "Procesando Envio: " . $fechaStart . ", Al " . $fechaEnd . "<br/>";
		//Reporte de Caja
		//
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////		
		$authorization		= 0;
		$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
		$objData		= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN, $tocken, APP_COMPANY, $authorization, $fechaStart, $fechaEnd, 0,0]
		);
		//Get Datos de Facturacion				
		$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?,?);";
		$objDataSales	= $this->Bd_Model->executeRender(
			$query,
			[APP_COMPANY, $tocken, APP_USERADMIN, $fechaStart, $fechaEnd, 0, "-1", 0,0,0,0]
		);

		$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
		$objDataSalesCredito	= $this->Bd_Model->executeRender(
			$query,
			[$companyID, $tocken, APP_USERADMIN, $fechaStart, $fechaEnd, 0, "-1",0]
		);


		//Get Datos de Entrada de Efectivo y Salida				
		$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
		$objDataCash	= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN, $tocken, APP_COMPANY, $authorization, $fechaStart, $fechaEnd, 0, "-1",0]
		);

		$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
		$objDataCashOut	= $this->Bd_Model->executeRender(
			$query,
			[APP_USERADMIN, $tocken, APP_COMPANY, $authorization, $fechaStart, $fechaEnd, 0, "-1",0]
		);


		if (isset($objData))
			$objDataResult["objDetail"]					= $objData;
		else
			$objDataResult["objDetail"]					= NULL;


		if (isset($objDataSales))
			$objDataResult["objSales"]					= $objDataSales;
		else
			$objDataResult["objSales"]					= NULL;

		if (isset($objDataSalesCredito))
			$objDataResult["objSalesCredito"]			= $objDataSalesCredito;
		else
			$objDataResult["objSalesCredito"]			= NULL;


		if (isset($objDataCash))
			$objDataResult["objCash"]					= $objDataCash;
		else
			$objDataResult["objCash"]					= NULL;

		if (isset($objDataCashOut))
			$objDataResult["objCashOut"]				= $objDataCashOut;
		else
			$objDataResult["objCashOut"]				= NULL;


		$montoTotal = 0;

		//Abonos
		if ($objDataResult["objDetail"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objDetail"], function ($var) {
				if (strtoupper($var["moneda"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'montoTotal'));
		}

		//Ventas de Contado
		if ($objDataResult["objSales"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objSales"], function ($var) {
				if (strtoupper($var["currencyName"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'totalDocument'));
		}

		//Ventas de Credito Prima
		if ($objDataResult["objSalesCredito"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objSalesCredito"], function ($var) {
				if (strtoupper($var["currencyName"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'receiptAmount'));
		}

		//Ingreos de Caja
		if ($objDataResult["objCash"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objCash"], function ($var) {
				if (strtoupper($var["moneda"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal + array_sum(array_column($objTempoDetail, 'montoTransaccion'));
		}

		//Salida de Caja
		if ($objDataResult["objCashOut"] != null) {
			$objTempoDetail 	= array_filter($objDataResult["objCashOut"], function ($var) {
				if (strtoupper($var["moneda"]) == "CORDOBA")
					return true;
			});
			$montoTotal = $montoTotal - array_sum(array_column($objTempoDetail, 'montoTransaccion'));
		}


		$params_["message"]							= str_replace(" 00:00:00", "", $fechaStart) . " " . $versionTest . " CAJA : " . $objCompany->name . " 3/4";
		$objDataResult["mensaje"] 					= "Monto en caja: " . $montoTotal;
		$objDataResult["objCompany"] 				= $objCompany;
		$objDataResult["objLogo"] 					= $objParameter;
		$objDataResult["startOn"] 					= $fechaStart;
		$objDataResult["endOn"] 					= $fechaEnd;
		$objDataResult["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
		$objDataResult["objFirmaEncription"] 		= md5($objDataResult["objFirma"]);
		$body3  				= /*--inicio view*/ view('core_template/email_notificacion', $objDataResult);
		$subject3 				= $params_["message"];




		if ($parameterAmountCash < $montoTotal &&  ($parameterAmountCash != 0)) {
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			//Enviar Correos
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			$this->email->setFrom(EMAIL_APP);
			$this->email->setTo($parameterEmail);
			$this->email->setSubject($subject3);
			$this->email->setMessage($body3);
			$resultSend01 = $this->email->send();
			$resultSend02 = $this->email->printDebugger();
			echo "*****************************<br/>";
			echo print_r($resultSend02, true);
			echo "*****************************<br/>";
			sleep(60);

			/////////////////////////////////////////////
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			//Enviar Whatsapp
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			/////////////////////////////////////////////
			if ($this->core_web_whatsap->validSendMessage(APP_COMPANY)) {
				$this->core_web_whatsap->sendMessage(APP_COMPANY, $objDataResult["mensaje"]);
			}
		}

		return view('core_template/close'); //--finview-r

	}
	function file_job_send_report_daly_share_sumary_80mm_general()
	{
		try {

			ini_set('max_execution_time', 0);
			$companyID			= 2;
			$branchID			= 2;
			$userID				= 2;
			$tocken				= '';
			$authorization		= "1";

			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "viewReport"); //--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "startOn"); //--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "endOn"); //--finuri		
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "hourStart"); //--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "hourEnd"); //--finuri
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "userIDFilter"); //--finuri	;
			$format				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "format"); //--finuri	;


			$format 			= $format ?  $format : "empty";
			$viewReport			= $viewReport ? $viewReport  : "empty";
			$startOn			= $startOn ? $startOn : "empty";
			$endOn				= $endOn ?  $endOn : "empty";
			$hourOn				= $hourOn ?	$hourOn : "00";
			$hourEnd			= $hourEnd ? $hourEnd : "23";
			$userIDFilter		= $userIDFilter ? $userIDFilter : "1";



			//Obtener parametros email del propietario
			$parameterEmail 		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
			$parameterEmail 		= $parameterEmail->value;

			//Obtener la ultima notificacion
			$parameterLastNotification 		= $this->core_web_parameter->getParameter("CORE_LAST_NOTIFICACION", APP_COMPANY);
			$parameterLastNotificationId 	= $parameterLastNotification->parameterID;
			$parameterLastNotification 		= $parameterLastNotification->value;

			//Obtener el deslizamineto del reprote
			$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP", $companyID);
			$parameterDaySleep					= $parameterDaySleep->value;


			//Obtener la fecha inicial y fecha final del reporte
			$fechaNowWile  		= \DateTime::createFromFormat('Y-m-d', $parameterLastNotification);  			//ahora		
			$fechaNowWile		= $fechaNowWile->modify('+' . $parameterDaySleep . ' day');
			$fechaNow			= $fechaNowWile->format("Y-m-d");

			$fechaDate				= date('Y-m-d');
			$fechaActual			= \DateTime::createFromFormat('Y-m-d', $fechaDate);
			$fechaActual			= $fechaActual->modify('-' . $parameterDaySleep . ' day');
			$fechaBefore			= $fechaActual->format("Y-m-d") . " 23:59:59";

			if ($fechaActual < $fechaNowWile) {
				$fechaActual			= $fechaActual->modify('+' . $parameterDaySleep . ' day');
				$fechaBefore			= $fechaActual->format("Y-m-d") . " 23:59:59";
			}


			//Es el repoete de pantalla
			if ($format == "pdf") {
				$fechaNow		= $startOn . " " . $hourOn . ":00:00";
				$fechaBefore	= $endOn . " " . $hourEnd . ":59:59";
			}
			//Es el envio de correo automatico
			else {

				$fechaBeforeWile  				= \DateTime::createFromFormat('Y-m-d', $fechaDate);
				$dataNewParameter 				= array();
				$fechaBeforeWile				= $fechaBeforeWile->modify('-' . $parameterDaySleep . ' day');
				$dataNewParameter["value"] 		= $fechaBeforeWile->format("Y-m-d");
				$this->Company_Parameter_Model->update_app_posme($companyID, $parameterLastNotificationId, $dataNewParameter);
			}



			//Procesar reporte			
			$obUserModel	= $this->User_Model->get_rowByPK($companyID, $branchID, $userIDFilter);
			$companyID 		= $companyID;
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);


			$query				= "CALL pr_box_get_report_closed(?,?,?,?,?,?,?);";
			if ($objCompany->type == "galmcuts")
				$query			= "CALL pr_box_get_report_closed_glamcuts(?,?,?,?,?,?,?);";
			if ($objCompany->type == "gym_power_house")
				$query			= "CALL pr_box_get_report_closed_gym(?,?,?,?,?,?,?);";


			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID, $tocken, $companyID, $authorization, $fechaNow, $fechaBefore, $userIDFilter]
			);

			if (isset($objData))
				$objDataResult["objDetail"]					= $objData;
			else
				$objDataResult["objDetail"]					= NULL;



			//parametros de reportes
			$params_["objCompany"]					= $objCompany;
			$params_["objStartOn"]					= str_replace(" 00:00:00", "", $fechaNow);
			$params_["startOn"]						= str_replace(" 00:00:00", "", $fechaNow);
			$params_["objEndOn"]					= str_replace(" 00:00:00", "", $fechaBefore);
			$params_["endOn"]						= str_replace(" 00:00:00", "", $fechaBefore);
			$params_["dateCurrent"]					= date("Y-m-d H:i:s");
			$params_["obUserModel"]					= $obUserModel;
			$params_["objDetail"]					= $objDataResult["objDetail"];
			$params_["objLogo"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			$params_["message"]						= str_replace(" 00:00:00", "", $fechaNow) . " CIERRE DE CAJA: " . $objCompany->name . " ";
			$params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_box_get_report_closed" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
			$params_["objFirmaEncription"] 			= md5($params_["objFirma"]);
			$subject								= $params_["message"];
			$html  									= /*--inicio view*/ view('app_box_report/share_summary_80mm_general/view_a_disemp', $params_); //--finview

			if ($format != "pdf") {

				echo $html;

				//enviar correo
				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo($parameterEmail);
				$this->email->setSubject($subject);
				$this->email->setMessage($html);
				$resultSend01 = $this->email->send();
				$resultSend02 = $this->email->printDebugger();


				//enviar correo
				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo(EMAIL_APP_COPY);
				$this->email->setSubject($subject);
				$this->email->setMessage($html);
				$resultSend01 = $this->email->send();
				$resultSend02 = $this->email->printDebugger();
			} 
			else 
			{
				$this->dompdf->loadHTML($html);
				$this->dompdf->render();
				$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
				$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
				$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW", $companyID);
				$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
				$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;

				$fileNamePut = "caja_0_" . date("dmYhis") . ".pdf";
				$path        = "./resource/file_company/company_" . $companyID . "/component_48/component_item_0/" . $fileNamePut;

				//Crear la Carpeta para almacenar los Archivos del Documento
				$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_0";							
				if (!file_exists($documentoPath))
				{
					mkdir($documentoPath, 0755, true);
					chmod($documentoPath, 0755);
				}
				
				
				file_put_contents($path, $this->dompdf->output());
				chmod($path, 644);

				if ($objParameterShowLinkDownload == "true") {
					echo "<a href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_48/component_item_0/" . $fileNamePut . "'>download caja</a>";
				} else {
					//visualizar
					$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview]);
				}
			}
		} catch (\Exception $ex) {
			exit($ex->getMessage());
		}
	}
	//mandar informe de moniotores de caja
	function file_next_date($companyID = "")
	{
		ini_set('max_execution_time', 0);
		$companyID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);


		$parameterLastNotification 		= $this->core_web_parameter->getParameter("CORE_LAST_NOTIFICACION", APP_COMPANY);
		$parameterLastNotificationId 	= $parameterLastNotification->parameterID;
		$parameterLastNotification 		= $parameterLastNotification->value;



		$parameterDaySleep					= $this->core_web_parameter->getParameter("INVOICE_BILLING_DAY_SLEEP", $companyID);
		$parameterDaySleep					= $parameterDaySleep->value;



		$fechaBeforeWile  				= \DateTime::createFromFormat('Y-m-d', $parameterLastNotification);
		$dataNewParameter 				= array();
		$fechaBeforeWile				= $fechaBeforeWile->modify('+' . $parameterDaySleep . ' day');
		$dataNewParameter["value"] 		= $fechaBeforeWile->format("Y-m-d");
		//$this->Company_Parameter_Model->update_app_posme($companyID,$parameterLastNotificationId,$dataNewParameter);	
		return view('core_template/close'); //--finview-r

	}
	//job o proceso que me permite cancelar las facturas con balances 0 a 0.2
	function file_job_process_customer_credit_document_to_cancel($companyID = "")
	{
		$companyID 						= helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
		$objListCustomerCreditDocument 	= $this->Customer_Credit_Document_Model->get_rowByBalanceBetweenCeroAndCeroPuntoCinco($companyID);

		$parameterCancelCuota 		= $this->core_web_parameter->getParameter("SHARE_CANCEL", APP_COMPANY);
		$parameterCancelCuota 		= $parameterCancelCuota->value;

		$parameterCancelDocumento 		= $this->core_web_parameter->getParameter("SHARE_DOCUMENT_CANCEL", APP_COMPANY);
		$parameterCancelDocumento 		= $parameterCancelDocumento->value;


		//recorarer lista de documentos
		if ($objListCustomerCreditDocument) {

			foreach ($objListCustomerCreditDocument as $objCustomCreditDocument) {
				$objCustomCreditAmoritization = $this->Customer_Credit_Amortization_Model->get_rowByCreditDocumentAndBalanceMinim($objCustomCreditDocument->customerCreditDocumentID);
				if ($objCustomCreditAmoritization) {

					if (count($objCustomCreditAmoritization) == 1) {

						//recorrar lista de cuotas de amortizacion
						foreach ($objCustomCreditAmoritization as $i) {
							$data 					= null;
							$data["remaining"] 		= 0;
							$data["statusID"] 		= $parameterCancelCuota;
							$this->Customer_Credit_Amortization_Model->update_app_posme($i->creditAmortizationID, $data);


							$data 					= null;
							$data["balance"] 		= 0;
							$data["statusID"] 		= $parameterCancelDocumento;
							$this->Customer_Credit_Document_Model->update_app_posme($objCustomCreditDocument->customerCreditDocumentID, $data);
						}
					}
				}
			}
		}


		return view('core_template/close'); //--finview-r

	}
	
	
	
	
	
	/********************************************/
	/********************************************/
	/********************************************/
	///NOTIFICACIONES PERSONALIZADAS 
	/********************************************/
	/********************************************/
	/********************************************/
	
	function sendWhatsappGlobalProCompraFrecuency1Meses()
	{

		$objNotificar = $this->Transaction_Master_Detail_Model->GlobalPro_get_Notification_Compra_1Meses();
		if ($objNotificar)
			foreach ($objNotificar as $i) {
				echo clearNumero($i->Destino) . "---" . $i->Mensaje . "</br></br>";
				$this->core_web_whatsap->sendMessageByLiveconnect(
					APP_COMPANY,
					replaceSimbol($i->Mensaje),
					clearNumero($i->Destino)
				);
			}

		echo "SUCCESS";
	}
	function sendWhatsappGlobalProLaptopMenorA14400Frecuency7Meses()
	{

		$objNotificar = $this->Transaction_Master_Detail_Model->GlobalPro_get_Notification_LaptopMenorA14400_7Meses();
		if ($objNotificar)
			foreach ($objNotificar as $i) {
				echo clearNumero($i->Destino) . "---" . $i->Mensaje . "</br></br>";
				$this->core_web_whatsap->sendMessageByLiveconnect(
					APP_COMPANY,
					replaceSimbol($i->Mensaje),
					clearNumero($i->Destino)
				);
			}

		echo "SUCCESS";
	}
	function sendWhatsappGlobalProLaptopMayoresA14400Frecuency11Meses()
	{

		$objNotificar = $this->Transaction_Master_Detail_Model->GlobalPro_get_Notification_LaptopMayoresA14400_11Meses();
		if ($objNotificar)
			foreach ($objNotificar as $i) {
				echo clearNumero($i->Destino) . "---" . $i->Mensaje . "</br></br>";
				$this->core_web_whatsap->sendMessageByLiveconnect(
					APP_COMPANY,
					replaceSimbol($i->Mensaje),
					clearNumero($i->Destino)
					/*"50587125827"*/
					/*"50557165864"*/
				);
			}

		echo "SUCCESS";
	}
	function sendWhatsappGlobalProCumpleAnnos()
	{

		$objNotificar = $this->Transaction_Master_Detail_Model->GlobalPro_get_Notification_CumpleAnnos();
		if ($objNotificar)
		{
			foreach ($objNotificar as $i) 
			{
				echo clearNumero($i->Destino) . "---" . $i->Mensaje . "</br></br>";
				
				
				$this->core_web_whatsap->sendMessageByLiveconnectFileGlobalPro(
					APP_COMPANY,
					replaceSimbol($i->Mensaje),	
					/*"50587125827"*/
					clearNumero($i->Destino),
					"https://posme.net/v4posme/globalpro/public/resource/img/feliz_cumple.jpeg",
					"feliz_cumple",
					"jpeg"
				);
				
				$this->core_web_whatsap->sendMessageByLiveconnect(
					APP_COMPANY,
					replaceSimbol($i->Mensaje),					
					/*"50587125827"*/
					clearNumero($i->Destino)
				);
				
				
				
			}
		}
		echo "SUCCESS";
	}
	function sendEmailGlamCustCitas()
	{
		log_message("error", print_r("sendEmailGlamCustCitas", true));
		$emailProperty = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$emailProperty = $emailProperty->value;
		$objCompany  	= $this->Company_Model->get_rowByPK(APP_COMPANY);

		$objNotificar = $this->Transaction_Master_Detail_Model->GlamCust_get_Citas(APP_COMPANY);
		if ($objNotificar)
			foreach ($objNotificar as $i) {


				$i->SiguienteVisita = \DateTime::createFromFormat('Y-m-d H:i:s', $i->SiguienteVisita)->format("Y-m-d h:i A");
				echo "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita . "</br>";
				log_message("error", "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita);

				$params_["objCompany"]  = $objCompany;
				$params_["firstName"]  	= $i->firstName;
				$params_["hour"]  		= $i->SiguienteVisita;
				$params_["mensaje"]  	= "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita;
				$subject 				= "Cita de: " . $i->firstName;
				$body  					= /*--inicio view*/ view('core_template/email_notificacion', $params_); //--finview

				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo($emailProperty /*"www.witman@gmail.com"*/);
				$this->email->setSubject($subject);
				$this->email->setMessage($body);
				$resultSend01 = $this->email->send();
			}

		echo "SUCCESS";
	}
	function sendEmailGlamCustCitasFrecuency2DayBefore()
	{
		log_message("error", print_r("sendEmailGlamCustCitas", true));
		$emailProperty = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$emailProperty = $emailProperty->value;
		$objCompany  	= $this->Company_Model->get_rowByPK(APP_COMPANY);

		$objNotificar = $this->Transaction_Master_Detail_Model->GlamCust_get_Citas_2DayBefore(APP_COMPANY);
		if ($objNotificar)
			foreach ($objNotificar as $i) {


				$i->SiguienteVisita = \DateTime::createFromFormat('Y-m-d H:i:s', $i->SiguienteVisita)->format("Y-m-d h:i A");
				echo "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita . "</br>";
				log_message("error", "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita);

				$params_["objCompany"]  = $objCompany;
				$params_["firstName"]  	= $i->firstName;
				$params_["hour"]  		= $i->SiguienteVisita;
				$params_["mensaje"]  	= "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita;
				$subject 				= "Cita de: " . $i->firstName;
				$body  					= /*--inicio view*/ view('core_template/email_notificacion', $params_); //--finview

				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo($emailProperty /*"www.witman@gmail.com"*/);
				$this->email->setSubject($subject);
				$this->email->setMessage($body);
				$resultSend01 = $this->email->send();
			}

		echo "SUCCESS";
	}

	function sendEmailAudioElPipeCitas()
	{
		log_message("error", print_r("sendEmailAudioElPipeCitas", true));
		$emailProperty = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL", APP_COMPANY);
		$emailProperty = $emailProperty->value;
		$objCompany  	= $this->Company_Model->get_rowByPK(APP_COMPANY);

		$objNotificar = $this->Transaction_Master_Detail_Model->AudioElPipe_get_Citas(APP_COMPANY);
		if ($objNotificar)
			foreach ($objNotificar as $i) {
                $detail             = $this->Transaction_Master_Detail_Model->get_rowByTransaction($objCompany->companyID, 19/*transaction id: factura*/,$i->transactionMasterID);

				$i->SiguienteVisita = \DateTime::createFromFormat('Y-m-d H:i:s', $i->SiguienteVisita)->format("Y-m-d h:i A");
				echo "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita . "</br> Enviada a: ".$emailProperty. " [[".$i->Notas."]]"."</br></br>";
				log_message("error", "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita." [[".$i->Notas."]]");

				$params_["objCompany"]      = $objCompany;
				$params_["firstName"]  	    = $i->firstName;
				$params_["hour"]  		    = $i->SiguienteVisita;
				$params_["mensaje"]  	    = "Cita de: " . $i->firstName . " programada para : " . $i->SiguienteVisita ." [[".$i->Notas."]] <br />" ;
				$params_["mensaje"]  	    .= "Con los siguientes detalles a revisar:";
                $params_["mensaje"]         .="<ol style='margin-left:20px'>";
                foreach($detail as $item){
                    $params_["mensaje"]     .= "<li>".$item->itemName."</li>";
                }
                $params_["mensaje"]         .= "</ol>";
				$subject 				    = "Cita de: " . $i->firstName;
				$body  					    = /*--inicio view*/ view('core_template/email_notificacion', $params_); //--finview

				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo($emailProperty /*"www.witman@gmail.com"*/);
				$this->email->setSubject($subject);
				$this->email->setMessage($body);
				$resultSend01 = $this->email->send();
			}

		echo "SUCCESS";
	}
	function sendWhatsappDiarioChochoMandado()
	{

		//Obtener la lista de recordatorios
		$tagName			= "ENVIAR WHATSAPP A CLIENTE";
		$objTag				= $this->Tag_Model->get_rowByName($tagName);
		
		$objListRemember 	= $this->Remember_Model->getNotificationCompanyByTagId(APP_COMPANY,$objTag->tagID);
		$objParameter		= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT", APP_COMPANY);
		$characterSplie 	= $objParameter->value;
		$chatSend			= [];
		$session 			= session();
		$mensaje			= "";
		$mensageByPage		= 100;
		
		
		//Si no existe la variable session obtener los chat
		if ( !$session->has('chatSend') )
		{
			
			if(!$objListRemember)
			return;
		
			
			foreach($objListRemember as $objRemember)
			{
				if($objRemember->leerFile == 0)
					continue;
				
				
				
				//Obtener la lista de mensajes
				$path 	= PATH_FILE_OF_APP . "/company_" . APP_COMPANY . "/component_76/component_item_" . $objRemember->rememberID;
				$path 	= $path . '/send.csv';
				if (!file_exists($path))
					continue;
				
				
				$this->csvreader->separator = $characterSplie;
				$table 						= $this->csvreader->parse_file($path);

				echo "est";
				if (!$table)
					continue;
				
				if (count($table) <= 0) 
					continue;
					
				
				if (!array_key_exists("Destino", $table[0])) {
					$table = null;
				}
				if (!array_key_exists("Mensaje", $table[0])) {
					$table = null;
				}

				if (is_null($table))
					continue;
				
				$objListCustomer = array();
				foreach ($table as $row) 
				{
					$rowx 					= array();
					$rowx["firstName"] 		= "";
					$rowx["phoneNumber"] 	= $row["Destino"];
					$rowx["mensaje"] 		= $row["Mensaje"];
					$rowx["urlImage"]		= array_key_exists("Imagen", $row) ? $row["Imagen"] : "";
					$rowx["rememberID"]		= $objRemember->rememberID;
					$rowx["key"]			= $rowx["phoneNumber"].$row["Mensaje"].$rowx["urlImage"];
					array_push($objListCustomer, $rowx);
				}	
				
				//Obtener los clientes unicos eliminar los repetidos
				$objListCustomerNuevoArray 		= [];
				$objListCustomerUnicos 			= [];
				foreach ($objListCustomer as $obj) {
					if (!in_array($obj["key"], $objListCustomerUnicos)) {
						$objListCustomerUnicos[] 	 = $obj["key"];
						$objListCustomerNuevoArray[] = $obj;
						$chatSend[]					 = $obj;
					}
				}
			}
		} 
		else 
		{
			$chatSend  = $session->get('chatSend');
		}


		
		
		
		
		
		
		$mensaje 			=  "Envio de: ".count($chatSend)." mensajes.</br></br>";	
		$chatSendTemp		= [];
		$counter 			= 0;
		foreach($chatSend as $customer)
		{
			$counter		= $counter+1;
			if($counter <= $mensageByPage)
			{
				$phoneNumber 	= clearNumero($customer["phoneNumber"]);
				$menssage		= $customer["mensaje"];//replaceSimbol($customer["mensaje"]);
				$rememberID		= $customer["rememberID"];
				$imagen			= $customer["urlImage"];
				$mensaje 		= $mensaje."Mensaje No: ".$counter." de ".count($chatSend)."  Al telefono: ".$phoneNumber . " **** Mensaje:" . $menssage. " **** Imagen: ".$imagen."</br></br>";				
				
				//50584766457
				if( strlen($phoneNumber) != 11)
					continue;
				
		
				if($menssage != "")
				{
					$this->core_web_whatsap->sendMessageByLiveconnect(
						APP_COMPANY,
						$menssage,										
						$phoneNumber
					);
				}
				
				if($imagen != "")
				{	
					// Obtener el nombre del archivo con la extensión
					$pathUrl 		= base_url()."/resource/file_company/company_2/component_76/component_item_".$rememberID."/".$imagen;
					$fileNameFull 	= basename($pathUrl);
					$extension 		= pathinfo($fileNameFull, PATHINFO_EXTENSION);
					$fileName 		= pathinfo($fileNameFull, PATHINFO_FILENAME);
					$this->core_web_whatsap->sendMessageByLiveconnectFile(
						APP_COMPANY,
						$menssage,						
						$phoneNumber,
						$pathUrl,
						"buscame",
						$extension
					);
				}
			}
			else 
			{
				$chatSendTemp[] = $customer;
			}
			
		}
		
		
		//Continuar
		if(!empty($chatSendTemp))
		{
			$session->set('chatSend', $chatSendTemp);
			$data["message"]	= $mensaje."<span class='btn btn-danger' >Volver a cargar hay mas datos por procesar...</span>";
			$data["javascript"]	= "
										$(document).ready(function() {
											// Desplazar al final de la página
											$('html, body').animate({ scrollTop: $(document).height() }, 'slow');
											
											// Esperar 5 segundos y recargar la página
											setTimeout(function() {												
												//window.open('".base_url()."/app_notification/sendWhatsappDiarioChochoMandado/procesarExcel/false', '_blank');
												location.reload();
												
											}, 5000);
										});
			";
			
            $data["urlLogin"]  	= base_url();
            $data["urlIndex"]  	= base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "sendWhatsappDiarioChochoMandado";
            $data["urlBack"]   	= base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "sendWhatsappDiarioChochoMandado";
            $resultView        	= view("core_template/message_general_not_ajax", $data);
			echo $resultView;
			
		}
		//No continuar
		else
		{
			$session->remove('chatSend');			
			$data["message"]   	= $mensaje."<span class='btn btn-success' >No hay mas datos cerrar ventana...</span>";
			$data["javascript"]	= "
										$(document).ready(function() {
											// Desplazar al final de la página
											$('html, body').animate({ scrollTop: $(document).height() }, 'slow');
										});
			";
            $data["urlLogin"]  	= base_url();
            $data["urlIndex"]  	= base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "sendWhatsappDiarioChochoMandado";
            $data["urlBack"]   	= base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "sendWhatsappDiarioChochoMandado";
            $resultView        	= view("core_template/message_general_not_ajax", $data);
			echo $resultView;
		}
		
	}
	
	
}
