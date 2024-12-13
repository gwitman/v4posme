<?php
//posme:2023-02-27
namespace App\Libraries\core_web_printer_direct;

//Cargar Libreria
include_once 'src/Mike42/Escpos/CapabilityProfiles/DefaultCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/EposTepCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/P822DCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/SimpleCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/StarCapabilityProfile.php';


include_once 'src/Mike42/Escpos/PrintBuffers/PrintBuffer.php';
include_once 'src/Mike42/Escpos/PrintBuffers/EscposPrintBuffer.php';
include_once 'src/Mike42/Escpos/PrintBuffers/ImagePrintBuffer.php';


include_once 'src/Mike42/Escpos/CapabilityProfile.php';
include_once 'src/Mike42/Escpos/CodePage.php';
include_once 'src/Mike42/Escpos/EscposImage.php';
include_once 'src/Mike42/Escpos/GdEscposImage.php';
include_once 'src/Mike42/Escpos/ImagickEscposImage.php';
include_once 'src/Mike42/Escpos/NativeEscposImage.php';
include_once 'src/Mike42/Escpos/Printer.php';

include_once 'src/Mike42/Escpos/Devices/AuresCustomerDisplay.php';

include_once 'src/Mike42/Escpos/PrintConnectors/PrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/ApiPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/CupsPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/DummyPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/FilePrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/NetworkPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/UriPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/WindowsPrintConnector.php';



