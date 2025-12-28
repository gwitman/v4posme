<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Relationship_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 

   function delete_app_posme($relationshipID){
		$db 	   = db_connect();
		$builder	= $db->table("tb_relationship");	
		
		$builder->where("relationshipID",$relationshipID);			
		$builder->delete();
		
   }

   function insert_app_posme($data){
		$db 			= db_connect();
		$builder		= $db->table("tb_relationship");	
		
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 		
		
    }
	
	function insert_OrMoveCustomerAfter($employeeID, $customerID, $customerIDAfter, $data)
    {
        $db         = db_connect();
        $builder    = $db->table('tb_relationship');

        $findMaxOrder 	= $builder->selectMax('orderNo')
						->where(['employeeID' => $employeeID, 'isActive' => '1'])
						->get()->getRow();
        $maxOrder 		= $findMaxOrder->orderNo ?? 0;

        // Buscar si ya existe
        $existing 			= $builder->where([
            'employeeID' 	=> $employeeID,
            'customerID' 	=> $customerID,
            'isActive' 		=> '1'
        ])->get()->getRow();

        // Obtener la posición del cliente después del cual se insertará
        $after = $builder->select('orderNo')
            ->where([
                'employeeID' 	=> $employeeID,
                'customerID' 	=> $customerIDAfter,
                'isActive' 		=> '1'
            ])->get()->getRow();

        $afterOrder = $after->orderNo ?? null;

        if (!is_null($existing)) {
            $oldOrder = $existing->orderNo;
            if(is_null($oldOrder)) {
                // Nuevo cliente
                if (!is_null($afterOrder) && $afterOrder > 0) {
                    $newOrder = $afterOrder + 1;

                    $db->query("
                        UPDATE tb_relationship
                        SET orderNo = orderNo + 1
                        WHERE employeeID = ? AND orderNo >= ? AND isActive = 1
                    ", [$employeeID, $newOrder]);
                } else {
                    $newOrder = $maxOrder + 1;
                }

                $data["orderNo"] = $newOrder;
                $this->update_app_posme($existing->relationshipID,$data);
                return;
            }
            // Si no se encuentra el cliente "después de" => agregar al final
            if (is_null($afterOrder)) {
                $newOrder = $maxOrder;

                if ($oldOrder == $newOrder) {
                    return; // ya está al final
                }

                // Liberar posición
                $this->update_app_posme($existing->relationshipID, ['orderNo' => null]);

                // Mover hacia arriba todos los que están después del anterior
                $builder->set('orderNo', 'orderNo - 1', false)
                    ->where('employeeID', $employeeID)
                    ->where('isActive', 1)
                    ->where('orderNo >', $oldOrder)
                    ->update();

                $data['orderNo'] = $newOrder;
                $this->update_app_posme($existing->relationshipID, $data);
                return;
            }

            // Si ya está justo después de ese cliente, no mover
            if ($oldOrder == $afterOrder + 1) {
                return;
            }

            // Liberar temporalmente
            $this->update_app_posme($existing->relationshipID, ['orderNo' => null]);

            if ($oldOrder < $afterOrder) {
                // Mover hacia abajo
                $builder->set('orderNo', 'orderNo - 1', false)
                    ->where('employeeID', $employeeID)
                    ->where('isActive', 1)
                    ->where('orderNo >', $oldOrder)
                    ->where('orderNo <=', $afterOrder)
                    ->update();

                $newOrder = $afterOrder;
            } else {
                // Mover hacia arriba
                $newOrder = $afterOrder + 1;

                $builder->set('orderNo', 'orderNo + 1', false)
                    ->where('employeeID', $employeeID)
                    ->where('isActive', 1)
                    ->where('orderNo >=', $newOrder)
                    ->where('orderNo <', $oldOrder)
                    ->update();
            }

            $data['orderNo'] = $newOrder;
            $this->update_app_posme($existing->relationshipID, $data);
        } else {
            // Nuevo cliente
            if (!is_null($afterOrder) && $afterOrder > 0) {
                $newOrder = $afterOrder + 1;

                $db->query("
                UPDATE tb_relationship
                SET orderNo = orderNo + 1
                WHERE employeeID = ? AND orderNo >= ? AND isActive = 1
            ", [$employeeID, $newOrder]);
            } else {
                $newOrder = $maxOrder + 1;
            }

            $data["orderNo"] = $newOrder;
            $this->insert_app_posme($data);
        }
    }



    function insert_OrMoveCustomerToOrder($employeeID, $customerID, $newOrder, $data)
    {
        $db = db_connect();
        $builder = $db->table('tb_relationship');

        // 1. Buscar si ya existe
        $existing = $builder->where([
            'employeeID' => $employeeID,
            'isActive' => '1',
            'customerID' => $customerID
        ])->get()->getRow();

        $occupied = $builder
            ->where(['employeeID' => $employeeID, 'orderNo' => $newOrder, 'isActive' => '1'])
            ->countAllResults();

        if ($existing) {
            $oldOrder = $existing->orderNo;

            if ($oldOrder == $newOrder) {
                // No hay cambio de posición
                return;
            }
            if ($occupied > 0) {
                if (is_null($oldOrder) || $oldOrder <= 0) {
                    $db->query("
                    UPDATE tb_relationship
                    SET orderNo = orderNo + 1
                    WHERE employeeID = ? AND orderNo >= ? AND isActive = 1
                ", [$employeeID, $newOrder]);
                }else{
                    // TEMPORAL: deja vacío su posición para evitar conflicto
                    $this->update_app_posme($existing->relationshipID,['orderNo' => null]);
                    $this::fn_DesplazarOrden($oldOrder, $newOrder,$db, $employeeID);
                }
            }

            // Asignar nueva posición
            $data["orderNo"] = $newOrder;
            $this->update_app_posme($existing->relationshipID, $data);

        } else {
            // Insertar nuevo cliente
            if($newOrder <= 0){
                $findMaxOrder   = $builder->selectMax('orderNo')
                    ->where(['employeeID'=>$employeeID,'isActive'=>'1'])
                    ->get()->getRow();
                $maxOrder       = $findMaxOrder->orderNo ?? 0;
                $newOrder = $maxOrder + 1;
            }

            // Mover los demás hacia abajo
            if ($occupied > 0) {
                // Solo si está ocupada, mover hacia abajo
                $db->query("
                UPDATE tb_relationship
                SET orderNo = orderNo + 1
                WHERE employeeID = ? AND orderNo >= ? AND isActive = 1
            ", [$employeeID, $newOrder]);
            }
            $data['orderNo'] = $newOrder;
            $this->insert_app_posme($data);
        }
    }
	

    function update_app_posme($relationshipID, $data)
    {
        $db         = db_connect();
        $builder    = $db->table("tb_relationship");

        $builder->where("relationshipID", $relationshipID);
        return $builder->update($data);
    }

	function get_rowByID($relationshipID)
    {
        $db          = db_connect();
        $builder     = $db->table('tb_relationship');

        $builder->where('relationshipID', $relationshipID);
        $builder->where('isActive', 1);
        $recordSet = $builder->get()->getRow();
        return $recordSet;
    }

    function get_rowByPK($employeeID, $customerID){
		$db 	= db_connect();
				
		$sql = "";
		$sql = sprintf("select 
			r.relationshipID, r.employeeID, r.customerID, r.orderNo, r.reference1,r.reference2, r.startOn, r.endOn,  r.isActive, 
			concat(e.employeNumber,' / ',concat(n.firstName,' ',n.lastName)) as firstName, 
			concat(c.customerNumber,' / ' ,l.legalName) as legalName,
			r.customerIDAfter
		");
		$sql = $sql.sprintf(" from 
									tb_relationship as r 
									inner join tb_naturales as n on r.employeeID = n.entityID 
									INNER join tb_legal as l on r.customerID = l.entityID 
									INNER join tb_employee e on e.entityID = n.entityID 
									INNER join tb_customer c on c.entityID = l.entityID");
		$sql = $sql.sprintf(" where r.employeeID = $employeeID");		
		$sql = $sql.sprintf(" and r.isActive= 1");		
		$sql = $sql.sprintf(" and r.customerID = $customerID");	
		
		//Ejecutar Consulta  
		return $db->query($sql)->getRow();
    }
	
	function get_rowByPKAndReference1($employeeID, $customerID,$reference1){
		$db 	= db_connect();
				
		$sql = "";
		$sql = sprintf("select 
			r.relationshipID, r.employeeID, r.customerID, r.orderNo, r.reference1,r.reference2, r.startOn, r.endOn,  r.isActive, 
			concat(e.employeNumber,' / ',concat(n.firstName,' ',n.lastName)) as firstName, 
			concat(c.customerNumber,' / ' ,l.legalName) as legalName,
			r.customerIDAfter
		");
		$sql = $sql.sprintf(" from 
									tb_relationship as r 
									inner join tb_naturales as n on r.employeeID = n.entityID 
									INNER join tb_legal as l on r.customerID = l.entityID 
									INNER join tb_employee e on e.entityID = n.entityID 
									INNER join tb_customer c on c.entityID = l.entityID");
		$sql = $sql.sprintf(" where r.employeeID = $employeeID");		
		$sql = $sql.sprintf(" and r.isActive= 1");		
		$sql = $sql.sprintf(" and r.customerID = $customerID");	
		$sql = $sql.sprintf(" and r.reference1 = '$reference1'");	
		
		//Ejecutar Consulta  
		return $db->query($sql)->getRow();
    }

    function get_rowByPKID($relationshipID){
		$db 	= db_connect();
				
		$sql = "";
		$sql = sprintf("select 
						r.relationshipID, r.employeeID, r.customerID, r.orderNo, 
						r.reference1,r.reference2, r.startOn, r.endOn,  
						r.isActive, concat(e.employeNumber,' / ',concat(n.firstName,' ',n.lastName)) as firstName, 
						concat(c.customerNumber,' / ' ,l.legalName) as legalName,
						r.customerIDAfter,						
						r.reference3
					");
		$sql = $sql.sprintf(" from 
									tb_relationship as r 
									inner join tb_naturales as n on r.employeeID = n.entityID 
									INNER join tb_legal as l on r.customerID = l.entityID 
									INNER join tb_employee e on e.entityID = n.entityID 
									INNER join tb_customer c on c.entityID = l.entityID");
		$sql = $sql.sprintf(" where r.relationshipID = $relationshipID");		
		$sql = $sql.sprintf(" and r.isActive= 1");		
		
		//Ejecutar Consulta  
		return $db->query($sql)->getRow();
    }

    

    private function fn_DesplazarOrden($oldOrder, $newOrder, $db, $employeeID)
    {
        // Primero ajustar los órdenes de los demás elementos
        if ($oldOrder < $newOrder) {
            // Mover hacia abajo → disminuir los órdenes de los elementos entre old y new
            $db->query("
            UPDATE tb_relationship
            SET orderNo = orderNo - 1
            WHERE employeeID = ? AND orderNo > ? AND orderNo <= ? AND isActive = 1
        ", [$employeeID, $oldOrder, $newOrder]);
        } else {
            // Mover hacia arriba → aumentar los órdenes de los elementos entre new y old
            $db->query("
            UPDATE tb_relationship
            SET orderNo = orderNo + 1
            WHERE employeeID = ? AND orderNo >= ? AND orderNo < ? AND isActive = 1
        ", [$employeeID, $newOrder, $oldOrder]);
        }
    }
}
?>