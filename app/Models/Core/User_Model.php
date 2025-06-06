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
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where comercio = '$comercio' ");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	function get_rowByRoleAdmin($companyID)
	{
		
		$db 	= db_connect();
		$sql = "";
		$sql = "
			select 
				u.companyID,
				u.branchID,
				u.userID,
				u.nickname,
				u.`password`,
				u.createdOn,
				u.isActive,
				u.email,
				u.createdBy,
				u.employeeID,
				u.useMobile,
				u.phone,
				u.lastPayment,
				u.comercio,
				u.foto,
				u.token_google_calendar,
				u.locationID 
			from 
				tb_user  u 
				inner join tb_membership p on
					u.userID = p.userID 
				inner join tb_role  r on 
					p.roleID = r.roleID 
			where 
				u.isActive = 1 and 
				r.`name` like '%admin%' and 
				u.companyID = ".$companyID." 
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	function get_rowByFoto($foto){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where foto = '$foto' ");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}


	function get_rowByExistNickname($nickname){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where nickname = '$nickname' ");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
	function get_rowByNiknamePassword($nickname,$password){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where nickname = '".str_replace('"',"",str_replace("'","",$nickname))."'");
		$sql = $sql.sprintf(" and password = '".str_replace('"',"",str_replace("'","",$password)."'"));
		$sql = $sql.sprintf(" and isActive= 1");	
				
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
	function get_rowByEmail($email){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where email = '$email'");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
	function get_rowByPK($companyID,$branchID,$userID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");		
		$sql = $sql.sprintf(" and userID = $userID");		
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}

    function get_rowByEmployeeID($companyID, $employeeID){
        $db 	= db_connect();
        $builder = $db->table("tb_user tu");
        $builder = $builder->select(" tu.companyID,
                                   tu.branchID,
                                   tu.userID,
                                   tu.nickname,
                                   tu.password,
                                   tu.createdOn,
                                   tu.isActive,
                                   tu.email,
                                   tu.createdBy,
                                   tu.employeeID,
                                   tu.useMobile,
                                   tu.phone,
                                   tu.lastPayment,
                                   tu.comercio,
                                   tu.foto,
                                   tu.token_google_calendar,
                                   tu.locationID")
            ->where("isActive",1)
            ->where("companyID",$companyID)
            ->where("employeeID",$employeeID);

        //Ejecutar Consulta
        return $builder->get()->getRowObject();
    }

	function get_rowByUserID($userID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where userID = $userID");
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
	function get_All($companyID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
		$sql = $sql.sprintf(" from tb_user");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}

	function get_UserByUserIDAndCompanyID($companyID,$userID){
		$db = db_connect();
		$builder = $db->table('tb_user tu');
		$builder= $builder->select("tn.companyID, tn.branchID, tn.entityID, tn.firstName, tn.lastName, tn.address, tn.statusID, tn.profesionID, tn.naturalesID, tu.userID, tu.nickname, tu.password, tu.createdOn, tu.isActive, tu.email, tu.createdBy, tu.employeeID, tu.useMobile, tu.phone, tu.lastPayment, tu.comercio, tu.foto, tu.token_google_calendar,locationID");
		$builder= $builder->join('tb_naturales tn','tu.employeeID = tn.entityID');
		$builder = $builder->where(['tu.companyID'=>$companyID, 'tu.userID'=>$userID]);
		return $builder->get()->getRowObject();
	}

	function get_UserByBussnes($companyID,$bussines){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID,useMobile,phone,lastPayment,comercio,foto,token_google_calendar,locationID");
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