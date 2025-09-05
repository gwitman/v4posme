-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: posme
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;



--
-- Temporary table structure for view `vw_contabilidad_comprobantes`
--

DROP TABLE IF EXISTS `vw_contabilidad_comprobantes`;
/*!50001 DROP VIEW IF EXISTS `vw_contabilidad_comprobantes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_contabilidad_comprobantes` AS SELECT
 1 AS `CodigoComprobante`,
  1 AS `FechaComprobante`,
  1 AS `TipoCambioComprobante`,
  1 AS `EstadoComprobante`,
  1 AS `DebitoComprobante`,
  1 AS `CrditoComprobante`,
  1 AS `TipoComprobante`,
  1 AS `MonedaComprobante`,
  1 AS `CentroCostoCuenta`,
  1 AS `CodigoCuenta`,
  1 AS `NombreCuenta`,
  1 AS `DebitoCuenta`,
  1 AS `CreditoCuenta`,
  1 AS `TipoCuenta`,
  1 AS `BeneficiarioComprobante`,
  1 AS `NotaComprobante` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_cxc_customer_list_real_estate`
--

DROP TABLE IF EXISTS `vw_cxc_customer_list_real_estate`;
/*!50001 DROP VIEW IF EXISTS `vw_cxc_customer_list_real_estate`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_cxc_customer_list_real_estate` AS SELECT
 1 AS `entityID`,
  1 AS `Codigo`,
  1 AS `Contacto`,
  1 AS `Modificacion`,
  1 AS `Cliente`,
  1 AS `Sexo`,
  1 AS `Email`,
  1 AS `Estado`,
  1 AS `Clasificacion`,
  1 AS `Categoria`,
  1 AS `Presupuesto`,
  1 AS `Telefono`,
  1 AS `Ubicacion Interes`,
  1 AS `Agente`,
  1 AS `Encuentra 24`,
  1 AS `Mensaje`,
  1 AS `Comentario 1`,
  1 AS `Comentario 2`,
  1 AS `Ubicacion`,
  1 AS `Forma de contacto` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gerencia_balance`
--

DROP TABLE IF EXISTS `vw_gerencia_balance`;
/*!50001 DROP VIEW IF EXISTS `vw_gerencia_balance`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gerencia_balance` AS SELECT
 1 AS `CentroCosto`,
  1 AS `Cuenta`,
  1 AS `Ano`,
  1 AS `Mes`,
  1 AS `MesOnly`,
  1 AS `C$saldoInicial`,
  1 AS `C$saldoFinal`,
  1 AS `C$saldoMensual` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gerencia_customer`
--

DROP TABLE IF EXISTS `vw_gerencia_customer`;
/*!50001 DROP VIEW IF EXISTS `vw_gerencia_customer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gerencia_customer` AS SELECT
 1 AS `customerNumber`,
  1 AS `firstName`,
  1 AS `identification`,
  1 AS `birthDate` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gerencia_desembolsos_detalle`
--

DROP TABLE IF EXISTS `vw_gerencia_desembolsos_detalle`;
/*!50001 DROP VIEW IF EXISTS `vw_gerencia_desembolsos_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gerencia_desembolsos_detalle` AS SELECT
 1 AS `Colaborador`,
  1 AS `NombreColaborador`,
  1 AS `Cliente`,
  1 AS `NombreCliente`,
  1 AS `Factura`,
  1 AS `creditAmortizationID`,
  1 AS `FechaCuota`,
  1 AS `AnoCuota`,
  1 AS `Mes1Cuota`,
  1 AS `Mes2Cuota`,
  1 AS `C$BalanceStartCuota`,
  1 AS `C$InteresCuota`,
  1 AS `C$CapitalCuota`,
  1 AS `C$BalanceEndCuota`,
  1 AS `C$ShareCuota`,
  1 AS `C$RemainingCuota`,
  1 AS `C$shareCapital`,
  1 AS `EstadoCuota`,
  1 AS `diasAtrazoCuota`,
  1 AS `Moneda`,
  1 AS `TipoCambioActual`,
  1 AS `C$CapitalPagado`,
  1 AS `C$CapitalPendiente`,
  1 AS `C$IntaresPagado`,
  1 AS `C$InteresPendiente` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gerencia_desembolsos_resumen`
--

DROP TABLE IF EXISTS `vw_gerencia_desembolsos_resumen`;
/*!50001 DROP VIEW IF EXISTS `vw_gerencia_desembolsos_resumen`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gerencia_desembolsos_resumen` AS SELECT
 1 AS `CodigoCliente`,
  1 AS `Nombre`,
  1 AS `Moneda`,
  1 AS `Edad`,
  1 AS `C$Monto`,
  1 AS `C$Balance`,
  1 AS `C$Provisionado`,
  1 AS `Estado`,
  1 AS `Interes`,
  1 AS `Plazo`,
  1 AS `TipoCambio`,
  1 AS `Fecha`,
  1 AS `TipoAmortizacion`,
  1 AS `PeriodoPago`,
  1 AS `Anio`,
  1 AS `Mes`,
  1 AS `MesUnicamente`,
  1 AS `Factura` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gerencia_estado_resultado_001`
--

DROP TABLE IF EXISTS `vw_gerencia_estado_resultado_001`;
/*!50001 DROP VIEW IF EXISTS `vw_gerencia_estado_resultado_001`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gerencia_estado_resultado_001` AS SELECT
 1 AS `Cuenta`,
  1 AS `Ano`,
  1 AS `Mes`,
  1 AS `MesOnly`,
  1 AS `C$saldoInicial`,
  1 AS `C$saldoFinal`,
  1 AS `C$saldoMensual` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gerencia_estado_resultado_002`
--

DROP TABLE IF EXISTS `vw_gerencia_estado_resultado_002`;
/*!50001 DROP VIEW IF EXISTS `vw_gerencia_estado_resultado_002`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gerencia_estado_resultado_002` AS SELECT
 1 AS `Ano`,
  1 AS `Mes`,
  1 AS `MesOnly`,
  1 AS `C$saldoInicial`,
  1 AS `C$saldoFinal`,
  1 AS `C$saldoMensual` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_inventory_list_item_real_estate`
--

DROP TABLE IF EXISTS `vw_inventory_list_item_real_estate`;
/*!50001 DROP VIEW IF EXISTS `vw_inventory_list_item_real_estate`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_inventory_list_item_real_estate` AS SELECT
 1 AS `Codigo interno`,
  1 AS `itemID`,
  1 AS `createdOn`,
  1 AS `Codigo`,
  1 AS `Nombre`,
  1 AS `Pagina Web Url`,
  1 AS `Pagina Web`,
  1 AS `Amueblado`,
  1 AS `Aires`,
  1 AS `Niveles`,
  1 AS `Hora de visita`,
  1 AS `Baños`,
  1 AS `Habitaciones`,
  1 AS `Diseño de propiedad`,
  1 AS `Tipo de casa`,
  1 AS `Proposito`,
  1 AS `Moneda`,
  1 AS `Fecha de enlistamiento`,
  1 AS `Fecha de actualizacion`,
  1 AS `Precio Venta`,
  1 AS `Precio Renta`,
  1 AS `Disponible`,
  1 AS `Area de contruccion M2`,
  1 AS `Area de terreno V2`,
  1 AS `ID Encuentra 24`,
  1 AS `Baño de servicio`,
  1 AS `Baño de visita`,
  1 AS `Cuarto de servicio`,
  1 AS `Walk in closet`,
  1 AS `Piscina privada`,
  1 AS `Area club con piscina`,
  1 AS `Acepta mascota`,
  1 AS `Corretaje`,
  1 AS `Plan de referido`,
  1 AS `Link Youtube Url`,
  1 AS `Link Youtube`,
  1 AS `Pagina Web Link Url`,
  1 AS `Pagina Web Link`,
  1 AS `Foto Url`,
  1 AS `Foto`,
  1 AS `Google Url`,
  1 AS `Google`,
  1 AS `Otros Link Url`,
  1 AS `Otros Link`,
  1 AS `Estilo de cocina`,
  1 AS `Agente`,
  1 AS `Zona`,
  1 AS `Condominio`,
  1 AS `Ubicacion`,
  1 AS `Exclusividad de agente`,
  1 AS `Pais`,
  1 AS `Estado`,
  1 AS `Ciudad`,
  1 AS `Telefono`,
  1 AS `isActive` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_sales_inventory`
--

DROP TABLE IF EXISTS `vw_sales_inventory`;
/*!50001 DROP VIEW IF EXISTS `vw_sales_inventory`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_sales_inventory` AS SELECT
 1 AS `createdOn`,
  1 AS `createdOnDay`,
  1 AS `currency`,
  1 AS `tipo`,
  1 AS `causal`,
  1 AS `transactionNumber`,
  1 AS `statusName`,
  1 AS `companiaName`,
  1 AS `warehouseName`,
  1 AS `customerNumber`,
  1 AS `firstName`,
  1 AS `itemNumber`,
  1 AS `name`,
  1 AS `categoryName`,
  1 AS `tipoCambio`,
  1 AS `quantity`,
  1 AS `unitaryCost`,
  1 AS `cost`,
  1 AS `unitaryAmount`,
  1 AS `amount`,
  1 AS `utility` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_sin_riesgo_reporte_clientes`
--

DROP TABLE IF EXISTS `vw_sin_riesgo_reporte_clientes`;
/*!50001 DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_clientes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_sin_riesgo_reporte_clientes` AS SELECT
 1 AS `FECHA REPORTE`,
  1 AS `IDENTIFICACION`,
  1 AS `TIPO DE PERSONA`,
  1 AS `NACIONALIDAD`,
  1 AS `SEXO`,
  1 AS `FECHA DE NACIMIENTO`,
  1 AS `ESTADO CIVIL`,
  1 AS `DIRECCION`,
  1 AS `DEPARTAMENTO`,
  1 AS `MUNICIPIO`,
  1 AS `DIRECCION DE TRABAJO`,
  1 AS `DEPARTAMENTO DE TRABAJO`,
  1 AS `MUNICIPIO DE TRABAJO`,
  1 AS `TELEFONO DOMICILIAR`,
  1 AS `TELEFONO TRABAJO`,
  1 AS `CELULAR`,
  1 AS `CORREO ELECTRONICO`,
  1 AS `OCUPACION`,
  1 AS `ACTIVIDAD ECONOMICA`,
  1 AS `SECTOR` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_sin_riesgo_reporte_creditos`
--

DROP TABLE IF EXISTS `vw_sin_riesgo_reporte_creditos`;
/*!50001 DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_creditos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_sin_riesgo_reporte_creditos` AS SELECT
 1 AS `companyID`,
  1 AS `customerCreditDocumentID`,
  1 AS `entityID`,
  1 AS `TIPO DE ENTIDAD`,
  1 AS `NUMERO CORRELATIVO`,
  1 AS `FECHA DE REPORTE`,
  1 AS `DEPARTAMENTO`,
  1 AS `NUMERO DE CEDULA O RUC`,
  1 AS `NOMBRE DE PERSONA`,
  1 AS `TIPO DE CREDITO`,
  1 AS `FECHA DE DESEMBOLSO`,
  1 AS `TIPO DE OBLIGACION`,
  1 AS `MONTO AUTORIZADO`,
  1 AS `PLAZO`,
  1 AS `FRECUENCIA DE PAGO`,
  1 AS `SALDO DEUDA`,
  1 AS `ESTADO`,
  1 AS `MONTO VENCIDO`,
  1 AS `ANTIGUEDAD DE MORA`,
  1 AS `TIPO DE GARANTIA`,
  1 AS `FORMA DE RECUPERACION`,
  1 AS `NUMERO DE CREDITO`,
  1 AS `VALOR DE LA CUOTA` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_sin_riesgo_reporte_creditos_to_systema`
--

DROP TABLE IF EXISTS `vw_sin_riesgo_reporte_creditos_to_systema`;
/*!50001 DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_creditos_to_systema`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_sin_riesgo_reporte_creditos_to_systema` AS SELECT
 1 AS `companyID`,
  1 AS `TIPO_DE_ENTIDAD`,
  1 AS `NUMERO_CORRELATIVO`,
  1 AS `FECHA_DE_REPORTE`,
  1 AS `DEPARTAMENTO`,
  1 AS `NUMERO_DE_CEDULA_O_RUC`,
  1 AS `NOMBRE_DE_PERSONA`,
  1 AS `TIPO_DE_CREDITO`,
  1 AS `FECHA_DE_DESEMBOLSO`,
  1 AS `TIPO_DE_OBLIGACION`,
  1 AS `MONTO_AUTORIZADO`,
  1 AS `PLAZO`,
  1 AS `FRECUENCIA_DE_PAGO`,
  1 AS `SALDO_DEUDA`,
  1 AS `ESTADO`,
  1 AS `MONTO_VENCIDO`,
  1 AS `ANTIGUEDAD_DE_MORA`,
  1 AS `TIPO_DE_GARANTIA`,
  1 AS `FORMA_DE_RECUPERACION`,
  1 AS `NUMERO_DE_CREDITO`,
  1 AS `VALOR_DE_LA_CUOTA` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_transaccion_master_concept_232425`
--

DROP TABLE IF EXISTS `vw_transaccion_master_concept_232425`;
/*!50001 DROP VIEW IF EXISTS `vw_transaccion_master_concept_232425`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_transaccion_master_concept_232425` AS SELECT
 1 AS `transactionMasterID`,
  1 AS `Descripcion`,
  1 AS `Fecha`,
  1 AS `Documento`,
  1 AS `Moneda`,
  1 AS `Concepto`,
  1 AS `Valor`,
  1 AS `Componente`,
  1 AS `componentItemID`,
  1 AS `Referencia1` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'posme'
--

--
-- Dumping routines for database 'posme'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fn_calculate_exchange_rate` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_calculate_exchange_rate`(`prCompanyID` INT, `prDate` DATE, `prCurrencySourceID` INT, `prCurrencyTargetID` INT, `prValorToConvert` DECIMAL(21,11)) RETURNS decimal(10,4)
BEGIN

	DECLARE currencyIDDefault INT DEFAULT 0;

	DECLARE currencyIDSource  INT DEFAULT 0;

	DECLARE currencyIDTarget  INT DEFAULT 0;

	DECLARE ratio_1 DECIMAL(18,8) DEFAULT 1; 

	DECLARE ratio_2 DECIMAL(18,8) DEFAULT 1;

  

	

	SET currencyIDDefault = (

		SELECT c.currencyID	

		FROM tb_parameter p 

		INNER JOIN tb_company_parameter cp ON p.parameterID = cp.parameterID 

		INNER JOIN tb_currency c ON  cp.value = c.name 

		WHERE p.name = 'ACCOUNTING_CURRENCY_NAME_FUNCTION' and c.isActive = 1 and cp.companyID = prCompanyID LIMIT 1 

	);

	SET currencyIDSource  = prCurrencySourceID;

	SET currencyIDTarget  = prCurrencyTargetID;	



	

	IF currencyIDSource != currencyIDTarget THEN

		SET ratio_1 = (

				SELECT ratio 

				FROM tb_exchange_rate e 

				where 

					e.companyID = prCompanyID and 

					e.currencyID = currencyIDSource and 

					e.targetCurrencyID = currencyIDTarget AND 

					e.`date` =  prDate

			);

	END IF;

	



	  

	

	IF currencyIDSource = currencyIDTarget THEN		

		RETURN ROUND((1 * prValorToConvert),2);

	ELSEIF currencyIDSource = 1 THEN 

		

		RETURN ROUND((prValorToConvert * ratio_1) ,2) ; 

	ELSE  

		

		RETURN ROUND((prValorToConvert * ratio_1),2) ; 

	END IF;

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fn_get_access_ready` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_get_access_ready`(`prCompanyID` INT, `prUserID` INT, `prElementID` INT, `prRowCreatedBy` INT, `prRowCreatedAt` INT) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Obtener el nivel de acceso de un usuario'
BEGIN

	

	

	

	DECLARE varAcceso INT DEFAULT 0;

	DECLARE varIsAdmin INT DEFAULT 0;

	DECLARE varBranch INT DEFAULT 0;

		

	

	SET varBranch = (

	SELECT 

		p.branchID  

	FROM 

		tb_user u

		inner join tb_membership p on 

			u.userID = p.userID 

		inner join tb_role r on 

			p.roleID = r.roleID 

	where

 		u.userID = prUserID and 

		u.companyID =  prCompanyID

	limit 1 

	);

	

	

	SET varIsAdmin = (

	SELECT 

		r.isAdmin 

	FROM 

		tb_user u

		inner join tb_membership p on 

			u.userID = p.userID 

		inner join tb_role r on 

			p.roleID = r.roleID 

	where

 		u.userID = prUserID and 

		u.companyID =  prCompanyID

	limit 1 

	);

	

	

	SET varAcceso = (

	SELECT 

		per.selected 

	FROM 

		tb_user u

		inner join tb_membership p on 

			u.userID = p.userID 

		inner join tb_role r on 

			p.roleID = r.roleID 

		inner join tb_user_permission per on 

			r.roleID = per.roleID and 

			r.companyID = per.companyID 

		inner join tb_element el on 

			per.elementID = el.elementID 

	WHERE  

		u.userID = prUserID and 

		u.companyID =  prCompanyID and 

		per.elementID  = prElementID

	LIMIT 1

	) ;

		

	

	





	

	IF varIsAdmin = 1 THEN 

		RETURN 1;

	END IF;

	

	

	IF (varAcceso IS NULL) OR (varAcceso = -1) THEN 

		RETURN 0;

	

	ELSEIF  (varAcceso = 0 ) THEN 

		RETURN  1;

	

	ELSEIF  (varAcceso = 1 ) THEN 

		IF (prRowCreatedAt = varBranch) THEN 

			RETURN 1;

		ELSE 

			RETURN 0;

		END IF;

	

	ELSEIF  (varAcceso = 2 ) THEN 

		IF (prRowCreatedBy = prUserID) THEN 

			RETURN 1;

		ELSE 

			RETURN 0;

		END IF;

	

	ELSE

		RETURN 0;		

	END IF;

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fn_get_provider_id` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_get_provider_id`(`prCompanyID` INT, `prUserID` INT) RETURNS int(11)
BEGIN

	DECLARE varProviderID INT DEFAULT 0;

	

	SET varProviderID = (

	SELECT 

		u.employeeID as providerID    

	FROM 

		tb_user u

		inner join tb_membership p on 

			u.userID = p.userID 

		inner join tb_role r on 

			p.roleID = r.roleID

		inner join tb_provider pro on 

			pro.entityID = u.employeeID 

	where

		u.userID = prUserID and 

		u.companyID = prCompanyID and 

		u.isActive = 1 and 

		r.name = 'INVERSIONISTA' LIMIT 1);

		

	IF (varProviderID IS NULL) OR (varProviderID = 0) THEN 

		RETURN 0;

	ELSE

		RETURN varProviderID;		

	END IF;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fn_insertar_string_n` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_insertar_string_n`(texto LONGTEXT,

    marcador VARCHAR(50),

    n INT) RETURNS longtext CHARSET utf8mb4 COLLATE utf8mb4_general_ci
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

END ;;
DELIMITER ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fn_translate_transaction_master_info_amounts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_translate_transaction_master_info_amounts`( 
	`prCompanyID` INT, 
	`prFlavorID` INT, 
	`prTransactionID` INT,
	`prCurrencyFunction` VARCHAR(50), -- moneda que queremos 
	`prCurrencyReport` VARCHAR(50), -- no usado en la lógica actual 
	`prCurrencyReportConvert` VARCHAR(50), -- moneda final para el reporte ('Cordoba', 'Dolar', 'None') 
	`prTransactionCurrencyID` INT, -- 1 = Córdoba, 2 = Dólar 
	`prExchangeRate` DECIMAL(18,8), -- tasa USD/C$
	`prTransactionAmount` DECIMAL(18,6), -- amount 
	`prTransactionAmountExt` DECIMAL(18,6),-- amountExt
	`prField` VARCHAR(30) -- 'Amount' o 'AmountExt', 'CurrencyName', Convert indica cuál esperamos recibir 
) 
RETURNS VARCHAR(50)
DETERMINISTIC
READS SQL DATA 
BEGIN 
	DECLARE vTransCurrency VARCHAR(10); 
	DECLARE vFuncCurrency VARCHAR(10); 
	DECLARE vConvCurrency VARCHAR(10);
	DECLARE vBaseAmount DECIMAL(18,6); 
	DECLARE vResult DECIMAL(18,6);
	 -- Solo para transactionID = 19 
	-- IF prTransactionID <> 19 THEN 
	-- RETURN NULL; 
	-- END IF; 
	SET vFuncCurrency 								= UPPER(TRIM(prCurrencyFunction)); 
	SET vConvCurrency 								= UPPER(TRIM(prCurrencyReportConvert)); 
	-- Moneda de la transacción y asignación de campos 
	IF prTransactionCurrencyID 				= 1 THEN 
		SET vTransCurrency 							= 'CORDOBA'; 
	ELSEIF prTransactionCurrencyID 		= 2 THEN 
		SET vTransCurrency 							= 'DOLAR'; 
	ELSE 
		RETURN NULL; 
	END IF; 
	-- 1) Selección según el campo que esperamos 
	IF UPPER(TRIM(prField)) 					= 'CURRENCYNAME' THEN
		IF vConvCurrency 								= 'NONE' THEN
			RETURN CONCAT(UCASE(LEFT(vTransCurrency, 1)), 
                             LCASE(SUBSTRING(vTransCurrency, 2)));
		ELSE
			RETURN CONCAT(UCASE(LEFT(vConvCurrency, 1)), 
                             LCASE(SUBSTRING(vConvCurrency, 2)));
		END IF;
	ELSEIF UPPER(TRIM(prField)) 			= 'AMOUNT' THEN 
		SET vBaseAmount 								= prTransactionAmount;
	ELSEIF UPPER(TRIM(prField)) 			= 'AMOUNTEXT' THEN 
		SET vBaseAmount									= prTransactionAmountExt; 
	ELSEIF UPPER(TRIM(prField)) 			= 'CONVERT' THEN 
		SET vBaseAmount 								= prTransactionAmount; 
	ELSE 
		RETURN NULL; -- campo inválido 
	END IF; 
	-- 2) Inversión si la moneda de función ≠ moneda de transacción 
	IF vFuncCurrency 									<> vTransCurrency THEN 
		IF UPPER(TRIM(prField)) 				= 'AMOUNT' THEN 
			SET vBaseAmount 							= prTransactionAmountExt; 
		ELSE 
			SET vBaseAmount 							= prTransactionAmount; 
		END IF; 
	END IF; 
	-- 3) Conversión final solo si prCurrencyReportConvert <> 'NONE' 
	IF UPPER(TRIM(prField)) 						= 'CONVERT' THEN 
		IF vConvCurrency 									= 'NONE' THEN 
			SET vResult 										= vBaseAmount;
		ELSEIF vConvCurrency 							= vTransCurrency THEN
			SET vResult 										= vBaseAmount;
		ELSE 
			IF vFuncCurrency 								= 'CORDOBA' 
			AND vConvCurrency 							= 'DOLAR' THEN 
				IF prExchangeRate IS NULL 
				OR prExchangeRate 						= 0 THEN 
					RETURN NULL; 
				END IF; 
				SET vResult 									= vBaseAmount * prExchangeRate; 
			ELSEIF vFuncCurrency						= 'DOLAR' 
			AND vConvCurrency 							= 'CORDOBA' THEN 
				IF prExchangeRate IS NULL 
				OR prExchangeRate 						= 0 THEN 
					RETURN NULL; 
				END IF; 
				SET vResult 									= vBaseAmount / prExchangeRate; 
			ELSE -- mismas monedas → no cambiar 
				IF vFuncCurrency 							<> vTransCurrency THEN
					IF vFuncCurrency 						= 'CORDOBA' 
					AND vTransCurrency 					= 'DOLAR' THEN 
						IF prExchangeRate IS NULL 
						OR prExchangeRate 				= 0 THEN 
							RETURN NULL; 
						END IF; 
						SET vResult 							= vBaseAmount / prExchangeRate; 
					ELSEIF vFuncCurrency 				= 'DOLAR' 
					AND vTransCurrency 					= 'CORDOBA' THEN 
						IF prExchangeRate IS NULL 
						OR prExchangeRate 				= 0 THEN 
							RETURN NULL; 
						END IF; 
						SET vResult 							= vBaseAmount * prExchangeRate; 
					END IF;
				ELSE 
					SET vResult 								= vBaseAmount; 
				END IF;
			END IF; 
		END IF; 
		RETURN ROUND(vResult, 4);
	ELSE  RETURN ROUND(vBaseAmount, 4);
	END IF;
END ;; 

DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_account_balance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_account_balance`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT)
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

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_calculate_utility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_calculate_utility`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prUtility` DECIMAL(18,8))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Calcular Utilidades del Ciclo'
BEGIN	

	DECLARE variableStart_ VARCHAR(1) DEFAULT '[';

	DECLARE variableEnd_ VARCHAR(1) DEFAULT ']';

	DECLARE expreStart_ VARCHAR(1) DEFAULT '(';

	DECLARE expreEnd_ VARCHAR(1) DEFAULT ')';

	DECLARE formulaUtitlity_ VARCHAR(250) DEFAULT '';

	DECLARE accountID_ INT;

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE balanceEnd_ DECIMAL(18,8);	

	DECLARE first_ INT;

	DECLARE last_ INT;

	DECLARE dif_ INT;	

	SET @parameterName_ 	= 'ACCOUNTING_FORMULATE_OF_UTILITY';

	SET @utilityResult 		= '';

	SET @query 				= '';

	

   

	

	SET	formulaUtitlity_ = (

		SELECT 

			cp.`value` 

		FROM 

			`tb_parameter` p 

			INNER JOIN tb_company_parameter cp 

				on p.parameterID = cp.parameterID  

		WHERE 

			cp.companyID = prCompanyID and 

			p.name = @parameterName_

		LIMIT 1

	);	  



	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(

		prCompanyID,prBranchID,prLoginID,prTocken,

		'pr_accounting_calculate_utility',1,'iniciando while',CURRENT_TIMESTAMP()

	);

		

	WHILE LOCATE(variableStart_,formulaUtitlity_) > 0 DO

		SET first_ 				= LOCATE(variableStart_,formulaUtitlity_);

		SET last_  				= LOCATE(variableEnd_,formulaUtitlity_);

		SET dif_ 				= last_ - first_;

		SET accountNumber_ 		= SUBSTRING(formulaUtitlity_,first_ + 1 , dif_ - 1);		

		

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_calculate_utility',

			1,concat('while ',accountNumber_),CURRENT_TIMESTAMP());

		

		SET accountID_  		   = (

			SELECT accountID 

			FROM 

				tb_account where companyID = prCompanyID and isActive = 1 and accountNumber = accountNumber_ 

			LIMIT 1

		);		

		SET accountID_ 				= IFNULL(accountID_,0);

		

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,

			'pr_accounting_calculate_utility',1,

			concat('while acountID',accountID_),CURRENT_TIMESTAMP()

		);

		

		SET balanceEnd_ 		= (

				SELECT balanceEnd 

				from  

					tb_accounting_balance_temp where companyID = prCompanyID and 

					branchID = prBranchID and loginID = prLoginID and 

					accountID = accountID_ LIMIT 1);

					

		SET balanceEnd_			= IF(balanceEnd_ IS NULL,0,balanceEnd_);

		

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,

			prTocken,'pr_accounting_calculate_utility',1,

			concat('while acountID balanceEnd ',ROUND(balanceEnd_,2)),

			CURRENT_TIMESTAMP()

		);

		

		SET formulaUtitlity_  	= REPLACE(

			formulaUtitlity_,CONCAT(variableStart_,accountNumber_,variableEnd_),

			balanceEnd_

		);		

		

	END WHILE ;	 



	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(

		prCompanyID,prBranchID,prLoginID,

		prTocken,'pr_accounting_calculate_utility',1,

		concat('fin while formula:',formulaUtitlity_),CURRENT_TIMESTAMP()

	);

		

	SET @query   		= CONCAT("SELECT ",expreStart_,formulaUtitlity_,expreEnd_," INTO @utilityResult ");

	PREPARE stmt FROM @query;  

    EXECUTE stmt;

    DEALLOCATE PREPARE stmt; 



	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(

		prCompanyID,prBranchID,prLoginID,

		prTocken,'pr_accounting_calculate_utility',1,

		concat('fin while resultado:',@utilityResult),CURRENT_TIMESTAMP()

	);

	SET prUtility 		= @utilityResult;

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_checkaccount_to_delete` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_checkaccount_to_delete`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prAccountID` INT, IN `prApp` VARCHAR(50), OUT `prResultMessage` VARCHAR(300), OUT `prResultCode` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para saber si se puede eliminar una cuenta contable'
BEGIN

	declare varBalance DECIMAL(26,8) default 0;

	declare varBalanceLast DECIMAL(26,8) default 0;

	

	set varBalance = (

	select 

		SUM(IF(att.naturaleza='D',jed.debit - jed.credit,jed.credit - jed.debit)) as amount

	from

		tb_journal_entry je  

		inner join	tb_journal_entry_detail jed on

			je.journalEntryID = jed.journalEntryID 

		inner join tb_account a on 

			jed.accountID = a.accountID 

		inner join tb_account_type att on 

			a.accountTypeID = att.accountTypeID 

		inner join tb_accounting_cycle acc on 

			je.accountingCycleID = acc.componentCycleID 

	where

		je.isActive = 1 and 

		jed.isActive = 1 and 

		acc.isActive = 1 and 

		je.companyID = prCompanyID and 

		jed.accountID = prAccountID and 

		acc.endOn <= current_timestamp()

	);



	set varBalanceLast = (

	select 

		SUM(IF(att.naturaleza='D',jed.debit - jed.credit,jed.credit - jed.debit)) as amount

	from

		tb_journal_entry je  

		inner join	tb_journal_entry_detail jed on

			je.journalEntryID = jed.journalEntryID 

		inner join tb_account a on 

			jed.accountID = a.accountID 

		inner join tb_account_type att on 

			a.accountTypeID = att.accountTypeID 

		inner join tb_accounting_cycle acc on 

			je.accountingCycleID = acc.componentCycleID 

	where

		je.isActive = 1 and 

		jed.isActive = 1 and 

		acc.isActive = 1 and 

		je.companyID = prCompanyID and 

		jed.accountID = prAccountID and 

		acc.startOn > current_timestamp()

	);

	

   set varBalance = (SELECT IF(varBalance is null,0,varBalance));

   set varBalanceLast = (SELECT IF(varBalanceLast is null,0,varBalanceLast));

   

   IF varBalanceLast <> 0 THEN

   	set prResultMessage 	= 'La cuenta es usada en ciclos mayores al actual';

   	set prResultCode 		= 0;

   ELSEIF varBalance <> 0 THEN 

   	set prResultMessage 	= 'La cuenta tiene un saldo != de 0';

   	set prResultCode 		= 0;

   ELSE 

   	set prResultMessage 	= 'SUCCESS';

   	set prResultCode 		= 1;   	

   END IF;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_closed_cycle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_closed_cycle`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prCreatedIn` VARCHAR(50), IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prCodeError` INT, OUT `prMessageResult` VARCHAR(250))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Cerrar un Ciclo Contable'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 4;		

	DECLARE workflowStageClosedPeriod_ INT DEFAULT 0;

	DECLARE workflowStageClosedCycle_ INT DEFAULT 0;

	DECLARE journalTypeIDCierre_ INT DEFAULT 0;	

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE totalDebit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE totalCredit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;	

	DECLARE workflowStageInitOfJournal_ INT DEFAULT 0;	

	DECLARE oldCycleID_ INT DEFAULT 0;

	DECLARE nextCycleID_ INT DEFAULT 0;

	DECLARE nextPeriodID_ INT DEFAULT 0;

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE accountIDResult_ INT DEFAULT 0;

	DECLARE journalEntryID_ INT DEFAULT 0;

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE journalNumber_ VARCHAR(50);

	DECLARE companyName_ VARCHAR(50);

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE accountTypeResult VARCHAR(150);



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",accountTypeResult);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_JOURNALTYPE_CLOSED",journalTypeIDCierre_);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",workflowStageClosedCycle_);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",workflowStageClosedPeriod_);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

	SET companyName_ 		= (select name from tb_company where companyID = prCompanyID);	

	CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_journal_entry","statusID",workflowStageInitOfJournal_ );	

	CALL pr_core_get_next_number (prCompanyID,"tb_journal_entry",prBranchID,journalTypeIDCierre_,journalNumber_);		

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_ACUMULATE',accountNumber_);

		

	SET accountIDResult_ = (

		SELECT accountID FROM tb_account where isActive = 1 and 

		companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1

	);	

	SET accountIDResult_ = IFNULL(accountIDResult_,0);

	

	

	SET oldCycleID_ 		= (

		SELECT 

			cc.componentCycleID 

		FROM 	

			tb_accounting_cycle cc inner join 

			tb_accounting_period cp on 

			cp.companyID = cc.companyID and 	

			cp.componentID = cc.componentID and 

			cp.componentPeriodID = cc.componentPeriodID 

		WHERE 	

			cc.companyID = prCompanyID AND 	

			cc.isActive = 1 and 	

			cp.isActive = 1 and 	

			cc.componentID = componentID_ AND 	

			cc.endOn < (		

						select 			

							cc2.startOn  		

						from 			

							tb_accounting_cycle cc2 		

						where 			

							cc2.componentCycleID = prCycleID 	

			) 

		ORDER BY 	

			cc.endOn DESC LIMIT 1 

		);

			

	SET nextCycleID_  	= (

			SELECT 

				cc.componentCycleID 

			FROM 	

				tb_accounting_cycle cc 

				inner join tb_accounting_period cp on 

					cp.companyID = cc.companyID and 	

					cp.componentID = cc.componentID and 

					cp.componentPeriodID = cc.componentPeriodID 

			WHERE 	

				cc.companyID = prCompanyID AND 	

				cc.isActive = 1 and 	

				cp.isActive = 1 and 	

				cc.componentID = componentID_ AND 	

				cc.startOn > (		

					select 			

						cc2.endOn  		

					from 			

						tb_accounting_cycle cc2 		

					where 			

						cc2.componentCycleID = prCycleID 	

					) 

				ORDER BY 	

					cc.startOn ASC LIMIT 1 

				);	

		

	SET nextPeriodID_ 	= (SELECT componentPeriodID FROM tb_accounting_cycle WHERE componentCycleID = nextCycleID_);	

	SET totalDebit_ 		= (

		SELECT SUM(ab.debit) 

		from 

			tb_accounting_balance ab 

			inner join tb_account a on ab.companyID = a.companyID and ab.accountID = a.accountID  

		where 

			ab.isActive = 1 and 

			ab.companyID = prCompanyID and 

			ab.componentID = componentID_ and 

			ab.componentPeriodID = prPeriodID and 

			ab.componentCycleID = prCycleID and 

			a.isActive = 1 and a.parentAccountID IS NULL

	);

		

		

	SET totalCredit_	= (

			SELECT SUM(ab.credit) 

			from 

				tb_accounting_balance  ab 

				inner join tb_account a on 

					ab.companyID = a.companyID and ab.accountID = a.accountID 

			where 

				ab.isActive = 1 and 

				ab.companyID = prCompanyID and 

				ab.componentID = componentID_ and 

				ab.componentPeriodID = prPeriodID and ab.componentCycleID = prCycleID and 

				a.isActive = 1 and a.parentAccountID IS NULL 

	);

	

	

	IF(

		(oldCycleID_ IS NOT NULL ) AND 

		(

			(

				SELECT componentCycleID 

				FROM tb_accounting_cycle 

				where 

					componentCycleID = oldCycleID_ and statusID <>  workflowStageClosedCycle_

			) IS NOT NULL 		

		) 

	) THEN

	

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ANTERIOR DEBE DE  ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,

			'El cilo anterior debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

		

		

	END IF;	

		IF nextCycleID_ IS NULL THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'NO PUEDE CERRAR EL CICLO, NO EXISTE UN SIGUIENTE CICLO CONTABLE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,

			'pr_accounting_closed_cycle',1,

			'No puede cerrar el ciclo, no existe un siguiente ciclo contable',CURRENT_TIMESTAMP()

		);

		LEAVE LBL_PROCEDURE;

	END IF;

		

	IF (

			(prCycleID IS NOT NULL) AND 

			(

					(

							SELECT componentCycleID 

							FROM tb_accounting_cycle 

							where 

								componentCycleID = prCycleID and statusID =  workflowStageClosedCycle_

					) IS NOT NULL 

			)

	) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ACTUAL NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,

			prLoginID,prTocken,

			'pr_accounting_closed_cycle',1,'El ciclo actual no debe de estar cerrado',

			CURRENT_TIMESTAMP()

		);

		LEAVE LBL_PROCEDURE;

	END IF;

	

	IF(

		(nextCycleID_ IS NOT NULL) AND 

		(

			(

				SELECT componentCycleID FROM tb_accounting_cycle 

				where componentCycleID = nextCycleID_ and statusID =  workflowStageClosedCycle_

			) IS NOT NULL 		

		)

	) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO SIGUIENTE NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,

			prTocken,'pr_accounting_closed_cycle',1,

			'El ciclo siguiente no debe de estar cerrado',CURRENT_TIMESTAMP());

			LEAVE LBL_PROCEDURE;

	END IF;

	

	IF totalDebit_ <> totalCredit_ THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'LOS MOVIMIENTOS DE CICLO NO SON EQUIVALENTES, DEBITOS Y CREDITOS DIFIEREN EN IMPORTE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,

			prTocken,'pr_accounting_closed_cycle',1,

			'Los movimientos del ciclo no son equivalente, debitos y creditos difieren en importe',

			CURRENT_TIMESTAMP()

		);

		LEAVE LBL_PROCEDURE;

	END IF;

	

	

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

	

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID , prBranchID,prLoginID,nextPeriodID_ , nextCycleID_ ,resultTemp_); 	

	

	CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

	CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);

	

	

	IF nextPeriodID_ = prPeriodID THEN			

		UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 		= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;		

	END IF;

	

	

	IF nextPeriodID_ <> prPeriodID THEN	

		CALL `pr_accounting_mayorizate_account_tmp` (

			prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0, 0, 0, utilityValue_

		);

						

		UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 			= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;	 	

			

		INSERT INTO tb_journal_entry (

			companyID,journalNumber,journalDate,tb_exchange_rate,createdOn,

			createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,journalTypeID,

			currencyID,accountingCycleID,entryName

		)

		VALUES(

			prCompanyID,journalNumber_,CURDATE(),exchangeRate_,CURRENT_TIMESTAMP(),'::1',

			prBranchID,prLoginID,1,0,workflowStageInitOfJournal_,

			CONCAT(CAST(utilityValue_ AS DECIMAL(19,2)),'/UTILIDAD'),journalTypeIDCierre_,

			currencyID_,prCycleID,'APP-CIERRE'

		);		

		SET journalEntryID_ = LAST_INSERT_ID();

	

			

		INSERT INTO tb_journal_entry_detail (

			journalEntryID,companyID,accountID,isActive,classID,debit,credit,

			note,isApplied,branchID,tb_exchange_rate

		) 

		SELECT 

			journalEntryID_ as journalEntryID,

			prCompanyID as companyID,

			a.accountID,

			1 as isActive,

			0 as classID,

			CASE 

				WHEN att.naturaleza = 'C' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'D' and t.balanceEnd < 0 then 

					t.balanceEnd

			END as debit,

			CASE 

				WHEN att.naturaleza = 'D' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'C' and t.balanceEnd < 0 THEN 

					t.balanceEnd

			END as credit,

			'' as note,

			1 as isApplied, 

			prBranchID as branchID,

			exchangeRate_ as exchangeRate

		FROM 

			tb_accounting_balance_temp t

			inner join tb_account a on 

				t.accountID = a.accountID 

			inner join tb_account_type att on 

				a.accountTypeID = att.accountTypeID 

		WHERE

			t.companyID 	= prCompanyID AND  

			t.branchID 		= prBranchID AND 

			t.loginID 		= prLoginID AND

			a.isOperative 	= 1 and 

			t.balanceEnd 	<> 0 and 

			a.accountNumber REGEXP accountTypeResult

		ORDER BY 

			a.accountNumber;  

			 

		INSERT INTO tb_journal_entry_detail (

			journalEntryID,companyID,accountID,isActive,classID,debit,credit,note,isApplied,branchID,tb_exchange_rate

		) 

		VALUES (

			journalEntryID_,

			prCompanyID ,

			accountIDResult_ , 

			1,

			0, 

			IF(utilityValue_ < 0 , utilityValue_ , 0) ,

			IF(utilityValue_ > 0 , utilityValue_ , 0) ,

			'' ,

			1 , 

			prBranchID ,

			exchangeRate_ );

			

				

		UPDATE tb_accounting_balance,tb_account 

			set tb_accounting_balance.balance = 0 

		where

			tb_accounting_balance.companyID 			= tb_account.companyID and 

			tb_accounting_balance.accountID 			= tb_account.accountID and 

			tb_accounting_balance.companyID 			= prCompanyID and 

			tb_accounting_balance.componentPeriodID 	= nextPeriodID_ and 

			tb_accounting_balance.componentCycleID 	= nextCycleID_ AND 

			tb_accounting_balance.branchID 			= prBranchID AND 

			tb_account.accountNumber REGEXP accountTypeResult;

		

	END IF;	

		

	

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

		

	IF nextPeriodID_ <> prPeriodID THEN	

		UPDATE tb_accounting_period set statusID = workflowStageClosedPeriod_ WHERE componentPeriodID = prPeriodID;

		UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

	ELSE

		UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

	END IF;

	

	SET prCodeError 	= 0;

	SET prMessageResult = 'SUCCESS';

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',0,'Success',CURRENT_TIMESTAMP());

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_financial_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_financial_reason`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT, IN `prMonthOnly` INT, IN `prParameterName` VARCHAR(50), OUT `prResult` DECIMAL(18,5))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'procedimiento para el calculo de razones financieras'
BEGIN

	DECLARE variableStart_ VARCHAR(1) DEFAULT '[';

	DECLARE variableEnd_ VARCHAR(1) DEFAULT ']';

	DECLARE expreStart_ VARCHAR(1) DEFAULT '(';

	DECLARE expreEnd_ VARCHAR(1) DEFAULT ')';

	DECLARE formulaUtitlity_ VARCHAR(300) DEFAULT '';

	DECLARE naturaleza_ VARCHAR(5) DEFAULT '';

	DECLARE accountID_ INT;

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE balanceEnd_ DECIMAL(18,8);	

	DECLARE balanceEndMonth_ DECIMAL(18,8);	

	DECLARE first_ INT;

	DECLARE last_ INT; 

	DECLARE dif_ INT;	 

	DECLARE resultTemp_ INT DEFAULT 0; 

	SET @utilityResult 	= 0;

	SET @query 			= '';

	

		DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

		 

		CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

		SET	formulaUtitlity_ = (

		SELECT 

			cp.`value` 

		FROM 

			`tb_parameter` p 

			INNER JOIN tb_company_parameter cp 

				on p.parameterID = cp.parameterID  

		WHERE 

			cp.companyID = prCompanyID and 

			p.name = convert(prParameterName using latin1) collate latin1_general_ci

		LIMIT 1

	);	  

	





	WHILE LOCATE(variableStart_,formulaUtitlity_) > 0 DO

		SET first_ 				= LOCATE(variableStart_,formulaUtitlity_);

		SET last_  				= LOCATE(variableEnd_,formulaUtitlity_);

		SET dif_ 				= last_ - first_;

		SET accountNumber_ 		= SUBSTRING(formulaUtitlity_,first_ + 1 , dif_ - 1);		

		SET accountID_  		   = (SELECT accountID FROM tb_account where companyID = prCompanyID and isActive = 1 and accountNumber = accountNumber_ LIMIT 1);		

		SET naturaleza_			= (SELECT att.naturaleza FROM tb_account a inner join tb_account_type att on a.accountTypeID = att.accountTypeID where a.accountID = accountID_ LIMIT 1);

		SET balanceEnd_ 		   = (SELECT balanceEnd from  tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and accountID = accountID_ LIMIT 1);		

		SET balanceEnd_			= IFNULL(balanceEnd_,0); 

				

		SET balanceEndMonth_	   = (SELECT if(naturaleza_ = 'D',debit - credit, credit-debit )  from  tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and accountID = accountID_ LIMIT 1);		

		SET balanceEndMonth_		= IFNULL(balanceEndMonth_,0); 		

		SET balanceEnd_			= IF(prMonthOnly = 1 ,balanceEndMonth_,balanceEnd_);		

		SET formulaUtitlity_  	= REPLACE(formulaUtitlity_,CONCAT(variableStart_,accountNumber_,variableEnd_),balanceEnd_);				

	END WHILE ;	 





	SET @query   		= CONCAT("SELECT ",expreStart_,formulaUtitlity_,expreEnd_," INTO @utilityResult ");

	PREPARE stmt FROM @query;  

   EXECUTE stmt;

   DEALLOCATE PREPARE stmt; 

	SET prResult 		= CAST(IFNULL(@utilityResult,0) AS DECIMAL(18,5)); 

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_history_balance_by_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_history_balance_by_account`(IN `prCompanyID` INT, IN `prAccountID` INT)
    SQL SECURITY INVOKER
BEGIN	

	DECLARE numberRow INT;

	CREATE TEMPORARY TABLE tblAccountBalance (startOnPeriod DATETIME,startOnCycle DATETIME,balance decimal(19,8),debit decimal(19,8),credit decimal(19,8),naturaleza varchar(1),balanceEnd decimal(19,4));



	INSERT INTO tblAccountBalance

	SELECT 

		cp.startOn as startOnPeriod,

		cc.startOn as startOnCycle,

		ab.balance,

		ab.debit,

		ab.credit,

		at.naturaleza,

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

		a.companyID = prCompanyID and 

		a.accountID = prAccountID and 

		ab.isActive = 1

	ORDER BY

		cc.startOn DESC 

	LIMIT 0,180;



	

	SELECT COUNT(*) INTO numberRow FROM tblAccountBalance;



	IF (numberRow IS NULL) OR (numberRow = 0) THEN

		SELECT 0 as count_register;

	ELSE

		SELECT startOnPeriod,startOnCycle,balance,debit,credit,naturaleza,balanceEnd FROM tblAccountBalance;

	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_auxiliar_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_auxiliar_account`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleIDStart` INT, IN `prCycleIDEnd` INT, IN `prAccountID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener los movimientos por cuenta'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 4;

	DECLARE startOn_ DATETIME;

	DECLARE endOn_ DATETIME;

	DECLARE balanceStart_ DECIMAL(18,8) DEFAULT 0;

	DECLARE balanceEnd_ DECIMAL(18,8) DEFAULT 0;

	DECLARE debit_ DECIMAL(18,8) DEFAULT 0;

	DECLARE credit_ DECIMAL(18,8) DEFAULT 0;

	DECLARE nature_ VARCHAR(1) DEFAULT 'D';

	

	SET startOn_ = (select startOn from tb_accounting_cycle where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleIDStart);

	SET endOn_   = (select endOn from tb_accounting_cycle where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleIDEnd);

	

		SELECT 

		a.accountNumber,

		a.name,

		a.description,

		att.naturaleza,

		a.isOperative,				

		c.name as money

	FROM

		tb_account a 

		inner join tb_account_type att on 

			a.companyID = att.companyID and 

			a.accountTypeID = att.accountTypeID 		

		inner join tb_currency c on 

			a.currencyID  = c.currencyID 

	where

		a.isActive = 1 and 

		att.isActive = 1 and 

		a.companyID = prCompanyID and 

		a.accountID = prAccountID;

	

		SET nature_  = (SELECT att.naturaleza FROM tb_account a inner join tb_account_type att on a.accountTypeID = att.accountTypeID  where a.isActive = 1 and att.isActive = 1 and a.companyID = prCompanyID AND a.accountID = prAccountID LIMIT 1  );

		

		SELECT balance INTO balanceStart_  FROM tb_accounting_balance where companyID = prCompanyID and componentID = componentID_ and componentPeriodID = prPeriodID and componentCycleID = prCycleIDStart and accountID = prAccountID;

	SET balanceStart_ = IF (balanceStart_ IS NULL,0,balanceStart_);

	SELECT balanceStart_ AS balanceStart;

	

		SELECT 

		je.journalNumber,

		je.note,

		je.reference1,

		ci.name as journalType,

		je.journalDate,

		jed.debit,

		jed.credit

	FROM 

		tb_journal_entry je 	

		inner join tb_journal_entry_detail jed on 

			je.companyID = jed.companyID and 

			je.journalEntryID = jed.journalEntryID 

		inner join tb_catalog_item ci on 

			je.journalTypeID = ci.catalogItemID 

	WHERE		

		je.isActive = 1 and 	

		jed.isActive = 1 and 

		jed.accountID = prAccountID and 

		je.journalDate between startOn_ and endOn_

	ORDER BY

		je.journalEntryID ;

	

		SELECT 		

		SUM(jed.debit),

		SUM(jed.credit)  

		INTO debit_,credit_

	FROM 

		tb_journal_entry je 	

		inner join tb_journal_entry_detail jed on 

			je.companyID = jed.companyID and 

			je.journalEntryID = jed.journalEntryID 

	WHERE		

		je.isActive = 1 and 	

		jed.isActive = 1 and 

		jed.accountID = prAccountID and 

		je.journalDate between startOn_ and endOn_

	ORDER BY

		je.journalDate;

	

	SET balanceEnd_ = IF (nature_ = 'D' ,balanceStart_ + (debit_ - credit_),balanceStart_ + (credit_- debit_ ));

	SELECT balanceEnd_ AS balanceEnd;

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_auxiliar_mov_tipo_comprobantes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_auxiliar_mov_tipo_comprobantes`(IN `prCompanyID` INT, IN `prJournalTypeID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prExcludeSystem` INT, IN `prStringContainer` VARCHAR(500), IN `prClassID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener los movimientos por tipo de comprobantes'
LBL_PROCEDURE:

BEGIN

	select

		je.journalNumber,

		je.journalDate,

		ci.name as journalType,

		je.note,

		je.reference1,

		je.tb_exchange_rate,

		a.accountNumber,

		a.name as accountName, 

		jed.debit,

		jed.credit

	from

		tb_journal_entry je 

		inner join tb_journal_entry_detail jed on 

			je.companyID = jed.companyID and 

			je.journalEntryID = jed.journalEntryID 

		inner join tb_account a on 

			jed.accountID = a.accountID 

		inner join tb_catalog_item ci on 

			je.journalTypeID = ci.catalogItemID 

		left join tb_center_cost cc on 

			a.classID = cc.classID 

	where

		je.isActive = 1 and 

		jed.isActive = 1 and 

		je.companyID = prCompanyID and 

		(

			(je.journalTypeID = prJournalTypeID and prJournalTypeID <> -1 ) or 

			(prJournalTypeID = -1 ) 

		) and 

		je.journalDate between prStartOn and prEndOn and 

		(

			(0 = prExcludeSystem) or 

			(prExcludeSystem != 0 and isModule = 0 )

		) and 

		(

			(prStringContainer = '') or 

			(prStringContainer <> '' and concat(' ', je.note,' ' ) like concat('%',prStringContainer,'%')) or 

			(prStringContainer <> '' and 

				je.journalEntryID in (

					select 

						jex.journalEntryID 

					from  

						tb_journal_entry jex 

						inner join tb_journal_entry_detail jedx on 

							jex.journalEntryID = jedx.journalEntryID 

						inner join tb_account ax on 

							jedx.accountID = ax.accountID 

					where

						jex.journalDate between prStartOn and prEndOn  and 

						concat(' ',ax.name,' ') like concat('%',prStringContainer,'%')

				) 

			)

		)

		

	order by

		je.createdOn desc, je.journalNumber asc ,jed.debit,jed.credit;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_balance_general` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_balance_general`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener el Balance General de la Empresa'
LBL_PROCEDURE:

BEGIN

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE accountIDResult_ INT DEFAULT 0;	

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE accountTypeResult VARCHAR(150);

	DECLARE varAccountNumberActivo VARCHAR(20);

	DECLARE varAccountNumberPasivo VARCHAR(20);

	DECLARE varAccountNumberCapital VARCHAR(20);



	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_ACTIVO',varAccountNumberActivo);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_PASIVO',varAccountNumberPasivo);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_CAPITAL',varAccountNumberCapital);

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",accountTypeResult);	

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_PERIOD',accountNumber_);

	

	SET accountIDResult_ = (SELECT accountID FROM tb_account where isActive = 1 and companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1);		

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;	

	CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);	

	CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);	

	

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_get_report_balance_general',1,concat('utilityValue: ',utilityValue_),CURRENT_TIMESTAMP());

		

	IF utilityValue_ > 0 and  utilityValue_ is not null THEN	

		CALL `pr_accounting_mayorizate_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0 , 0  , utilityValue_  , utilityValue_);

	END IF;

	IF utilityValue_ < 0 and utilityValue_ is not null THEN	

		CALL `pr_accounting_mayorizate_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0, ABS(utilityValue_)  , 0  , utilityValue_);

	END IF; 

 

	SELECT p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd FROM tb_accounting_balance_temp p inner join tb_account a on  p.accountID = a.accountID inner join tb_account_level l on  a.accountLevelID = l.accountLevelID WHERE p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 8 and p.accountNumber REGEXP CONCAT('^' , varAccountNumberActivo)   ORDER BY p.accountNumber;

	

	SELECT p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd FROM tb_accounting_balance_temp p inner join tb_account a on  p.accountID = a.accountID inner join tb_account_level l on  a.accountLevelID = l.accountLevelID WHERE p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 8 and p.accountNumber REGEXP CONCAT('^' , varAccountNumberPasivo)   ORDER BY p.accountNumber;



	SELECT p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd FROM tb_accounting_balance_temp p inner join tb_account a on  p.accountID = a.accountID inner join tb_account_level l on  a.accountLevelID = l.accountLevelID WHERE p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 8 and p.accountNumber REGEXP CONCAT('^' , varAccountNumberCapital)   ORDER BY p.accountNumber;



   

	SELECT 

		p.balanceEnd 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on  p.accountID = a.accountID 

		inner join tb_account_level l on  a.accountLevelID = l.accountLevelID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and 

		p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 3 and p.accountNumber 

		REGEXP CONCAT('^' , varAccountNumberActivo) and a.parentAccountID is null   

	ORDER BY p.accountNumber;

	

	

	SELECT 

		SUM(p.balanceEnd) as balanceEnd 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on  p.accountID = a.accountID 

		inner join tb_account_level l on  a.accountLevelID = l.accountLevelID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber 

		NOT REGEXP accountTypeResult and l.lengthTotal <= 3 and 

		p.accountNumber REGEXP CONCAT('^(' , varAccountNumberPasivo,'|' , varAccountNumberCapital,')') and 

		a.parentAccountID is null   

	ORDER BY 

		p.accountNumber;

		



	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_balanza_de_comprobacion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_balanza_de_comprobacion`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT, IN `prClassID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener la Balanza de Comprobacion'
LBL_PROCEDURE: 

BEGIN

	CALL pr_accounting_account_balance (prCompanyID,prPeriodID,prCycleID);

	

	SELECT 

			sum(ab.credit) as debit, 

			sum(ab.debit) as credit			

	FROM 

		tb_accounting_balance ab 

		inner join tb_account a on 

			ab.accountID = a.accountID and 

			ab.companyID = a.companyID 

		left join tb_center_cost cc on 

			a.classID = cc.classID 

	where 

		ab.companyID = prCompanyID AND 

		ab.componentPeriodID = prPeriodID and   

		ab.componentCycleID = prCycleID and 

		ab.isActive = 1 and 

		a.parentAccountID IS NULL;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_cash_flow` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_cash_flow`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCycleID` INT, IN `prPeriodID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	DECLARE varAccountNumberCash VARCHAR(20);

	DECLARE varAccountNumberIngresosEgresos VARCHAR(600);

	DECLARE varJournalTypeCapital VARCHAR(10);

	DECLARE varJournalTypeDividendo VARCHAR(10);

	DECLARE varJournalTypeProvision VARCHAR(10);

	CREATE TEMPORARY TABLE TB_CASH_FLOW (accountType varchar(25),accountNumber varchar(50) ,account varchar(150),saldoInicial decimal(19,2),saldoFinal decimal(19,2));



	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_CASH',varAccountNumberCash);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_INGRESOS_EGRESOS',varAccountNumberIngresosEgresos);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_JOURNALTYPE_APORTECAPITAL',varJournalTypeCapital);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_JOURNALTYPE_DIVIDENDO',varJournalTypeDividendo);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_JOURNALTYPE_PROVISION',varJournalTypeProvision);

		

		

	insert into TB_CASH_FLOW 

	select 

		'ENTRADA' as accountType,

		concat("'",a.accountNumber) as accountNumber,

		'Inicial'  as account,

		round(ac.balance,2) as saldoInicial,

		round(

		if(

		att.naturaleza = 'D',

		(ac.balance + (ac.debit - ac.credit)),

		(ac.balance + (ac.credit - ac.debit))

		),2) as saldoFinal

	from 

		tb_account a

		inner join tb_account_type att on 

			a.accountTypeID = att.accountTypeID 

		inner join tb_accounting_balance ac on 

			ac.accountID = a.accountID 

		inner join tb_accounting_cycle acc on 

			ac.componentCycleID = acc.componentCycleID 

	where

		a.companyID = prCompanyID and 

		a.isActive 	= 1 and 

		acc.componentCycleID = prCycleID  and 

		a.accountNumber LIKE varAccountNumberCash 

	order by 

		a.accountNumber ; 

		

	insert into TB_CASH_FLOW  

	select 

		'ENTRADA' as accountType,

		"'04-XX-XX" as accountNumber,

		'Aporte al Capital' as account ,

		0 as saldoInicial,		

		if( sum( je.debit) is null ,0,  sum( je.debit) )  as saldoFinal 

	from  

		tb_journal_entry jed  

		inner join tb_journal_entry_detail je on 

			jed.journalEntryID = je.journalEntryID 

	where

		jed.companyID = prCompanyID and 

		jed.accountingCycleID = prCycleID and 

		je.debit > 0 and 

		jed.journalTypeID IN (varJournalTypeCapital);

		



		

		

	insert into TB_CASH_FLOW 

	select 

			es.accountType,

			es.accountNumber,

			es.account,

			es.saldoInicial,

			(es.saldoFinal - ifnull(prov.saldo,0)) as saldoFinal 

	from 

		(

		select 

			if(att.naturaleza = 'D','SALIDA','ENTRADA') as accountType,

			concat("'",a.accountNumber) as accountNumber,

			a.name as account,

			0 as saldoInicial,

			round(

			if(

			att.naturaleza = 'D',

			(ac.debit - ac.credit)* (-1),

			(ac.credit - ac.debit)

			),2) as saldoFinal

		from 

			tb_account a

			inner join tb_account_type att on 

				a.accountTypeID = att.accountTypeID 

			inner join tb_accounting_balance ac on 

				ac.accountID = a.accountID 

			inner join tb_accounting_cycle acc on 

				ac.componentCycleID = acc.componentCycleID 		

		where

			a.companyID = prCompanyID  and 

			a.isActive 	= 1 and 

			acc.componentCycleID = prCycleID and 

			a.accountNumber  REGEXP  varAccountNumberIngresosEgresos 		

		order by 

			4 desc,

			a.accountNumber 

		) es   

		left join (

			select 

				a.accountID,

				concat("'",a.accountNumber) as accountNumber,

				round(

				if(

					att.naturaleza = 'D',

					(SUM(jex.debit) - SUM(jex.credit))* (-1),

					(SUM(jex.credit) - SUM(jex.debit))

				),2) as saldo

			from 

				tb_journal_entry je 

				inner join tb_journal_entry_detail jex on 

					je.journalEntryID = jex.journalEntryID 

				inner join tb_account a on 

					jex.accountID = a.accountID 

				inner join tb_account_type att on 

					att.accountTypeID = a.accountTypeID 

			where 

				je.isActive = 1 and 

				je.journalTypeID = varJournalTypeProvision and 

				je.accountingCycleID = prCycleID 

			group by 

				a.accountID,a.accountNumber

		) prov on 

			es.accountNumber = prov.accountNumber ;

				

	

	insert into TB_CASH_FLOW  

	select 

		'SALIDA' as accountType,

		"'06-XX-XX" as accountNumber, 

		'Desembolso' as  account,	

		0 as saldoInicial,		

		if( sum( tm.subAmount) is null ,0,

	   sum( tm.subAmount)  * (-1))  as saldoFinal 

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			tm.statusID = ws.workflowStageID  

		inner join tb_journal_entry jed on 

			tm.journalEntryID = jed.journalEntryID

		inner join tb_accounting_cycle acc on 

			jed.accountingCycleID = acc.componentCycleID  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = 19 AND 

		tm.isActive = 1 and 

		ws.vinculable = 1 and 

		acc.componentCycleID = prCycleID ; 

		

	insert into TB_CASH_FLOW  

	select 

		'SALIDA' as accountType,

		"'06-XX-XY" as accountNumber,

		'Provisiones' as account ,

		0 as saldoInicial,		

		if( sum( je.credit) is null ,0,  sum( je.credit) )  * -1  as saldoFinal 

	from  

		tb_journal_entry jed  

		inner join tb_journal_entry_detail je on 

			jed.journalEntryID = je.journalEntryID 

		inner join tb_account a on 

			je.accountID = a.accountID 

	where

		jed.companyID = prCompanyID and 

		jed.accountingCycleID = prCycleID and 

		je.credit > 0 and 

		a.accountNumber = '02-01-01-01'; 

		

		

	insert into TB_CASH_FLOW  

	select 

		'SALIDA' as accountType,

		"'06-XX-XX" as accountNumber,

		'Pago de Dividendo' as account ,

		0 as saldoInicial,		

		(if( sum( je.debit) is null ,0,  sum( je.debit) )) * -1  as saldoFinal 

	from  

		tb_journal_entry jed 

		inner join tb_journal_entry_detail je on 

			jed.journalEntryID = je.journalEntryID 

	where

		jed.companyID = prCompanyID and 

		jed.accountingCycleID = prCycleID and 

		je.debit > 0 and 

		je.isActive = 1 and 

		jed.journalTypeID = varJournalTypeDividendo; 

		

		UPDATE TB_CASH_FLOW set 

		accountType = IF(saldoFinal < 0,'SALIDA','ENTRADA' ) 

	where 

		saldoFinal <> 0 

		and account <> 'Inicial';

	

	

	SELECT p.accountType, p.accountNumber,p.account,p.saldoInicial,p.saldoFinal FROM TB_CASH_FLOW p order by p.accountType asc,p.accountNumber; 	

	DROP TABLE TB_CASH_FLOW ; 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_catalogo_de_cuenta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_catalogo_de_cuenta`(IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener el tb_catalog de cuenta de la empresa'
LBL_PROCEDURE:

BEGIN

	select 

		a.accountNumber,

		a.name,

		a.description,

		a.isOperative,

		cc.name as money,

		al.name as nivel,

		al.lengthTotal,

		att.name as tipo,

		att.naturaleza 

	from

		tb_account a 

		inner join tb_account_type att on 

			a.companyID = att.companyID and 

			a.accountTypeID = att.accountTypeID

		inner join tb_account_level al on  

			a.companyID = al.companyID and 

			a.accountLevelID = al.accountLevelID

		inner join tb_currency cc on

			a.currencyID = cc.currencyID 

	WHERE

		a.companyID = prCompanyID and

		a.isActive = 1 and 

		att.isActive = 1 and 

		al.isActive = 1

	ORDER BY

		a.accountNumber;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_estado_resultado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_estado_resultado`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, IN `prClassID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener el Estado de Resultado de la Empresa'
LBL_PROCEDURE:

BEGIN

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE accountIDResult_ INT DEFAULT 0;	

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE varAccountNumberIngreso VARCHAR(20);

	DECLARE varAccountNumberCostos VARCHAR(20);

	DECLARE varAccountNumberGastos VARCHAR(20);

	SET @accountTypeResult = '';

	

	 





	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_INGRESO',varAccountNumberIngreso);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_COSTOS',varAccountNumberCostos);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_GASTOS',varAccountNumberGastos);

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",@accountTypeResult);	

	

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_ACUMULATE',accountNumber_);

	SET accountIDResult_ = (

		SELECT 

			accountID 

		FROM 

			tb_account 

		where isActive = 1 and companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1

	);	

	

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

		

	CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

	CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);

		

	

	SELECT 

		p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,

		p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd, 

		if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on p.accountID = a.accountID 

		inner join tb_account_level l on a.accountLevelID = l.accountLevelID 

		inner join tb_account_type att on a.accountTypeID = att.accountTypeID 

	WHERE 

		p.companyID = prCompanyID AND 

		p.branchID = prBranchID AND p.loginID = prLoginID and 

		l.lengthTotal <= 8 and p.accountNumber REGEXP @accountTypeResult and 

		p.accountNumber REGEXP CONCAT('^',varAccountNumberIngreso) and

		(

			(prClassID = 0) OR 

			( 

				prClassID <> 0 AND 

				a.classID = prClassID

			)

		)

	ORDER BY p.accountNumber;

	

	SELECT 

		p.accountID,

		p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,

		p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd, 

		if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on p.accountID = a.accountID 

		inner join tb_account_level l on a.accountLevelID = l.accountLevelID 

		inner join tb_account_type att on a.accountTypeID = att.accountTypeID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND 

		p.loginID = prLoginID and l.lengthTotal <= 8 and 

		p.accountNumber REGEXP @accountTypeResult and p.accountNumber REGEXP CONCAT('^',varAccountNumberCostos) and 

		(

			(prClassID = 0) OR 

			( 

				prClassID <> 0 AND 

				a.classID = prClassID

			)

		)

	ORDER BY 

		p.accountNumber;

		

	SELECT 

		p.accountID,

		p.parentAccountID,

		p.accountNumber,

		p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd, 

		if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on p.accountID = a.accountID 

		inner join tb_account_level l on a.accountLevelID = l.accountLevelID 

		inner join tb_account_type att on a.accountTypeID = att.accountTypeID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and l.lengthTotal <= 8 

		and p.accountNumber REGEXP @accountTypeResult and 

		p.accountNumber REGEXP CONCAT('^',varAccountNumberGastos) and 

		(

			(prClassID = 0) OR 

			( 

				prClassID <> 0 AND 

				a.classID = prClassID 

			)

		)

		

	ORDER BY 

		p.accountNumber; 

	

	

	

	

	SELECT 

		'utilidades',SUM(IFNULL(TX.balanceEnd,0)) as valor 

	FROM 

		(

			SELECT 

				p.balanceEnd as balanceEnd 

			FROM 

				tb_accounting_balance_temp p  

				inner join tb_account al on p.accountID = al.accountID 

				inner join tb_account_level l on al.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on al.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND 

				p.loginID = prLoginID 

				

				and p.accountNumber 	REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberIngreso) 

				and al.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						al.classID = prClassID

					) 

				) 

				



			union all 



			SELECT 

				p.balanceEnd * -1 as balanceEnd 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account az on p.accountID = az.accountID 

				inner join tb_account_level l on az.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on az.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberCostos) 

				and az.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						az.classID = prClassID

					) 

				) 

				

			union all 



			SELECT 

				p.balanceEnd * -1 as balanceEnd 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account ax on p.accountID = ax.accountID 

				inner join tb_account_level l on ax.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on ax.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberGastos) 

				and ax.isOperative = 1 

				and (

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						ax.classID = prClassID

					) 

				) 

				

		) TX;

				

	

	SELECT 

		SUM(IFNULL(TX.balanceMensual,0)) as valor

	FROM 

		(

			SELECT 

				al.classID,

				if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

			FROM 

				tb_accounting_balance_temp p  

				inner join tb_account al on p.accountID = al.accountID 

				inner join tb_account_level l on al.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on al.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND 

				p.loginID = prLoginID 

				

				and p.accountNumber 	REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberIngreso) 

				and al.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						al.classID = prClassID

					) 

				) 

				



			union all 



			SELECT 

				az.classID,

				if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) * -1 as balanceMensual 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account az on p.accountID = az.accountID 

				inner join tb_account_level l on az.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on az.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberCostos) 

				and az.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						az.classID = prClassID

					) 

				) 

				

			union all 



			SELECT 

				ax.classID,

				if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) * -1 as balanceMensual 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account ax on p.accountID = ax.accountID 

				inner join tb_account_level l on ax.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on ax.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberGastos) 

				and ax.isOperative = 1 

				and (

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						ax.classID = prClassID

					) 

				) 

				

		) TX;

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_presupuestory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_presupuestory`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);

	



	 

	select 

		concat("'",`a`.`accountNumber`,"'") AS `accountNumber`,

		`a`.`name` AS `accountName`,

		case  

						when  a.accountNumber = '05-01-12' then 300 

						when  a.accountNumber = '06-02-05' then 115  

						when  a.accountNumber = '06-03-01' then 390.00			

										

						when  a.accountNumber = '06-03-08' then 35.00 

						when  a.accountnUmber = '06-03-10' then 11.00 

						when  a.accountNumber = '06-03-09' then 11.00 

						when  a.accountNumber = '06-01-02' then 115.00	

						

						when  a.accountNumber = '06-03-06' then 32 																		

						when  a.accountNumber = '06-02-03' then 20 

						when  a.accountNumber = '06-01-03' then 347.82 																								

						when  a.accountNumber = '06-03-04' then 100.00 						

						when  a.accountNumber = '06-03-05' then 60 						



						

						when  a.accountNumber = '06-02-07' then 0

						when  a.accountNumber = '05-01-06' then 0

						when  a.accountNumber = '06-02-02' then 0	

						when  a.accountNumber = '06-02-04' then 0

						when  a.accountNumber = '06-01-01' then 0

						when  a.accountNumber = '06-03-25' then 0

						when  a.accountNumber = '06-03-03' then 0

			else 0 

		end as numberPresupuesto,

		round((if((`att`.`naturaleza` = 'D'),((`acb`.`debit` - `acb`.`credit`)),((`acb`.`credit` - `acb`.`debit`))) / exchangeRate_),2) *-1 AS `realPresupuesto` 

	from 

		(((`tb_account` `a` 

		join `tb_accounting_balance` `acb` on 

				((`a`.`accountID` = `acb`.`accountID`))) 

		join `tb_accounting_cycle` `acc` on 

				((`acc`.`componentCycleID` = `acb`.`componentCycleID`))) 

		join `tb_account_type` `att` 

				on((`att`.`accountTypeID` = `a`.`accountTypeID`))) 

	where 

		acc.componentPeriodID = prPeriodID 

		and acc.componentCycleID = prCycleID  

		and a.accountNumber IN (

			'06-02-07',

			'06-03-06',

			'06-02-05',

			'05-01-12',

			'05-01-06',

			'06-01-03',

			'06-03-05',

			'06-02-02',

			'06-02-03',

			'06-02-04',

			'06-03-01',

			'06-03-10',

			'06-03-09',

			'06-01-02',

			'06-03-08',

			'06-01-01',

			'06-03-04',

			'06-03-25',

			'06-03-03'

		)

	order by 

		`a`.`accountNumber`,

		`acc`.`startOn` ;

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_get_report_razon_financial` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_get_report_razon_financial`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTokenID` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'reporte de las razones financieras'
BEGIN

	 	DECLARE varRazon VARCHAR(150) DEFAULT '';		 

	 	DECLARE varDescripcion VARCHAR(150) DEFAULT ''; 

	 	DECLARE varValor NUMERIC(19,2) DEFAULT 0;		

		DECLARE resultTemp_ INT DEFAULT 0; 

	

		CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 	

	

		DELETE FROM tb_razones_financieras_tmp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

		

		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0  ,'ACCOUNTING_RF_RAZON_CIRCULANTE',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'CIRCULANTE',IF(varValor IS NULL,0,varValor),'%','001','ACTIVO CIRCULANTE / PASIVO CIRCUALNTE');



		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_ENDEUDAMIENTO',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'ENDEUDAMIENTO',IF(varValor IS NULL,0,varValor),'%','002','PASIVO / PATRIMONIO');



		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_UTILIDAD_ANUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'UTILIDAD ANUAL',IF(varValor IS NULL,0,varValor),'C$','003','UTILIDAD');



		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,1 ,'ACCOUNTING_RF_UTILIDAD_MENSUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'UTILIDAD MENSUAL',IF(varValor IS NULL,0,varValor),'C$','004','UTILIDAD');

 

		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RENTABILIDAD_ANUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'RENTABILIDAD ANUAL',IF(varValor IS NULL,0,varValor),'%','005','UTILIDAD / ACTIVOS');



	

	

		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RENTABILIDAD_MENSUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'RENTABILIDAD MENSUAL',IF(varValor IS NULL,0,varValor),'%','006','UTILIDAD / ACTIVOS');

 

 

 

 		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RAZON_BANCO_BAC',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'SALDO EN BANCO BAC',IF(varValor IS NULL,0,varValor),'C$','007','[01-01-01-01] + [01-01-02-04]');

 

 

 		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RAZON_BANCO_BANPRO',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'SALDO EN BANCO BANPRO',IF(varValor IS NULL,0,varValor),' C$','008','[01-01-02-03] + [01-01-01-02]');

 

 

	 	CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RAZON_BANCO_AVANZ',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'SALDO EN BANCO AVANZ',IF(varValor IS NULL,0,varValor),' C$','009','[01-01-01-03]');

 



		SELECT 

			name,

			value, 

			simbol,

			description 

		FROM 

			tb_razones_financieras_tmp 

		where 

			companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID

		ORDER BY

			sequence;

			

		CALL pr_core_get_indicators (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID) ; 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_import_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_import_account`()
    MODIFIES SQL DATA
    COMMENT 'Procedimiento para importar cuentas'
BEGIN

								DECLARE varIDMin INT DEFAULT 0;

		DECLARE varIDMax INT DEFAULT 0;

		DECLARE varIDParent INT DEFAULT 0;

		DECLARE varIDNivel INT DEFAULT 0;

		DECLARE varCompanyID INT DEFAULT 2;

		DECLARE varStatusID INT DEFAULT 1; 

		DECLARE varCurrencyID INT DEFAULT 1;

		



		SET varIDMin 						= (SELECT accountID FROM tb_account_tmp a order by a.n1,a.n2,a.n3,a.n4,a.n5 asc limit 1);

		SET varIDMax 						= (SELECT accountID FROM tb_account_tmp a order by a.n1 desc,a.n2 desc,a.n3 desc,a.n4 desc,a.n5 asc limit 1); 



		WHILE (varIDMin <= varIDMax) and (varIDMin is not null)  DO	

		

				SET varIDNivel							= (SELECT a.nivel from tb_account_tmp a where a.accountID = varIDMin);

				SET varIDParent						= (SELECT accountID FROM tb_account_tmp a where a.nivel < varIDNivel  and  a.accountID < varIDMin order by a.n1 desc,a.n2 desc,a.n3 desc,a.n4 desc,a.n5 desc limit 1);

				update tb_account_tmp set accountParentID = varIDParent where accountID = varIDMin; 

				

								SET varIDMin 							= (SELECT accountID FROM tb_account_tmp a where a.accountID > varIDMin order by a.n1,a.n2,a.n3,a.n4,a.n5 asc limit 1);



		END WHILE;

		

	

				delete from tb_account;		

	

				insert into tb_account (companyID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive)	

		select  

			 varCompanyID,

			 case 	

			 	when ac.n1 = '01' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'ACTIVO' )

			 	when ac.n1 = '02' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'PASIVO' )

			 	when ac.n1 = '03' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'CAPITAL' )

			 	when ac.n1 = '04' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'INGRESOS' )

			 	when ac.n1 = '05' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'COSTOS' )

			 	when ac.n1 = '06' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'GASTOS' )

			 end accountTypeID,

 			 l.accountLevelID,

			 			 ac.accountParentID,

			 concat(ac.n1,'-',ac.n2,'-',ac.n3,'-',ac.n4,'-',ac.n5) as number,

			 replace(ac.name,'*','') as name,

			 replace(ac.name,'','') as description,

			 ac.operative,

			 varStatusID,

			 varCurrencyID, 

			 2,

			 '2016-01-01',

			 '::1',

			 '2',

			 1

			 		from 

			tb_account_tmp ac 

			inner join tb_account_level l on 

				(ac.nivel = 0 and l.lengthTotal = 2 ) or

				(ac.nivel = 3 and l.lengthTotal = 5 ) or 

				(ac.nivel = 6 and l.lengthTotal = 8 ) or

				(ac.nivel = 9 and l.lengthTotal = 11 ) 

		where 

			l.isActive = 1 and 

			l.companyID = varCompanyID 

		order by 

			ac.n1,ac.n2,ac.n3,ac.n4,ac.n5; 

		

				update 

				tb_account,

				(

				select 

					a.accountID,

					a2.accountID as parentID 

				from 

					tb_account a

					inner join tb_account_tmp att on 

						a.parentAccountID = att.accountID 

					inner join tb_account a2 on 

						concat(att.n1,'-',att.n2,'-',att.n3,'-',att.n4,'-',att.n5) = a2.accountNumber 

				) tl

 		 set tb_account.parentAccountID = tl.parentID 

 		 where

	 		  tb_account.accountID = tl.accountID ; 

				

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_initialize_account_tmp` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_initialize_account_tmp`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para inicializar la tabla temporal'
BEGIN

	INSERT INTO tb_accounting_balance_temp (companyID,branchID,loginID,accountID,parentAccountID,accountNumber,name,isOperative,statusID,accountTypeID,naturaleza,balanceStart,debit,credit,balanceEnd)

	SELECT

		a.companyID,

		prBranchID,

		prLoginID,

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

		a.isActive  = 1 and 

		ab.isActive = 1 and 

		cp.isActive = 1 and 

		cc.isActive = 1 and 

		a.companyID = prCompanyID and 

		cp.componentPeriodID  = prPeriodID and 

		cc.componentCycleID   = prCycleID 

	ORDER BY

		a.accountNumber ;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_mayorizate_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_mayorizate_account`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT, IN `prAccountID` INT, IN `prBalance` DECIMAL(19,8), IN `prDebit` DECIMAL(19,8), IN `prCredit` DECIMAL(19,8))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para mayorizar cuentas'
BEGIN

	DECLARE parentAccountID_ INT;	

	SET parentAccountID_ 		= (SELECT parentAccountID FROM tb_account where companyID = prCompanyID and accountID = prAccountID);		

	SET max_sp_recursion_depth = 6; 

	

	IF parentAccountID_ IS NOT NULL  THEN

		CALL pr_accounting_mayorizate_account(prCompanyID,prPeriodID,prCycleID,parentAccountID_,prBalance,prDebit,prCredit);

	END IF ;	

	

	UPDATE tb_accounting_balance set balance = balance + prBalance , debit = debit + prDebit , credit = credit + prCredit where companyID = prCompanyID and accountID = prAccountID and componentPeriodID = prPeriodID and componentCycleID = prCycleID;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_mayorizate_account_tmp` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_mayorizate_account_tmp`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prAccountID` INT, IN `prBalance` DECIMAL(19,8), IN `prDebit` DECIMAL(19,8), IN `prCredit` DECIMAL(19,8), IN `prBalanceEnd` DECIMAL(19,8))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para mayorizar la tabla Temporal'
BEGIN

	DECLARE parentAccountID_ INT;	

	DECLARE naturaleza_ VARCHAR(1);

	SET max_sp_recursion_depth = 6; 

	SET parentAccountID_ 		= (SELECT parentAccountID FROM tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and  accountID = prAccountID);	

	SET naturaleza_ 				= (SELECT naturaleza FROM tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and  accountID = prAccountID);	

	

	IF parentAccountID_ IS NOT NULL  THEN

		CALL pr_accounting_mayorizate_account_tmp(prCompanyID,prBranchID,prLoginID,prTocken,parentAccountID_,prBalance,prDebit,prCredit,prBalanceEnd);

	END IF ;	

	

	UPDATE tb_accounting_balance_temp set balanceStart = balanceStart + prBalance , debit = debit + prDebit , credit = credit + prCredit,balanceEnd = balanceEnd + prBalanceEnd  where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and  accountID = prAccountID;		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_mayorizate_cycle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_mayorizate_cycle`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prResult` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para mayorizar los comprobantes realizados'
LBL_PROCEDURE:

BEGIN

	DECLARE journalTypeClosed INT DEFAULT 0;

	DECLARE minAccountID INT;

	DECLARE maxAccountID INT;

	DECLARE debit_ decimal(19,8)  DEFAULT 0;

	DECLARE credit_ decimal(19,8)  DEFAULT 0;

	DECLARE balance_ decimal(19,8) DEFAULT 0;

	DECLARE componentAccountID 	INT DEFAULT 4;

	DECLARE workflowStageCycleClosed_ INT DEFAULT 0;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",workflowStageCycleClosed_);

	

		IF EXISTS(SELECT cc.companyID FROM tb_accounting_cycle cc where cc.companyID = prCompanyID and componentID = componentAccountID AND componentPeriodID = prPeriodID and componentCycleID = prCycleID AND statusID = workflowStageCycleClosed_ ) THEN

		SET prResult = 0;

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,'','pr_accounting_mayorizate_cycle',1,'El ciclo ya esta cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_JOURNALTYPE_CLOSED",journalTypeClosed);

	

		DELETE FROM tb_journal_entry_detail_summary WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

	

		INSERT INTO tb_journal_entry_detail_summary(companyID,branchID,loginID,journalEntryID,accountID,parentAccountID,debit,credit)

	SELECT 

		prCompanyID,

		prBranchID,

		prLoginID,

		je.journalEntryID,

		a.accountID,

		a.parentAccountID,

		sum(jed.debit),

		sum(jed.credit)

	FROM

		tb_journal_entry je 

		inner join tb_journal_entry_detail jed on 

				je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID 

		inner join tb_workflow_stage ws on

				je.statusID = ws.workflowStageID 

		inner join tb_account a on 

				jed.accountID = a.accountID 

	WHERE

		je.companyID = prCompanyID and 

		je.accountingCycleID = prCycleID and 		

		je.isActive = 1 and 

		jed.isActive = 1 and 

		je.journalTypeID != journalTypeClosed and 

		(jed.debit + jed.credit)  > 0  

	group by

		accountID;	

	

		INSERT INTO tb_accounting_balance (componentCycleID,componentPeriodID,companyID,componentID,accountID,branchID,balance,debit,credit,classID,isActive)

	SELECT 

		prCycleID,

		prPeriodID,

		prCompanyID,

		componentAccountID,

		a.accountID,

		prBranchID,

		0 AS balance,

		0 as debit,

		0 as credit,

		0 as classID,

		1 AS isActive

	FROM 

		tb_account a

	WHERE  

		a.companyID = prCompanyID and 

		a.accountID NOT IN (SELECT accountID FROM tb_accounting_balance where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleID and isActive = 1) AND 

		a.isActive = 1;

		 

		UPDATE tb_accounting_balance set debit = 0 , credit = 0 where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleID;

	SET minAccountID = (SELECT MIN(accountID) FROM  tb_journal_entry_detail_summary );

	SET maxAccountID = (SELECT MAX(accountID) FROM  tb_journal_entry_detail_summary );

	

	WHILE (minAccountID <= maxAccountID) and (minAccountID is not null) DO	

		SET balance_ 					= 0;  

		SET debit_ 						= (SELECT sum(debit) FROM tb_journal_entry_detail_summary WHERE accountID = minAccountID);

		SET credit_ 					= (SELECT sum(credit) FROM tb_journal_entry_detail_summary WHERE accountID = minAccountID);	

		CALL pr_accounting_mayorizate_account(prCompanyID,prPeriodID,prCycleID,minAccountID,balance_,debit_,credit_);

		SET minAccountID 				= (SELECT MIN(accountID) FROM  tb_journal_entry_detail_summary where accountID > minAccountID);

	END WHILE; 

	

		DELETE FROM tb_journal_entry_detail_summary WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

	SET prResult = 1;

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,'','pr_accounting_mayorizate_cycle',0,'Success',CURRENT_TIMESTAMP());

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_templated_to_journal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_templated_to_journal`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prApp` VARCHAR(50), IN `prJournalEntryTemplated` INT, INOUT `prJournalEntryResult` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'procedimiento para crear un comprobante a partir de un template'
BEGIN

	

DECLARE varJournalTypeID INT DEFAULT 0;

DECLARE varWorkflowStageInit INT DEFAULT 0;

DECLARE varJournalNumber VARCHAR(150) DEFAULT '';

DECLARE varCycleID INT DEFAULT 0;

DECLARE varDate DATE;

DECLARE varCurrentSourceID INT;

DECLARE varCurrentTargetID INT;

DECLARE varCurrentSourceName VARCHAR(50);

DECLARE varCurrentTargetName VARCHAR(50);

DECLARE varExchangeRate DECIMAL(18,8);

DECLARE varStatusIDClosedCycle INT;

DECLARE varTmp VARCHAR(150) DEFAULT '';

DECLARE varComponentID INT DEFAULT 0;







CALL pr_core_get_parameter_value(prCompanyID, 'ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED',varTmp);

SET  varStatusIDClosedCycle = CAST(varTmp AS UNSIGNED);



SET varComponentID 		  	 =  (SELECT c.componentID FROM tb_component c where c.name = '0-CONTABILIDAD');		



SET varDate 				 =  CURDATE();



SET varCurrentSourceID 		 =  (SELECT c.currencyID FROM tb_journal_entry c where c.journalEntryID = prJournalEntryTemplated);



SET varCurrentTargetID 		 =  (case when varCurrentSourceID = 1 then 2 else 1 end);

SET varCurrentSourceName 	 =  (case when varCurrentSourceID = 1 then 'Cordoba' else 'Dolar' end);

SET varCurrentTargetName 	 =  (case when varCurrentTargetID = 1 then 'Cordoba' else 'Dolar' end);





SET varJournalTypeID 		 =  (SELECT c.journalTypeID FROM tb_journal_entry c where c.journalEntryID = prJournalEntryTemplated);



CALL pr_core_get_next_number(prCompanyID, 'tb_journal_entry', prBranchID, varJournalTypeID, varJournalNumber);







CALL pr_core_get_exchange_rate(prCompanyID,varDate,varCurrentTargetName,varCurrentSourceName,varExchangeRate); 







SET varCycleID 	= (select c.componentCycleID from tb_accounting_cycle c where c.isActive = 1 and c.companyID = prCompanyID and c.componentID = varComponentID and statusID != varStatusIDClosedCycle AND  varDate between c.startOn and c.endOn LIMIT 1);



CALL pr_core_get_workflow_stage_init(prCompanyID,'tb_journal_entry','statusID',varWorkflowStageInit);

 



INSERT INTO tb_journal_entry (

	companyID,

	journalNumber,

	entryName,

	journalDate, 

	tb_exchange_rate, 

	createdOn, 

	createdIn,

	createdAt,

	createdBy,

	isActive,

	isApplied,

	statusID,

	note,

	reference1,reference2,reference3,

	journalTypeID,currencyID,accountingCycleID,

	isModule,transactionMasterID,transactionID)

SELECT 

	companyID,

	varJournalNumber,

	entryName,

	varDate, 

	varExchangeRate, 

	now(), 

	createdIn,

	createdAt,

	prLoginID,

	1,

	0,

	varWorkflowStageInit,

	note,

	reference1,reference2,reference3,

	varJournalTypeID,varCurrentSourceID,varCycleID,

	0,0,0

FROM 

	tb_journal_entry lx	

where

	lx.journalEntryID = prJournalEntryTemplated;



SET prJournalEntryResult = LAST_INSERT_ID();	



 



INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,debit,credit,note,isApplied,branchID,tb_exchange_rate)

select 

	companyID,prJournalEntryResult,accountID,classID,isActive,debit,credit,note,0,prBranchID,varExchangeRate

from 

	tb_journal_entry_detail l

where

	l.journalEntryID = prJournalEntryTemplated;





END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_accounting_transaction_to_journal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_accounting_transaction_to_journal`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTransactionID` INT, IN `prSourceName` VARCHAR(50), IN `prResult` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Este procedimiento es para contabilizar todos los documentos de los modulos que estaran involucrado a la contabilidad'
BEGIN

		DECLARE varIDMin INT DEFAULT 0;

		DECLARE varIDMax INT DEFAULT 0;

		DECLARE varTransactionMasterID INT DEFAULT 0;

		DECLARE varTransactionMasterCausalID INT DEFAULT 0;

		DECLARE varCurrencyID INT DEFAULT 0;

		DECLARE varExchangeRate DECIMAL(26,8) DEFAULT 0;

		DECLARE varTransactionOn DATETIME DEFAULT CURRENT_DATE;

		DECLARE varTransactionNumber VARCHAR(150) DEFAULT '';

		DECLARE varReference2 VARCHAR(150) DEFAULT '';		

		DECLARE varTransactionName VARCHAR(450) DEFAULT '';

		DECLARE varJournalTypeID INT DEFAULT 0;

		DECLARE varWorkflowStageInit INT DEFAULT 0;

		DECLARE varCycleID INT DEFAULT 0;

		DECLARE varJournalNumber VARCHAR(150) DEFAULT '';

		DECLARE varTmp VARCHAR(150) DEFAULT '';

		DECLARE varComponentID INT DEFAULT 0;

		DECLARE varStatusIDClosedCycle INT DEFAULT 0;

		DECLARE varJournalEntryID INT DEFAULT 0;

		DECLARE varDebit DECIMAL(26,8) DEFAULT 0;

		DECLARE varCredit DECIMAL(26,8) DEFAULT 0; 		

		DECLARE varIsRevert INT DEFAULT 0;

		DECLARE varTransactionIDOriginal INT DEFAULT 0;

		

		

		

		SET varComponentID 		  =  (SELECT c.componentID FROM tb_component c where c.name = '0-CONTABILIDAD');

		

		CALL pr_core_get_parameter_value(prCompanyID, 'ACCOUNTING_JOURNALENTRY_WORKFLOWSTAGE_FINISH',varTmp);

		SET  varWorkflowStageInit = CAST(varTmp AS UNSIGNED);

		

		CALL pr_core_get_parameter_value(prCompanyID, 'ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED',varTmp);

		SET  varStatusIDClosedCycle = CAST(varTmp AS UNSIGNED);

		

		DELETE  FROM tb_transaction_master_summary_concept_tmp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID ; 

		DELETE  FROM tb_transaction_profile_detail_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID;

		

		SET varIsRevert 		= (SELECT t.isRevert FROM tb_transaction t  where t.transactionID = prTransactionID and t.companyID = prCompanyID);		

		SET varTransactionName 	= (SELECT c.name FROM tb_transaction c where c.transactionID = prTransactionID and c.companyID = prCompanyID limit 1 );

		

		

		IF varIsRevert IS NULL OR varIsRevert = 0 THEN 

			INSERT INTO tb_transaction_master_summary_concept_tmp(companyID,branchID,loginID,transactionID,transactionMasterID,transactionMasterCausalID,transactionNumber,transactionDate,exchangeRate,currencyID,conceptID,reference1,reference2,reference3,value)

			SELECT 

				tm.companyID,

				prBranchID,

				prLoginID,

				tm.transactionID,

				tm.transactionMasterID,

				tm.transactionCausalID,			

				tm.transactionNumber ,

				tm.transactionOn, 

				tm.exchangeRate,

				tm.currencyID, 

				tmc.conceptID,  

				tm.reference1,

				tm.reference2,

				tm.reference3,

				SUM(ROUND(tmc.value,2)) as value 

			FROM  

				tb_transaction_master tm 

				inner join tb_transaction tt on 

					tm.transactionID = tt.transactionID and 

					tm.companyID = tt.companyID 

				inner join tb_transaction_causal tcc on 

					tm.companyID = tcc.companyID and 

					tm.transactionID = tcc.transactionID and 

					tm.transactionCausalID = tcc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					tm.statusID = ws.workflowStageID 

				inner join tb_transaction_master_concept tmc on 

					tm.companyID = tmc.companyID and 

					tm.transactionID = tmc.transactionID and 

					tm.transactionMasterID = tmc.transactionMasterID 

			where 

				tm.isActive = 1 and 

				tm.companyID = prCompanyID and 

				tm.transactionID = prTransactionID  and 

				ws.aplicable = 1 and 

				tm.journalEntryID = 0 and 

				tt.isActive = 1 and 

				tt.isCountable = 1 and 

				tcc.isActive = 1 

			group by 

				tm.companyID,

				tm.transactionID,  

				tm.transactionMasterID,

				tm.transactionCausalID,			

				tm.transactionNumber ,

				tm.currencyID,

				tmc.conceptID,

				tm.reference1,

				tm.reference2,

				tm.reference3;

		

		

		

			insert into tb_transaction_profile_detail_tmp (companyID,branchID,loginID,transactionID,transactionMasterID,transactionCausalID,conceptID,accountID,classID,debit,credit)

			SELECT 

					distinct 

					prCompanyID,

					prBranchID,

					prLoginID,

					tmp.transactionID,

					tmp.transactionMasterID,

					tmp.transactionMasterCausalID,

					tp.conceptID,

					tp.accountID,

					tp.classID,				

					CASE WHEN tp.sign = 'D' then tmp.value else 0  END as debit,

					CASE WHEN tp.sign = 'C' then tmp.value else 0  END as credit

		    FROM 

					tb_transaction_profile_detail tp  

					inner join tb_transaction_concept tmc on 

						tp.companyID = tmc.companyID and 

						tp.transactionID = tmc.transactionID 

					inner join tb_account ac on 

						tp.accountID = ac.accountID 

					inner join tb_transaction_master_summary_concept_tmp tmp on 

						tp.companyID = tmp.companyID and 

						tp.transactionID = tmp.transactionID and 

						tp.transactionCausalID = tmp.transactionMasterCausalID and 

						tp.conceptID = tmp.conceptID 

			where 

					tp.companyID 					= prCompanyID and 	

					tmp.branchID 					= prBranchID and 

					tmp.loginID 					= prLoginID and 

					tmp.transactionID 				= prTransactionID and 

					tmc.isActive 					= 1;

		

			SET varIDMin 						= (SELECT MIN(transactionMasterID) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID );

			SET varIDMax 						= (SELECT MAX(transactionMasterID) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID );

			SET varJournalTypeID 				= (SELECT journalTypeID FROM tb_transaction WHERE companyID = prCompanyID and transactionID = prTransactionID);

			WHILE (varIDMin <= varIDMax) and (varIDMin is not null) DO	

					SET varTransactionMasterID 			= (SELECT transactionMasterID FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and transactionMasterID = varIDMin LIMIT 1);

					SET varTransactionMasterCausalID 	= (SELECT transactionMasterCausalID FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varCurrencyID 					= (SELECT currencyID FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varExchangeRate 				= (SELECT exchangeRate FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varTransactionOn 				= (SELECT transactionDate FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varTransactionNumber 			= (SELECT transactionNumber FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varReference2 					= (SELECT CONCAT(IFNULL(reference1,'N/D'),'-',IFNULL(reference2,'N/D'),'-',IFNULL(reference3,'N/D')) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					

					

					SET varCycleID 						= 

					(select c.componentCycleID from tb_accounting_cycle c where c.isActive = 1 and c.companyID = prCompanyID and c.componentID = varComponentID and statusID != varStatusIDClosedCycle AND  date(varTransactionOn) between c.startOn and c.endOn LIMIT 1);

					

					IF varCycleID IS NULL THEN

							INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

							VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',1,concat('Ciclo Contable Cerrado: ',varTransactionOn),CURRENT_TIMESTAMP());	  									

							SET varJournalEntryID  = 0;		

					ELSE

							SET varDebit  = (select SUM(c.debit) from tb_transaction_profile_detail_tmp c where c.companyID = prCompanyID and c.branchID = prBranchID and c.loginID = prLoginID and c.transactionID = prTransactionID and c.transactionCausalID = varTransactionMasterCausalID and c.transactionMasterID = varTransactionMasterID);

							SET varCredit = (select SUM(c.credit) from tb_transaction_profile_detail_tmp c where c.companyID = prCompanyID and c.branchID = prBranchID and c.loginID = prLoginID and c.transactionID = prTransactionID and c.transactionCausalID = varTransactionMasterCausalID and c.transactionMasterID = varTransactionMasterID);

							

							IF varDebit <> varCredit  THEN

									INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

									VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',1,concat('Partida Descuadrada Documento (',varTransactionNumber,')'),CURRENT_TIMESTAMP());	  

									SET varJournalEntryID  = 0;		

							ELSE 

									IF NOT (varDebit = 0 OR varDebit IS NULL) THEN

										CALL pr_core_get_next_number(prCompanyID, 'tb_journal_entry', prBranchID, varJournalTypeID, varJournalNumber);

										

										INSERT INTO tb_journal_entry (companyID,journalNumber,entryName,journalDate, tb_exchange_rate, createdOn, createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,reference1,reference2,reference3,journalTypeID,currencyID,accountingCycleID,isModule,transactionMasterID,transactionID)

										VALUES(prCompanyID,varJournalNumber,'APP',varTransactionOn,varExchangeRate,CURRENT_DATE,'127.0.0.1',prBranchID,prLoginID,1,0,varWorkflowStageInit, CONCAT('AUTOMATIC','/',varTransactionNumber,'/',varTransactionName) ,varTransactionNumber,varReference2,'',varJournalTypeID,varCurrencyID,varCycleID,1,varTransactionMasterID,prTransactionID);

										SET varJournalEntryID = LAST_INSERT_ID();	

											  

										INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,debit,credit,note,isApplied,branchID,tb_exchange_rate)

										select 

												c.companyID,

												varJournalEntryID,

												c.accountID,

												c.classID,

												1,

												c.debit,

												c.credit,

												'',

												0,

												prBranchID,

												varExchangeRate

										from 

												tb_transaction_profile_detail_tmp c 

										where 

												c.companyID = prCompanyID and 

												c.branchID = prBranchID and 

												c.loginID = prLoginID and 

												c.transactionID = prTransactionID and 

												c.transactionCausalID = varTransactionMasterCausalID and 

												c.transactionMasterID = varTransactionMasterID; 

												

										UPDATE tb_transaction_master_summary_concept_tmp set journalEntryID =  varJournalEntryID where  companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and transactionMasterID = varTransactionMasterID AND transactionMasterCausalID = varTransactionMasterCausalID;

									

									ELSE 

										SET varJournalEntryID  = 0;									

									END IF;		

							END IF;

					END IF;

					SET varIDMin 	= (SELECT MIN(transactionMasterID) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID AND transactionMasterID > varIDMin);

			END WHILE;

	

	

			INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,note,isApplied,branchID,tb_exchange_rate,debit,credit)

			SELECT 

				jed.companyID,

				jed.journalEntryID,

				jed.accountID,

				jed.classID,

				jed.isActive,

								'TMP_PROCEDURE_NSSYSTEM' as note,

				jed.isApplied, 

				jed.branchID,

				jed.tb_exchange_rate,

				sum(jed.debit) as debit,

				sum(jed.credit) as credit

				

			FROM

				tb_journal_entry je 

				inner join tb_journal_entry_detail jed on 

					je.journalEntryID = jed.journalEntryID 

			where

				je.journalEntryID in ( 

					select distinct lmx.journalEntryID 

					from 

						tb_transaction_master_summary_concept_tmp lmx 

					where

						lmx.companyID = prCompanyID and 

						lmx.branchID = prBranchID and 

						lmx.loginID = prLoginID and 

						lmx.transactionID = prTransactionID

				) and 

				(jed.debit + jed.credit) <> 0  

			group by 

				jed.companyID,

				jed.journalEntryID,

				jed.accountID,

				jed.classID,

				jed.isActive,

				jed.note,

				jed.isApplied,

				jed.branchID,

				jed.tb_exchange_rate; 

				

			

			DELETE FROM tb_journal_entry_detail 

			where journalEntryID in ( 

					select 

						distinct lmx.journalEntryID 

					from 

						tb_transaction_master_summary_concept_tmp lmx 

					where

						lmx.companyID = prCompanyID and 

						lmx.branchID = prBranchID and 

						lmx.loginID = prLoginID and 

						lmx.transactionID = prTransactionID 

			) and note <> 'TMP_PROCEDURE_NSSYSTEM';

			

			

			UPDATE tb_transaction_master,tb_transaction_master_summary_concept_tmp 

			SET tb_transaction_master.journalEntryID = tb_transaction_master_summary_concept_tmp.journalEntryID 

		    WHERE 

					tb_transaction_master.companyID								= tb_transaction_master_summary_concept_tmp.companyID and 

					tb_transaction_master.transactionID 						= tb_transaction_master_summary_concept_tmp.transactionID and 

					tb_transaction_master.transactionMasterID 					= tb_transaction_master_summary_concept_tmp.transactionMasterID and 

					tb_transaction_master.transactionCausalID 					= tb_transaction_master_summary_concept_tmp.transactionMasterCausalID  and 

					tb_transaction_master_summary_concept_tmp.companyID 		= prCompanyID and 

					tb_transaction_master_summary_concept_tmp.transactionID 	= prTransactionID and 

					tb_transaction_master_summary_concept_tmp.branchID 			= prBranchID and 

					tb_transaction_master_summary_concept_tmp.loginID 			= prLoginID;		

				

		ELSE

			SET varIDMin 						= (SELECT MIN(trevert.transactionMasterID) FROM tb_transaction_master  trevert inner join tb_transaction_master tm on trevert.reference1 = tm.transactionID and trevert.reference2 = tm.transactionMasterID and trevert.reference3 = tm.transactionNumber inner join tb_journal_entry je on tm.journalEntryID = je.journalEntryID inner join tb_journal_entry_detail jed on je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID where trevert.isActive = 1 and trevert.journalEntryID = 0 and trevert.transactionID = prTransactionID and trevert.companyID = prCompanyID and je.isActive = 1  );

			SET varIDMax 						= (SELECT MAX(trevert.transactionMasterID) FROM tb_transaction_master  trevert inner join tb_transaction_master tm on trevert.reference1 = tm.transactionID and trevert.reference2 = tm.transactionMasterID and trevert.reference3 = tm.transactionNumber inner join tb_journal_entry je on tm.journalEntryID = je.journalEntryID inner join tb_journal_entry_detail jed on je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID where trevert.isActive = 1 and trevert.journalEntryID = 0 and trevert.transactionID = prTransactionID and trevert.companyID = prCompanyID and je.isActive = 1  );

			SET varJournalTypeID 				= (SELECT journalTypeID FROM tb_transaction WHERE companyID = prCompanyID and transactionID = prTransactionID);

			WHILE (varIDMin <= varIDMax) and (varIDMin is not null) DO	

			

					SET varTransactionMasterID			= (SELECT trevert.transactionMasterID FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin); 					

					SET varTransactionMasterCausalID 	= (SELECT trevert.transactionCausalID FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varCurrencyID 					= (SELECT trevert.currencyID FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varExchangeRate 				= (SELECT trevert.exchangeRate FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varTransactionOn 				= (SELECT trevert.transactionOn FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varTransactionNumber 			= (SELECT trevert.transactionNumber FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varReference2 					= (SELECT CONCAT(IFNULL(trevert.reference1,'N/D'),'-',IFNULL(trevert.reference2,'N/D'),'-',IFNULL(trevert.reference3,'N/D'))    FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);					

					SET varCycleID 						= (select c.componentCycleID from tb_accounting_cycle c where c.isActive = 1 and c.companyID = prCompanyID and c.componentID = varComponentID and statusID != varStatusIDClosedCycle AND  varTransactionOn between c.startOn and c.endOn LIMIT 1);

					

					IF varCycleID IS NULL THEN

							INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

							VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',1,concat('Ciclo Contable Cerrado: ',varTransactionOn),CURRENT_TIMESTAMP());	  

									

							SET varJournalEntryID  = 0;		

					ELSE		

						CALL pr_core_get_next_number(prCompanyID, 'tb_journal_entry', prBranchID, varJournalTypeID, varJournalNumber);

						

						INSERT INTO tb_journal_entry (companyID,journalNumber,entryName,journalDate, tb_exchange_rate, createdOn, createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,reference1,reference2,reference3,journalTypeID,currencyID,accountingCycleID,isModule,transactionMasterID,transactionID)

						VALUES(prCompanyID,varJournalNumber,'APP',varTransactionOn,varExchangeRate,CURRENT_DATE,'127.0.0.1',prBranchID,prLoginID,1,0,varWorkflowStageInit,'AUTOMATIC',varTransactionNumber,varReference2,'',varJournalTypeID,varCurrencyID,varCycleID,1,varTransactionMasterID,prTransactionID);

						SET varJournalEntryID = LAST_INSERT_ID();	

							  

						INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,debit,credit,note,isApplied,branchID,tb_exchange_rate)

						select 

								jed.companyID,

								varJournalEntryID,

								jed.accountID,

								jed.classID,

								1,

								jed.credit,

								jed.debit,

								'',

								0,

								prBranchID,

								varExchangeRate

						from 

								tb_transaction_master  trevert 

								inner join tb_transaction_master tm on 

									trevert.reference1 = tm.transactionID and 

									trevert.reference2 = tm.transactionMasterID and 

									trevert.reference3 = tm.transactionNumber 

								inner join tb_journal_entry je on 

									tm.journalEntryID = je.journalEntryID 

								inner join tb_journal_entry_detail jed on 

									je.journalEntryID = jed.journalEntryID and 

									je.companyID = jed.companyID 

						where 

							 trevert.isActive = 1 and 

							 trevert.journalEntryID = 0 and 

							 trevert.transactionID = prTransactionID and 

							 trevert.companyID = prCompanyID and 

							 trevert.transactionMasterID = varIDMin;   

								

								

						UPDATE tb_transaction_master set journalEntryID = varJournalEntryID where transactionMasterID = varIDMin;

					

					END IF;

					SET varIDMin 	= (SELECT MIN(trevert.transactionMasterID) FROM tb_transaction_master  trevert inner join tb_transaction_master tm on trevert.reference1 = tm.transactionID and trevert.reference2 = tm.transactionMasterID and trevert.reference3 = tm.transactionNumber inner join tb_journal_entry je on tm.journalEntryID = je.journalEntryID inner join tb_journal_entry_detail jed on je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID where trevert.isActive = 1 and trevert.journalEntryID = 0 and trevert.transactionID = prTransactionID and trevert.companyID = prCompanyID and je.isActive = 1 and trevert.transactionMasterID > varIDMin);

			END WHILE;

		END IF;

	

	

		SET prResult = 1;

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',0,'Success',CURRENT_TIMESTAMP());

		

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_app_invoice_survery_get_report` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_app_invoice_survery_get_report`(IN `prCompanyID` INT, 

	IN `prTokenID` VARCHAR(50), 

	IN `prUserID` INT, 

	IN `prDateTimeStart` DATETIME,

	IN `prDateTimeFinish` DATETIME,

	IN `prSurveryKey` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN



  SELECT 

    ttm.transactionNumber,

    ttm.transactionOn,

    tc.customerNumber,

    CONCAT(tn.firstName, ' ', tn.lastName) as Cliente,

    ti.itemNumber,

    ti.name,

    ttmd.quantity,

    tcr.simbol as Moneda,

    ttm.amount

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_customer tc ON ttm.entityID = tc.entityID

  INNER JOIN tb_naturales tn ON tc.entityID = tn.entityID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  order BY ttm.transactionOn DESC;



  SELECT 

    ti.itemNumber as Codigo,

    ti.name as Descripcion,

    SUM(ttmd.quantity) as Cantidad,

    tcr.simbol as Moneda,

    SUM(ttm.amount) as Monto

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE 

    ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  GROUP BY ti.itemNumber, tcr.simbol

  order BY ttm.transactionOn DESC;



  SELECT 

    tc.customerNumber,

    tn.firstName as Nombres,

    tn.lastName AS Apellidos,

    SUM(ttmd.quantity) as Cantidad,

    tcr.simbol as Moneda,

    SUM(ttm.amount) AS Monto

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_customer tc ON ttm.entityID = tc.entityID

  INNER JOIN tb_naturales tn ON tc.entityID = tn.entityID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE 

    ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  GROUP BY tc.customerNumber, tn.firstName, tn.lastName;



  SELECT 

    ttm.transactionOn as Fecha,

    ttm.transactionNumber,

    SUM(ttmd.quantity) as Cantidad,

    tcr.simbol as Moneda,

    SUM(ttm.amount) AS Monto 

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE 

    ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  GROUP BY ttm.transactionNumber, ttm.transactionOn, tcr.simbol

  order BY ttm.transactionOn DESC;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_closed_box` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_closed_box`(IN prUserID INT, 

	IN prBranchID INT,

	IN prTokenID VARCHAR(150), 

	IN prCompanyID INT,  

	IN prTransactionMasterOpen INT, 

	IN prTransactionMasterClosed INT, 

	IN prCashBoxID INT, 

	IN prCashBoxSessionID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'guarda el listado de transacciones que se guardan dentro de una session de caja'
BEGIN





	

	DELETE FROM tb_cash_box_session_transaction_master WHERE cashBoxSessionID = prCashBoxSessionID;

	

	

	

	INSERT INTO tb_cash_box_session_transaction_master (companyID,branchID,cashBoxID,cashBoxSessionID,transactionID,transactionMasterID)

	SELECT 

		prCompanyID,

		prBranchID,

		prCashBoxID,

		prCashBoxSessionID,

		c.transactionID,

		c.transactionMasterID

	FROM 

		tb_transaction_master c 

	WHERE 

		c.transactionMasterID between prTransactionMasterOpen and prTransactionMasterClosed;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_abonos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_abonos`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prAuthorization` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, 
IN `prUserIDFilter` INT,  IN `prBranchID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de abonos de los clientes'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 
	DECLARE isAdmin_ INT DEFAULT   0; 
  DECLARE convert_ VARCHAR(50);	

	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
		inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);


	SELECT 
		tt.name as transactionName,
		cus.customerNumber,
		concat(nat.firstName,' ',nat.lastName) as firstName , 
		tm.transactionNumber ,		
		DATE_FORMAT(tm.transactionOn, '%Y-%m-%d %r')  as transactionOn,
		tm.amount as montoTotal ,
		ws.name as estado,
		tm.note,		
		tmd.reference1 as Fac,
		if(
			cur.name = 'Dolar',
			tmd.amount * (exchangeRate_  + currencyTargetSale),
			tmd.amount
		)  as montoCordoba,
    case 
			when convert_ = 'Dolar' and cur.name != 'Dolar'  then 
				tmd.amount * (exchangeRate_  )
       when convert_ = 'Cordoba' and cur.name != 'Cordoba'  then 
				tmd.amount / (exchangeRate_ )
			else 
				tmd.amount 
		end as  montoFac,
		case 
			when convert_ = 'None'  then 
      cur.`name`
			else 
				convert_ 
		end as moneda,
		(exchangeRate_  + currencyTargetSale) as tipoCambio ,
		PERMISSION_ME,
		prAuthorization,
		tm.createdBy ,
		us.nickname ,
		'' as conceptosName ,
		'' as conceptosSubName 
	FROM 
		tb_transaction_master tm			
		inner join tb_transaction tt on 
			tm.transactionID = tt.transactionID 
		inner join tb_customer cus on 
			tm.entityID = cus.entityID 
		inner join tb_naturales nat on 
			cus.entityID = nat.entityID 
		inner join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 	
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID  
		inner join tb_transaction_master tm2 on 
			tmd.reference1 = tm2.transactionNumber and 
			tmd.companyID = tm2.companyID and 
			tm.entityID = tm2.entityID 
		inner join tb_currency cur on 
			tm2.currencyID = cur.currencyID 
		inner join tb_user us on 
			us.userID = tm.createdBy 
		inner join tb_branch braus on 
			braus.branchID = us.locationID
		inner join tb_company comp on 
			comp.companyID = tm.companyID 
	where
		tm.transactionID in  (23,24,25) 		
		and 
		(
				(
					comp.flavorID = 326  and 
					tm.transactionID in (23)
				)  or 
				(
					comp.flavorID != 326 
				)
		)		
		and tm.isActive = 1 
		and tmd.isActive = 1 
		and 
		(
			(braus.branchID = prBranchID and prBranchID != 0 )
			or 
			(prBranchID = 0)
		)
		and tm.companyID = prCompanyID 
		and tm.transactionOn  between prStartOn and prEndOn 
		and  
		(
				fn_get_access_ready(
					prCompanyID  , 
					prUserID  , 173, 
					tm.createdBy  , 0 
				) = 1 
		) and 
		(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
		) and 
		ws.aplicable = 1   
	order by 
		tm.transactionOn;   
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_attendance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_attendance`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de asistencia'
BEGIN

	

	

	

	SELECT 

		c.transactionNumber,

		c.createdOn,

		ws.`name` as estado,

		ci.`name` as prioridad, 

		nat.firstName,

		c.reference1  AS solvencia,

		c.reference2 AS proximoPago,

		c.reference4 AS diasProximoPago,

		c.reference3 AS vencimiento

	FROM 

		tb_transaction_master c 

		inner join tb_naturales nat on 

			c.entityID = nat.entityID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

		inner join tb_catalog_item ci  on 

			ci.catalogItemID = c.priorityID 

	where 

		c.isActive = 1 and 

		c.transactionID = 32 

	order by 

		c.transactionNumber; 

	

	 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_closed` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_closed`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de abonos de los clientes'
BEGIN

	

		DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);			

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE amount1 DECIMAL(18,4) DEFAULT 0;		

	DECLARE amount2 DECIMAL(18,4) DEFAULT 0;		

	DECLARE amount3 DECIMAL(18,4) DEFAULT 0;		

	DECLARE amount4 DECIMAL(18,4) DEFAULT 0;		

	

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE PERMISSION_NONE INT DEFAULT -1;

	DECLARE PERMISSION_ALL INT DEFAULT 0;

	DECLARE PERMISSION_BRANCH INT DEFAULT 1;

	DECLARE PERMISSION_ME INT DEFAULT 2; 		

	DECLARE isAdmin_ INT DEFAULT   0; 

	DECLARE varDif DECIMAL(19,4) DEFAULT 0;

	DECLARE varCompanyType VARCHAR(50);

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	create table tb_tmp_closed (

		substitulo varchar(250),

		codigo varchar(50),

		nombre varchar(150),

		cantidad varchar(150),

		subtotal varchar(150),

		total decimal(19,2),

		moneda int ,

		tipoCambio decimal(19,2),

		comandoProce varchar(150),

		sumary varchar(50)

		

	);

	

	

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'MinMax',

		'Estado',

		'001.001',

		'PRI Y ULTI.',

		IFNULL(min(tm.transactionMasterID),0) as min,

		IFNULL(max(tm.transactionMasterID),0) as max,

		0 as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where 		 

		tm.isActive = 1 and 

		tm.companyID = prCompanyID and 

		tm.transactionID = 19  and 

		tm.statusID = 67  and 		

		tm.createdOn between prStartOn  and prEndOn ;

		

	update tb_tmp_closed set 

		cantidad = IFNULL((select u.transactionNumber from tb_transaction_master u where u.transactionMasterID = cantidad ),0),

		subtotal = IFNULL((select u.transactionNumber from tb_transaction_master u where u.transactionMasterID = subtotal ),0)

	where 

		codigo = '001.001';

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Estado',

		'001.002',

		'TRAN. Eli',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where 		 

		tm.isActive = 1 and 

		tm.transactionID = 19  and 

		tm.statusID = 68  and  

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Estado',

		'001.003',

		'TRAN. Reg',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

	where 		

		tm.isActive = 1 and 

		tm.transactionID = 19  and 

		tm.statusID in ( 66  ) and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Estado',

		'001.003',

		'TRAN. Apli',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

	where 		

		tm.isActive = 1 and 

		tm.companyID = prCompanyID and 

		tm.transactionID = 19  and 

		tm.statusID in (67  ) and 

		tm.createdOn between prStartOn  and prEndOn ;

	

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura C$',

		'002.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 1 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

		

		

	

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura $',

		'003.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 2 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

		

	

	

		

		

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		*

	from 

		(

		select 

			'Si',

			'Default',

			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end  ) ,

			'004.001',

			concat('Efectivo ' , case when tm.currencyID = 2 then '$' else 'C$' end),

			IFNULL(count(tmi.receiptAmount),0) as efectivo,

			IFNULL(sum(tmi.receiptAmount),0)as transferencia,

			IFNULL(sum(tmi.receiptAmount),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 			

			tmi.receiptAmount > 0 and 

			tm.companyID = prCompanyID and 			

			tm.createdOn between prStartOn  and prEndOn 

		

		union all 

		

		select 

			'Si',

			'Default',

			concat('Formas de Pago ' , case when tm.currencyID = 2 then '$' else 'C$' end ),

			'004.002',

			concat('Tarjeta ', case when tm.currencyID = 2 then '$' else 'C$' end),

			IFNULL(count(tmi.receiptAmountCard),0) as efectivo,

			IFNULL(sum(tmi.receiptAmountCard),0)as transferencia,

			IFNULL(sum(tmi.receiptAmountCard),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 			

			tmi.receiptAmountCard > 0 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn  and prEndOn 

			

		union all 

		

		select 

			'Si',

			'Default',

			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end),

			'004.003',

			concat('Transferencia ', case when tm.currencyID = 2 then '$' else 'C$' end ),

			IFNULL(count(tmi.receiptAmountBank),0) as efectivo,

			IFNULL(sum(tmi.receiptAmountBank),0)as transferencia,

			IFNULL(sum(tmi.receiptAmountBank),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 		

			tmi.receiptAmountBank > 0 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn  and prEndOn 

	) as kl

	where 

		kl.tarjeta > 0 ;

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		*

	from 

		(

			select 

				'Si',

				'Default',

				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),

				'005.001',

				concat('Efectivo ', case when tm.currencyID = 2 then 'C$' else '$' end ),

				IFNULL(count(IFNULL(tmi.receiptAmountDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

				

			union all 

			

			select 

				'Si',

				'Default',

				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),

				'005.002',

				concat('Tarjeta ',case when tm.currencyID = 2 then 'C$' else '$' end),

				IFNULL(count(IFNULL(tmi.receiptAmountCardDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountCardDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

				

			union all 

			

			select 

				'Si',

				'Default',

				concat('Formas de Pago ',case when tm.currencyID = 2 then 'C$' else '$' end),

				'005.003',

				concat('Transferencia ',case when tm.currencyID = 2 then 'C$' else '$' end),

				IFNULL(count(IFNULL(tmi.receiptAmountBankDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountBankDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

	) as kl

 where 

	kl.tarjeta > 0 ; 				

			

		

	

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.001',

		concat('Vent Cred ',case when tm.currencyID = 1 then 'C$' else '$' end),

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where 

		t.transactionID = 19  and 

		tmc.transactionCausalID in (22  , 24  ) and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.002',

		concat('Vent Cont ',case when tm.currencyID = 1 then 'C$' else '$' end),

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where 

		t.transactionID = 19  and 

		tmc.transactionCausalID in (23  , 21  ) and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.003',

		'Abonos C$',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tmi.receiptAmount),0) as subtotal,

		IFNULL(sum(tmi.receiptAmount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_master_info tmi on 

			tm.transactionMasterID = tmi.transactionMasterID  

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_company com on 

			com.companyID = t.companyID 

	where

		tm.transactionID = 23 and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn and 

		tmi.receiptAmount > 0 ;

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.004',

		'Abonos $',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tmi.receiptAmountDol),0) as subtotal,

		IFNULL(sum(tmi.receiptAmountDol),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_master_info tmi on 

			tm.transactionMasterID = tmi.transactionMasterID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_company com on 

			com.companyID = t.companyID 

	where 

		tm.transactionID = 23 and 			

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn and 

		tmi.receiptAmountDol > 0 ;

	

	

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.005',

		'Ing. Efectivo C$',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 1 and 		

		ci2.`name` != 'Apertura' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.006',

		'Ing. Efectivo $',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 2 and 

		ci2.`name` != 'Apertura' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.007',

		'Primas C$',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(

		sum(

			tmi.receiptAmount + 

			tmi.receiptAmountDol +

			tmi.receiptAmountBank + 

			tmi.receiptAmountBankDol + 

			tmi.receiptAmountCard + 

			tmi.receiptAmountCardDol 

		),

		0) as subtotal,

		IFNULL(

		sum(

			tmi.receiptAmount + 

			tmi.receiptAmountDol + 

			tmi.receiptAmountBank + 

			tmi.receiptAmountBankDol + 

			tmi.receiptAmountCard + 

			tmi.receiptAmountCardDol 

		),

		0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_transaction_master_info tmi on 

			tmi.transactionMasterID = tm.transactionMasterID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where 

		t.transactionID = 19  and 

		tmc.transactionCausalID in (22  , 24  ) and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

		

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.008',

		'Egr. Efectivo C$',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 1 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	

			

			

			

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'No',

		'Default',

		'Operaciones',

		'006.009',

		'Egr. Efectivo $',

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 2 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn ;

		

	

	

		

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Cierre C$',

		'007.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 1 and 

		ci2.`name` = 'Cierre' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Cierre $',

		'008.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 2 and 

		ci2.`name` = 'Cierre' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

	

		

	

	

	

	IF varCompanyType != "galmcuts" THEN 



		SET varDif = 0;	

		

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont C$' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura C$' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos C$' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas C$' );

		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo C$' );

		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre C$' );

		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

		values ('No','Default','Diferencia','009.001','Dif C$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);

		

		

		

		SET varDif = 0;

		

		

		

		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont $' );

		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura $' );	

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos $' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas $' );

		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo $' );

		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre $' );

		

		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

		values ('No','Default','Diferencia','009.002','Dif $',0,ifnull(varDif,0),ifnull(varDif,0),1,0);

		

	end if;

	

	

	IF varCompanyType = "galmcuts" THEN 



		SET varDif = 0;	

		

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont C$' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura C$' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos C$' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas C$' );

		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo C$' );

		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre C$' );

		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

		values ('No','Default','Saldo Final Caja','009.001','C$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);

		

		

		

		SET varDif = 0;

		

		

		

		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont $' );

		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura $' );	

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos $' );

		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas $' );

		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo $' );

		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre $' );

		

		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

		values ('No','Default','Saldo Final Caja','009.002','$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);

		

	end if;

	

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Detalle de Venta' as subtitulo,

				'010.001' as codigo,

				i.`name` as nombre,		

				td.quantity as cantidad,

				td.amount as subtotal,

				td.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_master_detail td on 

					td.transactionMasterID = tm.transactionMasterID 

				inner join tb_item i on 

					td.componentItemID = i.itemID 

				inner join tb_item_category  cat on 

					cat.inventoryCategoryID = i.inventoryCategoryID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

			where 

				t.transactionID = 19  and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				td.isActive = 1 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				cat.`name`,i.`name`

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

	

		

	

	SET amount1 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Vent Cont C$');

	SET amount2 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Efectivo $'  and k.substitulo = 'Formas de Pago $');

	SET amount3 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Efectivo C$' and k.substitulo = 'Formas de Pago C$');

	SET amount4 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Tarjeta C$' and k.substitulo = 'Formas de Pago C$');

	SET amount1 = IFNULL(amount1,0);

	SET amount2 = IFNULL(amount2,0);

	SET amount3 = IFNULL(amount3,0);

	SET amount4 = IFNULL(amount4,0);

	

	

	UPDATE tb_tmp_closed set 

		total = amount1 -  (amount2 * 36.5) - amount4,

		subtotal = amount1 -  (amount2 * 36.5) - amount4 

	WHERE 

		nombre = 'Efectivo C$' and 

		substitulo = 'Formas de Pago C$';

		

		

	

	

		

			

	select 

		sumary,

		comandoProce,

		substitulo,

		codigo,

		nombre,

		cantidad,

		subtotal,

		total,

		moneda,

		tipoCambio

	from 

		tb_tmp_closed 

	order by 

		codigo asc ;

		

			

	drop table tb_tmp_closed;		

	

	

	 

	 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_glamcuts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_closed_glamcuts`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de abonos de los clientes'
BEGIN

	

	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);			

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE PERMISSION_NONE INT DEFAULT -1;

	DECLARE PERMISSION_ALL INT DEFAULT 0;

	DECLARE PERMISSION_BRANCH INT DEFAULT 1;

	DECLARE PERMISSION_ME INT DEFAULT 2; 		

	DECLARE isAdmin_ INT DEFAULT   0; 

	DECLARE varDif DECIMAL(19,4) DEFAULT 0;

	DECLARE varCompanyType VARCHAR(50);

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	create table tb_tmp_closed (

		substitulo varchar(250),

		codigo varchar(50),

		nombre varchar(150),

		cantidad varchar(150),

		subtotal varchar(150),

		total decimal(19,2),

		moneda int ,

		tipoCambio decimal(19,2),

		comandoProce varchar(150),

		sumary varchar(50)

		

	);

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura C$',

		'001.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 1 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

		

		

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos C$',

		'002.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 1 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura $',

		'003.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 2 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos $',

		'004.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 2 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Detalle de Venta' as subtitulo,

				'005.001' as codigo,

				i.`name` as nombre,		

				td.quantity as cantidad,

				td.amount as subtotal,

				td.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_master_detail td on 

					td.transactionMasterID = tm.transactionMasterID 

				inner join tb_item i on 

					td.componentItemID = i.itemID 

				inner join tb_item_category  cat on 

					cat.inventoryCategoryID = i.inventoryCategoryID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

			where 

				t.transactionID = 19  and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				td.isActive = 1 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				cat.`name`,i.`name`

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

	

	

	

		

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		*

	from 

		(

		select 

			'Si',

			'Default',

			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end  ) ,

			'006.001',

			concat('Efectivo ' , case when tm.currencyID = 2 then '$' else 'C$' end),

			IFNULL(count(tmi.receiptAmount),0) as efectivo,

			IFNULL(sum(tmi.receiptAmount),0)as transferencia,

			IFNULL(sum(tmi.receiptAmount),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 			

			tmi.receiptAmount > 0 and 

			tm.companyID = prCompanyID and 			

			tm.createdOn between prStartOn  and prEndOn 

		

		union all 

		

		select 

			'Si',

			'Default',

			concat('Formas de Pago ' , case when tm.currencyID = 2 then '$' else 'C$' end ),

			'006.002',

			concat('Tarjeta ', case when tm.currencyID = 2 then '$' else 'C$' end),

			IFNULL(count(tmi.receiptAmountCard),0) as efectivo,

			IFNULL(sum(tmi.receiptAmountCard),0)as transferencia,

			IFNULL(sum(tmi.receiptAmountCard),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 			

			tmi.receiptAmountCard > 0 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn  and prEndOn 

			

		union all 

		

		select 

			'Si',

			'Default',

			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end),

			'006.003',

			concat('Transferencia ', case when tm.currencyID = 2 then '$' else 'C$' end ),

			IFNULL(count(tmi.receiptAmountBank),0) as efectivo,

			IFNULL(sum(tmi.receiptAmountBank),0)as transferencia,

			IFNULL(sum(tmi.receiptAmountBank),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 		

			tmi.receiptAmountBank > 0 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn  and prEndOn 

	) as kl

	where 

		kl.tarjeta > 0 ;

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		*

	from 

		(

			select 

				'Si',

				'Default',

				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),

				'007.001',

				concat('Efectivo ', case when tm.currencyID = 2 then 'C$' else '$' end ),

				IFNULL(count(IFNULL(tmi.receiptAmountDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountDol - (tmi.changeAmount * tm.exchangeRate) ,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountDol - (tmi.changeAmount * tm.exchangeRate) ,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

				

			union all 

			

			select 

				'Si',

				'Default',

				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),

				'007.002',

				concat('Tarjeta ',case when tm.currencyID = 2 then 'C$' else '$' end),

				IFNULL(count(IFNULL(tmi.receiptAmountCardDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountCardDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

				

			union all 

			

			select 

				'Si',

				'Default',

				concat('Formas de Pago ',case when tm.currencyID = 2 then 'C$' else '$' end),

				'007.003',

				concat('Transferencia ',case when tm.currencyID = 2 then 'C$' else '$' end),

				IFNULL(count(IFNULL(tmi.receiptAmountBankDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountBankDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

	) as kl

 where 

	kl.tarjeta > 0 ; 				

		

		

			

	select 

		sumary,

		comandoProce,

		substitulo,

		codigo,

		nombre,

		cantidad,

		subtotal,

		total,

		moneda,

		tipoCambio

	from 

		tb_tmp_closed 

	order by 

		codigo asc ;

		

			

	drop table tb_tmp_closed;		

	

	

	 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_gym` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_closed_gym`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de abonos de los clientes'
BEGIN

	

	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);			

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE PERMISSION_NONE INT DEFAULT -1;

	DECLARE PERMISSION_ALL INT DEFAULT 0;

	DECLARE PERMISSION_BRANCH INT DEFAULT 1;

	DECLARE PERMISSION_ME INT DEFAULT 2; 		

	DECLARE isAdmin_ INT DEFAULT   0; 

	DECLARE varDif DECIMAL(19,4) DEFAULT 0;

	DECLARE varCompanyType VARCHAR(50);

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	SET isAdmin_ 						= (case when isAdmin_ is null then 0 else isAdmin_ end);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 				= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	create table tb_tmp_closed (

		substitulo varchar(250),

		codigo varchar(50),

		nombre varchar(150),

		cantidad varchar(150),

		subtotal varchar(150),

		total decimal(19,2),

		moneda int ,

		tipoCambio decimal(19,2),

		comandoProce varchar(150),

		sumary varchar(50)

		

	);

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura C$',

		'001.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 1 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

		

		

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos C$',

		'002.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 1 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura $',

		'003.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 2 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos $',

		'004.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 2 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Detalle de Venta' as subtitulo,

				'005.001' as codigo,

				i.`name` as nombre,		

				td.quantity as cantidad,

				td.amount as subtotal,

				td.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_master_detail td on 

					td.transactionMasterID = tm.transactionMasterID 

				inner join tb_item i on 

					td.componentItemID = i.itemID 

				inner join tb_item_category  cat on 

					cat.inventoryCategoryID = i.inventoryCategoryID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

			where 

				t.transactionID = 19  and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				td.isActive = 1 and  

				tm.companyID = prCompanyID and 

				ifnull(i.realStateRoomBatchServices,0) = 0 and 

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				cat.`name`,i.`name`

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

	

	

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Abonos' as subtitulo,

				'005.002' as codigo,

				nat.firstName as nombre,		

				1 as cantidad,

				tm.amount as subtotal,

				tm.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 			

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_naturales nat on 

					nat.entityID = tm.entityID 

			where 

				t.transactionID = 23   and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tm.companyID = prCompanyID and 				

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				nat.firstName 

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

		

		

		

		

			

	select 

		sumary,

		comandoProce,

		substitulo,

		codigo,

		nombre,

		cantidad,

		subtotal,

		total,

		moneda,

		tipoCambio

	from 

		tb_tmp_closed 

	order by 

		codigo asc ;

		

			

	drop table tb_tmp_closed;		

	

	

	 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_operation` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_closed_operation`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prUserBoxID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Reporte de cierre de caja'
BEGIN





SELECT 

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  cus.customerNumber as Entidad,

  CONCAT(nat.firstName, ' ', nat.lastName) as NombreCliente,

  tm.reference1 as Referencia1,

  tm.reference2 as Referencia2,

  tm.reference3 as Referencia3,

  tm.reference4 as Referencia4,

  tm.note as Concepto,

  emp.employeNumber as CodigoEmpleado,

  '' as SubCategoria,

  '' as Categoria,

  tc.name as ESTADO,

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

FROM 

  tb_transaction_master tm

  inner join tb_transaction_causal tc on 

    tc.transactionCausalID = tm.transactionCausalID

  inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID

  INNER JOIN tb_customer cus on tm.entityID = cus.entityID

  INNER JOIN tb_naturales nat on cus.entityID = nat.entityID

  INNER JOIN tb_user us on tm.createdBy = us.userID

  INNER JOIN tb_employee emp on us.employeeID = emp.entityID

  INNER JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  INNER JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE 

  tm.transactionID = 19  and 

  tm.isActive = 1 and 

  ws.aplicable = 1 and 

  tm.createdOn BETWEEN prStartOn AND prEndOn  and 

  (

    (tm.createdBy = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );





SELECT 

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  cus.customerNumber as Entidad,

  CONCAT(nat.firstName, ' ', nat.lastName) as NombreCliente,

  tm.reference1 as Referencia1,

  tm.reference2 as Referencia2,

  tm.reference3 as Referencia3,

  tm.reference4 as Referencia4,

  tm.note as Concepto,

  emp.employeNumber as CodigoEmpleado,

  '' as SubCategoria,

  '' as Categoria,

  ws.`name` as ESTADO,

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

FROM tb_transaction_master tm

inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID

INNER JOIN tb_customer cus on tm.entityID = cus.entityID

INNER JOIN tb_naturales nat on cus.entityID = nat.entityID

INNER JOIN tb_user us on tm.createdBy = us.userID

INNER JOIN tb_employee emp on us.employeeID = emp.entityID

INNER JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

INNER JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE tm.transactionID = 23 and tm.isActive = 1 and ws.aplicable = 1

and tm.createdOn BETWEEN prStartOn AND prEndOn

and 

  (

    (tm.createdBy = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );





SELECT   

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  caja.cashBoxCode as Entidad,

  caja.description as NombreCliente,

  '' as Referencia1,

  '' as Referencia2,

  '' as Referencia3,

  '' as Referencia4,

  tm.note as Concepto,

  usu.nickname as CodigoEmpleado,

  '' as SubCategoria,

  area.name as Categoria,

  ws.name as ESTADO,

  

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

  

FROM 

  tb_transaction_master tm

  LEFT JOIN tb_workflow_stage ws 

    ON tm.statusID = ws.workflowStageID 

  LEFT JOIN tb_cash_box caja 

    ON caja.cashBoxID = tm.classID 

  LEFT JOIN (

    SELECT 

      tcbu.cashBoxID, 

      tcbu.userID as userID 

    FROM tb_cash_box_user tcbu

    GROUP BY cashBoxID

  ) caja_usu 

    ON caja_usu.cashBoxID = caja.cashBoxID

  LEFT JOIN tb_user usu 

    ON usu.userID = caja_usu.userID  

  LEFT JOIN tb_catalog_item area 

    ON area.catalogItemID = tm.areaID 

  LEFT JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  LEFT JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE 

  tm.transactionID = 29  and 

  tm.isActive = 1 and ws.aplicable = 1 and 

  tm.createdOn BETWEEN prStartOn AND prEndOn and 

  (

    (caja_usu.userID = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );



SELECT   

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  caja.cashBoxCode as Entidad,

  caja.description as NombreCliente,

  '' as Referencia1,

  '' as Referencia2,

  '' as Referencia3,

  '' as Referencia4,

  tm.note as Concepto,

  usu.nickname as CodigoEmpleado,

  '' as SubCategoria,

  area.name as Categoria,

  ws.name as ESTADO,

  

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

  

FROM 

  tb_transaction_master tm

  LEFT JOIN tb_workflow_stage ws 

    ON tm.statusID = ws.workflowStageID 

  LEFT JOIN tb_cash_box caja 

    ON caja.cashBoxID = tm.classID 

  LEFT JOIN (

    SELECT 

      tcbu.cashBoxID, 

      tcbu.userID as userID 

    FROM tb_cash_box_user tcbu

    GROUP BY cashBoxID

  ) caja_usu 

    ON caja_usu.cashBoxID = caja.cashBoxID

  LEFT JOIN tb_user usu 

    ON usu.userID = caja_usu.userID  

  LEFT JOIN tb_catalog_item area 

    ON area.catalogItemID = tm.areaID 

  LEFT JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  LEFT JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE tm.transactionID = 30 and tm.isActive = 1 and ws.aplicable = 1

and tm.createdOn BETWEEN prStartOn AND prEndOn

and 

  (

    (caja_usu.userID = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );





SELECT

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  pro.providerNumber as Entidad,

  CONCAT(nat.firstName, ' ', nat.lastName) as NombreCliente,

  tm.reference1 as Referencia1,

  tm.reference2 as Referencia2,

  tm.reference3 as Referencia3,

  tm.reference4 as Referencia4,

  tm.note as Concepto,

  us.nickname as CodigoEmpleado,

  '' as SubCategoria,

  ci.name as Categoria,

  ws.name as ESTADO,



  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

FROM 

  tb_transaction_master tm

  inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID

  INNER JOIN tb_user us on tm.createdBy = us.userID

  INNER JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  INNER JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

  INNER JOIN tb_catalog_item ci on ci.catalogItemID = tm.priorityID   

  LEFT JOIN tb_provider pro on tm.entityID = pro.entityID

  LEFT JOIN tb_naturales nat on pro.entityID = nat.entityID  

WHERE 

  tm.transactionID= 38 and 

  tm.isActive = 1 and 

  ws.aplicable = 1 and 

  tm.createdOn BETWEEN prStartOn AND prEndOn

and 

  (

    (tm.createdBy = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );

  

  END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_input_cash` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_input_cash`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prAuthorization` INT, IN `prStartOn` DATE, IN `prEndOn` DATE, IN `prUserIDFilter` INT , IN prConceptFilter VARCHAR(150) ,IN `prBranchID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'listado de ingresos y egresos de efectivo de la caja'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 
	DECLARE isAdmin_ INT DEFAULT   0;
  DECLARE convert_ VARCHAR(50);	 

	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
		inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);	
  drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;

	SELECT 
		tt.name as transactionName,	
		tm.transactionNumber ,
		tm.createdOn as transactionOn,
		tm.amount as montoTotal ,
		ws.name as estado,
		tm.note,		
		tmd.reference1 as Fac,
		if(
			cur.name = 'Dolar',
			tmd.amount * (exchangeRate_  + currencyTargetSale),
			tmd.amount
		)  as montoCordoba,
    case 
			when convert_ = 'Dolar' and cur.name != 'Dolar'  then 
				tmd.amount * (exchangeRate_  )
			when convert_ = 'Cordoba' and cur.name != 'Cordoba'  then 
				tmd.amount / (exchangeRate_ )
			else 
				tmd.amount 
		end as montoTransaccion,
		case 
			when convert_ = 'None'  then 
      cur.`name`
			else 
				convert_ 
		end as moneda ,
		(exchangeRate_  + currencyTargetSale) as tipoCambio ,
		PERMISSION_ME,
		prAuthorization,
		tm.createdBy ,
		us.nickname ,		
		ten.`name` as tipoEntrada,
		subten.`name` as tipoSubEntrada,
		if(LENGTH(tm.note)  > 0 , tm.note ,CONCAT(tm.reference1,'-',tm.reference2,'-',tm.reference3) )as note 
	FROM 
		tb_transaction_master tm
		inner join tb_transaction tt on 
			tm.transactionID = tt.transactionID 	
		inner join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 	
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID  	
    inner join tb_currency cur on 
			tm.currencyID = cur.currencyID 
		inner join tb_user us on 
			us.userID = tm.createdBy 
		left join tb_catalog_item ten on 
			ten.catalogItemID = tm.areaID 
		left join tb_catalog_item subten on 
			subten.catalogItemID = tm.priorityID 
	where
		tm.transactionID IN  (29) 		
		and tm.isActive = 1 
		and tmd.isActive = 1 
		and tm.companyID = prCompanyID 
		AND ws.aplicable = 1  
		and 
		(
			(tm.branchID = prBranchID and prBranchID != 0 )
			or 
			(prBranchID = 0)
		)
		and cast(tm.createdOn as date) between prStartOn and concat(prEndOn,' 23:59:59') 
		and  
		(
				fn_get_access_ready(
					prCompanyID  , 
					prUserID  , 173  , 
					tm.createdBy  , 0 
				) = 1 
		) and 
		(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
		) and 
		(
			(prConceptFilter = '-1') or
			(
					prConceptFilter != '-1' and 
					tm.areaID in 
					(
						select val  from tb_tmp_split 
					)
			) 
		)
	order by 
		tm.createdOn;   
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_output_cash` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_output_cash`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prAuthorization` INT, IN `prStartOn` DATE, IN `prEndOn` DATE, IN `prUserIDFilter` INT,IN prConceptFilter VARCHAR(150) ,IN `prBranchID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'listado de ingresos y egresos de efectivo de la caja'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 
	DECLARE isAdmin_ INT DEFAULT   0;
  DECLARE convert_ VARCHAR(50);	 

	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
    inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);	
  drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;

	SELECT 			
		tt.name as transactionName,	
		tm.transactionNumber ,
		tm.createdOn as transactionOn,
		tm.amount as montoTotal ,
		ws.name as estado,
		tm.note,		
		case 
			when tm.transactionID = 30  then 
				tmd.reference1 
			else 
				''
		end as Fac,
		case 
			when tm.transactionID = 30  then 
				if(
					cur.name = 'Dolar',
					tmd.amount * (exchangeRate_  + currencyTargetSale),
					tmd.amount
				)  
			else 
				tm.amount 
		end as montoCordoba,
		case 
			when convert_ = 'Dolar' and cur.name != 'Dolar'  then 
				tmd.amount * (exchangeRate_  )
			when convert_ = 'Cordoba' and cur.name != 'Cordoba'  then 
				tmd.amount / (exchangeRate_  )
			else 
				tmd.amount 
		end as montoTransaccion,
		case 
			when convert_ = 'None'  then 
      cur.`name`
			else 
				convert_ 
		end as moneda  ,
		(exchangeRate_  + currencyTargetSale) as tipoCambio ,
		PERMISSION_ME,
		prAuthorization,
		tm.createdBy ,
		us.nickname ,
		case 
			when tm.transactionID = 30  then  
				ten.`name`
			else 
				''
		end  as tipoSalida,
		case 
			when tm.transactionID = 30  then 
				subten.`name` 
			else 
				''
		end as tipoSubSalida,
		if(LENGTH(tm.note)  > 0 , tm.note ,CONCAT(tm.reference1,'-',tm.reference2,'-',tm.reference3) )as notev2
	FROM 
		tb_transaction_master tm
		inner join tb_transaction tt on 
			tm.transactionID = tt.transactionID 	
		left join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 	and 
			tmd.isActive = 1 
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID  	
		inner join tb_currency cur on 
			tm.currencyID = cur.currencyID 
		inner join tb_user us on 
			us.userID = tm.createdBy 
		left join tb_catalog_item ten on 
			ten.catalogItemID = tm.areaID 
		left join tb_catalog_item subten on 
			subten.catalogItemID = tm.priorityID 
	where
		tm.transactionID IN  (30  ) 		
		and tm.isActive = 1 
		and tm.companyID = prCompanyID 
		AND ws.aplicable = 1  
		and 
		(
			(tm.branchID = prBranchID and prBranchID != 0 )
			or 
			(prBranchID = 0)
		) 
		and cast(tm.createdOn as date) between prStartOn and concat(prEndOn,' 23:59:59') 
		and  
		(			
				fn_get_access_ready(
					prCompanyID  , prUserID  , 
					173  , 
					tm.createdBy  , 0 
				) = 1 
		) and 
		(
			(tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
			or 
			(prUserIDFilter = 0)
		) and 
		(
			(prConceptFilter = '-1') or
			(
					prConceptFilter != '-1' and 
					tm.areaID in 
					(
						select val  from tb_tmp_split 
					)
			) 
		)
	order by 
		tm.createdOn;   
END;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_box_get_report_reconciliation_deposit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_box_get_report_reconciliation_deposit`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prEmployeeCode` VARCHAR(50),prStartOn DATETIME , prEndOn DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	declare varDate date;

	declare varZonaHoraria int default 0; 

	 

  set varZonaHoraria = (

			select 				

				uu.value  

			from 

				tb_parameter u 

				inner join tb_company_parameter uu on 

					uu.parameterID = u.parameterID

			where 

				u.`name` = 'CORE_ZONA_HORARIA' and 

				uu.companyID = 2 

	);

	

	set varDate =  date_add(now(), interval varZonaHoraria hour);	

	set varDate =  date_add(prEndOn, interval varZonaHoraria hour);	

	set varDate =  prEndOn;

	set varDate =  date_add(varDate, interval 23 hour);	

	set varDate =  date_add(varDate, interval 59 MINUTE);	

	set varDate =  date_add(varDate, interval 59 SECOND);	

	

	select 

	emp.employeNumber as FiltroCode ,

	empn.firstName as FiltroName,

	

	

	emp.employeNumber as NoGestor , 

	empn.firstName as Gestor,

	

	cus.customerNumber as NoCliente,

	nat.firstName as Cliente,

	

	

	tm.transactionOn as Fecha,

	tm.transactionNumber as Documento,

	tm.amount as Monto,

	tm.currencyID ,

	cur.`name` as Moneda 

	

from 

	tb_transaction_master tm 

	inner join tb_currency cur on 

		cur.currencyID = tm.currencyID 

	inner join tb_workflow_stage ws on 

		ws.workflowStageID = tm.statusID 

	inner join tb_naturales nat on 

		nat.entityID = tm.entityID 

	inner join tb_customer cus on 

		cus.entityID = nat.entityID 

	inner join tb_user usr on 

		usr.userID = tm.createdBy 

	inner join tb_employee emp on 

		emp.entityID = usr.employeeID 

	inner join tb_naturales empn on 

		empn.entityID = emp.entityID 

where 

	tm.isActive = 1 and 

	tm.transactionID  in (23  ) and 

	ws.aplicable =  1 and 

	tm.transactionOn BETWEEN prStartOn AND varDate and 

	(

		(

			emp.employeNumber = prEmployeeCode and (prEmployeeCode != 'EMP00000000' )

		)

		or 

		(

			'EMP00000000' = prEmployeeCode 

		)

	)  ;



	 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_collection_get_report_commision_provider` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_collection_get_report_commision_provider`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prStart` DATETIME, IN `prEnd` DATETIME, IN `prProviderID` INT)
    COMMENT 'Procedimiento para obtener la lista de movimientos y sus comisiones.'
BEGIN





CREATE TEMPORARY TABLE tmp_customer_info 					

(

	ID INT AUTO_INCREMENT PRIMARY KEY,

	TelefonoCobrador VARCHAR(50),

	NombreCobrador VARCHAR(150),

	

	CodigoProveedor VARCHAR(50),

	NombreProveedor VARCHAR(150),

	

	CodigoCliente VARCHAR(50),

	NombreCliente VARCHAR(150),

	TelefonoCliente VARCHAR(50),

	

	Factura VARCHAR(50),

	CodigoMovimiento VARCHAR(50),

	FechaMovimiento DATETIME,

	TipoMovimiento VARCHAR(80),

	FrecuenciaPagoMovimiento VARCHAR(50),

	PrimerFechaPagoMovimiento DATETIME,

	

	Balance DECIMAL(19,2),

	SaldoInicial DECIMAL(19,2),

	Abono DECIMAL(19,2),

	SaldoFinal DECIMAL(19,2),

	InteresTotalDelAbono DECIMAL(19,2),

	CapitalTotalDelAbono DECIMAL(19,2),

	CapitalDesembolso DECIMAL(19,2),

	InterestTotalDelCredito DECIMAL(19,2),

	GastoFijoMonto DECIMAL(19,2),

	GastoFijoPorcentaje DECIMAL(19,2),

	RendimientoCompartido DECIMAL(19,2),

	RendimientoXComision DECIMAL(19,2),

	RendimientoXProveedor DECIMAL(19,2),

	DepositoAProveedor DECIMAL(19,2) 	

); 

			

insert into tmp_customer_info	(	

	TelefonoCobrador ,	NombreCobrador ,CodigoProveedor,	NombreProveedor,CodigoCliente,NombreCliente,TelefonoCliente ,

	

	Factura,	CodigoMovimiento ,FechaMovimiento ,	TipoMovimiento,	FrecuenciaPagoMovimiento,PrimerFechaPagoMovimiento, 



	Balance,

	SaldoInicial,

	Abono ,

	SaldoFinal ,

	InteresTotalDelAbono ,

	CapitalTotalDelAbono ,

	CapitalDesembolso ,

	InterestTotalDelCredito ,

	GastoFijoMonto,

	GastoFijoPorcentaje ,

	RendimientoCompartido ,

	RendimientoXComision ,

	RendimientoXProveedor ,

	DepositoAProveedor 

	

)

select 

	

	'N/D' as TelefonoCobrador,

	

	'N/D' as NombreCobrador,

	

	proveedor.providerNumber as CodigoProveedor,

	if(prProviderID = 0,'TODOS',concat(prov.firstName ,' ',prov.lastName )) as NombreProveedor,

	

	cus.customerNumber as CodigoCliente,

	concat(cliente.firstName ,' ',cliente.lastName ) as NombreCliente,

	if(phoneCliente.number is null,'N/D',phoneCliente.number) as TelefonoCliente,

	

	movi.Factura,

	movi.CodigoMovimiento as CodigoTransaccion,

	movi.FechaMovimiento as FechaTransaccion,

	movi.TipoMovimiento as TipoTransaccion,

	resument.FrecuenciaPagoMovimiento,

	resument.PrimerFechaPagoMovimiento,

	

	resument.balance,

	CAST(movi.saldo_inicial AS DECIMAL(19,2)) as saldo_inicial,

	(movi.IMPORTE + movi.INTERES) as abono,

	CAST(movi.saldo_final AS DECIMAL(19,2)) as saldo_final,

	movi.INTERES,

	movi.IMPORTE,

	resument.desembolso,

	resument.interes,

	(resument.PorGastos / 100) * (movi.INTERES)  as GastosFijoMonto,

	resument.PorGastos as GastosFijoPorcentaje,

	(movi.INTERES  * (1 - (resument.PorGastos / 100))) as RendimientoCompartido,

	(movi.INTERES  * (1 - (resument.PorGastos / 100))) * 0.3 as RendimientoComision,

	(movi.INTERES  * (1 - (resument.PorGastos / 100))) * 0.7 as RendimientoProveedor,

	((movi.INTERES * (1 - (resument.PorGastos / 100))) * 0.7)  + movi.IMPORTE as DepositoProveedor

	

from

	tb_customer_credit_document ccc

	inner join tb_naturales cliente on 

		ccc.entityID = cliente.entityID		

	inner join tb_customer cus on 

		cus.companyID = cliente.companyID and 

		cus.entityID = cliente.entityID 

	inner join tb_provider proveedor on 

		ccc.companyID = proveedor.companyID and 

		ccc.providerIDCredit = proveedor.entityID 

	inner join tb_naturales prov on 

		prov.companyID = proveedor.companyID and 

		prov.entityID = proveedor.entityID 

	inner join tb_relationship relation on 

		ccc.entityID = relation.customerID 

	

	

	

	

	left join tb_entity_phone phoneCliente on 

		phoneCliente.entityID = ccc.entityID and phoneCliente.isPrimary = 1

	inner join (	

		select 

			tm.transactionNumber as CodigoMovimiento,

			td.reference1 as Factura ,

			tm.createdOn as FechaMovimiento,

			tm.amount as Monto,

			t.name as TipoMovimiento,

			tmc.transactionID ,

			td.reference2 as saldo_inicial,

			td.reference4 as saldo_final,			

			MAX(CASE WHEN tcon.name = "IMPORTE" THEN tmc.value END) as "IMPORTE",

			MAX(CASE WHEN tcon.name = "INTERES" THEN tmc.value END) as "INTERES" 

		from 

			tb_transaction_master tm 

			inner join tb_transaction_master_detail td on 

				tm.companyID = td.companyID and 

				tm.transactionID = td.transactionID and 

				tm.transactionMasterID = td.transactionMasterID 

			inner join tb_transaction t on 

				tm.transactionID = t.transactionID 			

			inner join tb_transaction_master_concept tmc on 

				tmc.companyID = td.companyID and 

				tmc.transactionID = td.transactionID and 

				tmc.transactionMasterID = td.transactionMasterID and 

				tmc.componentID = td.componentID and 

				tmc.componentItemID = td.componentItemID 

			inner join tb_transaction_concept tcon on 

				tcon.conceptID = tmc.conceptID 

		where

			tcon.name <> 'GANANCIA X T/C' and  

			CONVERT(tm.createdOn , DATE)  between prStart and prEnd and 

			tcon.name in ('IMPORTE','INTERES') and 

			tmc.transactionID in (24  , 23  , 25  )

		group by 

			tm.transactionNumber,

			td.reference1 ,

			tm.createdOn ,

			tm.amount ,

			t.name ,

			tmc.transactionID ,

			td.reference2 ,

			td.reference4 

	) movi on 

		movi.Factura = ccc.documentNumber

	inner join (

		select 

			c.customerCreditDocumentID,

			c.documentNumber,

			c.amount as desembolso,

			c.balance as balance,

			lxn.reference1 as PorGastos,			

			cit.name as  FrecuenciaPagoMovimiento,

			

			min(cl.dateApply) as  PrimerFechaPagoMovimiento,			

			sum(cl.interest) as interes,

			sum(cl.capital) as capital

		from 

			tb_customer_credit_document c

			inner join tb_catalog_item cit on 

				c.periodPay = cit.catalogItemID and 

				cit.catalogID = 43  

			inner join tb_customer_credit_amoritization cl on

				c.customerCreditDocumentID = cl.customerCreditDocumentID 

			inner join tb_transaction_master tml on 

				c.documentNumber = tml.transactionNumber and tml.transactionID = 19 

			inner join tb_transaction_master_detail_credit lxn on 

				tml.transactionMasterID = lxn.transactionMasterID  			

		group by 

			c.customerCreditDocumentID,

			c.documentNumber,

			c.amount ,

			c.balance,

			lxn.reference1 ,

			cit.name 

	) as resument on 

		resument.documentNumber = ccc.documentNumber

where

	(

		prProviderID = 0 

		and 

		(

		 	(

			 (ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0)

			)

		   or 

			(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	)

	or 

	(

		prProviderID <> 0

		and 

		(

			ccc.providerIDCredit = prProviderID

		)

	);



		

select 	

	TelefonoCobrador ,	

	NombreCobrador ,

	CodigoProveedor,	

	NombreProveedor,

	CodigoCliente,

	NombreCliente,

	TelefonoCliente ,

	Factura as CodigoDesembolso,	

	CodigoMovimiento  as CodigoTransaccion,

	FechaMovimiento as FechaTransaccion,	

	TipoMovimiento as TipoTransaccion,	

	FrecuenciaPagoMovimiento,

	PrimerFechaPagoMovimiento,



	Balance,

	SaldoInicial,

	Abono ,

	SaldoFinal ,

	InteresTotalDelAbono ,

	CapitalTotalDelAbono ,

	CapitalDesembolso ,

	InterestTotalDelCredito ,

	GastoFijoMonto,

	GastoFijoPorcentaje ,

	RendimientoCompartido ,

	RendimientoXComision ,

	RendimientoXProveedor ,

	DepositoAProveedor 



from 

	tmp_customer_info x

order by 

	x.FechaMovimiento;



	

DROP TABLE tmp_customer_info;

		

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_collection_get_report_customer` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_collection_get_report_customer`(IN `prUserID` INT, IN `prToken` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de clientes de los gestores de credito'
BEGIN

	DECLARE isAdmin_ INT DEFAULT   0;



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);



	

	

	

	

	select 

		customer.entityID,

		customer.customerCreditDocumentID,

		customer.Gestor,

		customer.CodigoCliente,

		customer.Cliente, 

		customer.TipoTelefono,

		customer.Telefono,

		customer.Factura,

		facturas.dias_atrazo,

		facturas.dias_proximo_pago 

	from 

		(

		select 

			c.entityID,

			IFNULL( gestor.firstName,'**SIN GESTOR') as Gestor,

			c.customerNumber as CodigoCliente,

			concat(nat.firstName,' ',nat.lastName) as Cliente,

			ci.display as TipoTelefono,

			ph.number as Telefono,

			ccc.documentNumber as Factura,

			ccc.customerCreditDocumentID 

		from 	

			tb_customer_credit_document  ccc 

			inner join tb_workflow_stage ws on 

				ccc.statusID = ws.workflowStageID 				

			left join tb_entity_phone ph on 

				ph.entityID = ccc.entityID  and  ph.isPrimary = 1 

			left join tb_catalog_item ci on 

				ph.typeID = ci.catalogItemID 

			inner join tb_customer c on 

				ccc.entityID = c.entityID 

			inner join tb_naturales nat on 

				c.entityID = nat.entityID 

				

			left join tb_relationship r on 

				c.entityID = r.customerID 										

			left join tb_naturales gestor  on 

				r.employeeID = gestor.entityID   

				

		where

			( 

				

				(

				r.employeeID = ( 

										select 

												u.employeeID 

										from 

												tb_user u 

										where 

												u.userID = prUserID 

									) and isAdmin_ = 0 

				)

				or 

				 

				(

					fn_get_access_ready(prCompanyID  , prUserID  , 181  , 0  , 0  ) = 1 

				)

				or

				

				(

				isAdmin_ = 1  

				)

			)

			and ccc.isActive = 1 

			and ws.vinculable = 1 

		) customer 

		inner join (

			select 

				ccc.customerCreditDocumentID,

				max(

				if (

					cccd.dateApply < current_date(),

					DATEDIFF(cccd.dateApply,current_date()) * -1,

					0

				))  as dias_atrazo ,

				min(

				if (

					cccd.dateApply >= current_date(),

					DATEDIFF(cccd.dateApply,current_date()) ,

					0

				))  as dias_proximo_pago

			from 

				tb_customer_credit_document ccc 

				inner join tb_customer_credit_amoritization cccd on 

					ccc.customerCreditDocumentID = cccd.customerCreditDocumentID 

				inner join tb_workflow_stage ws2 on 

					cccd.statusID = ws2.workflowStageID 

			where

				cccd.remaining > 0 

				and ws2.vinculable = 1 

			group by 

				ccc.customerCreditDocumentID 

		

		) facturas on 

			customer.customerCreditDocumentID = facturas.customerCreditDocumentID 

	order by 

		facturas.dias_atrazo desc,

		facturas.dias_proximo_pago asc,

		customer.Cliente; 

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_collection_get_report_detalle_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_collection_get_report_detalle_transaction`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTokenID` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Reporte para ver el calculo de comisiones de los gestores'
BEGIN

	SELECT 

		T.firstName,

		T.mes,

		SUM(T.comision10) AS comision10,

		SUM(T.comision20) AS comision20,

		SUM(T.comision30) AS comision30,

		SUM(T.comision40) AS comision40,

		SUM(T.comision50) AS comision50,

		SUM(T.comision100) AS comision100

	FROM 

		(

			select 

				cobrador.firstName,

				date_format(tm.transactionOn,'%Y-%m') as mes,

				round((tmc.value * 0.1),2) as comision10 ,

				round((tmc.value * 0.2),2) as comision20 ,

				round((tmc.value * 0.3),2) as comision30 ,

				round((tmc.value * 0.4),2) as comision40 ,

				round((tmc.value * 0.5),2) as comision50 ,

				round((tmc.value * 1),2) as comision100 

			from 

				tb_transaction_master tm

				inner join tb_transaction_master_concept tmc on 

					tm.transactionMasterID = tmc.transactionMasterID 

				inner join tb_transaction_concept tc on 

					tmc.conceptID = tc.conceptID 

				inner join tb_naturales cobrador  on 

					cobrador.entityID = tm.reference3 

				inner join tb_employee em on  

					tm.reference3 is not null and 

					tm.reference3 = em.entityID  	

				inner join tb_journal_entry je on 

					tm.journalEntryID = je.journalEntryID 	

				inner join tb_accounting_cycle acc on 

					je.accountingCycleID = acc.componentCycleID 		

			where

				tm.transactionID = 23  and 

				tc.conceptID in ( 36   ) and 

				em.departamentID	= 373  and 

				acc.componentCycleID = prCycleID and 

				acc.componentPeriodID = prPeriodID 

		) T 

	GROUP BY 

		T.firstName,T.mes 

	ORDER BY 

		T.mes DESC, T.firstName ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_collection_get_report_documents_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_collection_get_report_documents_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN prDate DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	DECLARE varMin DATETIME;

	DECLARE varMax DATETIME;

	DECLARE columnas_pivot VARCHAR(10000);

  DECLARE sql_query VARCHAR(10000);

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_document_temp;

	CREATE TEMPORARY TABLE tbl_document_temp AS

	select 

		d.customerCreditDocumentID,

		d.documentNumber,

		DATE(NOW()) as documentOn,

		0 as amount 

	from 

		tb_customer_credit_document d 

	where 

		d.isActive = 1 ; 

		

		

	

	DROP TEMPORARY TABLE IF EXISTS tbl_abonos_temp;

	CREATE TEMPORARY TABLE tbl_abonos_temp AS

	select 

		td.reference1 as documentNumber,

		date(c.transactionOn ) as transactionOn,

		sum(td.amount) as amount 

	from 

		tb_transaction_master c 

		inner join tb_transaction_master_detail td on 

			c.transactionMasterID = td.transactionMasterID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

	where 

		c.transactionID = 23  and 

		c.isActive = 1 and 

		td.isActive = 1 and 

		ws.aplicable = 1 and 

		c.transactionOn >=    (DATE(NOW()) - INTERVAL 10 DAY )

	group by 

		td.reference1,

		date(c.transactionOn ); 

		

		

	

	SET varMin = (DATE(NOW()) - INTERVAL 10 DAY );

	SET varMax = DATE(NOW());

	WHILE varMin < varMax DO 

	

	  

		insert into tbl_document_temp(customerCreditDocumentID,documentNumber,documentOn,amount )

		select 

				d.customerCreditDocumentID,

				d.documentNumber,

				varMin as documentOn ,

				0 as amount 

			from 

				tb_customer_credit_document d 

			where 

				d.isActive = 1 ; 

			

		

		

	  

		SET varMin = varMin + INTERVAL 1 DAY;

	END WHILE;

	

	

	

	UPDATE 

		tbl_document_temp d

		JOIN tbl_abonos_temp a ON 

				d.documentNumber = a.documentNumber and 

				d.documentOn = a.transactionOn

		SET 

			d.amount = a.amount;

			

	

		

    

		SELECT 

				GROUP_CONCAT(

						CONCAT(

								'SUM(CASE WHEN documentOn = ''', 

								DATE(documentOn), 

								''' THEN amount ELSE 0 END) AS ',

								'`',

								DATE(documentOn) , 

								

								

								

								

								

								

								'`'

						)

						ORDER BY DATE(documentOn) DESC 

						SEPARATOR ', '

				) 

		INTO columnas_pivot

		FROM (

				SELECT DISTINCT DATE(documentOn) AS documentOn

				FROM tbl_document_temp

				ORDER BY DATE(documentOn) DESC 

		) AS fechas;



		

    

		DROP TEMPORARY TABLE IF EXISTS tbl_document_pivot_temp;

    SET sql_query = CONCAT(

        ' 

	      CREATE TEMPORARY TABLE tbl_document_pivot_temp AS 

				SELECT 

					 customerCreditDocumentID, ', columnas_pivot, ' ',

        'FROM 

					tbl_document_temp 

				',

        '

				GROUP BY 

						customerCreditDocumentID

				'

    );



	

	 

    

		set @sql = sql_query;

		prepare stmt1 from @sql;

		execute stmt1;

		

		

		

		

	

	

	

	select 

		usu.nickname as orden,

		usu.nickname ,

		cast(cu.createdOn as date) as customerCreatedOn,

		cu.customerNumber,

		concat(nat.firstName,' ',nat.lastName) as customerName,

		d.documentNumber,

		leg.comercialName , 

		cu.identification, 

		sexo.`name` as sexo,

		cu.location, 

		(

			select 

				ph.number 

			from 

				tb_entity_phone ph 

			where 

				ph.entityID = cu.entityID 

			limit 1 

		) as phoneNumber ,

		civil.`name` as statusCivil,

		d.term,

		d.interes,

		d.amount as amountDocument,

		d.dateOn as dateDocument,

		(

			select 

				date(max(p.transactionOn)) 

			from 

				tb_transaction_master p 

				inner join tb_transaction_master_detail pd on 

					p.transactionMasterID = pd.transactionMasterID 

			where 

				p.isActive = 1 and 

				pd.isActive = 1 and 

				pd.reference1 = d.documentNumber 

				

		) as dateLastShareDocument,

		(

			select 

				sum(xaa.`share`) as total 

			from 

				tb_customer_credit_document xd 

				inner join tb_customer_credit_amoritization xaa on 

					xd.customerCreditDocumentID = xaa.customerCreditDocumentID 

			where 

				xaa.isActive = 1 and 

				xd.customerCreditDocumentID = d.customerCreditDocumentID 

		) as deudaTotal,

		(

				select 

					sum(s_td.amount) as montoPagado

				from 

					tb_transaction_master_detail s_td 

					inner join tb_transaction_master s_t on 

						s_td.transactionMasterID = s_t.transactionMasterID 

					inner join tb_workflow_stage s_ws on 

						s_ws.workflowStageID = s_t.statusID 

				where 

					s_td.isActive = 1 and 

					s_t.isActive = 1 and 

					s_t.transactionID = 23  and 

					s_ws.aplicable = 1 and 

					s_td.reference1 = d.documentNumber 

		) as montoPagado,



		ROUND(

			(

				IFNULL

				(

					(

						select 

							sum(s_td.amount) as montoPagado

						from 

							tb_transaction_master_detail s_td 

							inner join tb_transaction_master s_t on 

								s_td.transactionMasterID = s_t.transactionMasterID 

							inner join tb_workflow_stage s_ws on 

								s_ws.workflowStageID = s_t.statusID 

						where 

							s_td.isActive = 1 and 

							s_t.isActive = 1 and 

							s_t.transactionID = 23  and 

							s_ws.aplicable = 1 and 

							s_td.reference1 = d.documentNumber 

					),

					0

				)

				/

				(

							

					select 

						sum(xaa.`share`) as total 

					from 

						tb_customer_credit_document xd 

						inner join tb_customer_credit_amoritization xaa on 

							xd.customerCreditDocumentID = xaa.customerCreditDocumentID 

					where 

						xaa.isActive = 1 and 

						xd.customerCreditDocumentID = d.customerCreditDocumentID 

						

				)

			),

			2

		) * 100 AS avance ,  

		

		ROUND(

			(

				d.amount - 

				IFNULL

				(

					(

						select 

							sum(s_td.amount) as montoPagado

						from 

							tb_transaction_master_detail s_td 

							inner join tb_transaction_master s_t on 

								s_td.transactionMasterID = s_t.transactionMasterID 

							inner join tb_workflow_stage s_ws on 

								s_ws.workflowStageID = s_t.statusID 

						where 

							s_td.isActive = 1 and 

							s_t.isActive = 1 and 

							s_t.transactionID = 23  and 

							s_ws.aplicable = 1 and 

							s_td.reference1 = d.documentNumber 

					),

					0

				)

			),

			2

		) AS saldo ,  

		(

			select 

				if(ro.orderNo is null,0,ro.orderNo)  				

			from 

				tb_relationship ro 

			where 

				ro.isActive = 1 and 

				ro.employeeID = usu.employeeID and 

			  ro.customerID = cu.entityID 

			limit 1 

				

		) as Orden , 

		d.currencyID ,

		

		IF(

			(

				select 

					ccd.customerCreditDocumentID

				from 

					tb_customer_credit_document ccd 

				where 

					ccd.entityID = cu.entityID and 

					ccd.isActive = 1 and 

					ccd.statusID in  (77  )

				limit 1 

			) IS NULL , 

			'CANCELADO',

			'ACTIVO'

		) as statusCustomer,

		

		ws.`name` as statusName,

		pe.`name` as periodPay,		 

		i.* 

	from 

		tbl_document_pivot_temp i 

		inner join tb_customer_credit_document d on 

			d.customerCreditDocumentID = i.customerCreditDocumentID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = d.statusID 

		inner join tb_catalog_item pe on 

			pe.catalogItemID = d.periodPay 

		inner join tb_customer cu on 

			d.entityID = cu.entityID 

		inner join tb_naturales nat on 

			nat.entityID = cu.entityID 

		inner join tb_legal leg on 

			leg.entityID = cu.entityID 

		inner join tb_catalog_item sexo on 

			sexo.catalogItemID = cu.sexoID 

		inner join tb_catalog_item civil on 

			civil.catalogItemID = nat.statusID   

		inner join (

			select 

				distinct 

				r.customerID as entityIDCustomer,

				r.employeeID  

			from 

				tb_relationship  r 

			where 

				r.isActive = 1 

		) r on 

			r.entityIDCustomer = cu.entityID 

		inner join tb_user usu on 

			usu.employeeID = r.employeeID 

	WHERE 

		usu.isActive = 1 and 

		cu.isActive = 1 and 

		d.isActive = 1 and 

		usu.isActive = 1 and 

		usu.userID not in ( 2  , 666  )

	order by 

		22 

	;

	



	

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_collection_get_report_document_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_collection_get_report_document_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prDocumentNumber` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	declare varMinFixex DATETIME;

	declare varMin DATETIME;

	declare varMax DATETIME;

	declare varAmount DECIMAL(19,8) default 0;

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_cliente_temp;

	CREATE TEMPORARY TABLE tbl_cliente_temp AS

	select 

		d.customerCreditDocumentID,

		cu.entityID,

	  a.creditAmortizationID,

		cu.customerNumber,

		concat(nat.firstName,' ',nat.lastName ) as nameCustomer,

		d.documentNumber ,

		a.dateApply,  

		(select sum(amo.`share`) from tb_customer_credit_amoritization amo where amo.customerCreditDocumentID = d.customerCreditDocumentID ) as capitalMoreInteres,

		a.`share` shareProgramin,

		0 as shareReal,

		0 as balanceStart,

		0 as balanceEnd 

	from 

		tb_customer_credit_document d 

		inner join tb_naturales nat on 

			nat.entityID = d.entityID 

		inner join tb_customer cu on 

			cu.entityID = nat.entityID 

		inner join tb_customer_credit_amoritization a on 

			a.customerCreditDocumentID = d.customerCreditDocumentID 

	where 

		d.documentNumber = prDocumentNumber  and 

		d.isActive = 1 and 

		a.isActive = 1 

	order by 

		a.dateApply desc ; 

		

	DROP TEMPORARY TABLE IF EXISTS tbl_cliente_temp2;

	CREATE TEMPORARY TABLE tbl_cliente_temp2 AS

	SELECT * FROM tbl_cliente_temp;

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_abonos_temp;

	CREATE TEMPORARY TABLE tbl_abonos_temp AS

	select 

		c.transactionMasterID,

		DATE(c.transactionOn) as transactionOn,

		c.amount  

	from 

		tb_transaction_master c 

		inner join tb_transaction_master_detail td on 

			c.transactionMasterID = td.transactionMasterID 

	WHERE 

		c.transactionID = 23  and 

		c.isActive = 1 and 

		td.isActive = 1 and 

		td.reference1 = prDocumentNumber;

		

		

	

	DROP TEMPORARY TABLE IF EXISTS tbl_date_temp ;

	CREATE TEMPORARY TABLE tbl_date_temp AS

	select 

		distinct 

		u.dateOn 

	from 

		(

			select 

				x.transactionOn as dateOn 

			from 

				tbl_abonos_temp x 

			union all 

			select 

				k.dateApply as dateOn 

			from 

				tbl_cliente_temp k 

			where 

				k.dateApply 

		) u ;

		

		

		

		set varMinFixex 	= (select min(dateOn) from tbl_date_temp  );

		set varMin 				= (select min(dateOn) from tbl_date_temp  );

		set varMax 				= (select max(dateOn) from tbl_date_temp  );

		WHILE varMin <= varMax DO 

				SET varAmount = (SELECT sum(k.amount) FROM tbl_abonos_temp k where k.transactionOn = varMin);

				SET varAmount = IFNULL(varAmount,0);

				

				IF NOT EXISTS ( select * from tbl_cliente_temp k where k.dateApply = varMin ) THEN 

								insert into tbl_cliente_temp(

											customerCreditDocumentID,

											entityID,

											creditAmortizationID,

											customerNumber,

											nameCustomer,

											documentNumber ,

											dateApply,  

											capitalMoreInteres,

											shareProgramin,

											shareReal,

											balanceStart,

											balanceEnd 

								)

								select

										customerCreditDocumentID,

										entityID,

										0 as creditAmortizationID,

										customerNumber,

										nameCustomer,

										documentNumber ,

										varMin,  

										capitalMoreInteres,

										shareProgramin,

										shareReal,

										balanceStart,

										balanceEnd 

								from 

									  tbl_cliente_temp2 t 

								where 

									  t.dateApply = varMinFixex;

				END IF;

				

				UPDATE tbl_cliente_temp set shareReal = varAmount where dateApply = varMin;

				

				

        

        SET varMin = (select min(dateOn) from tbl_date_temp k where k.dateOn > varMin  );

    END WHILE;

		

	  

		SELECT 

			c.customerNumber,

			c.phoneNumber, 

			cur.simbol ,

			t.customerCreditDocumentID,			

			t.entityID,

			t.creditAmortizationID,

			t.customerNumber,

			t.nameCustomer,

			t.documentNumber ,

			t.dateApply,  

			t.capitalMoreInteres,

			t.shareProgramin,

			t.shareReal,

			t.balanceStart,

			t.balanceEnd 

		FROM 

			tbl_cliente_temp t 

			inner join  tb_customer c on 

				t.entityID = c.entityID 

			inner join tb_customer_credit_document dd on 

				dd.customerCreditDocumentID = t.customerCreditDocumentID

			inner join tb_currency cur on 

				cur.currencyID = dd.currencyID 

		ORDER BY 

			t.dateApply desc ; 

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_collection_get_report_summary_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_collection_get_report_summary_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN prDate DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	DECLARE varMin DATETIME;

	DECLARE varMax DATETIME;



	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_user_data_temp;

	CREATE TEMPORARY TABLE tbl_user_data_temp AS 

	SELECT 

		u.nickname ,

		0 as countCustomer,

		0 as countCredit,

		0 as countCustomerAcumulados,

		0 as countCustomerCancel,

		0 as countCustomerNew,

		0 as countCustomerRecuperation,

		0 as amountCartera,

		0 as amountCapital 

	FROM 

		tb_user u 

	WHERE 

		u.isActive = 1 and 

		u.userID != 2 and 

		u.employeeID > 0 and 

		u.userID not in ( 2  , 666  );

		

	

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count 

			from 

				tb_customer cu 

				inner join tb_entity cue on 

					cue.entityID = cu.entityID 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID in (

							select 

									sub_d.entityID 

							from 

								tb_customer_credit_document sub_d 

							where 

								sub_d.statusID = 77  and 

								sub_d.isActive = 1 

			)

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomer = o.count ; 



	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 				

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

				inner join tb_customer_credit_document d on 

					d.entityID = cu.entityID 

			where 

				cu.isActive = 1 and 

				d.isActive = 1 and 

				d.statusID in (77  ) 

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCredit = o.count ; 

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				sum(amo.`share`) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 			

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

				inner join tb_customer_credit_document d on 

					d.entityID = cu.entityID 

				inner join tb_customer_credit_amoritization amo on 

					amo.customerCreditDocumentID = d.customerCreditDocumentID 

			where 

				cu.isActive = 1 and 

				d.isActive = 1 and 

				d.statusID in (77  )  

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.amountCartera = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				sum(amo.capital) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 			

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

				inner join tb_customer_credit_document d on 

					d.entityID = cu.entityID 

				inner join tb_customer_credit_amoritization amo on 

					amo.customerCreditDocumentID = d.customerCreditDocumentID 

			where 

				cu.isActive = 1 and 

				d.isActive = 1 and 

				d.statusID in (77  )  

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.amountCapital = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID not in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				)   and 

				cu.entityID in (

					select 

						tm.entityID 

					from 

						tb_transaction_master tm  

					where 

						tm.isActive = 1 and 

						tm.transactionID = 23  and 

						tm.transactionOn  between 

									DATE_SUB(DATE_SUB(NOW(), INTERVAL 6 HOUR), INTERVAL 1 DAY) AND   

									DATE_SUB(NOW(), INTERVAL 6 HOUR) 

				) 

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerCancel = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID not in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				)   

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerAcumulados = o.count ; 

		

	

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

					inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID   

				inner join tb_user usu on 

					usu.employeeID = emp.entityID 

			where 

				cu.isActive = 1 and 

				cu.entityID in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				) and 

				cu.createdOn between 

						DATE_SUB(DATE_SUB(NOW(), INTERVAL 6 HOUR), INTERVAL 1 DAY) AND   

						DATE_SUB(NOW(), INTERVAL 6 HOUR) 

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerNew = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID   

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				)

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerRecuperation = o.count ; 

		

		

	

	select 

		k.nickname ,

		k.countCustomer,

		k.countCredit,

		k.countCustomerAcumulados, 

		k.countCustomerCancel,

		k.countCustomerNew,

		k.countCustomerRecuperation,

		k.amountCartera,

		k.amountCapital 

	from 

		tbl_user_data_temp k ;

	

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_billing` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_billing`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procediminento que se utiliza para calcular los conceptos de la transaccion de otras salidas de inventario'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameIVA varchar(50) DEFAULT 'IVA';

	declare vrConceptNameIMPORTE varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameDESCUENTO varchar(50) DEFAULT 'DESCUENTO';



	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdIVA int default 0;

	declare vrConceptIdIMPORTE int default 0;

	declare vrConceptIdDESCUENTO int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIVA  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIVA and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIMPORTE  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIMPORTE and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdDESCUENTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameDESCUENTO and isActive = 1 limit 1;

			

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdIVA,

		(tm.quantity * tm.tax1) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select  

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdIMPORTE,

		(tm.quantity * tm.unitaryAmount) as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master_detail tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdDESCUENTO,

		0, 

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master_detail tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_calendarpay` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_calendarpay`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

			declare vrConceptNameSalario varchar(50) DEFAULT 'SALARIO';

	declare vrConceptNameAdelanto varchar(50) DEFAULT 'ADELANTO';

	declare vrConceptNameComision varchar(50) DEFAULT 'COMISION';

	declare vrConceptIdSalario int default 0;

	declare vrConceptIdAdelanto int default 0;

	declare vrConceptIdComision int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdSalario  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameSalario and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdAdelanto  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameAdelanto and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdComision  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameComision and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdSalario,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,((tm.amount + tm.cost)-unitaryAmount),((tm.amount+tm.cost) - tm.unitaryAmount) / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID ,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

	

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_cancelinvoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_cancelinvoice`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Calcular los conceptos de la cancelacion de factura'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

				IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,tm.amount,tm.amount / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID ,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_input_unpost` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_input_unpost`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Entrada sin postear'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameIVA varchar(50) DEFAULT 'IVA';

	declare vrConceptNameIMPORTE varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameDESCUENTO varchar(50) DEFAULT 'DESCUENTO';



	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdIVA int default 0;

	declare vrConceptIdIMPORTE int default 0;

	declare vrConceptIdDESCUENTO int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIVA  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIVA and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIMPORTE  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIMPORTE and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdDESCUENTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameDESCUENTO and isActive = 1 limit 1;

			

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdIVA,

		tm.tax1 as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdDESCUENTO,

		tm.discount as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0, 

		0,

		vrConceptIdIMPORTE,

		(tm.subAmount + tm.tax1 - tm.discount ) as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

			

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_other_input` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_other_input`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para calcular los conceptos de Otras entradas a Inventario'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptIdICOSTO int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_other_output` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_other_output`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procediminento que se utiliza para calcular los conceptos de la transaccion de otras salidas de inventario'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptIdICOSTO int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_provider` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_provider`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Calculo de concepto de Provisiones'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0; 

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

						IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,tm.amount,tm.amount / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,	0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm 

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;  

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_returns_provider` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_returns_provider`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para calcular los conceptos de Devolucion de Compra'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameIVA varchar(50) DEFAULT 'IVA';

	declare vrConceptNameIMPORTE varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameDESCUENTO varchar(50) DEFAULT 'DESCUENTO';



	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdIVA int default 0;

	declare vrConceptIdIMPORTE int default 0;

	declare vrConceptIdDESCUENTO int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIVA  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIVA and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIMPORTE  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIMPORTE and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdDESCUENTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameDESCUENTO and isActive = 1 limit 1;			



	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdIVA,

		tm.tax1 as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdDESCUENTO,

		tm.discount as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0, 

		0,

		vrConceptIdIMPORTE,

		(tm.subAmount + tm.tax1 - tm.discount ) as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		

	update tb_transaction_master_concept set value = 0 WHERE value is null and  transactionMasterID = prTransactionMasterID and transactionID = prTransactionID ;

		

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_salaryadvance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_salaryadvance`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Contabilizar Adelantos de Salario'
BEGIN

		declare vrConceptNameAdelanto varchar(50) DEFAULT 'ADELANTOS';

	declare vrConceptIdSalario int default 0;

	declare vrConceptIdAdelanto int default 0;

	declare vrConceptIdComision int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdAdelanto  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameAdelanto and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdAdelanto,

		IF (IFNULL(t.exchangeRate,vrExchangeRate) > 1,round(tm.amount  / t.exchangeRate,2),tm.amount) as amountConcept ,

		t.currencyID as currencyID ,

		t.exchangeRate as exchangeRate

	from

		tb_transaction_master t

		inner join tb_transaction_master_detail tm  on 

			t.transactionMasterID = tm.transactionMasterID and 

			t.transactionID = tm.transactionID 

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

	

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_share` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_share`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener los conceptos de los Abonos de Credito'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

						IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,cc.capital,cc.capital / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,	0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdINTERES,

						IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,cc.interest,cc.interest / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_concept_helper_sharecapital` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_concept_helper_sharecapital`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Crear los conceptos del abono al capital'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

				IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,tm.amount,tm.amount / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID ,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_core_get_exchange_rate` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_core_get_exchange_rate`(IN `prCompanyID` INT, IN `prDate` DATE, IN `prCurrencySource` VARBINARY(250), IN `prCurrencyTarget` VARBINARY(250), OUT `prExchangeRate` DECIMAL(18,8))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener la tasa de cambio del dia '
LBL_PROCEDURE:

BEGIN

	DECLARE currencyIDDefault INT DEFAULT 0;

	DECLARE currencyIDSource  INT DEFAULT 0;

	DECLARE currencyIDTarget  INT DEFAULT 0;

	DECLARE ratio_1 DECIMAL(18,8) DEFAULT 1;

	DECLARE ratio_2 DECIMAL(18,8) DEFAULT 1;



	

	SET currencyIDDefault = (SELECT c.currencyID	FROM tb_parameter p INNER JOIN tb_company_parameter cp ON p.parameterID = cp.parameterID INNER JOIN tb_currency c ON  cp.value = c.name WHERE p.name = 'ACCOUNTING_CURRENCY_NAME_FUNCTION' and c.isActive = 1 and cp.companyID = prCompanyID LIMIT 1 );

	SET currencyIDSource  = (SELECT currencyID FROM tb_currency c WHERE c.name = prCurrencySource and isActive = 1 LIMIT 1);

	SET currencyIDTarget  = (SELECT currencyID FROM tb_currency c WHERE c.name = prCurrencyTarget and isActive = 1 LIMIT 1);

	

	

	IF currencyIDSource != currencyIDDefault THEN

		SELECT ratio INTO  ratio_1   FROM tb_exchange_rate e where e.companyID = prCompanyID and e.currencyID = currencyIDDefault and e.targetCurrencyID = currencyIDSource AND e.`date` =  prDate;

	END IF;

	

	IF currencyIDTarget != currencyIDDefault THEN 



		SELECT ratio INTO  ratio_2   FROM tb_exchange_rate e where e.companyID = prCompanyID and e.currencyID = currencyIDDefault and e.targetCurrencyID = currencyIDTarget AND e.`date` =  prDate;

	END IF;

	

	IF currencyIDSource = currencyIDTarget THEN 

		SET prExchangeRate =  1;

	ELSE 

		SET prExchangeRate = (1 * ratio_1) / ratio_2; 

	END IF; 

	  

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_core_get_indicators` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_core_get_indicators`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTokenID` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Obtiene la lista de indicadores'
BEGIN

	DECLARE minIndicatorID INT DEFAULT 0;

	DECLARE maxIndicatorID INT DEFAULT 0;

	DECLARE sqlScript VARCHAR(5000) DEFAULT '';	

	SET @utilityResult 	= 0;

	SET @query 				= '';

	SET @prPeriodID 		= prPeriodID;

	SET @prCycleID 		= prCycleID;

		

		delete from tb_indicator_tmp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

	

		SET minIndicatorID = (SELECT MIN(indicadorID) FROM tb_indicator where companyID = prCompanyID);

	SET maxIndicatorID = (SELECT MAX(indicadorID) FROM tb_indicator where companyID = prCompanyID);

	WHILE minIndicatorID <= maxIndicatorID and minIndicatorID is not null DO 	

	

				SET @utilityResult 	= 0;

		IF EXISTS(select i.indicadorID from tb_indicator i where i.indicadorID = minIndicatorID and i.isGroup <> 1 LIMIT 1 ) THEN 		

			SET sqlScript 			= (SELECT i.script FROM tb_indicator i where i.indicadorID = minIndicatorID);

			SET @query    			= CONCAT(sqlScript);

			PREPARE stmt FROM @query;  

			EXECUTE stmt;

		   DEALLOCATE PREPARE stmt;   

		END IF;

		

				INSERT INTO tb_indicator_tmp (companyID,branchID,loginID,tokenID,indicadorID,value)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,minIndicatorID,  IFNULL(@utilityResult,0) );

		

		SET minIndicatorID					= (SELECT MIN(indicadorID) FROM tb_indicator  WHERE indicadorID > minIndicatorID and companyID = prCompanyID);

	END WHILE; 	

	

		select 

		i.name,

		i.`order`,

		i.code,

		i.label,

		i.description,

		i.posfix,

		i.prefix,

		i.isGroup,

		it.value

	from 

		tb_indicator i 

		inner join tb_indicator_tmp it on 

			i.indicadorID = it.indicadorID 

	where

		it.companyID = prCompanyID and 

		it.branchID = prBranchID and 

		it.loginID = prLoginID and 

		i.isActive = 1 

	order by 

		i.code,

		i.`order` ;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_core_get_next_number` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_core_get_next_number`(IN `prCompanyID` INT, IN `prComponent` VARBINARY(250), IN `prBranchID` INT, IN `prComponentItemID` INT, OUT `prNumber` VARCHAR(250))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Obtener el siguiente numero de un Componente'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 0;

	DECLARE currentValue_ INT DEFAULT 0;

	DECLARE seed_ INT DEFAULT 0;

	DECLARE length_ INT DEFAULT 0;

	DECLARE counterID_ INT DEFAULT 0;

	DECLARE serie_ VARCHAR(10) DEFAULT '';

	DECLARE number_ VARCHAR(250) DEFAULT '';

	

		SET componentID_ 	= (SELECT c.componentID FROM tb_component c WHERE c.name = prComponent LIMIT 1);

	

		SELECT counterID,currentValue,seed,serie,`length` INTO counterID_,currentValue_,seed_,serie_,length_ FROM tb_counter WHERE companyID = prCompanyID AND componentID = componentID_ AND branchID = prBranchID  and componentItemID = prComponentItemID LIMIT 1;	

	

		UPDATE tb_counter set currentValue = currentValue + 1 WHERE counterID = counterID_;

	

		SET number_ 		= CAST(currentValue_ AS CHAR(250));

	SET number_			= LPAD(number_,length_ ,'0');

	SET prNumber 		= CONCAT(serie_,number_);

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_core_get_parameter_value` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_core_get_parameter_value`(IN `prCompanyID` INT, IN `prParameter` VARBINARY(250), OUT `prValue` VARCHAR(250))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener el valor de un parametro'
LBL_PROCEDURE:

BEGIN

	DECLARE value_ VARCHAR(250) DEFAULT '';	

	

	SET value_ 	= (SELECT cp.value FROM tb_parameter p  inner join tb_company_parameter cp on  p.parameterID = cp.parameterID WHERE cp.companyID = prCompanyID and  p.name = prParameter LIMIT 1 );

	SET prValue = value_;

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_core_get_workflow_stage_init` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_core_get_workflow_stage_init`(IN `prCompanyID` INT, IN `prTable` VARBINARY(250), IN `prField` VARBINARY(250), OUT `prWorkflowStageInit` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener el estado inicial de un tb_workflow Asociado a una columna de una Tabla'
LBL_PROCEDURE:

BEGIN

	

	DECLARE elementTypeIDTable_ INT DEFAULT 2; 

	DECLARE workflowID_ INT DEFAULT 0;

	DECLARE flavorID_ INT DEFAULT 0;

	DECLARE componentIDWorkflow_ INT DEFAULT 2;

	DECLARE workflowStageInit_ INT DEFAULT 0;

	

		SET workflowID_ 			= (SELECT se.workflowID FROM tb_element e inner join tb_subelement se on e.elementID = se.elementID WHERE e.name = prTable and se.name = prField AND e.elementTypeID = elementTypeIDTable_ AND workflowID IS NOT NULL LIMIT 1);

	IF (workflowID_ IS NULL) THEN		

		LEAVE LBL_PROCEDURE;

	END IF;

	

		SET flavorID_ 				= (SELECT flavorID FROM tb_company_component_flavor c where c.companyID = prCompanyID and componentID = componentIDWorkflow_ AND componentItemID = workflowID_ LIMIT 1);

	IF (flavorID_ IS NULL) THEN		

		LEAVE LBL_PROCEDURE;

	END IF;

	

		SET workflowStageInit_ 		= (SELECT ws.workflowStageID  FROM tb_workflow_stage ws WHERE ws.workflowID = workflowID_ and ws.flavorID = flavorID_ AND isInit = 1 LIMIT 1 );

	IF (workflowStageInit_ IS NULL) THEN		

		LEAVE LBL_PROCEDURE;

	END IF;

	

	SET prWorkflowStageInit = workflowStageInit_;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_certificate_of_grades` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_certificate_of_grades`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,  IN `prGrado` int, IN `prYear` INT , IN prCustomerID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Obtener certificado de nota '
BEGIN

	SET SESSION group_concat_max_len = 15000;

	

	

	SET @sqlTField = NULL;

	SELECT

	GROUP_CONCAT(DISTINCT CONCAT(

		' "',    ci.`name`  ,'" ' )

	)

	INTO @sqlTField	

	FROM 

		tb_catalog c 

		INNER JOIN tb_catalog_item ci  ON 

			c.catalogID = ci.catalogID 

	WHERE  

		c.isActive = 1 AND 

		c.catalogID = 102  

	ORDER BY 

	  ci.sequence ; 

		

		

	

	SET @sqlT = NULL;

	SELECT

	GROUP_CONCAT(DISTINCT CONCAT(

		'

				SUM(

					CASE WHEN 

						concat(uz.Mes ) = "', ci.`name`, '" THEN 

							uz.ValorCuantitativo 

					ELSE 

							0 

					END

				) 

				AS "',    ci.`name`  ,'" ' )

	)

	INTO @sqlT	

	FROM 

		tb_catalog c 

		INNER JOIN tb_catalog_item ci  ON 

			c.catalogID = ci.catalogID 

	WHERE  

		c.isActive = 1 AND 

		c.catalogID = 102  

	ORDER BY 

	  ci.sequence ; 



	

		

	SET @sqlT = 

	CONCAT(

		'

		SELECT 

			r1.Materia,

			

			

			`01-Enero`,

			`02-Febrero`,

			`03-Marzo`,

		  r1.Trimestral1,			

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral1 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre1Cualitativo, 

			

			

			

			`04-Abril`,

			`05-Mayo`,

			`06-Junio`,

			r1.Trimestral2,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral2 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre2Cualitativo, 

			

			

			

			`07-Julio`,

			`08-Agosto`,

			`09-Septiembre`,

			r1.Trimestral3,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral3 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre3Cualitativo, 

			

			

			

			`10-Octubre`,

			`11-Noviembre`,

			`12-Diciembre`,

			r1.Trimestral4,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral4 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre4Cualitativo, 

			

			

			

			r1.Anualidad,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Anualidad between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as AnualidadCualitativo

			

			

		FROM 

			(

					SELECT 

						Materia, 

						

						`01-Enero`,

						`02-Febrero`,

						`03-Marzo`,

						ROUND((IFNULL(`01-Enero`,0) + IFNULL(`02-Febrero`,0) + IFNULL(`03-Marzo`,0) ) / 3,2) Trimestral1 ,

						

						`04-Abril`,

						`05-Mayo`,

						`06-Junio`,

						ROUND((IFNULL(`04-Abril`,0) + IFNULL(`05-Mayo`,0) + IFNULL(`06-Junio`,0) ) / 3,2) Trimestral2 ,

						

						`07-Julio`,

						`08-Agosto`,

						`09-Septiembre`,

						ROUND((IFNULL(`07-Julio`,0) + IFNULL(`08-Agosto`,0) + IFNULL(`09-Septiembre`,0) ) / 3,2) Trimestral3 ,

						

						`10-Octubre`,

						`11-Noviembre`,

						`12-Diciembre`,

						ROUND((IFNULL(`10-Octubre`,0) + IFNULL(`11-Noviembre`,0) + IFNULL(`12-Diciembre`,0) ) / 3,2) Trimestral4 ,

						

						

						(

							ROUND(

							(

								(IFNULL(`01-Enero`,0)) +

								(IFNULL(`02-Febrero`,0)) +

								(IFNULL(`03-Marzo`,0)) +

								(IFNULL(`04-Abril`,0)) +

								(IFNULL(`05-Mayo`,0)) +

								(IFNULL(`06-Junio`,0)) +

								(IFNULL(`07-Julio`,0)) +

								(IFNULL(`08-Agosto`,0)) +

								(IFNULL(`09-Septiembre`,0)) +

								(IFNULL(`10-Octubre`,0)) +

								(IFNULL(`11-Noviembre`,0)) +

								(IFNULL(`12-Diciembre`,0))

							) 

							/ 12,2)

						) as Anualidad 

						

						

					FROM 

						(

								SELECT 

										Materia, 

										', @sqlT, 

								'from 

										(		

												SELECT 

													mat.`name` as Materia,

													mes.name as Mes ,

													c.amount as ValorCuantitativo

												FROM 

													tb_transaction_master c 

													inner join tb_public_catalog_detail mat on 

														mat.publicCatalogDetailID = c.areaID 

													inner join tb_catalog_item mes on 

														mes.catalogID = 102 /*lista de meses*/ and 

														MONTH(c.transactionOn) = mes.sequence  								 		

												WHERE 

													c.isActive = 1 and 

													YEAR(c.transactionOn) = ',prYear,' /**/ and 

													c.classID = ',prGrado,' /*grado*/ and 

													c.entityID = ',prCustomerID,' /*customerID*/ 

										)  uz 

									group by  				

										uz.Materia

						) proc 

				) r1 

	');





	

	

	

	PREPARE stmt FROM @sqlT;

	EXECUTE stmt;

	DEALLOCATE PREPARE stmt;

	

	

	select 	

		c.customerNumber,

		concat(nat.firstName,' ',nat.lastName) as Nombre,

		prYear as Ano ,

		(SELECT ci.display from tb_catalog_item ci where ci.catalogItemID =  prGrado) as Grado  

	from 

		tb_customer c 

		inner join tb_naturales nat on 

			nat.entityID = c.entityID 

	where 

		c.entityID = prCustomerID;

		

	

	select 

		c.`name` as Nombre,

		c.display ,

		c.description, 		

		c.sequence,

		c.reference1,

		c.reference2 ,

		c.reference3

	from 

		tb_catalog_item c 

	where 

		c.catalogID = 98 

	order by 

		c.sequence;  

 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_collection_manager` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_collection_manager`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prEmployeeCode` VARCHAR(50),prStartOn DATETIME , prEndOn DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	declare varDate date;

	declare varZonaHoraria int default 0; 

	 

  set varZonaHoraria = (

			select 				

				uu.value  

			from 

				tb_parameter u 

				inner join tb_company_parameter uu on 

					uu.parameterID = u.parameterID

			where 

				u.`name` = 'CORE_ZONA_HORARIA' and 

				uu.companyID = 2 

	);

	

	

	

	set prEndOn =  date_add(prEndOn, interval 23 hour);	

	set prEndOn =  date_add(prEndOn, interval 59 minute);	

	set prEndOn =  date_add(prEndOn, interval 59 second);	

	

	

	select 

	

		

		case 

			when prEmployeeCode = 'EMP00000000' then 

				'EMP00000000' 

			else 

				tec.employerNumber 

		end as FiltroCode,  

		case 

			when prEmployeeCode = 'EMP00000000' then 

				'TODOS' 

			else 

				tec.employerName   

		end as FiltroName,  

		tec.employerNumber as NoGestor,

		tec.employerName as Gestor,

		

		

		

		c.customerNumber as NoCliente,

		concat(cn.firstName,' ',cn.lastName) as Cliente,

		c.phoneNumber as Telefono,

				

		CONCAT(

			c.address,

			IFNULL(tv.note,'')	

		) as Direccion, 

		

		ccc.documentNumber as Factura, 

		ws.name as Estado,

		cca.dateApply as Fecha,

		round(cca.`share`,2) as CuotaCompleta,

		round(cca.`remaining`,2) as Cuota,

		'__________________' as Abono,

		cur.name as Moneda,

		if(varDate <= date(cca.dateApply),'BLUE'  ,'RED'  )  as Atraso,

		if(varDate <= date(cca.dateApply),0,round(cca.remaining,2) )  as MontoTotalAtrazo,

		if(varDate <= date(cca.dateApply), round(cca.remaining,2),  0 )  as MontoTotalCobradoCorriente,

		if(varDate = cca.dateApply,1,0 )  as MontoTotalMetaDia

		

		

	from

		tb_customer c 

		inner join tb_naturales cn on 

			c.entityID = cn.entityID 

		inner join tb_customer_credit_document ccc on 

			c.entityID = ccc.entityID 

		inner join tb_customer_credit_amoritization cca on 

			ccc.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID 

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

		left join tb_entity_phone ephone on 

			cn.entityID = ephone.entityID and ephone.isPrimary = 1 

			

		left join (			

							SELECT 

									cxx.entityID as clientID, 

									IFNULL(GROUP_CONCAT(nxx.firstName SEPARATOR ', '),'') AS employerName ,

									IFNULL(GROUP_CONCAT(exx.employeNumber SEPARATOR ', '),'') AS employerNumber 

							FROM 

								tb_customer cxx

								LEFT JOIN tb_relationship rxx ON 

									cxx.entityID = rxx.customerID and 

									rxx.isActive = 1 

								LEFT JOIN tb_employee exx ON 

									rxx.employeeID = exx.entityID

								LEFT JOIN tb_naturales nxx on 

									nxx.entityID = exx.entityID

								GROUP BY 

										cxx.entityID

								ORDER BY 

									exx.entityID asc 

		) tec on 

			tec.clientID = c.entityID  

		left join (

			select 

							tmx.entityID,

							max(tmx.transactionMasterID) IDMAx

						from 

							tb_transaction_master tmx 

						where 

							tmx.isActive = 1 and 

							tmx.transactionID = 35   

						group by 

							tmx.entityID 

		) tvm on 

			tvm.entityID = c.entityID 

		left join  tb_transaction_master tv on 

			tv.entityID = c.entityID  and 

			tv.transactionMasterID = tvm.IDMAx  

									

	where

		cca.remaining <> 0  		

		and 

		(

			cca.dateApply <= prEndOn 

			

		) 

		and  ws.vinculable = 1 

		and 

		(

			(

				tec.employerNumber = prEmployeeCode and (prEmployeeCode != 'EMP00000000' )

			)

			or 

			(

				'EMP00000000' = prEmployeeCode 

			)

		) 

		and 

		(	

					

			   	(

						(ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0)

					)

					

					or 

					(

						fn_get_access_ready(prCompanyID  , prUserID  , 174  , 0  , 0  ) = 1 

					)

					or

					

					(

						fn_get_provider_id (prCompanyID,prUserID) = 0 

					)

		)

	order by 

		tec.employerNumber,cn.firstName ,cca.dateApply;  

	 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_credit`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'cartera de credito diferenciada por moneda'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE minClientID int DEFAULT 0;

	DECLARE maxClientID int DEFAULT 0;

	DECLARE maxDiasMora_ int  DEFAULT 0;

	DECLARE montoAtrazado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE cantidadFactura_ INT DEFAULT 0;

	DECLARE customerNumber_ varchar(50); 

	DECLARE lastVisit_ varchar(550); 

	DECLARE documentNumber_ varchar(50); 

	DECLARE capitalPrestado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesDebengado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE capitalPagado_ DECIMAL(19,9) DEFAULT 0; 

	DECLARE interesPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE proximoPago_ DATE;

	DECLARE maximoPago_ DATE;	

	DECLARE ultimoPagoFecha_ DATE;

	DECLARE montoProximoPago_ DECIMAL(19,9) DEFAULT 0;

	DECLARE capitalAtrazado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesAtrazado_ DECIMAL(19,9) DEFAULT 0;

	

	CREATE TEMPORARY TABLE tmp_customer_credit 					( 

			ID INT AUTO_INCREMENT PRIMARY KEY,

			customerNumber varchar(50),legalName varchar(550),

			commercialName varchar(550),firstName varchar(550),lastName varchar(550),

			limitCredit decimal(19,9),balanceCredit decimal(19,9),

			moneda varchar(50),

			tipoCambio decimal(19,4),customerCreditLineID int,documentNumber varchar(50),

			statusDocument varchar(50),dateApply DATETIME,balanceStart DECIMAL(19,9),

			interest DECIMAL(19,9),capital DECIMAL(19,9),share DECIMAL(19,9),balanceEnd DECIMAL(19,9),

			remaining DECIMAL (19,9), dayDelay INT,statusShare varchar(50),

			direccion varchar(550),

			identification varchar(50) ,

			phone varchar(150),			

			lastShareNumber varchar(50),																

			dateLastShareNumber date,																

			amountLastShareNumber decimal(19,4),

			employerName varchar(150) 

	); 



	CREATE TEMPORARY TABLE tmp_customer_credit_summary 			(

				ID INT AUTO_INCREMENT PRIMARY KEY,customerNumber varchar(50),

				legalName varchar(550),commercialName varchar(550),firstName varchar(550),

				lastName varchar(550),limitCredit decimal(19,9),balanceCredit decimal(19,9),				

				factura varchar(50),				

				tipoCambio decimal(19,4),				

				moneda varchar(50),				

				capitalPrestado decimal(19,9) default 0,				

				maxDiasMora int default 0,				

				montoAtrazado decimal(19,9) default 0,				

				capitalAtrazado decimal(19,9) default 0,				

				interesAtrazado decimal(19,9) default 0,																				

				capitalPagado decimal(19,9) default 0,																				

				interesPagado decimal(19,9) default 0,

				proximoPago DATE ,

				montoProximoPago decimal(19,9) default 0,

				ultimoPagoFecha DATE,																

				direccion varchar(550), 

				identification varchar(50),

				phone varchar(150),				

				lastShareNumber varchar(50),				

				dateLastShareNumber date,				

				amountLastShareNumber decimal(19,4),

				lastVisit varchar(550),

				remainingDocument decimal(19,4),

				employerName varchar(150)

); 

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);



	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	



	insert into tmp_customer_credit (

		customerNumber ,legalName ,commercialName ,firstName ,lastName ,limitCredit ,

		balanceCredit,tipoCambio ,customerCreditLineID ,documentNumber ,statusDocument ,dateApply ,

		balanceStart ,interest ,capital ,share ,balanceEnd ,remaining, dayDelay ,

		statusShare,moneda ,direccion,identification,phone,

		lastShareNumber,dateLastShareNumber ,amountLastShareNumber	,

		employerName

	)

	select 

		c.customerNumber,

		l.legalName,

		l.comercialName,

		n.firstName,

		n.lastName,

		ccl.limitCredit  AS limitCredit,

		ccl.balance  AS balance,

		tmc.exchangeRate AS TipoCambio,

		ccl.customerCreditLineID,

		ccd.documentNumber,

		ws1.name,

		cca.dateApply,

		cca.balanceStart,

		cca.interest,

		cca.capital,

		cca.share,

		cca.balanceEnd,

		cca.remaining, 

		cca.dayDelay as dayDelay,

		ws2.name		,

		cur.simbol  , 

		n.address as direccion,

		c.identification as identification ,

		IFNULL(

			(select  lm.number from tb_entity_phone lm where lm.entityID = e.entityID and lm.isPrimary = 1 limit 1 ),

			'N/A'

		)  as phone,

		IFNULL(infoUltimaTransaccion.transactionNumber,'N/A') 	 as lastShareNumber,

		IFNULL(infoUltimaTransaccion.transactionOn,'N/A') 	 as dateLastShareNumber,

		IFNULL(infoUltimaTransaccion.amount,'0') 	 as amountLastShareNumber,

		nate.firstName as employerName 

	from 

		tb_entity e

		inner join tb_customer c on 

			e.entityID = c.entityID

		inner join tb_naturales n on 

			c.entityID = n.entityID  		

		inner join tb_legal l on 

			l.entityID = n.entityID

		inner join tb_customer_credit_line ccl on 

			e.entityID = ccl.entityID  

		inner join tb_customer_credit_document ccd on 

			ccd.entityID = e.entityID and 

			ccd.customerCreditLineID = ccl.customerCreditLineID 

		inner join tb_transaction_master tmc on  

			ccd.documentNumber = tmc.transactionNumber and 

			tmc.isActive = 1 and 

			ccd.isActive = 1 

		inner join tb_customer_credit_amoritization cca on 

			ccd.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			ccd.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on  

			cca.statusID = ws2.workflowStageID

		inner join tb_currency cur on 

			tmc.currencyID = cur.currencyID 

		inner join tb_naturales nate on 

			nate.entityID = tmc.entityIDSecondary 

		left join (		

				select 

					lxp.customerCreditDocumentID,

					max(cxl.transactionMasterID) maxTransactionID 

				from 

					tb_transaction_master cxl 

					inner join tb_transaction_master_detail lmxl on 

						lmxl.transactionMasterID = cxl.transactionMasterID 

					inner join tb_customer_credit_document lxp on 

						lxp.documentNumber = lmxl.reference1 and 

						lxp.customerCreditDocumentID = lmxl.componentItemID  

				where  

					cxl.transactionID = 23  

				group by 

					lxp.customerCreditDocumentID 

					

		) ultimaTransaccion on  

			ultimaTransaccion.customerCreditDocumentID = ccd.customerCreditDocumentID 

		left join (

				select 

					lxp.customerCreditDocumentID,

					cxl.transactionMasterID, 

					cxl.transactionNumber,

					cxl.transactionOn,

					cxl.amount 

				from 

					tb_transaction_master cxl 

					inner join tb_transaction_master_detail lmxl on 

						lmxl.transactionMasterID = cxl.transactionMasterID  

					inner join tb_customer_credit_document lxp on 

						lxp.documentNumber = lmxl.reference1 and 

						lxp.customerCreditDocumentID = lmxl.componentItemID  

		) as infoUltimaTransaccion on 

			ultimaTransaccion.customerCreditDocumentID = infoUltimaTransaccion.customerCreditDocumentID and 

			ultimaTransaccion.maxTransactionID = infoUltimaTransaccion.transactionMasterID 

	where

		e.companyID 	= prCompanyID and

		c.isActive 		= 1 and 

		ccl.isActive 	= 1  and 

		ccd.isActive 	= 1 and 

		tmc.isActive 	= 1 and 

		ws1.name in ('REGISTRADO' ) and 

		(	

			   	(

						(ccd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and 

						(fn_get_provider_id (prCompanyID,prUserID) != 0)

					)

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		c.customerNumber,ccd.dateOn, ccd.documentNumber,cca.creditAmortizationID;

		

		

	INSERT INTO tmp_customer_credit_summary (

		customerNumber ,legalName ,commercialName,firstName ,lastName ,factura,direccion,

		identification,phone,lastShareNumber,dateLastShareNumber ,amountLastShareNumber,employerName,limitCredit,balanceCredit, remainingDocument		 

	)

	select 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ,

		x.documentNumber,

		x.direccion as direccion,

		x.identification,

		x.phone,

		x.lastShareNumber,

		x.dateLastShareNumber , 

		x.amountLastShareNumber,		

		x.employerName,

		SUM(x.limitCredit) as limitCredit,

		SUM(x.balanceCredit) as balanceCredit,

		SUM(x.remaining) as remainingDocument

	from 

		tmp_customer_credit x

	group by 

		x.customerNumber ,

		x.legalName , 

		x.commercialName,

		x.firstName ,

		x.lastName,

		x.documentNumber ,

		x.direccion,

		x.identification,

		x.phone,

		x.lastShareNumber,

		x.dateLastShareNumber ,

		x.amountLastShareNumber,

		x.employerName; 

	

	SET minClientID = (SELECT MIN(ID) FROM tmp_customer_credit_summary);

	SET maxClientID = (SELECT MAX(ID) FROM tmp_customer_credit_summary);

	WHILE minClientID <= maxClientID and minClientID is not null DO 	

		SET documentNumber_				= (SELECT C.factura FROM tmp_customer_credit_summary C WHERE ID = minClientID LIMIT 1);

		SET customerNumber_				= (SELECT C.customerNumber FROM tmp_customer_credit_summary C WHERE ID = minClientID LIMIT 1);

		SET moneda_								= (SELECT C.moneda FROM tmp_customer_credit C WHERE documentNumber = documentNumber_ LIMIT 1);

		SET capitalPrestado_			= (SELECT balanceStart FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ ORDER BY dateApply LIMIT 1  );

		SET maxDiasMora_					= (

																		SELECT DATEDIFF(NOW(),dateApply) 

																		FROM tmp_customer_credit C 

																		WHERE 

																			documentNumber = documentNumber_  and 

																			dateApply <= curdate() AND 

																			remaining > 0  

																		ORDER BY dateApply LIMIT 1 

																);

		SET montoAtrazado_				= (

																	SELECT SUM(C.remaining)  

																	FROM tmp_customer_credit C 

																	WHERE 

																		documentNumber = documentNumber_ and 

																		remaining > 0 and  

																		dateApply <= curdate() 

																);

		SET capitalAtrazado_ 			= (SELECT SUM(capital) FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining > 0 AND dateApply <= curdate());

		SET interesAtrazado_ 			= (SELECT SUM(interest) FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining > 0 AND dateApply <= curdate());

		SET capitalPagado_				= (SELECT SUM(capital)  FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining <= 0);

		SET interesPagado_				= (SELECT SUM(interest) FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining <= 0);

		

		

		SET proximoPago_				= (SELECT MIN(C.dateApply) FROM tmp_customer_credit C WHERE documentNumber = documentNumber_  and remaining > 0  );		

		SET montoProximoPago_		= (SELECT SUM(C.remaining)  FROM tmp_customer_credit C WHERE C.documentNumber = documentNumber_  and C.dateApply = proximoPago_ );  

		SET maximoPago_ 				= (SELECT MAX(C.dateApply) FROM tmp_customer_credit C WHERE documentNumber = documentNumber_  and remaining > 0  );

		SET lastVisit_ 					= (

																SELECT IFNULL(note ,'Ninguna')

																FROM tb_transaction_master c 

																INNER JOIN tb_customer cus on 

																	cus.entityID = c.entityID 

																WHERE 

																	c.transactionID = 35  and 

																	c.isActive = 1 and 

																	cus.customerNumber = customerNumber_  

																ORDER BY 

																	c.transactionMasterID DESC 

																LIMIT 0,1 

															);

		 

		 

		UPDATE 	tmp_customer_credit_summary set 

			tipoCambio 			= exchangeRate_,

			moneda 				= moneda_,

			capitalPrestado 	= IFNULL(capitalPrestado_,0),				

			maxDiasMora 		= IFNULL(maxDiasMora_,0),

			montoAtrazado 		= IFNULL(montoAtrazado_,0),

			capitalAtrazado		= capitalAtrazado_,

			interesAtrazado		= interesAtrazado_,

			capitalPagado 		= IFNULL(capitalPagado_,0),

			interesPagado 		= IFNULL(interesPagado_,0),

			

			proximoPago 		= IF(DATE(NOW()) > maximoPago_,'1900-01-01',proximoPago_),

			montoProximoPago 	= IF(DATE(NOW()) > maximoPago_, 0,montoProximoPago_)  ,

			ultimoPagoFecha 	= maximoPago_,

			lastVisit = lastVisit_

		WHERE 

			ID = minClientID;

			

		

		SET minClientID					= (SELECT MIN(ID) FROM tmp_customer_credit_summary  WHERE ID > minClientID);



	END WHILE; 

	



	SELECT 

		RIGHT(customerNumber,15) as customerNumber ,		

		CONCAT(LEFT(firstName,250),' ',LEFT(lastName,250)) as legalName,

		commercialName,

		firstName ,lastName ,limitCredit,balanceCredit,

		exchangeRate_ - currencyTargetPurchase  as tipoCambioCompra,exchangeRate_ + currencyTargetSale as tipoCambioVenta,

		factura,		

		moneda,

		capitalPrestado,		

		maxDiasMora,

		montoAtrazado,

		capitalAtrazado,

		interesAtrazado,

		capitalPagado,

		interesPagado,		 

		proximoPago,

		montoProximoPago ,

		ultimoPagoFecha,

		direccion ,

		identification,

		phone ,

		lastShareNumber ,

		dateLastShareNumber ,

		amountLastShareNumber ,

		lastVisit ,

		remainingDocument  ,

		employerName

	FROM 

		tmp_customer_credit_summary

	ORDER BY 

		maxDiasMora desc, firstName;

	

	DROP TABLE tmp_customer_credit;

	DROP TABLE tmp_customer_credit_summary;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_credit_by_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_credit_by_user`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'cartera de credito diferenciada por moneda'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE minClientID int DEFAULT 0;

	DECLARE maxClientID int DEFAULT 0;

	DECLARE maxDiasMora_ int  DEFAULT 0;

	DECLARE montoAtrazado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE cantidadFactura_ INT DEFAULT 0;

	DECLARE customerNumber_ varchar(50); 

	DECLARE capitalPrestado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesDebengado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE capitalPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE proximoPago_ DATE;

	DECLARE montoProximoPago_ DECIMAL(19,9) DEFAULT 0;

	

	CREATE TEMPORARY TABLE tmp_customer_credit 					(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),

																moneda varchar(50),

																tipoCambio decimal(19,4),customerCreditLineID int,documentNumber varchar(50),statusDocument varchar(50),dateApply DATETIME,balanceStart DECIMAL(19,9),interest DECIMAL(19,9),capital DECIMAL(19,9),share DECIMAL(19,9),balanceEnd DECIMAL(19,9),remaining DECIMAL (19,9), dayDelay INT,statusShare varchar(50)

																); 

	CREATE TEMPORARY TABLE tmp_customer_credit_summary 			(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),

																moneda varchar(50),

																tipoCambio decimal(19,4),maxDiasMora int default 0,cantidadFactura int default 0,montoAtrazado decimal(19,9) default 0,capitalPrestado decimal(19,9) default 0,capitalPagado decimal(19,9) default 0,interesPagado decimal(19,9) default 0,proximoPago DATE ,montoProximoPago decimal(19,9) default 0

																); 

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	insert into tmp_customer_credit (customerNumber ,legalName ,commercialName ,firstName ,lastName ,limitCredit ,balanceCredit,tipoCambio ,customerCreditLineID ,documentNumber ,statusDocument ,dateApply ,balanceStart ,interest ,capital ,share ,balanceEnd ,remaining, dayDelay ,statusShare,moneda )

	select 

		c.customerNumber,

		l.legalName,

		l.comercialName,

		n.firstName,

		n.lastName,

		ccl.limitCredit  AS limitCredit,

		ccl.balance  AS balance,

		tmc.exchangeRate AS TipoCambio,

		ccl.customerCreditLineID,

		ccd.documentNumber,

		ws1.name,

		cca.dateApply,

		cca.balanceStart,

		cca.interest,

		cca.capital,

		cca.share,

		cca.balanceEnd,

		cca.remaining, 

		cca.dayDelay as dayDelay,

		ws2.name		,

		cur.simbol 

	from 

		tb_entity e

		inner join tb_customer c on 

			e.entityID = c.entityID

		inner join tb_naturales n on 

			c.entityID = n.entityID  

		inner join tb_legal l on 

			l.entityID = n.entityID

		inner join tb_customer_credit_line ccl on 

			e.entityID = ccl.entityID  

		inner join tb_customer_credit_document ccd on 

			ccd.entityID = e.entityID and 

			ccd.customerCreditLineID = ccl.customerCreditLineID 

		inner join tb_transaction_master tmc on  

			ccd.documentNumber = tmc.transactionNumber and 

			tmc.isActive = 1 and 

			ccd.isActive = 1 

		inner join tb_customer_credit_amoritization cca on 

			ccd.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			ccd.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on 

			cca.statusID = ws2.workflowStageID

		inner join tb_currency cur on 

			tmc.currencyID = cur.currencyID 

		inner join tb_user us on 

			us.employeeID = c.entityID 

	where

		e.companyID 	= prCompanyID and

		c.isActive 		= 1 and 

		ccl.isActive 	= 1  and 

		ccd.isActive 	= 1 and 

		tmc.isActive 	= 1 and 

		ws1.name not in ('CANCELADO','ANULADO') and

		(	

			   	((ccd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		c.customerNumber,ccd.dateOn, ccd.documentNumber,cca.creditAmortizationID;

		

	INSERT INTO tmp_customer_credit_summary (customerNumber ,legalName ,commercialName,firstName ,lastName ,moneda,limitCredit,balanceCredit )

	select 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ,

		x.moneda,

		SUM(x.limitCredit) as limitCredit,

		SUM(x.balanceCredit) as balanceCredit

	from 

		tmp_customer_credit x

	group by 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName,

		x.moneda ; 



	SET minClientID = (SELECT MIN(ID) FROM tmp_customer_credit_summary);

	SET maxClientID = (SELECT MAX(ID) FROM tmp_customer_credit_summary);

	WHILE minClientID <= maxClientID and minClientID is not null DO 	

		SET customerNumber_				= (SELECT C.customerNumber FROM tmp_customer_credit_summary C WHERE ID = minClientID );

		SET moneda_						   = (SELECT C.moneda FROM tmp_customer_credit_summary C WHERE ID = minClientID );



		SET maxDiasMora_				   = (SELECT DATEDIFF(NOW(),dateApply) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and moneda = moneda_ and dateApply <= curdate() AND remaining > 0  ORDER BY dateApply LIMIT 1 );

		SET cantidadFactura_			   = (SELECT COUNT(documentNumber) from (SELECT distinct C.documentNumber as documentNumber FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and moneda = moneda_ ) p );

		SET proximoPago_				   = (SELECT MIN(C.dateApply) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and remaining > 0 and moneda = moneda_ );

		SET montoAtrazado_				= (SELECT SUM(C.remaining)  FROM tmp_customer_credit C WHERE customerNumber = customerNumber_ and moneda = moneda_ and dateApply <= curdate() );

		SET capitalPrestado_			   = (SELECT SUM(capital) FROM tmp_customer_credit WHERE  customerNumber = customerNumber_  and moneda = moneda_);

		SET capitalPagado_				= (SELECT SUM((share - remaining) - interest)  FROM tmp_customer_credit WHERE  customerNumber = customerNumber_ and moneda = moneda_ and dateApply <= curdate() );

		SET interesPagado_				= (SELECT SUM( IF (((share - remaining) - capital   ) < 0 , 0 , (share - remaining) - capital   ) )   FROM tmp_customer_credit WHERE  customerNumber = customerNumber_  and moneda = moneda_ and dateApply <= curdate());

		SET montoProximoPago_			= (SELECT SUM(C.remaining)  FROM tmp_customer_credit C WHERE C.customerNumber = customerNumber_ and moneda = moneda_ and C.dateApply = proximoPago_ );  

 

		 

		UPDATE 	tmp_customer_credit_summary set tipoCambio = exchangeRate_,  maxDiasMora = IFNULL(maxDiasMora_,0),montoAtrazado = IFNULL(montoAtrazado_,0),cantidadFactura = IFNULL(cantidadFactura_,0),capitalPrestado = capitalPrestado + IFNULL(capitalPrestado_,0),	capitalPagado = capitalPagado + IFNULL(capitalPagado_,0),interesPagado = interesPagado + IFNULL(interesPagado_,0),proximoPago = proximoPago_,montoProximoPago = montoProximoPago_ where ID = minClientID;

		SET minClientID					= (SELECT MIN(ID) FROM tmp_customer_credit_summary  WHERE ID > minClientID);



	END WHILE; 





		SELECT 

		customerNumber ,legalName ,commercialName,firstName ,lastName ,

		limitCredit,

		balanceCredit,

		tipoCambio - currencyTargetPurchase  as tipoCambioCompra,

		tipoCambio + currencyTargetSale as tipoCambioVenta,

		moneda,

		maxDiasMora,

		montoAtrazado,

		cantidadFactura,

		capitalPrestado,

		0 capitalPagado,

		0 interesPagado,

		proximoPago,

		montoProximoPago 

	FROM 

		tmp_customer_credit_summary

	ORDER BY 

		proximoPago ASC,customerNumber , legalName;

	

		DROP TABLE tmp_customer_credit;

	DROP TABLE tmp_customer_credit_summary;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_credit_dolares` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_credit_dolares`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'reporte de lista de clientes de credito'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE minClientID int DEFAULT 0;

	DECLARE maxClientID int DEFAULT 0;

	DECLARE maxDiasMora_ int  DEFAULT 0;

	DECLARE montoAtrazado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE cantidadFactura_ INT DEFAULT 0;

	DECLARE customerNumber_ varchar(50); 

	DECLARE capitalPrestado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesDebengado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE capitalPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE proximoPago_ DATE;

	DECLARE montoProximoPago_ DECIMAL(19,9) DEFAULT 0;

	

	CREATE TEMPORARY TABLE tmp_customer_credit 					(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),

																moneda varchar(50),

																tipoCambio decimal(19,4),customerCreditLineID int,documentNumber varchar(50),statusDocument varchar(50),dateApply DATETIME,balanceStart DECIMAL(19,9),interest DECIMAL(19,9),capital DECIMAL(19,9),share DECIMAL(19,9),balanceEnd DECIMAL(19,9),remaining DECIMAL (19,9), dayDelay INT,statusShare varchar(50)

																); 

	CREATE TEMPORARY TABLE tmp_customer_credit_summary 			(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),																

																tipoCambio decimal(19,4),maxDiasMora int default 0,cantidadFactura int default 0,montoAtrazado decimal(19,9) default 0,capitalPrestado decimal(19,9) default 0,capitalPagado decimal(19,9) default 0,interesPagado decimal(19,9) default 0,proximoPago DATE ,montoProximoPago decimal(19,9) default 0

																); 

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	insert into tmp_customer_credit (customerNumber ,legalName ,commercialName ,firstName ,lastName ,limitCredit ,balanceCredit,tipoCambio ,customerCreditLineID ,documentNumber ,statusDocument ,dateApply ,balanceStart ,interest ,capital ,share ,balanceEnd ,remaining, dayDelay ,statusShare,moneda )

	select 

		c.customerNumber,

		l.legalName,

		l.comercialName,

		n.firstName,

		n.lastName,

		ccl.limitCredit  AS limitCredit,

		ccl.balance  AS balance,

		tmc.exchangeRate AS TipoCambio,

		ccl.customerCreditLineID,

		ccd.documentNumber,

		ws1.name,

		cca.dateApply,

		cca.balanceStart,

		cca.interest,

		cca.capital,

		cca.share,

		cca.balanceEnd,

		cca.remaining, 

		cca.dayDelay as dayDelay,

		ws2.name		,

		cur.name 

	from 

		tb_entity e

		inner join tb_customer c on 

			e.entityID = c.entityID

		inner join tb_naturales n on 

			c.entityID = n.entityID  

		inner join tb_legal l on 

			l.entityID = n.entityID

		inner join tb_customer_credit_line ccl on 

			e.entityID = ccl.entityID  

		inner join tb_customer_credit_document ccd on 

			ccd.entityID = e.entityID and 

			ccd.customerCreditLineID = ccl.customerCreditLineID 

		inner join tb_transaction_master tmc on  

			ccd.documentNumber = tmc.transactionNumber and 

			tmc.isActive = 1 and 

			ccd.isActive = 1 

		inner join tb_customer_credit_amoritization cca on 

			ccd.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			ccd.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on 

			cca.statusID = ws2.workflowStageID

		inner join tb_currency cur on 

			tmc.currencyID = cur.currencyID 

	where

		e.companyID 	= prCompanyID and

		c.isActive 		= 1 and 

		ccl.isActive 	= 1  and 

		ccd.isActive 	= 1 and 

		tmc.isActive 	= 1 and 

		ws1.name not in ('CANCELADO','ANULADO') and 

		(	

			   	((ccd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		c.customerNumber,ccd.dateOn, ccd.documentNumber,cca.creditAmortizationID;

		

	INSERT INTO tmp_customer_credit_summary (customerNumber ,legalName ,commercialName,firstName ,lastName ,limitCredit,balanceCredit )

	select 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ,	

		SUM(x.limitCredit) as limitCredit,

		SUM(x.balanceCredit) as balanceCredit

	from 

		tmp_customer_credit x

	group by 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ; 





	SET minClientID = (SELECT MIN(ID) FROM tmp_customer_credit_summary);

	SET maxClientID = (SELECT MAX(ID) FROM tmp_customer_credit_summary);

	WHILE minClientID <= maxClientID and minClientID is not null DO 	

		SET customerNumber_				= (SELECT C.customerNumber FROM tmp_customer_credit_summary C WHERE ID = minClientID );



		SET maxDiasMora_				   = (SELECT MAX(C.dayDelay) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and dateApply <= curdate() );

		SET cantidadFactura_			   = (SELECT COUNT(documentNumber) from (SELECT distinct C.documentNumber as documentNumber FROM tmp_customer_credit C WHERE customerNumber = customerNumber_   ) p );

		SET proximoPago_				   = (SELECT MIN(C.dateApply) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and remaining > 0 );

			

		SET montoAtrazado_				= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,(C.remaining) 													/ (exchangeRate_ + currencyTargetSale) 							, C.remaining ))   FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and dateApply <= curdate() );

		SET capitalPrestado_			   = (SELECT SUM( IF (C.moneda = currencyIDNameSource ,(C.capital) 														/ (exchangeRate_ + currencyTargetSale)								, C.capital))  FROM tmp_customer_credit C WHERE  customerNumber = customerNumber_  );

		SET capitalPagado_				= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,((share - remaining) - interest) 							/ (exchangeRate_ + currencyTargetSale)								,((share - remaining) - interest)))  FROM tmp_customer_credit C  WHERE  customerNumber = customerNumber_ and dateApply <= curdate() );

		SET interesPagado_				= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,((share - remaining) - capital) 							/ (exchangeRate_ + currencyTargetSale) 							,((share - remaining) - capital)))  FROM tmp_customer_credit C WHERE  customerNumber = customerNumber_   and dateApply <= curdate());

		SET montoProximoPago_			= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,(C.remaining) 													/ (exchangeRate_ + currencyTargetSale)								, C.remaining))   FROM tmp_customer_credit C WHERE C.customerNumber = customerNumber_ and C.dateApply = proximoPago_ );  



		 

		UPDATE 	tmp_customer_credit_summary set tipoCambio = exchangeRate_,  maxDiasMora = IFNULL(maxDiasMora_,0),montoAtrazado = IFNULL(montoAtrazado_,0),cantidadFactura = IFNULL(cantidadFactura_,0),capitalPrestado = capitalPrestado + IFNULL(capitalPrestado_,0),	capitalPagado = capitalPagado + IFNULL(capitalPagado_,0),interesPagado = interesPagado + IFNULL(interesPagado_,0),proximoPago = proximoPago_,montoProximoPago = montoProximoPago_ where ID = minClientID;

		SET minClientID					= (SELECT MIN(ID) FROM tmp_customer_credit_summary  WHERE ID > minClientID);

 

	END WHILE; 





		SELECT 

		customerNumber ,legalName ,commercialName,firstName ,lastName ,

		limitCredit,

		balanceCredit,

		tipoCambio - currencyTargetPurchase  as tipoCambioCompra,

		tipoCambio + currencyTargetSale as tipoCambioVenta,

		'Dolares' as moneda,

		maxDiasMora,

		montoAtrazado,

		cantidadFactura,

		capitalPrestado,capitalPagado,interesPagado,proximoPago,montoProximoPago 

	FROM  

		tmp_customer_credit_summary;

	

		DROP TABLE tmp_customer_credit;

	DROP TABLE tmp_customer_credit_summary;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_expensas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_expensas`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	select 

		ccc.customerCreditDocumentID,

		ccc.documentNumber,

		ccc.dateOn,

		round(ccc.amount,2) as amount,

		cur.simbol

	from 

		tb_customer_credit_document ccc

		inner join tb_customer cu on 

			ccc.entityID = cu.entityID 

		inner join tb_user us on 

			cu.entityID = us.employeeID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

	where

		ws.vinculable = 1 

		and us.userID = prUserID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		);

		

	select 

		ccc.customerCreditDocumentID,

		ccc.documentNumber,

		cca.dateApply,

		round(cca.share,2) as cuota,

		round(cca.remaining,2) as remaining ,

		cur.simbol

	from 

		tb_customer_credit_document ccc

		inner join tb_customer cu on 

			ccc.entityID = cu.entityID 

		inner join tb_user us on 

			cu.entityID = us.employeeID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID

		inner join tb_customer_credit_amoritization cca on 

			ccc.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

	where

		ws.vinculable = 1 

		and cca.remaining <> 0

		and us.userID = prUserID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		cca.customerCreditDocumentID,cca.dateApply ; 

		

		

	select 

		ccc.customerCreditDocumentID,

		tm.transactionNumber,

		cast(tm.createdOn as date) as createdOn,

		round(tmd.amount,2)  as amount,

		cur.simbol

	from 

		tb_customer_credit_document ccc

		inner join tb_customer cu on 

			ccc.entityID = cu.entityID 

		inner join tb_user us on 

			cu.entityID = us.employeeID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID

		inner join tb_transaction_master_detail tmd on 

			tmd.componentItemID = ccc.customerCreditDocumentID  

		inner join tb_transaction_master tm on 

			tmd.transactionMasterID = tm.transactionMasterID 

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

	where

		ws.vinculable = 1 and 

		tmd.transactionID in (23,24,25)

		and us.userID = prUserID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		ccc.documentNumber,tm.createdOn;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_list`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'lista de clientes'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE type VARCHAR(250);

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	SET type = (SELECT U.type FROM tb_company U WHERE U.companyID = prCompanyID);

	

	

	select 

		t.customerNumber,

		t.customerName,

		t.identification,

		t.phone,

		t.email,

		t.Moneda,

		ROUND(sum(IF(t.balanceTotal IS NULL,0, t.balanceTotal)),2) as balanceTotal,

		ROUND(sum(IF(t.balanceTotalCapital IS NULL,0, t.balanceTotalCapital)),2) as balanceTotalCapital,

		ROUND(sum(IF(t.balanceTotalInteres IS NULL,0, t.balanceTotalInteres)),2) as balanceTotalInteres

		

	from 

		(

		select 

			c.customerNumber,

			concat(n.firstName , ' ' , n.lastName ) as customerName,

			c.identification,

			COALESCE(ep.number,c.phoneNumber) as phone,

			em.email as email ,

			

			IFNULL(cur.`name`,'N/D') as Moneda,

			IFNULL(ccc.currencyID,0) as currencyID ,

			

			(

				select 

					sum(u.remaining) 

				from 

					tb_customer_credit_amoritization u 

				where 

					u.customerCreditDocumentID = ccc.customerCreditDocumentID  and 

					u.remaining > 0 

			) as balanceTotal,

			

			(

				select 

					sum(u.capital) 

				from 

					tb_customer_credit_amoritization u 

				where 

					u.customerCreditDocumentID = ccc.customerCreditDocumentID  and 

					u.remaining > 0 

			) as balanceTotalCapital,

			

			(

				select 

					sum(u.interest) 

				from 

					tb_customer_credit_amoritization u 

				where 

					u.customerCreditDocumentID = ccc.customerCreditDocumentID  and 

					u.remaining > 0 

			) as balanceTotalInteres

			

			

			 

		from 

			tb_customer c

			inner join tb_entity e on 	

				c.entityID = e.entityID and 

				c.companyID = e.companyID  

			inner join tb_naturales n on 

				e.entityID = n.entityID and 

				e.companyID = n.companyID 

			left join tb_entity_phone ep on 

				e.entityID = ep.entityID and 

				e.companyID = ep.companyID and 

				ep.isPrimary = true

			left join tb_entity_email em on 

				e.entityID = em.entityID and 

				e.companyID = em.companyID and 

				em.isPrimary = true  

			left join tb_customer_credit_document ccc on 

				c.entityID = ccc.entityID 

			left join tb_workflow_stage ws on 

				ccc.statusID = ws.workflowStageID 

			left join tb_currency cur on 

				cur.currencyID = ccc.currencyID

		where

			c.isActive = 1 and 

			e.companyID = prCompanyID and 

			(

				(

					ws.aplicable in (1) and 

					type != 'globalpro' 

				) or 

				(

					type = 'globalpro'

				)

			)

			and 			

			(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

			)

			

			

		) t 

	group by 

		t.customerNumber,

		t.customerName,

		t.identification,

		t.phone,

		t.email,

		t.Moneda;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_pay` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_pay`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50), IN `prReference` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Lista de Pagos del Cliente'
BEGIN





	select 

	  ROW_NUMBER() OVER (PARTITION BY tmd.reference1 ORDER BY transactionNumber) AS contador, 		

		tm.createdOn,

		tm.transactionNumber,

		usr.nickname as userName,

		tm.note,

		tmd.amount as Pago,

		tmd.reference1,

		tcc.amount as MontoDesembolso,

		tcc.balance as Balance,

		cur.name as MonedaDesembolso,

		case 

			when tmd.reference2 is null then 

				0 

			when tmd.reference2 = '' then 

				0

			else

				tmd.reference2

		end  SaldoAterior,

		case 

			when tmd.reference4 is null then 

				0 

			when tmd.reference4 = '' then 

				0

			else

				tmd.reference4

		end  SaldoNuevo

	from 

		tb_transaction t 

		inner join tb_transaction_master tm on 

			t.transactionID = tm.transactionID and 

			t.companyID = tm.companyID 

		inner join tb_transaction_master_detail tmd on 

			tm.companyID = tmd.companyID and 

			tm.transactionID = tmd.transactionID and 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_workflow_stage ws on  

			tm.statusID = ws.workflowStageID 

		inner join tb_transaction_master tm2 on 

			tmd.reference1 = tm2.transactionNumber and 

			tmd.companyID = tm2.companyID 

		inner join tb_workflow_stage ws2 on 

			tm2.statusID = ws2.workflowStageID 

		inner join tb_customer_credit_document tcc on 

			tm2.transactionNumber = tcc.documentNumber and 

			tm.entityID = tcc.entityID 

		inner join tb_customer c on 

			tm.entityID = c.entityID 

		inner join tb_currency cur on 

			tm.currencyID = cur.currencyID 

		inner join tb_user usr on 

			usr.userID = tm.createdBy 

	where

		t.companyID = prCompanyID  and 

		t.isActive = 1 and 

		t.transactionID in (23 ,24 ,25 ) and 

		tm.isActive =  1 and 

		tmd.isActive = 1 and 

		ws.aplicable = 1 and 

		tm2.isActive = 1 and 

		ws2.vinculable = 1 and 

		c.customerNumber = prCustomerNumber and 

		(	

			   	((tcc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		) and 

		(

				( tcc.documentNumber  = prReference and prReference != '0') or

				( prReference = '0' ) 

		)

	order by 

		tmd.reference1 desc ,tm.transactionNumber desc  ;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_pay_by_invoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_pay_by_invoice`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50),IN prInvoiceNumber  VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Lista de Pagos del Cliente'
BEGIN

	DECLARE varMin DATETIME;

	DECLARE varMax DATETIME;



	

	select 

		cu.customerNumber,

		CONCAT(nat.firstName,' ' , nat.lastName ) as customerName,

		cu.phoneNumber ,

		cu.location,

		cu.identification,

		

		c.dateOn as fechaDesembolso,

		round(c.amount,2) as montoDesembolso,

		round(c.interes,2) as interesDesembolso,

		c.term as plazo,

		ws.NAME as statusDesembolso,

		ci.NAME as frecuenciaDesembolso 

	from 

		tb_customer_credit_document c 

		inner join tb_customer cu on 

			cu.entityID = c.entityID 

		inner join tb_naturales nat on 

			nat.entityID = cu.entityID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

		inner join tb_catalog_item ci on 

			ci.catalogItemID = c.periodPay 

	where 

		c.documentNumber = prInvoiceNumber;

		

		

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_abonos_temp;

	CREATE TEMPORARY TABLE tbl_abonos_temp AS

	select 

	  ROW_NUMBER() OVER (PARTITION BY tmd.reference1 ORDER BY transactionNumber) AS contador, 		

		tm.createdOn,

		date(tm.createdOn) as createdOnDate, 

		tm.transactionNumber,

		usr.nickname as userName,

		tm.note,

		tmd.amount as Pago,

		tmd.reference1,

		tcc.amount as MontoDesembolso,

		tcc.balance as Balance,

		cur.simbol as MonedaDesembolso,

		case 

			when tmd.reference2 is null then 

				0 

			when tmd.reference2 = '' then 

				0

			else

				tmd.reference2

		end  SaldoAterior,

		case 

			when tmd.reference4 is null then 

				0 

			when tmd.reference4 = '' then 

				0

			else

				tmd.reference4

		end  SaldoNuevo

	from 

		tb_transaction t 

		inner join tb_transaction_master tm on 

			t.transactionID = tm.transactionID and 

			t.companyID = tm.companyID 

		inner join tb_transaction_master_detail tmd on 

			tm.companyID = tmd.companyID and 

			tm.transactionID = tmd.transactionID and 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_workflow_stage ws on  

			tm.statusID = ws.workflowStageID 

		inner join tb_transaction_master tm2 on 

			tmd.reference1 = tm2.transactionNumber and 

			tmd.companyID = tm2.companyID 

		inner join tb_workflow_stage ws2 on 

			tm2.statusID = ws2.workflowStageID 

		inner join tb_customer_credit_document tcc on 

			tm2.transactionNumber = tcc.documentNumber and 

			tm.entityID = tcc.entityID 

		inner join tb_customer c on 

			tm.entityID = c.entityID 

		inner join tb_currency cur on 

			tm.currencyID = cur.currencyID 

		inner join tb_user usr on 

			usr.userID = tm.createdBy 

	where

		t.companyID = prCompanyID  and 

		t.isActive = 1 and 

		t.transactionID in (23 ,24 ,25 ) and 

		tm.isActive =  1 and 

		tmd.isActive = 1 and 

		ws.aplicable = 1 and 

		tm2.isActive = 1 and 

		ws2.vinculable = 1 and 

		tcc.documentNumber = prInvoiceNumber 

	order by 

		tmd.reference1 desc ,tm.transactionNumber desc  ;

		

	

	DROP TEMPORARY TABLE IF EXISTS tbl_fecha_temp;

	CREATE TEMPORARY TABLE tbl_fecha_temp AS

	select 

		amo.dateApply  

	from 

		tb_customer_credit_document c 

		inner join tb_customer_credit_amoritization amo on 

			amo.customerCreditDocumentID = c.customerCreditDocumentID 

	where 

		c.documentNumber = prInvoiceNumber and 

		c.isActive = 1 and 

		amo.isActive = 1  

	order by 

		amo.dateApply ;

		

	

	SET varMin = (select min(dateApply) from tbl_fecha_temp );

	SET varMax = (select max(dateApply) from tbl_fecha_temp );

	WHILE varMin <= varMax DO 

	

	  IF NOT EXISTS (SELECT * FROM tbl_abonos_temp k where k.createdOnDate = varMin) THEN 

			INSERT INTO tbl_abonos_temp(

				contador, 		

				createdOn,

				createdOnDate, 

				transactionNumber,

				userName,

				note,

				Pago,

				reference1,

				MontoDesembolso,

				Balance,

				MonedaDesembolso,

				SaldoAterior,

				SaldoNuevo

			) VALUES

			(

				1,

				varMin,

				varMin,

				'',

				'',

				'',

				0,

				'',

				0,

				0,

				'',

				0,

				0

			);

		END IF;

		

		

	  

		SET varMin = (select min(dateApply) from tbl_fecha_temp where dateApply > varMin );

	END WHILE;

	

	

	SELECT 

		contador, 		

		createdOn,

		createdOnDate, 

		transactionNumber,

		userName,

		note,

		Pago,

		reference1,

		MontoDesembolso,

		Balance,

		MonedaDesembolso,

		SaldoAterior,

		SaldoNuevo

	FROM 

		tbl_abonos_temp kk 

	ORDER BY 

		kk.createdOnDate ASC ;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_sr_list` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_sr_list`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'muestra la lista de consultas elaboradas a sin riesgo'
BEGIN

	select 

		c.requestID ,

		c.name as cliente,

		c.id as cedulaCliente,

		c.`file` as file_,

		c.createdOn,

		ux.nickname as Usuario,

		IF(c.isPay = 1,'Pagado','Pendiente') as Estado

	from 

		tb_customer_consultas_sin_riesgo c

		inner join tb_user ux on 

			ux.userID = c.userID 

	where

		c.companyID = prCompanyID and 

        c.createdOn between prStartOn and prEndOn 

	order by 

		c.createdOn asc ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_customer_status`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Estado de cuenta del cliente'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE deudaTotalCordobas DECIMAL(19,0) DEFAULT 0;

	DECLARE deudaTotalDolares DECIMAL(19,0) DEFAULT 0;

	

	

	CREATE TEMPORARY TABLE tmp_customer_credit (

		ID INT AUTO_INCREMENT PRIMARY KEY,customerCreditDocumentID INT, 

		customerNumber VARCHAR(50), identificationType VARCHAR(50),

		firstName VARCHAR(150),lastName VARCHAR(150),comercialName VARCHAR(150),

		legalName VARCHAR(150),address VARCHAR(500),identification VARCHAR(50),

		country VARCHAR(50),state VARCHAR(50),city VARCHAR(120),birth DATE,statusClient VARCHAR(60),

		limitCreditCordoba DECIMAL(19,9),balanceCordoba DECIMAL(19,9), 

		deudaCordobas DECIMAL(19,9), deudaDolares DECIMAL(19,9), incomeCordoba DECIMAL(19,9),

		lineName VARCHAR(120),lineNumber VARCHAR(50) ,limitCreditCordobaLinea DECIMAL(19,9),

		balanceCordobaLinea DECIMAL(19,9),interestYearLine DECIMAL(19,9),

		termLine DECIMAL(19,9),periodPayLine VARCHAR(50),statusLine VARCHAR(50),

		documentNumber VARCHAR(50),documentOn DATE,amountDocument DECIMAL(19,9),

		interesDocument DECIMAL(19,9),termDocument DECIMAL(19,9),periodPayDocument VARCHAR(150),

		statusDocument VARCHAR(50),dateApplyAmori DATE,balanceStartAmori DECIMAL(19,9),

		interestAmori DECIMAL(19,9),capitalAmori DECIMAL(19,9),shareAmori DECIMAL(19,9),

		balanceEndAmori DECIMAL(19,9),remainingAmori DECIMAL(19,9),dayDelayAmori INT,

		statusShare VARCHAR(50),moneda VARCHAR(150),balanceDocument DECIMAL(19,9), 

		nota VARCHAR(1500), phoneNumber VARCHAR(255)

	); 



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	





	INSERT INTO tmp_customer_credit (

		customerCreditDocumentID,customerNumber , 

		identificationType ,firstName ,lastName ,comercialName ,

		legalName ,address ,identification ,country ,state ,city ,birth ,

		statusClient ,limitCreditCordoba ,balanceCordoba ,

		deudaCordobas,deudaDolares ,incomeCordoba ,lineName ,

		lineNumber  ,limitCreditCordobaLinea ,balanceCordobaLinea ,

		interestYearLine ,termLine ,periodPayLine ,statusLine ,

		documentNumber ,documentOn ,amountDocument,balanceDocument ,

		interesDocument ,termDocument,periodPayDocument ,statusDocument ,

		dateApplyAmori ,balanceStartAmori ,interestAmori ,capitalAmori ,

		shareAmori ,balanceEndAmori ,remainingAmori ,dayDelayAmori ,

		statusShare , moneda,nota,phoneNumber

	)

	SELECT 

		ccd.customerCreditDocumentID,

		c.customerNumber,

		ci1.name as identificationType,

		n.firstName,

		n.lastName,

		l.comercialName,

		l.legalName,

		c.address,

		c.identification,

		ci2.name as country,

		ci3.name as state,

		ci4.name as city,

		c.birthDate,

		ws1.name as statusClient,

		round((cc.limitCreditDol / exchangeRate_),2) as limitCreditCordoba,

		round((cc.balanceDol / exchangeRate_),2) as balanceCordoba,

		IF(ccd.currencyID = 1,ccda.remaining, 0) AS deudaCordobas,

		IF(ccd.currencyID = 2,ccda.remaining, 0) AS deudaDolares,

		round((cc.incomeDol / exchangeRate_),2) as incomeCordoba ,

		cl.name,

		ccl.accountNumber,

		IF(ccl.currencyID = currencyID_,ccl.limitCredit,round(ccl.limitCredit / exchangeRate_,2)  ) AS limitCreditCordobaLinea,

		IF(ccl.currencyID = currencyID_,ccl.balance,round(ccl.balance / exchangeRate_,2)  ) AS balanceCordobaLinea,

		ccl.interestYear,

		ccl.term,

		ci5.name as periodPayLine,

		ws2.name as statusLine,

		ccd.documentNumber,

		ccd.dateOn,

		ccd.amount,

		if(ccd.balance < 0 , 0 , ccd.balance) as balance,

		ccd.interes,

		ccd.term,

		ci6.name as periodPayDocument,

		ws3.name as statusDocument,

		ccda.dateApply,

		ccda.balanceStart,

		ccda.interest,

		ccda.capital,

		ccda.`share`,

		ccda.balanceEnd,

		ccda.remaining,

		ccda.dayDelay,

		ws4.name as statusShare,

		curx.name ,

		ccd.reference1 as nota,

		c.phoneNumber 

	FROM

		tb_entity e 

		inner join tb_customer c on 

			e.entityID = c.entityID and 

			e.companyID = c.companyID 

		inner join tb_naturales n on 

			c.entityID = n.entityID and

			c.companyID = n.companyID 

		inner join tb_legal l on 

			n.entityID = l.entityID and 

			n.companyID = l.companyID 

		inner join tb_customer_credit cc on 

			e.entityID = cc.entityID and 

			e.companyID = cc.companyID 

		inner join tb_customer_credit_line ccl on 

			cc.entityID = ccl.entityID  and 

			cc.companyID = ccl.companyID 

		inner join tb_credit_line cl on 

			ccl.creditLineID = cl.creditLineID 		

		inner join tb_catalog_item ci1 on 

			c.identificationType = ci1.catalogItemID 

		inner join tb_catalog_item ci2 on 

			c.countryID = ci2.catalogItemID 

		inner join tb_catalog_item ci3 on 

			c.stateID = ci3.catalogItemID 

		inner join tb_catalog_item ci4 on 

			c.cityID = ci4.catalogItemID 

		inner join tb_workflow_stage ws1 on 

			c.statusID = ws1.workflowStageID 

		inner join tb_catalog_item ci5 on 

			ccl.periodPay = ci5.catalogItemID 

		inner join tb_workflow_stage ws2 on 

			ccl.statusID = ws2.workflowStageID 

			

			

		left join tb_customer_credit_document ccd on 

			ccd.entityID =  c.entityID 

		left join tb_customer_credit_amoritization ccda on 

			ccd.customerCreditDocumentID = ccda.customerCreditDocumentID  

		left join tb_catalog_item ci6 on 

			ccd.periodPay = ci6.catalogItemID 		

		left join tb_workflow_stage ws3 on 

			ccd.statusID = ws3.workflowStageID 

		left  join tb_workflow_stage ws4 on 

				ccda.statusID = ws4.workflowStageID 	

		

		left join tb_transaction_master tmx on 	

				ccd.documentNumber = tmx.transactionNumber and 

				ccd.companyID = tmx.companyID and 

				ccd.entityID = tmx.entityID 

		left join tb_currency curx on 

			tmx.currencyID = curx.currencyID 

		

					

	where

		e.companyID = prCompanyID and 

		c.customerNumber = prCustomerNumber;

		

	

		

	CREATE TEMPORARY TABLE tmp_customer_credit_v2 select * from tmp_customer_credit;

	CREATE TEMPORARY TABLE tmp_customer_credit_v3 select * from tmp_customer_credit;

	CREATE TEMPORARY TABLE tmp_customer_credit_v4 select * from tmp_customer_credit;

	CREATE TEMPORARY TABLE tmp_customer_credit_v5 select * from tmp_customer_credit;

	CREATE TEMPORARY TABLE tmp_customer_credit_v6 select * from tmp_customer_credit;

	CREATE TEMPORARY TABLE tmp_customer_credit_v7 select * from tmp_customer_credit;

	

	

	SELECT SUM(deudaCordobas) INTO deudaTotalCordobas

    FROM tmp_customer_credit;

  

   SELECT SUM(deudaDolares) INTO deudaTotalDolares

    FROM tmp_customer_credit;

    





	

	SELECT 

		DISTINCT 

		'INFORMACION_DEL_CLIENTE',

		customerNumber , 

		identificationType ,

		firstName ,

		lastName ,

		comercialName ,

		legalName ,

		address ,

		identification ,country ,

		state ,city ,birth ,

		statusClient ,limitCreditCordoba ,

		balanceCordoba ,

		deudaTotalCordobas AS deudaCordobas, deudaTotalDolares AS deudaDolares,

		incomeCordoba,

		x.phoneNumber 

	FROM 

		tmp_customer_credit x;





	

	SELECT 

		DISTINCT 

		'INFORMACION_DE_LINEA_CREDITO',

		lineName ,lineNumber  ,

		limitCreditCordobaLinea ,

		balanceCordobaLinea ,

		interestYearLine ,termLine ,

		periodPayLine ,

		statusLine 

	FROM 

		tmp_customer_credit x;

	

	

	SELECT 

		DISTINCT 

		'INFORMACION_DOCUMENTO_CREDITO',	

		lineNumber,	

		documentNumber ,documentOn ,amountDocument ,

		interesDocument ,

		interesDocument / 120 as interesDocumentMultiploDe120,

		termDocument ,

		periodPayDocument,

		statusDocument,moneda,

		balanceDocument,

		

		IFNULL((

			select 

				datediff(current_date(),min(p.dateApplyAmori)) 

			from 			 

				tmp_customer_credit_v2 p

			where

				p.customerCreditDocumentID = x.customerCreditDocumentID and 

				p.remainingAmori <> 0 

		),0)    as dayAtrazo,

		

		IFNULL((

			select 

				sum(p.remainingAmori) 

			from 			 

				tmp_customer_credit_v3 p

			where

				p.customerCreditDocumentID = x.customerCreditDocumentID and 

				p.remainingAmori <> 0 and 

				p.dateApplyAmori <= current_date() 

		),0)    as amountAtrazo ,

		

		(

			select 

				sum(tn.interestAmori) 

			from 

				tmp_customer_credit_v4 tn

			where

				tn.customerCreditDocumentID = x.customerCreditDocumentID 

				

		) as interestTotalMontoDocument,



		(

			select 

				max(p.dateApplyAmori)

			from 			 

				tmp_customer_credit_v5 p

			where

				p.customerCreditDocumentID = x.customerCreditDocumentID  

		)    as vencimientoUltimaCuota,

			

			

		IFNULL((

			select 

				AVG(p.dayDelayAmori) 

			from 			 

				tmp_customer_credit_v6 p

			where

				p.customerCreditDocumentID = x.customerCreditDocumentID and 

				p.dayDelayAmori <> 0 

		),0)    as promedioDiaPago ,

		

		

		IFNULL((

			select 

				p.dayDelayAmori 

			from 			 

				tmp_customer_credit_v7 p

			where

				p.customerCreditDocumentID = x.customerCreditDocumentID 

			ORDER BY 

				p.dateApplyAmori 				

			LIMIT 1

		),0)    as atrasoCancelacionDia ,

		

		x.nota 				

	FROM 

		tmp_customer_credit x 

	order by 

		documentOn;

		

	

	SELECT 

		DISTINCT 

		'INFORMACION_TABLA_AMORITIZACION',

		documentNumber ,

		dateApplyAmori ,balanceStartAmori ,

		interestAmori ,capitalAmori ,

		shareAmori ,balanceEndAmori ,remainingAmori ,dayDelayAmori ,statusShare

	FROM 

		tmp_customer_credit x  ;

			

			

	DROP TABLE tmp_customer_credit; 

	DROP TABLE tmp_customer_credit_v2; 

	DROP TABLE tmp_customer_credit_v3; 

	DROP TABLE tmp_customer_credit_v4; 

	DROP TABLE tmp_customer_credit_v5;

	DROP TABLE tmp_customer_credit_v6;

	DROP TABLE tmp_customer_credit_v7;

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_document_contract` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_document_contract`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prDocumentNumber` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener el contrato de credito'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE nameLayEmployee VARCHAR(250);

	DECLARE nameLayEmployeeEstadoCivil VARCHAR(250);

	DECLARE nameLayEmployeeQuinquenio VARCHAR(500);



	CALL pr_core_get_parameter_value(prCompanyID,"CXC_LAY_EMPLOYER_NAME",nameLayEmployee);

	CALL pr_core_get_parameter_value(prCompanyID,"CXC_LAY_EMPLOYER_STATUS_PUBLIC",nameLayEmployeeEstadoCivil);

	CALL pr_core_get_parameter_value(prCompanyID,"CXC_LAY_EMPLOYER_QUINQUENIO",nameLayEmployeeQuinquenio);

				

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);	

		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	





	select 

		tx.legalName,

		tx.identification,

		tx.address,

		min(tx.dateApply) as fechInicial,

		tx.curridate as fechActual,

		tx.currencyName,

		tx.sexo,

		tx.term,

		tx.period,

		tx.createdOn,

		tx.NombreProveedor,

		tx.CedulaProveedor,

		tx.DireccionProveedor,

		tx.estadoCivil,

		tx.profesion,

		tx.Domicilio,

		tx.typeFirmaCustomer,

		tx.typeFirmaProvider,	

		round(fn_calculate_exchange_rate(tx.companyID,tx.dateOn,1  ,2  ,1  ),2) as TipoCambio,

		round(sum(tx.share),2) as cuota,

		round(sum(tx.shareDolares),2) as cuotaDolares, 

		(round(sum(tx.shareDolares),2) / tx.term) as montoCuota,

		tx.amountTotal,

		tx.receiptAmount, 

		max(tx.dateApply) as fechFinal,

		DATEDIFF(max(tx.dateApply),min(tx.dateApply)) as DuracionDelCredito,

		nameLayEmployee,

		nameLayEmployeeEstadoCivil,

		nameLayEmployeeQuinquenio, 

		documentNumber,

		Concepto,

		phoneNumber,

		lugarTrabajo,

		birthDate,

		CantidadProductos,

		

		phoneNumberTransactionMaster,

		referenceClientIdentifier,

		referenceClientName, 

		Zona,

		

		IFNULL((

				select (xp.reference2) from tb_transaction_master_detail_credit xp 

				where xp.transactionMasterID = tx.transactionMasterID ),'*******'

		) as LayWritePublicNumber,

		IFNULL((

				select (xp.reference1) from tb_transaction_master_detail_credit xp 

				where xp.transactionMasterID = tx.transactionMasterID ),'*******'

		) as LayPasoAnteMi,

		IFNULL((

				select (xp.reference3) from tb_transaction_master_detail_credit xp 

				where xp.transactionMasterID = tx.transactionMasterID ),'*******'

		) as LayPrimerLineaProtocolo,

		IFNULL((

				select (xpz.itemNameLog) from tb_transaction_master_detail xpz 

				where xpz.transactionMasterID = tx.transactionMasterID ),'*******'

		) as productNameLog

	from 

		(

			SELECT 

				zon.`name` as Zona,

				tmx.numberPhone as phoneNumberTransactionMaster,

				tmi.referenceClientIdentifier,

				tmi.referenceClientName,

				cu.birthDate,

				cu.phoneNumber,

				cu.reference1 as lugarTrabajo,

				tmx.transactionMasterID,

				c.companyID,

				leg.legalName,

				cu.identification,

				leg.address,

				c.dateOn,

				c.term,

				tmx.amount as amountTotal,

				tmx.createdOn,

				now() as curridate,

				amori.share,

				amori.dateApply,

				cur.name as currencyName ,		

				ci2.name as sexo,

				ci1.name as period,

				estadoCivil.display as estadoCivil,

				profesion.display as profesion,

				IF(c.currencyID = currencyID_,amori.share * c.exchangeRate,amori.share / c.exchangeRate  ) AS shareDolares,

				CONCAT(natProv.firstName , ' ',natProv.lastName ) as NombreProveedor,

				pro.numberIdentification as CedulaProveedor,

				pro.address as DireccionProveedor ,

				cu.location as Domicilio,

				firma.display as typeFirmaCustomer,

				'Ilegible' as typeFirmaProvider,

				c.documentNumber ,

				tmi.receiptAmount,

				(select xl.note from tb_transaction_master xl where xl.transactionNumber = c.documentNumber limit 1) as Concepto,

				( select 

					sum(xmld.quantity)

					from 

					tb_transaction_master_detail xmld 

					where xmld.transactionMasterID = tmx.transactionMasterID and xmld.isActive = 1  

				) as CantidadProductos

			FROM 

				tb_customer_credit_document c 

				inner join tb_transaction_master tmx on 

					c.companyID = tmx.companyID and 

					c.documentNumber = tmx.transactionNumber and 

					c.entityID = tmx.entityID 

				inner join tb_provider pro on 

					c.providerIDCredit = pro.entityID 

				inner join tb_naturales natProv on 

					pro.entityID = natProv.entityID 

				inner join tb_currency cur on 

					tmx.currencyID = cur.currencyID 

				inner join tb_customer_credit_line l on 

					c.customerCreditLineID = l.customerCreditLineID 

				inner join tb_customer cu on 

					l.entityID = cu.entityID and 

					l.companyID = cu.companyID 

				inner join tb_naturales nat on 

					cu.entityID = nat.entityID and 

					cu.companyID = nat.companyID

				inner join tb_legal leg on 

					nat.entityID = leg.entityID  and 

					nat.companyID = leg.companyID 

				inner join tb_entity ent on 

					leg.entityID = ent.entityID and 

					leg.companyID = ent.companyID 

				inner join tb_customer_credit_amoritization amori on 

					c.customerCreditDocumentID = amori.customerCreditDocumentID 

				inner join tb_workflow_stage ws1 on 

					l.statusID = ws1.workflowStageID 

				inner join tb_workflow_stage ws2 on 

					c.statusID = ws2.workflowStageID 

				inner join tb_workflow_stage ws3 on 

					amori.statusID = ws3.workflowStageID 

				inner join tb_catalog_item ci1 on 

					l.periodPay = ci1.catalogItemID 

				inner join tb_catalog_item ci2 on 

					cu.sexoID = ci2.catalogItemID 

				inner join tb_catalog_item estadoCivil on 

					estadoCivil.catalogItemID = nat.statusID 

				inner join tb_catalog_item profesion on

				 	profesion.catalogItemID = nat.profesionID 

				inner join tb_catalog_item firma on 

					cu.typeFirm = firma.catalogItemID

				inner join tb_transaction_master_info tmi on 

							tmi.transactionMasterID = tmx.transactionMasterID 

				inner join tb_catalog_item zon on 

					zon.catalogItemID = tmi.zoneID 

			where

				c.companyID = prCompanyID and 

				c.documentNumber = prDocumentNumber and 

				(	

			   	((c.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

				)

		) tx 

	group by 

		tx.legalName,

		tx.identification,

		tx.address,

		tx.dateOn,

		tx.curridate,

		tx.currencyName,

		tx.sexo,

		tx.term,

		tx.period,

		tx.createdOn,

		tx.NombreProveedor,

		tx.CedulaProveedor,

		tx.DireccionProveedor,

		tx.estadoCivil,

		tx.profesion,

		tx.Domicilio,

		tx.typeFirmaCustomer,

		tx.typeFirmaProvider,

		tx.phoneNumber,

		tx.lugarTrabajo,

		tx.birthDate,

		tx.CantidadProductos,

		tx.phoneNumberTransactionMaster,

		tx.referenceClientIdentifier,

		tx.referenceClientName,

		tx.Zona; 

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_document_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_document_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prDocumentNumber` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	





	SELECT 

		cu.customerNumber,

		leg.legalName,

		leg.comercialName,

		nat.firstName,

		nat.lastName,

		l.accountNumber,

		IF(l.currencyID = currencyID_,l.limitCredit,l.limitCredit / exchangeRate_  ) AS limitCreditCordoba,

		IF(l.currencyID = currencyID_,l.balance,l.balance / exchangeRate_  ) AS balanceCordoba,

		l.interestYear,

		ci1.name as periodPay,

		l.term,

		ws1.name as statusLine,

		c.documentNumber,

		c.dateOn,

		c.amount,

		c.interes,

		c.term,

		ws2.name as statusDocument,

		amori.dateApply,

		round(amori.balanceStart,2) as balanceStart,

		round(amori.interest,2) as interest,

		round(amori.capital,2) as capital,

		round(amori.`share`,2) as share,

		round(amori.balanceEnd,2) as balanceEnd,

		round(amori.remaining,2) as remaining,

		amori.dayDelay,

		ws3.name as statusShare,

		cur.name as currencyName ,

		(CASE WHEN c.reference1 = '' THEN 'NO DETERMINADA' ELSE c.reference1 END)  as note

	FROM 

		tb_customer_credit_document c 

		inner join tb_transaction_master tmx on 

			c.companyID = tmx.companyID and 

			c.documentNumber = tmx.transactionNumber and 

			c.entityID = tmx.entityID 

		inner join tb_currency cur on 

			tmx.currencyID = cur.currencyID 

		inner join tb_customer_credit_line l on 

			c.customerCreditLineID = l.customerCreditLineID 

		inner join tb_customer cu on 

			l.entityID = cu.entityID 

		inner join tb_naturales nat on 

			cu.entityID = nat.entityID 

		inner join tb_legal leg on 

			nat.entityID = leg.entityID 

		inner join tb_entity ent on 

			leg.entityID = ent.entityID 

		inner join tb_customer_credit_amoritization amori on 

			c.customerCreditDocumentID = amori.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			l.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on 

			c.statusID = ws2.workflowStageID 

		inner join tb_workflow_stage ws3 on 

			amori.statusID = ws3.workflowStageID 

		inner join tb_catalog_item ci1 on 

			l.periodPay = ci1.catalogItemID 

	where

		c.companyID = prCompanyID and 

		c.documentNumber = prDocumentNumber and 

		(	

			   	(

						(c.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and 

						(fn_get_provider_id (prCompanyID,prUserID) != 0)

					)

					or 

					( fn_get_provider_id (prCompanyID,prUserID) = 0 )

		) 

	order by 

		amori.dateApply; 

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_exchange_rate` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_exchange_rate`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN



	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	select

		r.date as Fecha,

		'Cordoba' as Cordoba,

		round(r.ratio - currencyTargetPurchase,2) as Compra,

		round(r.ratio + currencyTargetSale,2) as Venta,

		round(r.ratio,2) as Oficial,

		'Dolar' as Dolar

	from 

		tb_exchange_rate r

	where 

		r.date between date_sub(curdate(),interval 5 day) and date_add(curdate(), interval 1 month);

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_info_proyect` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_info_proyect`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	select 

		lm.customerNumber ,

		lm.legalName,

		lm.Dates as Fecha,

		date_format(lm.Dates,'%Y-%m') as FechaPeriodo,

		lm.capital,

		lm.interest,

		lm.cuota,

		lm.remaining ,

		'Dolares' as Moneda 

	from 

		(

			select 

				c.customerNumber,

				l.legalName,

				a.dateApply as Dates,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.interest,a.interest / tmc.exchangeRate  )) as interest,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.capital,a.capital / tmc.exchangeRate  )) as capital,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.share,a.share / tmc.exchangeRate  )) as cuota,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.remaining,a.remaining / tmc.exchangeRate  )) as remaining

			from 

				tb_customer_credit_document dcd 

				inner join tb_transaction_master tmc on  

					dcd.documentNumber = tmc.transactionNumber and 

					dcd.companyID = tmc.companyID and 

					dcd.entityID = tmc.entityID and 

					tmc.isActive = 1 and 

					dcd.isActive = 1 

				inner join tb_currency cur on 

					tmc.currencyID = cur.currencyID 

				inner join tb_customer_credit_amoritization a on 

					dcd.customerCreditDocumentID = a.customerCreditDocumentID 

				inner join tb_entity e on 

					dcd.entityID = e.entityID 

				inner join tb_customer c on 

					e.entityID = c.entityID 

				inner join tb_legal l on 

					c.entityID = l.entityID 

				inner join tb_workflow_stage ws on 

					dcd.statusID = ws.workflowStageID 

			where

					a.dateApply between (now() + interval -3 month ) and  (now() + interval 3 month )

					and dcd.isActive = 1 

					and ws.vinculable = 1 

					and dcd.companyID = prCompanyID  and 

					(	

			   		((dcd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

						or 

						(fn_get_provider_id (prCompanyID,prUserID) = 0 )

					)

			group by 

				c.customerNumber,

				l.legalName,

				a.dateApply

		) lm ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_interes_periodo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_interes_periodo`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'reporte para ver los intereses pagado y capital pagado en un intervalo de fecha'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

		  

		  

	select 

		cus.customerNumber,

		leg.legalName ,

		ccc.dateOn as documentFecha,

		ccc.documentNumber,

		tm.createdOn as transactionFecha,

		tm.transactionNumber,

		t.name as transactionName,

		ROUND(IF (ccc.currencyID = currencyID_ ,ccc.balance / exchangeRate_ ,ccc.balance),2) as balance, 	

		ROUND(IF (ccc.currencyID = currencyID_ ,tmdc.capital / exchangeRate_,tmdc.capital),2) as capital,		

		ROUND(IF (ccc.currencyID = currencyID_ ,tmdc.interest / exchangeRate_,tmdc.interest),2) as interest,

		exchangeRate_ as exchangeRate	,

		exchangeRate_ - currencyTargetSale as sale,

		exchangeRate_ + currencyTargetPurchase as purchase

	from 

		tb_transaction_master tm 

		inner join tb_transaction t on 

			tm.transactionID = t.transactionID 

		inner join tb_workflow_stage ws on 

			tm.statusID = ws.workflowStageID 

		inner join tb_customer cus on 

			tm.entityID = cus.entityID 

		inner join tb_legal leg on 

			cus.entityID = leg.entityID 

		inner join tb_transaction_master_detail tmd on 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_customer_credit_document ccc on 

			tmd.componentItemID = ccc.customerCreditDocumentID 

		inner join tb_transaction_master_detail_credit tmdc on 

			tmd.transactionMasterDetailID = tmdc.transactionMasterDetailID 

	where

		tm.isActive = 1 and 

		ws.eliminable = 0 and 

		tm.transactionID in (23,24,25) and 

		cast(tm.createdOn as date) between prStartOn and prEndOn and 

		tm.companyID = prCompanyID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		tm.createdOn DESC; 

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_invoice_by_customer` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_invoice_by_customer`(IN `prCompanyID` INT, 

	IN `prTokenID` VARCHAR(50), 

	IN `prUserID` INT, 

	IN `prDateTimeStart` DATETIME,

	IN `prDateTimeFinish` DATETIME,

	IN `prCustomerEntityID` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	

		select

			cu.customerNumber as Cliente  ,

			n.firstName as Nombre,

		  cu.identification as Identificacion 

		from 

			tb_naturales n 

			inner join tb_customer cu on 

				cu.entityID = n.entityID 

		where 

			cu.entityID = prCustomerEntityID; 

				

				

		select 

			tm.transactionOn as Fecha ,

			tm.transactionNumber as Factura,

			i.itemNumber as Codigo,

			i.`name` as Producto,

			td.quantity as Cantidad ,

			td.unitaryPrice  as Precio,

			td.amount as Monto 

			

		from 

			tb_transaction_master tm 

			inner join tb_transaction_master_detail td on 

				td.transactionMasterID = tm.transactionMasterID 

			inner join tb_item i on 

				i.itemID = td.componentItemID 

		where 

			tm.isActive = 1 and 

			tm.createdOn BETWEEN prDateTimeStart and prDateTimeFinish and 

			tm.entityID = prCustomerEntityID and 

			tm.transactionID = 19  ; 

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_movement_customer` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_movement_customer`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Estado de cuenta del cliente'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;		

	declare maxID int default 0;

	declare minID int default 0; 

	declare varBalance decimal(19,9);

	declare varEntityID int default 0;

	

	

	

	 

	CREATE TEMPORARY TABLE tmp_customer_credit (

		ID INT AUTO_INCREMENT PRIMARY KEY,

		entityID INT,

		transactionNumber varchar(50),

		transactionOn datetime,

		itemNumber varchar(50),

		itemName varchar(150),

		quantity decimal(19,9),

		unitaryPrice decimal(19,9),

		amount decimal(19,9),

		balance decimal(19,9),

		nota varchar(2500)

	); 

	

	CREATE TEMPORARY TABLE tmp_customer_credit_orden (

		ID INT AUTO_INCREMENT PRIMARY KEY,

		entityID INT,

		transactionNumber varchar(50),

		transactionOn datetime,

		itemNumber varchar(50),

		itemName varchar(150),

		quantity decimal(19,9),

		unitaryPrice decimal(19,9),

		amount decimal(19,9),

		balance decimal(19,9),

		nota varchar(2500)

	); 

 



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

	SET varEntityID = (SELECT cu.entityID FROM tb_customer cu where cu.customerNumber = prCustomerNumber );	 

	

	

	INSERT INTO tmp_customer_credit (

	  entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota 

	)	

	select 

		 tm.entityID,

		 tm.transactionNumber,

		 tm.createdOn,

		 i.barCode,

		 i.`name` as nameProducto,

		 tmd.quantity ,

		 tmd.unitaryPrice,

		 tmd.amount,

		 0,

		 tm.note

	from 

		tb_transaction_master tm 

		inner join tb_transaction_master_detail tmd on 

			tm.transactionMasterID = tmd.transactionMasterID

		inner join tb_item i on 

			i.itemID = tmd.componentItemID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where

		tm.transactionID = 19  and 

		tm.isActive = 1 and 

		tmd.isActive = 1 and 

		tm.transactionCausalID in  (22,24)     and 

		ws.aplicable = 1 and 

		tm.entityID = varEntityID;

	

	

	

	INSERT INTO tmp_customer_credit (

		entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance ,

		nota

	)		

	select 

		 tm.entityID,

		 tm.transactionNumber,

		 tm.createdOn,

		 '' as barCode,

		 'Prima' nameProducto,

		 0 as quantity ,

		 0 as unitaryPrice,

		 tmdi.receiptAmount * -1,

		 0 ,

		 tm.note

	from 

		tb_transaction_master tm 

		inner join tb_transaction_master_info tmdi on 

			tm.transactionMasterID = tmdi.transactionMasterID 	

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where

		tm.transactionID = 19  and 

		tm.isActive = 1 and 	

		tm.transactionCausalID in (22,24)   and 

		ws.aplicable = 1 and 

		tm.entityID = varEntityID;

	

	

	

	INSERT INTO tmp_customer_credit (

	  entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota 

	)		

	select 

		 tm.entityID,

		 tm.transactionNumber,

		 tm.createdOn,

		 '' as barCode,

		 'Abono' as nameProducto,

		 0 as quantity ,

		 0 as unitaryPrice,

		 tmc.capital * -1 ,

		 0 ,

		 tm.note

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_detail tmd on 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_transaction_master_detail_credit tmc on 

			tmd.transactionMasterDetailID = tmc.transactionMasterDetailID 

	where 

		tm.isActive = 1 and 

		tm.transactionID = 23  and 

		ws.aplicable = 1 and 

		tm.entityID = varEntityID;

	

	insert into tmp_customer_credit_orden (

	  entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota 

	)

	select 

		entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota

	from 

		tmp_customer_credit t 

	order by 

		t.transactionOn asc ,t.ID asc ;

	

	

	

	set minID 			= (select min(i.ID) from tmp_customer_credit_orden i );

	set maxID 			= (select max(i.ID) from tmp_customer_credit_orden i );

	set varBalance 	= 0;

	

	while minID <= maxID and minID is not null  do 			

			set varBalance = varBalance + (select i.amount from tmp_customer_credit_orden i where ID = minID );			

			update tmp_customer_credit_orden set balance = varBalance where ID = minID;

			set minID   = (select min(i.ID) from tmp_customer_credit_orden i where ID > minID );

	end while;

	

	

	

	select 

		cust.customerNumber,

		nat.firstName,

		ID,		

		c.entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance ,

		nota 

	from 

		tmp_customer_credit_orden c 

		inner join tb_naturales nat on 

			c.entityID = nat.entityID 

		inner join tb_customer cust on 

			cust.entityID = nat.entityID 

	order by 

		c.ID desc ;		

		

		

	drop table tmp_customer_credit;

	drop table tmp_customer_credit_orden;

 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_sales_customer` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_sales_customer`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, 

IN prCustomerNumber VARCHAR(250))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);



	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		



	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.employerName,

		rx.tipo,

		rx.transactionOn,

		rx.createdOn,

		DAYOFMONTH(rx.createdOn) as dayOfMonth,

		rx.customerNumber,

		rx.currencyName,

		rx.note,

		rx.legalName,

		rx.zone,

		rx.itemNumber,

		rx.itemName,

		rx.itemNameLog,

		rx.phoneNumber,

		rx.Agent,

		rx.Commentary,

		rx.nameCategory,

		rx.quantity,

		rx.unitaryCost,

		rx.unitaryPrice,

		(rx.unitaryCost * rx.quantity) as cost,

		(rx.unitaryPrice * rx.quantity) as amount,

		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,

		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

		(rx.iva) as iva,

		(rx.quantity * rx.iva) as ivaTotal,

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate,

		rx.amountCommision 

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,

					tc.name as tipo,

					tm.transactionOn,

					cus.customerNumber,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,

					ci.name as zone,

					i.itemNumber,

					i.name as itemName,

					tmd.itemNameLog,

					cat.`name` as nameCategory,

					cus.phoneNumber,

					'' AS Agent,

					'' as Commentary,

					tmd.quantity as quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					cur.`name` as currencyName,

					tm.note as note,

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							tmd.unitaryCost * ifnull(tmd.skuQuantity ,0) 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 												

						else 								



						  (1/tm.exchangeRate) *   tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 				

					end  unitaryCost,					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(tmd.tax1,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(tmd.tax1,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

					end as iva ,

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(amountCommision,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(amountCommision,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)

					end  as amountCommision 	

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_customer cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_transaction_master_info tmi on 

						tm.companyID = tmi.companyID and 

						tm.transactionID = tmi.transactionID and 

						tm.transactionMasterID = tmi.transactionMasterID 

					inner join tb_catalog_item ci on 

						tmi.zoneID = ci.catalogItemID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

					left join tb_naturales nat_emp on 

						nat_emp.entityID = tm.entityIDSecondary 

				where  					

					tm.companyID = prCompanyID and 

					cus.customerNumber = prCustomerNumber and			

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 

				order by 

					tm.transactionNumber desc, tmd.transactionMasterDetailID desc					

		) rx

		order by 

					rx.transactionNumber desc;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_summary_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_summary_credit`(IN `prUserID` INT, IN `prTokenID` VARCHAR(250), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

	select 

		t.customerNumber as codigoCliente,

		t.firstName as cliente,

		t.amount as capitalInicial,

		t.balance as capitalActual,

		round(t.share_,2) as cuotaPromedio,

		t.interes as interesMensual,

		round(t.interes*12,2) as interesAnual,

		t.term as numeroCuotas,	

		round(((t.term * t.frecuenciaPagoDias) / 30 ),2) as numeroDeMeses,

		t.frecuenciaPagoDias as frecuenciaPagoEnDia,

		t.tipoAmortization as amortizacion,

		t.tipoPago as frecuenciaPago,

		t.name as moneda,	

		t.simbol as simbolo,

		t.lastDate as ultimaFecha,

		datediff(t.lastDate,curdate()) as diasParaCancelar,

		round(datediff(t.lastDate,curdate())/30,2) as mesParaCancelar ,

		round((datediff(t.lastDate,curdate())/30) / ((t.term * t.frecuenciaPagoDias) / 30 ),2) * 100 as 'mesParaCancelar%'  ,

		t.TipoCambio,

		t.Factura,

		round(t.Provisionado ,2) as Provisionado 

	from 

		(

		select 

			cu.customerNumber,

			nat.firstName,

			round(ccc.amount,2) as amount,

			round(ccc.balance,2) as balance,

			cur.name,

			cur.simbol,

			round(ccc.interes/12,2) as interes,

			ccc.term,

			ci.name as tipoAmortization,

			ci2.name as tipoPago,

			ci2.sequence as frecuenciaPagoDias,

			(select avg(cca.`share`) from tb_customer_credit_amoritization cca inner join tb_workflow_stage ws2 on cca.statusID = ws2.workflowStageID  where cca.customerCreditDocumentID = ccc.customerCreditDocumentID and cca.remaining <> 0 ) as share_,

			(select max(cca.dateApply) from tb_customer_credit_amoritization cca inner join tb_workflow_stage ws2 on cca.statusID = ws2.workflowStageID  where cca.customerCreditDocumentID = ccc.customerCreditDocumentID )  as lastDate,

			ccc.exchangeRate as TipoCambio,

			ccc.documentNumber as Factura,

			ccc.balanceProvicioned as Provisionado

		from 

			tb_customer_credit_document ccc 

			inner join tb_customer_credit_line ccl on 

				ccc.customerCreditLineID = ccl.customerCreditLineID 

			inner join tb_catalog_item ci on 

				ccl.typeAmortization = ci.catalogItemID 

			inner join tb_catalog_item ci2 on 

				ccl.periodPay = ci2.catalogItemID 

			inner join tb_customer cu on 

				ccc.entityID = cu.entityID 

			inner join tb_naturales nat on 

				cu.entityID = nat.entityID 

			inner join tb_currency cur on 

				ccc.currencyID = cur.currencyID 

			inner join tb_workflow_stage ws on 

				ccc.statusID = ws.workflowStageID  

		where

			ccc.isActive = 1 and  

			ws.vinculable = 1 and 

			(

					((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

			)

		) t 

	order by 

		t.lastDate asc; 

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_get_report_upload_buro` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_get_report_upload_buro`(IN `prUserID` INT, IN `prTokenID` VARCHAR(250), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Reporte para reportal al buro de credito'
BEGIN

	DECLARE itemNotReportable VARCHAR(250);

  DECLARE varAbreviature VARCHAR(50);

	

	

		  

	SET varAbreviature = IFNULL((SELECT c.abreviature FROM tb_company c where c.companyID = prCompanyID ),'');

	

	CREATE TEMPORARY TABLE tb_tmp_customer_buro (

			companyID INT,

			customerCreditDocumentID INT,			

			entityID INT,

			TIPO_DE_ENTIDAD VARCHAR(50),

			NUMERO_CORRELATIVO VARCHAR(50),

			FECHA_DE_REPORTE VARCHAR(50),

			DEPARTAMENTO VARCHAR(50),

			NUMERO_DE_CEDULA_O_RUC VARCHAR(50),

			NOMBRE_DE_PERSONA VARCHAR(250),

			TIPO_DE_CREDITO VARCHAR(250),

			FECHA_DE_DESEMBOLSO VARCHAR(50),

			TIPO_DE_OBLIGACION VARCHAR(250),

			MONTO_AUTORIZADO DECIMAL(19,2),

			PLAZO INT,

			FRECUENCIA_DE_PAGO VARCHAR(50),

			SALDO_DEUDA DECIMAL(19,2),

			ESTADO VARCHAR(50),

      MONTO_VENCIDO DECIMAL(19,2),

			ANTIGUEDAD_DE_MORA INT,

			TIPO_DE_GARANTIA VARCHAR(50),

			FORMA_DE_RECUPERACION VARCHAR(50),

			NUMERO_DE_CREDITO VARCHAR(50),

			VALOR_DE_LA_CUOTA DECIMAL(19,2)

	);

	

	   

        

   CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_NOT_REPORT_TO_SINRIESGO",itemNotReportable);

   	

	

	INSERT INTO tb_tmp_customer_buro(

		companyID ,customerCreditDocumentID ,entityID ,TIPO_DE_ENTIDAD ,NUMERO_CORRELATIVO ,FECHA_DE_REPORTE ,DEPARTAMENTO ,

		NUMERO_DE_CEDULA_O_RUC ,NOMBRE_DE_PERSONA ,TIPO_DE_CREDITO ,FECHA_DE_DESEMBOLSO ,TIPO_DE_OBLIGACION ,

		MONTO_AUTORIZADO ,NUMERO_DE_CREDITO ,TIPO_DE_GARANTIA ,

		PLAZO ,

		FRECUENCIA_DE_PAGO ,

		SALDO_DEUDA ,

		ESTADO ,

		MONTO_VENCIDO ,

		ANTIGUEDAD_DE_MORA ,

		FORMA_DE_RECUPERACION ,

		VALOR_DE_LA_CUOTA )

	SELECT 

		    `cc`.`companyID` AS `companyID`,

        `cc`.`customerCreditDocumentID` AS `customerCreditDocumentID`,

        `cc`.`entityID` AS `entityID`,

        '99' AS `TIPO DE ENTIDAD`,  

        '552' AS `NUMERO CORRELATIVO`, 

        DATE_FORMAT(NOW(), '%d/%m/%Y') AS `FECHA DE REPORTE`,

        '08' AS `DEPARTAMENTO`, 

        REPLACE(`c`.`identification`, '-', '') AS `NUMERO DE CEDULA O RUC`,

        CONCAT(`nat`.`firstName`, ' ', `nat`.`lastName`) AS `NOMBRE DE PERSONA`,

        RIGHT(CONCAT('0000', `tipocredito`.`sequence`),2) AS `TIPO DE CREDITO`, 

        DATE_FORMAT(`cc`.`dateOn`, '%d/%m/%Y') AS `FECHA DE DESEMBOLSO`, 

        RIGHT(CONCAT('0000', `obli`.`sequence`),2) AS `TIPO DE OBLIGACION`,  

				

				

        ROUND(

					(FN_CALCULATE_EXCHANGE_RATE(2, CAST(NOW() AS DATE),`cc`.`currencyID`,1,`cc`.`amount`) * `p`.`ratioDesembolso`),2

			  ) AS `MONTO AUTORIZADO`,

				

				

        CONCAT(varAbreviature,`cc`.`documentNumber`) AS `NUMERO DE CREDITO`,

        RIGHT(CONCAT('0000', `tipogarantia`.`sequence`),2) AS `TIPO DE GARANTIA`,          

        

				

				

				CASE

						WHEN (`cc`.`periodPay` = 190) THEN ( `cc`.`term` ) /*mensual a meses */

						WHEN (`cc`.`periodPay` = 188) THEN ( round(`cc`.`term` / 4) ) /*semanal a meses */

						WHEN (`cc`.`periodPay` = 189) THEN ( round(`cc`.`term` / 2) ) /*quincenal a meses*/

						WHEN (`cc`.`periodPay` = 531) THEN ( round(`cc`.`term` / 30) ) /*diario a meses*/

						ELSE 0

				END as  `PLAZO`,

         

			

        (CASE

            WHEN (`cc`.`periodPay` = 190)  THEN '05'  

            WHEN (`cc`.`periodPay` = 188)  THEN '07'  

            WHEN (`cc`.`periodPay` = 189)  THEN '06'  

						WHEN (`cc`.`periodPay` = 531)  THEN '08'  

						WHEN (`cc`.`periodPay` = 2322) THEN '06'  

            ELSE 0

        END) AS `FRECUENCIA DE PAGO`,

        

        

        (CASE

            WHEN (`cc`.`statusID` = 82) THEN 0

            ELSE ROUND((FN_CALCULATE_EXCHANGE_RATE(2,

                            CAST(NOW() AS DATE),

                            `cc`.`currencyID`,

                            1,

                            `cc`.`balance`) * `p`.`ratioBalance`),

                    2)

        END) AS `SALDO DEUDA`,

        

		  

        (CASE

        		WHEN `estadosinriesgo`.`sequence` = 1  THEN   

				   CASE      			

		            WHEN

		                ((`ws`.`workflowStageID` NOT IN (93 , 92, 82))

		                    AND (CAST(NOW() AS DATE) > (SELECT 

		                        MAX(`xl`.`dateApply`)

		                    FROM

		                        `tb_customer_credit_amoritization` `xl`

		                    WHERE

		                        (`xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`))))

		            THEN

		                '02' 

		            WHEN

		                ((`ws`.`workflowStageID` NOT IN (93 , 92, 82)  )

		                    AND (CAST(NOW() AS DATE) > (SELECT 

		                        MIN(`xl`.`dateApply`)

		                    FROM

		                        `tb_customer_credit_amoritization` `xl`

		                    WHERE

		                        ((`xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

		                            AND (`xl`.`remaining` > 0)))))  

		            THEN

		                '02' 

		            WHEN (`ws`.`workflowStageID` = 83) THEN 'N/D'

		            WHEN (`ws`.`workflowStageID` = 92) THEN '08' 

		            WHEN (`ws`.`workflowStageID` = 82) THEN '03' 

		            WHEN (`ws`.`workflowStageID` = 77) THEN '01' 

		            ELSE RIGHT(CONCAT('0000', `estadosinriesgo`.`sequence`),2)

	            END 

	         ELSE 

	         	RIGHT(CONCAT('0000', `estadosinriesgo`.`sequence`),2)

        END) AS `ESTADO`,

        

		  

        ROUND(((

		  				  SELECT 

                        IFNULL(ROUND((CASE

                                                WHEN (`cc`.`typeAmortization` = 196)   THEN

                                                   AVG(FN_CALCULATE_EXCHANGE_RATE(2,CAST(NOW() AS DATE),`cc`.`currencyID`,1,`cx`.`balanceStart`))

                                                ELSE 

																	SUM(FN_CALCULATE_EXCHANGE_RATE(2,CAST(NOW() AS DATE),`cc`.`currencyID`,1,`cx`.`capital`)) 

                                            END),

                                            2),

                                    0) 

                    FROM

                        `tb_customer_credit_amoritization` `cx`

                    WHERE

                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

                            AND (`cx`.`isActive` = 1)

                            AND (`cx`.`remaining` > 0)

                            AND (`cx`.`statusID` = 78)

                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))

					) *  `p`.`ratioBalanceExpired` ),

         2) AS `MONTO VENCIDO`,

      	

			

        (SELECT 

                IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),

                            0)

            FROM

                `tb_customer_credit_amoritization` `cx`

            WHERE

                ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

                    AND (`cx`.`isActive` = 1)

                    AND (`cx`.`remaining` > 0)

                    AND (`cx`.`statusID` = 78)

                    AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))

			) AS `ANTIGUEDAD DE MORA`,

			

			

      (CASE

        		WHEN `recuperacion`.`sequence` = 1 THEN

        			CASE 

		            WHEN (`ws`.`workflowStageID` = 83) THEN '01' 

		            WHEN (`ws`.`workflowStageID` = 92) THEN '08' 

		            WHEN (`ws`.`workflowStageID` = 82) THEN '01' 

		            WHEN

		                ((`ws`.`workflowStageID` = 77)

		                    AND ((SELECT 

		                        IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),

		                                    0)

		                    FROM

		                        `tb_customer_credit_amoritization` `cx`

		                    WHERE

		                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

		                            AND (`cx`.`isActive` = 1)

		                            AND (`cx`.`remaining` > 0)

		                            AND (`cx`.`statusID` = 78)

		                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) BETWEEN 30 AND 59))

		            THEN

		                '03' 

		            WHEN

		                ((`ws`.`workflowStageID` = 77)

		                    AND ((SELECT 

		                        IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),

		                                    0)

		                    FROM

		                        `tb_customer_credit_amoritization` `cx`

		                    WHERE

		                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

		                            AND (`cx`.`isActive` = 1)

		                            AND (`cx`.`remaining` > 0)

		                            AND (`cx`.`statusID` = 78)

		                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) > 60))

		            THEN

		                '04' 

		            WHEN (`ws`.`workflowStageID` = 77) THEN '01' 

		            ELSE RIGHT(CONCAT('0000', `recuperacion`.`sequence`),2)

		         END 

		      ELSE 

		      	RIGHT(CONCAT('0000', `recuperacion`.`sequence`),2)

        END) AS `FORMA DE RECUPERACION`,

			  0 AS `VALOR DE LA CUOTA`

    FROM

        ((((((((((((`tb_customer_credit_document` `cc`

        JOIN `tb_currency` `cur` ON ((`cc`.`currencyID` = `cur`.`currencyID`)))

        JOIN `tb_workflow_stage` `ws` ON ((`cc`.`statusID` = `ws`.`workflowStageID`)))

        JOIN `tb_catalog_item` `ci` ON ((`cc`.`typeAmortization` = `ci`.`catalogItemID`)))

        JOIN `tb_customer_credit_document_entity_related` `p` ON ((`cc`.`customerCreditDocumentID` = `p`.`customerCreditDocumentID`)))

        JOIN `tb_catalog_item` `obli` ON ((`obli`.`catalogItemID` = `p`.`type`)))

        JOIN `tb_catalog_item` `tipocredito` ON ((`tipocredito`.`catalogItemID` = `p`.`typeCredit`)))

        JOIN `tb_catalog_item` `tipogarantia` ON ((`tipogarantia`.`catalogItemID` = `p`.`typeGarantia`)))

        JOIN `tb_catalog_item` `frepago` ON ((`frepago`.`catalogItemID` = `cc`.`periodPay`)))

        JOIN `tb_catalog_item` `recuperacion` ON ((`recuperacion`.`catalogItemID` = `p`.`typeRecuperation`)))

        JOIN `tb_catalog_item` `estadosinriesgo` ON ((`estadosinriesgo`.`catalogItemID` = `p`.`statusCredit`)))

        JOIN `tb_naturales` `nat` ON ((`p`.`entityID` = `nat`.`entityID`)))

        JOIN `tb_customer` `c` ON ((`nat`.`entityID` = `c`.`entityID`)))

    WHERE

        (

			  	(  `cc`.`isActive` = 1)

			  	AND (cc.reportSinRiesgo = 1 ) 

	        AND (`cc`.`entityID` <> 309)

	        AND (

						REPLACE(`c`.`identification`, '-', '') NOT IN (

						'0000000000000B', 

						'0000000000000A',

						'0000000000000C', 

						'0000000000000P',

						'0000000000000K',

						'2811803890004R',

						'2912906610000G',

						'2911206850000P',

						'0000000000000T'

						)

					)

	         AND (`ws`.`workflowStageID` <> 83)

			) 

    ORDER BY 

	 		CONCAT(`nat`.`firstName`, ' ', `nat`.`lastName`) ;

	 		

	

	 /*cuota segun la frecuencia de pago, si la frecuencia de pago es semanal, la cuota el monto se debe ver reflejado semanal*/

	update tb_tmp_customer_buro set VALOR_DE_LA_CUOTA = 

			(

			CASE 

				WHEN  tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' /*SEMANAL*/ THEN 

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 4) 

				WHEN  tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' /*MENSUAL*/ THEN 

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 1) 

				WHEN  tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' /*QUINCENAL*/ THEN 

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 2) 

				ELSE /*DIARIO*/

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 30 ) 

			END

			);

			

	

	

   update tb_tmp_customer_buro,tb_catalog_item set 

		tb_tmp_customer_buro.ESTADO = 

		case 

			when

				( 

					case 

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' then  

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 7 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 15 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 30 * tb_tmp_customer_buro.PLAZO DAY)  

					end

				) < CURDATE() THEN 

					'02' 

			else

					'01' 

		end ,

		tb_tmp_customer_buro.ANTIGUEDAD_DE_MORA = 

		case 

			when

				( 

					case 

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' then  

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 7 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 15 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 30 * tb_tmp_customer_buro.PLAZO DAY)  

					end

				) < CURDATE() THEN 

					DATEDIFF(

						CURDATE(),

						case 

							WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' then  

								DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 7 * tb_tmp_customer_buro.PLAZO  DAY)  

							WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' then 

								DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 15 * tb_tmp_customer_buro.PLAZO  DAY)  

							WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' then 

								DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 30 * tb_tmp_customer_buro.PLAZO DAY)  

						end 

					)   

			else

					0

		end

   where

   	tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = tb_catalog_item.sequence and 

   	tb_catalog_item.catalogID = 51 and 

   	tb_tmp_customer_buro.ESTADO = '01'  and 

		tb_tmp_customer_buro.FORMA_DE_RECUPERACION = '03'  and 

		tb_tmp_customer_buro.entityID = 0;

	

	 

	

	update tb_tmp_customer_buro set tb_tmp_customer_buro.FORMA_DE_RECUPERACION = '03'  

	where 

		tb_tmp_customer_buro.ESTADO = '02'  ; 	



	

	update tb_tmp_customer_buro set 

			tb_tmp_customer_buro.MONTO_VENCIDO = tb_tmp_customer_buro.SALDO_DEUDA 

	where

		tb_tmp_customer_buro.ESTADO = '02'  ; 	

		 

	

	update tb_tmp_customer_buro set tb_tmp_customer_buro.FORMA_DE_RECUPERACION = '01'  

	where 

		tb_tmp_customer_buro.ESTADO = '01'  and tb_tmp_customer_buro.ANTIGUEDAD_DE_MORA = 0;  	

					

	

   update tb_tmp_customer_buro set  ESTADO = '03' 

   where  

   	SALDO_DEUDA = 0;   	

		

	 

   select 

		`i`.`TIPO_DE_ENTIDAD`,

		`i`.`NUMERO_CORRELATIVO`, 

		`i`.`FECHA_DE_REPORTE`,

		`i`.`DEPARTAMENTO`,

		`i`.`NUMERO_DE_CEDULA_O_RUC`,

		`i`.`NOMBRE_DE_PERSONA`,

		`i`.`TIPO_DE_CREDITO`,

		`i`.`FECHA_DE_DESEMBOLSO`,

		`i`.`TIPO_DE_OBLIGACION`,

		`i`.`MONTO_AUTORIZADO`,

		`i`.`PLAZO`,

		`i`.`FRECUENCIA_DE_PAGO`,

		`i`.`SALDO_DEUDA`,

		`i`.`ESTADO`,

		`i`.`MONTO_VENCIDO`,

		`i`.`ANTIGUEDAD_DE_MORA`,

		`i`.`TIPO_DE_GARANTIA`,

		`i`.`FORMA_DE_RECUPERACION`,

		`i`.`NUMERO_DE_CREDITO`,

		`i`.`VALOR_DE_LA_CUOTA`	

	from 

		tb_tmp_customer_buro i;

   

   DROP TABLE tb_tmp_customer_buro;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_proc_expandinvoice` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_proc_expandinvoice`(IN `prCompanyID` INT, IN `prDocumentNumber` VARCHAR(50))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para aumentar de plazo los creditos que son tipo americano y estan proximo a vencer'
BEGIN

             

  	

	

	

	

  DECLARE varStatusRegistrado INT DEFAULT 78;

  DECLARE varStatusCancelado INT DEFAULT 81;

  DECLARE varLastDate DATE; 

  DECLARE varRefDate DATE;

  DECLARE varDateMin DATE;

  DECLARE varDateMax DATE;

  DECLARE varCustomerCreditDocumentID INT;

  

    SET varCustomerCreditDocumentID = (select c.customerCreditDocumentID from tb_customer_credit_document c where c.documentNumber =  prDocumentNumber  limit 1);

     



  

    SET varLastDate    		   = (select c.dateApply from tb_customer_credit_amoritization c where 

			c.customerCreditDocumentID = varCustomerCreditDocumentID and c.statusID = varStatusRegistrado order by c.dateApply desc limit 1);

			

    SET varRefDate 				= (select c.dateApply from tb_customer_credit_amoritization c where 

			c.customerCreditDocumentID = varCustomerCreditDocumentID and c.statusID = varStatusRegistrado and c.dateApply <  

			varLastDate order by c.dateApply desc limit 1);   

  



    CASE 

			WHEN varLastDate IS NOT NULL and varRefDate IS NOT NULL THEN 

						SET varDateMin 		= DATE_ADD(varRefDate, INTERVAL 1 MONTH);

						SET varDateMax 		= DATE_ADD(varRefDate, INTERVAL 12 MONTH);		  

		  

		  		  update tb_customer_credit_amoritization set 

						dateApply = varDateMax where customerCreditDocumentID = varCustomerCreditDocumentID and dateApply = varLastDate and statusID = varStatusRegistrado;

		  

		  		  WHILE varDateMin < varDateMax DO 

		  

		  	  		  	  CASE 

											WHEN NOT EXISTS (

												SELECT customerCreditDocumentID FROM tb_customer_credit_amoritization 

												WHERE customerCreditDocumentID = varCustomerCreditDocumentID and statusID = varStatusRegistrado and dateApply = varDateMin

											) 

											THEN 

		  	  

													INSERT INTO tb_customer_credit_amoritization (

															customerCreditDocumentID,dateApply,balanceStart,interest,capital,share,balanceEnd,

															remaining,shareCapital,dayDelay,note,statusID,isActive

														)

													SELECT 

														customerCreditDocumentID,varDateMin,balanceStart,interest,capital,share,

														balanceEnd,share,shareCapital, 0 ,'',varStatusRegistrado,1 

													FROM 

														tb_customer_credit_amoritization 

													WHERE

														customerCreditDocumentID = varCustomerCreditDocumentID 

														and statusID = varStatusRegistrado and dateApply = varRefDate limit 1;

		

										END CASE ; 

			  	  	    

		  	  

									SET varDateMin = DATE_ADD(varDateMin, INTERVAL 1 MONTH);

						END WHILE;

						SELECT 'EXITO!!!' as mensaje;

						

		ELSE

        SELECT CONCAT( 'NOT PROCESS!!! varCustomerCreditDocumentID: ' , cast(varCustomerCreditDocumentID as char )) as mensaje

				union		

        SELECT CONCAT( 'SELECT * from tb_customer_credit_amoritization c where c.customerCreditDocumentID = ',

				cast(varCustomerCreditDocumentID as char ),' order by c.dateApply ' ) as mensaje;  		

		END CASE;

  



  

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_proc_suprime_share` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_proc_suprime_share`(IN `prCompanyID` INT, IN `prDocumentNumber` VARCHAR(50), IN `prCuotaModificada` DATETIME, IN `prAumentaMesDeGracia` BIT, IN `prCambioInteresDelMes` DECIMAL(19,5))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Anula una cuota'
BEGIN

	

	

	

	

	

	

	

   DECLARE varPeriodoPago INT DEFAULT 0;

   DECLARE varDocumentID INT DEFAULT 0;

   DECLARE varCuotaPrimera INT DEFAULT 0; 

   DECLARE varCuotaSegunda INT DEFAULT 0;

   DECLARE varStatusCancelado INT DEFAULT 81; 

   DECLARE varStatusRegistrado INT DEFAULT 78; 

   

	SET varDocumentID  = (select c.customerCreditDocumentID from tb_customer_credit_document c where c.companyID = prCompanyID and c.documentNumber = prDocumentNumber limit 1);

	SET varPeriodoPago = (select c.periodPay from tb_customer_credit_document c where c.companyID = prCompanyID and c.documentNumber = prDocumentNumber limit 1);

	



	

	IF prAumentaMesDeGracia = 1 THEN

	

		 

		insert into tb_customer_credit_amoritization (customerCreditDocumentID,dateApply,balanceStart,interest,capital,share,balanceEnd,remaining,shareCapital,dayDelay,note,statusID,isActive)

		select 

			customerCreditDocumentID,dateApply,

			balanceStart,0 as interest,0 as capital, 0 as share,balanceEnd,0 as remaining,0 as shareCapital,

			0 as dayDelay,'se realizo prorroga de fecha' as note, varStatusCancelado ,isActive 

		from 

			tb_customer_credit_amoritization c  

		where 

			c.customerCreditDocumentID = varDocumentID and dateApply = prCuotaModificada;

		

		

		set varCuotaPrimera = (select min(c.creditAmortizationID) from tb_customer_credit_amoritization c where c.customerCreditDocumentID = varDocumentID and dateApply = prCuotaModificada limit 1);

		set varCuotaSegunda = (select max(c.creditAmortizationID) from tb_customer_credit_amoritization c where c.customerCreditDocumentID = varDocumentID and dateApply = prCuotaModificada limit 1);

		

		

		update tb_customer_credit_amoritization set 

			dateApply = (

								case 

									when varPeriodoPago = 190 then 

								  			date_add(dateApply , interval 1 month) 

								  	when varPeriodoPago = 188 then 

											date_add(dateApply , interval 7 day) 

										else 

								  			dateApply 

								end

							)

		where 

			customerCreditDocumentID = varDocumentID and 

			dateApply >= prCuotaModificada and 

			creditAmortizationID <> varCuotaSegunda ;  

						

		select 'EXITO!!!' as mensaje;  

		

	END IF;

	 

	

	IF prCambioInteresDelMes <> 0 THEN 

	

		update tb_customer_credit_amoritization set 

			interest = prCambioInteresDelMes  , share = prCambioInteresDelMes , remaining = prCambioInteresDelMes ,

			note = 'se realizo cambio de interes',

			statusID = varStatusRegistrado 

		where

			customerCreditDocumentID = varDocumentID 

			and dateApply = prCuotaModificada;

			

			

	END IF;

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxc_pro_add_solidario_to_credit_sin_riesgo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxc_pro_add_solidario_to_credit_sin_riesgo`(IN `prCompanyID` INT, IN `prInvoiceNumber` VARCHAR(50), IN `prCustomerNumber` VARCHAR(50), IN `prRatioDesembolso` DECIMAL(10,4), IN `prRatioBalance` DECIMAL(10,4), IN `prRatioBalanceExpired` DECIMAL(10,4), IN `prRatioShare` DECIMAL(10,4), IN `prTipoCredito` INT, IN `prTipoObligacion` INT, IN `prFrecuenciaPago` INT, IN `prEstadoCredito` INT, IN `prRecuperacion` INT, IN `prGarantia` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN

 

DECLARE VAR_customerCreditDocumentID INT DEFAULT 0;

DECLARE VAR_entiryIDSolidario INT DEFAULT 0;

DECLARE fiadorID INT DEFAULT 390;

DECLARE propietarioID INT DEFAULT 389;



SELECT c.customerCreditDocumentID INTO VAR_customerCreditDocumentID FROM tb_customer_credit_document c where c.documentNumber = prInvoiceNumber LIMIT 1;	

SELECT C.entityID INTO VAR_entiryIDSolidario FROM 	tb_customer C WHERE 	C.customerNumber = prCustomerNumber LIMIT 1;



CASE WHEN VAR_entiryIDSolidario != 0 THEN 

	CASE WHEN NOT EXISTS(

				SELECT * 

				FROM tb_customer_credit_document_entity_related c 

				where 

					c.customerCreditDocumentID = VAR_customerCreditDocumentID 

					and c.entityID = VAR_entiryIDSolidario 

	)  

	THEN 

				INSERT INTO 

				tb_customer_credit_document_entity_related(

							customerCreditDocumentID, entityID, `type`, typeCredit, 

							statusCredit, typeGarantia, typeRecuperation, 

							ratioDesembolso, ratioBalance, ratioBalanceExpired, 

							ratioShare, createdOn, createdBy, createdIn, createdAt, isActive 

					)

				SELECT 

						customerCreditDocumentID, VAR_entiryIDSolidario as entityID, 

						fiadorID as types, typeCredit, statusCredit, typeGarantia, 

						typeRecuperation, ratioDesembolso, ratioBalance, ratioBalanceExpired, 

						ratioShare, createdOn, createdBy, createdIn, createdAt, isActive 

				FROM 

						tb_customer_credit_document_entity_related c 

				where 

						c.customerCreditDocumentID = VAR_customerCreditDocumentID and type = propietarioID LIMIT 1;	

	

	END  CASE ;

END CASE  ;







UPDATE tb_customer_credit_document_entity_related set 

	typeCredit = prTipoCredito,

	statusCredit = prEstadoCredito, 	

	typeGarantia = prGarantia, 

	typeRecuperation = prRecuperacion,

	ratioDesembolso = (prRatioDesembolso ) , 

	ratioBalance = (prRatioBalance ) ,  

	ratioBalanceExpired = (prRatioBalanceExpired ) , 

	ratioShare = (prRatioShare )

where 

	customerCreditDocumentID = VAR_customerCreditDocumentID;





SELECT 

	`NOMBRE DE PERSONA`,    

    `TIPO DE OBLIGACION`, 

    `MONTO AUTORIZADO`, 

    PLAZO, 

    `FRECUENCIA DE PAGO`, 

    `SALDO DEUDA`, 

    ESTADO, 

    `MONTO VENCIDO`, 

    `ANTIGUEDAD DE MORA`,     

    `FORMA DE RECUPERACION`, 

    `VALOR DE LA CUOTA`,

    C.`TIPO DE GARANTIA` 

from 

	vw_sin_riesgo_reporte_creditos C 

WHERE 

	C.customerCreditDocumentID = VAR_customerCreditDocumentID order by C.`TIPO DE OBLIGACION`;    

	

select c.catalogItemID,c.catalogID,c.name as TipoCredito,c.sequence from tb_catalog_item c where c.catalogID = 50 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as TipoObligacion,c.sequence from tb_catalog_item c where c.catalogID = 49 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as FrecuenciaPago,c.sequence from tb_catalog_item c where c.catalogID = 51 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as EstadoCredito,c.sequence from tb_catalog_item c where c.catalogID = 52 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as FormaRecuperacion,c.sequence from tb_catalog_item c where c.catalogID = 54 order by c.sequence; 

select c.catalogItemID,c.catalogID,c.name as TipoGarantia,c.sequence from tb_catalog_item c where c.catalogID = 53 order by c.sequence;







END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxp_get_report_expenses_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxp_get_report_expenses_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE , IN prTipoExpense INT , IN prCategoryExpenses INT, IN prClassExpenses INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Obtener detalle de gastos '
BEGIN

	

	 

	select 

		 tm.transactionID,

		 tm.transactionMasterID,

		 tm.companyID,

		 tm.transactionNumber,

		 tm.createdOn,

		 tm.amount,

		 tm.tax1 as Iva,

		 tm.tax2 as Total,

		 tm.note,

		 tm.reference1,

		 tm.reference2 ,

		 tm.reference4 as ruc,

		 tm.currencyID,

		 tm.currencyID2,

		 tm.exchangeRate,

		 tm.areaID,

		 tm.priorityID  ,

		 ifnull(clas.display,'ND') as Clasificacion,

		 ci.name as Tipo,

		 ci2.name as Categoria,

		 '' as CodigoReglon,

		 tm.reference3 as Proveedor ,

		 br.`name` as sucursal 

	from 

		tb_transaction_master tm 

		inner join tb_branch br on 	

			br.branchID = tm.branchID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_public_catalog_detail ci on 

			ci.publicCatalogDetailID = tm.priorityID 

		inner join tb_public_catalog_detail ci2 on 

			ci2.publicCatalogDetailID = tm.areaID 

		left JOIN tb_catalog_item clas on 

			tm.classID = clas.catalogItemID

	where

		tm.transactionID = 38  and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.transactionOn between prStartOn and prEndOn and 

		(

			(

				(tm.areaID = prCategoryExpenses and prCategoryExpenses != 0) or 

				(prCategoryExpenses = 0)

			)  

			and 

			(

				(tm.priorityID = prTipoExpense and prTipoExpense != 0)  or 

				(prTipoExpense = 0 )

			)

			AND

			(

				(tm.classID=prClassExpenses and prClassExpenses !=0 ) OR

				(prClassExpenses=0)

			)

	  );

	

 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxp_get_report_expenses_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxp_get_report_expenses_summary`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,  IN `prStartOn` DATE, IN `prEndOn` DATE , IN prTipoExpense INT , IN prCategoryExpenses INT , IN prClassExpenses INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Obtener gastos resumidos por categoria '
BEGIN

	SET SESSION group_concat_max_len = 10000;

	

	SET @sqlT = NULL;

	SELECT

	GROUP_CONCAT(DISTINCT CONCAT(

		'

				SUM(

					CASE WHEN 

						concat(uz.transactionOn,"-01") = "', REPLACE(u.startOn,' 00:00:00',''), '" THEN 

							uz.amount 

					ELSE 

							0 

					END

				) 

				AS "',    DATE_FORMAT(STR_TO_DATE( REPLACE(u.startOn,' 00:00:00',''), '%Y-%m-%d'), '%d/%m/%Y')  ,'" ' )

	)

	INTO @sqlT

	FROM tb_accounting_cycle u 

	WHERE 

		u.startOn between concat(YEAR(prStartOn) ,"-", RIGHT(concat("00",MONTH(prStartOn)),2),"-01") and prEndOn;



	

		

	SET @sqlT = 

	CONCAT(

		'SELECT 

				Tipo, 

				', @sqlT, 

		'from 

				(

						select 

							 month(tm.transactionOn) as monthOnlyNumber,

							 concat(year(tm.transactionOn) ,"-", right(concat("00",month(tm.transactionOn)),2)   ) as transactionOn,

							 ci.`name` as Tipo,		 

							 tm.tax2	  as amount

						from 

							tb_transaction_master tm 

							inner join tb_workflow_stage ws on 

								ws.workflowStageID = tm.statusID 

							inner join tb_public_catalog_detail ci on 

								ci.publicCatalogDetailID = tm.priorityID 		

							left join  tb_catalog_item clas on 

								tm.classID = clas.catalogItemID		

						where

							tm.transactionID = 38  and 

							tm.isActive = 1 and 

							ws.aplicable = 1 and 

							tm.transactionOn between "',prStartOn,'" and "',prEndOn,'" and 

							(

								(

									(tm.areaID = ',prCategoryExpenses,' and ',prCategoryExpenses,' != 0) or 

									(',prCategoryExpenses,' = 0)

								)  

								and 

								(

									(tm.priorityID = ',prTipoExpense,' and ',prTipoExpense,' != 0)  or 

									(',prTipoExpense,' = 0 )

								)

								and 

								(

										(tm.classID =',prClassExpenses,' and ',prClassExpenses,' != 0 ) OR

										(',prClassExpenses, ' = 0)

								)

							)

				)  uz 

			group by  				

				uz.Tipo; 

	');

		

	

	PREPARE stmt FROM @sqlT;

	EXECUTE stmt;

	DEALLOCATE PREPARE stmt;

 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxp_get_report_expenses_summary_pivot` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxp_get_report_expenses_summary_pivot`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,  IN `prStartOn` DATE, IN `prEndOn` DATE)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Obtener gastos pivot por fecha'
BEGIN

	

	

	 

	select 

		 tm.transactionID,

		 tm.transactionMasterID,

		 tm.companyID,

		 tm.transactionNumber,

		 tm.createdOn,

		 tm.amount,

		 tm.note,

		 tm.currencyID,

		 tm.currencyID2,

		 tm.exchangeRate,

		 tm.areaID,

		 tm.priorityID  ,

		 ci.`name` as Tipo,

		 ci2.`name` as Categoria 

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_catalog_item ci on 

			ci.catalogItemID = tm.priorityID 

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where

		tm.transactionID = 38  and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.createdOn between prStartOn and prEndOn;

 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_cxp_get_report_purchase_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_cxp_get_report_purchase_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT,

 IN prEntityIDProvider INT,

 IN prItmeID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de Compra'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.transactionOn,

		rx.createdOn,

		rx.providerNumber,

		rx.currencyName,

		rx.note,

		rx.legalName,

		rx.itemNumber,

		rx.itemName,

		rx.Agent,

		rx.Commentary,

		rx.nameCategory,

		rx.quantity,		

		rx.unitaryCost,

		rx.unitaryPrice,

		(rx.unitaryCost * rx.quantity) as cost,

		(rx.unitaryPrice * rx.quantity) as amount,

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate,

		rx.expirationDate 		

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					tm.transactionOn,

					cus.providerNumber ,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,

					i.itemNumber,

					i.name as itemName,

					cat.`name` as nameCategory,

					'' AS Agent,

					'' as Commentary,

					tmd.quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					tmd.expirationDate,

					cur.`name` as currencyName,

					tm.note as note,

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							tmd.unitaryCost

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost														

						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost							

					end  unitaryCost 

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_provider cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

				where  					

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 					

					ws.aplicable = 1 and 

					(

						(tm.entityID = prEntityIDProvider AND prEntityIDProvider != 0)  

						OR

						(prEntityIDProvider = 0)

					)

					and 

					(

						(tmd.componentItemID = prItmeID AND prItmeID != 0)

						or 

						(prItmeID = 0 )

					)

					and 

					(

						prInventoryCategoryID = 0 

						or 

						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

					) and 

					(

						prWarehouse = 0 

						or 

						(

							prWarehouse != 0 and 

							tm.sourceWarehouseID =  prWarehouse 

					  )

					)

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

					

		) rx;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_calculate_kardex_new_input` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_calculate_kardex_new_input`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento que se utilizara para calcular el costo de los Item cuando se registra una nueva entrada de mercaderia'
BEGIN

	declare varBranchID int;

	declare varSign int;

	declare varTransactionOn datetime;

	declare varWarehouseID INT; 

	DECLARE varTiposCosto VARCHAR(50);

		

	CALL pr_core_get_parameter_value(prCompanyID,'INVENTORY_TYPE_COST',varTiposCosto);

		

	select tm.createdAt,sign,transactionOn,tm.targetWarehouseID into varBranchID,varSign,varTransactionOn,varWarehouseID 

	from 

		tb_transaction_master tm 

	where 

		tm.companyID = prCompanyID and tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID limit 1;

		

	insert into tb_item_warehouse (companyID,branchID,itemID,warehouseID,quantity,cost,quantityMax,quantityMin)

	select 

		k.companyID,k.branchID,k.itemID,k.warehouseID,0,0,0,0

	from

		tb_kardex k 

	where

		k.itemID not in (

				select 

					l.itemID 

				from 

					tb_item_warehouse l 

				where  

					l.companyID = prCompanyID and 

					l.warehouseID = varWarehouseID  

		) and 

		k.companyID = prCompanyID and 

		k.transactionID = prTransactionID and 

		k.transactionMasterID = prTransactionMasterID;

		

		

	insert into tb_kardex (

		companyID,branchID,transactionID,transactionMasterID,transactionDetailID,warehouseID,

		itemID,kardexCode,kardexDate,sign,movementOn,

		transactionQuantity,

		transactionCost,

	  quantityInWarehouseCurrent,quantityInCurrent 

	)

	select 

		tm.companyID,varBranchID,tm.transactionID,tm.transactionMasterID,tm.transactionMasterDetailID,

		tm.inventoryWarehouseTargetID,tm.componentItemID,0,current_timestamp(),varSign,

		varTransactionOn,		

		IF(tm.transactionID = 20  ,tm.skuQuantityBySku,tm.quantity) as tm_quantity, 

		tm.unitaryCost,

		ifnull(iw.quantity,0),i.quantity  

	from 

		tb_transaction_master_detail tm 

		inner join tb_item i on 

				tm.componentItemID = i.itemID 		

		left join tb_item_warehouse iw on 

			  iw.itemID = i.itemID and 

				iw.warehouseID = tm.inventoryWarehouseTargetID  

	where 

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

	

	

	

	update tb_kardex,tb_item  set 

		tb_kardex.oldQuantity = IFNULL(tb_item.quantity,0), 

		tb_kardex.oldCost = IFNULL(tb_item.cost,0)

	where

		tb_item.companyID = tb_kardex.companyID and 

		tb_item.itemID = tb_kardex.itemID and 		

		tb_kardex.companyID = prCompanyID and 

		tb_kardex.transactionID = prTransactionID and 

		tb_kardex.transactionMasterID = prTransactionMasterID ;



	update tb_item_warehouse , tb_kardex set 

		  tb_kardex.oldQuantityWarehouse = IFNULL(tb_item_warehouse.quantity,0),

      tb_kardex.oldCostWarehouse = 0 

	where 

		tb_item_warehouse.companyID = tb_kardex.companyID and 

		tb_item_warehouse.itemID = tb_kardex.itemID and 

		tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 

		tb_kardex.companyID = prCompanyID and 

		tb_kardex.transactionID = prTransactionID and 

		tb_kardex.transactionMasterID = prTransactionMasterID ; 

	

	

	update tb_kardex set 

		newQuantity           = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantity,0)) ,

		newQuantityWarehouse  = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantityWarehouse,0)) 

	where 

		companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;

	

	

	IF varTiposCosto = 'ULTIMO COSTO' THEN  

			update tb_kardex set 

				newCost               = IFNULL(transactionCost,0), 

				newCostWarehouse      = 0

			where 

				companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;

	END IF;

	

	

	IF varTiposCosto = 'PROMEDIO COSTO' THEN 

			update tb_kardex set 

				newCost               = 

					(

						(IFNULL(transactionQuantity,0) * IFNULL(transactionCost,0)) + 

						(IFNULL(oldQuantity,0) * IFNULL(oldCost,0))

					) / IFNULL(newQuantity,0) ,

				newCostWarehouse      = 0

			where 

				companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;

	END IF;

	

	IF varTiposCosto = 'PONDERADO COSTO' THEN 

			update tb_kardex set 

				newCost               = 				

				  (((transactionQuantity) / newQuantity)  * transactionCost )					

					+					

					(((oldQuantity) / newQuantity) * oldCost ) , 

				newCostWarehouse      = 0

			where 

				companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;

	END IF;

	

	

	

	update tb_item , tb_kardex set 

		tb_item.quantity = tb_kardex.newQuantity,

		tb_item.cost = tb_kardex.newCost,

		tb_item.dateLastUse = NOW()

	where

		tb_item.companyID = tb_kardex.companyID and 

		tb_item.itemID = tb_kardex.itemID and 

		tb_kardex.companyID = prCompanyID and 

		tb_kardex.transactionID = prTransactionID and 

		tb_kardex.transactionMasterID = prTransactionMasterID ;



	update tb_item_warehouse , tb_kardex set 

		tb_item_warehouse.quantity = tb_kardex.newQuantityWarehouse,

		tb_item_warehouse.cost = tb_kardex.newCostWarehouse

	where

		tb_item_warehouse.companyID = tb_kardex.companyID and 

		tb_item_warehouse.itemID = tb_kardex.itemID and 

		tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 

		tb_kardex.companyID = prCompanyID and 

		tb_kardex.transactionID = prTransactionID and 

		tb_kardex.transactionMasterID = prTransactionMasterID ;

			

	update 

		tb_transaction_master set isApplied = 1 	

	where 

		companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID; 

		

	

	

	INSERT INTO tb_item_warehouse_expired (warehouseID,itemID,companyID,quantity,lote,dateExpired)

	SELECT 

			c.inventoryWarehouseTargetID ,

			c.componentItemID,

			c.companyID,		

			0 as quantity, 

			REPLACE( UPPER(c.lote),' ','') as lote,

			c.expirationDate

	FROM 

			tb_transaction_master_detail c 

	WHERE	

			c.transactionMasterID = prTransactionMasterID  and 

			c.transactionID = prTransactionID and 		

			c.companyID = prCompanyID and 

			c.componentID = 33 and  

			c.isActive = 1 and 

			c.componentItemID not in (

				select 

					u.itemID 

				from 

					tb_item_warehouse_expired u 

				where 

					u.companyID = c.companyID and 

					u.itemID = c.componentItemID and 

					u.warehouseID = c.inventoryWarehouseTargetID and 				

					u.dateExpired = IFNULL(c.expirationDate,'0000-00-00 00:00:00') 

			);

		

		

		update tb_item_warehouse_expired , tb_transaction_master_detail set 

			tb_item_warehouse_expired.quantity = 

			tb_item_warehouse_expired.quantity + (varSign * tb_transaction_master_detail.quantity) 

		where

			tb_item_warehouse_expired.itemID = tb_transaction_master_detail.componentItemID and 

			tb_item_warehouse_expired.companyID = tb_transaction_master_detail.companyID and 

			tb_item_warehouse_expired.warehouseID = tb_transaction_master_detail.inventoryWarehouseTargetID and 			 			

			tb_item_warehouse_expired.dateExpired = IFNULL(

							tb_transaction_master_detail.expirationDate,

							'0000-00-00 00:00:00'

			)  and 

			tb_transaction_master_detail.companyID = prCompanyID and 

			tb_transaction_master_detail.transactionID = prTransactionID and 

			tb_transaction_master_detail.transactionMasterID = prTransactionMasterID;

			

			

			

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_calculate_kardex_new_output` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_calculate_kardex_new_output`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento que se utiliza para restar el inventario al momento de registrar una salida de inventario'
BEGIN

	 declare varBranchID int;

	 declare varSign int;

	 declare varTransactionOn datetime;

	 declare varWarehouseID int;

	 DECLARE varTiposCosto VARCHAR(50);

	 declare varTransactionIDFactura int default 19;

	 declare varWhileItemMin int default 0;

	 declare varWhileItemMax int default 0;

	 declare varWhileItemQuantity decimal(19,5) DEFAULT 0;

	 

	 declare varWhileFechaVencimientoMin date default 0;

	 declare varWhileFechaVencimientoMax date default 0;

	 declare varWhileFechaVencimientoQuantity decimal(19,5) DEFAULT 0;

	 declare varWhileFechaVencimientoContinue int default 1;

	  

    DECLARE EXIT HANDLER FOR SQLEXCEPTION 

		BEGIN

				ROLLBACK;

				RESIGNAL;

		END;



		

    START TRANSACTION;

		CALL pr_core_get_parameter_value(prCompanyID,'INVENTORY_TYPE_COST',varTiposCosto);

		

		

		select tm.createdAt,sign,transactionOn,tm.sourceWarehouseID 

		into varBranchID,varSign,varTransactionOn,varWarehouseID 

		from tb_transaction_master tm 

		where 

			tm.companyID = prCompanyID and 

			tm.transactionID = prTransactionID and tm.transactionMasterID = prTransactionMasterID limit 1;

	

	

				

		insert into tb_item_warehouse (companyID,branchID,itemID,warehouseID,quantity,cost,quantityMax,quantityMin)

		select 

			k.companyID,k.branchID,k.itemID,k.warehouseID,0,0,0,0

		from

			tb_kardex k  

		where

			k.itemID not in (

					select 

						l.itemID 

					from 

						tb_item_warehouse l 

					where  

						l.companyID = prCompanyID and 

						l.warehouseID = varWarehouseID  

			) and 

			k.companyID = prCompanyID and 

			k.transactionID = prTransactionID and 

			k.transactionMasterID = prTransactionMasterID;

			

			

		insert into tb_kardex (

		companyID,branchID,transactionID,transactionMasterID,

		transactionDetailID,warehouseID,itemID,kardexCode,kardexDate,sign,movementOn,transactionQuantity,transactionCost,

		quantityInWarehouseCurrent,quantityInCurrent

		)

		select 

			tm.companyID,varBranchID,tm.transactionID,tm.transactionMasterID,

			tm.transactionMasterDetailID,tm.inventoryWarehouseSourceID,tm.componentItemID,

			0,current_timestamp(),varSign,varTransactionOn,

			IF(tm.transactionID = 19  ,tm.skuQuantityBySku,tm.quantity) as tm_quantity,

			tm.unitaryCost ,

			iw.quantity,i.quantity 

		from 

			tb_transaction_master_detail tm 

			inner join tb_item i on 

				tm.componentItemID = i.itemID 

			inner join tb_item_warehouse iw on 

				iw.itemID = i.itemID and 

				iw.warehouseID = varWarehouseID 

		where 

			tm.companyID = prCompanyID and 

			tm.transactionID = prTransactionID and tm.transactionMasterID = prTransactionMasterID and tm.isActive = 1;

		



		

		

		

		update tb_kardex,tb_item  set 

			tb_kardex.oldQuantity = IFNULL(tb_item.quantity,0), 

			tb_kardex.oldCost     = IFNULL(tb_item.cost,0)

		where

			tb_item.companyID = tb_kardex.companyID and 

			tb_item.itemID = tb_kardex.itemID and 

			tb_kardex.companyID = prCompanyID and 

			tb_kardex.transactionID = prTransactionID and 

			tb_kardex.transactionMasterID = prTransactionMasterID ;

	

		

		update tb_item_warehouse , tb_kardex set 

					tb_kardex.oldQuantityWarehouse 	= IFNULL(tb_item_warehouse.quantity,0),

	        tb_kardex.oldCostWarehouse 			= IFNULL(tb_item_warehouse.cost,0)

		where

			tb_item_warehouse.companyID = tb_kardex.companyID and 

			tb_item_warehouse.itemID = tb_kardex.itemID and 

			tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 

			tb_kardex.companyID = prCompanyID and 

			tb_kardex.transactionID = prTransactionID and 

			tb_kardex.transactionMasterID = prTransactionMasterID ;

		

		

		

		update tb_kardex set 

			newQuantity           = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantity,0)) ,

			newQuantityWarehouse  = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantityWarehouse,0)) 

		where 

			companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;

		

		update tb_kardex set 

			newCost           = oldCost,

			newCostWarehouse  = oldCostWarehouse

		where 

			companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;	

		

		

		update tb_item , tb_kardex set 

			tb_item.quantity = tb_kardex.newQuantity,

			tb_item.cost = tb_kardex.newCost,

			tb_item.dateLastUse = NOW()

		where

			tb_item.companyID = tb_kardex.companyID and 

			tb_item.itemID = tb_kardex.itemID and 

			tb_kardex.companyID = prCompanyID and 

			tb_kardex.transactionID = prTransactionID and 

			tb_kardex.transactionMasterID = prTransactionMasterID ;

	

	  

		update tb_item_warehouse , tb_kardex set 

			tb_item_warehouse.quantity = tb_kardex.newQuantityWarehouse,

			tb_item_warehouse.cost = tb_kardex.newCostWarehouse

		where

			tb_item_warehouse.companyID = tb_kardex.companyID and 

			tb_item_warehouse.itemID = tb_kardex.itemID and 

			tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 

			tb_kardex.companyID = prCompanyID and 

			tb_kardex.transactionID = prTransactionID and 

			tb_kardex.transactionMasterID = prTransactionMasterID ;

				

				

				

		update tb_transaction_master set 

			isApplied = 1 	

		where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID; 

	

	

	  

		if varTransactionIDFactura = prTransactionID  then 

				update tb_item , tb_kardex set 

					tb_item.quantityInvoice = IFNULL(tb_item.quantityInvoice,0) + tb_kardex.transactionQuantity 

				where

					tb_item.companyID = tb_kardex.companyID and 

					tb_item.itemID = tb_kardex.itemID and 

					tb_kardex.companyID = prCompanyID and 

					tb_kardex.transactionID = prTransactionID and 

					tb_kardex.transactionMasterID = prTransactionMasterID ;

		end if;

		

		

		

		if varTransactionIDFactura != prTransactionID  then 		

					update tb_item_warehouse_expired , tb_transaction_master_detail set 

						  tb_item_warehouse_expired.quantity = 

							tb_item_warehouse_expired.quantity + (varSign * tb_transaction_master_detail.quantity) 

					where

						tb_item_warehouse_expired.itemID = tb_transaction_master_detail.componentItemID and 

						tb_item_warehouse_expired.companyID = tb_transaction_master_detail.companyID and 

						tb_item_warehouse_expired.warehouseID = tb_transaction_master_detail.inventoryWarehouseSourceID and 

						tb_item_warehouse_expired.dateExpired = IFNULL(

							tb_transaction_master_detail.expirationDate,

							'0000-00-00 00:00:00'

						)  and 

						tb_transaction_master_detail.companyID = prCompanyID and 

						tb_transaction_master_detail.transactionID = prTransactionID and 

						tb_transaction_master_detail.transactionMasterID = prTransactionMasterID and 

						tb_transaction_master_detail.isActive = 1;

		else 

				

				set varWhileItemMin = (

						select min(u.componentItemID) 

						from tb_transaction_master_detail u where u.isActive = 1 and u.transactionMasterID = prTransactionMasterID

				);

				

				set varWhileItemMax = (

						select max(u.componentItemID) 

						from tb_transaction_master_detail u where u.isActive = 1 and u.transactionMasterID = prTransactionMasterID

				);

				

				while varWhileItemMin <= varWhileItemMax do 

							

							set varWhileItemQuantity = (

								select u.skuQuantityBySku from tb_transaction_master_detail u where u.isActive = 1 and 

								u.componentItemID = varWhileItemMin and 

								u.transactionMasterID = prTransactionMasterID

							);

							

							

							set varWhileFechaVencimientoMin = (

								select min(u.dateExpired) from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 

								and u.warehouseID = varWarehouseID  

								and u.quantity > 0

							);

							

							

							set varWhileFechaVencimientoMax = (

								select max(u.dateExpired) from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 

								and u.warehouseID = varWarehouseID  

								and u.quantity > 0

							);

							

							

							set varWhileFechaVencimientoContinue = 1;

							

							

							while varWhileFechaVencimientoMin <= varWhileFechaVencimientoMax and varWhileFechaVencimientoContinue = 1 do 

							

										

										set varWhileFechaVencimientoQuantity = (

											select u.quantity from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 

											and u.dateExpired = varWhileFechaVencimientoMin and 

											u.quantity > 0 and 

											u.warehouseID = varWarehouseID limit 1 

										);

										

										

										if varWhileFechaVencimientoQuantity >= varWhileItemQuantity  then 											

												update tb_item_warehouse_expired set 

													quantity = quantity - varWhileItemQuantity

												where

													dateExpired = varWhileFechaVencimientoMin and 

													itemID = varWhileItemMin and 

													warehouseID = varWarehouseID;

													

												set varWhileItemQuantity = 0;

												

										elseif varWhileFechaVencimientoQuantity < varWhileItemQuantity  then 

												update tb_item_warehouse_expired set 

													quantity = 0

												where

													dateExpired = varWhileFechaVencimientoMin and 

													itemID = varWhileItemMin and 

													warehouseID = varWarehouseID;

													

												set varWhileItemQuantity = varWhileItemQuantity - varWhileFechaVencimientoQuantity;

										end if;

										

										

										if varWhileItemQuantity <= 0 then 

											set varWhileFechaVencimientoContinue = 0;

										end if;

										

										

										

										set varWhileFechaVencimientoMin = (

											select min(u.dateExpired) from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 

											and u.warehouseID = varWarehouseID  

											and u.quantity > 0 and 

											u.dateExpired > varWhileFechaVencimientoMin 

										);

							end while;

							

							

							set varWhileItemMin = (

									select min(u.componentItemID) 

									from tb_transaction_master_detail u 

									where 

										u.isActive = 1 and u.transactionMasterID = prTransactionMasterID

										and u.componentItemID > varWhileItemMin 

							);

				end while; 

				

		end if;

		

		COMMIT;

  

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_input_by_ajuste` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_create_transaction_input_by_ajuste`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 

	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, OUT `prResult` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para crer entradas de meraderia'
LBL_PROCEDURE:

BEGIN

	

			DECLARE varTransactionID int default  12; 		

			DECLARE varComponentID int default  34; 			

			DECLARE varComponentItemID int default  33; 	

			DECLARE varComponentAccountID int default 4; 	

				

			

			DECLARE varTransactionMasterID BIGINT default 0; 			

			declare varNote varchar(150) default 'Entrada por ajuste de inventario';			

			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

			DECLARE varTransactionCausalID int default  0;

			DECLARE varSignID int default  0;

			DECLARE varCurrencyIDFunction int default  0;

			DECLARE varCurrencyIDExternal int default  0;

			DECLARE varCurrencyIDReport int default  0;

			DECLARE varCurrencyTmporal varchar(50) default '';

			DECLARE varStatusIDTransactionInit int default  0;

			DECLARE varStatusIDTransactionFinish int default  0;

			DECLARE varWarehouseTempora varchar(50) default  '';

			DECLARE varWarehouseID int default  0;

			DECLARE varFechaStart datetime;

			DECLARE varFechaEnd datetime;

			DECLARE varWorkflowStageCycleClosed varchar(50) default '';

			DECLARE varAmount decimal(19,4) default 0;			

			DECLARE varCountDetail INT DEFAULT 0;

			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;

			

			

		

			

			CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_WAREHOUSE_DEFAULT",varWarehouseTempora);

			SET varWarehouseID 		= (

							SELECT 

								c.targetWarehouseID 

							FROM 

								tb_transaction_master  c 

							where 

								c.transactionMasterID = prTransactionMasterID  

							);					

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);

			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);

			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);

			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

				

			

			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			

			SET varTransactionCausalID = (

				select t.transactionCausalID from tb_transaction_causal t where t.transactionID = varTransactionID and t.isDefault = 1 limit 1); 

				

			set varExchangeRate = (

					SELECT c.ratio 

					from tb_exchange_rate c 

					where 

						c.currencyID = varCurrencyIDFunction and 

						c.targetCurrencyID = varCurrencyIDExternal and 

						c.date =  CURDATE()

			);

			

			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otherinput',prBranchID,0,varTransactionNumber);		

			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otherinput","statusID",varStatusIDTransactionInit );	

			

			

			SET varStatusIDTransactionFinish = (

				select 

					ws.workflowStageID 

				from 

					tb_subelement e 

					inner join tb_element el on 

						e.elementID = el.elementID

					inner join tb_workflow_stage ws on 

						ws.workflowID = e.workflowID 

				where 

					e.`name` = 'statusID' and 

					el.`name` = 'tb_transaction_master_otherinput' and 

					ws.aplicable = 1 

			);

			

			

			INSERT INTO tb_transaction_master(

				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,

				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,

				targetWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive) 

			VALUES 

			(

				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,

				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,

				varWarehouseID,prLoginID,prBranchID,NOW(),'::01',1

			);

			

			SET varTransactionMasterID = LAST_INSERT_ID();	

			

			

			DELETE FROM  

				tb_transaction_master_detail_temp 

			WHERE 

				companyID = prCompanyID and 

				transactionID = varTransactionID;

				

			

			

			

			

			INSERT INTO tb_transaction_master_detail_temp (

				transactionMasterDetailID,

 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,

 				amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,

 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

 				quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID				

 			)

 			SELECT 

				c.transactionMasterDetailID,

 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i.itemID,0,

 				0,c.unitaryPrice * (c.quantity - (iw.quantity)), (c.quantity - (iw.quantity))  ,0,c.unitaryPrice, c.unitaryCost,c.unitaryPrice,

 				0,0,1,0,0,

 				0,0,varWarehouseID

 			from 

				tb_transaction_master tm 

				inner join  tb_transaction_master_detail c  on 

					c.transactionMasterID = tm.transactionMasterID

				inner join tb_item i on 

					c.componentItemID  = i.itemID

				inner join tb_item_warehouse iw on 

					iw.itemID = i.itemID and 

					iw.warehouseID = varWarehouseID 

			where 

				c.isActive = 1 and 

				c.transactionID = prTransactionID and 

				c.transactionMasterID = prTransactionMasterID and 

				iw.quantity < c.quantity;

				

				

			

			INSERT INTO tb_transaction_master_detail (

 				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,

 				cost, quantity

 			)

 			select 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,		

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,

 				sum(cost) as cost, sum(quantity) as quantity

			from 

				tb_transaction_master_detail_temp u

			where 

				u.transactionID = varTransactionID 

			group by 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID; 

				

			

			

			SET varAmount 			= (select sum(u.cost) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);

			SET varAmount 			= (case when varAmount is null then 0 else varAmount end);

			SET varCountDetail 	= (select count(u.transactionMasterDetailID) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID and u.isActive = 1 );

			

 			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount where transactionMasterID = varTransactionMasterID;

			

			

			IF varCountDetail = 0 THEN

				UPDATE tb_transaction_master  set isActive = 0 where transactionMasterID = varTransactionMasterID;

				SET varTransactionMasterID = 0;		

			END IF;



			

			

			SET prResult = 1;			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_input_by_ajuste',

				0,'Success',CURRENT_TIMESTAMP());

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_input_by_ajuste_transactionID',

				0,varTransactionID,CURRENT_TIMESTAMP());

				

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_input_by_ajuste_transactionMasterID',

				0,varTransactionMasterID,CURRENT_TIMESTAMP());

		

		

		

		



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_otherinput_by_production` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_create_transaction_otherinput_by_production`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 

	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, 	

	IN prWarehouseID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para crer entradas de meraderia por produccion'
LBL_PROCEDURE:

BEGIN

	

			DECLARE varTransactionID int default  12  ; 		

			DECLARE varComponentID int default  34; 			

			DECLARE varComponentItemID int default  33; 	

			DECLARE varComponentAccountID int default 4; 	

		  DECLARE minItemID int default 0;

			DECLARE maxItemID int default 0;

			DECLARE quantityWhile  DECIMAL(18,8) default 0;

			DECLARE costWhile      DECIMAL(18,8) default 0;

			

			DECLARE varTransactionMasterID BIGINT default 0; 			

			declare varNote varchar(150) default 'Entrada por produccion de inventario';			

			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

			DECLARE varTransactionCausalID int default  78;

			DECLARE varSignID int default  0;

			DECLARE varCurrencyIDFunction int default  0;

			DECLARE varCurrencyIDExternal int default  0;

			DECLARE varCurrencyIDReport int default  0;

			DECLARE varCurrencyTmporal varchar(50) default '';

			DECLARE varStatusIDTransactionInit int default  0;

			DECLARE varStatusIDTransactionFinish int default  0;

			DECLARE varWarehouseTempora varchar(50) default  '';			

			DECLARE varFechaStart datetime;

			DECLARE varFechaEnd datetime;

			DECLARE varWorkflowStageCycleClosed varchar(50) default '';

			DECLARE varAmount decimal(19,4) default 0;			

			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;

			DECLARE varTransactionNumberOrigen varchar(50) default '';

			

		

			

			

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);

			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);

			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);

			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);						

			

			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			

			SET varTransactionNumberOrigen  = (

						select k.transactionNumber 

						from tb_transaction_master k 

						where k.transactionMasterID = prTransactionMasterID 

			);

				

			set varExchangeRate = (

					SELECT c.ratio 

					from tb_exchange_rate c 

					where 

						c.currencyID = varCurrencyIDFunction and 

						c.targetCurrencyID = varCurrencyIDExternal and 

						c.date =  CURDATE()

			);

			

			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otherinput',prBranchID,0,varTransactionNumber);		

			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otherinput","statusID",varStatusIDTransactionInit );	

			

			

			SET varStatusIDTransactionFinish = (

				select 

					ws.workflowStageID 

				from 

					tb_subelement e 

					inner join tb_element el on 

						e.elementID = el.elementID

					inner join tb_workflow_stage ws on 

						ws.workflowID = e.workflowID 

				where 

					e.`name` = 'statusID' and 

					el.`name` = 'tb_transaction_master_otherinput' and 

					ws.aplicable = 1 

			);

			

			

			INSERT INTO tb_transaction_master(

				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,

				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,

				targetWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive,reference1) 

			VALUES 

			(

				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,

				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,

				prWarehouseID,prLoginID,prBranchID,NOW(),'::01',1,varTransactionNumberOrigen

			);

			

			SET varTransactionMasterID = LAST_INSERT_ID();	

			

			

			DELETE FROM  

				tb_transaction_master_detail_temp 

			WHERE 

				companyID = prCompanyID and 

				transactionID = varTransactionID;

				

			

			

			

			SET minItemID = (

				select 

						min(c.componentItemID)

				from 

					tb_transaction_master_detail c 

				where 

					c.transactionMasterID = prTransactionMasterID and 

					c.inventoryWarehouseTargetID = prWarehouseID and 

					c.isActive = 1 

			);

			

			SET maxItemID = (

				select 

						min(c.componentItemID)

				from 

					tb_transaction_master_detail c 

				where 

					c.transactionMasterID = prTransactionMasterID and 

					c.inventoryWarehouseTargetID = prWarehouseID and 

					c.isActive = 1 

			);

			

			

			

			while minItemID <= maxItemID and minItemID is not null do 				

					set quantityWhile  = 0 ;

					set costWhile 		 = 0 ;

					set quantityWhile  = (

																select sum(u.quantity) from tb_transaction_master_detail u 

																where 

																		u.transactionMasterID = prTransactionMasterID AND 

																		u.isActive = 1 and 

																		u.inventoryWarehouseTargetID = prWarehouseID AND 

																		u.componentItemID = minItemID 

															);

															

					set costWhile  = (

																select 

																			sum(

																				u.quantity * u.unitaryCost

																			) 

																from 

																	  tb_transaction_master_detail u 

																where 

																		u.transactionMasterID = prTransactionMasterID AND 

																		u.isActive = 1 and 

																		u.inventoryWarehouseSourceID is not null and 

																		u.inventoryWarehouseSourceID > 0 and 

																		u.skuCatalogItemID = minItemID 

															);

					

					

					SET quantityWhile = IFNULL(quantityWhile,0);

					SET costWhile 		= IFNULL(costWhile,0);					

					

					INSERT INTO tb_transaction_master_detail_temp (

						transactionMasterDetailID,

						companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,

						amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,

						catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

						quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID				

					)

					VALUES (

						0,

						prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,minItemID,0,

						0  ,

						quantityWhile * costWhile   , 

						quantityWhile   ,

						0,

						costWhile  , 

						costWhile  ,

						costWhile  ,

						0,0,1,0,0,

						0,0,prWarehouseID

					);

					

			

					

					set minItemID 		= (

																	select 

																			min(c.componentItemID)

																	from 

																		tb_transaction_master_detail c 

																	where 

																		c.transactionMasterID = prTransactionMasterID and 

																		c.inventoryWarehouseTargetID = prWarehouseID and 

																		c.isActive = 1 and 

																		c.componentItemID > minItemID

															);

			end while;

			

			

			

				

				

			

			INSERT INTO tb_transaction_master_detail (

 				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,

 				cost, quantity

 			)

 			select 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,		

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,

 				sum(cost) as cost, sum(quantity) as quantity

			from 

				tb_transaction_master_detail_temp u

			where 

				u.transactionID = varTransactionID 

			group by 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID; 

				

			

			

			SET varAmount = (select sum(u.cost) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);

			SET varAmount = (case when varAmount is null then 0 else varAmount end);

 			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount where transactionMasterID = varTransactionMasterID;

			

			

			UPDATE tb_transaction_master set 

							reference1 = 	CASE 

															WHEN IFNULL(reference1,'') = '' THEN 

																	'|'

															ELSE 

																	''

														END 

			WHERE transactionMasterID = prTransactionMasterID;			

			UPDATE tb_transaction_master set 

							reference1 = 							

										CONCAT(

																	reference1, 

																	'|',

																	varTransactionNumber

									)

			WHERE transactionMasterID = prTransactionMasterID;

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_otherinput_by_production',

				0,'Success',CURRENT_TIMESTAMP());

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_otherinput_by_production_transactionID',

				0,varTransactionID,CURRENT_TIMESTAMP());

				

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_otherinput_by_production_transactionMasterID',

				0,varTransactionMasterID,CURRENT_TIMESTAMP());

		

		

		



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_otheroutput_by_production` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_create_transaction_otheroutput_by_production`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 

	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, 	

	IN prWarehouseID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para crer salida de meraderia'
LBL_PROCEDURE:

BEGIN

	

			DECLARE varTransactionID int default  8  ; 		

			DECLARE varComponentID int default  35; 			

			DECLARE varComponentItemID int default  33; 	

			DECLARE varComponentAccountID int default 4; 	

			

			DECLARE varTransactionMasterID BIGINT default 0; 			

			declare varNote varchar(150) default 'Salida por produccion de inventario';			

			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

			DECLARE varTransactionCausalID int default  77;

			DECLARE varSignID int default  0;

			DECLARE varCurrencyIDFunction int default  0;

			DECLARE varCurrencyIDExternal int default  0;

			DECLARE varCurrencyIDReport int default  0;

			DECLARE varCurrencyTmporal varchar(50) default '';

			DECLARE varStatusIDTransactionInit int default  0;

			DECLARE varStatusIDTransactionFinish int default  0;

			DECLARE varWarehouseTempora varchar(50) default  '';

			DECLARE varFechaStart datetime;

			DECLARE varFechaEnd datetime;

			DECLARE varWorkflowStageCycleClosed varchar(50) default '';

			DECLARE varAmount decimal(19,4) default 0;			

			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;

			DECLARE varTransactionNumberOrigen varchar(50) default '';

			

			

		

		

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);

			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);

			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);

			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);						

			

			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			

			SET varTransactionNumberOrigen  = (

						select k.transactionNumber 

						from tb_transaction_master k 

						where k.transactionMasterID = prTransactionMasterID 

			);

				

			set varExchangeRate = (

					SELECT c.ratio 

					from tb_exchange_rate c 

					where 

						c.currencyID = varCurrencyIDFunction and 

						c.targetCurrencyID = varCurrencyIDExternal and 

						c.date =  CURDATE()

			);

			

			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otheroutput',prBranchID,0,varTransactionNumber);		

			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otheroutput","statusID",varStatusIDTransactionInit );	

			

			

			SET varStatusIDTransactionFinish = (

				select 

					ws.workflowStageID 

				from 

					tb_subelement e 

					inner join tb_element el on 

						e.elementID = el.elementID

					inner join tb_workflow_stage ws on 

						ws.workflowID = e.workflowID 

				where 

					e.`name` = 'statusID' and 

					el.`name` = 'tb_transaction_master_otheroutput' and 

					ws.aplicable = 1 

			);

			

			

			INSERT INTO tb_transaction_master(

				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,

				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,

				sourceWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive,reference1,notificationID) 

			VALUES 

			(

				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,

				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,

				prWarehouseID,prLoginID,prBranchID,NOW(),'::01',1,varTransactionNumberOrigen,1 

			);

			

			SET varTransactionMasterID = LAST_INSERT_ID();	

			

			

			DELETE FROM  

				tb_transaction_master_detail_temp 

			WHERE 

				companyID = prCompanyID and 

				transactionID = varTransactionID;

			

			

			

			INSERT INTO tb_transaction_master_detail_temp (

				transactionMasterDetailID,

 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,

 				amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,

 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

 				quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID				

 			)

 			SELECT 

				c.transactionMasterDetailID,

 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i.itemID,0,

 				0,

				i.cost * c.quantity, 

				c.quantity  ,

				0,

				i.cost, 

				i.cost,

				i.cost,

 				0,0,1,0,0,

 				0,0,prWarehouseID

 			from 

				tb_transaction_master tm 

				inner join  tb_transaction_master_detail c  on 

					c.transactionMasterID = tm.transactionMasterID

				inner join tb_item i on 

					c.componentItemID  = i.itemID

			where 

				c.isActive = 1 and 

				c.transactionID = prTransactionID and 

				c.transactionMasterID = prTransactionMasterID and 

				IFNULL(c.inventoryWarehouseSourceID ,0) = prWarehouseID and 

				c.quantity > 0 ; 

			

			

			INSERT INTO tb_transaction_master_detail (

 				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,

 				cost, quantity

 			)

 			select 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,		

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,

 				sum(cost) as cost, sum(quantity) as quantity

			from 

				tb_transaction_master_detail_temp u

			where 

				u.transactionID = varTransactionID 

			group by 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID; 

			

			

			

			SET varAmount = (select sum(u.cost) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);

			SET varAmount = (case when varAmount is null then 0 else varAmount end);

 			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount where transactionMasterID = varTransactionMasterID;

			

			

			

			UPDATE tb_transaction_master set 

							reference1 = 	CASE 

															WHEN IFNULL(reference1,'') = '' THEN 

																	'|'

															ELSE 

																	''

														END 

			WHERE transactionMasterID = prTransactionMasterID;			

			UPDATE tb_transaction_master set 

							reference1 = 							

										CONCAT(

																	reference1, 

																	'|',

																	varTransactionNumber

									)

			WHERE transactionMasterID = prTransactionMasterID;

			



			

			

				

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_otheroutput_by_production',

				0,'Success',CURRENT_TIMESTAMP());

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_otheroutput_by_production_transactionID',

				0,varTransactionID,CURRENT_TIMESTAMP());

				

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_otheroutput_by_production_transactionMasterID',

				0,varTransactionMasterID,CURRENT_TIMESTAMP());

		  

		

		



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_output_by_ajuste` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_create_transaction_output_by_ajuste`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 

	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, OUT `prResult` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para crear facturacion de meraderia'
LBL_PROCEDURE:

BEGIN

	

			DECLARE varTransactionID int default  8; 		

			DECLARE varComponentID int default  48; 			

			DECLARE varComponentItemID int default  33; 	

			DECLARE varComponentAccountID int default 4; 	

				

			DECLARE varEntityClientDefault INT default 13;

			DECLARE varTransactionMasterID BIGINT default 0; 			

			declare varNote varchar(150) default 'Salida por ajuste de inventario';			

			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

			DECLARE varTransactionCausalID int default  0;

			DECLARE varSignID int default  0;

			DECLARE varCurrencyIDFunction int default  0;

			DECLARE varCurrencyIDExternal int default  0;

			DECLARE varCurrencyIDReport int default  0;

			DECLARE varCurrencyTmporal varchar(50) default '';

			DECLARE varStatusIDTransactionInit int default  0;

			DECLARE varStatusIDTransactionFinish int default  0;

			DECLARE varWarehouseTempora varchar(50) default  '';

			DECLARE varWarehouseID int default  0;

			DECLARE varFechaStart datetime;

			DECLARE varFechaEnd datetime;

			DECLARE varWorkflowStageCycleClosed varchar(50) default '';

			DECLARE varAmount decimal(19,4) default 0;

			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;

			

			

		

			

			CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_WAREHOUSE_DEFAULT",varWarehouseTempora);

			SET varWarehouseID 		= (

							SELECT 

								c.targetWarehouseID 

							FROM 

								tb_transaction_master  c 

							where 

								c.transactionMasterID = prTransactionMasterID  

							);		

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);

			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);

			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);

			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

				

			

			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			

			SET varTransactionCausalID = (

				select t.transactionCausalID from 

				tb_transaction_causal t where 

				t.transactionID = varTransactionID and t.isDefault = 1 limit 1

			); 

			

			set varExchangeRate = (

					SELECT c.ratio 

					from tb_exchange_rate c 

					where 

						c.currencyID = varCurrencyIDFunction and 

						c.targetCurrencyID = varCurrencyIDExternal and 

						c.date =  CURDATE()

			);

			

			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otheroutput',prBranchID,0,varTransactionNumber);		

			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otheroutput","statusID",varStatusIDTransactionInit );	

			

			

			SET varStatusIDTransactionFinish = (

				select 

					ws.workflowStageID 

				from 

					tb_subelement e 

					inner join tb_element el on 

						e.elementID = el.elementID

					inner join tb_workflow_stage ws on 

						ws.workflowID = e.workflowID 

				where 

					e.`name` = 'statusID' and 

					el.`name` = 'tb_transaction_master_otheroutput' and 

					ws.aplicable = 1 

			);

			

			

			INSERT INTO tb_transaction_master(

				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,

				entityID,transactionOn2,reference1,reference2,reference4,periodPay,nextVisit,reference3,

				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,

				sourceWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive) 

			VALUES 

			(

				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,

				varEntityClientDefault,CURDATE(),293  , 1 , 0 , 190  , '0000-00-00 00:00:00' , 'N/D',

				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDExternal,varExchangeRate,varStatusIDTransactionFinish,0,1,0,

				varWarehouseID,prLoginID,prBranchID,NOW(),'::01',1

			);

			

			SET varTransactionMasterID = LAST_INSERT_ID();	

			

			

			DELETE FROM  

				tb_transaction_master_detail_temp 

			WHERE 

				companyID = prCompanyID and 

				transactionID = varTransactionID;

				

			

			

			

			

			INSERT INTO tb_transaction_master_detail_temp (

				transactionMasterDetailID,

 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,

 				amount,cost,quantity,

				discount,unitaryAmount,unitaryCost,unitaryPrice,

 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

 				quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID,

				tax1,reference3,skuCatalogItemID,skuQuantity,skuQuantityBySku,

				skuFormatoDescription,itemNameLog 				

 			)

 			SELECT 

				c.transactionMasterDetailID,

 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i.itemID,0,

 				c.unitaryPrice * (iw.quantity - c.quantity),c.unitaryCost * (iw.quantity - c.quantity), (iw.quantity - c.quantity) ,

				0 ,c.unitaryPrice,c.unitaryCost, c.unitaryPrice,

 				0,0,1,0,0,

 				0,0,varWarehouseID,0, 0  , 78  ,  (iw.quantity - c.quantity)   , 1 , 'UNIDAD',i.name 

 			from 

				tb_transaction_master tm 

				inner join  tb_transaction_master_detail c  on 

					c.transactionMasterID = tm.transactionMasterID

				inner join tb_item i on 

					c.componentItemID  = i.itemID

				inner join tb_item_warehouse iw on 

					iw.itemID = i.itemID and 

					iw.warehouseID = varWarehouseID 

			where 

				c.isActive = 1 and 

				c.transactionID = prTransactionID and 

				c.transactionMasterID = prTransactionMasterID and 

				iw.quantity > c.quantity;

				

				

				

				

			

			INSERT INTO tb_transaction_master_detail (

 				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,tax1,reference3,skuCatalogItemID,skuQuantityBySku,skuFormatoDescription		,

				itemNameLog,

 				cost, quantity,skuQuantity

 			)

 			select 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,		

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,u.tax1,reference3,skuCatalogItemID,skuQuantityBySku,skuFormatoDescription		,

				itemNameLog,

 				sum(cost) as cost, sum(quantity) as quantity,sum(skuQuantity) as skuQuantity

			from 

				tb_transaction_master_detail_temp u

			where 

				u.transactionID = varTransactionID 

			group by 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,tax1,

				reference3,skuCatalogItemID,skuQuantityBySku,skuFormatoDescription,

				itemNameLog; 

				

			

			

			SET varAmount = 0; 

			SET varAmount = (select sum(u.amount) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);

			SET varAmount = (case when varAmount is null then 0 else varAmount end);

			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount,tax1 = 0  where transactionMasterID = varTransactionMasterID;

			

			

			insert into tb_transaction_master_info (

				companyID,transactionID,transactionMasterID,

				zoneID,routeID,referenceClientName,referenceClientIdentifier,

				receiptAmount,changeAmount,mesaID

				)

			VALUES(prCompanyID,varTransactionID,varTransactionMasterID,157,0,'','',varAmount,0,546);

			

			

			IF varAmount = 0 THEN

				UPDATE tb_transaction_master  set isActive = 0 where transactionMasterID = varTransactionMasterID;

				SET varTransactionMasterID = 0;		

			END IF;



			

			

			SET prResult = 1;			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_output_by_ajuste',

				0,'Success',CURRENT_TIMESTAMP());

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_output_by_ajuste_transactionID',

				0,varTransactionID,CURRENT_TIMESTAMP());

				

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_output_by_ajuste_transactionMasterID',

				0,varTransactionMasterID,CURRENT_TIMESTAMP());

		

		

		



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_output_by_formulated` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_create_transaction_output_by_formulated`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prPeriodID` INT, 

	IN `prCycleID` INT, OUT `prResult` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para crer una salida de inventario automatica, para los productos tipos formulas'
LBL_PROCEDURE:

BEGIN

			

			DECLARE varTransactionID int default  8; 	

			DECLARE varComponentID int default  35; 	

			DECLARE varComponentItemID int default  33; 							

			DECLARE varComponentAccountID int default 4;		

			

			DECLARE varCountTransactionMasterDetail int default 0;

			DECLARE varTransactionMasterID BIGINT default 0; 			

			declare varNote varchar(150) default 'Salida automatica por evaluacion de formulas';			

			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

			DECLARE varTransactionCausalID int default  0;

			DECLARE varSignID int default  0;

			DECLARE varCurrencyIDFunction int default  0;

			DECLARE varCurrencyIDExternal int default  0;

			DECLARE varCurrencyIDReport int default  0;

			DECLARE varCurrencyTmporal varchar(50) default '';

			DECLARE varStatusIDTransactionInit int default  0;

			DECLARE varStatusIDTransactionFinish int default  0;

			DECLARE varWarehouseTempora varchar(50) default  '';

			DECLARE varWarehouseID int default  0;

			DECLARE varFechaStart datetime;

			DECLARE varFechaEnd datetime;

			DECLARE varWorkflowStageCycleClosed varchar(50) default '';

			

			

			SET varFechaStart = (select u.startOn from tb_accounting_cycle u where u.componentCycleID = prCycleID and u.componentPeriodID = prPeriodID);

			SET varFechaEnd = (select u.endOn from tb_accounting_cycle u where u.componentCycleID = prCycleID and u.componentPeriodID = prPeriodID);

			

			

			CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_WAREHOUSE_DEFAULT",varWarehouseTempora);

			SET varWarehouseID 		= (SELECT warehouseID FROM tb_warehouse where number = varWarehouseTempora);		

			

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);

			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);

			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);

			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);

		

				

			

			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);		

			SET varTransactionCausalID = (

					select t.transactionCausalID 

					from tb_transaction_causal t 

					where t.transactionID = varTransactionID and t.isDefault = 1 limit 1

			); 

			

			

			

			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otheroutput","statusID",varStatusIDTransactionInit );	

			

			

			

			SET varStatusIDTransactionFinish = (

				select 

					ws.workflowStageID 

				from 

					tb_subelement e 

					inner join tb_element el on 

						e.elementID = el.elementID

					inner join tb_workflow_stage ws on 

						ws.workflowID = e.workflowID 

				where 

					e.`name` = 'statusID' and 

					el.`name` = 'tb_transaction_master_otheroutput' and 

					ws.aplicable = 1 

			);

			

			

			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",varWorkflowStageCycleClosed);

	



			

			IF   

				 EXISTS(

					SELECT cc.companyID FROM tb_accounting_cycle cc 

					WHERE  

						cc.companyID = prCompanyID and componentID = varComponentAccountID AND 

						componentPeriodID = prPeriodID and componentCycleID = prCycleID AND 

						statusID = varWorkflowStageCycleClosed 

				) 

			THEN

						SET prResult = 0;

						INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

						VALUES(prCompanyID,prBranchID,prLoginID,'','pr_inventory_create_transaction_output_by_formulated',1,'El ciclo ya esta cerrado',CURRENT_TIMESTAMP());

						LEAVE LBL_PROCEDURE;

			END IF ; 

	

	



			

			INSERT INTO tb_transaction_master(

				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,

				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,

				sourceWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive) 

			VALUES 

			(

				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,

				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,

				varWarehouseID,prLoginID,prBranchID,NOW(),'::01',1

			);

			

			SET varTransactionMasterID = LAST_INSERT_ID();	

			

			

			

			DELETE FROM  

				tb_transaction_master_detail_temp 

			WHERE 

				companyID = prCompanyID and 

				transactionID = varTransactionID;

				

			

 			INSERT INTO tb_transaction_master_detail_temp (

				transactionMasterDetailID,

 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,

 				amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,

 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

 				quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID				

 			)

 			SELECT 

				tmd.transactionMasterDetailID,

 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i2.itemID,0,

 				0,i2.cost * tmd.quantity * dsd.quantity, tmd.quantity * dsd.quantity ,0,0, i2.cost,0,

 				0,0,1,0,0,

 				0,0,varWarehouseID

 			FROM 

				tb_transaction_master_detail tmd 

				inner join tb_transaction_master tmm on 

					tmd.transactionMasterID = tmm.transactionMasterID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tmm.statusID 

				inner join tb_transaction tt on 

					tt.transactionID = tmm.transactionID 

				inner join tb_item i on 

					tmd.componentItemID = i.itemID 

				inner join tb_item_data_sheet ds on 

					ds.itemID = i.itemID 

				inner join tb_item_data_sheet_detail dsd on 

					ds.itemDataSheetID = dsd.itemDataSheetID 

				inner join tb_item i2 on 

					i2.itemID = dsd.itemID 

			WHERE

				tmm.isActive = 1 and 

				IFNULL(tt.signInventory,0) < 0 and 

				tmd.isActive = 1 and 

				tmd.itemFormulatedApplied = 0 and 

				ds.isActive = 1 and 

				dsd.isActive = 1 and 

				(

					(tmm.transactionOn BETWEEN varFechaStart and varFechaEnd) or 

					(

							prPeriodID = 0 and 

							prCycleID = 0 

					)

				) and 

				tt.transactionID in (19  ) and 

				ws.aplicable = 1;

				

			

			#guardar la relacion de la facturas con las salidas 

			insert into tb_company_component_relation(componentIDSource,componentItemIDSource,componentIDTarget,componentItemIDTarget,isActive,note)

			select 

				35 /*transacciones de : otras salidas*/ , 

				varTransactionMasterID,

				48 /*transaccines de factura*/ , 

				k.transactionMasterDetailID,

				1,

				'relacion entre las salidas de materia primera vs detalle de facturas de recetas '

			from 

				tb_transaction_master_detail_temp k ;

		

				

			

			UPDATE tb_transaction_master_detail , tb_transaction_master_detail_temp set 

				tb_transaction_master_detail.itemFormulatedApplied = 1

			WHERE	

				tb_transaction_master_detail.transactionMasterDetailID = 

				tb_transaction_master_detail_temp.transactionMasterDetailID;

				

				

			

			INSERT INTO tb_transaction_master_detail (

 				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,

 				cost, quantity

 			)

 			select 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,		

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID,

 				sum(cost) as cost, sum(quantity) as quantity

			from 

				tb_transaction_master_detail_temp u

			group by 

				companyID,transactionID,transactionMasterID,componentID,

				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			

				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,

				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				

				componentItemID; 

				

				

			SET varCountTransactionMasterDetail = (

			select 

				count(c.transactionMasterDetailID) as count_

			from 

				tb_transaction_master_detail c 

			where 

				c.companyID = prCompanyID and 

				c.isActive = 1 and 

				c.transactionMasterID = varTransactionMasterID and 

				c.quantity > 0 

			); 

			

			

			

			SET varCountTransactionMasterDetail = IFNULL(varCountTransactionMasterDetail,0);

			IF varCountTransactionMasterDetail = 0 THEN 

				 DELETE FROM tb_transaction_master WHERE transactionMasterID = varTransactionMasterID;

		  ELSE 

			   CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otheroutput',prBranchID,0,varTransactionNumber);							 	

			   UPDATE tb_transaction_master set transactionNumber = varTransactionNumber where transactionMasterID = varTransactionMasterID;

			END IF;

				

			

			

			SET prResult = 1;

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_output_by_formulated',

				0,'Success',CURRENT_TIMESTAMP());

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_output_by_formulated_transactionID',

				0,varTransactionID,CURRENT_TIMESTAMP());

				

			

			

			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

			VALUES(

				prCompanyID,prBranchID,prLoginID,'',

				'pr_inventory_create_transaction_output_by_formulated_transactionMasterID',

				0,varTransactionMasterID,CURRENT_TIMESTAMP());

				

			

		

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_eport_list_item_expired` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_eport_list_item_expired`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Lista de Productos por Vencer'
BEGIN

	SELECT 

		i.itemNumber,

		case

			when comp.flavorID = 309  then 

				CONCAT(i.name,' ',i.barCode )

			else 

				i.name 

		end as itemName,

		ciu.name as unitMeasure,

		ic.name as categoryName,

		i.quantity as quantity,

		i.cost as cost,

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 154 limit 1) as pricePublico,

		w.`name` as warehouseName,

		ue.dateExpired,

		ue.quantity quantityExpired ,

		ue.lote ,

		

		(

			select 

					GROUP_CONCAT(concat(natp.firstName,' ',natp.lastName) SEPARATOR ', ')

			from  

				tb_provider_item p 

				inner join tb_naturales natp on 

						natp.entityID = p.entityID 

			where 

				p.itemID = i.itemID 

		) as proveedorName  ,

		

		DATEDIFF(

			CURDATE(), 

			ue.dateExpired

		) as dateExpiredInDay 

		

	FROM 

		tb_item i

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 

		inner join tb_warehouse w on 

			w.warehouseID = i.defaultWarehouseID 	

		inner join tb_item_warehouse_expired ue on 

			ue.itemID = i.itemID and 

			ue.warehouseID = w.warehouseID and 

			ue.companyID = i.companyID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		ue.quantity > 0 

	ORDER BY 

		ue.dateExpired asc ;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_auxiliar_mov_by_allwarehouse` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_auxiliar_mov_by_allwarehouse`(IN `prCompanyID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prItemID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener la lista de todos los movimientos del producto de todas las bodegas'
BEGIN

  declare flavorID int default 0;

	set flavorID = (SELECT u.flavorID from tb_company u where u.companyID = prCompanyID);

	

	

	SELECT 

		x.movementOn as movementOn,

		case

			when flavorID = 306  then 

				 REPLACE(tm.transactionNumber,'ESP','COMPRA ')

			else 

				tm.transactionNumber

		end as transactionNumber,

		i.itemNumber,

		i.name as itemName,

		ci.name as unitMeasureName,

		x.oldQuantity,

		x.oldCost,

		(x.transactionQuantity * x.sign) as transactionQuantity,

		x.transactionCost , 

		x.newQuantity,

		x.newCost,

		IF(x.sign = 1 , 'Entrada','Salida') as transactionType,

		wr.name as warehouseName,

		concat(wr.number,' ',wr.name)   as warehouseNumber

	FROM 

		tb_kardex x 

		inner join tb_item i on 

			x.itemID = i.itemID 

		inner join tb_transaction_master tm  on 

			x.transactionMasterID = tm.transactionMasterID and 

			x.transactionID = tm.transactionID 

		inner join tb_catalog_item ci on 

			i.unitMeasureID = ci.catalogItemID 

		inner join tb_warehouse wr on 

			IF(x.sign = 1,tm.targetWarehouseID,tm.sourceWarehouseID) = wr.warehouseID  

	WHERE

		x.companyID = prCompanyID and 

		x.movementOn between prStartOn and prEndOn and 

		x.itemID = prItemID 

	ORDER BY 

	 	 x.kardexID  ;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_auxiliar_mov_by_warehouse` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_auxiliar_mov_by_warehouse`(IN `prCompanyID` INT, IN `prWarehouseID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prItemID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener los movimientos de productos de una bodega'
BEGIN

	SELECT 

		x.movementOn as transactionOn,

		tm.transactionNumber ,

		i.itemNumber,

		i.name as itemName,

		ci.name as itemUnitmeasure,

		IF(x.sign = 1,'Entrada','Salida') as itemType,

		x.transactionQuantity as quantity,

		x.newQuantityWarehouse as balance

	FROM 

		tb_kardex x 

		inner join tb_item i on 

			x.itemID = i.itemID 

		inner join tb_catalog_item ci on 

			i.unitMeasureID = ci.catalogItemID 

		inner join tb_transaction_master tm on 

			x.transactionMasterID = tm.transactionMasterID and 

			x.transactionID = tm.transactionID 

	WHERE 

		x.movementOn between prStartOn and prEndOn and 

		x.companyID = prCompanyID and 

		x.warehouseID = prWarehouseID and 

		i.itemID = prItemID 

	ORDER BY

	 	x.movementOn , x.kardexID  ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_list_item`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT,IN `prWarehouseID` INT,IN `prCategoryID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Lista de Productos'
BEGIN
	SELECT 
		sub.itemNumber,
		sub.barCode,
		sub.itemName,
		sub.categoryName,
		sub.unitMeasure,
		sub.cost,
		sub.price,
		sub.price2,
		sub.price3,
		sub.warehouseName,
		sub.Moneda,
		sub.unidadMedidaName,
		sub.familyName,
		sub.isActive,
		sub.vendors,
		sum(sub.quantity ) as quantity 
	FROM 
		(
			SELECT 
				i.itemNumber,
				i.barCode,
				case
					when comp.flavorID = 309  then 
						concat(i.`name` , ' ' , i.barCode) 
					else 
						i.name 
				end as itemName,
				ic.name as categoryName,
				ciu.name as unitMeasure,
				i.cost as cost,
				(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 154 limit 1) as price,
				(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 155 limit 1) as price2,
				(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 156 limit 1) as price3,
				w.`name` as warehouseName,
				cur.`name` as Moneda,
				ciu.`name` as unidadMedidaName ,
				IF(family.catalogItemID is null,family.`name`,family_cli.`name`) as familyName ,
				i.isActive,
				'1254' as vendors,  
				iw.quantity AS quantity
			FROM 
				tb_item i
				inner join tb_workflow_stage ws on 
					i.statusID = ws.workflowStageID 
				inner join tb_catalog_item ciu on 
					i.unitMeasureID = ciu.catalogItemID 
				inner join tb_item_category ic on 
					i.inventoryCategoryID = ic.inventoryCategoryID 
				inner join tb_currency cur on 
					cur.currencyID = i.currencyID 
				inner join tb_company comp on 
					comp.companyID = i.companyID 		
				left join tb_public_catalog_detail family_cli on 
					family_cli.publicCatalogDetailID = i.familyID 
				left join tb_catalog_item family on 
					family.catalogItemID = i.familyID 
					
				inner join tb_item_warehouse iw on 
					iw.itemID = i.itemID
				inner join tb_warehouse w on
					w.warehouseID = iw.warehouseID
			WHERE
				iw.quantity > 0 and 
				i.isActive = 1 AND 
				i.companyID = prCompanyID and 
				(
					(
						prWarehouseID = 0 
					)
					or 
					(
						iw.warehouseID = prWarehouseID and   prWarehouseID != 0  
					)
				) and 
				(
					(
						prCategoryID = 0 
					)
					or 
					(
						ic.inventoryCategoryID = prCategoryID and   prCategoryID != 0  
					)
				)			
			ORDER BY 
				w.`name` , ic.`name` , i.`name`
		) sub
	GROUP BY 
		sub.itemNumber,
		sub.barCode,
		sub.itemName,
		sub.categoryName,
		sub.unitMeasure,
		sub.cost,
		sub.price,
		sub.price2,
		sub.price3,
		sub.warehouseName,
		sub.Moneda,
		sub.unidadMedidaName,
		sub.familyName,
		sub.isActive,
		sub.vendors; 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item_by_warehouse` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_list_item_by_warehouse`(IN `prUserID` int,IN `prTokenID` varchar(50),IN `prCompanyID` int,IN `prWarehouseID` VARCHAR(150))
BEGIN



  

	drop temporary table if exists tb_tmp_split;

	drop temporary table if exists tb_tmp_split2;

	create temporary table tb_tmp_split( val char(255) );

	create temporary table tb_tmp_split2( val char(255) );

	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prWarehouseID, ",", "'),('"),"');");

	prepare stmt1 from @sql;

	execute stmt1;

	

	insert into tb_tmp_split2 (val) 

	select zu.val from tb_tmp_split zu; 

	

	SELECT 

		i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				CONCAT(i.`name`,' ',i.barCode)

			else 

				i.name 

		end as itemName,		

		i.barCode as barCode,

		cat.`name` as categoryName, 

		ciu.name as unitMeasure,

		ic.name as categoryName,

		iw.quantity as quantity,

		i.cost as cost,

		

		

		

		

		

		

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 154 limit 1) as price,

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 155 limit 1) as price2,

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 156 limit 1) as price3,

		w.`name` as warehouseName

	FROM 

		tb_item i		

		inner join tb_item_warehouse iw on  

			i.itemID = iw.itemID 

		inner join tb_warehouse w on 

			w.warehouseID = iw.warehouseID 			

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 		

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 		

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

			

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		(

			 (

			  0 IN ( SELECT TUX.val FROM tb_tmp_split2 TUX )

			 )

			 or 

			 (

				w.warehouseID IN (SELECT TU.val FROM tb_tmp_split TU )

			 )

			 

		) and 

		(

			(comp.flavorID = 309  and i.quantity != 0 ) 

			or 

			(comp.flavorID != 309)

		) and 

		(

		 (

				comp.type = 'chicextensiones' and 

				iw.quantity != 0 

		 ) or 

		 (

			 comp.type != 'chicextensiones'

		 )

		)

	ORDER BY 

		ic.`name` , i.`name`; 



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item_out_exists` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_list_item_out_exists`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Lista de Productos'
BEGIN

	SELECT 

		i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				CONCAT(i.`name`,' ',i.barCode)

			else 

				i.name 

		end as itemName,

		cat.`name` as categoryName, 

		ciu.name as unitMeasure,

		ic.name as categoryName,

		i.quantity as quantity,

		i.cost as cost,

		(select pp.price from tb_price pp where pp.itemID = i.itemID limit 1) as price,

		w.`name` as warehouseName

	FROM 

		tb_item i

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 

		inner join tb_warehouse w on 

			w.warehouseID = i.defaultWarehouseID 		

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

		

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		i.quantity = 0 

	ORDER BY 

		ic.`name` , i.`name`; 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item_width_exists` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_list_item_width_exists`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Lista de Productos'
BEGIN

	SELECT 

		i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				CONCAT(i.`name`,' ',i.barCode)

			else 

				i.name 

		end as itemName,

		cat.`name` as categoryName, 

		ciu.name as unitMeasure,

		ic.name as categoryName,

		i.quantity as quantity,

		i.cost as cost,

		(select pp.price from tb_price pp where pp.itemID = i.itemID limit 1) as price,

		w.`name` as warehouseName

	FROM 

		tb_item i

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 

		inner join tb_warehouse w on 

			w.warehouseID = i.defaultWarehouseID 		

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

		

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		i.quantity > 0 

	ORDER BY 

		ic.`name` , i.`name`; 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_master_kardex` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_master_kardex`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prWarehouseID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener el karde de mercaderia'
BEGIN

	declare minItemID int default 0;

	declare maxItemID int default 0;

	declare minKardexID int default 0; 

	declare newCost decimal(19,9)		 default 0;

	declare newQuantity decimal(19,9) default 0;

	declare itemName varchar(500) default '';

	declare itemNumber varchar(50) default ''; 

	 

	set minItemID = (select min(i.itemID) from tb_item i where i.companyID = prCompanyID and i.isActive = 1 and IFNULL(i.isServices,0) = 0 );

	set maxItemID = (select max(i.itemID) from tb_item i where i.companyID = prCompanyID and i.isActive = 1 and IFNULL(i.isServices,0) = 0 );

		

  delete  from tb_master_kardex_temp  where userID = prUserID;

	

	

	insert into tb_master_kardex_temp (

				userID,tokenID,companyID,itemID,itemNumber,itemName,itemCategoryName,minKardexID,

				quantityInput,costInput,quantityOutput,costOutput

	)

	SELECT  

		prUserID,

		prTokenID,

		prCompanyID,

		i.itemID,

		i.itemNumber,

		i.name as itemName,

		cat.`name` as categoryName,

		MIN(k.kardexID) as kardexID,

		SUM(IF(k.sign = 1,k.transactionQuantity,0)) as entrada_cantidad,

		SUM(IF(k.sign = 1,k.transactionQuantity * k.transactionCost, 0)) as entrada_costo,

		SUM(IF(k.sign = -1,k.transactionQuantity,0)) as salida_cantidad,

		SUM(IF(k.sign = -1,k.transactionQuantity * k.transactionCost,0)) as salida_costo

	FROM 

		tb_kardex k 

		inner join tb_item i on 

			k.itemID = i.itemID and 

			k.companyID = i.companyID 

		inner join tb_item_category cat on 

			cat.inventoryCategoryID = i.inventoryCategoryID 

	where	

	  IFNULL(i.isServices,0) = 0 and 

		k.companyID = prCompanyID and 

		k.movementOn between prStartOn and  prEndOn and 

		((k.warehouseID = prWarehouseID  and prWarehouseID <> 0) or (prWarehouseID = 0))

	group by 

		prUserID,

		prTokenID,

		prCompanyID,

		i.itemID,

		i.itemNumber,

		i.name;



	

	insert into tb_master_kardex_temp (

			userID,tokenID,companyID,itemID,itemNumber,itemName,itemCategoryName,

			minKardexID,quantityInput,costInput,quantityOutput,costOutput

	)

	select 

		prUserID,prTokenID,prCompanyID,i.itemID,i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				concat(i.name,' ',i.barCode)

			else 

			  concat(i.name)

		end as nameItem ,

		cat.`name`,0,0,0,0,0

	from 

		tb_item i

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

	where

	  IFNULL(i.isServices,0) = 0 and 

		i.companyID = prCompanyID and 

		i.isActive = 1 and 

		i.itemID not in (select k.itemID from tb_master_kardex_temp k where k.userID = prUserID and k.companyID = prCompanyID);

		

		

			

	while minItemID <= maxItemID and minItemID is not null do 	

		

				

				if exists (

					select  p.itemID from tb_master_kardex_temp p 

					where p.companyID = prCompanyID and p.itemID = minItemID and userID = prUserID and p.minKardexID <> 0  

				) 

				then 

						set newQuantity = 0;

						set newCost 	 = 0;

						set minKardexID 	= (

																	select min(c.minKardexID) 

																	from tb_master_kardex_temp c 

																	where c.itemID = minItemID and c.companyID = prCompanyID and c.userID = prUserID

																);

						set minKardexID	= (

																select max(p.kardexID) 

																from tb_kardex p 

																where 

																	p.companyID = prCompanyID and p.itemID = minItemID 

																	and p.kardexID < minKardexID and 

																		(

																			(p.warehouseID = prWarehouseID  and prWarehouseID <> 0) or (prWarehouseID = 0)

																		)  

															); 

						

						select 

							p.newCost,p.newQuantity into newCost,newQuantity 

						from 

							tb_kardex p where p.kardexID = minKardexID;

							

						update tb_master_kardex_temp set quantityInicial =  newQuantity, costInicial = (newCost * newQuantity) 

						where 

							companyID = prCompanyID and itemID = minItemID and userID = prUserID;

				else

				

						set newQuantity = 0;

						set newCost 	 = 0;

					

						set minKardexID = (

																select max(p.kardexID) 

																from tb_kardex p 

																where 

																	p.companyID = prCompanyID and p.itemID = minItemID and p.movementOn < prStartOn and 

																	(

																		(p.warehouseID = prWarehouseID  and prWarehouseID <> 0) or (prWarehouseID = 0)

																	) 

															); 

						select p.newCost,p.newQuantity into newCost,newQuantity from tb_kardex p where p.kardexID = minKardexID;

						

						update tb_master_kardex_temp set 

							quantityInicial =  newQuantity, costInicial = (newCost * newQuantity),minKardexID = minKardexID  

						where 

							companyID = prCompanyID and itemID = minItemID and userID = prUserID;

											

				end if;

				

				set minItemID 		= (

																select min(i.itemID) from tb_item i where i.isActive = 1 and i.companyID = prCompanyID and i.itemID > minItemID and IFNULL(i.isServices,0) = 0 

														);

	end while;

		

	select 

		* 

	from 

		tb_master_kardex_temp i 

	where 	

		userID = prUserID 

	order by 

		i.itemCategoryName,i.itemName;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_get_report_purchase` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_get_report_purchase`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prWarehouseID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN prEntityIDProvider INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para obtener el karde de mercaderia'
BEGIN

	

		SELECT 

			tm.transactionMasterID,

			tm.transactionNumber,

			tm.createdOn,

			cu.name as currencyName,

			ws.`name` as statusName,

			nat.firstName as providerName,

			w.`name` as warehouseName,

			i.itemNumber,

			i.`name` as itemName,

			td.quantity,

			td.unitaryCost,

			td.cost 

		FROM 

			tb_transaction_master tm 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_naturales nat on 

				nat.entityID = tm.entityID 

			inner join tb_transaction_master_detail td on 

				td.transactionMasterID = tm.transactionMasterID 

			inner join tb_item i on 

				i.itemID = td.componentItemID 

			inner join tb_warehouse w on 

				w.warehouseID = tm.targetWarehouseID 

			inner join tb_currency cu on 

				cu.currencyID = tm.currencyID 

		WHERE 

			tm.isActive = 1 and 

			tm.transactionID = 21  and 

			ws.aplicable = 1 and 

			td.isActive = 1 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn and prEndOn and 

			(

				(

					tm.targetWarehouseID  = prWarehouseID  and 

					prWarehouseID != 0

				)

				or 

				(

				  prWarehouseID = 0 

				)

			) and 

			(

				(

					 tm.entityID  = prEntityIDProvider and 

					 prEntityIDProvider != 0 

				)

				or 

				(

					prEntityIDProvider = 0 

				)

			) 

		ORDER BY 

			tm.createdOn ASC;

			

			

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_last_item_movement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_last_item_movement`(IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Recalcular costo de inventario'
BEGIN

	

	

	select 

		i.itemID,

		i.itemNumber,

		i.barCode,

		i.`name`,

		i.cost,

		i.quantity ,

		p.typePriceID,

		p.price,

		p.percentage 

	from 

		tb_item i 

		inner join tb_price p on 

			i.itemID = p.itemID 

	where 

		i.itemID in (

					select 	

						distinct  

						c.componentItemID 	

					from 	 

						tb_transaction_master tm 

						inner join tb_transaction_master_detail c on 

							tm.transactionMasterID = c.transactionMasterID 

					where

						DATE(tm.createdOn) >= DATE_ADD(DATE(NOW()), INTERVAL -1 DAY)   

		);  

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_inventory_recalculate_cost` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_inventory_recalculate_cost`(IN `prCompanyID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Recalcular costo de inventario'
BEGIN

	declare minItemID_ int default 0;

	declare maxItemID_ int default 0;

	declare minKardexID_ int default 0;

	declare maxKardexID_ int default 0;

	declare sign_ int default 0;

	declare quantity1_ decimal(19,9) default 0;

	declare quantity_ decimal(19,9) default 0;

	declare cost_ decimal(19,9) default 0;

	declare quantityT_ decimal(19,9) default 0;

	declare costT_ decimal(19,9) default 0;

	DECLARE varTiposCosto VARCHAR(50);

		

	CALL pr_core_get_parameter_value(prCompanyID,'INVENTORY_TYPE_COST',varTiposCosto);

	

	set minItemID_ = (select MIN(i.itemID) from tb_item i where i.isActive = 1);

	set maxItemID_ = (select MAX(i.itemID) from tb_item i where i.isActive = 1);

	

	while minItemID_ <= maxItemID_ and minItemID_ is not null do 

		set minKardexID_ = 0;

		set maxKardexID_ = 0;

		set sign_  = 0;

		set quantity1_ = 0;

		set quantity_ = 0;

		set cost_ = 0;

		set quantityT_ = 0;

		set costT_ = 0;

	

		set minKardexID_ = (select min(i.kardexID) from tb_kardex i where i.itemID = minItemID_);

		set maxKardexID_ = (select max(i.kardexID) from tb_kardex i where i.itemID = minItemID_);

				while minKardexID_ <= maxKardexID_ and minKardexID_ is not null do

						select p.transactionQuantity,p.transactionCost,p.sign into quantityT_,costT_,sign_ from tb_kardex p where p.kardexID = minKardexID_;

			

						update tb_kardex set 

				oldCost = cost_ , 

				oldCostWarehouse = cost_,				

				oldQuantity = quantity_ ,

				oldQuantityWarehouse = quantity_

			where 

			   kardexID = minKardexID_;

						

						if sign_ = 1 then 

				set quantity1_ = quantity_;

				set quantity_ 	= quantity_  + quantityT_;

				set cost_ 		= ROUND((((quantityT_ * costT_) + (quantity1_ * cost_)) /  quantity_),8); 

						else 

				set quantity_ 	= quantity_  - quantityT_;

				set cost_ 		= cost_;

			end if;

			

						update tb_kardex set 

				newCost = ROUND(cost_,8),

				newCostWarehouse = ROUND(cost_,8) , 

				newQuantity = ROUND(quantity_,8),

				newQuantityWarehouse = ROUND(quantity_,8) where kardexID = minKardexID_;

			

						set minKardexID_ = (select min(i.kardexID) from tb_kardex i where i.itemID = minItemID_ and i.kardexID > minKardexID_);

		end while; 

		

		update tb_item set cost = ROUND(cost_,8) where itemID = minItemID_;

		update tb_item_warehouse set cost = ROUND(cost_,8) where itemID = minItemID_;

				set minItemID_ = (select MIN(i.itemID) from tb_item i where i.isActive = 1 and i.itemID > minItemID_);	

	end while;

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_notification_buy` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_notification_buy`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

  select 

		gru.nameTransaction ,		

		gru.itemNumber,

		gru.itemName,

	  sum(gru.quantity) as Cantidad,

		avg(gru.unitaryCost) as CostoPromedio,

		sum(gru.utilidad) as Utilidad  		

	from 

		(

			select 

				rx.userID,

				rx.nickname,

				rx.transactionNumber,

				rx.tipo,

				rx.transactionOn,

				rx.itemNumber,

				rx.itemName,

				rx.quantity,

				rx.unitaryCost,

				rx.unitaryPrice,

				(rx.unitaryCost * rx.quantity) as cost,

				(rx.unitaryPrice * rx.quantity) as amount,

				(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

				varCurrencyReporte,

				rx.currencyID,

				rx.exchangeRate,

				rx.`nameTransaction`

			from 

				(

						select 

							tt.`name` as nameTransaction ,

							usr.userID,

							usr.nickname,

							tm.transactionNumber,

							tc.name as tipo,

							tm.transactionOn,				

							i.itemNumber,

							i.name as itemName,

							tmd.quantity,

							tm.currencyID,

							tm.exchangeRate,

							case 

								when varCurrencyReporte = tm.currencyID then 

									tmd.unitaryPrice 

								when tm.exchangeRate > 1 then 

									tm.exchangeRate * (tmd.unitaryPrice)

								else 

									(1/tm.exchangeRate) * (tmd.unitaryPrice)

							end unitaryPrice,

							case 

								when varCurrencyCompras = varCurrencyReporte  then 				

									tmd.unitaryCost

								when tm.exchangeRate > 1 then 

									tm.exchangeRate *  tmd.unitaryCost														

								else 								

									(1/tm.exchangeRate) *   tmd.unitaryCost							

							end  unitaryCost 

							

							

						from 

							tb_transaction_master tm 

							inner join tb_transaction_master_detail tmd on 

								tm.companyID = tmd.companyID and 

								tm.transactionID = tmd.transactionID and 

								tm.transactionMasterID = tmd.transactionMasterID 

							inner join tb_transaction tt on 

								tm.transactionID = tt.transactionID 

							inner join tb_transaction_causal tc on 

								tm.transactionCausalID = tc.transactionCausalID 			

							inner join tb_user usr on 

								tm.createdBy = usr.userID 

							inner join tb_workflow_stage ws on 

								tm.statusID = ws.workflowStageID 		

							inner join tb_item i on 

								tmd.componentItemID = i.itemID 

						where

							tm.companyID = prCompanyID and 

							tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

							tm.isActive = 1 and 

							ws.aplicable = 1 

						order by 

							tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

							

				) rx

		) gru 

	group by 

		gru.nameTransaction ,

		gru.itemNumber,

		gru.itemName; 

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_planilla_create_transaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_planilla_create_transaction`(IN `prCompanyID` INT, IN `prCalendarID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para crear la transaccion de planilla'
BEGIN

  DECLARE varTransactionPayRoll INT DEFAULT 28;   DECLARE varTransactionMasterID INT DEFAULT 0;

  DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

  DECLARE varNominaNumber VARCHAR(50) DEFAULT '';

  DECLARE varTransactionCausalID INT DEFAULT 0;

  DECLARE varUsuario INT DEFAULT 0;

  DECLARE varBranchID INT DEFAULT 0;

  DECLARE varComponentID INT DEFAULT 0;

  DECLARE varCurrencyID INT DEFAULT 0;

  DECLARE varCalendarID INT DEFAULT 0;

  DECLARE varStatusID INT DEFAULT 0;

  DECLARE varAmount DECIMAL(18,4) DEFAULT 0;

  DECLARE varComponentPayRoll INT DEFAULT 75;

  DECLARE varComponentEmployee INT DEFAULT 39;

  DECLARE varCurrencyID2 INT DEFAULT 0;

  DECLARE varExchangeRate DECIMAL(18,8);





    select c.transactionCausalID INTO varTransactionCausalID from tb_transaction_causal c where c.transactionID = varTransactionPayRoll and c.isActive = 1 and c.isDefault = 1 ; 



    CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_rrhh_payroll","statusID",varStatusID );	

 

  SELECT 

  		tm.createdBy,   		tm.createdAt,   		tm.currencyID,

  		tm.number

  INTO 

  		varUsuario,

  		varBranchID,

  		varCurrencyID,

  		varNominaNumber

  FROM

  		tb_employee_calendar_pay tm

  WHERE

  		tm.companyID = prCompanyID 

  		and tm.calendarID = prCalendarID;

  		

    SELECT 

  	   SUM((C.salary + C.commission)  - C.adelantos )

  INTO 

  		varAmount 

  FROM 

  		tb_employee_calendar_pay_detail C

  WHERE

  		C.calendarID = prCalendarID 

		and C.isActive = 1;	

  		

  		

    CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_rrhh_payroll',varBranchID,0,varTransactionNumber);		

	

  

	SET varCurrencyID2 = (case when varCurrencyID = 1 then 2 else 1 end);

  

    CALL pr_core_get_exchange_rate (prCompanyID,NOW(),varCurrencyID,varCurrencyID2,varExchangeRate);

	

  INSERT INTO tb_transaction_master (companyID,transactionID,transactionNumber,branchID,entityID,transactionCausalID,transactionOn,sign,componentID,currencyID,reference1,reference2,descriptionReference,statusID,amount,createdBy,createdAt,createdOn,createdIn,isActive,isApplied,statusIDChangeOn,journalEntryID,classID,areaID,sourceWarehouseID,targetWarehouseID,currencyID2,exchangeRate)  

  VALUES (prCompanyID,varTransactionPayRoll,varTransactionNumber,varBranchID,0,varTransactionCausalID,NOW(),0,varComponentPayRoll,varCurrencyID,prCalendarID,varNominaNumber,'reference1: calendarID,reference2: NominaNumber',varStatusID,varAmount,varUsuario,varBranchID,NOW(),'::1',1,0,NOW(),0,0,0,0,0,varCurrencyID2,varExchangeRate); 

  

  SET varTransactionMasterID = LAST_INSERT_ID();

  

  INSERT INTO tb_transaction_master_detail (companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,amount,cost,unitaryAmount,quantity,discount,unitaryCost,unitaryPrice,descriptionReference,exchangeRateReference,catalogStatusID,inventoryStatusID,isActive)

  SELECT 

  	  prCompanyID,varTransactionPayRoll,varTransactionMasterID,varComponentEmployee,c.employeeID,0,

  	  c.salary,        	  c.commission,    	  c.adelantos,     	  0,0,0,0,'Amount: Salario,Cost: Comision,UnitaryAmount: Adelantos',

  	  varExchangeRate,

  	  0,

	  0, 

	  1  	  

  FROM 

  	 tb_employee_calendar_pay_detail c

  WHERE

  	  c.calendarID = prCalendarID 

  	  and c.isActive = 1;

  

  

   	CALL pr_concept_helper_calendarpay (prCompanyID,varTransactionPayRoll,varTransactionMasterID);

	  

  

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_planilla_remove_adelanto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_planilla_remove_adelanto`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Eliminar el adelanto de planilla'
BEGIN



  DECLARE varAmount NUMERIC(18,2) DEFAULT 0;

  DECLARE varAdelantosActuales NUMERIC(18,2) DEFAULT 0;

  DECLARE varCalendarDetailID INT DEFAULT 0;

  DECLARE varEmployeeID INT DEFAULT 0;

  DECLARE varAccountingCycleID INT DEFAULT 0;

  DECLARE varTypeIDNomina INT DEFAULT 0;

  DECLARE varCurrencyID INT DEFAULT 0;

	



	SELECT 

		tm.currencyID, 		tm.reference1, 		tm.reference2, 		tm.entityID, 			tm.amount 			INTO 

		varCurrencyID,

		varTypeIDNomina,

		varAccountingCycleID,

		varEmployeeID,

		varAmount

	FROM 

		tb_transaction_master tm

	WHERE

		tm.companyID = prCompanyID 

		and tm.transactionID = prTransactionID 

		and tm.transactionMasterID = prTransactionMasterID

		and tm.isActive = 1;  

		

		

	select 

		pd.adelantos ,

		pd.calendarDetailID 

	INTO 

		varAdelantosActuales,

		varCalendarDetailID

	from 

		tb_employee_calendar_pay p 

		inner join tb_employee_calendar_pay_detail pd on 

			p.calendarID = pd.calendarID 

	where

		p.isActive = 1 

		and p.typeID = varTypeIDNomina 

		and p.currencyID = varCurrencyID 

		and p.accountingCycleID = varAccountingCycleID

		and pd.isActive = 1 

		and pd.employeeID = varEmployeeID;

		

		update tb_employee_calendar_pay_detail set adelantos = (varAdelantosActuales - varAmount) where calendarDetailID = varCalendarDetailID; 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_planilla_update_adelanto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_planilla_update_adelanto`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para actualizar los adelantos en la planilla'
BEGIN

 

  DECLARE varAmount NUMERIC(18,2) DEFAULT 0;

  DECLARE varAdelantosActuales NUMERIC(18,2) DEFAULT 0;

  DECLARE varCalendarDetailID INT DEFAULT 0;

  DECLARE varEmployeeID INT DEFAULT 0;

  DECLARE varAccountingCycleID INT DEFAULT 0;

  DECLARE varTypeIDNomina INT DEFAULT 0;

  DECLARE varCurrencyID INT DEFAULT 0;

	



	SELECT 

		tm.currencyID, 		tm.reference1, 		tm.reference2, 		tm.entityID, 			tm.amount 			INTO 

		varCurrencyID,

		varTypeIDNomina,

		varAccountingCycleID,

		varEmployeeID,

		varAmount

	FROM 

		tb_transaction_master tm

	WHERE

		tm.companyID = prCompanyID 

		and tm.transactionID = prTransactionID 

		and tm.transactionMasterID = prTransactionMasterID

		and tm.isActive = 1;  

		

		

	select 

		pd.adelantos ,

		pd.calendarDetailID 

	INTO 

		varAdelantosActuales,

		varCalendarDetailID

	from 

		tb_employee_calendar_pay p 

		inner join tb_employee_calendar_pay_detail pd on 

			p.calendarID = pd.calendarID 

	where

		p.isActive = 1 

		and p.typeID = varTypeIDNomina 

		and p.currencyID = varCurrencyID 

		and p.accountingCycleID = varAccountingCycleID

		and pd.isActive = 1 

		and pd.employeeID = varEmployeeID;

		

		update tb_employee_calendar_pay_detail set adelantos = (varAdelantosActuales + varAmount) where calendarDetailID = varCalendarDetailID; 

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_purchase_get_report_purchase_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_purchase_get_report_purchase_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prProviderID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de Compras'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.tipo,

		rx.transactionOn,

		rx.createdOn,

		DAYOFMONTH(rx.createdOn) as dayOfMonth,

		rx.providerNumber,

		rx.legalName,

		rx.zone,

		rx.itemNumber,

		rx.itemName,

		rx.nameCategory,

		rx.quantity,

		rx.unitaryCost,		

		(rx.unitaryCost * rx.quantity) as cost,	

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					tc.name as tipo,

					tm.transactionOn,

					pro.providerNumber,

					l.legalName,

					'' as zone,

					i.itemNumber,

					i.name as itemName,

					cat.`name` as nameCategory,

					tmd.quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					

					case 

						when varCurrencyCompras = varCurrencyReporte  then 				

							tmd.unitaryCost

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost														

						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost							

					end  unitaryCost 					

					

				from 

					tb_transaction_master tm 

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_provider pro on 

						pro.entityID = tm.entityID 

					inner join tb_legal l on 

						pro.entityID = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 										

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

				where

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.transactionID in (21  ) and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 and 

					(

						(tm.entityID = prProviderID and prProviderID != 0) or

						(prProviderID = 0 )

					)

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

					

		) rx;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_purchase_get_report_purchase_taller` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_purchase_get_report_purchase_taller`(IN `prUserID` int,IN `prTokenID` varchar(50),IN `prCompanyID` int,IN `prEmployerID` varchar(150), IN prStartOn DATETIME, IN prEndOn DATETIME)
BEGIN

	

	

		drop temporary table if exists tb_tmp_split;

	drop temporary table if exists tb_tmp_split2;

	create temporary table tb_tmp_split( val char(255) );

	create temporary table tb_tmp_split2( val char(255) );

	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prEmployerID, ",", "'),('"),"');");

	prepare stmt1 from @sql;

	execute stmt1;

	

	insert into tb_tmp_split2 (val) 

	select zu.val from tb_tmp_split zu; 

	

	

	

	select 

		estado.`name` as Estado,	

		CONCAT(u.firstName) as firstName,

		u.entityID ,

		count(*) as Cantidad 	

	from 

		tb_transaction_master c 

		inner join tb_naturales u on 

			c.entityIDSecondary = u.entityID 

		inner join tb_catalog_item estado on 

			estado.catalogItemID = c.areaID 

		inner join tb_employee emp on 

			emp.entityID = u.entityID

	where 

		c.transactionID = 40   and 

		c.isActive = 1 and 

		c.companyID = prCompanyID and 

		c.createdOn between prStartOn and  prEndOn and  

		(

			 (

			  0 IN ( SELECT TUX.val FROM tb_tmp_split2 TUX )

			 )

			 or 

			 (

				u.entityID IN (SELECT TU.val FROM tb_tmp_split TU )

			 )

			 

		) 

	group by 

		estado.`name`,

		u.firstName,

		u.entityID 

	order by 

		1,2 ;

		

		

	select 

		c.transactionNumber,

		c.transactionOn,

		cus.customerNumber,

		nat.firstName ,

		ep.employeNumber, 

		natp.firstName as firstNameEmployer, 

		c.note ,

		c.reference2,

		c.reference3 

	from 

		tb_transaction_master c 

		inner join tb_customer cus on 

			cus.entityID = c.entityID 

		inner join tb_naturales nat on 

			cus.entityID = nat.entityID  

		inner join tb_naturales natp on 

			natp.entityID = c.entityIDSecondary 

		inner join tb_employee ep on 

			ep.entityID = natp.entityID 

	where 

		c.transactionID = 40  and 

		c.isActive = 1 and 

		c.createdOn between prStartOn and  prEndOn   

	order by 

		c.transactionMasterID desc ;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_by_client` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_by_client`(IN `prCompanyID` INT,

	IN `prTokenID` VARCHAR(50),

	IN `prUserID` INT,

	IN `prStartOn` DATE,

	IN `prEndOn` DATE,

	IN `prInventoryCategoryID` INT,

	IN `prWarehouse` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;		

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);



	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

					

	select 



		uv.customerNumber,

		uv.legalName,			

		SUM(uv.quantity) as quantity,

		SUM((uv.unitaryCost * uv.quantity)) as cost,

		SUM((uv.unitaryPrice * uv.quantity)) as amount,

		SUM((uv.unitaryPrice * uv.quantity) + (uv.iva * uv.quantity))  as amountConIva,

		SUM((uv.unitaryPrice * uv.quantity) - (uv.unitaryCost * uv.quantity) )   as utilidad,

		SUM((uv.iva)) as iva,

		SUM((uv.quantity * uv.iva)) as ivaTotal,

		varCurrencyReporte,

		uv.currencyID,

		SUM(uv.amountCommision ) as amountCommision,		

		SUM(uv.pagoConPuntos) as pagoConPuntos

	from 

		(				

					select 

						rx.userID,

						rx.nickname,

						rx.transactionNumber,

						rx.employerName,

						rx.tipo,

						rx.transactionOn,

						rx.createdOn,

						DAYOFMONTH(rx.createdOn) as dayOfMonth,

						rx.customerNumber,

						rx.legalName,

						rx.zone,

						rx.itemNumber,

						rx.itemName,

						rx.itemNameLog,

						rx.phoneNumber,

						rx.Agent,

						rx.Commentary,

						rx.nameCategory,

						rx.quantity,

						rx.unitaryCost,

						rx.unitaryPrice,

						(rx.unitaryCost * rx.quantity) as cost,

						(rx.unitaryPrice * rx.quantity) as amount,

						(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,

						(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

						(rx.iva) as iva,

						(rx.quantity * rx.iva) as ivaTotal,

						varCurrencyReporte,

						rx.currencyID,

						rx.exchangeRate,

						rx.amountCommision,						

						rx.pagoConPuntos 

					from 

						(

								select 

									usr.userID,

									usr.nickname,

									tm.transactionNumber,

									IFNULL(nat_emp.firstName,'') as employerName,

									tc.name as tipo,

									tm.transactionOn,

									cus.customerNumber,

									l.legalName,

									ci.name as zone,

									i.itemNumber,

									i.name as itemName,

									tmd.itemNameLog,

									cat.`name` as nameCategory,

									cus.phoneNumber,

									'' AS Agent,

									'' as Commentary,

									tmd.quantity,

									tm.currencyID,

									tm.exchangeRate,

									tm.createdOn,									

									case 

										when varCurrencyReporte = tm.currencyID then 

											tmd.unitaryPrice 

										when tm.exchangeRate > 1 then 

											tm.exchangeRate * (tmd.unitaryPrice)

										else 

											(1/tm.exchangeRate) * (tmd.unitaryPrice)

									end unitaryPrice,

									case 

										when varCurrencyReporte = tm.currencyID  then 			

											tmd.unitaryCost

										when tm.exchangeRate > 1 then 

											tm.exchangeRate *  tmd.unitaryCost

										else 

											(1/tm.exchangeRate) *   tmd.unitaryCost					

									end  unitaryCost ,

									case 

										when varCurrencyReporte = tm.currencyID  then 			

											IFNULL(tmd.tax1,0)

										when tm.exchangeRate > 1 then 

											tm.exchangeRate *  IFNULL(tmd.tax1,0)

										else 								

											(1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

									end as iva ,

									case 

										when varCurrencyReporte = tm.currencyID  then 			

											IFNULL(amountCommision,0)

										when tm.exchangeRate > 1 then 

											tm.exchangeRate *  IFNULL(amountCommision,0)

										else 								

											(1/tm.exchangeRate) *   IFNULL(amountCommision,0)

									end  as amountCommision , 									

									IFNULL(tmi.receiptAmountPoint,0) / 0.03 as pagoConPuntos															

								from 

									tb_transaction_master tm  					

									inner join tb_transaction_master_detail tmd on 

										tm.companyID = tmd.companyID and 

										tm.transactionID = tmd.transactionID and 

										tm.transactionMasterID = tmd.transactionMasterID 

									inner join tb_transaction_causal tc on 

										tm.transactionCausalID = tc.transactionCausalID 

									inner join tb_customer cus on 

										tm.entityID = cus.entityID 

									inner join tb_legal l on 

										cus.entityID = l.entityID 

									inner join tb_user usr on 

										tm.createdBy = usr.userID 

									inner join tb_workflow_stage ws on 

										tm.statusID = ws.workflowStageID 

									inner join tb_transaction_master_info tmi on 

										tm.companyID = tmi.companyID and 

										tm.transactionID = tmi.transactionID and 

										tm.transactionMasterID = tmi.transactionMasterID 

									inner join tb_catalog_item ci on 

										tmi.zoneID = ci.catalogItemID 

									inner join tb_item i on 

										tmd.componentItemID = i.itemID 

									inner join tb_item_category cat on 

										cat.inventoryCategoryID = i.inventoryCategoryID 

									left join tb_naturales nat_emp on 

										nat_emp.entityID = tm.entityIDSecondary 

								where  					

									tm.companyID = prCompanyID and 

									tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

									tm.isActive = 1 and 

									tmd.isActive = 1 and 

									ws.aplicable = 1 and 

									(



										prInventoryCategoryID = 0 

										or 

										(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

									) and 

									(

										prWarehouse = 0 

										or 

										(

											prWarehouse != 0 and 

											tm.sourceWarehouseID =  prWarehouse 

										)

									)

								order by 

									tm.transactionMasterID asc, tmd.transactionMasterDetailID asc								



						) rx		



		) uv

		GROUP BY 

			uv.customerNumber, uv.legalName;



		



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_comisssion_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_comisssion_summary`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 		

		rx.employerName,		

		rx.amountCommision 

	from 

		(

				select 		

					nat.firstName as employerName,	

					sum(tmd.amountCommision) as amountCommision  

				from  

					tb_transaction_master tm 

					inner join tb_transaction_master_detail  tmd on 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_workflow_stage ws on 

						ws.workflowStageID = tm.statusID 

					inner join tb_naturales nat on 

						tm.entityIDSecondary = nat.entityID 

				where 

					tm.transactionID = 19 and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					tm.transactionOn BETWEEN prStartOn and prEndOn and 

					ws.aplicable = 1 

				group by 

					nat.firstName 

					

		) rx; 



		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_day` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_day`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		ul.itemNumber,

		ul.itemName,

		ul.nameCategory,

		ul.quantity,

		ul.unitaryCost,

		ul.unitaryPrice,

		ul.cost,

		ul.amount,

		ul.utilidad

	from 

		(

			select 		

				rx.itemNumber,

				lower(rx.itemName) as itemName,		

				rx.nameCategory,

				sum(rx.quantity) as quantity,

				avg(rx.unitaryCost) as unitaryCost,

				avg(rx.unitaryPrice) as unitaryPrice,

				avg((rx.unitaryCost * rx.quantity)) as cost,

				sum((rx.unitaryPrice * rx.quantity)) as amount,

				sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad

			

			from 

				(

						select 

							usr.userID,

							usr.nickname,

							tm.transactionNumber,

							tc.name as tipo,

							tm.transactionOn,

							cus.customerNumber,

							l.legalName,

							ci.name as zone,

							i.itemNumber,

							i.name as itemName,

							cat.`name` as nameCategory,

							tmd.quantity,

							tm.currencyID,

							tm.exchangeRate,

							

							

							case 

								when varCurrencyReporte = tm.currencyID then 

									tmd.unitaryPrice 

								when tm.exchangeRate > 1 then 

									tm.exchangeRate * (tmd.unitaryPrice)

								else 

									(1/tm.exchangeRate) * (tmd.unitaryPrice)

							end unitaryPrice,

							

							

							

							case 

								when varCurrencyCompras = varCurrencyReporte  then 				

									tmd.unitaryCost

								when tm.exchangeRate > 1 then 

									tm.exchangeRate *  tmd.unitaryCost														

								else 								

									(1/tm.exchangeRate) *   tmd.unitaryCost							

							end  unitaryCost 

							

							

						from 

							tb_transaction_master tm 

							inner join tb_transaction_master_detail tmd on 

								tm.companyID = tmd.companyID and 

								tm.transactionID = tmd.transactionID and 

								tm.transactionMasterID = tmd.transactionMasterID 

							inner join tb_transaction_causal tc on 

								tm.transactionCausalID = tc.transactionCausalID 

							inner join tb_customer cus on 

								tm.entityID = cus.entityID 

							inner join tb_legal l on 

								cus.entityID = l.entityID 

							inner join tb_user usr on 

								tm.createdBy = usr.userID 

							inner join tb_workflow_stage ws on 

								tm.statusID = ws.workflowStageID 

							inner join tb_transaction_master_info tmi on 

								tm.companyID = tmi.companyID and 

								tm.transactionID = tmi.transactionID and 

								tm.transactionMasterID = tmi.transactionMasterID 

							inner join tb_catalog_item ci on 

								tmi.zoneID = ci.catalogItemID 

							inner join tb_item i on 

								tmd.componentItemID = i.itemID 

							inner join tb_item_category cat on 

								cat.inventoryCategoryID = i.inventoryCategoryID 

						where

						  tm.transactionID in (19 /*factura*/ ) and 

							tm.companyID = prCompanyID and 

							tm.createdOn between prStartOn  and prEndOn   and 

							tm.isActive = 1 and 

							tmd.isActive = 1 and 

							ws.aplicable = 1 

						order by 

							tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

							

				) rx

			GROUP BY 

				rx.itemNumber,

				rx.itemName,

				rx.nameCategory 

		) ul 

	order by 

		ul.nameCategory,ul.itemName ;

	

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), 	IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT ,

IN prUserIDCreatedBy INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.employerName,

		rx.tipo,

		rx.transactionOn,

		rx.createdOn,

		DAYOFMONTH(rx.createdOn) as dayOfMonth,

		rx.customerNumber,

		rx.currencyName,

		rx.note,

		rx.legalName,

		rx.zone,

		rx.itemNumber,

		rx.itemName,

		rx.itemNameLog,

		rx.phoneNumber,

		rx.Agent,

		rx.Commentary,

		rx.nameCategory,

		rx.quantity,

		rx.unitaryCost,

		rx.unitaryPrice,

		(rx.unitaryCost * rx.quantity) as cost,

		(rx.unitaryPrice * rx.quantity) as amount,

		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,

		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

		(rx.iva) as iva,

		(rx.quantity * rx.iva) as ivaTotal,

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate,

		rx.amountCommision 

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName,' |',usr.nickname ),'') as employerName,

					tc.name as tipo,

					tm.transactionOn,

					cus.customerNumber,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,

					ci.name as zone,

					i.itemNumber,

					i.name as itemName,

					tmd.itemNameLog,

					cat.`name` as nameCategory,

					cus.phoneNumber,

					'' AS Agent,

					'' as Commentary,

					tmd.quantity as quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					cur.`name` as currencyName,

					tm.note as note,

					

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					

					

					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							tmd.unitaryCost * ifnull(tmd.skuQuantity ,0) 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 												

						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 				

					end  unitaryCost ,

					

					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(tmd.tax1,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(tmd.tax1,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

					end as iva ,

					

					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(amountCommision,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(amountCommision,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)

					end  as amountCommision 

					

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_customer cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_transaction_master_info tmi on 

						tm.companyID = tmi.companyID and 

						tm.transactionID = tmi.transactionID and 

						tm.transactionMasterID = tmi.transactionMasterID 

					inner join tb_catalog_item ci on 

						tmi.zoneID = ci.catalogItemID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

					left join tb_naturales nat_emp on 

						nat_emp.entityID = tm.entityIDSecondary 

				where  					

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 and 

					tm.transactionID in (19 /*FACTURA*/ ) and 

					(

						prInventoryCategoryID = 0 

						or 

						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

					) and 

					(

						prWarehouse = 0 

						or 

						(

							prWarehouse != 0 and 

							tm.sourceWarehouseID =  prWarehouse 

					  )

					) and 

					(

						prUserIDCreatedBy = 0 

						or 

						(

							prUserIDCreatedBy != 0 and 

							prUserIDCreatedBy = tm.createdBy 

						)

					)

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

					

		) rx;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_detail_commission` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_detail_commission`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT, IN prUserIDSales INT, IN prText VARCHAR(50), IN prEmployerID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.employerName,

		rx.tipo,

		rx.transactionOn,

		rx.createdOn,

		DAYOFMONTH(rx.createdOn) as dayOfMonth,

		rx.customerNumber,

		rx.currencyName,

		rx.note,

		rx.legalName,

		rx.zone,

		rx.itemNumber,

		rx.itemName,

		rx.itemNameLog,

		rx.phoneNumber,

		rx.Agent,

		rx.Commentary,

		rx.nameCategory,

		rx.quantity,

		rx.unitaryCost,

		rx.unitaryPrice,

		(rx.unitaryCost * rx.quantity) as cost,

		(rx.unitaryPrice * rx.quantity) as amount,

		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,

		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

		(rx.iva) as iva,

		(rx.quantity * rx.iva) as ivaTotal,

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate,

		rx.amountCommision 

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,

					tc.name as tipo,

					tm.transactionOn,

					cus.customerNumber,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,

					ci.name as zone,

					i.itemNumber,

					i.name as itemName,

					tmd.itemNameLog,

					cat.`name` as nameCategory,

					cus.phoneNumber,

					'' AS Agent,

					'' as Commentary,

					tmd.quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					cur.`name` as currencyName,

					tm.note as note,

					

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					

					

					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							tmd.unitaryCost

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost														

						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost							

					end  unitaryCost ,

					

					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(tmd.tax1,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(tmd.tax1,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

					end as iva ,

					

					

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(amountCommision,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(amountCommision,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)

					end  as amountCommision 

					

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_customer cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_transaction_master_info tmi on 

						tm.companyID = tmi.companyID and 

						tm.transactionID = tmi.transactionID and 

						tm.transactionMasterID = tmi.transactionMasterID 

					inner join tb_catalog_item ci on 

						tmi.zoneID = ci.catalogItemID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

					left join tb_naturales nat_emp on 

						nat_emp.entityID = tm.entityIDSecondary 

				where  					

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 and 

					(

						prInventoryCategoryID = 0 

						or 

						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

					) and 

					(

						prWarehouse = 0 

						or 

						(

							prWarehouse != 0 and 

							tm.sourceWarehouseID =  prWarehouse 

					  )

					)

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

					

		) rx;

		

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_summary`(IN `prCompanyID` INT, 
IN `prTokenID` VARCHAR(50), IN `prUserID` INT, 
 IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prUserIDFilter` INT , 
 IN prConceptFilter VARCHAR(150), IN prWithTax1 INT ,IN `prBranchID` INT, 
 IN prWarehouseID INT, IN prEntityIDCustomer INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Resumen de venta'
BEGIN
	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE varZoneOraria INT DEFAULT 0;
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE convert_ VARCHAR(50);	
	DECLARE prFlavorID INT DEFAULT 0;

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	CALL pr_core_get_parameter_value(prCompanyID,"CORE_ZONA_HORARIA",varZoneOraria);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_); 
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);
	SET prFlavorID 								= (SELECT flavorID FROM tb_company c where c.companyID = prCompanyID);

	drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;
  

	select 
			rx.userID,
			rx.nickname,
			rx.currencyID,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
      fn_translate_transaction_master_info_amounts( prCompanyID,prFlavorID,rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 0, 0,  'CurrencyName') as currencyName,
			GROUP_CONCAT(DISTINCT rx.categoryName ORDER BY rx.categoryName SEPARATOR ', ') as categoryName,
			'' as categorySubName,
			rx.exchangeRate,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmount, rx.receiptAmountDol,  'Amount'), DECIMAL(10,2)) as  EfectivoCordoba,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmount, rx.receiptAmountDol,  'AmountExt'), DECIMAL(10,2))  as EfectivoDolares ,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountCard, rx.receiptAmountCardDol,  'Amount'), DECIMAL(10,2))  as TarjetaCordoba,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountCard, rx.receiptAmountCardDol,  'AmountExt'), DECIMAL(10,2))  as TarjetaDolares ,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountBank, rx.receiptAmountBankDol,  'Amount'), DECIMAL(10,2))  as TansferenciaCordoba,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountBank, rx.receiptAmountBankDol,  'AmountExt'), DECIMAL(10,2))  as TransferenciaDolares, 
			avg(rx.receiptAmountPoint) as receiptAmountPoint , 
			IFNULL(AVG(rx.discount),0) as discount, 
			sum((rx.unitaryCost * rx.quantity)) as cost,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 
			(sum((rx.unitaryPrice  * rx.quantity) +  ifnull(rx.tax2,0) )), 0,  'Convert') , DECIMAL(10,2))
			 as totalSinIva,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 
			(sum(rx.iva * rx.quantity)), 0,  'Convert'), DECIMAL(10,2)) as totalIva,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, (sum(
				(rx.unitaryPrice * rx.quantity) + 
				(rx.iva * rx.quantity ) + 
				( ifnull(rx.tax2,0) * 1 ) 
        ) - 
        avg(rx.receiptAmountPoint)  - 
         IFNULL(AVG(rx.discount),0)), 0,  'Convert') , DECIMAL(10,2))
      as totalDocument,
			sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad		
	from
		(
				select 
					usr.userID,
					case 
						when comp.flavorID = 306 then 
							nat_emp.firstName 
						else 
							usr.nickname
					end as nickname,				
					tm.transactionNumber,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,
					ci.name as zone,
					tm.tax2 as tax2,					
					tmi.receiptAmount,
					tmi.receiptAmountDol,
					tmi.receiptAmountCard,
					tmi.receiptAmountCardDol,
					tmi.receiptAmountBank ,
					tmi.receiptAmountBankDol,			
					tmi.receiptAmountPoint , 		
					CASE 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn) THEN  
								'ANULADA' 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn > prEndOn THEN  
								'POST-ANULADA' 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn < prStartOn and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn)  THEN  
								'DEVOLUCION' 
						ELSE 
							ws.name	
					END as statusName,
					nat.firstName ,
					cu.name AS currencyName,
					icat.`name` as categoryName,
					CASE 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn) THEN  
								tmd.quantity * 0
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn > prEndOn THEN  
								tmd.quantity 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn < prStartOn and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn) THEN  
								tmd.quantity * -1
						ELSE 
							tmd.quantity 
					END as quantity,
					tmd.unitaryPrice,
					case 
						when varCurrencyCompras = varCurrencyReporte  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost ,
					IFNULL(tmd.tax1,0) as  iva ,
					IFNULL(tmd.amountCommision,0) as amountCommision,
					tm.exchangeRate,
					tm.discount,
					tm.transactionID,
					tm.currencyID 
				from 
					tb_transaction_master tm  					
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					INNER JOIN tb_naturales nat ON 
						nat.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_branch braus on 
						braus.branchID = usr.locationID
					inner join tb_workflow_stage ws on  
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					INNER JOIN tb_currency cu ON 
						cu.currencyID = tm.currencyID 
					inner join tb_item it on 
						it.itemID = tmd.componentItemID 
					inner join tb_item_category icat on 
						icat.inventoryCategoryID = it.inventoryCategoryID 
					inner join tb_company comp on 
						comp.companyID = tm.companyID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where
					tm.companyID = prCompanyID and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 			
					(
						 (tm.entityID = prEntityIDCustomer and prEntityIDCustomer != 0 )
						 or 
						 (prEntityIDCustomer = 0)
					)
					and 		
					(
					  (tm.tax1 = 0 and prWithTax1 = -1 )
						or 
						(tm.tax1 > 0 and prWithTax1 = 1 )
						or 
						(prWithTax1 = 0)
					)
					and 					
					(
						(prConceptFilter = '-1') or
						(
								prConceptFilter != '-1' and 
								it.inventoryCategoryID in 
								(
									select val  from tb_tmp_split 
								)
						) 
					)
					and 
  				(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
					)
					and 
					(
						(braus.branchID = prBranchID and prBranchID != 0 )
						or 
						(prBranchID = 0)
					)
					and 
					(
						(tm.sourceWarehouseID = prWarehouseID and prWarehouseID != 0)
						or 
						(prWarehouseID = 0)
					)
					and 
					(
					(
  							tm.transactionID = 19 and 
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR)  between prStartOn and prEndOn and 								ws.aplicable = 1  
							)
							or 
							(
								tm.transactionID = 19 and 
								ws.eliminable = 0 and 
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn  and 
								DATE_ADD(tm.statusIDChangeOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn 
							)
							or 
							(
	  						tm.transactionID = 19 and 
								ws.eliminable = 0 and 								
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn  and 
								DATE_ADD(tm.statusIDChangeOn, INTERVAL varZoneOraria HOUR) > prEndOn 
  						)
							or 							
							(
								tm.transactionID = 19 and 
								ws.eliminable = 0 and 
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR) < prStartOn  and 
								DATE_ADD(tm.statusIDChangeOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn 
							)						
					)
				order by 
					tm.transactionMasterID asc 
		) rx 
	group by 
			rx.userID,
			rx.nickname,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			rx.currencyName,
			rx.exchangeRate,
			rx.discount,
			rx.transactionID ;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_summary_credit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_summary_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prUserIDFilter` INT , IN prConceptFilter VARCHAR(150) ,IN `prBranchID` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Resumen de venta'
BEGIN
	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);
  DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;
  DECLARE currencyIDNameTarget VARCHAR(250);	
  DECLARE currencyIDNameSource VARCHAR(250);
  DECLARE convert_ VARCHAR(50);	 
  
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		
  CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
  CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
  CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_); 
  CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);	
  
	drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;

	select 
			rx.userID,
			rx.nickname,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			case 
        when convert_ = 'None'  then 
          rx.currencyName
        else 
          convert_ 
      end as currencyName,
			rx.categoryName,
			'' as categorySubName,
      case 
			when convert_ = 'Dolar' and rx.currencyName != 'Dolar'  then 
				rx.receiptAmount * exchangeRate_ 
			when convert_ = 'Cordoba' and rx.currencyName != 'Cordoba'  then 
				rx.receiptAmount / exchangeRate_  
			else 
				rx.receiptAmount
		end as receiptAmount,
			sum((rx.unitaryCost * rx.quantity)) as cost,
			sum((rx.unitaryPrice * rx.quantity)) as totalDocument,
			sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad		
	from
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					l.legalName,
					ci.name as zone,
					ws.name AS statusName,
					nat.firstName ,
					cu.name AS currencyName,
					icat.`name` as categoryName,
					tmd.quantity,
					tmin.receiptAmount, 
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					case 
						when varCurrencyCompras = varCurrencyReporte  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost 
				from 
					tb_transaction_master tm 
					inner join tb_transaction_master_info  tmin on 
						tmin.transactionMasterID = tm.transactionMasterID  
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					INNER JOIN tb_naturales nat ON 
						nat.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_branch braus on 
						braus.branchID = usr.locationID
					inner join tb_workflow_stage ws on  
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on  
						tmi.zoneID = ci.catalogItemID 
					INNER JOIN tb_currency cu ON 
						cu.currencyID = tm.currencyID 
					inner join tb_item it on 
						it.itemID = tmd.componentItemID 
					inner join tb_item_category icat on 
						icat.inventoryCategoryID = it.inventoryCategoryID 
					inner join tb_company comp on 
						tm.companyID = comp.companyID 
				where
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn and concat(prEndOn,' 23:59:59') and  
					tc.transactionCausalID in (22,24)  and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					(
							(
								comp.flavorID != 326 
							)
					)		and 
					(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
					) 
					and 
					(
						(braus.branchID = prBranchID and prBranchID != 0 )
						or 
						(prBranchID = 0)
					)
					and 
					(
						(prConceptFilter = '-1') or
						(
								prConceptFilter != '-1' and 
								it.inventoryCategoryID in 
								(
									select val  from tb_tmp_split 
								)
						) 
					)
				order by 
					tm.transactionMasterID asc 
		) rx 
	group by 
			rx.userID,
			rx.nickname,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			rx.currencyName,
			rx.receiptAmount ; 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_utility_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_report_sales_utility_summary`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE, IN prExpenditureClassification INT, IN prBranchID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

		

	

	create table tb_tmp_comision_gastos (

		gastosName varchar(250),

		amount decimal(19,2)		

	);

	

	

	insert into tb_tmp_comision_gastos(gastosName,amount)

	select 		

		 ci.`name` as Tipo,

		 tm.amount

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_public_catalog_detail ci on 

			ci.publicCatalogDetailID = tm.priorityID 

		inner join tb_public_catalog_detail ci2 on 

			ci2.publicCatalogDetailID = tm.areaID 

	where

		tm.transactionID = 38  and 

		(

			(tm.branchID = prBranchID and prBranchID != 0)

			or 

			(prBranchID = 0 )			

		) and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.transactionOn between prStartOn and prEndOn and 

		(

			(

				tm.classID = prExpenditureClassification and 

				prExpenditureClassification != 0 

			) OR 

			(

				prExpenditureClassification = 0

			)

		);

	

	

	

	create table tb_tmp_comision_venta_costo (

		ventaName varchar(250),

		amountVenta decimal(19,2),

		amountCost decimal(19,2)

	);

	

	

	insert into tb_tmp_comision_venta_costo(ventaName,amountVenta,amountCost)	

	select 

		'venta',		

		

		case 

					when varCurrencyReporte = tm.currencyID then 

						tmd.unitaryPrice  * tmd.quantity 

					when tm.exchangeRate > 1 then 

						tm.exchangeRate * (tmd.unitaryPrice) * tmd.quantity 

					else 

						(1/tm.exchangeRate) * (tmd.unitaryPrice) * tmd.quantity 

		end unitaryPrice,

					

		case 

					when varCurrencyCompras = varCurrencyReporte  then 				

							tmd.unitaryCost * tmd.quantity

					when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost * tmd.quantity					

					else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost		* tmd.quantity				

		end  unitaryCost 

					

					

	from 

		tb_transaction_master tm  					

		inner join tb_transaction_master_detail tmd on 

			tm.companyID = tmd.companyID and 

			tm.transactionID = tmd.transactionID and 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_transaction_causal tc on 

			tm.transactionCausalID = tc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			tm.statusID = ws.workflowStageID 

		inner join tb_user usr on 

			usr.userID = tm.createdBy 

		inner join tb_branch br on 

			usr.locationID = br.branchID 

	where

	  tm.transactionID = 19  and 

		(

			(br.branchID = prBranchID and prBranchID != 0)

			or 

			(prBranchID = 0 )			

		) and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

		tm.isActive = 1 and 

		tmd.isActive = 1 and 

		ws.aplicable = 1;



	

	create table tb_tmp_comision_report (

	  orden varchar(50),

		ventaName varchar(250),		

		amountVenta decimal(19,2),

		amountCost decimal(19,2)

	);

	

	

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost)

	select 

		'001',

		'01) Ventas',

		0,

		sum(c.amountVenta) as Venta 

	from 

		tb_tmp_comision_venta_costo c ;

	

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost)

	select 

		'002',

		'02) Costo',

		0,

		sum(c.amountCost) as Venta 

	from 

		tb_tmp_comision_venta_costo c ;

		

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost) values 

	(

		'003',

		'03) Utilidad',

		0,

		(select x.amountCost from tb_tmp_comision_report x where x.orden = '001')

		-

		(select x.amountCost from tb_tmp_comision_report x where x.orden = '002')

	) ;

	

		

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost)

	select 

		'004',

		'04) Gastos',

		0,

		sum(c.amount) as Venta 

	from 

		tb_tmp_comision_gastos c ;

		

		

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost) values 

	(

		'005',

		'05) Utilidad Neta',

		0,

		(select x.amountCost from tb_tmp_comision_report x where x.orden = '001')

		-

		(select x.amountCost from tb_tmp_comision_report x where x.orden = '002')

		-

		(select x.amountCost from tb_tmp_comision_report x where x.orden = '004')

	) ;

	

	

	

	select 

		u.ventaName as Indicador , 

		u.amountVenta as Valor ,

		u.amountCost as Monto

	from 

		tb_tmp_comision_report u 

	order by 

		u.orden; 

		

		

	drop table tb_tmp_comision_report;

	drop table tb_tmp_comision_venta_costo;

	drop table tb_tmp_comision_gastos;

	drop table tb_tmp_comision_agente;

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_venta_de_producto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=CURRENT_USER PROCEDURE `pr_sales_get_report_venta_de_producto`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas'
BEGIN
	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

	select 
		rx.itemID,
		rx.itemNumber,
		rx.itemName,
		ix.quantity as quantityInAllWarehouse,
		if(fam.publicCatalogDetailID is null, famp.`name` , fam.`name` )  as family,		
		GROUP_CONCAT(DISTINCT (CONCAT(nx.firstName," ",nx.lastName)) SEPARATOR ', <br/>') as provider,
		sum(rx.quantity) as quantity,		
		sum((rx.unitaryCost * rx.quantity)) as cost,
		sum((rx.unitaryPrice * rx.quantity)) as amount,
		sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad ,
		ix.quantity + sum(rx.quantity)  as quantityInicial, 
		IFNULL(
			round(
				(
					sum(rx.quantity) / 
					(ix.quantity + sum(rx.quantity))
				) * 100,
				2
			),
			0
		) as percentageSales	
	from 
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					IFNULL(nat_emp.firstName,'') as employerName,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					l.legalName,
					ci.name as zone,
					i.itemID,
					i.itemNumber,
					i.name as itemName,
					cat.`name` as nameCategory,
					tmd.quantity,
					tm.currencyID,
					tm.exchangeRate,
					tm.createdOn,
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					case 
						when varCurrencyCompras = varCurrencyReporte  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost ,
					IFNULL(tmd.tax1,0) as  iva ,
					IFNULL(amountCommision,0) as amountCommision
				from 
					tb_transaction_master tm 
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					(
						prInventoryCategoryID = 0 
						or 
						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )
					)
				order by 
					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc
		) rx
		inner join  tb_item ix on 
			ix.itemID = rx.itemID  
		left join tb_public_catalog_detail fam on 
			fam.publicCatalogDetailID = ix.familyID 
		left join tb_catalog_item famp on 
			famp.catalogItemID = ix.familyID 
		left join tb_provider_item pix ON pix.itemID = ix.itemID
		LEFT JOIN tb_naturales nx ON nx.entityID = pix.entityID
	group by 
		rx.itemID, 
		rx.itemNumber,
		rx.itemName,
		ix.quantity ,
		fam.`name` ; 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_repor_sales_by_reference` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_sales_get_repor_sales_by_reference`(IN `prCompanyID` int,IN `prTokenID` varchar(50),IN `prUserID` int,IN `prStartOn` date,IN `prEndOn` date,IN `prInventoryCategoryID` int,IN `prWarehouse` int)
BEGIN

	

	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;		

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	select   

		rx.doctor,

	   

		rx.doctor as employerName,

		rx.transactionNumber,		

		rx.tipo,	

		rx.currencyName,	

		rx.customerNumber,							

		rx.legalName,	

		varCurrencyReporte,

		sum((rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity))  as amountConIva					 

	from  

		(   

				select 		

					doctor.`name` as doctor,

					tm.transactionNumber,

					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,

					tc.name as tipo,				

					cus.customerNumber,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,				

					tmd.quantity as quantity,

					cur.`name` as currencyName,	

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(tmd.tax1,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(tmd.tax1,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

					end as iva 																																																										

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_customer cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_transaction_master_info tmi on 

						tm.companyID = tmi.companyID and 

						tm.transactionID = tmi.transactionID and 

						tm.transactionMasterID = tmi.transactionMasterID 

					inner join tb_catalog_item ci on 

						tmi.zoneID = ci.catalogItemID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_catalog_item doctor on 

						doctor.catalogItemID = tmi.mesaID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

					left join tb_naturales nat_emp on 

						nat_emp.entityID = tm.entityIDSecondary 

				where  					

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 and 

					(

						prInventoryCategoryID = 0 

						or 

						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

					)  and 

					(

						prWarehouse = 0 

						or 

						(

							prWarehouse != 0 and

							tm.sourceWarehouseID =  prWarehouse

					  )

					) 					

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc			

		) rx

group by

		rx.doctor,

    rx.transactionNumber,

    rx.employerName,

    rx.tipo,

    rx.customerNumber,

    rx.currencyName,

    rx.legalName,

		varCurrencyReporte		

ORDER BY 

		rx.doctor,  rx.transactionNumber;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_transaction_master_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_transaction_master_detail`(IN `prCompanyID` int,IN `prTransactionID` int,IN `prTransactionMasterID` int)
BEGIN



	IF prTransactionID = 16 THEN 	 

				SELECT 

					i.itemNumber,

					i.`name` as itemName,

					td.quantity 

				FROM 

					tb_transaction_master_detail td 

					inner join tb_item i on 

						i.itemID = td.componentItemID 

				WHERE 

					td.transactionMasterID = prTransactionMasterID and 

					td.isActive = 1

				ORDER BY 

						i.`name`;

	END IF ;	

	



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_transaction_report_registradas_anuladas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_transaction_report_registradas_anuladas`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de Transacciones Anuladas'
BEGIN



	SELECT 

		c.createdOn , 

		c.transactionNumber,

		ws.`name` as statusName,

		c.amount as monto ,

		t.`name` as transactionName 

	from 

		tb_transaction_master c 

		inner join tb_transaction t on 

			c.transactionID = t.transactionID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

	where 

		(

			c.isActive = 0 

			or 	

			ws.aplicable = 0 

		)

		and 

		c.companyID = prCompanyID 

		and c.createdOn between prStartOn  and concat(prEndOn,' 23:59:59'); 

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_transaction_revert` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_transaction_revert`(IN `prCompanyID` INT, IN `prTransactionIDOriginal` INT, IN `prTransactionMasterIDOriginal` BIGINT, IN `prTransactionIDRevert` INT, IN `prTransactionMasterIDRevert` BIGINT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento que se utiliza para revertir una transaccion'
BEGIN

	DECLARE transactionNumber VARCHAR(50) DEFAULT '';
	DECLARE transactionNumberOriginal VARCHAR(50) DEFAULT '';
	DECLARE statusIDTransactionInit INT DEFAULT 0;
	DECLARE statusIDTransactionAnulada INT DEFAULT 0;
	DECLARE branchID INT DEFAULT 0;
	DECLARE transactionInfoNumberOriginal VARCHAR(50) DEFAULT '';
	DECLARE transactionInfoNumber VARCHAR(50) DEFAULT '';
	DECLARE prEntityID INT DEFAULT 0;
	DECLARE prOldPoints INT DEFAULT 0;
	DECLARE prNewPoints INT DEFAULT 0;
	DECLARE prTransPoints INT DEFAULT 0;

	SET transactionNumberOriginal = (
			SELECT tm.transactionNumber 
			FROM tb_transaction_master tm 
			where 
				tm.companyID = prCompanyID and 
				tm.transactionID = prTransactionIDOriginal 
				and tm.transactionMasterID = prTransactionMasterIDOriginal limit 1);

	SET branchID = (
		SELECT tm.branchID 
		FROM tb_transaction_master tm 
		where 
			tm.companyID = prCompanyID and 
			tm.transactionID = prTransactionIDOriginal and 
			tm.transactionMasterID = prTransactionMasterIDOriginal limit 1);
			
	SET transactionInfoNumberOriginal = COALESCE((
		SELECT CAST(tmi.transactionMasterInfoID AS UNSIGNED)
		FROM tb_transaction_master_info tmi
		WHERE tmi.transactionMasterID = prTransactionMasterIDOriginal
		LIMIT 1
		), 0);

	CALL pr_core_get_parameter_value (prCompanyID,'INVOICE_BILLING_ANULADAS',statusIDTransactionAnulada);	
	CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_billing_revertion","statusID",statusIDTransactionInit );		
	CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_billing_revertion',branchID,0,transactionNumber);							 	

	INSERT INTO tb_transaction_master (	
		companyID,transactionID,transactionNumber,branchID,transactionCausalID,entityID,
		transactionOn,statusIDChangeOn,componentID,note,sign,currencyID,currencyID2,
		exchangeRate,reference1,reference2,reference3,reference4,statusID,amount,
		isApplied,journalEntryID,classID,areaID,priorityID,sourceWarehouseID,
		targetWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive,
		discount,subAmount,tax1,tax2,tax3,tax4 , entityIDSecondary, 
		transactionOn2,descriptionReference,isTemplate,periodPay, nextVisit,numberPhone,notificationID,printerQuantity,dayExcluded 		
	)	
	select 
		tm.companyID,prTransactionIDRevert,transactionNumber,tm.branchID,tm.transactionCausalID,tm.entityID,
		CURRENT_DATE(),NOW(),tm.componentID,tm.note,(tm.sign * -1),tm.currencyID,tm.currencyID2,
		tm.exchangeRate,prTransactionIDOriginal,prTransactionMasterIDOriginal,transactionNumberOriginal,tm.reference4,statusIDTransactionInit,tm.amount,
		1,0,tm.classID,tm.areaID,tm.priorityID,tm.targetWarehouseID,tm.sourceWarehouseID,
		tm.createdBy,tm.createdAt,tm.createdOn,tm.createdIn,tm.isActive,
		tm.discount,tm.subAmount,tm.tax1,tm.tax2,tm.tax3,tm.tax4 , tm.entityIDSecondary   ,
		tm.transactionOn2,tm.descriptionReference,tm.isTemplate,tm.periodPay, tm.nextVisit,tm.numberPhone,tm.notificationID,tm.printerQuantity,tm.dayExcluded 		
	from 
		tb_transaction_master tm
	where
		tm.companyID = prCompanyID and 
		tm.transactionID = prTransactionIDOriginal and 
		tm.transactionMasterID = prTransactionMasterIDOriginal;
	

	SET prTransactionMasterIDRevert = LAST_INSERT_ID();	

	INSERT INTO tb_transaction_master_detail (
			companyID,transactionID,transactionMasterID,componentID,componentItemID,
			promotionID,amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,reference1,
			reference2,reference3,catalogStatusID,inventoryStatusID,isActive,quantityStock,
			quantiryStockInTraffic,quantityStockUnaswared,remaingStock,expirationDate,
			inventoryWarehouseSourceID,inventoryWarehouseTargetID,tax1,tax2,tax3,tax4,
			reference4,reference5,reference6,reference7,
			descriptionReference,exchangeRateReference,lote,itemFormulatedApplied,typePriceID,skuCatalogItemID,
			skuQuantity,skuQuantityBySku,skuFormatoDescription,itemNameLog,amountCommision,itemNameDescriptionLog 
	)
	SELECT 
			tm.companyID,prTransactionIDRevert,prTransactionMasterIDRevert,tm.componentID,tm.componentItemID,
			tm.promotionID,tm.amount,tm.cost,tm.quantity,tm.discount,tm.unitaryAmount,tm.unitaryCost,tm.unitaryPrice,tm.reference1,
			tm.reference2,tm.reference3,tm.catalogStatusID,tm.inventoryStatusID,tm.isActive,tm.quantityStock,
			tm.quantiryStockInTraffic,tm.quantityStockUnaswared,tm.remaingStock,tm.expirationDate,
			tm.inventoryWarehouseTargetID,tm.inventoryWarehouseSourceID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,
			tm.reference4,tm.reference5,tm.reference6,tm.reference7,
			tm.descriptionReference,tm.exchangeRateReference,tm.lote,tm.itemFormulatedApplied,tm.typePriceID,tm.skuCatalogItemID,
			tm.skuQuantity,tm.skuQuantityBySku,tm.skuFormatoDescription,tm.itemNameLog,tm.amountCommision,tm.itemNameDescriptionLog 
	FROM 
		tb_transaction_master_detail tm 
	WHERE 
		tm.companyID = prCompanyID and
		tm.transactionID = prTransactionIDOriginal and 
		tm.transactionMasterID = prTransactionMasterIDOriginal; 
	
	
	INSERT INTO tb_transaction_master_concept (
		companyID,transactionID,transactionMasterID,
		componentID,componentItemID,conceptID,value,currencyID,exchangeRate )
	select 
		tm.companyID,prTransactionIDRevert,prTransactionMasterIDRevert,
		tm.componentID,tm.componentItemID,tm.conceptID,tm.value,tm.currencyID,tm.exchangeRate 
	from 
		tb_transaction_master_concept tm
	WHERE 
		tm.companyID = prCompanyID and
		tm.transactionID = prTransactionIDOriginal and 
		tm.transactionMasterID = prTransactionMasterIDOriginal; 	
  
  
	IF transactionInfoNumberOriginal > 0
	THEN 
  
		/* Crear transaction_master_info_reverse	al anualr una factura*/
		INSERT INTO tb_transaction_master_info ( companyID, transactionID, transactionMasterID, zoneID, routeID, mesaID, referenceClientName, 
		referenceClientIdentifier, changeAmount, receiptAmountPoint, receiptAmount, receiptAmountDol, reference1, reference2, receiptAmountBank, receiptAmountBankID, 
		receiptAmountBankReference, receiptAmountBankDol, receiptAmountBankDolID, receiptAmountBankDolReference, receiptAmountCard, receiptAmountCardBankID, 
		receiptAmountCardBankReference, receiptAmountCardDol, receiptAmountCardBankDolID, receiptAmountCardBankDolReference)
		SELECT tmi.companyID, prTransactionIDRevert, prTransactionMasterIDRevert, tmi.zoneID, tmi.routeID, tmi.mesaID, tmi.referenceClientName, 
		tmi.referenceClientIdentifier, tmi.changeAmount, tmi.receiptAmountPoint, tmi.receiptAmount, tmi.receiptAmountDol, tmi.reference1, tmi.reference2, tmi.receiptAmountBank, 
		tmi.receiptAmountBankID, tmi.receiptAmountBankReference, tmi.receiptAmountBankDol, tmi.receiptAmountBankDolID, tmi.receiptAmountBankDolReference, tmi.receiptAmountCard, tmi.receiptAmountCardBankID, 
		tmi.receiptAmountCardBankReference, tmi.receiptAmountCardDol, tmi.receiptAmountCardBankDolID, tmi.receiptAmountCardBankDolReference 
		FROM tb_transaction_master_info tmi
		WHERE tmi.transactionMasterInfoID = transactionInfoNumberOriginal limit 1;
    
    
		/*Se hace la reversion de puntos al anular una factura*/
		SET prEntityID = (SELECT tm.entityID 
		FROM tb_transaction_master tm 
		where tm.companyID = prCompanyID and tm.transactionMasterID = prTransactionMasterIDOriginal limit 1);
        
		
		SET prTransPoints = (SELECT 
		 tmi.receiptAmountPoint
		FROM tb_transaction_master_info tmi
		WHERE tmi.transactionMasterID = prTransactionMasterIDOriginal limit 1);
    
		SET prOldPoints = (SELECT c.balancePoint FROM tb_customer c WHERE c.entityID = prEntityID);
		SET prNewPoints = prOldPoints+prTransPoints;
    
		UPDATE tb_customer SET balancePoint = prNewPoints WHERE entityID = prEntityID;  
    
	END IF;

	CALL pr_inventory_calculate_kardex_new_input (prCompanyID,prTransactionIDRevert,prTransactionMasterIDRevert);

	UPDATE tb_transaction_master set 
		statusID = statusIDTransactionAnulada 
	where 
			companyID = prCompanyID and 
			transactionID = prTransactionIDOriginal and 
			transactionMasterID = prTransactionMasterIDOriginal;
      
END
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_zerror_reparar_kardex` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_zerror_reparar_kardex`(IN `prItemID` INT  , IN prWarehouseID INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Reparar kardex de un producto en especifico, en una bodega '
BEGIN



	declare minDocumentID_ int default 0;

	declare maxDocumentID_ int default 0;

	declare balanceQuantity decimal(19,2) default 0;

	declare balanceTotal decimal(19,2) default 0;

	

	

	CREATE TABLE tmp_reparar_t (		

				kardexID int ,

				inicial decimal(19,2),

				cantidad decimal(19,2),

				final decimal(19,2),

				sign decimal(19,2),

				transactionMasterID int

	); 

	

  

	INSERT INTO tmp_reparar_t (kardexID,inicial,cantidad,final,sign,transactionMasterID) 

	select 

			k.kardexID,

			k.oldQuantityWarehouse,

			k.transactionQuantity,

			k.newQuantityWarehouse ,

			k.sign,

			k.transactionMasterID 

	from 

			tb_kardex k 

	where 

			k.itemID = prItemID and 

			k.warehouseID = prWarehouseID ;

			



		

			

	set minDocumentID_ = (select min(kardexID) from tmp_reparar_t);

	set maxDocumentID_ = (select max(kardexID) from tmp_reparar_t);

		

	while minDocumentID_ <= maxDocumentID_ and minDocumentID_ is not null do 

			

		update tb_kardex set oldQuantityWarehouse = balanceQuantity where kardexID = minDocumentID_;

		

		

	  set balanceQuantity = balanceQuantity + (

				select k.cantidad * k.sign from tmp_reparar_t k where k.kardexID =  minDocumentID_ 

		);		

		

		

		update tb_kardex set newQuantityWarehouse = balanceQuantity where kardexID = minDocumentID_;

		

		

		set minDocumentID_ 	= (select min(kardexID) from tmp_reparar_t where kardexID > minDocumentID_);

	end while;

	

	

	update tb_item_warehouse set quantity = balanceQuantity 

	where 

		itemID = prItemID and 

		warehouseID = prWarehouseID;

		

	

	set balanceTotal = 0;

	set balanceTotal = (select sum(c.quantity) from tb_item_warehouse c where c.itemID = prItemID);

	update tb_item set quantity = balanceTotal where itemID = prItemID;

	

	

	

	

	set balanceQuantity = 0;

	delete from tmp_reparar_t;

	

	INSERT INTO tmp_reparar_t (kardexID,inicial,cantidad,final,sign) 

	select 

			k.kardexID,

			k.oldQuantity,

			k.transactionQuantity,

			k.newQuantity ,

			k.sign

	from 

			tb_kardex k

	where 

			k.itemID = prItemID;

			

			

	set minDocumentID_ = (select min(kardexID) from tmp_reparar_t);

	set maxDocumentID_ = (select max(kardexID) from tmp_reparar_t);

		

	while minDocumentID_ <= maxDocumentID_ and minDocumentID_ is not null do 

			

		update tb_kardex set oldQuantity = balanceQuantity where kardexID = minDocumentID_;

		

		

	  set balanceQuantity = balanceQuantity + (

				select k.cantidad * k.sign from tmp_reparar_t k where k.kardexID =  minDocumentID_ 

		);		

		

		

		update tb_kardex set newQuantity = balanceQuantity where kardexID = minDocumentID_;

		

		

		set minDocumentID_ 	= (select min(kardexID) from tmp_reparar_t where kardexID > minDocumentID_);

	end while;

	

	

  DROP TABLE tmp_reparar_t;	

	select 'success' as mensaje; 

	

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_zerror_reparar_tabla_amortization_dias_para_gym_raptor` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_zerror_reparar_tabla_amortization_dias_para_gym_raptor`(IN `prItemID` INT , IN `prDay` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Reparar facturas, que se facturaron como semanales, y la frecuencia de pago quedo mensual'
BEGIN



	declare minDocumentID_ int default 0;

	declare maxDocumentID_ int default 0;

	declare minAmorID_ int default 0;

	declare maxAmorID_ int default 0;

	declare dayX datetime ;

	declare countar int default 0;

	

	

		CREATE TEMPORARY TABLE tmp_reparar (		

				entityID int ,

				customerCreditDocumentID INT,

				creditAmortizationID INT,

				creditAmortizationIDMax INT,

				dateApply datetime

			); 

	

	

	 INSERT INTO tmp_reparar (entityID,customerCreditDocumentID,creditAmortizationID,creditAmortizationIDMax,dateApply) 

		select 

			ccd.entityID,

			ccd.customerCreditDocumentID,		

			min(am.creditAmortizationID) as creditAmortizationID ,

			max(am.creditAmortizationID) as creditAmortizationIDMax ,

			min(am.dateApply) as dateApply 

		from 

			tb_transaction_master tm 

			inner join tb_transaction_master_detail td on 

				tm.transactionMasterID = td.transactionMasterID 

			inner JOIN tb_customer_credit_document ccd on 

				ccd.documentNumber = tm.transactionNumber 

			inner join tb_customer_credit_amoritization am on 

				am.customerCreditDocumentID = ccd.customerCreditDocumentID 

		where

			td.componentItemID = prItemID 

		group by 

			ccd.customerCreditDocumentID,

			tm.transactionMasterID,

			tm.transactionNumber;

			

			

	set minDocumentID_ = (select min(customerCreditDocumentID) from tmp_reparar);

	set maxDocumentID_ = (select max(customerCreditDocumentID) from tmp_reparar);

		

	while minDocumentID_ <= maxDocumentID_ and minDocumentID_ is not null do 

		set dayX 						= (select min(dateApply) from tmp_reparar where customerCreditDocumentID = minDocumentID_);

		set minAmorID_ = (select min(creditAmortizationID) from tmp_reparar where customerCreditDocumentID = minDocumentID_);

		set maxAmorID_ = (select max(creditAmortizationIDMax) from tmp_reparar where customerCreditDocumentID = minDocumentID_);

		set countar = 0;

		

		

		while minAmorID_ <= maxAmorID_ and minAmorID_ is not null do 

		

			

			if countar > 0 then 

				set dayX 	= date_add(dayX,interval prDay day);

				update tb_customer_credit_amoritization set dateApply = dayX where creditAmortizationID = minAmorID_;

			end if;

			

			set countar = countar + 1;

			set minAmorID_ 	= (select min(creditAmortizationID) from tb_customer_credit_amoritization 

					where customerCreditDocumentID = minDocumentID_ and creditAmortizationID > minAmorID_ );

		end while;

		

		

		set minDocumentID_ 	= (select min(customerCreditDocumentID) from tmp_reparar where customerCreditDocumentID > minDocumentID_);

	end while;

	

	select * from tmp_reparar;

	drop table tmp_reparar;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_zerror_trasladar_todo_a_bodega_despacho` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_zerror_trasladar_todo_a_bodega_despacho`()
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Trasladar todo a bodega despacho .. '
BEGIN



		update tb_item_warehouse, tb_item 

			set tb_item_warehouse.quantity = tb_item.quantity

		where 

			tb_item_warehouse.itemID = tb_item.itemID;

			

		update tb_item_warehouse set quantity = 0 where warehouseID != 4; 

		

		update tb_transaction_master set tb_transaction_master.targetWarehouseID = 4; 

		

		update tb_kardex set warehouseID = 4; 

		

	END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `_Navicat_Temp_Stored_Proc` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `_Navicat_Temp_Stored_Proc`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prCreatedIn` VARCHAR(50), IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prCodeError` INT, OUT `prMessageResult` VARCHAR(250))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Procedimiento para Cerrar un Ciclo Contable'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 4;		

	DECLARE workflowStageClosedPeriod_ INT DEFAULT 0;

	DECLARE workflowStageClosedCycle_ INT DEFAULT 0;

	DECLARE journalTypeIDCierre_ INT DEFAULT 0;	

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE totalDebit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE totalCredit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;	

	DECLARE workflowStageInitOfJournal_ INT DEFAULT 0;	

	DECLARE oldCycleID_ INT DEFAULT 0;

	DECLARE nextCycleID_ INT DEFAULT 0;

	DECLARE nextPeriodID_ INT DEFAULT 0;

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE accountIDResult_ INT DEFAULT 0;

	DECLARE journalEntryID_ INT DEFAULT 0;

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE journalNumber_ VARCHAR(50);

	DECLARE companyName_ VARCHAR(50);

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE accountTypeResult VARCHAR(150);



		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",accountTypeResult);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_JOURNALTYPE_CLOSED",journalTypeIDCierre_);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",workflowStageClosedCycle_);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",workflowStageClosedPeriod_);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

		SET companyName_ 		= (select name from tb_company where companyID = prCompanyID);	

		CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_journal_entry","statusID",workflowStageInitOfJournal_ );	

		CALL pr_core_get_next_number (prCompanyID,"tb_journal_entry",prBranchID,journalTypeIDCierre_,journalNumber_);		

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_ACUMULATE',accountNumber_);

		

	SET accountIDResult_ = (

		SELECT accountID FROM tb_account where isActive = 1 and 

		companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1

	);	

		

	SET oldCycleID_ 		= (

		SELECT 

			cc.componentCycleID 

		FROM 	

			tb_accounting_cycle cc inner join 

			tb_accounting_period cp on 

			cp.companyID = cc.companyID and 	

			cp.componentID = cc.componentID and 

			cp.componentPeriodID = cc.componentPeriodID 

		WHERE 	

			cc.companyID = prCompanyID AND 	

			cc.isActive = 1 and 	

			cp.isActive = 1 and 	

			cc.componentID = componentID_ AND 	

			cc.endOn < (		

						select 			

							cc2.startOn  		

						from 			

							tb_accounting_cycle cc2 		

						where 			

							cc2.componentCycleID = prCycleID 	

			) 

		ORDER BY 	

			cc.endOn DESC LIMIT 1 

		);

			

		SET nextCycleID_  	= (SELECT cc.componentCycleID FROM 	tb_accounting_cycle cc inner join tb_accounting_period cp on cp.companyID = cc.companyID and 	cp.componentID = cc.componentID and cp.componentPeriodID = cc.componentPeriodID WHERE 	cc.companyID = prCompanyID AND 	cc.isActive = 1 and 	cp.isActive = 1 and 	cc.componentID = componentID_ AND 	cc.startOn > (		select 			cc2.endOn  		from 			tb_accounting_cycle cc2 		where 			cc2.componentCycleID = prCycleID 	) ORDER BY 	cc.startOn ASC LIMIT 1 );	

	SET nextPeriodID_ 	= (SELECT componentPeriodID FROM tb_accounting_cycle WHERE componentCycleID = nextCycleID_);	

		SET totalDebit_ 	= (SELECT SUM(ab.debit) from tb_accounting_balance ab inner join tb_account a on ab.companyID = a.companyID and ab.accountID = a.accountID  where ab.isActive = 1 and ab.companyID = prCompanyID and ab.componentID = componentID_ and ab.componentPeriodID = prPeriodID and ab.componentCycleID = prCycleID and a.isActive = 1 and a.parentAccountID IS NULL);

	SET totalCredit_	= (SELECT SUM(ab.credit) from tb_accounting_balance  ab inner join tb_account a on ab.companyID = a.companyID and ab.accountID = a.accountID where ab.isActive = 1 and ab.companyID = prCompanyID and ab.componentID = componentID_ and ab.componentPeriodID = prPeriodID and ab.componentCycleID = prCycleID and a.isActive = 1 and a.parentAccountID IS NULL );

		IF ((oldCycleID_ IS NOT NULL ) AND ((SELECT componentCycleID FROM tb_accounting_cycle where componentCycleID = oldCycleID_ and statusID <>  workflowStageClosedCycle_) IS NOT NULL ) ) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ANTERIOR DEBE DE  ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'El cilo anterior debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;	

		IF nextCycleID_ IS NULL THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'NO PUEDE CERRAR EL CICLO, NO EXISTE UN SIGUIENTE CICLO CONTABLE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'No puede cerrar el ciclo, no existe un siguiente ciclo contable',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

		IF ((prCycleID IS NOT NULL) AND ((SELECT componentCycleID FROM tb_accounting_cycle where componentCycleID = prCycleID and statusID =  workflowStageClosedCycle_) IS NOT NULL )) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ACTUAL NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'El ciclo actual no debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

		IF ((nextCycleID_ IS NOT NULL) AND ((SELECT componentCycleID FROM tb_accounting_cycle where componentCycleID = nextCycleID_ and statusID =  workflowStageClosedCycle_) IS NOT NULL )) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO SIGUIENTE NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'El ciclo siguiente no debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

		IF totalDebit_ <> totalCredit_ THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'LOS MOVIMIENTOS DE CICLO NO SON EQUIVALENTES, DEBITOS Y CREDITOS DIFIEREN EN IMPORTE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'Los movimientos del ciclo no son equivalente, debitos y creditos difieren en importe',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

	

	

		CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

		DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

	

		CALL `pr_accounting_mayorizate_cycle` (prCompanyID , prBranchID,prLoginID,nextPeriodID_ , nextCycleID_ ,resultTemp_); 	

	

		CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

		CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);

	

	

		IF nextPeriodID_ = prPeriodID THEN			

				UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 		= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;		

	END IF;

	

	

		IF nextPeriodID_ <> prPeriodID THEN	

				CALL `pr_accounting_mayorizate_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0, 0, 0, utilityValue_);

		

				UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 			= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;	 	

			

		INSERT INTO tb_journal_entry (companyID,journalNumber,journalDate,tb_exchange_rate,createdOn,createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,journalTypeID,currencyID,accountingCycleID,entryName)

		VALUES(prCompanyID,journalNumber_,CURDATE(),exchangeRate_,CURRENT_TIMESTAMP(),'::1',prBranchID,prLoginID,1,0,workflowStageInitOfJournal_,CONCAT(CAST(utilityValue_ AS DECIMAL(19,2)),'/UTILIDAD'),journalTypeIDCierre_,currencyID_,prCycleID,'APP-CIERRE');		

		SET journalEntryID_ = LAST_INSERT_ID();

	

			

				INSERT INTO tb_journal_entry_detail (journalEntryID,companyID,accountID,isActive,classID,debit,credit,note,isApplied,branchID,tb_exchange_rate) 

		SELECT 

			journalEntryID_ as journalEntryID,

			prCompanyID as companyID,

			a.accountID,

			1 as isActive,

			0 as classID,

			CASE 

				WHEN att.naturaleza = 'C' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'D' and t.balanceEnd < 0 then 

					t.balanceEnd

			END as debit,

			CASE 

				WHEN att.naturaleza = 'D' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'C' and t.balanceEnd < 0 THEN 

					t.balanceEnd

			END as credit,

			'' as note,

			1 as isApplied, 

			prBranchID as branchID,

			exchangeRate_ as exchangeRate

		FROM 

			tb_accounting_balance_temp t

			inner join tb_account a on 

				t.accountID = a.accountID 

			inner join tb_account_type att on 

				a.accountTypeID = att.accountTypeID 

		WHERE

			t.companyID 	= prCompanyID AND  

			t.branchID 		= prBranchID AND 

			t.loginID 		= prLoginID AND

			a.isOperative 	= 1 and 

			t.balanceEnd 	<> 0 and 

			a.accountNumber REGEXP accountTypeResult

		ORDER BY 

			a.accountNumber; 

			 

				INSERT INTO tb_journal_entry_detail (journalEntryID,companyID,accountID,isActive,classID,debit,credit,note,isApplied,branchID,tb_exchange_rate) 

		VALUES (

			journalEntryID_,

			prCompanyID ,

			accountIDResult_ , 

			1,

			0, 

			IF(utilityValue_ < 0 , utilityValue_ , 0) ,

			IF(utilityValue_ > 0 , utilityValue_ , 0) ,

			'' ,

			1 , 

			prBranchID ,

			exchangeRate_ );

			

				

				UPDATE tb_accounting_balance,tb_account 

			set tb_accounting_balance.balance = 0 

		where

			tb_accounting_balance.companyID 			= tb_account.companyID and 

			tb_accounting_balance.accountID 			= tb_account.accountID and 

			tb_accounting_balance.companyID 			= prCompanyID and 

			tb_accounting_balance.componentPeriodID 	= nextPeriodID_ and 

			tb_accounting_balance.componentCycleID 	= nextCycleID_ AND 

			tb_accounting_balance.branchID 			= prBranchID AND 

			tb_account.accountNumber REGEXP accountTypeResult;

		

	END IF;	

		

	

	

		DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

		

		IF nextPeriodID_ <> prPeriodID THEN	

				UPDATE tb_accounting_period set statusID = workflowStageClosedPeriod_ WHERE componentPeriodID = prPeriodID;

				UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

		ELSE

				UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

	END IF;

	

	SET prCodeError 	= 0;

	SET prMessageResult = 'SUCCESS';

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',0,'Success',CURRENT_TIMESTAMP());

	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_by_payment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=CURRENT_USER PROCEDURE `pr_sales_get_report_sales_by_payment`(IN `prCompanyID` INT, 	IN `prUserID` INT, IN `prTokenID` VARCHAR(50) , IN `prDateTimeStart` VARCHAR(50),   IN `prDateTimeFinish` VARCHAR(50) )
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
    COMMENT 'Detalle de ventas por metodo de pago'
BEGIN

	DECLARE vStart DATETIME;
	DECLARE vEnd DATETIME;
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;
	DECLARE convert_ VARCHAR(50);	
	DECLARE prFlavorID INT DEFAULT 0;
	
 

	SET vStart = STR_TO_DATE(prDateTimeStart, '%Y-%m-%d %H:%i:%s');
	SET vEnd   = STR_TO_DATE(prDateTimeFinish, '%Y-%m-%d %H:%i:%s');
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_); 
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);
	SET prFlavorID 								= (SELECT flavorID FROM tb_company c where c.companyID = prCompanyID);



	SELECT 
			COALESCE(b.name, 
					CASE 
							WHEN tipo = 'EfectivoCordoba' THEN 'EFECTIVO CORDOBA'
							WHEN tipo = 'EfectivoDolar'   THEN 'EFECTIVO DOLAR'
							WHEN tipo = 'Puntos'          THEN 'PUNTOS'
					END
			) AS Banco,

			ROUND(SUM(CASE WHEN tipo = 'TransferenciaCordoba' THEN monto ELSE 0 END),2) AS `Transferencia Cordoba`,
			ROUND(SUM(CASE WHEN tipo = 'TransferenciaDolar'   THEN monto ELSE 0 END),2) AS `Transferencia Dólar`,
			ROUND(SUM(CASE WHEN tipo = 'TarjetaCordoba'       THEN monto ELSE 0 END),2) AS `Tarjeta Cordoba`,
			ROUND(SUM(CASE WHEN tipo = 'TarjetaDolar'         THEN monto ELSE 0 END),2) AS `Tarjeta Dólar`,
			ROUND(SUM(CASE WHEN tipo = 'EfectivoCordoba'      THEN monto ELSE 0 END),2) AS `Efectivo Cordoba`,
			ROUND(SUM(CASE WHEN tipo = 'EfectivoDolar'        THEN monto ELSE 0 END),2) AS `Efectivo Dólar`,
			ROUND(SUM(CASE WHEN tipo = 'Puntos'               THEN monto ELSE 0 END),2) AS Puntos,

			-- Totales por fila
			ROUND(SUM(CASE WHEN moneda = 'Cordoba' THEN monto ELSE 0 END),2) AS `Total Cordoba`,
			ROUND(SUM(CASE WHEN moneda = 'Dolar'   THEN monto ELSE 0 END),2) AS `Total Dólar`

	FROM (
			-- Normalizamos todos los pagos
			SELECT tmi.transactionMasterID, CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmount, tmi.receiptAmountDol,  'Amount'), DECIMAL(10,2))  AS monto, 'EfectivoCordoba' AS tipo, 'Cordoba' AS moneda, NULL AS bankID
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
			UNION ALL
			SELECT tmi.transactionMasterID, CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmount, tmi.receiptAmountDol,  'AmountExt'), DECIMAL(10,2))  AS monto,    'EfectivoDolar',   'Dolar',   NULL AS bankID
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
			UNION ALL
			SELECT tmi.transactionMasterID, CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmountCard, tmi.receiptAmountCardDol,  'Amount'), DECIMAL(10,2))  AS monto,   'TarjetaCordoba',  'Cordoba', tmi.receiptAmountCardBankID
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
			UNION ALL
			SELECT tmi.transactionMasterID, CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmountCard, tmi.receiptAmountCardDol,  'AmountExt'), DECIMAL(10,2))  AS monto, 'TarjetaDolar',    'Dolar',   tmi.receiptAmountCardBankDolID
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
			UNION ALL
			SELECT tmi.transactionMasterID, CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmountBank, tmi.receiptAmountBankDol,  'Amount'), DECIMAL(10,2))  AS monto,   'TransferenciaCordoba','Cordoba', tmi.receiptAmountBankID
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
			UNION ALL
			SELECT tmi.transactionMasterID, CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmountBank, tmi.receiptAmountBankDol,  'AmountExt'), DECIMAL(10,2))  AS monto,'TransferenciaDolar', 'Dolar',   tmi.receiptAmountBankDolID
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
			UNION ALL
			SELECT tmi.transactionMasterID, tmi.receiptAmountPoint, 'Puntos', 'Cordoba', NULL
			FROM tb_transaction_master_info tmi
			INNER JOIN tb_transaction_master tm
			ON tm.transactionMasterID = tmi.transactionMasterID
	) pagos
	INNER JOIN tb_transaction_master tm 
			ON tm.transactionMasterID = pagos.transactionMasterID
	LEFT JOIN tb_bank b 
			ON b.bankID = pagos.bankID

	WHERE tm.companyID = prCompanyID and tm.isApplied = 1 
		AND tm.isActive = 1 
		AND tm.transactionID = 19
		AND tm.createdOn BETWEEN vStart and vEnd

	GROUP BY Banco WITH ROLLUP;
	
END;;	
DELIMITER ;

--
-- Final view structure for view `vw_contabilidad_comprobantes`
--

/*!50001 DROP VIEW IF EXISTS `vw_contabilidad_comprobantes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_contabilidad_comprobantes` AS select `je`.`journalNumber` AS `CodigoComprobante`,`je`.`journalDate` AS `FechaComprobante`,`je`.`tb_exchange_rate` AS `TipoCambioComprobante`,`ws`.`name` AS `EstadoComprobante`,`je`.`debit` AS `DebitoComprobante`,`je`.`credit` AS `CrditoComprobante`,`ci`.`name` AS `TipoComprobante`,`cur`.`simbol` AS `MonedaComprobante`,`cc`.`description` AS `CentroCostoCuenta`,concat('\'',`a`.`accountNumber`) AS `CodigoCuenta`,`a`.`name` AS `NombreCuenta`,`jed`.`debit` AS `DebitoCuenta`,`jed`.`credit` AS `CreditoCuenta`,`act`.`name` AS `TipoCuenta`,`je`.`entryName` AS `BeneficiarioComprobante`,`je`.`note` AS `NotaComprobante` from (((((((`tb_journal_entry` `je` join `tb_journal_entry_detail` `jed` on(`je`.`journalEntryID` = `jed`.`journalEntryID`)) join `tb_account` `a` on(`a`.`accountID` = `jed`.`accountID`)) join `tb_account_type` `act` on(`act`.`accountTypeID` = `a`.`accountTypeID`)) join `tb_center_cost` `cc` on(`cc`.`classID` = `a`.`classID`)) join `tb_workflow_stage` `ws` on(`je`.`statusID` = `ws`.`workflowStageID`)) join `tb_catalog_item` `ci` on(`ci`.`catalogItemID` = `je`.`journalTypeID`)) join `tb_currency` `cur` on(`cur`.`currencyID` = `je`.`currencyID`)) where `je`.`isActive` = 1 and `jed`.`isActive` = 1 order by `je`.`journalDate` desc,`je`.`createdOn` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_cxc_customer_list_real_estate`
--

/*!50001 DROP VIEW IF EXISTS `vw_cxc_customer_list_real_estate`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_cxc_customer_list_real_estate` AS select `c`.`entityID` AS `entityID`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`customerNumber`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`customerNumber`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`customerNumber`,'</span>') else `c`.`customerNumber` end AS `Codigo`,`c`.`dateContract` AS `Contacto`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',cast(`c`.`modifiedOn` as date),'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',cast(`c`.`modifiedOn` as date),'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',cast(`c`.`modifiedOn` as date),'</span>') else cast(`c`.`modifiedOn` as date) end AS `Modificacion`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`nat`.`firstName`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`nat`.`firstName`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`nat`.`firstName`,'</span>') else `nat`.`firstName` end AS `Cliente`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`sex`.`name`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`sex`.`name`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`sex`.`name`,'</span>') else `sex`.`name` end AS `Sexo`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',ifnull((select min(`ue`.`email`) from `tb_entity_email` `ue` where `ue`.`entityID` = `c`.`entityID`),''),'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',ifnull((select min(`ue`.`email`) from `tb_entity_email` `ue` where `ue`.`entityID` = `c`.`entityID`),''),'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',ifnull((select min(`ue`.`email`) from `tb_entity_email` `ue` where `ue`.`entityID` = `c`.`entityID`),''),'</span>') else ifnull((select min(`ue`.`email`) from `tb_entity_email` `ue` where `ue`.`entityID` = `c`.`entityID`),'') end AS `Email`,case when `ws`.`name` = 'ACTIVO' then concat(' <span style="font-weight: bold;color:#000000" >',`ws`.`name`,'</span>') when `ws`.`name` = 'INACTIVO' then concat(' <span style="font-weight: bold;color:red" >',`ws`.`name`,'</span>') when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`ws`.`name`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`ws`.`name`,'</span>') when `ws`.`name` = 'EN CONTACTACION' then concat(' <span style="font-weight: bold;color:#000000" >',`ws`.`name`,'</span>') when `ws`.`name` = 'SEGUIMIENTO' then concat(' <span style="font-weight: bold;color:yellow" >',`ws`.`name`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`ws`.`name`,'</span>') else concat(' <span style="font-weight: bold;color:" >',`ws`.`name`,'</span>') end AS `Estado`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`clas`.`name`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`clas`.`name`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`clas`.`name`,'</span>') else `clas`.`name` end AS `Clasificacion`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`cat`.`name`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`cat`.`name`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`cat`.`name`,'</span>') else `cat`.`name` end AS `Categoria`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`budget`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`budget`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`budget`,'</span>') else `c`.`budget` end AS `Presupuesto`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`phoneNumber`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`phoneNumber`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`phoneNumber`,'</span>') else `c`.`phoneNumber` end AS `Telefono`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`location`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`location`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`location`,'</span>') else `c`.`location` end AS `Ubicacion Interes`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',ifnull(`agent`.`firstName`,'N/D'),'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',ifnull(`agent`.`firstName`,'N/D'),'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',ifnull(`agent`.`firstName`,'N/D'),'</span>') else ifnull(`agent`.`firstName`,'N/D') end AS `Agente`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`reference1`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference1`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference1`,'</span>') else `c`.`reference1` end AS `Encuentra 24`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`reference2`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference2`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference2`,'</span>') else `c`.`reference2` end AS `Mensaje`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`reference3`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference3`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference3`,'</span>') else `c`.`reference3` end AS `Comentario 1`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`reference4`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference4`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference4`,'</span>') else `c`.`reference4` end AS `Comentario 2`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`c`.`reference5`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference5`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`c`.`reference5`,'</span>') else `c`.`reference5` end AS `Ubicacion`,case when `ws`.`name` = 'NO CERRADO' then concat(' <span style="font-weight: bold;color:red" >',`cont`.`name`,'</span>') when `ws`.`name` = 'CERRADO' then concat(' <span style="font-weight: bold;color:#00FF00" >',`cont`.`name`,'</span>') when `ws`.`name` = 'PROCESO DE CIERRE' then concat(' <span style="font-weight: bold;color:#00FF00" >',`cont`.`name`,'</span>') else `cont`.`name` end AS `Forma de contacto` from (((((((`tb_customer` `c` join `tb_workflow_stage` `ws` on(`ws`.`workflowStageID` = `c`.`statusID`)) join `tb_naturales` `nat` on(`c`.`entityID` = `nat`.`entityID`)) join `tb_catalog_item` `sex` on(`sex`.`catalogItemID` = `c`.`sexoID`)) join `tb_catalog_item` `clas` on(`clas`.`catalogItemID` = `c`.`clasificationID`)) join `tb_catalog_item` `cat` on(`cat`.`catalogItemID` = `c`.`categoryID`)) join `tb_catalog_item` `cont` on(`cont`.`catalogItemID` = `c`.`formContactID`)) left join `tb_naturales` `agent` on(`agent`.`entityID` = `c`.`entityContactID`)) where `c`.`isActive` = 1 and `c`.`companyID` = 2 order by `c`.`createdOn` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gerencia_balance`
--

/*!50001 DROP VIEW IF EXISTS `vw_gerencia_balance`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gerencia_balance` AS select `cco`.`description` AS `CentroCosto`,concat('\'',`a`.`accountNumber`,'\' ',`a`.`name`) AS `Cuenta`,date_format(`acc`.`endOn`,'%Y') AS `Ano`,date_format(`acc`.`endOn`,'%Y-%m') AS `Mes`,date_format(`acc`.`endOn`,'%m') AS `MesOnly`,`acb`.`balance` AS `C$saldoInicial`,if(`att`.`naturaleza` = 'D',`acb`.`balance` + (`acb`.`debit` - `acb`.`credit`),`acb`.`balance` + (`acb`.`credit` - `acb`.`debit`)) AS `C$saldoFinal`,if(`att`.`naturaleza` = 'D',`acb`.`debit` - `acb`.`credit`,`acb`.`credit` - `acb`.`debit`) AS `C$saldoMensual` from ((((`tb_account` `a` join `tb_accounting_balance` `acb` on(`a`.`accountID` = `acb`.`accountID`)) join `tb_accounting_cycle` `acc` on(`acc`.`componentCycleID` = `acb`.`componentCycleID`)) join `tb_account_type` `att` on(`att`.`accountTypeID` = `a`.`accountTypeID`)) join `tb_center_cost` `cco` on(`cco`.`classID` = `a`.`classID`)) where `acc`.`startOn` <= curdate() and `acc`.`startOn` >= curdate() - interval 72 month order by `a`.`accountNumber`,`acc`.`startOn` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gerencia_customer`
--

/*!50001 DROP VIEW IF EXISTS `vw_gerencia_customer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gerencia_customer` AS select `c`.`customerNumber` AS `customerNumber`,`nat`.`firstName` AS `firstName`,`c`.`identification` AS `identification`,`c`.`birthDate` AS `birthDate` from (`tb_customer` `c` join `tb_naturales` `nat` on(`c`.`entityID` = `nat`.`entityID`)) where `c`.`isActive` = 1 and `nat`.`isActive` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gerencia_desembolsos_detalle`
--

/*!50001 DROP VIEW IF EXISTS `vw_gerencia_desembolsos_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gerencia_desembolsos_detalle` AS select `emple`.`employeNumber` AS `Colaborador`,`natemp`.`firstName` AS `NombreColaborador`,`cus`.`customerNumber` AS `Cliente`,`nat`.`firstName` AS `NombreCliente`,`ccc`.`documentNumber` AS `Factura`,`cca`.`creditAmortizationID` AS `creditAmortizationID`,`cca`.`dateApply` AS `FechaCuota`,date_format(`cca`.`dateApply`,'%Y') AS `AnoCuota`,date_format(`cca`.`dateApply`,'%Y-%m') AS `Mes1Cuota`,date_format(`cca`.`dateApply`,'%m') AS `Mes2Cuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`balanceStart` * `r`.`ratio`,`cca`.`balanceStart`),2) AS `C$BalanceStartCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) AS `C$InteresCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) AS `C$CapitalCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`balanceEnd` * `r`.`ratio`,`cca`.`balanceEnd`),2) AS `C$BalanceEndCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`share` * `r`.`ratio`,`cca`.`share`),2) AS `C$ShareCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`remaining` * `r`.`ratio`,`cca`.`remaining`),2) AS `C$RemainingCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`shareCapital` * `r`.`ratio`,`cca`.`shareCapital`),2) AS `C$shareCapital`,`ws2`.`name` AS `EstadoCuota`,case when `cca`.`dayDelay` > 0 then `cca`.`dayDelay` when `cca`.`dateApply` < current_timestamp() and `cca`.`remaining` > 0 then to_days(current_timestamp()) - to_days(`cca`.`dateApply`) else 0 end AS `diasAtrazoCuota`,`cur`.`name` AS `Moneda`,`ractual`.`ratio` AS `TipoCambioActual`,case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`share` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`share` - `cca`.`remaining`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) else 0 end AS `C$CapitalPagado`,round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) - case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`share` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`share` - `cca`.`remaining`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) else 0 end AS `C$CapitalPendiente`,case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then 0 when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`interest` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`interest` - `cca`.`remaining`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) else 0 end AS `C$IntaresPagado`,round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) - case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then 0 when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`interest` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`interest` - `cca`.`remaining`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) else 0 end AS `C$InteresPendiente` from (((((((((((`tb_customer_credit_document` `ccc` join `tb_workflow_stage` `ws` on(`ccc`.`statusID` = `ws`.`workflowStageID`)) join `tb_customer` `cus` on(`ccc`.`entityID` = `cus`.`entityID`)) join `tb_naturales` `nat` on(`cus`.`entityID` = `nat`.`entityID`)) join `tb_exchange_rate` `r` on(`r`.`date` = `ccc`.`dateOn`)) join `tb_customer_credit_amoritization` `cca` on(`ccc`.`customerCreditDocumentID` = `cca`.`customerCreditDocumentID`)) join `tb_workflow_stage` `ws2` on(`cca`.`statusID` = `ws2`.`workflowStageID`)) join `tb_currency` `cur` on(`ccc`.`currencyID` = `cur`.`currencyID`)) join `tb_exchange_rate` `ractual` on(`ractual`.`date` = curdate() and `ractual`.`currencyID` = 1)) left join `tb_relationship` `rls` on(`rls`.`customerID` = `cus`.`entityID`)) left join `tb_employee` `emple` on(`rls`.`employeeID` = `emple`.`entityID`)) left join `tb_naturales` `natemp` on(`emple`.`entityID` = `natemp`.`entityID`)) where `ccc`.`isActive` = 1 and `r`.`currencyID` = 1 and `cca`.`isActive` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gerencia_desembolsos_resumen`
--

/*!50001 DROP VIEW IF EXISTS `vw_gerencia_desembolsos_resumen`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gerencia_desembolsos_resumen` AS select `cu`.`customerNumber` AS `CodigoCliente`,`nat`.`firstName` AS `Nombre`,`cur`.`simbol` AS `Moneda`,year(current_timestamp()) - year(`cu`.`birthDate`) AS `Edad`,if(`cc`.`exchangeRate` < 1,round(`cc`.`amount` / `cc`.`exchangeRate`,2),round(`cc`.`amount`,2)) AS `C$Monto`,if(`cc`.`exchangeRate` < 1,round(`cc`.`balance` / `cc`.`exchangeRate`,2),round(`cc`.`balance`,2)) AS `C$Balance`,if(`cc`.`exchangeRate` < 1,round(`cc`.`balanceProvicioned` / `cc`.`exchangeRate`,2),round(`cc`.`balanceProvicioned`,2)) AS `C$Provisionado`,`ws`.`display` AS `Estado`,`cc`.`interes` AS `Interes`,`cc`.`term` AS `Plazo`,`cc`.`exchangeRate` AS `TipoCambio`,`cc`.`dateOn` AS `Fecha`,`cix`.`name` AS `TipoAmortizacion`,`cix2`.`name` AS `PeriodoPago`,date_format(`cc`.`dateOn`,'%Y') AS `Anio`,date_format(`cc`.`dateOn`,'%Y-%m') AS `Mes`,date_format(`cc`.`dateOn`,'%m') AS `MesUnicamente`,`cc`.`documentNumber` AS `Factura` from ((((((`tb_customer_credit_document` `cc` join `tb_naturales` `nat` on(`cc`.`entityID` = `nat`.`entityID`)) join `tb_customer` `cu` on(`nat`.`entityID` = `cu`.`entityID`)) join `tb_currency` `cur` on(`cc`.`currencyID` = `cur`.`currencyID`)) join `tb_workflow_stage` `ws` on(`ws`.`workflowStageID` = `cc`.`statusID`)) join `tb_catalog_item` `cix` on(`cix`.`catalogItemID` = `cc`.`typeAmortization`)) join `tb_catalog_item` `cix2` on(`cix2`.`catalogItemID` = `cc`.`periodPay`)) where `cc`.`isActive` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gerencia_estado_resultado_001`
--

/*!50001 DROP VIEW IF EXISTS `vw_gerencia_estado_resultado_001`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gerencia_estado_resultado_001` AS select `c`.`Cuenta` AS `Cuenta`,`c`.`Ano` AS `Ano`,`c`.`Mes` AS `Mes`,`c`.`MesOnly` AS `MesOnly`,`c`.`C$saldoInicial` * 1 AS `C$saldoInicial`,`c`.`C$saldoFinal` * 1 AS `C$saldoFinal`,`c`.`C$saldoMensual` * 1 AS `C$saldoMensual` from `vw_gerencia_balance` `c` where `c`.`Cuenta` like '\'04-%' union select `c`.`Cuenta` AS `Cuenta`,`c`.`Ano` AS `Ano`,`c`.`Mes` AS `Mes`,`c`.`MesOnly` AS `MesOnly`,`c`.`C$saldoInicial` * -1 AS `C$saldoInicial`,`c`.`C$saldoFinal` * -1 AS `C$saldoFinal`,`c`.`C$saldoMensual` * -1 AS `C$saldoMensual` from `vw_gerencia_balance` `c` where `c`.`Cuenta` like '\'05-%' union select `c`.`Cuenta` AS `Cuenta`,`c`.`Ano` AS `Ano`,`c`.`Mes` AS `Mes`,`c`.`MesOnly` AS `MesOnly`,`c`.`C$saldoInicial` * -1 AS `C$saldoInicial`,`c`.`C$saldoFinal` * -1 AS `C$saldoFinal`,`c`.`C$saldoMensual` * -1 AS `C$saldoMensual` from `vw_gerencia_balance` `c` where `c`.`Cuenta` like '\'06-%' */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gerencia_estado_resultado_002`
--

/*!50001 DROP VIEW IF EXISTS `vw_gerencia_estado_resultado_002`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gerencia_estado_resultado_002` AS select `tx`.`Ano` AS `Ano`,`tx`.`Mes` AS `Mes`,`tx`.`MesOnly` AS `MesOnly`,sum(`tx`.`C$saldoInicial`) AS `C$saldoInicial`,sum(`tx`.`C$saldoFinal`) AS `C$saldoFinal`,sum(`tx`.`C$saldoMensual`) AS `C$saldoMensual` from `vw_gerencia_estado_resultado_001` `tx` group by `tx`.`Ano`,`tx`.`Mes`,`tx`.`MesOnly` order by `tx`.`Ano`,`tx`.`Mes` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_inventory_list_item_real_estate`
--

/*!50001 DROP VIEW IF EXISTS `vw_inventory_list_item_real_estate`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_inventory_list_item_real_estate` AS select `i`.`itemID` AS `Codigo interno`,concat('<a href="[[BASE_URL]]','/app_inventory_item/edit/companyID/',`i`.`companyID`,'/itemID/',`i`.`itemID`,'" ',' target="_blank" >',`i`.`itemID`,'</a>') AS `itemID`,date_format(`i`.`createdOn`,'%Y-%m-%d') AS `createdOn`,`i`.`itemNumber` AS `Codigo`,`i`.`name` AS `Nombre`,`i`.`barCode` AS `Pagina Web Url`,concat('',`i`.`barCode`,'') AS `Pagina Web`,if(ifnull(`i`.`isPerishable`,0) = 0,'No','Si') AS `Amueblado`,`i`.`capacity` AS `Aires`,`i`.`quantityMin` AS `Niveles`,`i`.`quantityMax` AS `Hora de visita`,`i`.`factorBox` AS `Baños`,`i`.`factorProgram` AS `Habitaciones`,`cat`.`name` AS `Diseño de propiedad`,`tipo`.`name` AS `Tipo de casa`,`pro`.`name` AS `Proposito`,'Dolares' AS `Moneda`,date_format(`i`.`createdOn`,'%Y-%m-%d') AS `Fecha de enlistamiento`,date_format(`i`.`modifiedOn`,'%Y-%m-%d') AS `Fecha de actualizacion`,(select `ii`.`price` from `tb_price` `ii` where `ii`.`itemID` = `i`.`itemID` and `ii`.`typePriceID` = 154 limit 1) AS `Precio Venta`,(select `ii`.`price` from `tb_price` `ii` where `ii`.`itemID` = `i`.`itemID` and `ii`.`typePriceID` = 155 limit 1) AS `Precio Renta`,if(ifnull(`i`.`isServices`,0) = 0,'No','Si') AS `Disponible`,`i`.`reference1` AS `Area de contruccion M2`,`i`.`reference2` AS `Area de terreno V2`,`i`.`reference3` AS `ID Encuentra 24`,if(ifnull(`i`.`realStateRoomBatchServices`,0) = 0,'No','Si') AS `Baño de servicio`,if(ifnull(`i`.`realStateRooBatchVisit`,0) = 0,'No','Si') AS `Baño de visita`,if(ifnull(`i`.`realStateRoomServices`,0) = 0,'No','Si') AS `Cuarto de servicio`,if(ifnull(`i`.`realStateWallInCloset`,0) = 0,'No','Si') AS `Walk in closet`,if(ifnull(`i`.`realStatePiscinaPrivate`,0) = 0,'No','Si') AS `Piscina privada`,if(ifnull(`i`.`realStateClubPiscina`,0) = 0,'No','Si') AS `Area club con piscina`,if(ifnull(`i`.`realStateAceptanMascota`,0) = 0,'No','Si') AS `Acepta mascota`,if(ifnull(`i`.`realStateContractCorrentaje`,0) = 0,'No','Si') AS `Corretaje`,if(ifnull(`i`.`realStatePlanReference`,0) = 0,'No','Si') AS `Plan de referido`,`i`.`realStateLinkYoutube` AS `Link Youtube Url`,concat('<a href="',`i`.`realStateLinkYoutube`,'"  target="_blank" >',`i`.`realStateLinkYoutube`,'</a>') AS `Link Youtube`,`i`.`realStateLinkPaginaWeb` AS `Pagina Web Link Url`,concat('<a href="',`i`.`realStateLinkPaginaWeb`,'" target="_blank" >',`i`.`realStateLinkPaginaWeb`,'</a>') AS `Pagina Web Link`,`i`.`realStateLinkPhontos` AS `Foto Url`,concat('<a href="',`i`.`realStateLinkPhontos`,'" target="_blank" >',`i`.`realStateLinkPhontos`,'</a>') AS `Foto`,`i`.`realStateLinkGoogleMaps` AS `Google Url`,concat('<a href="',`i`.`realStateLinkGoogleMaps`,'" target="_blank" >',`i`.`realStateLinkGoogleMaps`,'</a>') AS `Google`,`i`.`realStateLinkOther` AS `Otros Link Url`,concat('<a href="',`i`.`realStateLinkOther`,'"  target="_blank" >',`i`.`realStateLinkOther`,'</a>') AS `Otros Link`,`i`.`realStateStyleKitchen` AS `Estilo de cocina`,ifnull(`nat`.`firstName`,'N/D') AS `Agente`,`i`.`realStateReferenceZone` AS `Zona`,`i`.`realStateReferenceCondominio` AS `Condominio`,`i`.`realStateReferenceUbicacion` AS `Ubicacion`,`excl`.`name` AS `Exclusividad de agente`,`country`.`name` AS `Pais`,`state`.`name` AS `Estado`,`city`.`name` AS `Ciudad`,ifnull(`i`.`realStatePhone`,'') AS `Telefono`,`i`.`isActive` AS `isActive` from (((((((((`tb_item` `i` join `tb_item_category` `cat` on(`cat`.`inventoryCategoryID` = `i`.`inventoryCategoryID`)) join `tb_catalog_item` `tipo` on(`tipo`.`catalogItemID` = `i`.`familyID`)) join `tb_catalog_item` `pro` on(`pro`.`catalogItemID` = `i`.`displayID`)) join `tb_currency` `cur` on(`cur`.`currencyID` = `i`.`currencyID`)) left join `tb_naturales` `nat` on(`nat`.`entityID` = `i`.`realStateEmployerAgentID`)) join `tb_catalog_item` `excl` on(`excl`.`catalogItemID` = `i`.`realStateGerenciaExclusive`)) join `tb_catalog_item` `country` on(`country`.`catalogItemID` = `i`.`realStateCountryID`)) join `tb_catalog_item` `state` on(`state`.`catalogItemID` = `i`.`realStateStateID`)) join `tb_catalog_item` `city` on(`city`.`catalogItemID` = `i`.`realStateCityID`)) where `i`.`companyID` = 2 order by `i`.`createdOn` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_sales_inventory`
--

/*!50001 DROP VIEW IF EXISTS `vw_sales_inventory`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sales_inventory` AS select `tm`.`createdOn` AS `createdOn`,extract(day from `tm`.`createdOn`) AS `createdOnDay`,`cu`.`name` AS `currency`,`t`.`name` AS `tipo`,`tc`.`name` AS `causal`,`tm`.`transactionNumber` AS `transactionNumber`,`ws`.`name` AS `statusName`,`comp`.`name` AS `companiaName`,`w`.`name` AS `warehouseName`,`cus`.`customerNumber` AS `customerNumber`,`nat`.`firstName` AS `firstName`,`i`.`itemNumber` AS `itemNumber`,`i`.`name` AS `name`,`cat`.`name` AS `categoryName`,1 / `tm`.`exchangeRate` AS `tipoCambio`,`td`.`quantity` AS `quantity`,`td`.`unitaryCost` AS `unitaryCost`,`td`.`quantity` * `td`.`unitaryCost` AS `cost`,if(`tm`.`currencyID` = 1,`td`.`unitaryAmount`,1 / `tm`.`exchangeRate` * `td`.`unitaryAmount`) AS `unitaryAmount`,if(`tm`.`currencyID` = 1,`td`.`amount`,1 / `tm`.`exchangeRate` * `td`.`amount`) AS `amount`,if(`tm`.`currencyID` = 1,`td`.`amount`,1 / `tm`.`exchangeRate` * `td`.`amount`) - `td`.`quantity` * `td`.`unitaryCost` AS `utility` from (((((((((((`tb_transaction_master` `tm` join `tb_transaction_master_detail` `td` on(`td`.`transactionMasterID` = `tm`.`transactionMasterID`)) join `tb_company` `comp` on(`comp`.`companyID` = `tm`.`companyID`)) join `tb_warehouse` `w` on(`w`.`warehouseID` = `tm`.`sourceWarehouseID`)) join `tb_workflow_stage` `ws` on(`ws`.`workflowStageID` = `tm`.`statusID`)) join `tb_currency` `cu` on(`tm`.`currencyID` = `cu`.`currencyID`)) join `tb_transaction` `t` on(`t`.`transactionID` = `tm`.`transactionID`)) join `tb_transaction_causal` `tc` on(`tm`.`transactionCausalID` = `tc`.`transactionCausalID`)) join `tb_item` `i` on(`i`.`itemID` = `td`.`componentItemID`)) join `tb_item_category` `cat` on(`cat`.`inventoryCategoryID` = `i`.`inventoryCategoryID`)) join `tb_naturales` `nat` on(`tm`.`entityID` = `nat`.`entityID`)) join `tb_customer` `cus` on(`cus`.`entityID` = `nat`.`entityID`)) where `tm`.`isActive` = 1 and `td`.`isActive` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_sin_riesgo_reporte_clientes`
--

/*!50001 DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_clientes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sin_riesgo_reporte_clientes` AS select date_format(current_timestamp(),'%d/%m/%Y') AS `FECHA REPORTE`,replace(`cus`.`identification`,'-','') AS `IDENTIFICACION`,'N' AS `TIPO DE PERSONA`,'NICARAGUENSE' AS `NACIONALIDAD`,case when `sexo`.`display` = 'FEMENINO' then 'F' else 'M' end AS `SEXO`,date_format(`cus`.`birthDate`,'%d/%m/%Y') AS `FECHA DE NACIMIENTO`,'SOL' AS `ESTADO CIVIL`,`cus`.`address` AS `DIRECCION`,'08' AS `DEPARTAMENTO`,'84' AS `MUNICIPIO`,`cus`.`address` AS `DIRECCION DE TRABAJO`,'08' AS `DEPARTAMENTO DE TRABAJO`,'84' AS `MUNICIPIO DE TRABAJO`,if((select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1) is null,'',(select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1)) AS `TELEFONO DOMICILIAR`,if((select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1) is null,'',(select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1)) AS `TELEFONO TRABAJO`,if((select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1) is null,'',(select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1)) AS `CELULAR`,'' AS `CORREO ELECTRONICO`,'COMERCIANTE' AS `OCUPACION`,'PULPERIA' AS `ACTIVIDAD ECONOMICA`,'DETALLE' AS `SECTOR` from ((`tb_naturales` `nat` join `tb_customer` `cus` on(`nat`.`entityID` = `cus`.`entityID`)) join `tb_catalog_item` `sexo` on(`cus`.`sexoID` = `sexo`.`catalogItemID`)) where `nat`.`isActive` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_sin_riesgo_reporte_creditos`
--

/*!50001 DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_creditos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sin_riesgo_reporte_creditos` AS select `cc`.`companyID` AS `companyID`,`cc`.`customerCreditDocumentID` AS `customerCreditDocumentID`,`cc`.`entityID` AS `entityID`,'03' AS `TIPO DE ENTIDAD`,'552' AS `NUMERO CORRELATIVO`,date_format(current_timestamp(),'%d/%m/%Y') AS `FECHA DE REPORTE`,'08' AS `DEPARTAMENTO`,replace(`c`.`identification`,'-','') AS `NUMERO DE CEDULA O RUC`,concat(`nat`.`firstName`,' ',convert(`nat`.`lastName` using utf8)) AS `NOMBRE DE PERSONA`,right(concat('0000',`tipocredito`.`sequence`),2) AS `TIPO DE CREDITO`,date_format(`cc`.`dateOn`,'%d/%m/%Y') AS `FECHA DE DESEMBOLSO`,right(concat('0000',`obli`.`sequence`),2) AS `TIPO DE OBLIGACION`,round(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`amount`) * `p`.`ratioDesembolso`,2) AS `MONTO AUTORIZADO`,if(round(case when `cc`.`periodPay` = 190 then `cc`.`term` * 30 when `cc`.`periodPay` = 188 then `cc`.`term` * 7 when `cc`.`periodPay` = 189 then `cc`.`term` * 14 else 0 end / 30,0) = 0,1,round(case when `cc`.`periodPay` = 190 then `cc`.`term` * 30 when `cc`.`periodPay` = 188 then `cc`.`term` * 7 when `cc`.`periodPay` = 189 then `cc`.`term` * 14 else 0 end / 30,0)) AS `PLAZO`,case when `cc`.`periodPay` = 190 then '05' when `cc`.`periodPay` = 188 then '07' when `cc`.`periodPay` = 189 then '06' else 0 end AS `FRECUENCIA DE PAGO`,case when `cc`.`statusID` = 82 then 0 else round(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`balance`) * `p`.`ratioBalance`,2) end AS `SALDO DEUDA`,case when `estadosinriesgo`.`sequence` = 1 then case when `ws`.`workflowStageID` not in (93,92,82) and cast(current_timestamp() as date) > (select max(`xl`.`dateApply`) from `tb_customer_credit_amoritization` `xl` where `xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`) then '02' when `ws`.`workflowStageID` not in (93,92,82) and cast(current_timestamp() as date) > (select min(`xl`.`dateApply`) from `tb_customer_credit_amoritization` `xl` where `xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `xl`.`remaining` > 0) then '02' when `ws`.`workflowStageID` = 83 then 'N/D' when `ws`.`workflowStageID` = 92 then '08' when `ws`.`workflowStageID` = 82 then '03' when `ws`.`workflowStageID` = 77 then '01' else right(concat('0000',`estadosinriesgo`.`sequence`),2) end else right(concat('0000',`estadosinriesgo`.`sequence`),2) end AS `ESTADO`,round((select ifnull(round(case when `cc`.`typeAmortization` = 196 then avg(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cx`.`balanceStart`)) else sum(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cx`.`capital`)) end,2),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) * `p`.`ratioBalanceExpired`,2) AS `MONTO VENCIDO`,(select ifnull(to_days(current_timestamp()) - to_days(min(`cx`.`dateApply`)),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) AS `ANTIGUEDAD DE MORA`,right(concat('0000',`tipogarantia`.`sequence`),2) AS `TIPO DE GARANTIA`,case when `recuperacion`.`sequence` = 1 then case when `ws`.`workflowStageID` = 83 then '01' when `ws`.`workflowStageID` = 92 then '08' when `ws`.`workflowStageID` = 82 then '01' when `ws`.`workflowStageID` = 77 and (select ifnull(to_days(current_timestamp()) - to_days(min(`cx`.`dateApply`)),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) between 30 and 59 then '03' when `ws`.`workflowStageID` = 77 and (select ifnull(to_days(current_timestamp()) - to_days(min(`cx`.`dateApply`)),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) > 60 then '04' when `ws`.`workflowStageID` = 77 then '01' else right(concat('0000',`recuperacion`.`sequence`),2) end else right(concat('0000',`recuperacion`.`sequence`),2) end AS `FORMA DE RECUPERACION`,`cc`.`documentNumber` AS `NUMERO DE CREDITO`,round(case when `ci`.`catalogItemID` = 196 then case when `cc`.`periodPay` = 190 then `FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`balance`) * (`cc`.`interes` / 12 * `cc`.`term` / 100 + 1) / `cc`.`term` when `cc`.`periodPay` = 188 then `FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`balance`) * (`cc`.`interes` / 52 * `cc`.`term` / 100 + 0) / `cc`.`term` else 0 end else (select avg(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cp`.`share`)) from `tb_customer_credit_amoritization` `cp` where `cp`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`) end * `p`.`ratioShare`,2) AS `VALOR DE LA CUOTA` from ((((((((((((`tb_customer_credit_document` `cc` join `tb_currency` `cur` on(`cc`.`currencyID` = `cur`.`currencyID`)) join `tb_workflow_stage` `ws` on(`cc`.`statusID` = `ws`.`workflowStageID`)) join `tb_catalog_item` `ci` on(`cc`.`typeAmortization` = `ci`.`catalogItemID`)) join `tb_customer_credit_document_entity_related` `p` on(`cc`.`customerCreditDocumentID` = `p`.`customerCreditDocumentID`)) join `tb_catalog_item` `obli` on(`obli`.`catalogItemID` = `p`.`type`)) join `tb_catalog_item` `tipocredito` on(`tipocredito`.`catalogItemID` = `p`.`typeCredit`)) join `tb_catalog_item` `tipogarantia` on(`tipogarantia`.`catalogItemID` = `p`.`typeGarantia`)) join `tb_catalog_item` `frepago` on(`frepago`.`catalogItemID` = `cc`.`periodPay`)) join `tb_catalog_item` `recuperacion` on(`recuperacion`.`catalogItemID` = `p`.`typeRecuperation`)) join `tb_catalog_item` `estadosinriesgo` on(`estadosinriesgo`.`catalogItemID` = `p`.`statusCredit`)) join `tb_naturales` `nat` on(`p`.`entityID` = `nat`.`entityID`)) join `tb_customer` `c` on(`nat`.`entityID` = `c`.`entityID`)) where `cc`.`isActive` = 1 and `cc`.`entityID` <> 309 and replace(`c`.`identification`,'-','') not in ('0000000000000B','0000000000000A','0000000000000C','0000000000000P','0000000000000K','2811803890004R','2912906610000G','2911206850000P','0000000000000T') and `ws`.`workflowStageID` <> 83 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_sin_riesgo_reporte_creditos_to_systema`
--

/*!50001 DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_creditos_to_systema`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sin_riesgo_reporte_creditos_to_systema` AS select `i`.`companyID` AS `companyID`,`i`.`TIPO DE ENTIDAD` AS `TIPO_DE_ENTIDAD`,`i`.`NUMERO CORRELATIVO` AS `NUMERO_CORRELATIVO`,`i`.`FECHA DE REPORTE` AS `FECHA_DE_REPORTE`,`i`.`DEPARTAMENTO` AS `DEPARTAMENTO`,`i`.`NUMERO DE CEDULA O RUC` AS `NUMERO_DE_CEDULA_O_RUC`,`i`.`NOMBRE DE PERSONA` AS `NOMBRE_DE_PERSONA`,`i`.`TIPO DE CREDITO` AS `TIPO_DE_CREDITO`,`i`.`FECHA DE DESEMBOLSO` AS `FECHA_DE_DESEMBOLSO`,`i`.`TIPO DE OBLIGACION` AS `TIPO_DE_OBLIGACION`,`i`.`MONTO AUTORIZADO` AS `MONTO_AUTORIZADO`,`i`.`PLAZO` AS `PLAZO`,`i`.`FRECUENCIA DE PAGO` AS `FRECUENCIA_DE_PAGO`,`i`.`SALDO DEUDA` AS `SALDO_DEUDA`,`i`.`ESTADO` AS `ESTADO`,`i`.`MONTO VENCIDO` AS `MONTO_VENCIDO`,`i`.`ANTIGUEDAD DE MORA` AS `ANTIGUEDAD_DE_MORA`,`i`.`TIPO DE GARANTIA` AS `TIPO_DE_GARANTIA`,`i`.`FORMA DE RECUPERACION` AS `FORMA_DE_RECUPERACION`,`i`.`NUMERO DE CREDITO` AS `NUMERO_DE_CREDITO`,`i`.`VALOR DE LA CUOTA` AS `VALOR_DE_LA_CUOTA` from `vw_sin_riesgo_reporte_creditos` `i` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_transaccion_master_concept_232425`
--

/*!50001 DROP VIEW IF EXISTS `vw_transaccion_master_concept_232425`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_transaccion_master_concept_232425` AS select `tm`.`transactionMasterID` AS `transactionMasterID`,`t`.`name` AS `Descripcion`,`tm`.`createdOn` AS `Fecha`,`tm`.`transactionNumber` AS `Documento`,`cur`.`name` AS `Moneda`,`tc`.`name` AS `Concepto`,`tmd`.`value` AS `Valor`,`comp`.`name` AS `Componente`,`td`.`componentItemID` AS `componentItemID`,`td`.`reference1` AS `Referencia1` from ((((((`tb_transaction_master` `tm` join `tb_transaction` `t` on(`tm`.`transactionID` = `t`.`transactionID`)) join `tb_transaction_master_detail` `td` on(`tm`.`transactionMasterID` = `td`.`transactionMasterID`)) join `tb_transaction_master_concept` `tmd` on(`td`.`transactionMasterID` = `tmd`.`transactionMasterID` and `td`.`componentItemID` = `tmd`.`componentItemID`)) join `tb_currency` `cur` on(`tm`.`currencyID` = `cur`.`currencyID`)) join `tb_transaction_concept` `tc` on(`tmd`.`conceptID` = `tc`.`conceptID`)) join `tb_component` `comp` on(`comp`.`componentID` = `td`.`componentID`)) where `tm`.`companyID` = 2 and `tm`.`isActive` = 1 and `tm`.`transactionID` in (23,24,25) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-15  3:15:36