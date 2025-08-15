<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);



/* CONSTANTES DE LA APLICACION*/
//
//
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
//APP
define('DB_BDNAME',"posme");
define('APP_COMERCE_NAME',"posmev4");

define('APP_NEED_AUTHENTICATION',true);
define('EMAIL_APP',"posme2022@gmail.com");
define('EMAIL_APP_PASSWORD',"tbxbdwxjcddqptxk");
define('EMAIL_APP_NOTIFICACION',"posme2022@gmail.com");
define('EMAIL_APP_COPY',"www.witman@gmail.com");
define('PATH_FILE_OF_APP','C:/xampp/teamds2/nsSystem/v4posme/public/resource/file_company');
define('PATH_FILE_OF_APP_ROOT','C:/xampp/teamds2/nsSystem/v4posme/public/resource');
define('PATH_FILE_OF_APP_SYSTEM','C:/xampp/teamds2/nsSystem/v4posme/');
define('PATH_FILE_OF_UPLOAD_WRITE','C:/xampp/teamds2/nsSystem/v4posme/writable/uploads');
define('PATH_FILE_OF_XAMPP_TMP','C:/xampp/tmp');
define('PATH_EMAIL','C:\xampp\sendmail\sendmail.exe -t');
define('APP_NAME','WSYSTEM');
define('APP_TIMEZONE','America/Managua');/*yyyy-m-d*/
define("APP_COMPANY","2");
define("APP_USERADMIN","2");
define("APP_ROL_SUPERADMIN","3");
define("URL_SUFFIX_OLD",".aspx");
define("URL_SUFFIX_NEW","");
define("URL_SUFFIX","");
define("TIME_CACHE_APP","300");
define('APP_IP_SERVER',"localhost");
define('APP_BRANCH','2');
define('APP_PROVIDER','293');
define('APP_EMPLOYEE','614');
define('APP_CUSTOMER01','13');
define('APP_CUSTOMER02','309');
define('APP_HOUR_DIFERENCE_MYSQL','00:00:00');
define('APP_HOUR_DIFERENCE_PHP','+0 minutes');
define('APP_HOUR_DIFERENCE_MYSQL_EMBEDDED','-0 HOUR');
define('APP_URL_RESOURCE_CSS_JS','http://localhost/posmev4');
define('URL_REDIRECT_CALENDAR_POSME','http://localhost/'.APP_COMERCE_NAME.'/core_user/savepublicgooglereturn');
define('APP_URL_WOOCOMERCE','https://posme.net');
define('APP_USERDEFAULT_VALUE','superadmin');
define('APP_PASSWORDEFAULT_VALUE','jose.');

//BD
//define("DB_PASSWORD","root1.2Blandon");
//define("DB_USER","root");
//define('DB_BDNAME',"posme");
//define('DB_BDNAME_MERGE',"posme_merge");
//define('DB_SERVER',"localhost");
define("DB_PASSWORD","root1.2Blandon");
define("DB_USER","root");
define('DB_BDNAME_MERGE',"posme_merge");
define('DB_SERVER',"localhost");


//Tipos de Menu
define("MENU_TOP",4);
define("MENU_LEFT",5);
define("MENU_BODY",6);
define("MENU_TOP_BODY",7);
define("MENU_HIDDEN_POPUP",8);

//Tipos de Vistas
define('ELEMENT_TYPE_PAGE','1');
define('ELEMENT_TYPE_TABLE','2');
define('CALLERID_LIST','1');
define('CALLERID_SEARCH','2');

//Permisos sobre los registros en las vistas
define('PERMISSION_NONE','-1');
define('PERMISSION_ALL','0');
define('PERMISSION_BRANCH','1');
define('PERMISSION_ME','2');

//Permisos sobre los registros en los workflow
define('COMMAND_VINCULATE','1');
define('COMMAND_EDITABLE','2');
define('COMMAND_EDITABLE_TOTAL','3');
define('COMMAND_ELIMINABLE','4'); 
define('COMMAND_APLICABLE','5');

//Configuracion de Reportes 
define("PAGE_SIZE","LETTER");
define("LEFT_MARGIN","0.5");//cm
define("RIGHT_MARGIN","0.5");//cm
define("BOTTOM_MARGIN","0.5");//cm
define("TOP_MARGIN","0.5");//cm
define("FONT_SIZE_SMALL","7");
define("FONT_SIZE","8");
define("FONT_SIZE_BOLD","9");
define("FONT_SIZE_TITLE","10");
define("LOGO_SIZE_WIDTH","105");
define("LOGO_SIZE_HEIGTH","60"); 

//Configuracion de Factura
define("PAGE_INVOICE","INVOICE_PRINTER_TERMICA_001");
define("LEFT_MARGIN_INVOICE","0");//cm
define("RIGHT_MARGIN_INVOICE","0.9");//cm
define("BOTTOM_MARGIN_INVOICE","0");//cm
define("TOP_MARGIN_INVOICE","0");//cm
define("FONT_SIZE_TITLE_INVICE","12");//cm
define("FONT_SIZE_BODY_INVICE","10");//cm

//Mensajes
define("USER_NOT_AUTENTICATED","TIEMPO DE ESPERA AGOTADO:   <a href='http://".APP_IP_SERVER."/".APP_COMERCE_NAME."/'>**INGRESAR**</a> ");
define("USER_LOGIN","<a href='http://".APP_IP_SERVER."/".APP_COMERCE_NAME."/'>**INGRESAR**</a> ");
define("NOT_ALL_INSERT","NO PUEDE INGRESAR UN REGISTRO");
define("NOT_ALL_EDIT","NO PUEDE EDITAR NINGUN REGISTRO");
define("NOT_EDIT","NO PUEDE EDITAR UN REGISTRO QUE NO FUE CREADO POR USTED");
define("NOT_WORKFLOW_EDIT","EL REGISTRO NO PUEDE SER EDITADO POR SU ESTADO ACTUAL");
define("NOT_ALL_DELETE","NO PUEDE ELIMINAR NINGUN REGISTRO");
define("NOT_DELETE","NO PUEDE ELIMINAR UN REGISTRO QUE NO FUE CREADO POR USTED");
define("NOT_WORKFLOW_DELETE","EL REGISTRO NO PUEDE SER ELIMINADO POR SU ESTADO ACTUAL");
define("NOT_ACCESS_CONTROL","NO TIENE ACCESO AL CONTROLADOR");
define("NOT_ACCESS_FUNCTION","NO TIENE ACCESO A LA FUNCION");
define("NOT_PARAMETER","PARAMETROS INCORRECTOS");
define("SUCCESS","SUCCESS");
define("ERROR","ERROR");

define("NOT_VALID_USER","USUARIO O PASSWORD INCORRECTO");
define("NOT_VALID_EMAIL","EMAIL INCORRECTA");
define("HELLOW","posMe NOTICIAS AUTOMATICAS");
define("NICKNAME_DUPLI","NICKNAME EXISTE");
define("EMAIL_DUPLI","EMAIL EXISTE");
define("MESSAGE_EMAL","DATOS ENVIADOS A SU CUENTA DE CORREO");
define("REMEMBER_PASSWORD","PASSWORD ENVIADO");


define('DB_BDNAME_BIOMETRIC',"biometric");
define('DB_BDNAME_SHEMA',"information_schema");
define('PHONE_POSME','50587125827');
define('GENERAR_LOG_INFO_OF_DB',false);