/*BD: 	posme:parametro_farma_larreynaga*/

/******************************************************************/
/*****Personalizar pantalla**********/
/******************************************************************/	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_SEND_SFTP_PEDIDOSYA_IP"; #Ip de pedidos ya para mandar archivo csv, por sftp
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_SEND_SFTP_PEDIDOSYA_USERNAME";  #Username de pedidos ya para mandar archivo csv, por sftp
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_SEND_SFTP_PEDIDOSYA_PASSWORD"; #Password de pedidos ya para mandar archivo csv, por sftp
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_SEND_SFTP_PEDIDOSYA_PORT"; #Puerto de pedidos ya para mandar archivo csv, por sftp


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_SEND_SFTP_PEDIDOSYA_MERCHATID"; #Id del comercio de pedidos ya para mandar archivo csv, por sftp



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_EXECUTE_FORMULATED"; 
## Ejecutar las formulas al facturar


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_INSRT_ALL_WAREHOUSE_IN_NEWITEM"; 
## Agregar todas las bodegas al momento de crear un nuevo item

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/add/codigoMesero/none" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_URL_RESULT"; 
## Url de resultado pagadito 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "MOBILE_SHOW_URL_CUSTOMER_PAY"; 
## Indica la url que se debe de mostrar al momento de ver el estado de cuenta de un cliente	
## https://posme.net/v4posme/carlos/public/app_cxc_report/pay_by_invoice/viewReport/true/invoiceNumber/{0}


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Gracias por su compra {firstName}, le recordamos que por cada compra acumula puntos! Su puntos acumulados son {amount}" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SEND_WHATAPP_BY_POINT_TEMPLATE"; ## Plantilla de envio de whatapp al comprar por puntos
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SEND_WHATAPP_BY_POINT"; ## Enviar whatapp por puntos
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_RATIO_OF_POINT_BY_BILLING"; ## Configuracion de los puntos ganados por cada cordoba vendido
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "DEFAULT" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_TYPE_PRINTER_SHARE_MOBILE"; ## Tipo de impresion en mobile:  DEFAULT | FINANCIAL
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_tools_endorsements/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ENDORSEMENTS_URL_PRINTER"; ## Url de impresion de endosos
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_MESERO_SCREEN_INDIVIDUAL";## True si cada mesero ocuipa su propio dispositivo
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TRAKING_GPS";## Valida si es neceasrio llevar el seguimiento del gps
	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SHOW_FIELD_PESO";## Campo de peso en el detalle de la factura visible true


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://posme.net/v4posme/posme/app_mobile_api/setPositionGps" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_SEND_GPS_POSITION_TO_POSME";## Url envio de notificaciones


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "2629824000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "MOBILE_SYNC_GPS";## Enviar la sincronizacion al api cada 20 minutos



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "60" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "MOBILE_SHOW_TOP_CUSTOMER";## En la pantalla de cliente en el mobile mostrar el top

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "10" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "MOBILE_SHOW_TOP_ITEMS";## En la pantalla de items en el mobile mostrar el top


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "50" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "MOBILE_SHOW_TOP_CUSTOMER_IN_SHARE";## En la pantalla de cliente en el mobile mostrar el top durante el abono
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_SHOW_BALANCE_IN_SHARE_MOBILE";## Mostrar los saldos en abonos mobiles


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_share/viewRegisterFormatoPaginaTicketInvoiceCancel" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_SHARE_URL_PRINTER_INVOICE_CANCEL";## Imprmir reporte de facturas canceladas con el abono
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "http://localhost/posmev4/app_inventory_report/printer_stiker_sin_precio" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ITEM_PRINTER_BARCODE_MASIVE_SIN_PRECIO";## Imprmir stiker sin precio
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "http://localhost/posmev4/app_inventory_report/printer_stiker_con_precio" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ITEM_PRINTER_BARCODE_MASIVE_CON_PRECIO";## Imprimir stiker con precio
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_VALIDATE_EXONERATION";## Validar la exoneracion
	
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_cxc_report/customer_detail_invoice_printer" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_URL_PRINT_CUSTOMER_DETAIL";## Detalle de la deuda
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "78" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_AMORTIZATION_STATUS_REGISTER";## Ruta de Finger Print
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "77" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_DOCUMENT_CREDIT_STATUS_REGISTER";## Ruta de Finger Print

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "C:\\execute.exe" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "OPEN_FINGERPRINT_EXECUTE_PATH";## Ruta de Finger Print
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "OPEN_FINGERPRINT_EXECUTE";## Ejecutar progrma finger print automaticamente
	
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_TRAKING_BAR";## Auto imprimir asistencia
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "139" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TRANSFEROUTPUT_WORKFLOW_APPLY";## Workflow Aplicable de la Transferencia por salida
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "140" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TRANSFERINPUT_WORKFLOW_APPLY";## Workflow Aplicable de la Transferencia por entrada
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ATTENDANCE_AUTO_PRINTER";## Auto imprimir asistencia
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TRANSFEROUTPUT_AUTO_APPLY_TRANSFERINPUT";## Auto aplicar transferencia
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_PREVIEW_AND_DIRECT";## Imprimir directo en la impresora 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "50" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TRANSACTION_CAUSAL_PRODUCTION_ITEM";## Id del Causal que sirve para producir mercaderia
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_UPDATE_DATE_APPLYCATION_IN_MOMENT_APLICATION";## Actualizar la fecha de apliacion de la factura al momento de aplicarla



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "2323" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_DAY_EXCLUDED_IN_CREDIT";## Banco por defecto
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "848" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ITEM_REAL_STATE_GERENCIA_EXCLUSIVE";## Banco por defecto
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "2" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_BANKID_DEFAULT";## Banco por defecto
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "546" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_MESAID_DEFAULT";## Mesa por defecto
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "157" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_ZONEID_DEFAULT";## Zona por defecto
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "872" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_FORM_CONTACT_ID_DEFAULT";## Forma de contacto 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "468" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_TYPE_FIRM_ID_DEFAULT";## Tipo de firma
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "719" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CUSTOMER_SEX_ID_DEFAULT";## Sexo por defecto	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "96" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT";## Condicion de pago
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "92" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT";## Tipo de pago	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "99" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CUSTOMER_TYPE_ID_DEFAULT";## Tipo de cliente	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "103" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_SUBCATEGORY_ID_DEFAULT";## Sub Categoria por defecto
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "102" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CATEGORY_ID_DEFAULT";## Categoria por defecto
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "97" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CLASIFICATION_ID_DEFAULT";## Clasificacion por defecto


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "85" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_IDENTIFICATION_TYPE_DEFAULT";## Tipo de identificacion por defecto

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "466" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_PROFESION_ID_DEFAULT";## Profesion por defecto
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "464" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_STATUS_CIVIL_ID_DEFAULT";## Estado Civil del cliente
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_WAREHOUSE_ID_DEFAULT";## Id de la bodega por dececto
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "81" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_DISPLAY_ID_DEFAULT";## Presentacion por defecto	
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "75" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_FAMILY_ID_DEFAULT";## Familia por defecto del producto
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "78" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_UNITMEASURE_ID_DEFAULT";## Unidad de Medida por defecto
	
	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "6000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TIME_FRECUENCY_NOTIFICATION";## Intervalo de notificaciones
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_RUN_NOTIFICATION";## Correr las notificaciones

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_USE_BIOMETRIC";## Ingresar en las tablas del biometric



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WHATSAP_URL_REQUEST_SESSION_PARAMETERF1";## Parametro 1 para envio de whatsap


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SEND_EMAIL_IN_INSERT";## Moneda por defecto al momento de facturar	
	
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Cordoba" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_IN_BILLING";## Moneda por defecto al momento de facturar	
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_CURRENT_REQUEST_BURO";## Usuario para conectarse a la sin riesgo 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "25" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_COUNT_MAX_REQUEST_BURO";## Usuario para conectarse a la sin riesgo 
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_cxc_customer/viewPrinterDirectBalance58mm" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_URL_PRINT_BALANCE_CUSTOMER";	## Imprimir el precio en la etiqueta

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_BAR_CODE_UNIQUE";	## codigo de barra unico
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ITEM_PRINTER_BARCODE_SHOWPRICE";	## Imprimir el precio en la etiqueta

	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_IS_RESTAURANT";	## Es restaurante
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SHOW_BARCODE_INNAME";	## Mostrar el codigo del producto scanerado al momento de facturar
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_purchase_taller/viewPrinterFormatoA4Stiker" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WORKSHOW_URL_PRINTER_TALLER_STIKER";	## True, no permite cedula repetidas, False, permite cecedula repetidas
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_purchase_taller/viewPrinterFormatoA4Output" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WORKSHOW_URL_PRINTER_TALLER_OUTPUT";	## True, no permite cedula repetidas, False, permite cecedula repetidas
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_purchase_taller/viewPrinterFormatoA4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WORKSHOW_URL_PRINTER_TALLER";	## True, no permite cedula repetidas, False, permite cecedula repetidas
	
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_FILE_SERVER";	## Servidor de archivo



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_purchase_garantia/viewPrinterFormatoA4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WORKSHOW_URL_PRINTER_GARANTIA";	## Print de deposito en garantia



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SELECTITEM";	## SI este valor esta en true, los produyctos se seleccionar a traves de un selecte item
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_VALIDAR_CEDULA_REPETIDA";	## True, no permite repetir la cedula, false, permite repetir la cedula
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_MUCHOS_PRODUCTOS";	## Parametro que define si la tienea tiene muchos productos y no es viable mostrala en un combobox
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_ACCOUNT_BANK";	## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://api.ultramsg.com/instance65915/messages/chat" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WAHTSAP_URL_ENVIO_MENSAJE";## Se usa para poner un label al sistema,  como un segundo nombre de sistema


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "n5hb8n1wf0r6e27i" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WHATSAP_TOCKEN";## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://api.whaticket.com/api/v1/whatsapps" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WHATSAP_URL_REQUEST_SESSION";## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "50584766457" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WHATSAP_CURRENT_PROPIETARY_COMMERSE";## WHATSAPP DEL PRIPIETARIO
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WHATSAP_COUNTER_MESSAGE";	## Contador de mensaje de whatsapp
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "WHATSAP_MESSAGE_BY_MONTO";	## Cantidad de mensajes mensuales
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "3" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "POSME_CALENDAR_ROLE_DEFAULT";## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "8" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "POSME_CALENDAR_TAG_DEFAULT";## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "http://localhost/posmev4/app_config_noti/eventgoogleadd" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "POSME_CALENDAR_URL_CITA";## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	
	
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_LABEL_SISTEMA_SUPLANTATION";## Se usa para poner un label al sistema,  como un segundo nombre de sistema
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Interes anual" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_VIEW_CUSTOM_LABEL_INTERES_ANUAL";## Tipo de precio por defecto

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "smaller" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER";## Varible para realiar calculos 		
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA";## Alto de la fila en los reportes
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_ZONA_HORARIA";## Zona horaria del servidor


