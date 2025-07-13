<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class Reporting_Result_Model extends Model
{
    protected $table            = "tb_reporting_result";
    protected $primaryKey       = 'reportResultID';
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
    public function delete_app_posme($reportResultID){
        $this->where('reportResultID', $reportResultID)->set(['isActive' => 0])->update();
    }

    /**
     * @throws ReflectionException
     */
    public function update_app_posme($reportResultID, $data){
        $this->where('reportResultID', $reportResultID)->set($data)->update();
    }

    public function get_rowByReportID($reportID){
        return $this->where(['reportID' => $reportID, 'isActive'=>1])->orderBy('resultNumber', 'asc')->orderBy('sequence','asc')->findAll();
    }
}