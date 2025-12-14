<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_Model extends Model  {
   function __construct(){
	
      parent::__construct();
   }
   function delete_app_posme($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");
		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);
		
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$transactionID,$transactionMasterID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.descriptionReference, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive, ws.name as workflowStageName,tm.priorityID, tm.transactionOn2 , tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary , tm.dayExcluded  ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on tm.statusID = ws.workflowStageID");
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByTransactionMasterID($companyID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");   
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID, tm.transactionOn2, tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary, tm.dayExcluded ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByTransactionID_And_EntityID($companyID,$transactionID,$entityID)
   {
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");   
		$sql = "";
		$sql = sprintf(
			"select 
				tm.companyID, 
				tm.transactionID, tm.transactionMasterID, tm.branchID, 
				tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, 
				tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign,
				tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, 
				tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, 
				tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, 
				tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID, tm.transactionOn2, 
				tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary, 
				tm.dayExcluded 
			");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.entityID = $entityID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		$sql = $sql.sprintf(" order by  tm.transactionMasterID desc limit 10 ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByTransactionNumber($companyID,$transactionNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2 , tm.isTemplate, tm.periodPay,tm.nextVisit,tm.numberPhone,tm.printerQuantity,tm.entityIDSecondary , tm.dayExcluded ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionNumber = '$transactionNumber' ");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   
   function get_rowByNotification($companyID)
   {
	   $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2 , tm.isTemplate, tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary , tm.dayExcluded  ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where");
		$sql = $sql.sprintf(" 		tm.companyID = $companyID and ");
		$sql = $sql.sprintf(" 		tm.isActive= 1 and ");		
		$sql = $sql.sprintf(" 		tm.nextVisit is not null and ");	
		$sql = $sql.sprintf(" 		tm.nextVisit != '0000-00-00' and ");	
		$sql = $sql.sprintf(" 		tm.notificationID is null  ");	

		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowInStatusRegister($companyID,$transactionMasterID)
   {
	   	$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
   
		
	
		$sql = "";
		$sql = sprintf("
			select 
				tm.transactionNumber,
				ws.`name` as nameStatus
			from 
				tb_transaction_master tm 
				inner join tb_workflow_stage ws on 
					tm.statusID = ws.workflowStageID 
			where 
				tm.isActive = 1 and 
				tm.companyID = $companyID and 
				ws.editableTotal = 1 and 
				tm.transactionMasterID != $transactionMasterID 
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByCreatedBy_AndCurrentDate($companyID,$userID)
   {
	    $db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
   
		
		/*Regresas solo las facturas aplicadas de tipo abono, y de tipo factura*/
		$sql = "";
		$sql = sprintf("
			select 
				t.transactionID,
				t.transactionMasterID,
				t.currencyID,
				t.amount 
			from 
				tb_transaction_master t 
			where 
				t.isActive = 1 and 
				t.transactionId in (19 /*factura*/, 23 /*abonos*/ ) and 
				t.statusID in (67 /*facturas aplicadas*/ , 80 /*abonos aplicados*/ ) and 
				t.companyID = $companyID and 
				t.createdBy = $userID and 
				date(t.createdOn) = date(NOW()) 
		");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByNumberExoneration($companyID,$exonerationNumber)
   {
	   	$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
   
		
	
		$sql = "";
		$sql = sprintf("
			select 
				t.transactionNumber,
				tr.reference1 as  exonerationNumber 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = t.statusID 
				inner join tb_transaction_master_references tr on 
					tr.transactionMasterID = t.transactionMasterID 
			where 
				t.transactionID = 19 /*factura*/ and 
				t.isActive = 1 and 
				ws.aplicable = 1 and 
				tr.reference1 != '' and 
				tr.reference1 = '$exonerationNumber' 
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

	function get_ZonasByCatalogItemID($catalogItemId):array{
		$db 		= db_connect();
		$builder	= $db->table("tb_catalog_item tci")
						->distinct()
						->select("tci.catalogID,
							tci.catalogItemID,
							tci.name,
							tci.display,
							tci.flavorID,
							tci.description,
							tci.sequence,
							tci.parentCatalogID,
							tci.parentCatalogItemID,
							tci.ratio, 
							IF(
								(
								SELECT 
									MIN(ttmi.transactionMasterID)
								FROM 
									tb_transaction_master ttm  
									INNER JOIN tb_transaction_master_info ttmi
										ON ttm.transactionMasterID = ttmi.transactionMasterID
									INNER JOIN tb_workflow_stage tws
										ON ttm.statusID = tws.workflowStageID
										AND tws.isInit = 1
								WHERE 
									tci.catalogItemID = ttmi.zoneID
									AND ttm.transactionID = 19
									AND ttm.isActive = 1
									AND ttm.transactionOn >= NOW() - INTERVAL 1 DAY
								) IS NULL,tci.reference1, REPLACE(tci.reference1, '.JPG', '_BUSSY.JPG')
							) AS reference1,
							tci.reference2,
							tci.reference3,
							tci.reference4")	
		->whereIn("tci.catalogItemID",$catalogItemId);
		return $builder->get()->getResultObject();
	}

	function get_MesasByCatalogItemID($catalogItemId):array{
		$db 		= db_connect();
		$builder	= $db->table("tb_catalog_item tci")
						->select("
								tci.catalogID,
								tci.catalogItemID,
								tci.name,
								tci.display,
								tci.flavorID,
								tci.description,
								tci.sequence,
								tci.parentCatalogID,
								tci.parentCatalogItemID,
								tci.ratio, 								
								IF(
									(								
									SELECT 
										MIN(ttmi.transactionMasterID)
									FROM 
										tb_transaction_master ttm  
										INNER JOIN tb_transaction_master_info ttmi
											ON ttm.transactionMasterID = ttmi.transactionMasterID
										INNER JOIN tb_workflow_stage tws
											ON ttm.statusID = tws.workflowStageID
											AND tws.isInit = 1
									WHERE 
										tci.catalogItemID = ttmi.mesaID
										AND ttm.transactionID = 19
										AND ttm.isActive = 1
										AND ttm.transactionOn >= NOW() - INTERVAL 1 DAY
									) IS NULL,
									tci.reference1, 
									REPLACE(tci.reference1, '.JPG', '_BUSSY.JPG')
								) AS reference1,
								
								IFNULL(
									(
										SELECT 
											MIN(ttmi.transactionMasterID)
										FROM 
											tb_transaction_master ttm  
											INNER JOIN tb_transaction_master_info ttmi
												ON ttm.transactionMasterID = ttmi.transactionMasterID
											INNER JOIN tb_workflow_stage tws
												ON ttm.statusID = tws.workflowStageID
												AND tws.isInit = 1
											WHERE 
												tci.catalogItemID = ttmi.mesaID
												AND ttm.transactionID = 19
												AND ttm.isActive = 1
												AND ttm.transactionOn >= NOW() - INTERVAL 1 DAY
									),
									0
								) AS reference2,
								tci.reference3,
								tci.reference4")
						->distinct()
						->whereIn("tci.catalogItemID",$catalogItemId);
						
		return $builder->get()->getResultObject();
	}

    /*
     * Grado -> classID
     * Alumno -> entityID
     * Colaborador -> entityIDSecondary
     * Materia -> areaID
     * Anio, mes y dia -> transactionON
     * ValorCuantitativo -> amount
     * ValorCualitativo -> priorityID
     */
    function get_RowByNotas($classID, $entityID, $entityIDSecondary, $areaID, $transactionON, $priorityID)
    {
        $db 		= db_connect();

        $builder	= $db->table("tb_transaction_master ttm")
                        ->select("ttm.transactionMasterID,
                     ttm.companyID,
                     ttm.transactionNumber,
                     ttm.transactionID,
                     ttm.branchID,
                     ttm.transactionCausalID,
                     ttm.entityID,
                     ttm.transactionOn,
                     ttm.transactionOn2,
                     ttm.statusIDChangeOn,
                     ttm.componentID,
                     ttm.reference1,
                     ttm.reference2,
                     ttm.amount,
                     ttm.classID,
                     ttm.areaID,
                     ttm.priorityID,
                     ttm.createdBy,
                     ttm.createdAt,
                     ttm.createdOn,
                     ttm.createdIn,
                     ttm.isActive,
                     ttm.entityIDSecondary");
        if (!empty($classID)) {
            $builder->where("ttm.classID",$classID);
        }
        if (!empty($entityID)) {
            $builder->where("ttm.entityID",$entityID);
        }
        if (!empty($entityIDSecondary)) {
            $builder->where("ttm.entityIDSecondary",$entityIDSecondary);
        }
        if (!empty($areaID)) {
            $builder->where("ttm.areaID",$areaID);
        }
        if (!empty($priorityID)) {
            $builder->where("ttm.priorityID",$priorityID);
        }
        if (!empty($transactionON)) {
            $builder->where("ttm.transactionON",$transactionON);
        }
        $builder->where("ttm.isActive",1);
        $builder->where("ttm.componentID",108); /*tb_transaction_master_grade_book*/
        return $builder->get()->getRow();
    }

	
    function get_RowGradeBook($classID, $entityID, $entityIDSecondary, $areaID, $anio, $mes, $transactionON)
    {
        $db 		= db_connect();

        $builder	= $db->table("tb_transaction_master ttm")
                        ->select("
                          ttm.transactionID,
                          ttm.transactionMasterID,
                          tc.customerNumber as codigoAlumno,
                          CONCAT(tn.firstName, ' ', tn.lastName) as alumno, 
                          te.employeNumber as codigoColaborador,
                          CONCAT(tn1.firstName, ' ', tn1.lastName) AS colaborador,
                          tpcd.name as materia, 
                          tci.description AS calificacionCualitativa,
                          tci2.display as grado, 
                          ttm.amount AS calificacionCuantitativa,
                          ttm.transactionOn,
                          tci.display as color")
            ->join('tb_naturales tn', 'ttm.entityID=tn.entityID', 'inner')
            ->join('tb_customer tc', 'tn.entityID = tc.entityID', 'inner')
            ->join('tb_naturales tn1', 'ttm.entityIDSecondary=tn1.entityID', 'inner')
            ->join('tb_employee te', 'tn1.entityID = te.entityID', 'inner')
            ->join('tb_public_catalog_detail tpcd', 'ttm.areaID=tpcd.publicCatalogDetailID', 'inner')
            ->join('tb_catalog_item tci', 'ttm.priorityID = tci.catalogItemID', 'inner')
            ->join('tb_catalog_item tci2', 'ttm.classID = tci2.catalogItemID', 'inner');
        if (!empty($classID)) {
            $builder->where("ttm.classID",$classID);
        }
        if (!empty($entityID)) {
            $builder->where("ttm.entityID",$entityID);
        }
        if (!empty($entityIDSecondary)) {
            $builder->where("ttm.entityIDSecondary",$entityIDSecondary);
        }
        if (!empty($areaID)) {
            $builder->where("ttm.areaID",$areaID);
        }
        if (!empty($anio)) {
            $builder->where("YEAR(ttm.transactionON)",$anio);
        }
        if (!empty($mes) && is_numeric($mes) && $mes >= 1 && $mes <= 12) {
            $builder->where("MONTH(ttm.transactionON)", $mes);
        }
        if (!empty($transactionON)) {
            $builder->where("ttm.transactionON",$transactionON);
        }
        $builder->where("ttm.isActive",1);
        $builder->where("ttm.componentID",108); /*tb_transaction_master_grade_book*/
        $builder->orderBy('MONTH(ttm.transactionON)','desc');
        $builder->orderBy('ttm.areaID','desc');
        $builder->orderBy('ttm.entityIDSecondary','desc');
        return $builder->get()->getResult();
    }
	function get_RowAll_TransactionMaster_Task()
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
   
		
	
		$sql = "";
		$sql = sprintf("
			select
				c.companyID,
				c.transactionID,				
				c.transactionMasterID,
				c.transactionNumber,
				c.transactionOn,
				c.transactionOn2,
				c.nextVisit,
				c.note,
				c.reference1,
				c.reference2,
				c.reference3,
				c.reference4,
				c.entityID,
				c.entityIDSecondary,
				c.areaID,
				c.priorityID,
				c.statusID,
				c.note,
				c.descriptionReference,
				nat.firstName as responsable ,
				asig.firstName as asignado ,
				ws.`name` as statusName,
				ci.`name` as priorityName,
				ci2.`name` as categoryName
			from 
				tb_transaction_master c
				inner join tb_naturales nat on 
					nat.entityID = c.entityID 
				inner join tb_naturales asig on 
					asig.entityID = c.entityIDSecondary 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = c.statusID 
				inner join tb_catalog_item ci on 
					ci.catalogItemID = c.priorityID 
				inner join tb_catalog_item ci2 on 
					ci2.catalogItemID = c.areaID 
			where 
				c.transactionID = 44 /*task*/ and 
				c.isActive = 1 and
				c.statusID not  in (146 /*terminada*/, 147 /*cancelada*/) 
			order by 
				nat.firstName,CAST(c.reference3 AS UNSIGNED)   asc  
				
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	}
}
?>