/******************************************************************/
/*****       **********/
/******************************************************************/		

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "154" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_DEFAULT_TYPE_PRICE";## Tipo de precio por defecto


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "PRO00000131" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXP_PROVIDER_DEFAULT";## Proveedor por defecto


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "CORE_TEMPORAL003" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TEMPORAL003";## Varible para realiar calculos 
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "121" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_TYPE_EMPLOYEER";## Varible para realiar calculos 
	
	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_VALIDATE_BALANCE";## Varible para realiar calculos 
	
	

				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "CORE_TEMPORAL002" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TEMPORAL002";## Varible para realiar calculos 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TEMPORAL001";## Varible para realiar calculos 				
		
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CACHE_IN_VIEW";## Cache en controlador viewX
	
	
			
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_CATEGORY_BY_DEFAULT";## Establecer la categoria por defecto
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "293" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_ITEM_PROVIDER_DEFAULT";## Proveedor por defecto para los productos. 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_IN_NEW_ITEM_MAINTAIN_NAME";## Cuando se hace un nuevo producto mantener el nombre del producto anterior
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_INTERES_DEFAULT";## Plazo predeterminado

	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_PLAZO_DEFAULT";## Plazo predeterminado
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_INVOICE_BY_INVOICE";## Si este valor esta en true, se tiene que seleccionar una factura a la ves, par ir abonando una a una
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE";## Scrol en la lista de compra detalle
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_VIEW_CUSTOM_SCROLL_IN_LIST_CUSTOMER";## Scrol en el listga de clientes
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "284" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_MUNICIPIO_DEFAULT";## Municipio por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "190" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_FRECUENCIA_PAY_DEFAULT";## CXC_FRECUENCIA_PAY_DEFAULT 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "193" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_TYPE_AMORTIZATION";## CXC_TYPE_AMORTIZATION 
				
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "12" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_DEFAULT_PRICELIST";## LISTA DE PRECIO POR DEFECTO 

				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "211" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_DEPARTAMENTO_DEFAULT";## Departamento por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "42" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_PAIS_DEFAULT";## Pais por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "00002" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_ITEM_WAREHOUSE_DEFAULT";## Bodega por defecto 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "389" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_PROPIETARIO_DEL_CREDITO";## PROPIETARIO DEL CREDITO 				
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "383" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_PROVISION";## ACCOUNTING_JOURNALTYPE_PROVISION 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "378" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_DIVIDENDO";## Cuenta para medir el pago de dividendo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CREDIT_INTERES_MULTIPLO";## INTERES MULTIPLICADOR DE INTERES 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "93" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CREDIT_AMORTIZATION_PROVISIONED";## ESTADO PROVISIONADO DE LAS CUOTAS DE CREDITO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "92" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CREDIT_DOCUMENT_PROVISIONED";## ESTADO PROVISIONADO DEL DOCUMENTO DE CREDITO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "72" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_APORTECAPITAL";## tipo de comprobante aporte al capital 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "196" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_AMERICANO";## TIPO DE AMORTIZACION AMERICANA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "83" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_DOCUMENT_ANULADO";## ESTADO DEL DOCUMENTO DE CREDITO ANULADO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "82" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_DOCUMENT_CANCEL";## ESTADO DE DOCUMENTO DE CREDITO CANCELADO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "81" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_CANCEL";## ABONO DE CREDITO CANCELADO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "22,24,55" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_CREDIT";## ID DE LOS CAUSALES DE CREDITO DE LA FACTURACION. 
	
			
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_EMPLOYEE_DEFAULT";## Variable para poner por defecto al vendedor
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "20" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_TRANSACTION_REVERSION_TO_BILLING";## TRANSACCIÓN DE REVERSIÓN PARA LA FACTURA 
	
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "CLI00000000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_CLIENTDEFAULT";## Cliente por defecto al realizar una factura
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "67" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_CANCEL";## FACTURAS EN ESTADO CANCELADAS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "68" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_ANULADAS";## FACTURAS EN ESTADOS ANULADAS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "153" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_AREA_ND";## Catalogo para el Área no Determinada 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "15" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TRANSFEROUTPUT_RELATION_TRANSFERINPUT";## La relación entre la transferencia por salida (16) y su transacción automática es con la entrada por transferencia (15) 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "36" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALENTRY_WORKFLOWSTAGE_FINISH";## Ultimo estado de los Comprobantes 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "74" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_CLOSED";## VALOR DEL TIPO DE COMPROBANTE DE CIERRE: catalogItemID : 74 
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "logo-micro-finanza.jpg" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_COMPANY_LOGO";## Logo de la empresa 

				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "34" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED";## Parámetro para almacenar el estado de los ciclos cerrados  
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "32" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED";## Este parámetro identifica todos los periodos contables cerrados. 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Lic." 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_TITLE";## Titulo del propietario de la empresa 


