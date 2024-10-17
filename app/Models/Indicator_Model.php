<?php

namespace App\Models;
use CodeIgniter\Model;

class Indicator_Model extends Model
{
	function __construct()
	{
		parent::__construct();
	}

	function insert_app_posme($data)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_indicator");

		$result = $builder->insert($data);
		return $db->insertID();
	}

	function update_app_posme($companyID, $indicatorID, $data)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_indicator");

		$builder->where("companyID", $companyID);
		$builder->where("indicadorID", $indicatorID);
		return $builder->update($data);
	}

	function delete_app_posme($companyID, $indicatorID)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_indicator");
		$data["isActive"] = 0;

		$builder->where("companyID", $companyID);
		$builder->where("indicadorID", $indicatorID);
		return $builder->update($data);
	}

	function getByPK($companyID, $indicatorID)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_indicator");

		$sql = "";
		$sql = sprintf("select indicadorID,companyID,code,name,label,description,ti.order,script,prefix,posfix,isActive");
		$sql = $sql . sprintf(" from tb_indicator ti");
		$sql = $sql . sprintf(" where companyID = $companyID");
		$sql = $sql . sprintf(" and isActive= 1");
		$sql = $sql . sprintf(" and indicadorID = $indicatorID");

		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
}
