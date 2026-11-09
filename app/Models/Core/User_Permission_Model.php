<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class User_Permission_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function insert_app_posme($data){
		$db 		= db_connect();		
		
		$builder  	= $db->table("tb_user_permission");
		$result		= $builder->insert($data);
		
		return $result; 
   }
   function delete_ByRole($companyID,$branchID,$roleID){
		$db 	= db_connect();	
		$build  = $db->table("tb_user_permission");
		
		$build->where("companyID",$companyID);
		$build->where("branchID",$branchID);
		$build->where("roleID",$roleID);
		
		$result	= $build->delete();		
		return $result; 	
			

   }
   /**
    * Elimina (borrado fisico, igual que delete_ByRole) los permisos de un rol
    * solo para los elementos indicados. Los permisos de otros elementos del rol
    * no se tocan.
    *
    * @param int   $companyID      Identificador de la empresa.
    * @param int   $branchID       Identificador de la sucursal.
    * @param int   $roleID         Identificador del rol.
    * @param array $arrayElementID Lista de elementID a eliminar del rol.
    * @return int Cantidad de filas eliminadas (0 si el array viene vacio).
    */
   function delete_ByRoleAndElements($companyID,$branchID,$roleID,$arrayElementID){
		//Sin elementos no se elimina nada (evita borrar todo el rol)
		if(empty($arrayElementID) || !is_array($arrayElementID))
			return 0;

		$db 	= db_connect();
		$build  = $db->table("tb_user_permission");

		$build->where("companyID",$companyID);
		$build->where("branchID",$branchID);
		$build->where("roleID",$roleID);
		$build->whereIn("elementID",array_values($arrayElementID));

		$build->delete();
		return $db->affectedRows();
   }
   function get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID){
		$db 	= db_connect(); 	
		$sql = "";
		$sql = sprintf("
				select 
					tb_user_permission.companyID,tb_user_permission.branchID,
					tb_user_permission.roleID,tb_user_permission.elementID,
					tb_user_permission.selected,tb_user_permission.inserted,
					tb_user_permission.deleted,tb_user_permission.edited,
					tb_menu_element.orden,tb_menu_element.display,
					tb_menu_element.typeApp  
				from 
					tb_user_permission
					inner join  tb_element on tb_element.elementID = tb_user_permission.elementID 
					inner join  tb_menu_element on tb_menu_element.elementID = tb_user_permission.elementID 
				where 
					tb_user_permission.companyID = $companyID 
					and tb_user_permission.branchID = $branchID 
					and tb_user_permission.roleID = $roleID 	
					and tb_menu_element.companyID = $companyID 
				order by 
					tb_menu_element.typeApp asc ,
					tb_menu_element.orden 
				
				");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$branchID,$roleID,$elementID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID,branchID,roleID,elementID,selected,inserted,deleted,edited");
		$sql = $sql.sprintf(" from tb_user_permission");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");
		$sql = $sql.sprintf(" and roleID = $roleID");
		$sql = $sql.sprintf(" and elementID = $elementID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>