/******************************************************************/
/*****Configuracion de Pago**********/
/******************************************************************/		

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "35f6110eb79c3640a9bc35f876fe05f6" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRODUCCION_USUARIO_COMMERCECLIENT";## Clave para realizar el pago en ambiente de produccion 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "56720c930f874d4011ff7f3e2a86eddb" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRODUCCION_CLAVE_COMMERCECLIENTE";## Clave para realizar el pago en ambiente de produccion 
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "12ef04e7197c001b66920797fac63c46" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRODUCCION_CLAVE";## Clave para realizar el pago en ambiente de produccion 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "7be685eb39ed6cac49de29bd36f4665e" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRODUCCION_USUARIO";## Usuario para realizar el pago en ambiente de produccion 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "56720c930f874d4011ff7f3e2a86eddb" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRUEBA_CLAVE";## Clave para realizar el pago en ambiente de prueba 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "35f6110eb79c3640a9bc35f876fe05f6" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRUEBA_USUARIO";## Usuario para realizar el pago en ambiente de prueba 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_SENDBOX";## API DE PAGO DE PRUEBA 


/******************************************************************/
/*****Configuracion de Sin Riesgo**********/
/******************************************************************/


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://www.sinriesgos.com.ni/ServiceFacade/servicios.asmx?wsdl" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_UPLOAD";## Urls para cargar la informacio de los deudores a ala sin riesgo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://www.sinriesgos.com.ni/WS/WebService.asmx?WSDL" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO";## Urls para consultar a la sin riesgo y para reportar a la sin riesgo 
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "180389Gonzalez." 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_PASSWORD";## Passwords para conectarse a la sin riesgo 


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://servicios.bcn.gob.ni/Tc_Servicio/ServicioTC.asmx?WSDL" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_ACCOUNTING_WSDL_DER";## Urls para consultar el tipo de cambio 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "b77a5c561934e089" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_UPLOAD_CODIGO";## Contraseña para subir la informacion a la sin riesgo identificacion del comercio 

				

