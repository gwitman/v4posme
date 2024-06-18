select 
	*
from 
	(
		select 
			distinct 
			c.customerNumber,
			nat.firstName,
			IFNULL(
				(
				SELECT 
					COUNT(u.creditAmortizationID) as i 
				FROM 
					tb_customer_credit_amoritization u 
				WHERE 
					u.customerCreditDocumentID = cd.customerCreditDocumentID and 
					u.statusID in (78 /*registrado*/)
				) 
				,
				0
			) as AbonoPendientes
		from 
			tb_customer c 
			inner join tb_naturales nat on 
				nat.entityID = c.entityID 
			inner join tb_customer_credit_document cd on 
				cd.entityID = c.entityID 
	) ku 
where 
	ku.AbonoPendientes = 0  and 
	ku.customerNumber not in (



			select 
				tu.customerNumber
			from 
				(
					select 
						distinct 
						c.customerNumber,
						nat.firstName,
						IFNULL(
							(
							SELECT 
								COUNT(u.creditAmortizationID) as i 
							FROM 
								tb_customer_credit_amoritization u 
							WHERE 
								u.customerCreditDocumentID = cd.customerCreditDocumentID and 
								u.statusID in (78 /*registrado*/)
							) 
							,
							0
						) as AbonoPendientes
					from 
						tb_customer c 
						inner join tb_naturales nat on 
							nat.entityID = c.entityID 
						inner join tb_customer_credit_document cd on 
							cd.entityID = c.entityID 
				) tu
			where 
				tu.AbonoPendientes > 0 
			order by 
				tu.customerNumber







	
	)
order by 
	ku.customerNumber ;
	
	
	
