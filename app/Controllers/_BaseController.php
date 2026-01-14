<?php

namespace App\Controllers;

use App\Models\Reporting_Model;
use App\Models\Reporting_Parameter_Model;
use App\Models\Reporting_Result_Model;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;




use App\Libraries\core_financial;
use App\Libraries\core_web_accounting;
use App\Libraries\core_web_amortization;
use App\Libraries\core_web_auditoria;
use App\Libraries\core_web_authentication;
use App\Libraries\core_web_catalog;
use App\Libraries\core_web_concept;
use App\Libraries\core_web_convertion;
use App\Libraries\core_web_counter;
use App\Libraries\core_web_currency;
use App\Libraries\core_web_error;
use App\Libraries\core_web_inventory;
use App\Libraries\core_web_javascript;
use App\Libraries\core_web_menu;
use App\Libraries\core_web_notification;
use App\Libraries\core_web_parameter;
use App\Libraries\core_web_permission;
use App\Libraries\core_web_tools;
use App\Libraries\core_web_transaction;
use App\Libraries\core_web_transaction_master_detail;
use App\Libraries\core_web_view;
use App\Libraries\core_web_workflow;
use App\Libraries\core_web_whatsap;
use App\Libraries\core_web_pagadito\core_web_pagadito;


use App\Libraries\core_web_barcode\core_web_barcode;
use App\Libraries\core_web_qr\core_web_qr;
use App\Libraries\core_web_csv\csvreader;
use App\Libraries\core_web_printer_direct\core_web_printer_direct;
use Dompdf\Dompdf;
use App\Libraries\financial\financial_amort;




use App\Models\Core\Bd_Model;
use App\Models\Core\Branch_Model;
use App\Models\Core\Bank_Model;
use App\Models\Core\Catalog_Item_convertion_Model;
use App\Models\Core\Catalog_Item_Model;
use App\Models\Core\Catalog_Model;
use App\Models\Core\Company_Component_flavor_Model;
use App\Models\Core\Company_Component_Model;
use App\Models\Core\Company_Data_View_Model;
use App\Models\Core\Company_Default_Data_View_Model;
use App\Models\Core\Company_Model;
use App\Models\Core\Company_Parameter_Model;
use App\Models\Core\Company_Subelement_audit_Model;
use App\Models\Core\Component_Audit_detail_Model;
use App\Models\Core\Component_Audit_Model;
use App\Models\Core\Component_Autorization_Model;
use App\Models\Core\Component_Model;
use App\Models\Core\Counter_Model;
use App\Models\Core\Currency_Model;
use App\Models\Core\Data_View_Model;
use App\Models\Core\Element_Model;
use App\Models\Core\Exchangerate_Model;
use App\Models\Core\Log_Model;
use App\Models\Core\Membership_Model;
use App\Models\Core\Menu_Element_Model;
use App\Models\Core\Parameter_Model;
use App\Models\Core\Role_Autorization_Model;
use App\Models\Core\Role_Model;
use App\Models\Core\Sub_Element_Model;
use App\Models\Core\Transaction_Concept_Model;
use App\Models\Core\Transaction_Model;
use App\Models\Core\User_Model;
use App\Models\Core\User_Permission_Model;
use App\Models\Core\Workflow_Model;
use App\Models\Core\Workflow_Stage_Model;
use App\Models\Core\Workflow_Stage_Relation_Model;
use App\Models\Core\Workflow_Stage_Affect_Model;

