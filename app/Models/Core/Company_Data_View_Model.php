<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Company_Data_View_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowBy_companyIDDataViewID($companyID,$dataViewID,$callerID,$componentID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,componentID,callerID,dataViewID,companyDataViewID,name,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,summaryColumns,formatColumns,flavorID");
		$sql = $sql.sprintf(" from tb_company_dataview");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and componentID = $componentID");
		$sql = $sql.sprintf(" and callerID = $callerID");
		$sql = $sql.sprintf(" and dataViewID = $dataViewID");
		$sql = $sql.sprintf(" and isActive = 1");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowBy_companyIDDataViewIDAndFlavor($companyID,$dataViewID,$callerID,$componentID,$flavorID)
   {
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,componentID,callerID,dataViewID,companyDataViewID,name,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,summaryColumns,formatColumns,flavorID");
		$sql = $sql.sprintf(" from tb_company_dataview");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and componentID = $componentID");
		$sql = $sql.sprintf(" and callerID = $callerID");
		$sql = $sql.sprintf(" and dataViewID = $dataViewID");
		$sql = $sql.sprintf(" and flavorID = $flavorID");
		$sql = $sql.sprintf(" and isActive = 1");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }

}
?>