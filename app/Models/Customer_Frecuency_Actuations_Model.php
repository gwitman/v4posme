<?php 
//posme:2024-08-08
namespace App\Models;
use CodeIgniter\Model;

class Customer_Frecuency_Actuations_Model extends model{

    function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_frecuency_actuations");
		
		$result = $builder->insert($data);
		return $db->insertID();		
   }

   function update_app_posme($entityID,$idfrecuency,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_frecuency_actuations")
				->where("entityID",$entityID)
				->where('customerFrecuencyActuations',$idfrecuency);
		return $builder->update($data);
	}

	function delete_app_posme($entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_frecuency_actuations");		
		$data["isActive"] = 0;
		
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
	}

	function deleteWhereIDNotIn($entityID,$arrayId){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_frecuency_actuations")
					->where("entityID",$entityID)
					->whereNotIn('customerFrecuencyActuations',$arrayId);
		$data["isActive"] = 0;
		return $builder->update($data);
	}

	function get_rowByEntityID($entityID)  {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_frecuency_actuations");
		$query      = $builder->select("customerFrecuencyActuations,
                                    entityID,
                                    createdOn,
                                    name,
                                    situationID,
                                    frecuencyContactID,
                                    isActive,
                                    isApply")
						->where("entityID",$entityID)
						->where("isActive", 1)
						->orderBy('customerFrecuencyActuations desc')
						->get();
		//Ejecutar Consulta
		return $query->getResult();
	}

	function get_rowExpiredRegisters($userName = null)
	{
		$db = db_connect();
		$builder = $db->table("tb_customer_frecuency_actuations cf");
		$query = $builder
		->select("cf.name, ci.description ")
		->join("tb_catalog_item ci", "cf.frecuencyContactId = ci.catalogItemID")
		->where("cf.isActive",1)
		->where("cf.isApply",0)
		->where("DATE_ADD(cf.createdOn, INTERVAL ci.sequence DAY) >=" . date('Y-m-d'))
		->orderBy('cf.name','desc')
		->get();

		return $query->getResult();
	}
}