<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class User_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   
   function insert_app_posme($data){
		$db 					= db_connect();   
		$builder				= $db->table("tb_user");
		$result 				= $builder->insert($data);
		return $db->insertID();
		
   }
   function update_app_posme($companyID,$branchID,$userID,$data){
		$db 					= db_connect();   
		$builder				= $db->table("tb_user");
		
		$builder->where("branchID",$branchID);
		$builder->where("companyID",$companyID);
		$builder->where("userID",$userID);
		
		$result 				= $builder->update($data);
		return $result;		
		
   }
   
   function get_rowByComercio($comercio){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where comercio = '$comercio' ");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByFoto($foto){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where foto = '$foto' ");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }


   function get_rowByExistNickname($nickname){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where nickname = '$nickname' ");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByNiknamePassword($nickname,$password){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where nickname = '$nickname'");
		$sql = $sql.sprintf(" and password = '$password'");
		$sql = $sql.sprintf(" and isActive= 1");	
				
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEmail($email){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where email = '$email'");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$branchID,$userID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");		
		$sql = $sql.sprintf(" and userID = $userID");		
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_All($companyID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_UserByBussnes($companyID,$bussines){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where companyID = $companyID");					
		$sql = $sql.sprintf(" and nickname like '%s'" ,"%@".$bussines);	
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_countUser($companyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		
   		return $db->query($sql)->getRow()->counter;
   }
  
   function getCount($companyID){
		$db 	= db_connect();		
		
		$sql = "";
		$sql = sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		
   		return $db->query($sql)->getRow()->counter;
   }
   
}
?>