use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class core_web_printer_direct {
    var $nombre_impresora;
    var $connectorWindowPrinter;
    var $printer;

    function configurationPrinter($printerName){	
		
        $this->nombre_impresora           = $printerName;		
        $this->connectorWindowPrinter     = new WindowsPrintConnector($this->nombre_impresora);
        $this->printer                    = new Printer($this->connectorWindowPrinter);
		//$this->printer->close();  
	}

	function addSpaces($string = '', $valid_string_length = 0) {
		if (strlen($string) < $valid_string_length) {
			$spaces = $valid_string_length - strlen($string);
			for ($index1 = 1; $index1 <= $spaces; $index1++) {
				$string = $string . ' ';
			}
		}
	
		return $string;
	}

	function executePrinterOpen(){    
		//$this->printer->feed();						
		//$this->printer->cut();		
		$this->printer->pulse();		
		$this->printer->close();
    }
	
	
    function executePrinter80mm($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmBarExitOrignal($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text("\nORIGINAL");
		$this->printer->text("\n");
		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesa: ".$dataSetValores["objMesa"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$servicio	= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice + $row->tax2  ,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount + ($row->tax2 * $row->quantity) ,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$servicio	= $servicio + ($row->tax2 * $row->quantity);				
				$subtotal	= $subtotal + $row->amount;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$total		= $subtotal + $servicio;
		$iva 		= number_format(round($iva,2),2,'.',',');		
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount - $servicio );
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSub Total: ".$dataSetValores["prefixCurrency"].$subtotal) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n12% Servicio: ".$dataSetValores["prefixCurrency"].$servicio) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de Restaurante: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nPropina voluntaria");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nposMe Version 3.1 PRO");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmBarExitCopia($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text("\nCOPIA");
		$this->printer->text("\n");
		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesa: ".$dataSetValores["objMesa"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$servicio	= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice + $row->tax2  ,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount + ($row->tax2 * $row->quantity) ,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$servicio	= $servicio + ($row->tax2 * $row->quantity);
				$subtotal	= $subtotal + $row->amount;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$total		= $subtotal + $servicio;
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount - $servicio );
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSub Total: ".$dataSetValores["prefixCurrency"].$subtotal) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n12% Servicio: ".$dataSetValores["prefixCurrency"].$servicio) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de Restaurante: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nPropina voluntaria");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nposMe Version 3.1 PRO");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmBarExitCuenta($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text("\nPRE CUENTA");
		$this->printer->text("\n");
		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesa: ".$dataSetValores["objMesa"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$servicio	= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice + $row->tax2  ,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount + ($row->tax2 * $row->quantity) ,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$servicio	= $servicio + ($row->tax2 * $row->quantity);
				$subtotal	= $subtotal + $row->amount;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$total		= $subtotal + $servicio;
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount - $servicio );
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSub Total: ".$dataSetValores["prefixCurrency"].$subtotal) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n12% Servicio: ".$dataSetValores["prefixCurrency"].$servicio) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de Restaurante: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nPropina voluntaria");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nposMe Version 3.1 PRO");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmDistribuidoraRD($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		//$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFec: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nUsuario: ". substr($dataSetValores["objUser"]->nickname,1,5) );
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n");
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 24). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n\n");

		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n*********************");
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	function executePrinter80mmBarMilekin($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	function executePrinter80mmPizzaLaus($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-logo-micro-finanza.png';
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text("PIZZA LAUS");
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesa: ".$dataSetValores["objMesa"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetailReferences"]){
			foreach($dataSetValores["objTransactionMasterDetailReferences"] as $row)
			{	
				$this->printer->text($this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 20). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
		
			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nDirección de entrega.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objTransactionMasterInfo"]->referenceClientIdentifier);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNo Telefono cliente.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objTransactionMaster"]->numberPhone);
		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmCafeRetorno($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-logo-micro-finanza.png';
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text("CAFE Y HOSTEL RETORNO");
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesa: ".$dataSetValores["objMesa"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 20). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(2, 1);
		//$this->printer->text("\n****************************.");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nDirección de entrega.");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\n".$dataSetValores["objTransactionMasterInfo"]->referenceClientIdentifier);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nNo Telefono cliente.");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\n".$dataSetValores["objTransactionMaster"]->numberPhone);
		//$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	function executePrinter80mmBlueMoon($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesa: ".$dataSetValores["objMesa"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMesero: ".$dataSetValores["objEmployerNaturales"]->firstName);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\Sub Total: ".$dataSetValores["prefixCurrency"].$subtotal) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nIva: ".$dataSetValores["prefixCurrency"].$iva) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nMonto recibido: ".$dataSetValores["prefixCurrency"]. number_format(round($dataSetValores["objTransactionMasterInfo"]->receiptAmount,2),2,".",",") );
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	function executePrinter80mmPuraVida($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		
		
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,35)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmReportCashOutRustik($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nCIERRE DE CAJA");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		$this->printer->text("\n");
		$this->printer->text(" Fecha:     ".date("Y-m-d h:i a"));
		$this->printer->text("\n");
		$this->printer->text(" Usuario:   ".$dataSetValores["objUsuario"]->nickname);
		$this->printer->text("\n");
		$this->printer->text(" Moneda:    ".$dataSetValores["objCurrency"]->name);
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" DENOMINACIONES  ");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Descripcion               Cant          Total");
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$total = 0;
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		foreach($dataSetValores["objTMDD"] as $t)
		{
			if($t->quantity > 0)
			{
				$this->printer->text("\n");	
				$this->printer->text(" ".substr($t->denominationName."                        ",0,22). substr("      ".$t->quantity,-4).substr("                 ". $t->quantity* $t->reference1,-17));	
				$total = $total + ($t->quantity* $t->reference1);
			}
		}
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$this->printer->text("TOTAL : ".round($total,2));
		$this->printer->text("\n");
		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Revisado");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Autorizado");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		$this->printer->text("\n");
		$this->printer->text("\n");

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	function executePrinter80mmReportCashOutComidaChinaMijo($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,2);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nSALIDA DE CAJA");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		$this->printer->text("\n");
		$this->printer->text(" Fecha:     ".date("Y-m-d h:i a"));
		$this->printer->text("\n");
		$this->printer->text(" Usuario:   ".$dataSetValores["objUsuario"]->nickname);
		$this->printer->text("\n");
		$this->printer->text(" Moneda:    ".$dataSetValores["objCurrency"]->name);
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" DENOMINACIONES  ");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Descripcion               Cant          Total");
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$total = 0;
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);		
		
		foreach($dataSetValores["objTMDD"] as $t)
		{
			if($t->quantity > 0)
			{
				$this->printer->text("\n");	
				$this->printer->text(" ".substr($t->denominationName."                        ",0,22). substr("      ".$t->quantity,-4).substr("                 ". $t->quantity* $t->reference1,-17));	
				$total = $total + ($t->quantity* $t->reference1);
			}
		}
		
		if($total == 0 )
		{
			$this->printer->text("\n");	
			$this->printer->text(" ".substr("Retiro de efectivo"."                        ",0,22). substr("      "." ",-4).substr("                 ". $dataSetValores["objTM"]->amount * 1,-17));	
			$total = $dataSetValores["objTM"]->amount;
		}
		
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$this->printer->text("TOTAL : ".round($total,2));
		$this->printer->text("\n");
		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Revisado");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Autorizado");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		$this->printer->text("\n");
		$this->printer->text("\n");

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	function executePrinter80mmReportCashInputRustik($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nAPERTURA");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		$this->printer->text("\n");
		$this->printer->text(" Fecha:     ".date("Y-m-d h:i a"));
		$this->printer->text("\n");
		$this->printer->text(" Usuario:   ".$dataSetValores["objUsuario"]->nickname);
		$this->printer->text("\n");
		$this->printer->text(" Moneda:    ".$dataSetValores["objCurrency"]->name);
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" DENOMINACIONES  ");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Descripcion               Cant          Total");
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$total = 0;
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		foreach($dataSetValores["objTMDD"] as $t)
		{
			if($t->quantity > 0)
			{
				$this->printer->text("\n");	
				$this->printer->text(" ".substr($t->denominationName."                        ",0,22). substr("      ".$t->quantity,-4).substr("                 ". $t->quantity* $t->reference1,-17));	
				$total = $total + ($t->quantity* $t->reference1);
			}
		}
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$this->printer->text("TOTAL : ".round($total,2));
		$this->printer->text("\n");
		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Revisado");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Autorizado");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		$this->printer->text("\n");
		$this->printer->text("\n");

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	function executePrinter80mmReportCashClosedDirect($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nCAJA");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		$this->printer->text("\n");
		$this->printer->text(" Fecha:     ".date("Y-m-d h:i a"));
		$this->printer->text("\n");
		$this->printer->text(" Usuario:   ".$dataSetValores["objUsuario"]->nickname);
		$this->printer->text("\n");		
		
		$objListCategory				= array_column($dataSetValores["objDetail"],"substitulo");
		$objListCategoryDistinct		= array_unique($objListCategory);
		$subTotal 						= 0;
		$total							= 0;
		foreach($objListCategoryDistinct as $category)
		{
			$subTotal 	= 0;
			$objItem 	= array_filter($dataSetValores["objDetail"], function($k) use ($category)
			{										
				return $k["substitulo"] == $category;
			});			
			
			$this->printer->setJustification(Printer::JUSTIFY_LEFT);
			$this->printer->text("\n");			
			$this->printer->text("\n");
			$this->printer->text(" ".strtoupper($category));
			$this->printer->text("\n");
			$this->printer->text("-----------------------------------------------");
			$this->printer->text("\n");
			$comandoProcess = "";
			foreach($objItem as $i)
			{
				$comandoProcess = $i["comandoProce"];
				if($comandoProcess == "Default" )
				{
					$this->printer->text(" ". substr(strtoupper($i["nombre"])."                    ",0,18 )." ". substr("      ".round($i["cantidad"],2),-4) ."           ".substr("           ".round($i["total"],2),-12));
					$this->printer->text("\n");
					$subTotal = $subTotal + round($i["total"],2);
				}
				if($comandoProcess == "MinMax" )
				{
					$this->printer->text(" ". substr(strtoupper($i["nombre"])."                    ",0,18 )." ". substr("      ".$i["cantidad"],-12) ." ".substr("           ".$i["subtotal"],-12));
					$this->printer->text("\n");
					$subTotal = $subTotal + round($i["total"],2);
				}
				
			}
			
			$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
			$this->printer->text("------------");
			$this->printer->text("\n");
			
			if($comandoProcess == "Default" )
			{
				$this->printer->text(substr($subTotal,-12));
			}
			
			$this->printer->text("\n");
			$total = $total + $subTotal;
			
		}
		$this->printer->text("\n");
		$this->printer->text("\n");		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);				
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Revisado");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text(" Firma ---------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Autorizado");
		$this->printer->text("\n");
		$this->printer->text("******* fin reporte *******");
		$this->printer->text("\n");
		$this->printer->text("\n");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		$this->printer->text("\n");
		$this->printer->text("\n");

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmReportVentaDirect($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nVENTA");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		$this->printer->text("\n");
		$this->printer->text(" Fecha:     ".date("Y-m-d h:i a"));
		$this->printer->text("\n");
		$this->printer->text(" Usuario:   ".$dataSetValores["objUsuario"]->nickname);
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		$this->printer->text("\n");
		$this->printer->text(" Descripcion        Cant      Precio       Total");
		$this->printer->text("\n");
		$this->printer->text("-----------------------------------------------");
		
		$objListCategory				= array_column($dataSetValores["objDetail"],"nameCategory");
		$objListCategoryDistinct		= array_unique($objListCategory);
		$subTotal 						= 0;
		$total							= 0;
		foreach($objListCategoryDistinct as $category)
		{
			$subTotal 	= 0;
			$objItem 	= array_filter($dataSetValores["objDetail"], function($k) use ($category)
			{										
				return $k["nameCategory"] == $category;
			});			
			
			$this->printer->setJustification(Printer::JUSTIFY_LEFT);
			$this->printer->text("\n");
			$this->printer->text("\n");
			$this->printer->text("\n");
			$this->printer->text("\n");
			$this->printer->text(" ".strtoupper($category));
			$this->printer->text("\n");
			$this->printer->text("-----------------------------------------------");
			$this->printer->text("\n");
			foreach($objItem as $i)
			{
				$this->printer->text(" ". substr(strtoupper($i["itemName"])."                    ",0,18 )." ". substr("      ".round($i["quantity"],2),-4) ."     ".substr("      ".round($i["unitaryPrice"],2),-6)." ".substr("           ".round($i["amount"],2),-12));
				$this->printer->text("\n");
				$subTotal = $subTotal + round($i["amount"],2);
			}
			$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
			$this->printer->text("------------");
			$this->printer->text("\n");
			$this->printer->text(substr($subTotal,-12));
			$this->printer->text("\n");
			$total = $total + $subTotal;
			
		}
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$this->printer->text("TOTAL VENTA : ".round($total,2));
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->text("\n");
		$this->printer->text("******* fin venta *******");
		$this->printer->text("\n");
		$this->printer->text("\n");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		$this->printer->text("\n");
		$this->printer->text("\n");

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	function executePrinter80mmRustikChillGrill($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		$this->printer->text("\nFecha:       ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado:      ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario:     ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo:        ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente:     ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre:      ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\nArea:        ".$dataSetValores["objTransactionMasterInfo"]->zonaName);
		$this->printer->text("\nMesa:        ".$dataSetValores["objTransactionMasterInfo"]->mesaName);
		$this->printer->text("\nCajero:      ".substr($dataSetValores["objUser"]->nickname,0,15));
		$this->printer->text("\nMesero:      ".substr($dataSetValores["objTransactionMaster"]->reference3,0,15));
		$this->printer->text("\nTipo cambio: ". round(1 / $dataSetValores["objTransactionMaster"]->exchangeRate,2) );
		
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_RIGHT);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total."      ") ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio."      ");
		$this->printer->text("\n");
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: 8817-2985");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	function executePrinter80mmComidaChinaMijoFactura($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,2);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		
		if($dataSetValores["objTransactionMaster"]->printerQuantity == 0)
		{
			$this->printer->pulse();
		}

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmComidaAudioElPipe($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,2);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		
		if($dataSetValores["objTransactionMaster"]->printerQuantity == 0)
		{
			$this->printer->pulse();
		}

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmYahwetFart($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,2);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$varLetter1	= 1;
		$varLetter2 = 1;
		
		$this->printer->feed(10);
		$this->printer->setTextSize($varLetter1, $varLetter2);
		$this->printer->text("     ".$dataSetValores["objCompany"]->name);		
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("     RUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("        #".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\n");

		$this->printer->text("\nFecha:   ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("Estado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\nTipo:    ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\nNombre:  ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text(strtolower(substr($row->itemName,0,20)));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 3) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 3). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\n           Total: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("           Cambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("\n    Gracias por su compra.");				
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("   ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text("  ".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize($varLetter1, $varLetter2);		
		$this->printer->text(" Sistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(1);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	
	function executePrinter80mmCommandaCocina($dataSetValores){
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n"."                  #".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->text("\n"."                  cocina");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text(" Fecha            "."".date("Y-m-d g:i a") /*$dataSetValores["objTransactionMaster"]->createdOn*/ );
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n Nombre:          ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n Cajero:          ".substr($dataSetValores["objUser"]->nickname,0,15));
		$this->printer->text("\n Mesero:          ".substr($dataSetValores["objTransactionMaster"]->reference3,0,15));
		$this->printer->text("\n Area:            ".substr($dataSetValores["objTransactionMasterInfo"]->zonaName,0,15));
		$this->printer->text("\n Mesa:            ".substr($dataSetValores["objTransactionMasterInfo"]->mesaName,0,15));
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n Comentario:      ".substr($dataSetValores["objComentario"],0,120));
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		$this->printer->text($this->addSpaces(' Cantidad', 18) . $this->addSpaces('Descripcion', 20) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	
				$this->printer->text(
						" ".
						$this->addSpaces(number_format(round(1.0,2),2,'.',','), 17).
						$this->addSpaces(strtolower(substr($row->itemName,0,50)), 20)	
				); 
				$this->printer->text("\n");				

				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		//$this->printer->text("\n");

		//$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		//$iva 		= number_format(round($iva,2),2,'.',',');
		//$total 		= number_format(round($total,2),2,'.',',');
		//$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		//$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		//$cambio 	= number_format(round($cambio,2),2,'.',',');
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		//$this->printer->text("\n");

		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nGracias por su compra.");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n **********************Fin**********************");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nSistema:+(505) 8712-5827");

		//$this->printer->setTextSize(2, 1);
		$this->printer->feed(5);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
	}
	
	function executePrinter80mmCommandaBarPizzaLaus($dataSetValores){
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n"."                  #".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->text("\n"."                  BARRA");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text(" Fecha            "."".date("Y-m-d g:i a") /*$dataSetValores["objTransactionMaster"]->createdOn*/ );
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n Nombre:          ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n Cajero:          ".substr($dataSetValores["objUser"]->nickname,0,15));
		$this->printer->text("\n Mesero:          ".substr($dataSetValores["objTransactionMaster"]->reference3,0,15));
		$this->printer->text("\n Area:            ".substr($dataSetValores["objTransactionMasterInfo"]->zonaName,0,15));
		$this->printer->text("\n Mesa:            ".substr($dataSetValores["objTransactionMasterInfo"]->mesaName,0,15));
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n Comentario:      ".substr($dataSetValores["objComentario"],0,120));
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		$this->printer->text($this->addSpaces(' Cantidad', 18) . $this->addSpaces('Descripcion', 20) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	
				$this->printer->text(
						" ".
						$this->addSpaces(number_format(round(1.0,2),2,'.',','), 17).
						$this->addSpaces(strtolower(substr($row->itemName,0,50)), 20)	
				); 
				$this->printer->text("\n");				

				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		//$this->printer->text("\n");

		//$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		//$iva 		= number_format(round($iva,2),2,'.',',');
		//$total 		= number_format(round($total,2),2,'.',',');
		//$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		//$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		//$cambio 	= number_format(round($cambio,2),2,'.',',');
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		//$this->printer->text("\n");

		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nGracias por su compra.");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n *****************Fin*****************");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nSistema:+(505) 8712-5827");

		//$this->printer->setTextSize(2, 1);
		$this->printer->feed(5);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
	}
	
	function executePrinter80mmCommandaCocinaPizzaLaus($dataSetValores){
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n"."                  #".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->text("\n"."                  COCINA");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text(" Fecha            "."".date("Y-m-d g:i a") /*$dataSetValores["objTransactionMaster"]->createdOn*/ );
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n Nombre:          ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n Cajero:          ".substr($dataSetValores["objUser"]->nickname,0,15));
		$this->printer->text("\n Mesero:          ".substr($dataSetValores["objTransactionMaster"]->reference3,0,15));
		$this->printer->text("\n Area:            ".substr($dataSetValores["objTransactionMasterInfo"]->zonaName,0,15));
		$this->printer->text("\n Mesa:            ".substr($dataSetValores["objTransactionMasterInfo"]->mesaName,0,15));
		$this->printer->text("\n");
		$this->printer->text("\n");
		$this->printer->text("\n Comentario:      ".substr( str_replace("%20"," ",$dataSetValores["objComentario"])   ,0,350));
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		$this->printer->text($this->addSpaces(' Cantidad', 18) . $this->addSpaces('Descripcion', 20) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	
				$this->printer->text(
						" ".
						$this->addSpaces(number_format(round(1.0,2),2,'.',','), 17).
						$this->addSpaces(strtolower(substr($row->itemName,0,50)), 20)	
				); 
				$this->printer->text("\n");				

				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		//$this->printer->text("\n");

		//$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		//$iva 		= number_format(round($iva,2),2,'.',',');
		//$total 		= number_format(round($total,2),2,'.',',');
		//$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		//$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		//$cambio 	= number_format(round($cambio,2),2,'.',',');
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		//$this->printer->text("\n");

		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nGracias por su compra.");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n *****************Fin*****************");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nSistema:+(505) 8712-5827");

		//$this->printer->setTextSize(2, 1);
		$this->printer->feed(5);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
	}
	
	function executePrinter80mmAttendance($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["objCustumer"]->customerNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["objNatural"]->firstName);						
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSolvencia: ".$dataSetValores["objTransactionMaster"]->reference1);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nProxima pago: ".$dataSetValores["objTransactionMaster"]->reference2);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nDias Proxima pago: ".$dataSetValores["objTransactionMaster"]->reference4);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nVencimiento: ".$dataSetValores["objTransactionMaster"]->reference3);		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su regitro.");		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");
		$this->printer->setTextSize(2, 1);
		$this->printer->feed();
		//$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter80mmAttendanceNoImage($dataSetValores){    
		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["objCustumer"]->customerNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["objNatural"]->firstName);						
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSolvencia: ".$dataSetValores["objTransactionMaster"]->reference1);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nProxima pago: ".$dataSetValores["objTransactionMaster"]->reference2);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nDias Proxima pago: ".$dataSetValores["objTransactionMaster"]->reference4);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nVencimiento: ".$dataSetValores["objTransactionMaster"]->reference3);		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su regitro.");		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");
		$this->printer->setTextSize(2, 1);
		$this->printer->feed();
		//$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	function executePrinter80mmFerreteriaDouglas($dataSetValores)
	{    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;
		echo $pathImg;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		//
		//Imprimimos un mensaje. Podemos usar
		//el salto de línea o llamar muchas
		//veces a $printer->text()
		//

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");				
				
				$this->printer->text(						
						$this->addSpaces(   number_format(round($row->quantity,2),2,'.',','), 10) . 						
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25). 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0)						
				);
				$this->printer->text("\n");
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
	
		//
		//Hacemos que el papel salga. Es como
		//dejar muchos saltos de línea sin escribir nada
		
		//$this->printer->feed(15);

		//
		//Cortamos el papel. Si nuestra impresora
		//no tiene soporte para ello, no generará
		//ningún error
		
		$this->printer->cut();

		//
		//Por medio de la impresora mandamos un pulso.
		//Esto es útil cuando la tenemos conectada
		//por ejemplo a un cajón
		//
		$this->printer->pulse();

		//
		//Para imprimir realmente, tenemos que "cerrar"
		//la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		//
		$this->printer->close();
    }
	
	
	
	function executePrinter58mmBarCodeList($objComponentItem,$dataSetValores,$dataView)
	{   
		
	
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		foreach($dataSetValores as $objItem)
		{
			
			$pathImg = PATH_FILE_OF_APP_ROOT.'/file_company/company_2/component_'.$objComponentItem->componentID;
			$pathImg = $pathImg.'/component_item_'.$objItem->itemID.'/barcode.png';	
			
			//--wgonzalez
			//--posme
			//--cambio al momento de cambiar la version de code integer 4.1
			//-core_web_printer_direct-77
			
			//referencia de modificaciones
			$logo = EscposImage::load($pathImg, true);
			$this->printer->text( $objItem->itemNumber);		
			$this->printer->setTextSize(2, 1 /*alto de la letra*/);
			$this->printer->text("\n");					
			$this->printer->text( strtolower(substr($objItem->name,0,15 )) );
			$this->printer->text("\n");	
			
			if($dataView["objParameterPrinterShowPrice"] == "true")
			{
				$this->printer->text( strtolower(substr($objItem->itemPrice,0,15 )) );
				$this->printer->text("\n");	
			}
			
			$this->printer->bitImage($logo,0);	
			
			$this->printer->text("\n");		
			$this->printer->text("\n");	
			$this->printer->text("\n");	
			$this->printer->text("\n");	
			
			
		}
		
		
		
		//Cortamos el papel. Si nuestra impresora
		//no tiene soporte para ello, no generará
		//ningún error		
		//$this->printer->cut();


		
		//Para imprimir realmente, tenemos que "cerrar"
		//la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
	
		$this->printer->close();
    }
	
	function executePrinter58mmBarCodeListLocalHost($objComponentItem,$dataSetValores,$dataView)
	{   
		
	
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		foreach($dataSetValores as $objItem)
		{
			
			$pathImg = PATH_FILE_OF_APP_ROOT.'/file_company/company_2/component_'.$objComponentItem;
			$pathImg = $pathImg.'/component_item_'.$objItem["itemID"].'/barcode.png';	
			
			//--wgonzalez
			//--posme
			//--cambio al momento de cambiar la version de code integer 4.1
			//-core_web_printer_direct-77
			
			//referencia de modificaciones
			$logo = EscposImage::load($pathImg, true);
			$this->printer->text( $objItem["itemNumber"]);		
			$this->printer->setTextSize(2, 1 /*alto de la letra*/);
			$this->printer->text("\n");					
			$this->printer->text(strtolower(substr( $objItem["name"], 0 , 15  )) );
			$this->printer->text("\n");		
			
			if($dataView["objParameterPrinterShowPrice"] == "true")
			{
				$this->printer->text(strtolower(substr( $objItem["itemPrice"], 0 , 15  )) );
				$this->printer->text("\n");		
			}
			
			$this->printer->bitImage($logo,0);	
			
			$this->printer->text("\n");		
			$this->printer->text("\n");	
			$this->printer->text("\n");	
			$this->printer->text("\n");	
			
			
		}
		
		
		
		//Cortamos el papel. Si nuestra impresora
		//no tiene soporte para ello, no generará
		//ningún error		
		//$this->printer->cut();


		
		//Para imprimir realmente, tenemos que "cerrar"
		//la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
	
		$this->printer->close();
    }
	
	
	function executePrinter58mm($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text( $dataSetValores["objCompany"]->name);
		$this->printer->text("\n");		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nAtiende: ". substr( $dataSetValores["objUser"]->nickname,0,10) );
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["nombreCliente"]);
		//$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");
				$this->printer->text(
						$this->addSpaces(number_format(round($row->quantity,2),2,'.',','), 10) . 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 0)
				);

				$this->printer->text("\n");
				$this->printer->text($this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0));
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
				$this->printer->text("\n");

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		//$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNegocio: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(1);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter58mmBalanceCustomer($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text( $dataSetValores["objCompany"]->name);
		$this->printer->text("\n");		
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objCustumer"]->customerNumber);
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nFecha: ".helper_getDateTime());		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["objNatural"]->firstName);		
		$this->printer->setTextSize(1, 1);
		
		if($dataSetValores["objBalanceNacional"])
		{
			$this->printer->text("\nSaldo en cordoba:".number_format( round($dataSetValores["objBalanceNacional"][0]->amount,2), 2 , '.',',') );
			$this->printer->setTextSize(1, 1);
		}
		
		if($dataSetValores["objBalanceExtranjero"])
		{
			$this->printer->text("\nSaldo en dolares:".number_format( round($dataSetValores["objBalanceExtranjero"][0]->amount,2), 2 , '.',',') );
			$this->printer->setTextSize(1, 1);
		}
		
		$this->printer->text("\nGracias.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNegocio: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");
		$this->printer->setTextSize(2, 1);
		$this->printer->feed(1);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

	

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter58ChicharronesCarasenos($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text("Chicarrones");
		$this->printer->text("\n");
		$this->printer->text("Carazeños");
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nAtiende: "."Rosita");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");
				$this->printer->text(
						$this->addSpaces(number_format(round($row->quantity,2),2,'.',','), 10) . 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 0)
				);

				$this->printer->text("\n");
				$this->printer->text($this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0));
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
				$this->printer->text("\n");

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		//$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNegocio: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(8);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }

	function executePrinter58LaTenera($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text("Veterinaria La Tenera");
		//$this->printer->text("\n");
		//$this->printer->text("Carazeños");
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nAtiende: "."luis vargas");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");
				$this->printer->text(
						$this->addSpaces(number_format(round($row->quantity,2),2,'.',','), 10) . 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 0)
				);

				$this->printer->text("\n");
				$this->printer->text($this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0));
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
				$this->printer->text("\n");

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		//$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNegocio: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(8);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	function executePrinter58Compra($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;		
		//pone en negrita
		//$this->printer->setEmphasis(true);				
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");
				$this->printer->text(
						$this->addSpaces(number_format(round($row->quantity,2),2,'.',','), 10) . 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 0)
				);

				$this->printer->text("\n");
				$this->printer->text($this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0));
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
				$this->printer->text("\n");

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');		
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\n****************************");
		$this->printer->setTextSize(2, 1);
		$this->printer->feed(8);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }


	function executePrinter58mmCommandaCocina($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		//$logo = EscposImage::load($pathImg, false);
		//$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text("Chicarrones");
		$this->printer->text("\n");
		$this->printer->text("Carazeños");
		$this->printer->text("\n");
		//$this->printer->setTextSize(1, 1);		
		//$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nAtiende: "."Rosita");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");
				$this->printer->text(
						$this->addSpaces(number_format(round($row->quantity,2),2,'.',','), 10) 						
				);
				//$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 0)

				//$this->printer->text("\n");
				//$this->printer->text($this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0));
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
				$this->printer->text("\n");

			}
		}
		//$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		//$this->printer->text("\n");

		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		//$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************");
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nNegocio: ".$dataSetValores["objParameterPhoneProperty"]->value);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		//$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(8);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	
	
	
	function executePrinter58mmShareDirect($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$dataSetValores["objParameterLogo"]->value;

		//--wgonzalez
		//--posme
		//--cambio al momento de cambiar la version de code integer 4.1
		//-core_web_printer_direct-77
		//referencia de modificaciones
		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);
		
		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(1, 1);
		$this->printer->text($dataSetValores["objCompany"]->name);		
		$this->printer->text("\n");
		$this->printer->setTextSize(1, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");
		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->createdOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nAtiende: ".$dataSetValores["objUser"]->nickname);		
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);				
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSaldo inicial: ".$dataSetValores["saldoInicial"]) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nAbono: ".$dataSetValores["saldoAbonado"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSaldo final: ".$dataSetValores["saldoFinal"]);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias.");
		$this->printer->setTextSize(1, 1);
		//$this->printer->text("\nContamos con servicio a domicilio.");
		//$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNegocio: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(8);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }
	

}

?>