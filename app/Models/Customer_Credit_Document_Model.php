<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Credit_Document_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($customerCreditDocumentID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");
		
		$builder->where("customerCreditDocumentID",$customerCreditDocumentID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($customerCreditDocumentID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");		
  		$data["isActive"] = 0;
		
		$builder->where("customerCreditDocumentID",$customerCreditDocumentID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByPK($customerCreditDocumentID){
		$db 	     = db_connect();
		$builder	 = $db->table("tb_customer_credit_document");
	    $sql = "";
		$sql = sprintf("
		select 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,
			i.documentNumber, i.dateOn, i.amount, i.interes, i.term,i.exchangeRate, 
			i.reference1, i.reference2, i.reference3, i.statusID, i.isActive,i.balance,
			i.balanceProvicioned,i.currencyID,cur.name as currencyName,cur.simbol as currencySimbol,	
			(
			select 	
				sum(tccda.remaining) 
			from 
				tb_customer_credit_document tccd 
				inner join tb_customer_credit_amoritization tccda on tccd.customerCreditDocumentID = tccda.customerCreditDocumentID 
			where 
				tccd.customerCreditDocumentID = i.customerCreditDocumentID
			)  as balanceNew,
			i.reportSinRiesgo, 
			(
				select 	
					max(tccda.dateApply) 
				from 
					tb_customer_credit_document tccd 
					inner join tb_customer_credit_amoritization tccda on tccd.customerCreditDocumentID = tccda.customerCreditDocumentID 
				where 
					tccd.customerCreditDocumentID = i.customerCreditDocumentID
			) as dateFinish 
		");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");	
		$sql = $sql.sprintf(" inner join  tb_currency cur on i.currencyID = cur.currencyID");
		$sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($companyID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		$sql = "";
		$sql = sprintf("select customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByEntityApplied($companyID,$entityID,$currencyID ){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");  
		
		$sql = "		
		select 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, 
			i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, 
			i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,
			i.reportSinRiesgo,
			sum(cc.remaining) as remaining
		from 
			tb_customer_credit_document i
			inner join tb_customer_credit_amoritization cc on cc.customerCreditDocumentID = i.customerCreditDocumentID
			inner join tb_workflow_stage a on a.workflowStageID = i.statusID 
		where 
			i.companyID  = $companyID and 
			i.entityID   = $entityID and 
			i.isActive   = 1 and 
			a.aplicable  = 1 and 
			i.currencyID = $currencyID 
		group by 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, 
			i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, 
			i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,
			i.reportSinRiesgo

		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	
   }
   
   function get_rowByEntityCreditLine($companyID,$entityID,$creditLineID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		$sql = "";
		$sql = sprintf("select customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.customerCreditLineID = $creditLineID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByDocument($companyID,$entityID,$documentNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");
	    $sql = "";
		$sql = sprintf("select 
							i.customerCreditDocumentID, i.companyID, i.entityID, 
							i.customerCreditLineID,i.documentNumber, i.dateOn, 
							amount, interes, term,exchangeRate, i.reference1, 
							i.reference2, i.reference3, i.statusID,
							i.isActive,i.balance,i.currencyID,
							i.reportSinRiesgo,
							period.`name`  as periodName,
							(
								select max(tccda.dateApply) 
								from 
									tb_customer_credit_document tccd 
									inner join tb_customer_credit_amoritization tccda on 
										tccd.customerCreditDocumentID = tccda.customerCreditDocumentID 
								where 
									tccd.customerCreditDocumentID = i.customerCreditDocumentID
							)  as dateFinish,
							(
								select min(tccda.dateApply) 
								from 
									tb_customer_credit_document tccd 
									inner join tb_customer_credit_amoritization tccda on 
										tccd.customerCreditDocumentID = tccda.customerCreditDocumentID 
								where 
									tccd.customerCreditDocumentID = i.customerCreditDocumentID
							)  as dateStart 
					  ");
		$sql = $sql.sprintf(" from 
								tb_customer_credit_document i");		
		$sql = $sql.sprintf("
								inner join tb_catalog_item period on 
									period.catalogItemID   = i.periodPay 

							");
							
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.documentNumber = '$documentNumber' ");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByBalanceBetweenCeroAndCeroPuntoCinco($companyID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		
		$sql = "";
		$sql = sprintf("select customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		$sql = $sql.sprintf(" and i.balance between 0 and 0.2 ");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByBalancePending($companyID,$entityID,$customerCreditDocumentID,$currencyID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		
		$sql = "";
		$sql = sprintf("
			select 
				d.entityID,
				d.customerCreditDocumentID,
				d.customerCreditLineID,
				d.documentNumber,
				d.balance as balanceDocument,
				d.currencyID,
				d.statusID as statusDocument,
				min(a.creditAmortizationID) as creditAmortizationID,
				min(a.dateApply) as dateApply,	
				sum(a.remaining) as remaingin,
				min(a.statusID) as statusAmotization,
				min(wsa.name) as statusAmortizatonName
			from 
				tb_customer_credit_document  d 
				inner join tb_customer_credit_amoritization a on 
					d.customerCreditDocumentID = a.customerCreditDocumentID 
				inner join tb_workflow_stage wsa on 
					wsa.workflowStageID = a.statusID 
				inner join tb_workflow_stage wsd on 
					wsd.workflowStageID = d.statusID 
					
			where 
				d.isActive = 1 and 
				d.companyID = $companyID and 
				a.isActive = 1 and 
				wsa.aplicable = 1 and 
				wsd.aplicable = 1 and 
				a.remaining > 0 and 		
				d.entityID = $entityID  and 
				a.customerCreditDocumentID >= $customerCreditDocumentID and 
				d.currencyID = $currencyID
			group by 
				d.entityID,
				d.customerCreditDocumentID,
				d.customerCreditLineID,
				d.documentNumber,
				d.balance,
				d.currencyID,
				d.statusID
			order by 
				d.customerCreditDocumentID 
		");
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByBalancePendingByCompanyToMobile($companyID,$userID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		
		$sql = "";
		$sql = sprintf("
			select 
				k.entityID,
				k.customerCreditDocumentID,
				k.customerCreditLineID,
				k.documentNumber,
				k.currencyID,
				k.currencyName,
				k.statusDocument,
				k.exchangeRate,
				k.creditAmortizationID,
				k.dateApply,
				k.statusAmotization,
				k.statusAmortizatonName,
				
				(
					select 
						sum(uu.remaining) 
					from 
						tb_customer_credit_amoritization uu 
					where 
						uu.customerCreditDocumentID = k.customerCreditDocumentID and 
						uu.remaining > 0 		
				) as  balance /*sirver para ver cual es la menta a cobrar*/,
				
				(
					select 
						sum(uu.remaining) 
					from 
						tb_customer_credit_amoritization uu 
					where 
						uu.customerCreditDocumentID = k.customerCreditDocumentID and 
						uu.remaining > 0 		
				) as  remaining /*se modifica en cada cuota mobile*/,
				
				(
					select 
						max(amor.share)
					from 
						tb_customer_credit_amoritization amor 
					where 
						amor.customerCreditDocumentID = k.customerCreditDocumentID  and 
						amor.isActive = 1 
				) as CuotaPactada , 
				
				(
					select 
						count(amor.creditAmortizationID)
					from 
						tb_customer_credit_amoritization amor 
					where 
						amor.customerCreditDocumentID = k.customerCreditDocumentID  and 
						amor.isActive = 1 
				) as CantidadCuotas ,
				
				(
					select 
						min(uu.dateApply) 
					from 
						tb_customer_credit_amoritization uu 
					where 
						uu.customerCreditDocumentID = k.customerCreditDocumentID and 
						uu.remaining > 0 		
				) as  MinFechaPago /*primer fecha de pago*/ 
				
				
			from 
				(
				select 
								d.entityID,
								d.customerCreditDocumentID,
								d.customerCreditLineID,
								d.documentNumber,				
								d.currencyID,
								cur.simbol as currencyName,
								d.statusID as statusDocument,
								min(ex.ratio) as exchangeRate,
								min(a.creditAmortizationID) as creditAmortizationID,
								min(a.dateApply) as dateApply,					
								sum(a.remaining) as balance,
								sum(a.remaining) as remaining,								
								min(a.statusID) as statusAmotization,
								min(wsa.name) as statusAmortizatonName
							from 
								tb_customer_credit_document  d 
								inner join tb_customer_credit_amoritization a on 
									d.customerCreditDocumentID = a.customerCreditDocumentID 
								inner join tb_workflow_stage wsa on 
									wsa.workflowStageID = a.statusID 
								inner join tb_workflow_stage wsd on 
									wsd.workflowStageID = d.statusID 
								inner join tb_currency cur on 
									cur.currencyID = d.currencyID 
								inner join tb_customer_credit_line crd on 
									crd.customerCreditLineID = d.customerCreditLineID 
								inner join tb_customer cust on 
									cust.entityID = d.entityID
									
									
								inner join  (
									select 
										distinct 
										usrx.userID,
										usrx.employeeID,
										ccx.entityID,
										ccx.customerID 
									from 
										tb_user usrx 
										inner join tb_relationship rrx on 
											usrx.employeeID = rrx.employeeID 
										inner join tb_customer ccx on 
											rrx.customerID = ccx.entityID 
									where 
										rrx.isActive = 1 
								) as usr  on 
									usr.customerID = cust.customerID 
									
								left join tb_exchange_rate ex on 
									ex.currencyID = 1 and 
									ex.targetCurrencyID = 2 and 
									ex.date = DATE(now()) 
							where 
								d.companyID = $companyID  and 
								usr.userID = $userID  and 
								d.isActive = 1 and 				
								a.isActive = 1 and 
								wsa.aplicable = 1 and 
								wsd.aplicable = 1 and 
								a.remaining > 0  
							group by 
								d.entityID,
								d.customerCreditDocumentID,
								d.customerCreditLineID,
								d.documentNumber,				
								d.currencyID,
								cur.name, 
								d.statusID
							order by 
								d.customerCreditDocumentID 
				) k 
			order by 
				MinFechaPago,
				k.entityID
		");
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByBalancePendingByCompanyToMobileTuFuturo($companyID,$userID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		
		$sql = "";
		$sql = sprintf("
			select 
				k.entityID,
				k.customerCreditDocumentID,
				k.customerCreditLineID,
				k.documentNumber,
				k.currencyID,
				k.currencyName,
				k.statusDocument,
				k.exchangeRate,
				k.creditAmortizationID,
				k.dateApply,
				k.statusAmotization,
				k.statusAmortizatonName,
				
				(
					select 
						sum(uu.remaining) 
					from 
						tb_customer_credit_amoritization uu 
					where 
						uu.customerCreditDocumentID = k.customerCreditDocumentID and 
						uu.remaining > 0 		
				) as  balance /*sirver para ver cual es la menta a cobrar*/,
				
				(
					select 
						sum(uu.remaining) 
					from 
						tb_customer_credit_amoritization uu 
					where 
						uu.customerCreditDocumentID = k.customerCreditDocumentID and 
						uu.remaining > 0 		
				) as  remaining /*se modifica en cada cuota mobile*/,
				
				(
					select 
						max(amor.share)
					from 
						tb_customer_credit_amoritization amor 
					where 
						amor.customerCreditDocumentID = k.customerCreditDocumentID  and 
						amor.isActive = 1 
				) as CuotaPactada , 
				
				(
					select 
						count(amor.creditAmortizationID)
					from 
						tb_customer_credit_amoritization amor 
					where 
						amor.customerCreditDocumentID = k.customerCreditDocumentID  and 
						amor.isActive = 1 
				) as CantidadCuotas ,
				
				(
					select 
						min(uu.dateApply) 
					from 
						tb_customer_credit_amoritization uu 
					where 
						uu.customerCreditDocumentID = k.customerCreditDocumentID and 
						uu.remaining > 0 		
				) as  MinFechaPago /*primer fecha de pago*/ 
				
				
			from 
				(
				select 
								d.entityID,
								d.customerCreditDocumentID,
								d.customerCreditLineID,
								d.documentNumber,				
								d.currencyID,
								cur.simbol as currencyName,
								d.statusID as statusDocument,
								min(ex.ratio) as exchangeRate,
								min(a.creditAmortizationID) as creditAmortizationID,
								min(a.dateApply) as dateApply,					
								sum(a.remaining) as balance,
								sum(a.remaining) as remaining,								
								min(a.statusID) as statusAmotization,
								min(wsa.name) as statusAmortizatonName
							from 
								tb_customer_credit_document  d 
								inner join tb_customer_credit_amoritization a on 
									d.customerCreditDocumentID = a.customerCreditDocumentID 
								inner join tb_workflow_stage wsa on 
									wsa.workflowStageID = a.statusID 
								inner join tb_workflow_stage wsd on 
									wsd.workflowStageID = d.statusID 
								inner join tb_currency cur on 
									cur.currencyID = d.currencyID 
								inner join tb_customer_credit_line crd on 
									crd.customerCreditLineID = d.customerCreditLineID 
								inner join tb_customer cust on 
									cust.entityID = d.entityID
									
									
								inner join  (
									select 
										distinct 
										usrx.userID,
										usrx.employeeID,
										ccx.entityID,
										ccx.customerID,
										IFNULL(rrx.reference1,'') as fac
									from 
										tb_user usrx 
										inner join tb_relationship rrx on 
											usrx.employeeID = rrx.employeeID 
										inner join tb_customer ccx on 
											rrx.customerID = ccx.entityID 
									where 
										rrx.isActive = 1 
								) as usr  on 
									usr.customerID = cust.customerID and 
									usr.fac = d.documentNumber 
									
								left join tb_exchange_rate ex on 
									ex.currencyID = 1 and 
									ex.targetCurrencyID = 2 and 
									ex.date = DATE(now()) 
							where 
								d.companyID = $companyID  and /*
								usr.userID = $userID  and */ 
								d.isActive = 1 and 				
								a.isActive = 1 and 
								wsa.aplicable = 1 and 
								wsd.aplicable = 1 and 
								a.remaining > 0  
							group by 
								d.entityID,
								d.customerCreditDocumentID,
								d.customerCreditLineID,
								d.documentNumber,				
								d.currencyID,
								cur.name, 
								d.statusID
							order by 
								d.customerCreditDocumentID 
				) k 
			order by 
				MinFechaPago,
				k.entityID
		");
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
   function get_rowByCobroPorWhatapp($companyID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		
		$sql = "";
		$sql = "
			select 
				res.customerNumber,
				res.phoneNumber,
				res.firstName,
				res.lastName , 				
				res.simbol , 
				res.fechaPrometidaPago,
				SUM(res.total) AS total,
				GROUP_CONCAT(DISTINCT res.documentNumber ORDER BY res.documentNumber SEPARATOR ', ') AS documentos,
				GROUP_CONCAT(DISTINCT res.note ORDER BY res.note SEPARATOR ', ') AS notas
			from 
				(
					select 
							c.customerNumber,
							c.phoneNumber,
							nat.firstName,
							nat.lastName , 
							ccd.documentNumber,
							tm.note,
							cur.simbol , 
							cca.remaining as total ,
							(
									select 
											ot.nextVisit 
									from 
										tb_transaction_master ot 
									where 
										ot.entityID = ccd.entityID and 
										ot.isActive = 1 and 
										ot.transactionID = 35 /*siguiente visita */ 
									order by 
										ot.transactionMasterID desc 
									limit 1 
							) as fechaPrometidaPago 
						from 
							tb_customer_credit_amoritization cca 
							inner join tb_customer_credit_document ccd on 
								ccd.customerCreditDocumentID = cca.customerCreditDocumentID
							inner join tb_transaction_master tm on 
								tm.transactionNumber = ccd.documentNumber and 
								tm.isActive = 1 
							inner join tb_customer c on 
								c.entityID = ccd.entityID 
							inner join tb_naturales nat on 
								nat.entityID = c.entityID 
							inner join tb_currency cur on 
								cur.currencyID = ccd.currencyID  
						where 
							cca.remaining > 0 and 
							cca.dateApply < CURDATE() and 
							cca.statusID in (78 /*registrado*/) and 
							ccd.statusID in (77 /*registrado*/ ) and 
							c.allowWhatsappCollection in  (1 /*cobro por whatapp*/  )  and 
							cca.isActive = 1 and 
							ccd.isActive = 1  and 
							/*
							validar si se debe enviar o no los mensajes
							si el dia del mes esta entre 1 - 31 
							y el dia de la semana no es sabado ni domingo
							y el dia del mes es modulo 2 es decir cada dos dias
							enviar el mensaje 
							*/
							(
								DAY(DATE_SUB(NOW(), INTERVAL 6 HOUR)) BETWEEN 1 AND 31
								AND WEEKDAY(DATE_SUB(NOW(), INTERVAL 6 HOUR)) BETWEEN 0 AND 4  -- 0=Lunes, 4=Viernes
								/*
								AND DAY(DATE_SUB(NOW(), INTERVAL 6 HOUR)) % 2 = 0
								*/ 
							)
							
				) res 
			group by 
				res.customerNumber,
				res.phoneNumber,
				res.firstName,
				res.lastName , 				
				res.simbol , 
				res.fechaPrometidaPago
			HAVING 
				fechaPrometidaPago IS NULL OR fechaPrometidaPago <= CURDATE()
			ORDER BY 
				res.firstName ; 
		";
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByCobroPorGymJalapa($companyID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		
		$sql = "";
		$sql = "
			select 
				res.customerNumber,
				res.identification as phoneNumber,
				res.firstName,
				res.lastName , 				
				res.simbol , 
				res.fechaPrometidaPago,
				SUM(res.total) AS total,
				GROUP_CONCAT(DISTINCT res.documentNumber ORDER BY res.documentNumber SEPARATOR ', ') AS documentos,
				GROUP_CONCAT(DISTINCT res.note ORDER BY res.note SEPARATOR ', ') AS notas
			from 
				(
					select 
							c.customerNumber,
							c.identification,
							c.phoneNumber,
							nat.firstName,
							nat.lastName , 
							ccd.documentNumber,
							tm.note,
							cur.simbol , 
							cca.remaining as total ,
							(
									select 
											ot.nextVisit 
									from 
										tb_transaction_master ot 
									where 
										ot.entityID = ccd.entityID and 
										ot.isActive = 1 and 
										ot.transactionID = 35 /*siguiente visita */ 
									order by 
										ot.transactionMasterID desc 
									limit 1 
							) as fechaPrometidaPago 
						from 
							tb_customer_credit_amoritization cca 
							inner join tb_customer_credit_document ccd on 
								ccd.customerCreditDocumentID = cca.customerCreditDocumentID
							inner join tb_transaction_master tm on 
								tm.transactionNumber = ccd.documentNumber and 
								tm.isActive = 1 
							inner join tb_customer c on 
								c.entityID = ccd.entityID 
							inner join tb_naturales nat on 
								nat.entityID = c.entityID 
							inner join tb_currency cur on 
								cur.currencyID = ccd.currencyID  
						where 
							cca.remaining > 0 and 
							cca.dateApply < CURDATE() and 
							cca.statusID in (78 /*registrado*/) and 
							ccd.statusID in (77 /*registrado*/ ) and 
							cca.isActive = 1 and 
							ccd.isActive = 1  and 
							/*
							validar si se debe enviar o no los mensajes
							si el dia del mes esta entre 1 - 31 
							y el dia de la semana no es sabado ni domingo
							y el dia del mes es modulo 2 es decir cada dos dias
							enviar el mensaje 
							*/
							(
								DAY(DATE_SUB(NOW(), INTERVAL 6 HOUR)) BETWEEN 1 AND 31
								AND WEEKDAY(DATE_SUB(NOW(), INTERVAL 6 HOUR)) BETWEEN 0 AND 4  -- 0=Lunes, 4=Viernes
								/*
								AND DAY(DATE_SUB(NOW(), INTERVAL 6 HOUR)) % 2 = 0
								*/ 
							)
							
				) res 
			group by 
				res.customerNumber,
				res.phoneNumber,
				res.firstName,
				res.lastName , 				
				res.simbol , 
				res.fechaPrometidaPago
			HAVING 
				fechaPrometidaPago IS NULL OR fechaPrometidaPago <= CURDATE()
			ORDER BY 
				res.firstName ; 
		";
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
  
   
   
}
?>