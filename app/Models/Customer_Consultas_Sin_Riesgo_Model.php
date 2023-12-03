<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Consultas_Sin_Riesgo_Model extends Model  {
   function __construct(){	
      parent::__construct(); 
   }
   function update_app_posme($requestID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");
		
		$builder->where("requestID",$requestID);	
		return $builder->update($data);
		
   }
   function updateByCedula($companyID,$cedula,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo"); 
		
        $builder->where("companyID",$companyID);	
        $builder->where("id",$cedula);	
		
        return $builder->update($data);
        
   }
   
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   //Buscar Por Id
   function get_rowByPK($requestID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");
		
        $sql = "";
		$sql = sprintf("select requestID, companyID, name, id, `file`, userID, createdOn, createdBy, createdIn, createdAt, modifiedOn");
        $sql = $sql.sprintf(" from tb_customer_consultas_sin_riesgo i");		
        $sql = $sql.sprintf(" where i.requestID = $requestID");
        
       //Ejecutar Consulta
		return $db->query($sql)->getRow();
    }
   //Buscar en la lista el Cliente. 
   function get_rowByCedulaLast($companyID,$cedula){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");		
        $sql = "";
		$sql = sprintf("select requestID, companyID, name, id, `file`, userID, createdOn, createdBy, createdIn, createdAt, modifiedOn");
        $sql = $sql.sprintf(" from tb_customer_consultas_sin_riesgo i");		
        $sql = $sql.sprintf(" where i.id = '$cedula'");
        $sql = $sql.sprintf(" and i.companyID = $companyID");
        $sql = $sql.sprintf(" order by createdOn desc");
        $sql = $sql.sprintf(" limit 0,1 ");
        
        //Ejecutar Consulta
		return $db->query($sql)->getRow();
    }
    function get_rowValidOld($requestID,$old){
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");		
        $sql = "";
		$sql = sprintf("select requestID, companyID, name, id, `file`, userID, createdOn, createdBy, createdIn, createdAt, modifiedOn");
        $sql = $sql.sprintf(" from tb_customer_consultas_sin_riesgo i");		
        $sql = $sql.sprintf(" where i.requestID = $requestID");        

        //Si el parametro old es mayor que 0 , filtrar por antiguedad
        if($old > 0)
        $sql = $sql.sprintf(" and DATEDIFF(NOW(), i.createdOn) > ".$old." "); 
        
       //Ejecutar Consulta
		return $db->query($sql)->getRow();
    }
    
    //Buscar en la lista el Cliente. 
   function get_rowByCedula_FileName($companyID,$cedula){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");		
		
        
        $sql = "";
		$sql = sprintf("select distinct i.`file`");
        $sql = $sql.sprintf(" from tb_customer_consultas_sin_riesgo i");		
        $sql = $sql.sprintf(" where i.id = '$cedula' ");
        $sql = $sql.sprintf(" and i.companyID = $companyID");
        $sql = $sql.sprintf(" order by i.`file` desc");
       
	   //Ejecutar Consulta
		return $db->query($sql)->getResult();
    }
	
   //Obtener Data
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_consultas_sin_riesgo");	
		
		$sql = "";
		$sql = sprintf("select `i`.`TIPO_DE_ENTIDAD`,`i`.`NUMERO_CORRELATIVO`,`i`.`FECHA_DE_REPORTE`,`i`.`DEPARTAMENTO`,`i`.`NUMERO_DE_CEDULA_O_RUC`,`i`.`NOMBRE_DE_PERSONA`,`i`.`TIPO_DE_CREDITO`,`i`.`FECHA_DE_DESEMBOLSO`,`i`.`TIPO_DE_OBLIGACION`,`i`.`MONTO_AUTORIZADO`,`i`.`PLAZO`,`i`.`FRECUENCIA_DE_PAGO`,`i`.`SALDO_DEUDA`,`i`.`ESTADO`,`i`.`MONTO_VENCIDO`,`i`.`ANTIGUEDAD_DE_MORA`,`i`.`TIPO_DE_GARANTIA`,`i`.`FORMA_DE_RECUPERACION`,`i`.`NUMERO_DE_CREDITO`,`i`.`VALOR_DE_LA_CUOTA`");		
        $sql = $sql.sprintf(" from vw_sin_riesgo_reporte_creditos_to_systema i");		
        $sql = $sql.sprintf(" where i.companyID = $companyID");             
       
	    //Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
}
?>