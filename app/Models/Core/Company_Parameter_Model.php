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
		return $recordSet;
   }
   
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
		return $db->query($sql)->getResult();

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