/******************************************************************/
/*****Configuracion de Contabilidad y Razones financieras**********/
/******************************************************************/


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "(04-03-02|04-03-03|04-03-04|04-03-05|04-03-06|04-03-07|04-03-08|05-01-03|05-01-04|05-01-05|06-01-01|06-01-02|06-01-03|06-01-04|06-01-05|06-01-06|06-02-01|06-02-02|06-02-03|06-02-04|06-02-05|06-02-06|06-03-01|06-03-02|06-03-03|06-03-04|06-03-05)" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_INGRESOS_EGRESOS";## ACCOUNTING_NUMBER_INGRESOS_EGRESOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "01-01-01-01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_CASH";## CUENTA DE BANCO O CAJA 
				

				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[01-01-01-03]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_BANCO_AVANZ";## Resumen de cuenta AVANZ 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[01-01-02-03] + [01-01-01-02]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_BANCO_BANPRO";## Resument de Cuenta BANPRO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "([01-01-01-01]+[01-01-02-04])" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_BANCO_BAC";## Resument de Saldo en Banco BAC 
				

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "((([04]-[05]-[06])/[01])*100)/12" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RENTABILIDAD_MENSUAL";## RENTABILIDAD MENSUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "(([04]-[05]-[06])/[01])*100" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RENTABILIDAD_ANUAL";## RENTABILIDAD ANUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[04]-[05]-[06]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_UTILIDAD_MENSUAL";## UTILIDAD MENSUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[04]-[05]-[06]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_UTILIDAD_ANUAL";## UTILIDAD ANUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "([02-02-02]/[03-02-01])*100" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_ENDEUDAMIENTO";## QUE TANTO ESTA ENDEUDADA LA EMPRESA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "([01-01-01-01]/[02-02-02])*100" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_CIRCULANTE";## RAZÓN DEL CIRCULANTE 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "^(04|05|06|07)" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_ACCOUNTTYPE_RESULT";## LAS CUENTAS DE RESULTADOS SON LAS QUE EMPIEZAN CON ESTA NOMENCLATURA 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "03" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_CAPITAL";## CUENTA DE CAPITAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "02" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_PASIVO";## CUENTA DE PASIVO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_ACTIVO";## CUENTA DE ACTIVO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "05" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_COSTOS";## CUENTA DE COSTOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "06" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_GASTOS";## CUENTA DE GASTOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "04" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_INGRESO";## CUENTA DE INGRESOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[04]-[05]-[06]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_FORMULATE_OF_UTILITY";## FORMULA PARA LAS UTILIDADES DE LA EMPRESA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "03-01-01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_UTILITY_ACUMULATE";## ACCOUNTING_NUMBER_UTILITY_ACUMULATE 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "03-01-02" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_UTILITY_PERIOD";## ACCOUNTING_NUMBER_UTILITY_PERIOD 


