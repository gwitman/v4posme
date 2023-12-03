<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Element_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowByComponentIDNotIn($componentID,$elementTypeID){
		$db 		= db_connect();
		
		
		$builder->select("e.elementID,e.name as elementName");
		$builder->from("tb_element e");
		$builder->join("tb_component_element ce", "e.elementID = ce.elementID ");
		$builder->whereNotIn("ce.componentID",$componentID);
		$builder->where("e.elementTypeID",$elementTypeID);
		
		
		//Ejecutar Consulta
		return $builder->get()->getResult();
		
   }
   function get_rowByName($name,$elementTypeID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select e.elementID,e.name,e.elementTypeID,e.columnAutoIncrement");
		$sql = $sql.sprintf(" from tb_element e");
		$sql = $sql.sprintf(" where e.name = '$name' ");
		$sql = $sql.sprintf(" and e.elementTypeID = $elementTypeID");		
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
   function get_rowByTypeAndLayout($elementTypeID,$layoutID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.elementID,e.name as elementName");
		$sql = $sql.sprintf(" from tb_element e");
		$sql = $sql.sprintf(" inner join  tb_component_element ce on e.elementID=ce.elementID");
		$sql = $sql.sprintf(" inner join  tb_menu_element me on e.elementID=me.elementID");
		$sql = $sql.sprintf(" where me.typeMenuElementID = $layoutID");
		$sql = $sql.sprintf(" and e.elementTypeID = $elementTypeID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($componentID,$elementTypeID){
		$db 	= db_connect(); 
		$sql = "";
		$sql = sprintf("select e.elementID,e.name as elementName");
		$sql = $sql.sprintf(" from tb_element e");
		$sql = $sql.sprintf(" inner join  tb_component_element ce on e.elementID=ce.elementID");
		$sql = $sql.sprintf(" where ce.componentID = $componentID");
		$sql = $sql.sprintf(" and e.elementTypeID = $elementTypeID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
}
?>