use App\Models\Accounting_Balance_Model;
use App\Models\Account_Level_Model;
use App\Models\Account_Model;
use App\Models\Account_Type_Model;
use App\Models\Biblia_Model;
use App\Models\Center_Cost_Model;
use App\Models\Company_Component_Concept_Model;
use App\Models\Company_Currency_Model;
use App\Models\Company_Log_Model;
use App\Models\Component_Cycle_Model;
use App\Models\Component_Period_Model;
use App\Models\Company_Component_Relation_Model;
use App\Models\Credit_Line_Model;
use App\Models\Customer_Consultas_Sin_Riesgo_Model;
use App\Models\Customer_Credit_Amortization_Model;
use App\Models\Customer_Credit_Document_Endity_Related_Model;
use App\Models\Customer_Credit_Document_Model;
use App\Models\Customer_Credit_Line_Model;
use App\Models\Customer_Credit_Model;
use App\Models\Customer_Model;
use App\Models\Customer_Payment_Method_Model;
use App\Models\Customer_Frecuency_Actuations_Model;
use App\Models\Employee_Calendar_Pay_detail_Model;
use App\Models\Employee_Calendar_Pay_Model;
use App\Models\Employee_Model;
use App\Models\Entity_Account_Model;
use App\Models\Entity_Email_Model;
use App\Models\Entity_Location_Model;
use App\Models\Entity_Model;
use App\Models\Entity_Phone_Model;
use App\Models\Error_Model;
use App\Models\Fixed_Assent_Model;
use App\Models\Itemcategory_Model;
use App\Models\Indicator_Model;
use App\Models\Itemwarehouse_Model;
use App\Models\Item_Data_Sheet_Detail_Model;
use App\Models\Item_Data_Sheet_Model;
use App\Models\Item_Model;
use App\Models\Item_Warehouse_Expired_Model;
use App\Models\Item_Sku_Model;
use App\Models\Journal_Entry_Detail_Model;
use App\Models\Journal_Entry_Model;
use App\Models\Legal_Model;
use App\Models\List_Price_Model;
use App\Models\Natural_Model;
use App\Models\Notification_Model;
use App\Models\Price_Model;
use App\Models\Provideritem_Model;
use App\Models\Provider_Model;
use App\Models\Relationship_Model;
use App\Models\Remember_Model;
use App\Models\Tag_Model;
use App\Models\Transaction_Causal_Model;
use App\Models\Public_Catalog_Model;
use App\Models\Public_Catalog_Detail_Model;
use App\Models\Cash_Box_User_Model;
use App\Models\Cash_Box_Session_Model;
use App\Models\Cash_Box_Model;
use App\Models\Log_Session_Model;

use App\Models\Transaction_Master_Concept_Model;
use App\Models\Transaction_Master_Detail_Credit_Model;
use App\Models\Transaction_Master_Detail_Model;
use App\Models\Transaction_Master_Detail_References_Model;
use App\Models\Transaction_Master_Info_Model;
use App\Models\Transaction_Master_Denomination_Model;
use App\Models\Transaction_Master_Model;