/***********************************************************************/
/*****Configuracion de Sistema******/
/***********************************************************************/

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "2023-04-01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_LAST_NOTIFICACION";## La fecha que esta escrita aqui, significa que el informe enviado los datos del informe enviado incluidan este dia, que se muestra en el campo
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "7" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_MAX_USER";## MAXIMO NUMERO DE USUARIOS 

				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = ";" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CSV_SPLIT";## CARACTERE SEPARADOR DE CVS 


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "flc_victoria_store" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_USUARIO";## Usuario para conectarse a la sin riesgo 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_FALVOR";## Usuario para conectarse a la sin riesgo 


/***********************************************************************/
/*****Configuracion de Facturacion Inventario y Generar documentos******/
/***********************************************************************/

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SHOW_COMMAND_BAR";## Mostrar el bonto de impresion en bar


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "posMe" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR";## Nombre de la impresora de bar

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "posMe" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_URL_BAR";## Url para imprimir comanda de bar
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "posMe" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_URL_BAR";## Url para imprimir comanda de bar con previoew
	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0.4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_EXCHANGE_SALE";## GANANCIA POR VENTA DE Dolares
				

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SHOW_IMAGE_IN_DETAIL_SELECTION";## Mostrar la imagen al momento de seleccionar el producto
	
	
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0.4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_EXCHANGE_PURCHASE";## ganancia por compra de dolares 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "ULTIMO COSTO" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TYPE_COST";## Tipos de Costo: ULTIMO COSTO; PROMEDIO 


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_UPDATEPRICE_ONLINE";## ACTUALIZAR EL PRECIO DURANTE LA FACTURACION 				
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_UPDATENAME_IN_TRANSACTION_ONLY";## Actualiazar el nombre unicamente en la transaccion

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "400" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_ALTO_MODAL_DE_SELECCION_DE_PRODUCTO_AL_FACTURAR";## Alto del modal de seleccion de producto al momento de facturar
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SCROLL_DE_MODAL_EN_SELECCION_DE_PRODUTO_AL_FACTURAR";## El mondal de seleccion de producto al momento de facturar es scroll o paginado
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_QUANTITY_ZERO";## Permite facturar productos, aunque las existencias esten en 0 unidades 
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION";## Mostrar Calendario de Pago , al momento de imprimir una factura
				

				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Dolar" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_EXTERNAL";## MONEXA EXTRAJERA 
				

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Cordoba" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_REPORT";## Moneda de Reporte para Presentar los Estados Financieros 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Cordoba" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_FUNCTION";## Símbolo de la Moneda Funcional 
			
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_SHOW_LINK_DOWNOAD";#No mostrar el preio del pdf, si no generar un linck de descarga



UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_SHOW_DOWNLOAD_PREVIEW";#No mostrar previoew, solo descargar
	

/*--Imprimir de orden de compra*/
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_inventory_inputunpost/viewRegisterFormato80mm" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_URL_PRINTER_INPUTUNPOST";## URL PARA LA IMPRESION DE ORDEN DE COMPRA
	
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_inventory_inputunpost/viewRegisterFormato80mm" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY";## URL PARA LA IMPRESION DE ORDEN DE COMPRA
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES";## URL PARA LA IMPRESION DE ORDEN DE COMPRA
	
	
	
/*--Imprimir codigos masivos*/
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "http://localhost/posmev4/app_inventory_itemmasive/printer_barcode_58ml_direct" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ITEM_PRINTER_BARCODE_MASIVE";## URL PARA LA IMPRESION DE CANCELACION 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "posMe58Codigo" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_BARCODE_PRINTER_DIRECT_NAME_DEFAULT";## URL PARA LA IMPRESION DE CANCELACION 
	

				
/*--Imprimir cancelacion de factura*/
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_canceldocument/viewRegisterFormatoPaginaNormal" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_CANCELDOCUMENT_URL_PRINTER";## URL PARA LA IMPRESION DE CANCELACION 
				
/*--Imprimir abonos al capital*/
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_sharecapital/viewRegisterFormatoPaginaNormal" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_SHARECAPITAL_URL_PRINTER";## URL PARA LA IMPRESION DE ABONOS AL CAPITAL 

