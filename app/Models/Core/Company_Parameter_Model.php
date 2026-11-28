<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Company_Parameter_Model extends Model  {
   function __construct(){		
		parent::__construct();
   }  
   function update_app_posme($companyID,$parameterID,$data){
		$db 		= db_connect();		
		$builder 	= $db->table('tb_company_parameter');
		
		$builder->where("companyID",$companyID);
		$builder->where("parameterID",$parameterID);				
		$builder->update($data);		
		
   }
   /**
    * Parametro de la compania con `value` ya resuelto (ver resolveValue).
    * @param int $companyID   Compania.
    * @param int $parameterID Parametro (tb_parameter).
    * @return object|null Fila de tb_company_parameter; valueOriginal conserva el value de la tabla.
    */
   function get_rowByParameterID_CompanyID($companyID,$parameterID){
		$db 		= db_connect();



		$sql = "";
		$sql = sprintf("select companyID,parameterID,display,description,value,customValue");
		$sql = $sql.sprintf(" from  tb_company_parameter ");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and parameterID = $parameterID");

		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getRow();

		//Resultado
		return $this->resolveValue($recordSet);
   }

   /**
    * Parametros de la compania con `value` ya resuelto (ver resolveValue).
    * @param int $companyID Compania.
    * @return array Filas de tb_company_parameter + name; valueOriginal conserva el value de la tabla.
    */
   function get_rowByCompanyID($companyID)
   {
	   	$db 	= db_connect();
		$db->table('tb_company_parameter');
		$sql = "";
		$sql = sprintf("select c.companyID,c.parameterID,c.display,c.description,c.value,c.customValue, p.name ");
		$sql = $sql.sprintf(" from tb_company_parameter  c ");
		$sql = $sql.sprintf(" inner join tb_parameter p  on p.parameterID = c.parameterID ");
		$sql = $sql.sprintf(" where companyID = $companyID");

		//Ejecutar Consulta
		$recordSet = $db->query($sql)->getResult();
		foreach($recordSet as $objRow)
		{
			$this->resolveValue($objRow);
		}
		return $recordSet;

   }

   /**
    * Resuelve el valor efectivo del parametro y lo deja en `value`, en este orden:
    *   1) tb_company_parameter_user.value del usuario en sesion (isActive = 1)
    *   2) tb_company_parameter.customValue
    *   3) tb_company_parameter.value
    * Un nivel "sin valor" es NULL o ''. El value original de la tabla queda en valueOriginal.
    * @param object|null $objRow Fila de tb_company_parameter.
    * @return object|null La misma fila resuelta.
    */
   function resolveValue($objRow)
   {
		if(!$objRow)
		return $objRow;

		$objRow->valueOriginal 	= $objRow->value;
		$userValues 			= $this->getUserValuesSession();

		if(isset($userValues[$objRow->parameterID]))
		$objRow->value = $userValues[$objRow->parameterID];
		else if($objRow->customValue !== null && $objRow->customValue !== "")
		$objRow->value = $objRow->customValue;

		return $objRow;
   }

   /**
    * Valores personalizados (tb_company_parameter_user) del usuario en sesion, indexados por parameterID.
    * Sin sesion activa (API, cron, rabbitmq) o sin la tabla en la BD devuelve un arreglo vacio.
    * Se cachea por BD y usuario durante la peticion para no repetir la consulta en cada parametro.
    * @return array parameterID => value (solo valores no vacios).
    */
   function getUserValuesSession()
   {
		static $cache = array();

		//Usuario en sesion: solo si la sesion ya fue iniciada (no se abre una nueva)
		if(session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION["user"]))
		return array();

		$objUser = $_SESSION["user"];
		$userID  = is_object($objUser) && isset($objUser->userID) ? (int)$objUser->userID : 0;
		if($userID <= 0)
		return array();

		$db  = db_connect();
		$key = $db->getDatabase()."_".$userID;
		if(isset($cache[$key]))
		return $cache[$key];

		$cache[$key] = array();
		if(!$db->tableExists("tb_company_parameter_user"))
		return $cache[$key];

		//Si hay varias filas para el mismo parametro gana la mas reciente (orden ascendente, se sobrescribe)
		$sql = "";
		$sql = $sql.sprintf("select ");
		$sql = $sql.sprintf("  pu.parameterID, ");
		$sql = $sql.sprintf("  pu.value ");
		$sql = $sql.sprintf("from tb_company_parameter_user pu ");
		$sql = $sql.sprintf("where pu.userID = %d ", $userID);
		$sql = $sql.sprintf("and pu.isActive = 1 ");
		$sql = $sql.sprintf("and pu.value is not null ");
		$sql = $sql.sprintf("and pu.value <> '' ");
		$sql = $sql.sprintf("order by pu.parameterUserID ");

		foreach($db->query($sql)->getResult() as $objRow)
		{
			$cache[$key][$objRow->parameterID] = $objRow->value;
		}
		return $cache[$key];
   }

   /**
    * Parametros editables (tb_parameter.isEdited = 1) de la compania, para el tab Parametros de core_user/edit.
    * @param int $companyID Compania.
    * @return array Objetos con companyParameterID, parameterID, name, type, source, display, description, value, customValue.
    */
   function get_rowByCompanyIDEditable($companyID)
   {
		$db 	= db_connect();
		$sql 	= "";
		$sql 	= $sql.sprintf("select ");
		$sql 	= $sql.sprintf("  c.companyParameterID, ");
		$sql 	= $sql.sprintf("  c.parameterID, ");
		$sql 	= $sql.sprintf("  p.name, ");
		$sql 	= $sql.sprintf("  p.type, ");
		$sql 	= $sql.sprintf("  p.source, ");
		$sql 	= $sql.sprintf("  c.display, ");
		$sql 	= $sql.sprintf("  c.description, ");
		$sql 	= $sql.sprintf("  c.value, ");
		$sql 	= $sql.sprintf("  c.customValue ");
		$sql 	= $sql.sprintf("from tb_company_parameter c ");
		$sql 	= $sql.sprintf("inner join tb_parameter p on p.parameterID = c.parameterID ");
		$sql 	= $sql.sprintf("where c.companyID = %d ", $companyID);
		$sql 	= $sql.sprintf("and p.isEdited = 1 ");
		$sql 	= $sql.sprintf("order by c.display ");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

   /**
    * Actualiza solo customValue de un parametro de la compania (por companyParameterID + companyID).
    * @param int    $companyID          Compania.
    * @param int    $companyParameterID Registro de tb_company_parameter.
    * @param string $customValue        Valor personalizado.
    * @return int Filas afectadas.
    */
   function update_customValue_app_posme($companyID,$companyParameterID,$customValue)
   {
		$db 		= db_connect();
		$builder 	= $db->table('tb_company_parameter');

		$builder->where("companyID",$companyID);
		$builder->where("companyParameterID",$companyParameterID);
		$builder->update(array("customValue" => $customValue));
		return $db->affectedRows();
   }

   
}
?>