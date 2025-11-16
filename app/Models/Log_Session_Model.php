<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Log_Session_Model extends Model  
{
    protected $table      = 'tb_log_session';
    protected $primaryKey = 'session_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['session_id','userID', 'ip_address','user_agent','last_activity','last_update','user_data'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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
	
	
	public function delete_app_posme(string $field, $value)
    {
        return $this->where($field, $value)->delete();
    }
	public function get_rowBySessionID($sessionID)
	{
		$db = db_connect();

		$sql = "
			select 
				c.session_id,
				c.userID,
				c.ip_address,
				c.user_agent,
				c.last_activity,
				c.last_update,
				c.user_data
			from 
				tb_log_session c 
			where 
				c.session_id = '$sessionID'

		";

		return $db->query($sql)->getResult();
	}
	public function get_rowByUserID($userID)
	{
		$db = db_connect();

		$sql = "
			select 
				c.session_id,
				c.userID,
				c.ip_address,
				c.user_agent,
				c.last_activity,
				c.last_update,
				c.user_data
			from 
				tb_log_session c 
			where 
				c.userID = ? 

		";

		return $db->query($sql, [$userID])->getResult();
	}
	
   
}
?>