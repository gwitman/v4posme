<?php 
//posme:2024-08-08
namespace App\Models;
use CodeIgniter\Model;

class Customer_Payment_Method_Model extends Model  {

    function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_payment_method");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }

   function update_app_posme($entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_payment_method");
		
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
	}
	function delete_app_posme($entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_payment_method");		
		$data["isActive"] = 0;
		
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
	} 

	function get_rowByEntityID($entityID)  {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_payment_method");
		$query      = $builder->select("customerPaymentMethod, entityID, statusID, isActive, name, number, email, expirationDate, cvc, typeId")
						->where("entityID",$entityID)
						->where("isActive", true)
						->get();
		//Ejecutar Consulta
		return $query->getRow();
	}
}