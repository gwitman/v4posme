<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Employee_Calendar_Pay_Detail_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($calendarDetailID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");
		
		$builder->where("calendarDetailID",$calendarDetailID);
		return $builder->update($data);
		
   }
   function deleteWhereIDNotIn($calendarID,$arrayID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");
	    	$data["isActive"] = 0;
		
		$builder->where("calendarID",$calendarID);
		$builder->whereNotIn("calendarDetailID",$arrayID);		
		return $builder->update($data);
		
   }
   	function deleteWhereCalendarID($calendarID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");
		$data["isActive"] = 0;
		
		$builder->where("calendarID",$calendarID);
		return $builder->update($data);
	}
   function delete_app_posme($calendarDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");		  		
		$data["isActive"]	= 0;
		
		$builder->where("calendarDetailID",$calendarDetailID);
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 		
		return $autoIncrement;
		
   }
   function get_rowByPK($calendarDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");    
		
		$sql = "";
		$sql = sprintf("select 	
			i.calendarDetailID,
			i.calendarID,
			i.employeeID,
			i.plus_salary,
			i.plus_commission,
			i.plus_bonus,
			i.minus_adelantos,
			i.minus_deduction_for_loans,
			i.minus_deduction_for_late_arrival,
			i.minus_inss,
			i.inss_patronal,
			i.minus_ir,
			i.saving,
			i.equal_neto,
			i.isActive,
			n.firstName,
			n.lastName ,
			e.employeNumber ,
			e.hourCost,
			e.comissionPorcentage 
		");
		$sql = $sql.sprintf(" from tb_employee_calendar_pay_detail i");		
		$sql = $sql.sprintf(" inner join  tb_employee e on i.employeeID = e.entityID");
		$sql = $sql.sprintf(" inner join  tb_naturales n on i.employeeID = n.entityID");
		$sql = $sql.sprintf(" where i.calendarDetailID = $calendarDetailID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByCalendarID($calendarID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee_calendar_pay_detail");    
		
		$sql = "";
		$sql = sprintf("select 	
			i.calendarDetailID,
			i.calendarID,
			i.employeeID,
			i.plus_salary,
			i.plus_commission,
			i.plus_bonus,
			i.minus_adelantos,
			i.minus_deduction_for_loans,
			i.minus_deduction_for_late_arrival,
			i.minus_inss,
			i.inss_patronal,
			i.minus_ir,
			i.saving,
			i.equal_neto,
			i.isActive,
			n.firstName,
			n.lastName ,
			e.employeNumber ,
			e.hourCost,
			e.comissionPorcentage 
		");
		$sql = $sql.sprintf(" from tb_employee_calendar_pay_detail i");		
		$sql = $sql.sprintf(" inner join  tb_employee e on i.employeeID = e.entityID");
		$sql = $sql.sprintf(" inner join  tb_naturales n on i.employeeID = n.entityID");
		$sql = $sql.sprintf(" where i.calendarID = $calendarID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>