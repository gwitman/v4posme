<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Cash_Box_Session_Model extends Model  
{
    protected $table      = 'tb_cash_box_session';
    protected $primaryKey = 'cashBoxSessionID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['cashBoxSessionID', 'companyID','branchID','cashBoxID','startOn','endOn','statusID','isActive','userID','transactionMasterIDOpen','transactionMasterIDClosed'];

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
	
	
	public function get_rowByCashBoxOpenBy_UserID($companyID,$userID,$date)
	{
		$db = db_connect();

		$sql = "
			SELECT 
				c.companyID,
				c.branchID,
				c.cashBoxID,
				c.cashBoxSessionID,
				c.startOn,
				c.endOn,
				c.statusID,
				c.isActive,
				c.userID,
				c.transactionMasterIDOpen,
				c.transactionMasterIDClosed
			FROM 
				tb_cash_box_session c
			WHERE 
				c.companyID = ? AND
				c.userID = ? AND
				c.isActive = 1 AND
				c.statusID IN (120 /*ABIERTA*/ ) AND 
				(
					(
						c.endOn != '0000-00-00' 
						AND ? BETWEEN c.startOn AND c.endOn
					)
					OR
					(
						c.endOn = '0000-00-00' 
						AND ? >= c.startOn
					)
				)
		";

		return $db->query($sql, [$companyID, $userID, $date, $date])->getResult();
	}
	public function get_rowByCashBoxOpenBy_CashBoxIDAnd_Date($companyID, $cashBoxID, $date)
	{
		$db = db_connect();

		$sql = "
			SELECT 
				c.companyID,
				c.branchID,
				c.cashBoxID,
				c.cashBoxSessionID,
				c.startOn,
				c.endOn,
				c.statusID,
				c.isActive,
				c.userID,
				c.transactionMasterIDOpen,
				c.transactionMasterIDClosed
			FROM 
				tb_cash_box_session c
			WHERE 
				c.companyID = ? AND
				c.cashBoxID = ? AND
				c.isActive = 1 AND
				c.statusID IN (120 /*ABIERTA*/ ) AND 
				(
					(
						c.endOn != '0000-00-00' 
						AND ? BETWEEN c.startOn AND c.endOn
					)
					OR
					(
						c.endOn = '0000-00-00' 
						AND ? >= c.startOn
					)
				)
		";

		return $db->query($sql, [$companyID, $cashBoxID, $date, $date])->getResult();
	}

	
   
}
?>