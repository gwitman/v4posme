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

    protected $allowedFields = ['publicCatalogDetailID','publicCatalogID', 'name','display','flavorID','description','sequence','parentCatalogDetailID','ratio','reference1','reference2','reference3','reference4','isActive','parentName'];

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
	
}
?>