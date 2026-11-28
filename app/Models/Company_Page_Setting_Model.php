<?php
namespace App\Models;
use CodeIgniter\Model;

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
        return $this->where(['keyi' => $key, 'isActive'=>1])->findAll();
    }
	
	public function get_rowByKeyAndController($key, $controller)
	{
		return $this->where(['keyi' => $key, 'isActive'=>1,'controller'=> $controller ])->findAll();
	}

    public function get_rowByKeyAndControllerAndEmelement($key, $controller, $element){
        return $this->where([
            'keyi'           => $key,
            'controller'    => $controller,
            'element'       => $element,
            'isActive'      =>1
        ])->first();
    }

    /**
     * Lista los registros activos de un tipo de compania (keyi) para el editor de core_user/edit.
     * @param string $key Tipo de compania (tb_company.type) o 'demo'.
     * @return array Filas {id, namei, keyi, controller, method, type, flavorID, element, valuei}.
     */
    public function get_rowsEditorByKey($key)
    {
        $db     = db_connect();
        $sql    = "";
        $sql    = $sql.sprintf("select ");
        $sql    = $sql.sprintf("  s.customPageID as id, ");
        $sql    = $sql.sprintf("  s.namei, ");
        $sql    = $sql.sprintf("  s.keyi, ");
        $sql    = $sql.sprintf("  s.controller, ");
        $sql    = $sql.sprintf("  s.method, ");
        $sql    = $sql.sprintf("  s.type, ");
        $sql    = $sql.sprintf("  s.flavorID, ");
        $sql    = $sql.sprintf("  s.element, ");
        $sql    = $sql.sprintf("  s.valuei ");
        $sql    = $sql.sprintf("from tb_company_page_setting s ");
        $sql    = $sql.sprintf("where s.keyi = %s ", $db->escape((string)$key));
        $sql    = $sql.sprintf("and s.isActive = 1 ");
        $sql    = $sql.sprintf("order by s.controller, s.method, s.element ");

        //Ejecutar Consulta
        return $db->query($sql)->getResult();
    }

    /**
     * Registros 'demo' cuyo controller + method + element no existen (activos) para el tipo de compania.
     * @param string $key Tipo de compania (tb_company.type).
     * @return array Filas {id, namei, keyi, controller, method, type, flavorID, element, valuei}.
     */
    public function get_rowsEditorMissingFromDemo($key)
    {
        $db     = db_connect();
        $sql    = "";
        $sql    = $sql.sprintf("select ");
        $sql    = $sql.sprintf("  d.customPageID as id, ");
        $sql    = $sql.sprintf("  d.namei, ");
        $sql    = $sql.sprintf("  d.keyi, ");
        $sql    = $sql.sprintf("  d.controller, ");
        $sql    = $sql.sprintf("  d.method, ");
        $sql    = $sql.sprintf("  d.type, ");
        $sql    = $sql.sprintf("  d.flavorID, ");
        $sql    = $sql.sprintf("  d.element, ");
        $sql    = $sql.sprintf("  d.valuei ");
        $sql    = $sql.sprintf("from tb_company_page_setting d ");
        $sql    = $sql.sprintf("where d.keyi = 'demo' ");
        $sql    = $sql.sprintf("and d.isActive = 1 ");
        $sql    = $sql.sprintf("and not exists ( ");
        $sql    = $sql.sprintf("  select 1 from tb_company_page_setting c ");
        $sql    = $sql.sprintf("  where c.keyi = %s ", $db->escape((string)$key));
        $sql    = $sql.sprintf("  and c.isActive = 1 ");
        $sql    = $sql.sprintf("  and c.controller <=> d.controller ");
        $sql    = $sql.sprintf("  and c.method <=> d.method ");
        $sql    = $sql.sprintf("  and c.element <=> d.element ");
        $sql    = $sql.sprintf(") ");
        $sql    = $sql.sprintf("order by d.controller, d.method, d.element ");

        //Ejecutar Consulta
        return $db->query($sql)->getResult();
    }

    /**
     * Busca el registro activo que identifica controller + method + element para un tipo de compania.
     * @param string      $key        Tipo de compania (keyi).
     * @param string|null $controller Controlador.
     * @param string|null $method     Metodo.
     * @param string|null $element    Elemento.
     * @return object|null Fila o null.
     */
    public function get_rowByKeyControllerMethodElement($key, $controller, $method, $element)
    {
        return $this->where([
            'keyi'          => $key,
            'controller'    => $controller,
            'method'        => $method,
            'element'       => $element,
            'isActive'      => 1
        ])->first();
    }
    /**
     * Inserta un registro desde el editor de core_user/edit (Query Builder: el modelo no define allowedFields).
     * @param array $data namei, keyi, controller, method, type, flavorID, element, valuei, isActive.
     * @return int ID insertado.
     */
    public function insertEditor_app_posme($data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_company_page_setting");
        $builder->insert($data);
        return $db->insertID();
    }

    /**
     * Actualiza solo valuei de un registro activo de un tipo de compania.
     * @param int    $id     customPageID.
     * @param string $key    Tipo de compania (keyi); evita modificar registros de otro tipo.
     * @param string $valuei Valor nuevo.
     * @return bool Resultado del update.
     */
    public function updateValueiEditor_app_posme($id, $key, $valuei)
    {
        $db      = db_connect();
        $builder = $db->table("tb_company_page_setting");
        $builder->where("customPageID", $id);
        $builder->where("keyi", $key);
        $builder->where("isActive", 1);
        return $builder->update(array("valuei" => $valuei));
    }
}