use App\Models\Transaction_Profile_Detail_Model;
use App\Models\Userwarehouse_Model;
use App\Models\User_Tag_Model;
use App\Models\Warehouse_Model;
use App\Models\Temp_Fingerprint_Model;
use App\Models\Fingerprints_Model;
use App\Models\Biometric_User_Model;
use App\Models\Transaction_Master_References_Model;
use App\Models\Bank_Cheque_Model;
use App\Models\Company_Page_Setting_Model;


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class _BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [
        'url','form','web_tools','cookie','text','path',
        'language',
		
        'report/web_tools_report_0001',
		'report/web_tools_report_pasteleria_balladares_helper',
		'report/web_tools_report_licoreria_central_helper',
		'report/web_tools_report_majo_helper',
		'report/web_tools_report_farma_ley_helper',
		'report/web_tools_report_chicharrones_caracenos_helper',
		'report/web_tools_report_divas_helper',
		'report/web_tools_report_elektroclima_helper',
		
		
        'customization/web_tools_customizationview_0000001',
		'customization/web_tools_customizationview_bloomoon',
        'customization/web_tools_customizationview_colirio',
		'customization/web_tools_customizationview_tenampa',
		'customization/web_tools_customizationview_emanuel',
		'customization/web_tools_customizationview_emanuel_pizza',
		'customization/web_tools_customizationview_carlosluis',
		'customization/web_tools_customizationview_creditaguil',
		'customization/web_tools_customizationview_tu_futuro_helper',
		'customization/web_tools_customizationview_autolavadomaximum_helper',
		'customization/web_tools_customizationview_pasteleria_balladares_helper',
		'customization/web_tools_customizationview_pasteleria_peralta_helper',
		'customization/web_tools_customizationview_chic_helper',
		'customization/web_tools_customizationview_majo_helper',
		'customization/web_tools_customizationview_globalpro_helper',
		'customization/web_tools_customizationview_tisey_helper',
		'customization/web_tools_customizationview_cristo_rey_helper',
		'customization/web_tools_customizationview_farma_ley_helper',
		'customization/web_tools_customizationview_frozen_market_helper',
		'customization/web_tools_customizationview_taller_nys_helper',
		'customization/web_tools_customizationview_posme_helper',
		'customization/web_tools_customizationview_fidlocal_helper',
		'customization/web_tools_customizationview_jorge_ramirez_helper',
		
		
    ];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $email;
    protected $validation;
    protected $uri;
    protected $cache;

    protected $core_financial;
    protected $core_web_accounting;
    protected $core_web_amortization;
    protected $core_web_auditoria;
    protected $core_web_authentication;
    protected $core_web_catalog;
    protected $core_web_concept;
    protected $core_web_convertion;
    protected $core_web_counter;
    protected $core_web_currency;
    protected $core_web_elfinder;
    protected $core_web_error;
    protected $core_web_inventory;
    protected $core_web_javascript;
    protected $core_web_logs;
    protected $core_web_menu;
    protected $core_web_notification;
    protected $core_web_parameter;
    protected $core_web_permission;
    protected $core_web_tools;
    protected $core_web_transaction;
    protected $core_web_transaction_master_detail;
    protected $core_web_view;
    protected $core_web_workflow;
    protected $core_web_whatsap;
    protected $core_web_pagadito;


    protected $financial_amort;
    protected $core_web_barcode;
    protected $core_web_qr;
    protected $csvreader;
    protected $core_web_printer_direct;
    protected $dompdf;




    protected $Bd_Model;
    protected $Branch_Model;
    protected $Bank_Model;
    protected $Catalog_Item_convertion_Model;
    protected $Catalog_Item_Model;
    protected $Catalog_Model;
    protected $Company_Component_flavor_Model;
    protected $Company_Component_Model;
	protected $Company_Component_Relation_Model;
    protected $Company_Data_View_Model;
    protected $Company_Default_Data_View_Model;
    protected $Company_Model;
    protected $Company_Parameter_Model;
    protected $Company_Subelement_audit_Model;
    protected $Component_Audit_detail_Model;
    protected $Component_Audit_Model;
    protected $Component_Autorization_Model;
    protected $Component_Element_Model;
    protected $Component_Model;
    protected $Counter_Model;
    protected $Currency_Model;
    protected $Data_View_Model;
    protected $Element_Model;
    protected $Exchangerate_Model;
    protected $Log_Model;
    protected $Membership_Model;
    protected $Menu_Element_Model;
    protected $Parameter_Model;
    protected $Role_Autorization_Model;
    protected $Role_Model;
    protected $Sub_Element_Model;
    protected $Transaction_Concept_Model;
    protected $Transaction_Model;
    protected $User_Model;
    protected $User_Permission_Model;
    protected $Workflow_Model;
    protected $Workflow_Stage_Model;
    protected $Workflow_Stage_Relation_Model;
	protected $Workflow_Stage_Affect_Model;

    protected $Accounting_Balance_Model;
    protected $Account_Level_Model;
    protected $Account_Model;
    protected $Account_Type_Model;
    protected $Biblia_Model;
    protected $Center_Cost_Model;
    protected $Company_Component_Concept_Model;
    protected $Company_Currency_Model;
    protected $Company_Log_Model;
    protected $Component_Cycle_Model;
    protected $Component_Period_Model;
    protected $Credit_Line_Model;
    protected $Customer_Consultas_Sin_Riesgo_Model;
    protected $Customer_Credit_Amortization_Model;
    protected $Customer_Credit_Document_Endity_Related_Model;
    protected $Customer_Credit_Document_Model;
    protected $Customer_Credit_Line_Model;
    protected $Customer_Credit_Model;
    protected $Customer_Model;
    protected $Customer_Payment_Method_Model;
    protected $Customer_Frecuency_Actuations_Model;
    protected $Employee_Calendar_Pay_detail_Model;
    protected $Employee_Calendar_Pay_Model;
    protected $Employee_Model;
    protected $Entity_Account_Model;
    protected $Entity_Email_Model;
    protected $Entity_Location_Model;
    protected $Entity_Model;
    protected $Entity_Phone_Model;
    protected $Error_Model;
    protected $Fixed_Assent_Model;
    protected $Itemcategory_Model;
    protected $Indicator_Model;
    protected $Itemwarehouse_Model;
    protected $Item_Data_Sheet_Detail_Model;
    protected $Item_Data_Sheet_Model;
    protected $Item_Model;
    protected $Item_Warehouse_Expired_Model;
    protected $Item_Sku_Model;
    protected $Journal_Entry_Detail_Model;
    protected $Journal_Entry_Model;
    protected $Legal_Model;
    protected $List_Price_Model;
    protected $Natural_Model;
    protected $Notification_Model;
    protected $Price_Model;
    protected $Provideritem_Model;
    protected $Provider_Model;
    protected $Relationship_Model;
    protected $Remember_Model;
    protected $Tag_Model;
    protected $Transaction_Causal_Model;
    protected $Public_Catalog_Model;
    protected $Public_Catalog_Detail_Model;
    protected $Cash_Box_User_Model;
    protected $Cash_Box_Session_Model;
    protected $Cash_Box_Model;
    protected $Log_Session_Model;

    protected $Transaction_Master_Concept_Model;
    protected $Transaction_Master_Detail_Credit_Model;
    protected $Transaction_Master_Detail_Model;
    protected $Transaction_Master_Detail_References_Model;
    protected $Transaction_Master_Info_Model;
    protected $Transaction_Master_Denomination_Model;
    protected $Transaction_Master_Model;

    protected $Transaction_Profile_Detail_Model;
    protected $Userwarehouse_Model;
    protected $User_Tag_Model;
    protected $Warehouse_Model;
    protected $Temp_Fingerprint_Model;
    protected $Fingerprints_Model;
    protected $Biometric_User_Model;

    protected $Transaction_Master_References_Model;
    protected $Bank_Cheque_Model;
	protected $Company_Page_Setting_Model;

    protected $Reporting_Model;
    protected $Reporting_Parameter_Model;
    protected $Reporting_Result_Model;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session 	= Services::session();
        $this->cache = Services::cache();
        $this->email 	= Services::email();
        $this->validation = Services::validation();
        $this->uri = $this->request->getUri();

        $this->core_financial = new core_financial();
        $this->core_web_accounting= new core_web_accounting();
        $this->core_web_amortization= new core_web_amortization();
        $this->core_web_auditoria= new core_web_auditoria();
        $this->core_web_authentication= new core_web_authentication();
        $this->core_web_catalog= new core_web_catalog();
        $this->core_web_concept= new core_web_concept();
        $this->core_web_convertion= new core_web_convertion();
        $this->core_web_counter= new core_web_counter();
        $this->core_web_currency= new core_web_currency();

        $this->core_web_error= new core_web_error();
        $this->core_web_inventory= new core_web_inventory();
        $this->core_web_javascript= new core_web_javascript();

        $this->core_web_menu= new core_web_menu();
        $this->core_web_notification= new core_web_notification();
        $this->core_web_parameter= new core_web_parameter();
        $this->core_web_permission= new core_web_permission();
        $this->core_web_tools= new core_web_tools();
        $this->core_web_transaction= new core_web_transaction();
        $this->core_web_transaction_master_detail = new core_web_transaction_master_detail();
        $this->core_web_view= new core_web_view();
        $this->core_web_workflow = new core_web_workflow();
        $this->core_web_whatsap = new core_web_whatsap();
        $this->core_web_pagadito = new core_web_pagadito();

        $this->financial_amort = new financial_amort();
        $this->core_web_barcode = new core_web_barcode();
        $this->core_web_qr = new core_web_qr();
        $this->csvreader = new csvreader();
        $this->core_web_printer_direct = new core_web_printer_direct();



        $this->dompdf = new dompdf();
        //$this->core_web_elfinder= new core_web_elfinder();




        $this->Bd_Model = new Bd_Model();
        $this->Branch_Model= new Branch_Model();
        $this->Bank_Model= new Bank_Model();
        $this->Catalog_Item_convertion_Model= new Catalog_Item_convertion_Model();
        $this->Catalog_Item_Model= new Catalog_Item_Model();
        $this->Catalog_Model= new Catalog_Model();
        $this->Company_Component_flavor_Model= new Company_Component_flavor_Model();
        $this->Company_Component_Model= new Company_Component_Model();
		$this->Company_Component_Relation_Model = new Company_Component_Relation_Model();
        $this->Company_Data_View_Model= new Company_Data_View_Model();
        $this->Company_Default_Data_View_Model= new Company_Default_Data_View_Model();
        $this->Company_Model= new Company_Model();
        $this->Company_Parameter_Model= new Company_Parameter_Model();
        $this->Company_Subelement_audit_Model= new Company_Subelement_audit_Model();
        $this->Component_Audit_detail_Model= new Component_Audit_detail_Model();
        $this->Component_Audit_Model= new Component_Audit_Model();
        $this->Component_Autorization_Model= new Component_Autorization_Model();


        $this->Component_Model= new Component_Model();
        $this->Counter_Model= new Counter_Model();
        $this->Currency_Model= new Currency_Model();
        $this->Data_View_Model= new Data_View_Model();
        $this->Element_Model= new Element_Model();
        $this->Exchangerate_Model= new Exchangerate_Model();
        $this->Log_Model= new Log_Model();
        $this->Membership_Model= new Membership_Model();
        $this->Menu_Element_Model= new Menu_Element_Model();
        $this->Parameter_Model= new Parameter_Model();
        $this->Role_Autorization_Model= new Role_Autorization_Model();
        $this->Role_Model= new Role_Model();
        $this->Sub_Element_Model= new Sub_Element_Model();
        $this->Transaction_Concept_Model= new Transaction_Concept_Model();
        $this->Transaction_Model= new Transaction_Model();
        $this->User_Model= new User_Model();
        $this->User_Permission_Model= new User_Permission_Model();
        $this->Workflow_Model= new Workflow_Model();
        $this->Workflow_Stage_Model= new Workflow_Stage_Model();
        $this->Workflow_Stage_Relation_Model= new Workflow_Stage_Relation_Model();
		$this->Workflow_Stage_Affect_Model = new Workflow_Stage_Affect_Model();

        $this->Accounting_Balance_Model= new Accounting_Balance_Model();
        $this->Account_Level_Model= new Account_Level_Model();
        $this->Account_Model= new Account_Model();
        $this->Account_Type_Model= new Account_Type_Model();
        $this->Biblia_Model= new Biblia_Model();
        $this->Center_Cost_Model= new Center_Cost_Model();
        $this->Company_Component_Concept_Model= new Company_Component_Concept_Model();
        $this->Company_Currency_Model= new Company_Currency_Model();
        $this->Company_Log_Model= new Company_Log_Model();
        $this->Component_Cycle_Model= new Component_Cycle_Model();
        $this->Component_Period_Model= new Component_Period_Model();
        $this->Credit_Line_Model= new Credit_Line_Model();
        $this->Customer_Consultas_Sin_Riesgo_Model= new Customer_Consultas_Sin_Riesgo_Model();
        $this->Customer_Credit_Amortization_Model= new Customer_Credit_Amortization_Model();
        $this->Customer_Credit_Document_Endity_Related_Model= new Customer_Credit_Document_Endity_Related_Model();
        $this->Customer_Credit_Document_Model= new Customer_Credit_Document_Model();
        $this->Customer_Credit_Line_Model= new Customer_Credit_Line_Model();
        $this->Customer_Credit_Model= new Customer_Credit_Model();
        $this->Customer_Model= new Customer_Model();
        $this->Customer_Payment_Method_Model=new Customer_Payment_Method_Model();
        $this->Customer_Frecuency_Actuations_Model=new Customer_Frecuency_Actuations_Model();
        $this->Employee_Calendar_Pay_detail_Model= new Employee_Calendar_Pay_detail_Model();
        $this->Employee_Calendar_Pay_Model= new Employee_Calendar_Pay_Model();

        $this->Employee_Model= new Employee_Model();
        $this->Entity_Account_Model= new Entity_Account_Model();
        $this->Entity_Email_Model= new Entity_Email_Model();
        $this->Entity_Location_Model = new Entity_Location_Model();
        $this->Entity_Model= new Entity_Model();
        $this->Entity_Phone_Model= new Entity_Phone_Model();
        $this->Error_Model= new Error_Model();
        $this->Fixed_Assent_Model= new Fixed_Assent_Model();
        $this->Itemcategory_Model= new Itemcategory_Model();
        $this->Indicator_Model = new Indicator_Model();
        $this->Itemwarehouse_Model= new Itemwarehouse_Model();
        $this->Item_Data_Sheet_Detail_Model= new Item_Data_Sheet_Detail_Model();
        $this->Item_Data_Sheet_Model= new Item_Data_Sheet_Model();
        $this->Item_Model= new Item_Model();
        $this->Item_Warehouse_Expired_Model= new Item_Warehouse_Expired_Model();
        $this->Item_Sku_Model = new Item_Sku_Model();
        $this->Journal_Entry_Detail_Model= new Journal_Entry_Detail_Model();
        $this->Journal_Entry_Model= new Journal_Entry_Model();
        $this->Legal_Model= new Legal_Model();
        $this->List_Price_Model= new List_Price_Model();
        $this->Natural_Model= new Natural_Model();
        $this->Notification_Model= new Notification_Model();
        $this->Price_Model= new Price_Model();
        $this->Provideritem_Model= new Provideritem_Model();
        $this->Provider_Model= new Provider_Model();
        $this->Relationship_Model= new Relationship_Model();
        $this->Remember_Model= new Remember_Model();
        $this->Tag_Model= new Tag_Model();
        $this->Transaction_Causal_Model= new Transaction_Causal_Model();
        $this->Public_Catalog_Model = new Public_Catalog_Model();
        $this->Public_Catalog_Detail_Model = new Public_Catalog_Detail_Model();
        $this->Cash_Box_User_Model = new Cash_Box_User_Model();
        $this->Cash_Box_Session_Model = new Cash_Box_Session_Model();
        $this->Cash_Box_Model = new Cash_Box_Model();
        $this->Log_Session_Model = new Log_Session_Model();

        $this->Transaction_Master_Concept_Model= new Transaction_Master_Concept_Model();
        $this->Transaction_Master_Detail_Credit_Model= new Transaction_Master_Detail_Credit_Model();
        $this->Transaction_Master_Detail_Model= new Transaction_Master_Detail_Model();
        $this->Transaction_Master_Detail_References_Model = new Transaction_Master_Detail_References_Model();
        $this->Transaction_Master_Info_Model= new Transaction_Master_Info_Model();
        $this->Transaction_Master_Denomination_Model = new Transaction_Master_Denomination_Model();
        $this->Transaction_Master_Model= new Transaction_Master_Model();

        $this->Transaction_Profile_Detail_Model= new Transaction_Profile_Detail_Model();
        $this->Userwarehouse_Model= new Userwarehouse_Model();
        $this->User_Tag_Model= new User_Tag_Model();
        $this->Warehouse_Model= new Warehouse_Model();

        $this->Temp_Fingerprint_Model = new Temp_Fingerprint_Model();
        $this->Fingerprints_Model = new Fingerprints_Model();
        $this->Biometric_User_Model = new Biometric_User_Model();
        $this->Transaction_Master_References_Model = new Transaction_Master_References_Model();
        $this->Bank_Cheque_Model = new Bank_Cheque_Model();
		$this->Company_Page_Setting_Model = new Company_Page_Setting_Model();

        $this->Reporting_Model              = new Reporting_Model();
        $this->Reporting_Parameter_Model    = new Reporting_Parameter_Model();
        $this->Reporting_Result_Model       = new Reporting_Result_Model();

    }

    function posMeGetRutasOfController(){

        $controllerMethods 	= get_class_methods($this);
        $result 			= "\r\n";
        $lineaNew			= "";
        $lineaNew2			= "";
        foreach($controllerMethods as $methosi){
            $lineaNew			= "";
            $lineaNew2			= "";
            if(
                $methosi == "cachePage" ||
                $methosi == "forceHTTPS" ||
                $methosi == "loadHelpers" ||
                $methosi == "initController" ||
                $methosi == "validate" ||
                $methosi == "validateData" ||
                $methosi == "posMeGetRutasOfController"
            )
                continue;


            $lineaNew 			= $lineaNew."\$routes->match(['get','post'],'". str_replace("app\\controllers\\","", strtolower(get_class($this)))  ."/".$methosi."";
            $refl 				 = new \ReflectionClass(get_class($this));
            $par 				 = $refl->getMethod($methosi)->getParameters();

            //Obtener definision de la funcion
            $archivoNombre 		= $refl->getMethod($methosi)->getFileName();
            $liniaInicial		= $refl->getMethod($methosi)->getStartLine();
            $liniaFinal			= $refl->getMethod($methosi)->getEndLine();
            $textoArchivo 		= file($archivoNombre);
            $functionDefinition = "";
            for($il = ($liniaInicial-1); $il < $liniaFinal; $il++){
                $functionDefinition = $functionDefinition.$textoArchivo[$il]."\r\n";
            }

            //Ver si la funcion contiene la palabra 'getSegments'
            $posicion 			= strpos($functionDefinition,"getSegments");
            $i 					= 0;
            $cantidadMetodos	= count($par);
            if($cantidadMetodos > 0){
                //super importante no borrar
                //obtener la cantidad de parametros de cada controlador.

                //foreach($par as $parameteri){
                //	$result = $result." Parametro ".$i;
                //	$result = $result."-Nombre :".$parameteri->name;
                //	$result = $result."-Es opcional : ".($parameteri->isOptional() == true? "true":"false");
                //	$result = $result."-Es Default Habilitado  : ".($parameteri->isDefaultValueAvailable() == true? "true":"false");
                //
                //	if($parameteri->isDefaultValueAvailable()){
                //		$valueDefault 	= $parameteri->getDefaultValue();
                //		$valueDefault	= is_null($valueDefault) ? "NULL": $valueDefault;
                //		$valueDefault	= $valueDefault === "" ? "EMPTY": $valueDefault;
                //		$result 		= $result."-Valor por defecto  : ".$valueDefault;
                //	}
                //	else
                //		$result = $result."-Valor por defecto  : N/D";
                //
                //
                //	$i++;
                //}
                $lineaNew = $lineaNew."/(:any)";
            }
            else if($posicion > 0){
                $lineaNew = $lineaNew."/(:any)";
            }

            //si la ultima linea tiene la palabra any, ingrear otra lineas sin la palabra any
            $lineaNew = $lineaNew."','".str_replace("App\\Controllers\\","", get_class($this)) ."::".$methosi."');\r\n<br/>";
            if(strpos($lineaNew,"/(:any)") > 0){
                $lineaNew2 = str_replace("/(:any)","",$lineaNew);
            }


            $result = $result.$lineaNew2.$lineaNew;

        }

        log_message("info",$result);
        return $result;

    }


}
