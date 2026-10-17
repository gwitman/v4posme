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
   /**
    * Obtiene todas las ordenes de taller asociadas a un activo fijo.
    * El activo fijo se guarda en el campo periodPay de la transaccion de taller.
    * Devuelve datos ordenados cronologicamente (mas reciente primero) con
    * nombres de estado del workflow, estado del equipo (areaID) y tipo (priorityID).
    */
   function getWorkshopOrdersByFixedAssent($companyID,$fixedAssentID){
		$db 	= db_connect();
		$companyID		= (int)$companyID;
		$fixedAssentID	= (int)$fixedAssentID;
		$sql = "";
		$sql = $sql.sprintf("select ");
		$sql = $sql.sprintf("	tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, ");
		$sql = $sql.sprintf("	tm.transactionNumber, tm.transactionOn, tm.statusIDChangeOn, tm.note, ");
		$sql = $sql.sprintf("	tm.amount, tm.statusID, tm.areaID, tm.priorityID, tm.periodPay, tm.nextVisit, ");
		$sql = $sql.sprintf("	tm.reference1, tm.reference2, tm.reference3, tm.createdAt, ");
		$sql = $sql.sprintf("	ws.name as workflowStageName, ");
		$sql = $sql.sprintf("	ciEstado.name as estadoEquipoName, ");
		$sql = $sql.sprintf("	ciTipo.name  as tipoMantenimientoName ");
		$sql = $sql.sprintf(" from tb_transaction_master tm ");
		$sql = $sql.sprintf(" inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID ");
		$sql = $sql.sprintf(" left  join tb_catalog_item ciEstado on tm.areaID     = ciEstado.catalogItemID ");
		$sql = $sql.sprintf(" left  join tb_catalog_item ciTipo   on tm.priorityID = ciTipo.catalogItemID ");
		$sql = $sql.sprintf(" where tm.companyID = %d ", $companyID);
		$sql = $sql.sprintf(" and tm.periodPay = %d ", $fixedAssentID);
		$sql = $sql.sprintf(" and tm.isActive = 1 ");
		$sql = $sql.sprintf(" order by tm.transactionOn desc, tm.transactionMasterID desc ");
		return $db->query($sql)->getResult();
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
		->whereIn("tci.catalogItemID",$catalogItemId)
		->orderBy("tci.name", "ASC");
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
										AND ttm.transactionID = 19 /*factura*/
										AND ttm.isActive = 1
										AND ttm.statusID in (66 /*registrada*/ ) 
										AND ttm.transactionOn >= NOW() - INTERVAL 3 DAY
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
												AND ttm.transactionID = 19 /*factura*/ 
												AND ttm.isActive = 1 
												AND ttm.statusID in (66 /*registrada*/ ) 
												AND ttm.transactionOn >= NOW() - INTERVAL 3 DAY
									),
									0
								) AS reference2,
								tci.reference3,
								tci.reference4")
						->distinct()
						->whereIn("tci.catalogItemID",$catalogItemId);
						
		$objGet = $builder->get();				
		return $objGet->getResultObject();
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

	function getRowAll_Lead_ByEntityID($customerID)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql.sprintf("SELECT ");
		$sql = $sql.sprintf("    tm.transactionMasterID, ");
		$sql = $sql.sprintf("    tm.transactionNumber, ");
		$sql = $sql.sprintf("    tm.transactionOn, ");
		$sql = $sql.sprintf("    tm.note, ");
		$sql = $sql.sprintf("    tm.areaID, ");
		$sql = $sql.sprintf("    tm.priorityID, ");
		$sql = $sql.sprintf("    tm.reference1, ");
		$sql = $sql.sprintf("    tm.statusID, ");
		$sql = $sql.sprintf("    tm.entityID, ");
		$sql = $sql.sprintf("    tm.createdOn, ");
		$sql = $sql.sprintf("    ws.name AS statusName, ");
		$sql = $sql.sprintf("    ci_tipo.name AS tipoName, ");
		$sql = $sql.sprintf("    ci_subtipo.name AS subTipoName ");
		$sql = $sql.sprintf("FROM 
								  	tb_transaction_master tm ");
		$sql = $sql.sprintf("		INNER JOIN tb_workflow_stage ws ON ws.workflowStageID = tm.statusID ");
		$sql = $sql.sprintf("		LEFT JOIN tb_public_catalog_detail ci_tipo ON ci_tipo.publicCatalogDetailID = tm.areaID ");
		$sql = $sql.sprintf("		LEFT JOIN tb_public_catalog_detail ci_subtipo ON ci_subtipo.publicCatalogDetailID = tm.priorityID ");
		$sql = $sql.sprintf("WHERE 
								tm.entityID = %d ", $customerID);
		$sql = $sql.sprintf("	AND tm.isActive = 1 ");
		$sql = $sql.sprintf("	AND tm.transactionID IN ( 42 /*lead de clientes */ )  ");
		$sql = $sql.sprintf("ORDER BY 
								tm.transactionMasterID DESC ");

		return $db->query($sql)->getResult();
	}

	function get_rowByTransactionNumberMobile_AndUser($companyID,$transactionNumberMobile,$userName)
    {
	   	$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
	
		$sql = "";
		$sql = sprintf("
			select 
				tm.transactionMasterID
				,tm.companyID
				,tm.transactionNumber
				,tm.transactionID
				,tm.branchID
				,tm.transactionCausalID
				,tm.entityID
				,tm.transactionOn
				,tm.transactionOn2
				,tm.statusIDChangeOn
				,tm.componentID
				,tm.note
				,tm.sign
				,tm.currencyID
				,tm.currencyID2
				,tm.exchangeRate
				,tm.reference1
				,tm.reference2
				,tm.reference3
				,tm.reference4
				,tm.descriptionReference
				,tm.statusID
				,tm.amount
				,tm.tax1
				,tm.tax2
				,tm.tax3
				,tm.tax4
				,tm.discount
				,tm.subAmount
				,tm.isApplied
				,tm.journalEntryID
				,tm.classID
				,tm.areaID
				,tm.priorityID
				,tm.sourceWarehouseID
				,tm.targetWarehouseID
				,tm.createdBy
				,tm.createdAt
				,tm.createdOn
				,tm.createdIn
				,tm.isActive
				,tm.isTemplate
				,tm.periodPay
				,tm.nextVisit
				,tm.numberPhone
				,tm.notificationID
				,tm.printerQuantity
				,tm.entityIDSecondary
				,tm.dayExcluded
			from 
				tb_transaction_master tm 
				inner join tb_user usr on 
					usr.userID = tm.createdBy 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				tm.companyID = ".$companyID." and 
				tm.isActive = 1 and 
				usr.nickname = '".$userName."' and 
				tmi.reference2 = '".$transactionNumberMobile."' ;
		");
	
		//Ejecutar Consulta		
		return $db->query($sql)->getRow();
    } 

	function getRowAll_DashboardMobile_Facturas(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				t.transactionID AS TransactionID,
				t.`name` AS Transaccion,
				c.transactionMasterID,
				c.transactionNumber AS Documento,
				c.createdOn AS Fecha,
				CONCAT(nat.firstName, ' ', nat.lastName) AS Cliente,
				c.amount AS Monto,
				i.itemNumber AS Codigo,
				i.`name` AS Producto,
				tmd.quantity AS Cantidad,
				tmd.amount AS SubMonto
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID
			INNER JOIN tb_naturales nat
				ON nat.entityID = c.entityID
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_item i
				ON i.itemID = tmd.componentItemID
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = $transactionID
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
				AND (
					'$itemName' = ''
					OR i.`name` LIKE '%%$itemName%%'
				)
			ORDER BY c.createdOn DESC
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	function getRowAll_DashboardMobile_CostOfSales(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				t.transactionID AS TransactionID,
				t.`name` AS Transaccion,
				c.transactionMasterID,
				c.transactionNumber AS Documento,
				c.createdOn AS Fecha,
				CONCAT(nat.firstName, ' ', nat.lastName) AS Cliente,
				c.amount AS Monto,
				i.itemNumber AS Codigo,
				i.`name` AS Producto,
				tmd.quantity AS Cantidad,
				tmd.cost AS SubMonto
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID
			INNER JOIN tb_naturales nat
				ON nat.entityID = c.entityID
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_item i
				ON i.itemID = tmd.componentItemID
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = $transactionID
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
				AND (
					'$itemName' = ''
					OR i.`name` LIKE '%%$itemName%%'
				)
			ORDER BY c.createdOn DESC
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	function getRowAll_DashboardMobile_FacturasProductosAmount(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				i.itemID,
				i.itemNumber as Codigo,
				i.name as Nombre,
				sum(tmd.amount) as Monto   
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID
			INNER JOIN tb_naturales nat
				ON nat.entityID = c.entityID
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_item i
				ON i.itemID = tmd.componentItemID
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = 19 
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
				AND (
					'$itemName' = ''
					OR i.`name` LIKE '%%$itemName%%'
				)
			GROUP BY 
				i.itemID,
				i.itemNumber,
				i.name
			ORDER BY 
				4 ASC ,3 ASC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	function getRowAll_DashboardMobile_FacturasProductosQuantity(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				i.itemID,
				i.itemNumber as Codigo,
				i.name as Nombre,
				sum(tmd.quantity) as Cantidad  
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID
			INNER JOIN tb_naturales nat
				ON nat.entityID = c.entityID
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_item i
				ON i.itemID = tmd.componentItemID
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = 19 
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
				AND (
					'$itemName' = ''
					OR i.`name` LIKE '%%$itemName%%'
				)
			GROUP BY 
				i.itemID,
				i.itemNumber,
				i.name
			ORDER BY 
				4 ASC , 3 ASC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	

	function getRowAll_DashboardMobile_Abonos(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql.sprintf("
			select
				t.transactionID as TransactionID,
				t.`name` as Transaccion,
				c.transactionMasterID,
				c.transactionNumber as Documento,
				c.createdOn as Fecha,
				concat(nat.firstName,' ',nat.lastName) as Cliente,
				c.amount as Monto,
				c.reference1 as Referencia,
				c.note as Nota
			from
				tb_transaction_master c
				inner join tb_workflow_stage ws on
					ws.workflowStageID = c.statusID
				inner join tb_naturales nat on
					nat.entityID = c.entityID
				inner join tb_transaction t on
					t.transactionID = c.transactionID
			where
				c.isActive = 1 and
				ws.aplicable = 1 and
				c.transactionID = $transactionID and
				c.createdOn between '$startOn' and '$endOn'  
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
			ORDER BY 
				c.createdOn DESC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}
	function getRowAll_DashboardMobile_Intereses(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql.sprintf("
			select
				t.transactionID as TransactionID,
				t.`name` as Transaccion,
				c.transactionMasterID,
				c.transactionNumber as Documento,
				c.createdOn as Fecha,
				concat(nat.firstName,' ',nat.lastName) as Cliente,
				tmdc.interest as Monto,
				c.reference1 as Referencia,
				c.note as Nota
			from
				tb_transaction_master c
				inner join tb_workflow_stage ws on
					ws.workflowStageID = c.statusID
				inner join tb_naturales nat on
					nat.entityID = c.entityID
				inner join tb_transaction t on
					t.transactionID = c.transactionID 
				inner join tb_transaction_master_detail_credit tmdc on 
					tmdc.transactionMasterID = c.transactionMasterID 
			where
				c.isActive = 1 and
				ws.aplicable = 1 and
				c.transactionID = $transactionID and
				c.createdOn between '$startOn' and '$endOn'  
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
			ORDER BY 
				c.createdOn DESC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	function getRowAll_DashboardMobile_Capital(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql.sprintf("
			select
				t.transactionID as TransactionID,
				t.`name` as Transaccion,
				c.transactionMasterID,
				c.transactionNumber as Documento,
				c.createdOn as Fecha,
				concat(nat.firstName,' ',nat.lastName) as Cliente,
				tmdc.capital as Monto,
				c.reference1 as Referencia,
				c.note as Nota
			from
				tb_transaction_master c
				inner join tb_workflow_stage ws on
					ws.workflowStageID = c.statusID
				inner join tb_naturales nat on
					nat.entityID = c.entityID
				inner join tb_transaction t on
					t.transactionID = c.transactionID 
				inner join tb_transaction_master_detail_credit tmdc on 
					tmdc.transactionMasterID = c.transactionMasterID 
			where
				c.isActive = 1 and
				ws.aplicable = 1 and
				c.transactionID = $transactionID and
				c.createdOn between '$startOn' and '$endOn'  
				AND (
					'$custonerName' = ''
					OR CONCAT(nat.firstName, ' ', nat.lastName) LIKE '%%$custonerName%%'
				)
			ORDER BY 
				c.createdOn DESC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	function getRowAll_DashboardMobile_SalidaDeCaja(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				c.transactionMasterID,
				c.createdOn as Fecha,
				c.transactionNumber as Codigo,
				tipo.`name` as Tipo,
				categoria.`name` as Categoria ,
				c.amount as Monto,
				c.reference1 as Descripcion   
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID			
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_catalog_item tipo on 
				tipo.catalogItemID = c.areaID 
			INNER JOIN tb_catalog_item categoria on 	
				categoria.catalogItemID = c.priorityID 
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = 30 
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR CONCAT(c.reference1) LIKE '%%$custonerName%%'
				) 
			GROUP BY 
				c.createdOn 
			ORDER BY 
				1 DESC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}
	function getRowAll_DashboardMobile_EntradaDeCaja(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				c.transactionMasterID,
				c.createdOn as Fecha,
				c.transactionNumber as Codigo,
				tipo.`name` as Tipo,
				categoria.`name` as Categoria ,
				c.amount as Monto,
				c.reference1 as Descripcion   
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID			
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_catalog_item tipo on 
				tipo.catalogItemID = c.areaID 
			INNER JOIN tb_catalog_item categoria on 	
				categoria.catalogItemID = c.priorityID 
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = 29 
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR CONCAT(c.reference1) LIKE '%%$custonerName%%'
				) 
			GROUP BY 
				c.createdOn 
			ORDER BY 
				1 DESC 
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}
	function getRowAll_DashboardMobile_OtrasEntradas(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				t.transactionID AS TransactionID,
				t.`name` AS Transaccion,
				c.transactionMasterID,
				c.transactionNumber AS Documento,
				c.createdOn AS Fecha,
				c.note AS Concepto,
				c.amount AS Monto,
				i.itemNumber AS Codigo,
				i.`name` AS Producto,
				tmd.quantity AS Cantidad,
				tmd.unitaryCost AS Costo,
				tmd.unitaryPrice AS Precio
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_transaction_master_detail tmd
				ON tmd.transactionMasterID = c.transactionMasterID
			INNER JOIN tb_item i
				ON i.itemID = tmd.componentItemID
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = 12
				AND c.createdOn BETWEEN '$startOn' AND '$endOn'
				AND tmd.isActive = 1
				AND (
					'$custonerName' = ''
					OR c.note LIKE '%%$custonerName%%'
				)
				AND (
					'$itemName' = ''
					OR i.`name` LIKE '%%$itemName%%'
				)
			ORDER BY c.createdOn DESC
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}
	function getRowAll_DashboardMobile_Gastos(
			$transactionID,
			$startOn,
			$endOn,
			$custonerName,
			$itemName
	)
	{
		$db = db_connect();
		$sql = "";
		$sql = $sql . sprintf("
			SELECT
				c.transactionMasterID,
				c.createdOn as Fecha,
				c.transactionNumber as Codigo,
				tipo.`name` as Tipo,
				categoria.`name` as Categoria, 
				c.amount as Monto,	
				c.note as Descripcion 
			FROM tb_transaction_master c
			INNER JOIN tb_workflow_stage ws
				ON ws.workflowStageID = c.statusID 
			INNER JOIN tb_transaction t
				ON t.transactionID = c.transactionID
			INNER JOIN tb_catalog_item	 tipo on 
				tipo.catalogItemID = c.areaID 
			inner join tb_catalog_item categoria on 
				categoria.catalogItemID = c.priorityID 
			WHERE
				c.isActive = 1
				AND ws.aplicable = 1
				AND c.transactionID = 38 
				AND c.createdOn BETWEEN '$startOn' AND '$endOn' 
				AND (
					'$custonerName' = ''
					OR CONCAT(c.note) LIKE '%%$custonerName%%'
				)
			GROUP BY 
				c.createdOn
			ORDER BY 
				1 DESC  
		");

		log_message("error",print_r($sql,true));
		return $db->query($sql)->getResult();
	}

	//Para mostrar en el calendario
	//Ordenes de taller (transactionID 40) con fecha de siguiente visita (nextVisit)
	//Incluye datos del activo fijo (priorityID) y de su cliente asignado (asignedCustomerID)
	function getProgrammingOrdenTaller($companyID)
	{
		$db 	= db_connect();
		$sql = "
		select
			tm.transactionNumber as rememberID,
			CONCAT('".base_url()."/app_purchase_taller/edit/companyID/',tm.companyID,'/transactionID/40/transactionMasterID/',tm.transactionMasterID) as url,
			CONCAT(
				IFNULL(CONCAT(TIME_FORMAT(tm.transactionOn,'%h:%i %p'), ' '), ''),
				'TALLER ',
				tm.transactionNumber,
				IFNULL(CONCAT(' - ', nat.firstName, ' ', IFNULL(nat.lastName,'')), ''),
				IFNULL(CONCAT(' *** ', fa.name), '')
			) as title,
			CONCAT(
				'Estado ', IFNULL(ws.name,''),
				'<br>',
				'Hora ', IFNULL(TIME_FORMAT(tm.transactionOn,'%h:%i %p'),''),
				'<br><br>',
				'Cliente ', IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),''),
				'<br>',
				'Telefono ', IFNULL(cus.phoneNumber,''),
				'<br>',
				'Activo Fijo ', IFNULL(fa.name,''),
				'<br>',
				'Direccion ', IFNULL(fa.address, IFNULL(cus.address,'')),
				'<br>',
				IFNULL(tm.note,'')
			) as description,
			tm.transactionOn as createdOn,
			tm.transactionOn as createdOn2,
			0 as tagID,
			'purple' as color,
			IFNULL(cus.customerNumber,'') as entidad,
			IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),'') as nombre,
			IFNULL(cus.phoneNumber,'') as telefono,
			IFNULL(fa.name,'') as entregado
		from
			tb_transaction_master tm
			inner join tb_workflow_stage ws on
				tm.statusID = ws.workflowStageID
			left join tb_customer cus on
				cus.entityID = tm.entityID and
				cus.companyID = tm.companyID
			left join tb_naturales nat on
				nat.entityID = tm.entityID
			left join tb_fixed_assent fa on
				fa.fixedAssentID = tm.periodPay
		where
			tm.isActive = 1 and
			tm.companyID = $companyID and
			tm.transactionID = 40 /*ORDEN DE TALLER*/ and
			tm.transactionOn > '1000-01-01 00:00:00'
		";

		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}

	//Para imprimir una sola orden de taller por su transactionNumber (idevent)
	function getProgrammingOrdenTallerById($companyID, $transactionNumber)
	{
		$db 	= db_connect();
		$sql = "
		select
			tm.transactionNumber as rememberID,
			CONCAT('".base_url()."/app_purchase_taller/edit/companyID/',tm.companyID,'/transactionID/40/transactionMasterID/',tm.transactionMasterID) as url,
			CONCAT(
				IFNULL(CONCAT(TIME_FORMAT(tm.transactionOn,'%h:%i %p'), ' '), ''),
				'TALLER ',
				tm.transactionNumber,
				IFNULL(CONCAT(' - ', nat.firstName, ' ', IFNULL(nat.lastName,'')), ''),
				IFNULL(CONCAT(' *** ', fa.name), '')
			) as title,
			CONCAT(
				'Estado: ', IFNULL(ws.name,''),
				'<br>',
				'Hora: ', IFNULL(TIME_FORMAT(tm.transactionOn,'%h:%i %p'),''),
				'<br><br>',
				'Cliente: ', IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),''),
				'<br>',
				'Telefono: ', IFNULL(cus.phoneNumber,''),
				'<br>',
				'Activo Fijo: ', IFNULL(fa.name,''),
				'<br>',
				'Direccion: ', IFNULL(fa.address, IFNULL(cus.address,'')),
				'<br><br>',
				IFNULL(tm.note,'')
			) as description,
			tm.transactionOn as createdOn,
			tm.transactionOn as createdOn2,
			0 as tagID,
			'purple' as color,
			IFNULL(cus.customerNumber,'') as entidad,
			IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),'') as nombre,
			IFNULL(cus.phoneNumber,'') as telefono,
			IFNULL(fa.name,'') as entregado
		from
			tb_transaction_master tm
			inner join tb_workflow_stage ws on
				tm.statusID = ws.workflowStageID
			left join tb_customer cus on
				cus.entityID = tm.entityID and
				cus.companyID = tm.companyID
			left join tb_naturales nat on
				nat.entityID = tm.entityID
			left join tb_fixed_assent fa on
				fa.fixedAssentID = tm.periodPay
		where
			tm.isActive = 1 and
			tm.companyID = $companyID and
			tm.transactionID = 40 /*ORDEN DE TALLER*/ and
			tm.transactionNumber = ?
		";

		//Ejecutar Consulta
		return $db->query($sql, [$transactionNumber])->getRow();
	}

	//Para imprimir todas las ordenes de taller de una fecha (por nextVisit)
	function getProgrammingOrdenTallerByDate($companyID, $date)
	{
		$db 	= db_connect();
		$sql = "
		select
			tm.transactionNumber as rememberID,
			CONCAT('".base_url()."/app_purchase_taller/edit/companyID/',tm.companyID,'/transactionID/40/transactionMasterID/',tm.transactionMasterID) as url,
			CONCAT(
				IFNULL(CONCAT(TIME_FORMAT(tm.transactionOn,'%h:%i %p'), ' '), ''),
				'TALLER ',
				tm.transactionNumber,
				IFNULL(CONCAT(' - ', nat.firstName, ' ', IFNULL(nat.lastName,'')), ''),
				IFNULL(CONCAT(' *** ', fa.name), '')
			) as title,
			CONCAT(
				'Estado: ', IFNULL(ws.name,''),
				'<br>',
				'Hora: ', IFNULL(TIME_FORMAT(tm.transactionOn,'%h:%i %p'),''),
				'<br><br>',
				'Cliente: ', IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),''),
				'<br>',
				'Telefono: ', IFNULL(cus.phoneNumber,''),
				'<br>',
				'Activo Fijo: ', IFNULL(fa.name,''),
				'<br>',
				'Direccion: ', IFNULL(fa.address, IFNULL(cus.address,'')),
				'<br><br>',
				IFNULL(tm.note,'')
			) as description,
			tm.transactionOn as createdOn,
			tm.transactionOn as createdOn2,
			0 as tagID,
			'purple' as color,
			IFNULL(cus.customerNumber,'') as entidad,
			IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),'') as nombre,
			IFNULL(cus.phoneNumber,'') as telefono,
			IFNULL(fa.name,'') as entregado
		from
			tb_transaction_master tm
			inner join tb_workflow_stage ws on
				tm.statusID = ws.workflowStageID
			left join tb_customer cus on
				cus.entityID = tm.entityID and
				cus.companyID = tm.companyID
			left join tb_naturales nat on
				nat.entityID = tm.entityID
			left join tb_fixed_assent fa on
				fa.fixedAssentID = tm.periodPay
		where
			tm.isActive = 1 and
			tm.companyID = $companyID and
			tm.transactionID = 40 /*ORDEN DE TALLER*/ and
			DATE(tm.transactionOn) = ?
		";

		//Ejecutar Consulta
		return $db->query($sql, [$date])->getResult();
	}

	//Para notificar por correo las ordenes de taller proximas a su siguiente visita (nextVisit)
	//Trae ordenes cuya nextVisit sea hoy (0), manana (1), en 2 o en 3 dias
	function getProgrammingOrdenTallerToNotify($companyID)
	{
		$db 	= db_connect();
		$sql = "
		select
			DATEDIFF(DATE(tm.transactionOn), CURDATE()) as diasRestantes,
			tm.transactionMasterID,
			tm.transactionNumber,
			tm.transactionOn,
			tm.note,
			IFNULL(ws.name,'') as estado,
			IFNULL(fa.fixedAssentCode,'') as activoCodigo,
			IFNULL(fa.name,'') as activoNombre,
			IFNULL(fa.marca,'') as activoMarca,
			IFNULL(fa.modelNumber,'') as activoModelo,
			IFNULL(fa.address, IFNULL(cus.address,'')) as activoDireccion,
			IFNULL(cus.customerNumber,'') as clienteCodigo,
			IFNULL(CONCAT(nat.firstName,' ',IFNULL(nat.lastName,'')),'') as clienteNombre,
			IFNULL(cus.phoneNumber,'') as clienteTelefono
		from
			tb_transaction_master tm
			inner join tb_workflow_stage ws on
				tm.statusID = ws.workflowStageID
			left join tb_customer cus on
				cus.entityID = tm.entityID and
				cus.companyID = tm.companyID
			left join tb_naturales nat on
				nat.entityID = tm.entityID
			left join tb_fixed_assent fa on
				fa.fixedAssentID = tm.periodPay
		where
			tm.isActive = 1 and
			tm.companyID = $companyID and
			tm.transactionID = 40 /*ORDEN DE TALLER*/ and 
			DATEDIFF(DATE(tm.transactionOn), CURDATE()) in (0,1,2,3)
		order by
			tm.transactionOn asc
		";

		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}

   /**
    * Cuenta la cantidad total de transacciones creadas por un usuario (createdBy),
    * uniendo con tb_user por userID = createdBy.
    */
   function get_countByCreatedBy($companyID,$userID)
   {
		$db 		= db_connect();
		$companyID	= (int)$companyID;
		$userID		= (int)$userID;
		$sql = "";
		$sql = $sql.sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_transaction_master tm ");
		$sql = $sql.sprintf(" inner join tb_user u on u.userID = tm.createdBy ");
		$sql = $sql.sprintf(" where tm.companyID = %d ", $companyID);
		$sql = $sql.sprintf(" and tm.createdBy = %d ", $userID);
		$sql = $sql.sprintf(" and tm.isActive = 1 ");
		return $db->query($sql)->getRow()->counter;
   }

   /**
    * Estadistica de la cantidad de transacciones por tipo de transaccion
    * para un usuario filtrado (createdBy vs userID).
    * Devuelve el nombre de la transaccion y la cantidad.
    */
   function get_statisticByCreatedBy($companyID,$userID)
   {
		$db 		= db_connect();
		$companyID	= (int)$companyID;
		$userID		= (int)$userID;
		$sql = "";
		$sql = $sql.sprintf("select t.transactionID, t.`name` as transactionName, count(*) as counter ");
		$sql = $sql.sprintf(" from tb_transaction_master tm ");
		$sql = $sql.sprintf(" inner join tb_user u on u.userID = tm.createdBy ");
		$sql = $sql.sprintf(" inner join tb_transaction t on t.transactionID = tm.transactionID ");
		$sql = $sql.sprintf(" where tm.companyID = %d ", $companyID);
		$sql = $sql.sprintf(" and tm.createdBy = %d ", $userID);
		$sql = $sql.sprintf(" and tm.isActive = 1 ");
		$sql = $sql.sprintf(" group by t.transactionID, t.`name` ");
		$sql = $sql.sprintf(" order by counter desc ");
		return $db->query($sql)->getResult();
   }

   /**
    * Lista las ordenes de taller (tb_transaction_master_workshop_taller) aplicando
    * filtros opcionales: rango de fecha, colaborador (entityIDSecondary),
    * proyecto/activo fijo (periodPay) y estado del workflow (statusID).
    * Devuelve datos listos para el grid/dashboard con nombres de cliente,
    * colaborador, estado del workflow y estado del equipo.
    */
   function getWorkshopOrdersFiltered($companyID,$transactionID,$startOn,$endOn,$employerEntityID,$fixedAssentID,$statusID)
   {
		$db 				= db_connect();
		$companyID			= (int)$companyID;
		$transactionID		= (int)$transactionID;
		$employerEntityID	= (int)$employerEntityID;
		$fixedAssentID		= (int)$fixedAssentID;
		$statusID			= (int)$statusID;
		$startOn			= $db->escapeString($startOn);
		$endOn				= $db->escapeString($endOn);

		$sql = "";
		$sql = $sql.sprintf("select ");
		$sql = $sql.sprintf("	tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, ");
		$sql = $sql.sprintf("	tm.transactionNumber, tm.transactionOn, tm.statusIDChangeOn, tm.note, ");
		$sql = $sql.sprintf("	tm.amount, tm.statusID, tm.areaID, tm.priorityID, tm.periodPay, ");
		$sql = $sql.sprintf("	tm.entityID, tm.entityIDSecondary, tm.reference2, tm.createdOn, ");
		$sql = $sql.sprintf("	ws.name as workflowStageName, ");
		$sql = $sql.sprintf("	TRIM(CONCAT(IFNULL(natCli.firstName,''),' ',IFNULL(natCli.lastName,''))) as customerName, ");
		$sql = $sql.sprintf("	TRIM(CONCAT(IFNULL(natEmp.firstName,''),' ',IFNULL(natEmp.lastName,''))) as employerName, ");
		$sql = $sql.sprintf("	fa.marca as marcaName, ");
		$sql = $sql.sprintf("	fa.modelNumber as modeloName, ");
		$sql = $sql.sprintf("	fa.fixedAssentID as fixedAssentID, ");
		$sql = $sql.sprintf("	fa.branchID as fixedAssentBranchID, ");
		$sql = $sql.sprintf("	fa.fixedAssentCode as fixedAssentCode, ");
		$sql = $sql.sprintf("	fa.name as fixedAssentName ");
		$sql = $sql.sprintf(" from tb_transaction_master tm ");
		$sql = $sql.sprintf(" inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID ");
		$sql = $sql.sprintf(" left  join tb_naturales natCli on tm.entityID = natCli.entityID ");
		$sql = $sql.sprintf(" left  join tb_naturales natEmp on tm.entityIDSecondary = natEmp.entityID ");
		$sql = $sql.sprintf(" left  join tb_fixed_assent fa on tm.periodPay = fa.fixedAssentID and tm.companyID = fa.companyID ");
		$sql = $sql.sprintf(" where tm.companyID = %d ", $companyID);
		$sql = $sql.sprintf(" and tm.transactionID = %d ", $transactionID);
		$sql = $sql.sprintf(" and tm.isActive = 1 ");

		if($startOn != "" && $endOn != ""){
			$sql = $sql.sprintf(" and date(tm.transactionOn) between '%s' and '%s' ", $startOn, $endOn);
		}
		if($employerEntityID > 0){
			$sql = $sql.sprintf(" and tm.entityIDSecondary = %d ", $employerEntityID);
		}
		if($fixedAssentID > 0){
			$sql = $sql.sprintf(" and tm.periodPay = %d ", $fixedAssentID);
		}
		if($statusID > 0){
			$sql = $sql.sprintf(" and tm.statusID = %d ", $statusID);
		}

		$sql = $sql.sprintf(" order by tm.transactionOn desc, tm.transactionMasterID desc ");
		return $db->query($sql)->getResult();
   }

}

?>


