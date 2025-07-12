DROP PROCEDURE IF EXISTS pr_accounting_account_balance ; 

CREATE DEFINER=CURRENT_USER PROCEDURE `pr_accounting_account_balance`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT)
	MODIFIES SQL DATA
	SQL SECURITY INVOKER
	COMMENT 'Procedimiento para obtener el tb_catalog de Cuenta de un Ciclo y sus Saldo'
BEGIN
	SELECT
		a.companyID,
		a.accountID,
		a.parentAccountID,
		a.accountNumber,
		a.name,
		a.isOperative,
		a.statusID,
		at.accountTypeID,
		at.naturaleza,
		ab.balance as balanceStart,
		ab.debit,
		ab.credit,		
		if(at.naturaleza = 'D',ab.balance + (ab.debit - ab.credit),ab.balance + (ab.credit - ab.debit)) as balanceEnd
	FROM 
		tb_accounting_balance ab 
		inner join tb_account a on
			ab.accountID = a.accountID and 
			ab.companyID = a.companyID
		inner join tb_account_type at on
			a.accountTypeID = at.accountTypeID and 
			a.companyID = at.companyID 
		inner join tb_accounting_cycle cc on 
			ab.componentCycleID = cc.componentCycleID and 
			ab.companyID = cc.companyID 
		inner join tb_accounting_period cp on 
			ab.componentPeriodID = cp.componentPeriodID and 
			ab.companyID = cp.companyID 
	WHERE			
		ab.isActive = 1 and 
		cp.isActive = 1 and 
		cc.isActive = 1 and 
		a.companyID = prCompanyID and 
		cp.componentPeriodID  = prPeriodID and 
		cc.componentCycleID   = prCycleID 
	ORDER BY
		a.accountNumber ; 
	
END 


DROP FUNCTION IF EXISTS fn_insertar_string_n ; 

CREATE DEFINER=CURRENT_USER FUNCTION `fn_insertar_string_n`(texto LONGTEXT,
    marcador VARCHAR(50),
    n INT
) RETURNS longtext CHARSET utf8mb4 COLLATE utf8mb4_general_ci
    DETERMINISTIC
BEGIN
    DECLARE resultado LONGTEXT DEFAULT '';
    DECLARE inicio INT DEFAULT 1;
    DECLARE longitud INT;

    SET longitud = CHAR_LENGTH(texto);

    WHILE inicio <= longitud DO
        SET resultado = CONCAT(resultado, SUBSTRING(texto, inicio, n), marcador);
        SET inicio = inicio + n;
    END WHILE;

    RETURN resultado;
END