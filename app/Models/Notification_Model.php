<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Notification_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   
   function update_app_posme_by_sumary($summary,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");
		
		$builder->where("summary",$summary);
		return $builder->update($data);
		
   }
   function update_app_posme($notificationID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");
		
		$builder->where("notificationID",$notificationID);
		return $builder->update($data);
		
   }
   function delete_app_posme($notificationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_notification");		
  		$data["isActive"] = 0;
		
		$builder->where("notificationID",$notificationID);
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");
		$result		= $builder->insert($data);
		return $db->insertID();		
   }
   function get_rowByPK($notificationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_notification");    
		$sql = "";
		$sql = sprintf("select 
				notificationID,errorID,`from`,`to`,`subject`,message,summary,
				title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,
				programDate,programHour,sendEmailOn,sendWhatsappOn,
				addedCalendarGoogle,quantityOcupation,quantityDisponible,
				googleCalendarEventID,isRead,entityIDSource,entityIDTarget");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.notificationID = $notificationID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rows($top){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");    
   
		$sql = "";
		$sql = sprintf("select 
							notificationID,errorID,`from`,`to`,`subject`,message,summary,title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,programDate,programHour,sendEmailOn,sendWhatsappOn,addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
							isRead,entityIDSource,entityIDTarget ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.sendOn is null");
		$sql = $sql.sprintf(" limit 0,$top ");
		
		echo $sql;
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
    function get_rowsEmail($top){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");    
   
		$sql = "";
		$sql = sprintf("select 
								notificationID,errorID,`from`,`to`,`subject`,message,summary,title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,programDate,programHour,sendEmailOn,sendWhatsappOn,addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
								isRead,entityIDSource,entityIDTarget ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.sendEmailOn is null");
		$sql = $sql.sprintf(" limit 0,$top ");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   

	
	
    function get_rowsWhatsappPrimerEmployeerOcupado($datetime_cliente,$business)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");    
   
		$sql = "";
		$sql = sprintf("select notificationID,errorID,`from`,`to`,`subject`,message,summary,title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,programDate,programHour,sendEmailOn,sendWhatsappOn,addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
							isRead,entityIDSource,entityIDTarget ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive = 1 ");
		$sql = $sql.sprintf(" and   n.summary = '$business' ");
		$sql = $sql.sprintf(" and 
							  (
									(
										ADDTIME(CAST(CONCAT(n.programDate,' ',n.programHour,':00') AS DATETIME),'+00:30:00') >= 
										CAST('".$datetime_cliente."' AS DATETIME) and 
										CAST(n.programDate AS DATETIME) = CAST('".$datetime_cliente."' AS DATE) 
									)
									
									or 
									
									(
										ADDTIME(CAST('".$datetime_cliente."' AS DATETIME),'-00:30:00') <= 
										CAST(CONCAT(n.programDate,' ',n.programHour,':00') AS DATETIME)  and 			
										CAST(n.programDate AS DATETIME) = CAST('".$datetime_cliente."' AS DATE)
									)
									
							  )
							");
		
			
			
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	}
	
	function get_rowsToAddedGoogleCalendar($tagID,$business)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");    
   
		$sql = "";
		$sql = sprintf("select notificationID,errorID,`from`,`to`,`subject`,message,summary,title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,programDate,programHour,sendEmailOn,sendWhatsappOn,addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
							isRead,entityIDSource,entityIDTarget ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.addedCalendarGoogle = 0 and n.tagID = ".$tagID);
		$sql = $sql.sprintf(" and n.summary = '".$business."'");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	
	function get_rowsWhatsappPosMeSendMessage($top){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");    
   
		$sql = "";
		$sql = sprintf("select n.notificationID,n.errorID,n.`from`,n.`to`,n.`subject`,n.message,n.summary,n.title,n.tagID,n.createdOn,n.sendOn,n.isActive,n.phoneFrom,n.phoneTo,n.programDate,n.programHour,n.sendEmailOn,n.sendWhatsappOn,n.addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
								n.isRead,n.entityIDSource,n.entityIDTarget ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" inner join tb_tag t on n.tagID = t.tagID  ");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and t.sendSMS = 1");
		$sql = $sql.sprintf(" and n.sendWhatsappOn is null ");
		$sql = $sql.sprintf(" and 
									CAST(CONCAT(n.programDate,' ','00:00',':00') AS DATETIME) >= 
									ADDTIME(ADDTIME(CURRENT_DATE(),'".APP_HOUR_DIFERENCE_MYSQL."') , '-00:00:00')   
									
							  and 
									CAST(CONCAT(n.programDate,' ','00:00',':00') AS DATETIME) <= 
									ADDTIME(ADDTIME(CURRENT_DATE(),'".APP_HOUR_DIFERENCE_MYSQL."') , '+23:59:59')   
							");
		
			
			
		$sql = $sql.sprintf(" limit 0,$top ");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
    function get_rowsWhatsappPosMeCalendar($top){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");    
   
		$sql = "";
		$sql = sprintf("select 	notificationID,errorID,`from`,`to`,`subject`,message,summary,title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,programDate,programHour,sendEmailOn,sendWhatsappOn,addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
								isRead,entityIDSource,entityIDTarget ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.sendWhatsappOn is null");
		$sql = $sql.sprintf(" and 
									CAST(CONCAT(n.programDate,' ',n.programHour,':00') AS DATETIME) > 
									ADDTIME(ADDTIME(now(),'".APP_HOUR_DIFERENCE_MYSQL."') , '-00:30:00')   
									
							  and 
									CAST(CONCAT(n.programDate,' ',n.programHour,':00') AS DATETIME) <= 
									ADDTIME(ADDTIME(now(),'".APP_HOUR_DIFERENCE_MYSQL."') , '+00:30:00')   
							");
		
			
			
		$sql = $sql.sprintf(" limit 0,$top ");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowsByToMessage($to,$message){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select 
								notificationID,errorID,`from`,`to`,`subject`,message,summary,title,tagID,createdOn,sendOn,isActive,phoneFrom,phoneTo,programDate,programHour,sendEmailOn,sendWhatsappOn,addedCalendarGoogle,quantityOcupation,quantityDisponible,googleCalendarEventID,
								isRead,entityIDSource,entityIDTarget  ");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.to = '$to' ");
		$sql = $sql.sprintf(" and n.message = ".$db->escape($message)." ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();	
   }
   
   function get_rowByEntityIDCustomer($entityIDCustomer)
   {
	   $db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("
		 select 
			c.notificationID,
			c.errorID,
			c.`from`,
			c.`to`,
			c.`subject`,
			c.message,
			c.summary,
			c.title,
			c.tagID,
			c.phoneFrom,
			c.phoneTo,
			c.programDate,
			c.programHour,
			c.sendOn,
			c.sendEmailOn,
			c.sendWhatsappOn,
			c.addedCalendarGoogle,
			c.quantityOcupation,
			c.quantityDisponible,
			c.googleCalendarEventID,
			c.isRead,
			c.entityIDSource,
			c.entityIDTarget,
			c.createdOn,
			DATE_FORMAT(c.createdOn, '%%Y-%%m-%%d %%h:%%i:%%s %%p') as createdOnFormato12H,
			ns.firstName as firstNameSource,
			nt.firstName as firstNameTartet,
			
			ifnull(emp.entityID,'0') as targetIDIsEmployeer
		from 
			tb_notification c 
			left join tb_naturales ns on 
				ns.entityID = c.`entityIDSource` 
			left join tb_naturales nt on 
				nt.entityID = c.`entityIDTarget`  
				
			left join tb_employee emp on 
				emp.entityID = c.entityIDTarget
		where 
			c.`entityIDSource` = ".$entityIDCustomer." or 
			c.`entityIDTarget` = ".$entityIDCustomer." 
		order by 
			c.notificationID desc 
		");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }
   function get_rowByCustomer($phoneNumber)
   {
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("
		 select 
			c.notificationID,
			c.errorID,
			c.`from`,
			c.`to`,
			c.`subject`,
			c.message,
			c.summary,
			c.title,
			c.tagID,
			c.phoneFrom,
			c.phoneTo,
			c.programDate,
			c.programHour,
			c.sendOn,
			c.sendEmailOn,
			c.sendWhatsappOn,
			c.addedCalendarGoogle,
			c.quantityOcupation,
			c.quantityDisponible,
			c.googleCalendarEventID,
			c.isRead,
			c.entityIDSource,
			c.entityIDTarget 
		from 
			tb_notification c 
		where 
			c.`from` = '".$phoneNumber."' or 
			c.`to` = '".$phoneNumber."'
		order by 
			c.notificationID asc 
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }
   
}
?>