<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_api extends _BaseController {
	
    function uploadDataEncuentra24(){
		try{ 
		
			
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$tocken				= "";
			
			$startOn			= '1900-01-01';//--helper_PrimerDiaDelMes();
			$endOn				= '2024-03-09';//--helper_UltimoDiaDelMes();
			
						
			//Get Datos
			$query			= "
								select 
									x.`Codigo interno`,
									x.`itemID`,
									x.`createdOn`
									,x.`Codigo`
									,x.`Nombre`
									,x.`Pagina Web`
									,x.`Amueblado`
									,x.`Aires`
									,x.`Niveles`
									,x.`Hora de visita`
									,x.`Baños`
									,x.`Habitaciones`
									,x.`Diseño de propiedad`																		
									,x.`Fecha de enlistamiento`
									,x.`Fecha de actualizacion`
									,x.`Precio Venta`
									,x.`Precio Renta`
									,x.`Disponible`
									,x.`Area de contruccion M2`
									,x.`Area de terreno V2`
									,x.`ID Encuentra 24`
									,x.`Baño de servicio`
									,x.`Baño de visita`
									,x.`Cuarto de servicio`
									,x.`Walk in closet`
									,x.`Piscina privada`
									,x.`Area club con piscina`
									,x.`Acepta mascota`
									,x.`Corretaje`
									,x.`Plan de referido`
									,x.`Link Youtube`
									,x.`Link Youtube Url`
									,x.`Pagina Web Link`
									,x.`Pagina Web Link Url`
									,x.`Foto`
									,x.`Google`
									,x.`Otros Link`
									,x.`Estilo de cocina`
									,x.`Agente`
									,x.`Zona`
									,x.`Condominio`
									,x.`Ubicacion`
									,x.`Exclusividad de agente`
									,x.`Pais`
									,x.`Estado`
									,case 
										when x.`Proposito` = 'RENTA' then 
											'rent'
										else 
											'property'
									end as `Proposito`
									,x.`Proposito` as `Proposito descripcion`
									,case 
										when x.`Tipo de casa` = 'CASA' and x.`Proposito` = 'VENTA'  then 
											173
										when x.`Tipo de casa` = 'TERRENO' and x.`Proposito` = 'VENTA'  then 
											178
										when x.`Tipo de casa` = 'APARTAMENTO' and x.`Proposito` = 'VENTA' then 
											179
										when x.`Tipo de casa` = 'MODULOS' and x.`Proposito` = 'VENTA' then 
											172
										when x.`Tipo de casa` = 'BODEGAS' and x.`Proposito` = 'VENTA' then 
											172
										when x.`Tipo de casa` = 'EDIFICIOS' and x.`Proposito` = 'VENTA' then 
											170
										when x.`Tipo de casa` = 'CASA DE PLAYA' and x.`Proposito` = 'VENTA' then 
											177
										when x.`Tipo de casa` = 'TERRENO DE PLAYA' and x.`Proposito` = 'VENTA' then 
											177
										when x.`Tipo de casa` = 'QUINTA' and x.`Proposito` = 'VENTA' then 
											173
										when x.`Tipo de casa` = 'HOTEL' and x.`Proposito` = 'VENTA' then 
											173
										
										
										when x.`Tipo de casa` = 'CASA' and x.`Proposito` = 'RENTA'  then 
											157
										when x.`Tipo de casa` = 'TERRENO' and x.`Proposito` = 'RENTA'  then 
											178
										when x.`Tipo de casa` = 'APARTAMENTO' and x.`Proposito` = 'RENTA' then 
											156
										when x.`Tipo de casa` = 'MODULOS' and x.`Proposito` = 'RENTA' then 
											160
										when x.`Tipo de casa` = 'BODEGAS' and x.`Proposito` = 'RENTA' then 
											160
										when x.`Tipo de casa` = 'EDIFICIOS' and x.`Proposito` = 'RENTA' then 
											159
										when x.`Tipo de casa` = 'CASA DE PLAYA' and x.`Proposito` = 'RENTA' then 
											162
										when x.`Tipo de casa` = 'TERRENO DE PLAYA' and x.`Proposito` = 'RENTA' then 
											155
										when x.`Tipo de casa` = 'QUINTA' and x.`Proposito` = 'RENTA' then 
											157
										when x.`Tipo de casa` = 'HOTEL' and x.`Proposito` = 'RENTA' then 
											158											
										else 
											0
									end as `Tipo de casa`	
									,x.`Tipo de casa` as `Tipo de casa descripcion`
									,case 
										when x.`Ciudad` = 'Managua' then 
											153
										when x.`Ciudad` = 'Larreynaga Malpaisillo' then 
											234
										when x.`Ciudad` = 'San Juan Sur' then 
											250
										when x.`Ciudad` = 'Carazo' then 
											173
										when x.`Ciudad` = 'León' then 
											230
										when x.`Ciudad` = 'Granada' then 
											181
										when x.`Ciudad` = 'Rivas' then 
											241
										when x.`Ciudad` = 'Masaya' then 
											163
										when x.`Ciudad` = 'Estelí' then 
											209
										when x.`Ciudad` = 'San Rafael Del Sur' then 
											159
										else 
											0
									end as `Ciudad`
									, x.`Ciudad` as `Ciudad descripcion`
									,case 
										when x.`Moneda` = 'Cordoba' then 
											'NIO'
										else 
											'USD'
									end as `Moneda`
									,x.`Moneda` as `Moneda descripcion`
								from 
									vw_inventory_list_item_real_estate x 								
								limit 1
								/*where */
									  /*x.createdOn BETWEEN ? and ?*/
							";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$startOn,$endOn]
			);
			
			
			//Crear XML
			$xmlContent =	'<?xml version="1.0" encoding="UTF-8"?>
<import>
<settings>
	<type><![CDATA[property]]></type>
	<language><![CDATA[es]]></language>
</settings>
<items>[[ITEMS]]</items>
</import>';

			
			$xmlContentItem = "";
			foreach($objData as $key => $value)
			{
				
				
				$xmlContentItem = $xmlContentItem."<item>";
					$xmlContentItem = $xmlContentItem."<required>";
						$xmlContentItem = $xmlContentItem."<ad>";
						  $xmlContentItem = $xmlContentItem."<countryid>152</countryid>";
						  $xmlContentItem = $xmlContentItem."<sourceid>".$value["Codigo interno"]."</sourceid>";
						  $xmlContentItem = $xmlContentItem."<categoryid>".$value["Tipo de casa"]."</categoryid>";
						  $xmlContentItem = $xmlContentItem."<regionid>".$value["Ciudad"]."</regionid>";						  
						  $xmlContentItem = $xmlContentItem."<type><![CDATA[".$value["Proposito"]."]]></type>";
						  $xmlContentItem = $xmlContentItem."<title><![CDATA[".$value["Nombre"]."]]></title>";						  
						  $xmlContentItem = $xmlContentItem."<currency><![CDATA[".$value["Moneda"]."]]></currency>";	
						  
						  if($value["Proposito"] == "rent")
						  {
								$xmlContentItem = $xmlContentItem."<rent>".$value["Precio Renta"]."</rent>";							
						  }
						  else
						  {							 
								$xmlContentItem = $xmlContentItem."<price>".$value["Precio Venta"]."</price>";
						  }
						  
						  $xmlContentItem = $xmlContentItem."<rooms>".$value["Habitaciones"]."</rooms>";
						  $xmlContentItem = $xmlContentItem."<bath>".$value["Baños"]."</bath>";
						  $xmlContentItem = $xmlContentItem."<square>".$value["Area de contruccion M2"]."</square>";
						  $xmlContentItem = $xmlContentItem."<parking>1</parking>";
						  $xmlContentItem = $xmlContentItem."<advertiser><![CDATA[Agente]]></advertiser>";						  
						$xmlContentItem = $xmlContentItem."</ad>";
						$xmlContentItem = $xmlContentItem."<contact>";
						  $xmlContentItem = $xmlContentItem."<email><![CDATA[operaciones@santaluciare.com]]></email>";
						  $xmlContentItem = $xmlContentItem."<phone><![CDATA[00505 85138974]]></phone>";
						  $xmlContentItem = $xmlContentItem."<contact><![CDATA[Jessica Romero]]></contact>";
						  $xmlContentItem = $xmlContentItem."<city><![CDATA[Managua]]></city>";
						$xmlContentItem = $xmlContentItem."</contact>";
					$xmlContentItem = $xmlContentItem."</required>";	

					$xmlContentItem = $xmlContentItem."<optional>";
						$xmlContentItem = $xmlContentItem."<ad>";
							$xmlContentItem = $xmlContentItem."<exact><![CDATA[".$value["Ubicacion"]."]]></exact>";
							$xmlContentItem = $xmlContentItem."<lotsize><![CDATA[".$value["Area de terreno V2"]."]]></lotsize>";
							$xmlContentItem = $xmlContentItem."<floortype><![CDATA[".$value["Nombre"]."]]></floortype>";
							$xmlContentItem = $xmlContentItem."<stories><![CDATA[".$value["Niveles"]."]]></stories>";
							$xmlContentItem = $xmlContentItem."<swimmingpool><![CDATA[".$value["Piscina privada"]."]]></swimmingpool>";
							$xmlContentItem = $xmlContentItem."<appliances><![CDATA[".$value["Amueblado"]."]]></appliances>";
							$xmlContentItem = $xmlContentItem."<youtube1><![CDATA[".$value["Link Youtube Url"]."]]></youtube1>";
							$xmlContentItem = $xmlContentItem."<m2><![CDATA[".$value["Area de contruccion M2"]."]]></m2>";
							$xmlContentItem = $xmlContentItem."<available><![CDATA[".$value["Disponible"]."]]></available>";
							$xmlContentItem = $xmlContentItem."<picture><![CDATA[".$value["Pagina Web Link Url"]."]]></picture>";							
						$xmlContentItem = $xmlContentItem."</ad>";
						$xmlContentItem = $xmlContentItem."<contact>";
						  $xmlContentItem = $xmlContentItem."<email><![CDATA[operaciones@santaluciare.com]]></email>";
						  $xmlContentItem = $xmlContentItem."<phone2><![CDATA[00505 85138974]]></phone2>";
						  $xmlContentItem = $xmlContentItem."<contact><![CDATA[Jessica Romero]]></contact>";
						  $xmlContentItem = $xmlContentItem."<city><![CDATA[Managua]]></city>";
						$xmlContentItem = $xmlContentItem."</contact>";
					$xmlContentItem = $xmlContentItem."</optional>";
				$xmlContentItem = $xmlContentItem."</item>";
			}
			
				
			 // Crear el contenido XML
			$xmlContent =   str_replace("[[ITEMS]]",$xmlContentItem,$xmlContent);			
			//$xmlContent =	htmlspecialchars($xmlContent);
			//echo $xmlContent;
			
			
			//wgonzalez-// Configurar la respuesta HTTP con el tipo de contenido XML
			//wgonzalez-$response = $this->response->setContentType('text/xml');
			//wgonzalez-
			//wgonzalez-// Enviar el contenido XML como respuesta
			//wgonzalez-return $response->setBody($xmlContent);
	
			
			$filename = 'user_'.date('Ymd').'.xml'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: text/xml; ");			
			print($xmlContent);
			exit;
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
			
	}
	
	function generatedTransactionOutputByFormulate(){
		try{ 
		
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
				
			
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$componentPeriodID	= /*inicio get post*/ $this->request->getPost("componentPeriodID");
			$componentCycleID	= /*inicio get post*/ $this->request->getPost("componentCycleID");
			
			
						
			$query									= "CALL pr_inventory_create_transaction_output_by_formulated(?,?,?,?,?,@resultMayorization);";
			$resultMayorizate						= $this->Bd_Model->executeRender(
				$query,[$companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID]
			);	
			
			$query									= "SELECT @resultMayorization as codigo";
			$resultMayorizate						= $this->Bd_Model->executeRender($query,null);			
			
			
			$resultMayorizate						= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			$resultMayorizateTransactionID			= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionID');
			$resultMayorizateTransactionMasterIDID	= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionMasterID');
			$resultMayorizateTransactionID 			=  $resultMayorizateTransactionID->description;
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
	
	function getInforDashBoards(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}
			
			
			//Obtener Parametros
			$companyID 		= $dataSession["user"]->companyID;
			if((!$companyID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			
			
						
			
			$numberItem		= $this->Item_Model->getCount($companyID);					
			$numberInput	= $this->Transaction_Model->getCountInput($companyID);
			$numberOutput	= $this->Transaction_Model->getCountOutput($companyID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'numberitem'	  	=> $numberItem,
				'numberinput'	  	=> $numberInput,
				'numberoutput'	  	=> $numberOutput
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
}
?>