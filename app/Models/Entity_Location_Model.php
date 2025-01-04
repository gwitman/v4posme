<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Entity_Location_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
    function delete_app_posme($entityLocationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		$builder->where("entityLocationID",$entityLocationID);	
		$builder->delete();
		
   }
   function deleteByEntity($entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		
		$builder->where("entityID",$entityID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($entityLocationID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		
		$builder->where("entityLocationID",$entityLocationID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($entityLocationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");    
		
		$sql = "";
		$sql = sprintf("select 
				c.entityLocationID,c.entityID,c.createdOn,
				c.isActive,c.longituded,c.latituded,c.reference1,
				c.userName,c.companyName
			");
		$sql = $sql.sprintf(" from tb_entity_location c");		
		$sql = $sql.sprintf(" where c.entityLocationID = $entityLocationID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");    
		
		$sql = "";
		$sql = sprintf("select 
				c.entityLocationID,c.entityID,c.createdOn,
				c.isActive,c.longituded,c.latituded,c.reference1,
				c.userName,c.companyName
			");
		$sql = $sql.sprintf(" from tb_entity_location c");		
		$sql = $sql.sprintf(" where c.entityID = $entityID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
   function get_UsersLocationAll()
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				IFNULL(kk.userName,0) as Name,
				kk.latituded as Latitude,
				kk.longituded as Longitude,
				kk.companyName 
			from 
				tb_entity_location kk 
				inner join (
						select 
							e.companyName,
							e.userName,
							max(e.entityLocationID) as maxId 
						from 
							tb_entity_location e 
						where 
							e.isActive = 1 and 
							e.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
							e.userName is not null and 
							e.companyName is not null and 
							e.userName != '' 
						GROUP BY 
							e.companyName,e.userName 
				) cc on 
					cc.maxId = kk.entityLocationID 
		");

		return $db->query($sql)->getResult();
   }
   
   
   function get_UsersLocationByCompany($companyName)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				IFNULL(kk.userName,0) as Name,
				kk.latituded as Latitude,
				kk.longituded as Longitude,
				kk.companyName 
			from 
				tb_entity_location kk 
				inner join (
						select 
							e.userName,
							max(e.entityLocationID) as maxId 
						from 
							tb_entity_location e 
						where 
							e.isActive = 1 and 
							e.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
							e.companyName = '".$companyName."' and 
							e.userName is not null and 
							e.userName != '' 
						GROUP BY 
							e.userName 
				) cc on 
					cc.maxId = kk.entityLocationID 
		");

		return $db->query($sql)->getResult();
   }
   
   function get_UsersLocationByCompanyAndUser($companyName,$userName)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				IFNULL(kk.userName,0) as Name,
				kk.latituded as Latitude,
				kk.longituded as Longitude,
				kk.companyName 
			from 
				tb_entity_location kk 
			where 
				kk.isActive = 1 and 
				kk.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
				kk.companyName = '".$companyName."' and 
				kk.userName = '".$userName."'  and 
				kk.userName != '' 
		");

		return $db->query($sql)->getResult();
   }
   
   function get_UsersLocationByCompanyAndUserLast($companyName,$userName)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				IFNULL(kk.userName,0) as Name,
				kk.latituded as Latitude,
				kk.longituded as Longitude,
				kk.companyName 
			from 
				tb_entity_location kk 
			where 
				kk.isActive = 1 and 
				kk.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
				kk.companyName = '".$companyName."' and 
				kk.userName = '".$userName."'  and 
				kk.userName != '' 
			order by 
				kk.entityLocationID desc limit 1
		");

		return $db->query($sql)->getResult();
   }
   
   
   function get_Company()
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				distinct ifnull(e.companyName ,'') as companyName 
			from 
				tb_entity_location e 
			where 
				e.isActive = 1 and 
				e.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
				e.companyName is not null and 
				e.userName is not null and 
				e.userName != ''  
		");

		return $db->query($sql)->getResult();
   }
   
   function get_UserByCompany($companyName)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				distinct ifnull(e.userName ,'') as userName 
			from 
				tb_entity_location e 
			where 
				e.isActive = 1 and 
				e.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
				e.companyName = '".$companyName."' and 
				e.userName is not null and 
				e.userName != ''  
		");

		return $db->query($sql)->getResult();
   }
   
   function get_UserAll()
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			select 
				distinct ifnull(e.userName ,'') as userName 
			from 
				tb_entity_location e 
			where 
				e.isActive = 1 and 
				e.createdOn between DATE_ADD(NOW(),INTERVAL -1 YEAR) AND  NOW() and 
				e.companyName  is not null and 
				e.userName is not null and 
				e.userName != '' 
		");

		return $db->query($sql)->getResult();
   }
   
}
?>