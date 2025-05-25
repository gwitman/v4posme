<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Relationship_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 
  function delete_app_posme($employeeID,$customerID){
		$db 	= db_connect();
		$builder	= $db->table("tb_relationship");	
		
		$builder->where("employeeID",$employeeID);
		$builder->where("customerID",$customerID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 			= db_connect();
		$builder		= $db->table("tb_relationship");	
		
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 		
		
   }

    function update_app_posme($relationshipID, $data)
    {
        $db         = db_connect();
        $builder    = $db->table("tb_relationship");

        $builder->where("relationshipID", $relationshipID);

        return $builder->update($data);
    }

	 function get_rowByID($relationshipID)
    {
        $db         = db_connect();
        $builder     = $db->table('tb_relationship');

        $builder->where('relationshipID', $relationshipID);
        $builder->where('isActive', 1);
        $recordSet = $builder->get()->getRow();
        return $recordSet;
    }


      function get_rowByPK($employeeID,$customerID){
		$db 	= db_connect();
				
		$sql = "";
		$sql = sprintf("select r.relationshipID, r.employeeID, r.customerID, r.orderNo, r.startOn, r.endOn,  r.isActive, concat(n.firstName,' ',n.lastName) as firstName, l.legalName");
		$sql = $sql.sprintf(" from tb_relationship as r inner join tb_naturales as n on r.employeeID = n.entityID INNER join tb_legal as l on r.customerID = l.entityID");
		$sql = $sql.sprintf(" where r.employeeID = $employeeID");		
		$sql = $sql.sprintf(" and r.isActive= 1");		
		$sql = $sql.sprintf(" and r.customerID = $customerID");	
		
		//Ejecutar Consulta  
		return $db->query($sql)->getRow();
   }

}
?>