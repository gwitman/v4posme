<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class Reporting_Parameter_Model extends Model
{
    protected $table            = "tb_reporting_parameter";
    protected $primaryKey       = 'reportParameterID';
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
    public function delete_app_posme($reportParameterID){
        $this->where('reportParameterID', $reportParameterID)->set(['isActive' => 0])->update();
    }

    /**
     * @throws ReflectionException
     */
    public function update_app_posme($reportParameterID, $data){
        $this->where('reportParameterID', $reportParameterID)->set($data)->update();
    }

    public function get_rowByPK($reportParameterID){
        return $this->where(['reportParameterID' => $reportParameterID])->first();
    }

    public function get_rowByReportID($reportID): array
    {
        return $this->where(['reportID' => $reportID, 'isActive'=>1])->findAll();
    }
}