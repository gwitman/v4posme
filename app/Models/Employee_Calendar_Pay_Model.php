<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Employee_Calendar_Pay_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($calendarID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay");
		
		$builder->where("calendarID",$calendarID);
		return $builder->update($data);
		
   }
   function delete_app_posme($calendarID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay");		  		
		$data["isActive"]	= 0;
		
		$builder->where("calendarID",$calendarID);
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 			= db_connect();
		$builder		= $db->table("tb_employee_calendar_pay");
		
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 		
		return $autoIncrement;
		
   }
   function get_rowByPK($calendarID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay");    
		
		$sql = "";
		$sql = sprintf("select i.calendarID,i.companyID,i.accountingCycleID,i.name,i.number,i.typeID,i.currencyID,i.statusID,i.description,i.createdBy,i.createdAt,i.createdOn,i.createdIn,i.isActive");
		$sql = $sql.sprintf(" from tb_employee_calendar_pay i");		
		$sql = $sql.sprintf(" where i.calendarID = $calendarID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
}
?>