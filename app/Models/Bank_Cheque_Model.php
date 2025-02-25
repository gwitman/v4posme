<?php
//posme:2025-02-24
namespace App\Models;

use CodeIgniter\Model;

class Bank_Cheque_Model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function delete_app_posme($chequeID)
    {
        $db         = db_connect();
        $builder    = $db->table("tb_bank_cheque");

        $data                 = array();
        $data["isActive"]     = 0;
        $builder->where("chequeID", $chequeID);

        return $builder->update($data);
    }
    function update_app_posme($chequeID, $data)
    {
        $db         = db_connect();
        $builder    = $db->table("tb_bank_cheque");

        $builder->where("chequeID", $chequeID);

        return $builder->update($data);
    }
    function insert_app_posme($data)
    {
        $db         = db_connect();
        $builder    = $db->table("tb_bank_cheque");

        $result     = $builder->insert($data);
        return $db->insertID();
    }

    function get_rowByPK($chequeID)
    {
        $db         = db_connect();
        $builder     = $db->table('tb_bank_cheque');

        $builder->where('chequeID', $chequeID);
        $builder->where('isActive', 1);
        $recordSet = $builder->get()->getRow();
        return $recordSet;
    }

    function get_rowByChequeNumber($chequeNumber)
    {
        $db         = db_connect();
        $builder    = $db->table('tb_bank_cheque');

        $builder->where('chequeNumber', $chequeNumber);
        $builder->where('isActive',1);
        $result     = $builder->get()->getRow();
        return $result;
    }
}
