<?php
namespace App\Models;
use CodeIgniter\Model;

class Company_Page_Setting_Large_Model extends Model
{
    protected $table            = "tb_company_page_setting_large";
    protected $primaryKey       = 'customPageLargeID';
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
    public function delete_app_posme($customPageLargeID){
        $this->where('customPageLargeID', $customPageLargeID)->set(['isActive' => 0])->update();
    }

    /**
     * @throws ReflectionException
     */
    public function update_app_posme($customPageLargeID, $data){
        $this->where('customPageLargeID', $customPageLargeID)->set($data)->update();
    }

    public function get_rowByPk($customPageLargeID){
        return $this->where(['customPageLargeID' => $customPageLargeID, 'isActive'=>1])->first();
    }

    public function get_rowByKey($key){
        return $this->where(['keyi' => $key, 'isActive'=>1])->findAll();
    }

    public function get_rowByKeyAndControllerAndEmelement($key, $controller, $element){
        return $this->where([
            'keyi'           => $key,
            'controller'    => $controller,
            'element'       => $element,
            'isActive'      =>1
        ])->first();
    }
}