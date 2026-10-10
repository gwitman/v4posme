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

	/**
	 * Obtiene los indicadores activos y calcula su valor sin usar el procedimiento
	 * almacenado pr_core_get_indicators.
	 *
	 * Por cada indicador se ejecuta su campo "script" (que debe dejar el resultado
	 * en la variable de sesion @utilityResult) y se lee ese valor.
	 *
	 * @param int    $companyID   Compania del indicador
	 * @param string $companyType Tipo de compania (reservado para variantes futuras)
	 * @param int    $userID      Usuario que solicita (disponible dentro del script como @userID)
	 * @param string $type        Tipo de indicador. Se filtra contra el campo "code"
	 * @param string $rolID       Rol. Se filtra contra el campo "label"
	 *
	 * @return array Lista de objetos { name, systemName, value }
	 */
	function get_indicatorsToMobile($companyID, $companyType, $userID, $type, $rolID)
	{
		$db 		= db_connect();

		//Obtener la definicion de los indicadores a evaluar
		$sql = "";
		$sql = $sql . sprintf("SELECT indicadorID, companyID, code, name, label, ti.order, script, prefix, posfix ");
		$sql = $sql . sprintf("FROM tb_indicator ti ");
		$sql = $sql . sprintf("WHERE companyID = %d ", $companyID);
		$sql = $sql . sprintf("AND isActive = 1 ");
		$sql = $sql . sprintf("AND isGroup <> 1 ");

		//Filtrar por tipo de indicador (campo code)
		if (!empty($type)) {
			$sql = $sql . sprintf("AND code = %s ", $db->escape($type));
		}

		//Filtrar por rol (campo label)
		if (!empty($rolID)) {
			$sql = $sql . sprintf("AND label = %s ", $db->escape($rolID));
		}

		$sql = $sql . sprintf("ORDER BY code, ti.order");

	    
		$objListIndicator = $db->query($sql)->getResult();

		$result = array();

		if (empty($objListIndicator)) {
			return $result;
		}

		//Exponer el usuario al script por si el indicador lo necesita
		$db->query(sprintf("SET @userID = %d", $userID));

		foreach ($objListIndicator as $objIndicator) {

			$value = 0;

			//El script debe dejar el valor en @utilityResult
			$script = trim($objIndicator->script);

			if ($script != "" && $script != "0") {

				//Reiniciar el acumulador antes de evaluar
				$db->query("SET @utilityResult = 0");

				//Ejecutar el script del indicador
				$db->query($script);

				//Leer el resultado calculado
				$objValue = $db->query("SELECT @utilityResult AS value")->getRow();
				$value    = ($objValue && $objValue->value !== null) ? $objValue->value : 0;
			}

			$obj             = new \stdClass();
			$obj->name       = $objIndicator->name;
			$obj->systemName = $objIndicator->code;
			$obj->value      = $value;
			$obj->order      = $objIndicator->order;
			$obj->prefix     = $objIndicator->prefix;
			$obj->posfix     = $objIndicator->posfix;

			$result[] = $obj;
		}

		return $result;
	}

}
