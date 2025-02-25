<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Public_Catalog_Detail_Model extends Model  
{
	
	
    protected $table      = 'tb_public_catalog_detail';
    protected $primaryKey = 'publicCatalogDetailID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
		'publicCatalogDetailID',
		'publicCatalogID', 'name','display','flavorID','description','sequence','parentCatalogDetailID','ratio',
		'reference1','reference2','reference3','reference4',
		'reference5','reference6','reference7','reference8',
		'reference9','reference10','reference11','reference12',
		'reference13','reference14','reference15','reference16',
		'reference17','reference18','reference19','reference20',
		'reference21','reference22','reference23','reference24',
		'isActive','parentName'
	];

    // Dates
    //protected $useTimestamps = false;
    //protected $dateFormat    = 'datetime';
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
	
    function getView($publicCatalogID)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_public_catalog_detail");		
		$sql 		= 
		'
				select 
					c.publicCatalogDetailID,
					c.publicCatalogID, 
					c.name,display,
					c.flavorID,
					c.description,
					c.sequence,
					c.parentCatalogDetailID,
					c.ratio,
					c.reference1,
					c.reference2,
					c.reference3,
					c.reference4,
					c.parentName,
					c.isActive
				from 
					tb_public_catalog_detail c 
				where 
					c.publicCatalogID = '.$publicCatalogID.' and 
					c.isActive = 1 
			';
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
    }
	
	function getRowByCatalogIDAndName($publicCatalogID,$name)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_public_catalog_detail");		
		$sql 		= 
		"
				select 
					c.publicCatalogDetailID,
					c.publicCatalogID, 
					c.name,display,
					c.flavorID,
					c.description,
					c.sequence,
					c.parentCatalogDetailID,
					c.ratio,
					c.reference1,
					c.reference2,
					c.reference3,
					c.reference4,
					c.parentName,
					c.isActive
				from 
					tb_public_catalog_detail c 
				where 
					c.publicCatalogID = ".$publicCatalogID." and 
					c.isActive = 1 and 
					c.name = '".$name."'
			";
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}

	function getRowCSV($publicCatalogID)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_public_catalog_detail");		
		$sql 		= 
		'
				select 
					c.publicCatalogDetailID as Id,
					c.name as Indicador,
					c.display as Valores,
					c.reference4 as Consjunto, 
					c.description as Grupo,
					c.reference1 as SubGrupo,
					c.reference3 as Edad,
					c.reference2 as Sexo,
					c.parentName as Parent,
					c.sequence as Sequencia,
					c.ratio as Ratio,
					c.flavorID as Flavor
				from 
					tb_public_catalog_detail c 
				where 
					c.publicCatalogID = '.$publicCatalogID.' and 
					c.isActive = 1 
			';
			
		//Ejecutar Consulta
		return $db->query($sql)->getResultArray();
		
    }

	function get_rowByPk($publicCatalogDetailID)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_public_catalog_detail");		
		$sql 		= 
		'
				select 
					c.publicCatalogDetailID as Id,
					c.name as Indicador,
					c.display as Valores,
					c.reference4 as Conjunto, 
					c.description as Grupo,
					c.reference1 as SubGrupo,
					c.reference3 as Edad,
					c.reference2 as Sexo,
					c.parentName as Parent,
					c.sequence as Sequencia,
					c.ratio as Ratio,
					c.flavorID as Flavor
				from 
					tb_public_catalog_detail c 
				where 
					c.publicCatalogDetailID = '.$publicCatalogDetailID.' and 
					c.isActive = 1 
			';
			
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
    }
	
}
?>