/*--Retiro de efectivo*/
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_outcash/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_OUTCASH_URL_PRINTER";## url para retiro de efectivo
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_inputcash/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_INPUTCASH_URL_PRINTER";## url para retiro de efectivo
	
	
	
	
	

	
/*--Imprimir abonos	*/			
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_share/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_SHARE_URL_PRINTER";## URL PARA LA IMPRESION DE ABONOS 
	
	
/*--Facturacion		*/		
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "-"  /*-,editv2*/
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_PANTALLA_FACTURACION";## PANTALLA PARA LA FACTUACION
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_DAY_SLEEP";## Dia de defase del reporte
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SHOW_PREVIEW_INLIST";## Dia de defase del reporte
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SAVE_AFTER_TO_LIST";## Despues de Guardar o aplicar regresar a la lista
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SAVE_AFTER_TO_ADD";## Despues de Guardar o aplicar regresar a la lista
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_SHOW_POPUP_FIND_PRODUCTO_NOT_SCANER";#No escanerar codigo despues de cerrar el popu de busqueda de producto

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "default" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_VIEW_CUSTOM_PANTALLA_DE_FACTURACION_POPUP_SELECCION_PRODUCTO_FORMA_MOSTRAR";#Forma de mostrar el popup de seleccion de producto en la pantalla de facturacion
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_HIDEN_ITEMNUMBER_IN_POPUP";#No escanerar codigo despues de cerrar el popu de busqueda de producto
	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT";## Imprimir directo en la impresora 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DOWNLOAD";## Imprimir directo en la impresora 	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE";## Aplicar tabla de amortizacoin segun parametros durante la facturacion.
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_AUTOAPPLY_CASH";## Auto aplicar las facturas de contados 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_PRINT_BY_INVOICE";## Imprimir por cada factura
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "POS-80-Series4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT";## Nombre de la impresora por defecto 	


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_FROM_SERVER";## Impresion en impresora local con datos del servidor 

	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewRegisterFormatoPaginaNormal80mmOpcion1DB" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_URL_PRINTER";## URLs PARA LA IMPRESION DE FACTURA 	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewRegisterFormatoPaginaNormalA4Opcion1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_URL_PRINTER_OPCION2";## URLs PARA LA IMPRESION DE FACTURA 	
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewPrinterDirectFactura80mm" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_URL";## Urls para imprimir el recibo directamente en la impresora, mejor dicho imprimir la factura 

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "http://localhost/posmev4/" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_SERVER_PATH";## URL DE servidor de impresion
	
			
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_CANTIDAD_ITEM";## PANTALLA PARA LA FACTUACION
	
	
/*--Cocina*/
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_SHOW_COMMAND_FOOT";## Símbolo de la Moneda Funcional 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "POS-80-Series4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA";## Imprimie Directamente la Cocina

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewRegisterFormatoPaginaCocina58mm" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_URL_PRINTER_COCINA";## Símbolo de la Moneda Funcional 
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewPrinterDirectCocina58mm" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_URL_PRINTER_COCINA_DIRECT";## Símbolo de la Moneda Funcional 


	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE";## Abrir la caja cuando se imprime una factura


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "123" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_OPEN_CASH_PASSWORD";## Codigo de impresoin de factura
	


/***************************************************************/
/*****CXC *******************/
/***************************************************************/
update tb_catalog_item set ratio  = 1 where catalogItemID = 190;  /*MENSUAL*/
update tb_catalog_item set ratio  = 1 where catalogItemID = 189;  /*QUINCENAL*/
update tb_catalog_item set ratio  = 1 where catalogItemID = 188;  /*SEMANAL*/
update tb_catalog_item set ratio  = 1 where catalogItemID = 531;  /*DIARIO*/

