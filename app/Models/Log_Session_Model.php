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
    protected $allowedFields = ['session_id','userID','companyID','ip_address','user_agent','last_activity','last_update','user_data','authorizationStatus','authorizationTokenHash','authorizationExpiresOn','authorizationOn','authorizationBy','authorizationIP'];

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
	
	public function update_app_posme($sessionID,$data)
	{
		$db      = db_connect();
        $builder = $db->table("tb_log_session");

        $builder->where("session_id", $sessionID);
        return $builder->update($data);
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
				c.user_data,
				c.companyID,
				c.authorizationStatus,
				c.authorizationTokenHash,
				c.authorizationExpiresOn,
				c.authorizationOn,
				c.authorizationBy,
				c.authorizationIP
			from
				tb_log_session c
			where
				c.session_id = ?

		";

		return $db->query($sql, [(string)$sessionID])->getResult();
	}

	/**
	 * Obtiene la sesion asociada a un token de autorizacion de login (hash SHA-256 del token).
	 * @param string $tokenHash hash del token enviado por correo
	 * @return object|null fila de tb_log_session o null si no existe
	 */
	public function get_rowByAuthorizationTokenHash($tokenHash)
	{
		$db = db_connect();

		$sql = "
			select
				c.session_id,
				c.userID,
				c.companyID,
				c.ip_address,
				c.user_agent,
				c.last_activity,
				c.last_update,
				c.user_data,
				c.authorizationStatus,
				c.authorizationTokenHash,
				c.authorizationExpiresOn,
				c.authorizationOn,
				c.authorizationBy,
				c.authorizationIP
			from
				tb_log_session c
			where
				c.authorizationTokenHash = ?
			limit 1
		";

		return $db->query($sql, [(string)$tokenHash])->getRow();
	}

	/**
	 * Resuelve (autoriza o rechaza) una solicitud de autorizacion de login de forma atomica:
	 * solo actualiza si la solicitud sigue 'pending' y no ha vencido, evitando que un
	 * mismo enlace se use dos veces.
	 * @param string $tokenHash hash del token de autorizacion
	 * @param string $status    'authorized' | 'rejected'
	 * @param int    $userIDBy  usuario que resuelve la solicitud
	 * @param string $ip        IP desde donde se resuelve
	 * @param string $now       fecha/hora actual (helper_getDateTime()) del llamador
	 * @param string $userData  nuevo valor de user_data (bitacora)
	 * @return int filas afectadas (1 = aplicado, 0 = vencido, ya usado o inexistente)
	 */
	public function update_app_posme_authorization($tokenHash, $status, $userIDBy, $ip, $now, $userData)
	{
		$db = db_connect();

		$sql = "
			update tb_log_session
			set
				authorizationStatus = ?,
				authorizationOn     = ?,
				authorizationBy     = ?,
				authorizationIP     = ?,
				user_data           = ?
			where
				authorizationTokenHash = ? and
				authorizationStatus    = 'pending' and
				authorizationExpiresOn >= ?
		";

		$db->query($sql, [
			(string)$status,
			(string)$now,
			(int)$userIDBy,
			(string)$ip,
			(string)$userData,
			(string)$tokenHash,
			(string)$now
		]);

		return $db->affectedRows();
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

	/**
	 * Obtiene los ultimos registros de sesion de un usuario (por defecto 5),
	 * ordenados por el ultimo movimiento mas reciente.
	 */
	public function get_lastRowsByUserID($userID,$limit = 5)
	{
		$db 	= db_connect();
		$userID	= (int)$userID;
		$limit	= (int)$limit;
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
			order by 
				c.last_activity desc, c.last_update desc 
			limit ".$limit."
		";

		return $db->query($sql, [$userID])->getResult();
	}

	/**
	 * Obtiene los ultimos registros de sesion de un usuario filtrando por el
	 * campo user_data (ej: "nickname ... create session"), por defecto 5,
	 * ordenados por el ultimo movimiento mas reciente.
	 */
	public function get_lastRowsByUserData($nickname,$limit = 5)
	{
		$db 	= db_connect();
		$limit	= (int)$limit;
		$pattern = $nickname." %create session";
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
				c.user_data like ? 
			order by 
				c.last_activity desc, c.last_update desc 
			limit ".$limit."
		";

		return $db->query($sql, [$pattern])->getResult();
	}
	
   
}
?>