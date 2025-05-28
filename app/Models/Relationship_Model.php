<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Relationship_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 

  function delete_app_posme($relationshipID){
		$db 	   = db_connect();
		$builder	= $db->table("tb_relationship");	
		
		$builder->where("relationshipID",$relationshipID);			
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
        $db          = db_connect();
        $builder     = $db->table('tb_relationship');

        $builder->where('relationshipID', $relationshipID);
        $builder->where('isActive', 1);
        $recordSet = $builder->get()->getRow();
        return $recordSet;
    }

   function get_rowByPK($employeeID, $customerID){
		$db 	= db_connect();
				
		$sql = "";
		$sql = sprintf("select r.relationshipID, r.employeeID, r.customerID, r.orderNo, r.reference1, r.startOn, r.endOn,  r.isActive, concat(e.employeNumber,' / ',concat(n.firstName,' ',n.lastName)) as firstName, concat(c.customerNumber,' / ' ,l.legalName) as legalName");
		$sql = $sql.sprintf(" from tb_relationship as r inner join tb_naturales as n on r.employeeID = n.entityID INNER join tb_legal as l on r.customerID = l.entityID INNER join tb_employee e on e.entityID = n.entityID INNER join tb_customer c on c.entityID = l.entityID");
		$sql = $sql.sprintf(" where r.employeeID = $employeeID");		
		$sql = $sql.sprintf(" and r.isActive= 1");		
		$sql = $sql.sprintf(" and r.customerID = $customerID");	
		
		//Ejecutar Consulta  
		return $db->query($sql)->getRow();
   }

   function get_rowByPKID($relationshipID){
		$db 	= db_connect();
				
		$sql = "";
		$sql = sprintf("select r.relationshipID, r.employeeID, r.customerID, r.orderNo, r.reference1, r.startOn, r.endOn,  r.isActive, concat(e.employeNumber,' / ',concat(n.firstName,' ',n.lastName)) as firstName, concat(c.customerNumber,' / ' ,l.legalName) as legalName");
		$sql = $sql.sprintf(" from tb_relationship as r inner join tb_naturales as n on r.employeeID = n.entityID INNER join tb_legal as l on r.customerID = l.entityID INNER join tb_employee e on e.entityID = n.entityID INNER join tb_customer c on c.entityID = l.entityID");
		$sql = $sql.sprintf(" where r.relationshipID = $relationshipID");		
		$sql = $sql.sprintf(" and r.isActive= 1");		
		
		//Ejecutar Consulta  
		return $db->query($sql)->getRow();
   }

}
?>