/***************************************************************/
/*****Configuracion de Plan *******************/
/***************************************************************/

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE";## Precio mensual de la licencia 


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0.01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_BY_INVOICE";## PRECIO POR FACTURA , ES LO que se le resgta al balane del core, por cada factura realizada dentro del sistema, siempre y cuando el tipo de pago sea:	CONSUMIBLE
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "MEMBRESIA" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_TIPO_PLAN";## TIPO DE PAGO:  CONSUMIBLE O MENSUALIDAD O PERPETUA
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_BALANCE";## Saldo recargado para usar el sistema, estes saldo se consume a medida que se crean facturas. cuando el plan es PREPAGO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "2050-03-07" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_LICENCES_EXPIRED";## Fecha de expiracion de la licencia 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "10" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_SLEEP";## Dormida despues de pasado el tiempo de espera de la licencia en segundo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "20230827" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_VERSION";## Versions del sistema 
				

/***************************************************************/
/*****Configuracion General *******************/
/***************************************************************/
		
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Heysell Maxell Morales Barrera" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_NAME";## Witman José González Rostran 

				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "gwitman@yahoo.com" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_EMAIL";## CORREO DEL PROPIETARIO


				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "281-220992-0004X" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_ID";## CEDULA DEL PROPIETARIO 


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "281-220992-0004X" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_COMPANY_IDENTIFIER";## RUC 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "TEL: 8953-2942  8611-1898" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PHONE";## TELEFONO DE LA FACTURACION 


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "TEL: 8953-2942  8611-1898" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_PHONE";## TELEFONO DEL PROPIETARIO 
				
	

UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Frente a la antigua estacion del ferrocarril" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_ADDRESS";## la ciudad de Malpaisillo, de la policia nacional 3C.E y 1/2C.S  
							

UPDATE tb_company SET 
	NAME = 'Farmacia Larreynaga' , address = 'Frente a la antigua estacion del ferrocarril' ,
	flavorID = 903 /*usuarioID*/,type='farma_larreynaga'  , abreviature='demo'
WHERE 
	companyID = 2; ##Actualizar el nombre de la compania


/*
Eliminar o desactivar usuarios
*/

/*
update tb_user set isActive = 0;
update tb_user set isActive = 1 WHERE userID in (
 2, 	
 897,  
 898, 
 899,
 900,  
 901, 
 902, 
 903
);

update tb_role set isActive = 0; 
update tb_role set isActive = 1 where roleID in (
	3,
	832,
	833,
	834,
	835,
	836,
	837,
	838	
);
*/




/*tipo de cambio de dolares a cordoba*/
update tb_exchange_rate set 
	ratio = 36 
where 
	currencyID = 2
	and targetCurrencyID = 1; 
	
	
/*tipo de cambio de dolares a cordoba*/
update tb_exchange_rate set 
	ratio = 0.027777 
where 
	currencyID = 1
	and targetCurrencyID = 2; 
	
	
update tb_item set realStateEmployerAgentID = ifnull(realStateEmployerAgentID,0);
update tb_item set realStateCityID = ifnull(realStateCityID,0);
update tb_item set realStateCountryID = ifnull(realStateCountryID,0);
update tb_item set realStateStateID = ifnull(realStateStateID,0);
update tb_item set realStateStateID = ifnull(realStateStateID,0);
update tb_item set realStateRoomBatchServices = ifnull(realStateRoomBatchServices,0);
update tb_item set realStateRooBatchVisit = ifnull(realStateRooBatchVisit,0);
update tb_item set realStateRoomServices = ifnull(realStateRoomServices,0);



update tb_customer set entityContactID = ifnull(entityContactID,0);
update tb_customer_credit_line set dayExcluded = IFNULL(dayExcluded,2323);
update tb_customer set balancePoint = ifnull(balancePoint,0);
update tb_customer set budget = ifnull(budget,0);


update tb_currency set `name` = 'Cordoba' where currencyID = 1;
update tb_currency set `name` = 'Dolar' where currencyID = 2;	
update tb_company_currency set `simb` = 'C$' where currencyID = 1;
update tb_company_currency set `simb` = 'U$' where currencyID = 2;	

	
