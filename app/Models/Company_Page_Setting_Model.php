<?php
use CodeIgniter\Model;
use ReflectionException;

class Company_Page_Setting_Model extends Model
{
    protected $table            = "tb_company_page_setting";
    protected $primaryKey       = 'customPageID';
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
    public function delete_app_posme($customPageID){
        $this->where('customPageID', $customPageID)->set(['isActive' => 0])->update();
    }

    /**
     * @throws ReflectionException
     */
    public function update_app_posme($customPageID, $data){
        $this->where('customPageID', $customPageID)->set($data)->update();
    }

    public function get_rowByPk($customPageID){
        return $this->where(['customPageID' => $customPageID, 'isActive'=>1])->first();
    }

    public function get_rowByKey($key){
        return $this->where(['key' => $key, 'isActive'=>1])->findAll();
    }

    public function get_rowByKeyAndControllerAndEmelement($key, $controller, $element){
        return $this->where([
            'key'           => $key,
            'controller'    => $controller,
            'element'       => $element,
            'isActive'      =>1
        ])->first();
    }
}