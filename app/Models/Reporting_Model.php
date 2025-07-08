<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class Reporting_Model extends Model
{
    protected $table            = "tb_reporting";
    protected $primaryKey       = 'reportID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';

    public function insert_app_posme($data){
        try {
            return $this->insert($data);
        } catch (ReflectionException $e) {
            return 0;
        }
    }

    /**
     * @throws ReflectionException
     */
    public function delete_app_posme($reportID){
        $this->where('reportID', $reportID)->set(['isActive' => 0])->update();
    }

    /**
     * @throws ReflectionException
     */
    public function update_app_posme($reportID, $data){
        $this->where('reportID', $reportID)->set($data)->update();
    }

    public function get_rowByPK($reportID){
        return $this->where(['reportID' => $reportID, 'isActive'=>1])->first();
    }

    public function get_rowByKey($key){
        return $this->where(['key' => $key, 'isActive'=>1])->first();
    }
}