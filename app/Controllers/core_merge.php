<?php
//posme:2023-02-27
namespace App\Controllers;
class core_merge extends _BaseController {
	
	protected $dbDestino			;
	protected $dbOrigen				;
	protected $forgeOrigen			;
	
	
	protected $sourceName						;
	protected $targetName						;
	protected $dbConectTarget 					;
	protected $dbConectSource					;
	
	protected $dbConectInformationSchema		;
	protected $forgeTarget						;
		
	function __construct()
	{
		
				
	}
	
	
	function merge_of_posme_merge_to_posme_structure()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		
		//https://codeigniter.com/user_guide/dbmgmt/forge.html
		//https://codeigniter.com/userguide3/database/utilities.html		

		$this->dbOrigen							= \Config\Database::connect("merge");
		$this->sourceName						= DB_BDNAME_MERGE;
		$this->dbDestino						= \Config\Database::connect();
		$this->targetName						= DB_BDNAME;
		
		
		$this->dbConectTarget 					= $this->dbDestino;
		$this->dbConectSource					= $this->dbOrigen;
		$this->dbConectInformationSchema		= \Config\Database::connect("information_schema");		
		$this->forgeOrigen						= \Config\Database::forge($this->dbOrigen);	
		$this->forgeTarget						= \Config\Database::forge($this->dbConectTarget);	
		
		$sourceName						= $this->sourceName;
		$targetName						= $this->targetName;
		$dbConectTarget 				= $this->dbConectTarget;
		$dbConectSource					= $this->dbConectSource;
		$dbConectInformationSchema		= $this->dbConectInformationSchema;
		$forge 							= $this->forgeTarget;
		
		
		//Obtener el listado de tablas
		$sql 					= "";
		$sql 					= "select * from TABLES C where C.TABLE_SCHEMA = '".$targetName."' and C.TABLE_TYPE = 'BASE TABLE'; ";
		$objListTableTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from TABLES C where C.TABLE_SCHEMA = '".$sourceName."' and C.TABLE_TYPE = 'BASE TABLE'; ";
		$objListTableSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		
		//Obtener el listado de vistas
		$sql 					= "";
		$sql 					= "select * from TABLES C where C.TABLE_SCHEMA = '".$targetName."' and C.TABLE_TYPE = 'VIEW'; ";
		$objListViewTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from TABLES C where C.TABLE_SCHEMA = '".$sourceName."' and C.TABLE_TYPE = 'VIEW'; ";
		$objListViewSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		
		//Obtener el listado de triger
		$sql 					= "";
		$sql 					= "select * from TRIGGERS C where C.TRIGGER_SCHEMA = '".$targetName."' ; ";
		$objListTriggerTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from TRIGGERS C where C.TRIGGER_SCHEMA = '".$sourceName."' ; ";
		$objListTriggeSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		
			
		//Obtener el listado de funciones 
		$sql 					= "";
		$sql 					= "select * from ROUTINES C where C.ROUTINE_SCHEMA = '".$targetName."' ; ";
		$objListRoutineTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from ROUTINES C where C.ROUTINE_SCHEMA = '".$sourceName."' ; ";
		$objListRoutineSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		//Obtener el listado de columnas 
		$sql 					= "";
		$sql 					= "select * from COLUMNS C where C.TABLE_SCHEMA = '".$targetName."' ; ";
		$objListColumnTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from COLUMNS C where C.TABLE_SCHEMA = '".$sourceName."' ; ";
		$objListColumnSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		
		//Obtener el listado de columnas auto_increment
		$sql 					= "";
		$sql 					= "select * from COLUMNS C where C.TABLE_SCHEMA = '".$targetName."' AND EXTRA = 'auto_increment' ; ";
		$objListColumnAutoIncrementTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from COLUMNS C where C.TABLE_SCHEMA = '".$sourceName."' AND EXTRA = 'auto_increment' ; ";
		$objListColumnAutoIncrementSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		
		
		
		//Obtener la lista de contranins
		$sql 					= "";
		$sql 					= "select * from TABLE_CONSTRAINTS C where C.TABLE_SCHEMA = '".$targetName."' ; ";
		$objListConstrainTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select * from TABLE_CONSTRAINTS C where C.TABLE_SCHEMA = '".$sourceName."' ; ";
		$objListContrainSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		//Obtener la lista de index 
		$sql 					= "";
		$sql 					= "select distinct TABLE_CATALOG, TABLE_SCHEMA,TABLE_NAME,NON_UNIQUE,INDEX_SCHEMA,INDEX_NAME   from STATISTICS C where C.TABLE_SCHEMA = '".$targetName."' ; ";
		$objListIndexTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select distinct TABLE_CATALOG, TABLE_SCHEMA,TABLE_NAME,NON_UNIQUE,INDEX_SCHEMA,INDEX_NAME   from STATISTICS C where C.TABLE_SCHEMA = '".$sourceName."' ; ";
		$objListIndexSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		
		//Asegurarse que los campos auto increment en origen no se repiten. 
		foreach($objListColumnAutoIncrementSource as $autoSource)
		{
			$sql 	= "select ".$autoSource->COLUMN_NAME.",count(*) from ".$autoSource->TABLE_SCHEMA.".".$autoSource->TABLE_NAME." group by ".$autoSource->COLUMN_NAME." having count(*) > 1;  ";			
			$objR 	= $dbConectSource->query($sql)->getRow();
			if($objR)
			{				
				echo $sql."<br/>";
				$sql 	= "select * from ".$autoSource->TABLE_SCHEMA.".".$autoSource->TABLE_NAME.";   ";			
				echo $sql."<br/>";
				echo "La tabla ".$autoSource->TABLE_SCHEMA.".".$autoSource->TABLE_NAME." el auto increment, tiene valores repetidos..";
				exit;
			}
		}		
		echo "<br/><br/><br/><br/>";
		
		
		//Asegurarse que los campos auto increment en destino no se repiten. 
		foreach($objListColumnAutoIncrementTarget as $autoTarget)
		{
			$sql 	= "select ".$autoTarget->COLUMN_NAME.",count(*) from ".$autoTarget->TABLE_SCHEMA.".".$autoTarget->TABLE_NAME." group by ".$autoTarget->COLUMN_NAME." having count(*) > 1;  ";			
			$objR 	= $dbConectTarget->query($sql)->getRow();
			if($objR)
			{
				
				$sql 			= "select max(".$autoTarget->COLUMN_NAME.") as maxx from ".$autoTarget->TABLE_SCHEMA.".".$autoTarget->TABLE_NAME." ;  ";			
				$max    		= $dbConectTarget->query($sql)->getRow()->maxx + 1 ;
				
				
				$sql    		= "select ".$autoTarget->COLUMN_NAME.",count(*) as cantidad from ".$autoTarget->TABLE_SCHEMA.".".$autoTarget->TABLE_NAME." group by ".$autoTarget->COLUMN_NAME." having count(*) > 1;  ";	
				$objListRow 	= $dbConectTarget->query($sql)->getResult();
				
				foreach($objListRow as $objRow)
				{
					for($i =  0 ; $i < $objRow->cantidad; $i++)
					{
						$objRowArray = (array)$objRow;
						 
						$sql = "UPDATE 
									".$autoTarget->TABLE_SCHEMA.".".$autoTarget->TABLE_NAME.
								" SET ".
									$autoTarget->COLUMN_NAME." = ".$max."
								WHERE 
									".$autoTarget->COLUMN_NAME."  = ".$objRowArray[$autoTarget->COLUMN_NAME]."
								LIMIT 1 ";
						
						$dbConectTarget->query($sql);
						echo $sql." SUCCESS</br>";
						
						
						$sql = "ALTER TABLE `".$autoTarget->TABLE_SCHEMA."`.`".$autoTarget->TABLE_NAME."` AUTO_INCREMENT = ".$max.";";
						$dbConectTarget->query($sql);
						$max++;
						
					}
					
					
					
				}
				
				
				
				
				
			}
		}
		echo "<br/><br/><br/><br/>";
		
		
		//Eliminar todas las vistas
		foreach($objListViewTarget as $viewTarget)
		{
			$sql = 'DROP VIEW '.$viewTarget->TABLE_NAME;
			$dbConectTarget->query($sql);
			echo $sql." SUCCESS<br/>";
		}
		echo "<br/><br/><br/><br/>";
		
		//Eliminar todos los triger 
		foreach($objListTriggerTarget as $triggerTarget)
		{
			$sql = 'DROP TRIGGER  '.$triggerTarget->TRIGGER_NAME;
			$dbConectTarget->query($sql);
			echo $sql." SUCCESS<br/>";
		}
		echo "<br/><br/><br/><br/>";
		
		//Eliminar funciones 
		foreach($objListRoutineTarget as $routineTarget)
		{
			
			$sql = 'DROP '.$routineTarget->ROUTINE_TYPE.'  '.$routineTarget->ROUTINE_NAME;
			$dbConectTarget->query($sql);
			echo $sql." SUCCESS<br/>";
		}
		echo "<br/><br/><br/><br/>";
		
				
		//Eliminar Constraint Auto Increment
		foreach($objListColumnAutoIncrementTarget as $colum)
		{					
				
			$sql = 'ALTER TABLE `'.$colum->TABLE_SCHEMA.'`.`'.$colum->TABLE_NAME.'` MODIFY COLUMN `'.$colum->COLUMN_NAME.'` INT NOT NULL;';
			$dbConectTarget->query($sql);
			echo $sql." SUCCESS<br/>";
			
		}
		echo "<br/><br/><br/><br/>";
		
		
		//Eliminar Constrain primary key 		
		foreach($objListConstrainTarget as $constraTarget)
		{	
		
			if($constraTarget->CONSTRAINT_TYPE == "PRIMARY KEY")
			{			
				
				$sql = 'ALTER TABLE `'.$constraTarget->TABLE_SCHEMA.'`.`'.$constraTarget->TABLE_NAME.'` DROP PRIMARY KEY ';
				$dbConectTarget->query($sql);
				echo $sql." SUCCESS<br/>";
			
			}
			
		}
		echo "<br/><br/><br/><br/>";
		
		
		
		//Eliminar Constar Index y Unique		
		$sql 					= "";
		$sql 					= "select distinct TABLE_CATALOG, TABLE_SCHEMA,TABLE_NAME,NON_UNIQUE,INDEX_SCHEMA,INDEX_NAME   from STATISTICS C where C.TABLE_SCHEMA = '".$targetName."' ; ";
		$objListIndexTarget 	= $dbConectInformationSchema->query($sql)->getResult();
		
		$sql 					= "";
		$sql 					= "select distinct TABLE_CATALOG, TABLE_SCHEMA,TABLE_NAME,NON_UNIQUE,INDEX_SCHEMA,INDEX_NAME   from STATISTICS C where C.TABLE_SCHEMA = '".$sourceName."' ; ";
		$objListIndexSource 	= $dbConectInformationSchema->query($sql)->getResult();
		
		foreach($objListIndexTarget as $inxtTarget)
		{			
			$sql = 'ALTER TABLE `'.$inxtTarget->TABLE_SCHEMA.'`.`'.$inxtTarget->TABLE_NAME.'` DROP INDEX `'.$inxtTarget->INDEX_NAME.'`; ';
			$dbConectTarget->query($sql);
			echo $sql." SUCCESS<br/>";
		}
		echo "<br/><br/><br/><br/>";
		
		
		//crear tablas con valor por defecto
		foreach($objListTableSource as $table)
		{
			$sql	= "select * from TABLES C where C.TABLE_SCHEMA = '".$targetName."' and C.TABLE_NAME = '".$table->TABLE_NAME."' ; ";			
			$resusl = $dbConectInformationSchema->query($sql)->getRow();
			echo "CREATE TABLE ".$table->TABLE_NAME." SUCCESS<br/>";
			if(!$resusl)
			{
				$fields = [
					'foreach' => [
						'type'           => 'INT',					
						'default'        => NULL,
						
					]
				];
				$forge->addField($fields);
				$attributes = ['ENGINE' => $table->ENGINE ];
				$forge->createTable($table->TABLE_NAME, true);			
				
			}
		}
		echo "<br/><br/><br/><br/>";		
	
		
		
		
		//crear campos 	
		foreach($objListColumnSource as $column)
		{
			$sql	= "select * from TABLES C where C.TABLE_SCHEMA = '".$column->TABLE_SCHEMA."' and C.TABLE_NAME = '".$column->TABLE_NAME."' ; ";
			$resusl2 = $dbConectInformationSchema->query($sql)->getRow();
			if($resusl2)
			{
				if($resusl2->TABLE_TYPE == "BASE TABLE")
				{					
					$sql	= "select * from COLUMNS C where C.TABLE_SCHEMA = '".$targetName."' and C.TABLE_NAME = '".$column->TABLE_NAME."' and C.COLUMN_NAME = '".$column->COLUMN_NAME."' ; ";					
					$resusl3 = $dbConectInformationSchema->query($sql)->getRow();					
					if(!$resusl3)
					{

						$sql = "ALTER TABLE `".$targetName."`.`".$column->TABLE_NAME."` ADD COLUMN  `".$column->COLUMN_NAME."` VARCHAR(55)  DEFAULT NULL;  ";						
						$dbConectTarget->query($sql);
						echo $sql." SUCCESS</br>";
						
					}
					
				}
				
			}
			
			
		}
		echo "<br/><br/><br/><br/>";		
		
		//crear campos primary key y auto increment
		foreach($objListColumnSource as $column)
		{
			$sql	= "select * from TABLES C where C.TABLE_SCHEMA = '".$column->TABLE_SCHEMA."' and C.TABLE_NAME = '".$column->TABLE_NAME."' ; ";
			$resusl2 = $dbConectInformationSchema->query($sql)->getRow();
			if($resusl2)
			{
				if($resusl2->TABLE_TYPE == "BASE TABLE")
				{					
			
					if($column->EXTRA == "auto_increment")
					{
						$sql 		= "select IFNULL(max(`".$column->COLUMN_NAME."`),0) as AUTO_INCREMENT from `".$targetName."`.`".$column->TABLE_NAME."` ; ";
						$resultx	= $dbConectTarget->query($sql)->getRow();
						$cant       = $resultx->AUTO_INCREMENT+1;						
						
						$sql = "ALTER TABLE `".$targetName."`.`".$column->TABLE_NAME."` MODIFY COLUMN  `".$column->COLUMN_NAME."` ".$column->DATA_TYPE." NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`".$column->COLUMN_NAME."`);   ";
						$dbConectTarget->query($sql);
						
						$sql = "ALTER TABLE `".$targetName."`.`".$column->TABLE_NAME."` AUTO_INCREMENT = ".$cant.";";
						$dbConectTarget->query($sql);
						echo $sql." SUCCESS</br>";

						
						
					}

				}
				
			}
			
			
		}
		echo "<br/><br/><br/><br/>";		
		
		
		
		//crear campos valores por defecto 	y tipo de datos y nullable
		foreach($objListColumnSource as $column)
		{
			$sql	= "select * from TABLES C where C.TABLE_SCHEMA = '".$column->TABLE_SCHEMA."' and C.TABLE_NAME = '".$column->TABLE_NAME."' ; ";
			$resusl2 = $dbConectInformationSchema->query($sql)->getRow();
			if($resusl2)
			{
				if($resusl2->TABLE_TYPE == "BASE TABLE")
				{		
					
					if($column->EXTRA != "auto_increment")
					{
						$notNull 		= "";					
						$defaultValue 	= "";
						$typeData 		= "";
						
						//Nulable
						if($column->IS_NULLABLE == "YES")
						{						
							$notNull =  " NULL ";
						}
						else if ( $column->IS_NULLABLE == "NO" && $column->EXTRA == "auto_increment")
						{
							$notNull =  " NULL ";
						}
						else 
						{
							$notNull =  " NOT NULL ";
						}
						
						//Default 
						if($column->IS_NULLABLE == "NO" && $column->COLUMN_DEFAULT != null)
						{
							$defaultValue = " DEFAULT ".$column->COLUMN_DEFAULT." ";						
						}
						else if($column->IS_NULLABLE == "YES"  && $column->COLUMN_DEFAULT == null )
						{
							$defaultValue = " DEFAULT NULL ";
						}					
						else if($column->IS_NULLABLE == "NO" && $column->COLUMN_DEFAULT == null)
						{
							$defaultValue = "  ";						
						}
						else if($column->IS_NULLABLE == "YES"  && $column->COLUMN_DEFAULT != null )
						{
							$defaultValue = " DEFAULT ".$column->COLUMN_DEFAULT." ";						
						}
						else 
						{
							$defaultValue = " DEFAULT NULL ";
						}
						
						
						//Tipo de dato 
						$typeData 		= " ".$column->COLUMN_TYPE." ";					
						
						
						$sql = "ALTER TABLE `".$targetName."`.`".$column->TABLE_NAME."` MODIFY COLUMN  `".$column->COLUMN_NAME."` ".$typeData."  ".$notNull."  ".$defaultValue." ;  ";																					
						echo $sql." PREVIEW</br>";
						$dbConectTarget->query($sql);
						echo $sql." SUCCESS</br>";
					
					}
					
		
				}
				
			}			
			
		}
		echo "<br/><br/><br/><br/>";	
					
		
		
		//Crear Index	
		$sql 					= "";
		$sql 					= "select distinct TABLE_CATALOG, TABLE_SCHEMA,TABLE_NAME,NON_UNIQUE,INDEX_SCHEMA,INDEX_NAME   from STATISTICS C where C.TABLE_SCHEMA = '".$sourceName."' ; ";
		$objListIndexSource 	= $dbConectInformationSchema->query($sql)->getResult();
		foreach($objListIndexSource as $inxtSource)
		{			
		
			if($inxtSource->INDEX_NAME != "PRIMARY"  )
			{
				$columnx 		= "";
				$arraycolumn 	= array();
				$sql 			= "select TABLE_CATALOG, TABLE_SCHEMA,TABLE_NAME,NON_UNIQUE,INDEX_SCHEMA,INDEX_NAME ,COLUMN_NAME, SEQ_IN_INDEX  from STATISTICS C where C.TABLE_SCHEMA = '".$sourceName."' and C.TABLE_NAME = '".$inxtSource->TABLE_NAME."' and C.INDEX_NAME = '".$inxtSource->INDEX_NAME."' order by SEQ_IN_INDEX ; ";
				$objResulIndex  = $dbConectInformationSchema->query($sql)->getResult();
				
				
				foreach($objResulIndex AS $inxx)
				{
					array_push($arraycolumn,"`".$inxx->COLUMN_NAME."`");
				}
				
				
				$columnx 		= implode(",",$arraycolumn);
				$sql 			= "ALTER TABLE `".$targetName."`.`".$inxtSource->TABLE_NAME."` ADD INDEX `".$inxtSource->INDEX_NAME."`(".$columnx."); ";
				echo $sql." PREVIEW<br/>";
				$dbConectTarget->query($sql);
				echo $sql." SUCCESS<br/>";
			}
			
		}
		echo "<br/><br/><br/><br/>";
		
		
		
		//crear funciones en destino procedimiento
		foreach($objListRoutineSource AS $routine)
		{
			
			$return = "";
			if($routine->ROUTINE_TYPE == "FUNCTION")
			{
				$return = "RETURNS ".$routine->DTD_IDENTIFIER;	
			}
			
			$modif = "";
			if($routine->ROUTINE_TYPE == "PROCEDURE")
			{
				$modif = " 
					MODIFIES SQL DATA 
					SQL SECURITY INVOKER    
					
				";
			}
			
			$parameter 			= "";
			$array              = array();
			$sql 				= "SELECT * FROM PARAMETERS WHERE SPECIFIC_SCHEMA = '".$sourceName."' AND SPECIFIC_NAME = '".$routine->ROUTINE_NAME."' ";
			$objListParameter 	= $dbConectInformationSchema->query($sql)->getResult();
			foreach($objListParameter as $para)
			{
				if($para->PARAMETER_NAME != null)
				{
					if ($para->ROUTINE_TYPE == "FUNCTION")
					{
						array_push($array,
							" 
							"."`".$para->PARAMETER_NAME."` ".$para->DTD_IDENTIFIER.
							"
							"
						);
					}
					if ($para->ROUTINE_TYPE == "PROCEDURE")
					{
						array_push($array,
							" 
							".$para->PARAMETER_MODE." `".$para->PARAMETER_NAME."` ".$para->DTD_IDENTIFIER.
							"
							"
						);
					}
				}
			}
			$parameter 			= implode(",",$array);
			
			
			
			$sql 	= "";
			$sql 	= "
			CREATE DEFINER = CURRENT_USER ".$routine->ROUTINE_TYPE." `".$routine->ROUTINE_NAME."`(".$parameter.") ".$return." ".$modif."
			".$routine->ROUTINE_DEFINITION."
			";
			
			
			echo $sql;						
			$dbConectTarget->query($sql);
			echo "</br>".$routine->ROUTINE_NAME." SUCCESS</br>";
			echo "</br></br></br></br>";
					
		}
		
		//crear vistas en destino 
		foreach($objListViewSource as $view)
		{
			
			$sql    	= "SELECT * FROM VIEWS C WHERE C.TABLE_SCHEMA = '".$sourceName."' AND C.TABLE_NAME = '".$view->TABLE_NAME."'; ";
			$objView 	= $dbConectInformationSchema->query($sql)->getRow();
			
			if($objView)
			{
				$sql 	= "";
				$sql 	= "CREATE VIEW `".$targetName."`.`".$view->TABLE_NAME."` AS  ".$objView->VIEW_DEFINITION."  ;";
				
				
				echo $sql;						
				$dbConectTarget->query($sql);
				echo "</br>".$view->TABLE_NAME." SUCCESS</br>";
				echo "</br></br>";
			}
			
		}
		echo "<br/><br/><br/><br/>";		
		
		
		//eliminar campos temporales
		foreach($objListTableSource as $table)
		{
			$sql	= "select * from COLUMNS C where C.TABLE_SCHEMA = '".$targetName."' and C.TABLE_NAME = '".$table->TABLE_NAME."' and c.COLUMN_NAME =  'foreach'  ; ";			
			$resusl = $dbConectInformationSchema->query($sql)->getRow();			
			if($resusl)
			{
			
				$sql = "ALTER TABLE `".$targetName."`.`".$table->TABLE_NAME."` DROP COLUMN `foreach`;";
				echo $sql." PREVIEW</br></br>";
				
				$dbConectTarget->query($sql);
				echo "DROP COLUMN foreach SUCCESS</br>";
				echo "</br></br>";
			}
		}
		
		
		
		echo "<br/><br/><br/><br/>";	
		echo "SUCCESS";
		echo "<br/><br/><br/><br/>";	
		
		//crear triger en destino 
		
	}
	
	
	function merge_of_posme_merge_to_posme_data_insert_and_update()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		$this->dbOrigen							= \Config\Database::connect("merge");
		$this->sourceName						= DB_BDNAME_MERGE;
		$this->dbDestino						= \Config\Database::connect();
		$this->targetName						= DB_BDNAME;
		
		
		$this->dbConectTarget 					= $this->dbDestino;
		$this->dbConectSource					= $this->dbOrigen;
		$this->dbConectInformationSchema		= \Config\Database::connect("information_schema");		
		$this->forgeOrigen						= \Config\Database::forge($this->dbOrigen);	
		$this->forgeTarget						= \Config\Database::forge($this->dbConectTarget);	
		
		$dbDestino			= $this->dbDestino;
		$dbOrigen			= $this->dbOrigen;
		$forge 				= $this->forgeOrigen;			
		$sourceName 		= $this->request->getGet('sourceName');
		$targetName 		= $this->request->getGet('targetName');
		
		if ($sourceName !== null || $targetName !== null ) 
		{
			echo "Sincronizndo BASE; ".$sourceName." ---> ".$targetName."</br>";			
			//Seguridad: solo nombres de base de datos válidos (evita inyección SQL)
			if(!$this->merge_isSafeIdentifier($sourceName) || !$this->merge_isSafeIdentifier($targetName))
			return "Nombre de base de datos inválido.";
			$dbOrigen->query("USE ".$sourceName.";");
			$dbDestino->query("USE ".$targetName.";");
			
		}
		 
		$tablasSync 		= array();
		array_push($tablasSync,"tb_catalog_item_convertion:catalogItemConvertionID");
		array_push($tablasSync,"tb_catalog_item:catalogItemID");
		array_push($tablasSync,"tb_catalog:catalogID");				
		array_push($tablasSync,"tb_company_component_flavor:companyComponentFlavorID");		
		array_push($tablasSync,"tb_company_component_item_dataview:companyComponentItemDataviewID");
		array_push($tablasSync,"tb_company_default_dataview:companyDefaultDataviewID");
		array_push($tablasSync,"tb_company_dataview:companyDataViewID");
		array_push($tablasSync,"tb_dataview:dataViewID");		
		array_push($tablasSync,"tb_credit_line:creditLineID");		
		array_push($tablasSync,"tb_company_subelement_audit:companySubelementAudiID");
		array_push($tablasSync,"tb_company_subelement_obligatory:companySubelementObligatoryID");
		array_push($tablasSync,"tb_element_type:elementTypeID");
		array_push($tablasSync,"tb_subelement:subElementID");
		array_push($tablasSync,"tb_menu_element:menuElementID");
		array_push($tablasSync,"tb_element:elementID");
		array_push($tablasSync,"tb_type_menu_element:typeMenuElementID");		
		array_push($tablasSync,"tb_component_autorization:componentAutorizationID");
		array_push($tablasSync,"tb_component_autorization_detail:componentAurotizationDetailID");		
		array_push($tablasSync,"tb_component_element:componentElementID");	
		array_push($tablasSync,"tb_company_component:companyComponentID");
		array_push($tablasSync,"tb_component:componentID");		
		array_push($tablasSync,"tb_tag:tagID");				
		array_push($tablasSync,"tb_transaction_profile_detail:profileDetailID");
		array_push($tablasSync,"tb_transaction_concept:conceptID");
		array_push($tablasSync,"tb_transaction_causal:transactionCausalID");
		array_push($tablasSync,"tb_transaction:transactionID");		
		array_push($tablasSync,"tb_workflow_stage_relation:workflowStageRelationID");
		array_push($tablasSync,"tb_workflow_stage:workflowStageID");
		array_push($tablasSync,"tb_workflow:workflowID");
		array_push($tablasSync,"tb_workflow_stage_affect:workflowStageAffectID");
		array_push($tablasSync,"tb_reporting:reportID");
		array_push($tablasSync,"tb_reporting_result:reportResultID");
		array_push($tablasSync,"tb_reporting_parameter:reportParameterID");
		array_push($tablasSync,"tb_company_page_setting:customPageID");
		array_push($tablasSync,"tb_company_page_setting_large:customPageLargeID");
		
		
		
		
		
		
		$recordSet = $dbDestino->query("SET FOREIGN_KEY_CHECKS=0;");		
		foreach($tablasSync as $key => $tabla)
		{
			$tabla      = explode(":",$tabla)[0];
			echo "merge:".$tabla."</br>";
			$fields 	= $dbOrigen->getFieldNames($tabla);
			$columns 	= "";
		
			foreach ($fields as $field) {
					$columns = $columns.$field.",";
			}
			
					
		
			$columns 	= substr($columns,0,strlen($columns)-1);
			$sql 		= "";
			$sql 		= sprintf("select ".$columns." ");		
			$sql 		= $sql.sprintf(" from ".$tabla." ");	

			
			//Ejecutar Consulta
			$recordSet = $dbOrigen->query($sql);
			$recordSet = $recordSet->getResult();
			
			
			$builder   = $dbDestino->table($tabla);		
			$builder->upsertBatch($recordSet);
			
		}
		$recordSet = $dbDestino->query("SET FOREIGN_KEY_CHECKS=1;");
		
	}
	function merge_of_posme_merge_to_poeme_data_onlyinsert()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		$this->dbOrigen							= \Config\Database::connect("merge");
		$this->sourceName						= DB_BDNAME_MERGE;
		$this->dbDestino						= \Config\Database::connect();
		$this->targetName						= DB_BDNAME;
		
		
		$this->dbConectTarget 					= $this->dbDestino;
		$this->dbConectSource					= $this->dbOrigen;
		$this->dbConectInformationSchema		= \Config\Database::connect("information_schema");		
		$this->forgeOrigen						= \Config\Database::forge($this->dbOrigen);	
		$this->forgeTarget						= \Config\Database::forge($this->dbConectTarget);	
		
		$dbDestino			= $this->dbDestino;
		$dbOrigen			= $this->dbOrigen;
		$forge 				= $this->forgeOrigen;	
		$sourceName 		= $this->request->getGet('sourceName');
		$targetName 		= $this->request->getGet('targetName');
		
		if ($sourceName !== null || $targetName !== null ) 
		{
			echo "Sincronizndo BASE; ".$sourceName." ---> ".$targetName."</br>";			
			//Seguridad: solo nombres de base de datos válidos (evita inyección SQL)
			if(!$this->merge_isSafeIdentifier($sourceName) || !$this->merge_isSafeIdentifier($targetName))
			return "Nombre de base de datos inválido.";
			$dbOrigen->query("USE ".$sourceName.";");
			$dbDestino->query("USE ".$targetName.";");
			
		}
		
		//Ingreasr los contadores
		$sql 			= "select * from tb_counter order by counterID";
		$objListOrigen 	= $dbOrigen->query($sql)->getResult();
		foreach($objListOrigen as $origenItem)
		{
			$sql		 		= "select * from tb_counter c where c.counterID = ".$origenItem->counterID;
			$objDestinoItem 	= $dbDestino->query($sql)->getRow();
			if(!$objDestinoItem)
			{
				echo $origenItem->serie."  only insert listos </br>";
				$sql 		= "insert into tb_counter (componentID,companyID,branchID,componentItemID,initialValue,currentValue,seed,serie,length) values ( ".$origenItem->componentID.",2,2,0,0,0,0, '".$origenItem->serie."',8 ); ";
				$dbDestino->query($sql);
				
			}
		}
		
		
		//Ingresar los parametros
		$sql 			= "select * from tb_parameter ";
		$objListOrigen 	= $dbOrigen->query($sql)->getResult();
		foreach($objListOrigen as $origenItem)
		{
			$sql		 		= "select * from tb_parameter c where c.parameterID = ".$origenItem->parameterID;
			$objDestinoItem 	= $dbDestino->query($sql)->getRow();
			if(!$objDestinoItem)
			{
				echo $origenItem->name."  only insert listos </br>";
				$sql 		= "insert into tb_parameter (parameterID,`name`,description,isRequiered,isEdited) values ( ".$origenItem->parameterID.",'".$origenItem->name."','".$origenItem->description."',".$origenItem->isRequiered.",".$origenItem->isEdited." ); ";
				$dbDestino->query($sql);
				
			}
		}
		
		
		//Ingresar los company parameter 
		$sql 			= "select * from tb_company_parameter ";
		$objListOrigen 	= $dbOrigen->query($sql)->getResult();
		foreach($objListOrigen as $origenItem)
		{
			$sql		 		= "select * from tb_company_parameter c where c.companyParameterID = ".$origenItem->companyParameterID;
			$objDestinoItem 	= $dbDestino->query($sql)->getRow();
			if(!$objDestinoItem)
			{
				echo $origenItem->display."  only insert listos </br>";
				$sql 		= "insert into tb_company_parameter (parameterID,companyID,display,description,`value`,customValue,companyParameterID) values ( ".$origenItem->parameterID.",".$origenItem->companyID.",'".$origenItem->display."','".$origenItem->description."','".$origenItem->value."','".$origenItem->customValue."', ".$origenItem->companyParameterID." ); ";
				$dbDestino->query($sql);
				
			}
		}
		
		
		
		
		
		
		echo "SUCCESS";
		
	}
	function merge_of_posme_merge_to_posme_data_delete()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		
	
		$this->dbOrigen							= \Config\Database::connect("merge");
		$this->sourceName						= DB_BDNAME_MERGE;
		$this->dbDestino						= \Config\Database::connect();
		$this->targetName						= DB_BDNAME;
		
		
		$this->dbConectTarget 					= $this->dbDestino;
		$this->dbConectSource					= $this->dbOrigen;
		$this->dbConectInformationSchema		= \Config\Database::connect("information_schema");		
		$this->forgeOrigen						= \Config\Database::forge($this->dbOrigen);	
		$this->forgeTarget						= \Config\Database::forge($this->dbConectTarget);	
		
		$dbDestino			= $this->dbDestino;
		$dbOrigen			= $this->dbOrigen;
		$forge 				= $this->forgeOrigen;
		$sourceName 		= $this->request->getGet('sourceName');
		$targetName 		= $this->request->getGet('targetName');
		
		if ($sourceName !== null || $targetName !== null ) 
		{
			echo "Sincronizndo BASE; ".$sourceName." ---> ".$targetName."</br>";			
			//Seguridad: solo nombres de base de datos válidos (evita inyección SQL)
			if(!$this->merge_isSafeIdentifier($sourceName) || !$this->merge_isSafeIdentifier($targetName))
			return "Nombre de base de datos inválido.";
			$dbOrigen->query("USE ".$sourceName.";");
			$dbDestino->query("USE ".$targetName.";");
			
		}
		
		
		//tb_counter
		$tablasSync 		= array();
		array_push($tablasSync,"tb_catalog_item_convertion:catalogItemConvertionID");
		array_push($tablasSync,"tb_catalog_item:catalogItemID");
		array_push($tablasSync,"tb_catalog:catalogID");		
		array_push($tablasSync,"tb_company_parameter:companyParameterID");
		array_push($tablasSync,"tb_parameter:parameterID");				
		array_push($tablasSync,"tb_company_component_flavor:companyComponentFlavorID");		
		array_push($tablasSync,"tb_company_component_item_dataview:companyComponentItemDataviewID");
		array_push($tablasSync,"tb_company_default_dataview:companyDefaultDataviewID");
		array_push($tablasSync,"tb_company_dataview:companyDataViewID");
		array_push($tablasSync,"tb_dataview:dataViewID");		
		array_push($tablasSync,"tb_credit_line:creditLineID");	
		array_push($tablasSync,"tb_company_subelement_audit:companySubelementAudiID");
		array_push($tablasSync,"tb_company_subelement_obligatory:companySubelementObligatoryID");
		array_push($tablasSync,"tb_element_type:elementTypeID");
		array_push($tablasSync,"tb_subelement:subElementID");
		array_push($tablasSync,"tb_menu_element:menuElementID");
		array_push($tablasSync,"tb_element:elementID");
		array_push($tablasSync,"tb_type_menu_element:typeMenuElementID");		
		array_push($tablasSync,"tb_component_autorization:componentAutorizationID");
		array_push($tablasSync,"tb_component_autorization_detail:componentAurotizationDetailID");		
		array_push($tablasSync,"tb_component_element:componentElementID");	
		array_push($tablasSync,"tb_company_component:companyComponentID");
		array_push($tablasSync,"tb_component:componentID");
		array_push($tablasSync,"tb_company:companyID");			
		array_push($tablasSync,"tb_tag:tagID");				
		array_push($tablasSync,"tb_transaction_profile_detail:profileDetailID");
		array_push($tablasSync,"tb_transaction_concept:conceptID");
		array_push($tablasSync,"tb_transaction_causal:transactionCausalID");
		array_push($tablasSync,"tb_transaction:transactionID");		
		array_push($tablasSync,"tb_workflow_stage_relation:workflowStageRelationID");
		array_push($tablasSync,"tb_workflow_stage:workflowStageID");
		array_push($tablasSync,"tb_workflow:workflowID");
		array_push($tablasSync,"tb_workflow_stage_affect:workflowStageAffectID");
		array_push($tablasSync,"tb_reporting:reportID");
		array_push($tablasSync,"tb_reporting_result:reportResultID");
		array_push($tablasSync,"tb_reporting_parameter:reportParameterID");
		array_push($tablasSync,"tb_company_page_setting:customPageID");
		array_push($tablasSync,"tb_company_page_setting_large:customPageLargeID");
		
		$recordSet = $dbDestino->query("SET FOREIGN_KEY_CHECKS=0;");	
		foreach($tablasSync as $key => $tabla)
		{
			
			$tabla 		= explode(":",$tabla );
			$primaryKey	= $tabla[1];
			$tabla		= $tabla[0];
			echo "delete".$tabla."</br>";
			
			$fields 	= $dbOrigen->getFieldNames($tabla);
			$columns 	= "";

			foreach ($fields as $field) {
					$columns = $columns.$field.",";
			}
			
					
		
			$columns 	= substr($columns,0,strlen($columns)-1);
			$sql 		= "";
			$sql 		= sprintf("select ".$columns." ");		
			$sql 		= $sql.sprintf(" from ".$tabla." ");		
		
			
			//Ejecutar Consulta
			$recordSet = $dbOrigen->query($sql);
			$recordSet = $recordSet->getResult();
			
			
			$arrayDelete  	= array_column($recordSet,$primaryKey);	
			$builder 		= $dbDestino->table($tabla);
			$builder->whereNotIn($primaryKey,$arrayDelete);			
			$builder->delete();
		
		}
		$recordSet = $dbDestino->query("SET FOREIGN_KEY_CHECKS=1;");	
		echo "SUCCESS</br>";
		
		
	}
	
	function merge_of_posme_merge_to_posme_initialize()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		$this->dbOrigen      = \Config\Database::connect("merge");
		$this->sourceName    = DB_BDNAME_MERGE;
		$this->dbDestino     = \Config\Database::connect();
		$this->targetName    = DB_BDNAME;

		$this->dbConectTarget = $this->dbDestino;
		$this->dbConectSource = $this->dbOrigen;
		$this->forgeOrigen    = \Config\Database::forge($this->dbOrigen);
		$this->forgeTarget    = \Config\Database::forge($this->dbConectTarget);

		$sourceName = $this->sourceName;
		$targetName = $this->targetName;
		$dbConectTarget = $this->dbConectTarget;
		$dbConectSource = $this->dbConectSource;
		$forge = $this->forgeTarget;

		echo "<div style='padding:10px; background:#e6f7ff; border-left:5px solid #1890ff; margin:10px 0;'>
				🔗 Iniciando conexión a las bases de datos.
			  </div>";

		$sql = "SELECT 'Probando conexión -- limpia archivos del servidor para disminuir los inodos' AS x";
		echo "<h2 style='color:#1890ff;'>📝 Consulta de prueba:</h2>";
		echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #d9d9d9; overflow:auto;'>" . htmlentities($sql) . "</pre>";

		$dbConectTarget->query($sql);

		echo "<div style='padding:10px; background:#f6ffed; border-left:5px solid #52c41a; margin:10px 0;'>
				✅ Conexión de prueba ejecutada correctamente.
			  </div>";

		$dirParent = opendir(PATH_FILE_OF_APP . "/../../../../");

		while ($currentParent = readdir($dirParent)) {
			if (
				$currentParent != "." && 
				$currentParent != ".." && 
				$currentParent != "tutoriales" &&
				$currentParent != "index.html" 
			) 
			{
				echo "<hr>";
				$path_file_of_app = PATH_FILE_OF_APP . "/../../../../" . $currentParent;
				echo "<div style='padding:10px; background:#e6f7ff; border-left:5px solid #1890ff; margin:10px 0;'>
						🔍 Procesando compañía: <strong>{$currentParent}</strong>
						<br>Ruta: {$path_file_of_app}
					  </div>";

				// LIMPIANDO LOGS
				echo "<h3 style='color:#faad14;'>🧹 Limpiando logs...</h3>";
				$dir = opendir($path_file_of_app . "/writable/logs");
				while ($current = readdir($dir)) {
					if ($current != "." && $current != ".." && $current != "index.html") {
						unlink($path_file_of_app . "/writable/logs/" . $current);
					}
				}
			
				
				// LIMPIANDO CACHE
				echo "<h3 style='color:#faad14;'>🧹 Limpiando cache...</h3>";
				$dir = opendir($path_file_of_app . "/writable/cache");
				while ($current = readdir($dir)) {
					if ($current != "." && $current != ".." && $current != "index.html") {
						unlink($path_file_of_app . "/writable/cache/" . $current);
					}
				}

				// LIMPIANDO SESSION
				echo "<h3 style='color:#faad14;'>🧹 Limpiando sessions...</h3>";
				$dir = opendir($path_file_of_app . "/writable/session");
				while ($current = readdir($dir)) {
					if ($current != "." && $current != ".." && $current != "index.html") {
						unlink($path_file_of_app . "/writable/session/" . $current);
					}
				}

				// LIMPIANDO UPLOADS
				echo "<h3 style='color:#faad14;'>🧹 Limpiando uploads...</h3>";
				$dir = opendir($path_file_of_app . "/writable/uploads");
				while ($current = readdir($dir)) {
					if ($current != "." && $current != ".." && $current != "index.html") {
						if (is_dir($path_file_of_app . "/writable/uploads/" . $current)) {
							$dir2 = opendir($path_file_of_app . "/writable/uploads/" . $current);
							while ($current2 = readdir($dir2)) {
								if ($current2 != "." && $current2 != "..") {
									if (is_dir($path_file_of_app . "/writable/uploads/" . $current . "/" . $current2))
										$this->eliminarDirectorio($path_file_of_app . "/writable/uploads/" . $current . "/" . $current2);
									else
										unlink($path_file_of_app . "/writable/uploads/" . $current . "/" . $current2);
								}
							}
						} else {
							unlink($path_file_of_app . "/writable/uploads/" . $current);
						}
					}
				}

				// LIMPIANDO DEBUGBAR
				echo "<h3 style='color:#faad14;'>🧹 Limpiando debugbar...</h3>";
				$dir = opendir($path_file_of_app . "/writable/debugbar");
				while ($current = readdir($dir)) {
					if ($current != "." && $current != ".." && $current != "index.html") {
						unlink($path_file_of_app . "/writable/debugbar/" . $current);
					}
				}

				// LIMPIANDO ARCHIVOS DE COMPONENTES
				echo "<h3 style='color:#faad14;'>🧹 Limpiando archivos de componentes...</h3>";
				// Aquí va tu lógica extendida con los mismos echo con bloques y colores

				// CREANDO CARPETAS DE COMPONENTES
				echo "<div style='padding:10px; background:#f6ffed; border-left:5px solid #52c41a; margin:10px 0;'>
						📁 Creando carpetas de componentes...
					  </div>";
				for ($i = 1; $i <= 131; $i++) {
					$documentoPath = $path_file_of_app . "/public/resource/file_company/company_" . APP_COMPANY . "/component_" . $i;
					if (!file_exists($documentoPath)) {
						mkdir($documentoPath, 0755, true);
					}
					
					//Eliminarlos
					$this->eliminarDirectorio($documentoPath);
					
					//Crearlo
					mkdir($documentoPath, 0755, true);
				}

				$documentoPath = $path_file_of_app . "/public/resource/file_company/company_" . APP_COMPANY . "/component_1/component_item_0";
				if (!file_exists($documentoPath)) {
					mkdir($documentoPath, 0755, true);
				}
				
				
				echo "<div style='padding:10px; background:#f6ffed; border-left:5px solid #52c41a; margin:10px 0;'>
						📁 Eliminandos archivos de backup
					  </div>";
				if (file_exists($documentoPath)) {
					$this->eliminarDirectorio($documentoPath);
				}
				if (!file_exists($documentoPath)) {
					mkdir($documentoPath, 0755, true);
				}
				
				
				echo "<div style='padding:10px; background:#f6ffed; border-left:5px solid #52c41a; margin:10px 0;'>
						✅ Proceso completado para: <strong>{$currentParent}</strong>
					  </div>";
			}
		}

		echo "<div style='padding:15px; background:#f6ffed; border-left:5px solid #52c41a; margin:20px 0; font-size:1.2em;'>
				✅ <strong>Inicialización COMPLETADA con éxito.</strong>
			  </div>";
	}

	function merge_of_posme_merge_to_posme_backup()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		$objComponentSeguridad			= $this->core_web_tools->getComponentIDBy_ComponentName("0-SEGURIDAD");
		if(!$objComponentSeguridad)
		throw new \Exception("EL COMPONENTE '0-SEGURIDAD' NO EXISTE...");
		
		
		$documentoPath = PATH_FILE_OF_APP."/company_2/component_".$objComponentSeguridad->componentID."/component_item_0";	
		if (!file_exists($documentoPath)) 
		{
			// Crear el directorio con permisos 0755 (y recursivo por si faltan subdirectorios)
			if (!mkdir($documentoPath, 0755, true)) 
			{
				throw new \Exception("❌ No se pudo crear el directorio: $documentoPath");
			}
		}
		
		chmod($documentoPath, 0755);
		$core_mysql_dump	= new core_mysql_dump("mysql:host=".DB_SERVER.";dbname=".DB_BDNAME,DB_USER,DB_PASSWORD);
		$sqlBacku           = $documentoPath."/".DB_BDNAME."_DB_".date("Ymd_H_i_s").".sql";
		$core_mysql_dump->start($sqlBacku);
		
		
	}
	
	function merge_of_posme_merge_to_posme_aplicar_parameter()
	{
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		// Conexiones y configuración
		$this->dbOrigen                         = \Config\Database::connect("merge");
		$this->sourceName                       = DB_BDNAME_MERGE;
		$this->dbDestino                        = \Config\Database::connect();
		$this->targetName                       = DB_BDNAME;

		$this->dbConectTarget                   = $this->dbDestino;
		$this->dbConectSource                   = $this->dbOrigen;
		$this->dbConectInformationSchema        = \Config\Database::connect("information_schema");
		$this->forgeOrigen                      = \Config\Database::forge($this->dbOrigen);
		$this->forgeTarget                      = \Config\Database::forge($this->dbConectTarget);

		$dbDestino          = $this->dbDestino;
		$dbOrigen           = $this->dbOrigen;
		$forge              = $this->forgeOrigen;
		$sourceName         = $this->request->getGet('sourceName');
		$fileSourceName     = "";
		$targetName         = $this->request->getGet('targetName');
		$fileTargetName     = "";
		$syncStructure      = $this->request->getGet('syncStructure');

		//Seguridad: los nombres de archivo no pueden salir de file_sql
		if(($sourceName !== null && !$this->merge_isSafeName($sourceName)) || !$this->merge_isSafeName($targetName))
		return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>❌ ERROR: Nombre de archivo inválido.</div>";

		if($syncStructure == "0")
		{
			// Leer archivo origen
			$ruta = PATH_FILE_OF_APP . "/../../../public/resource/file_sql/" . $sourceName;
			if (!file_exists($ruta)) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: El archivo origen no existe.
						</div>";
			}

			$sqlString = file_get_contents($ruta);
			if ($sqlString === false) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: No se pudo leer el archivo origen.
						</div>";
			}

			// Obtener nombre de base de datos origen
			$pattern = '/\/\*BD:\s*(.*?)\s*\*\//';
			if (preg_match($pattern, $sqlString, $matches)) {
				$sourceName = trim($matches[1]);
				echo "<div style='padding:10px; background:#e6f7ff; border-left:5px solid #1890ff; margin:10px 0;'>
						✔ Base de datos ORIGEN detectada: <strong>{$sourceName}</strong>
					  </div>";
			} else {
				echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						⚠ No se encontró el marcador origen <code>/*BD: ... */</code>.
					  </div>";
			}

			// Leer archivo destino
			$ruta = PATH_FILE_OF_APP . "/../../../public/resource/file_sql/" . $targetName;
			if (!file_exists($ruta)) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: El archivo destino no existe.
						</div>";
			}

			$sqlStringDestino = file_get_contents($ruta);
			if ($sqlStringDestino === false) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: No se pudo leer el archivo destino.
						</div>";
			}

			// Obtener nombre de base de datos destino
			if (preg_match($pattern, $sqlStringDestino, $matches)) {
				$targetName = trim($matches[1]);
				echo "<div style='padding:10px; background:#e6f7ff; border-left:5px solid #52c41a; margin:10px 0;'>
						✔ Base de datos DESTINO detectada: <strong>{$targetName}</strong>
					  </div>";
			} else {
				echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						⚠ No se encontró el marcador destino <code>/*BD: ... */</code>.
					  </div>";
			}

			// Si ambas BD se encontraron
			if ($sourceName !== null || $targetName !== null) {
				echo "<div style='padding:10px; background:#f6ffed; border-left:5px solid #52c41a; margin:10px 0;'>
						🔄 Sincronizando: <strong>{$sourceName}</strong> &rarr; <strong>{$targetName}</strong>
					  </div>";

				//Seguridad: el nombre viene de la cabecera del archivo; validar antes de usarlo
				if(!$this->merge_isSafeIdentifier(explode(":", $targetName)[0]))
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>❌ ERROR: Nombre de base de datos inválido en la cabecera.</div>";
				$dbDestino->query("USE " . (explode(":", $targetName)[0]) . ";");

				echo "<h2 style='color:#1890ff;'>📝 String Origen:</h2>";
				echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #d9d9d9; overflow:auto; max-height:300px;'>" .
					htmlentities($sqlString) .
					"</pre>";

				echo "<h2 style='color:#1890ff;'>📝 String Destino:</h2>";
				echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #d9d9d9; overflow:auto; max-height:300px;'>" .
					htmlentities($sqlStringDestino) .
					"</pre>";

				// Ejecutar consultas origen
				$mysqli = $dbDestino->connID;
				if ($mysqli->multi_query($sqlString)) {
					do {
						if ($result = $mysqli->store_result()) {
							$result->free();
						}
					} while ($mysqli->next_result());
				} else {
					echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ Error ejecutando origen: {$mysqli->error}
						  </div>";
				}

				// Ejecutar consultas destino
				$mysqli = $dbDestino->connID;
				if ($mysqli->multi_query($sqlStringDestino)) {
					do {
						if ($result = $mysqli->store_result()) {
							$result->free();
						}
					} while ($mysqli->next_result());
				} else {
					echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ Error ejecutando destino: {$mysqli->error}
						  </div>";
				}
			}

		}
		
		
		// Crear procedimientos, vistas, funciones
		if($syncStructure == "1")
		{
			// Leer archivo destino
			$ruta = PATH_FILE_OF_APP . "/../../../public/resource/file_sql/" . $targetName;
			if (!file_exists($ruta)) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: El archivo destino no existe.
						</div>";
			}

			$sqlStringDestino = file_get_contents($ruta);
			if ($sqlStringDestino === false) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: No se pudo leer el archivo destino.
						</div>";
			}

			// Obtener nombre de base de datos destino
			$pattern = '/\/\*BD:\s*(.*?)\s*\*\//';
			if (preg_match($pattern, $sqlStringDestino, $matches)) {
				$targetName = trim($matches[1]);
				echo "<div style='padding:10px; background:#e6f7ff; border-left:5px solid #52c41a; margin:10px 0;'>
						✔ Base de datos DESTINO detectada: <strong>{$targetName}</strong>
					  </div>";
			} else {
				echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						⚠ No se encontró el marcador destino <code>/*BD: ... */</code>.
					  </div>";
			}
			
			
			$ruta = PATH_FILE_OF_APP . "/../../../public/resource/file_sql/script_sincronization_procedure_vista_funciones.sql";
			echo "<h2 style='color:#faad14;'>⚙ Sincronizando estructuras y procedimientos, vistas y triggers...</h2>";

			if (!file_exists($ruta)) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: El archivo de procedimientos no existe.
						</div>";
			}

			$sqlString = file_get_contents($ruta);
			if ($sqlString === false) {
				return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
							❌ ERROR: No se pudo leer el archivo de procedimientos.
						</div>";
			}

			echo "<h2 style='color:#1890ff;'>📝 Script:</h2>";
			echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #d9d9d9; overflow:auto; max-height:300px;'>" .
				htmlentities($sqlString) .
				"</pre>";
				
			$mysqli = $dbDestino->connID;
			if ($mysqli->multi_query($sqlString)) {
				do {
					if ($result = $mysqli->store_result()) {
						$result->free();
					}
				} while ($mysqli->next_result());
			} else {
				echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						❌ Error ejecutando procedimientos: {$mysqli->error}
					  </div>";
			}
		}

		echo "<div style='padding:15px; background:#f6ffed; border-left:5px solid #52c41a; margin:20px 0; font-size:1.2em;'>
				✅ <strong>Sincronización COMPLETADA con éxito.</strong>
			  </div>";
	}


	function submitapp()
    {
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;
		$dir 		= "../../";
		$folders 	= array_filter(glob($dir . '*'), 'is_dir');

		//Lista de companiñas
		$companys		= "";
		$companys    	.= '<option selected value="demo">demo</option>';
		foreach ($folders as $folder) {
			// Extrae solo el nombre de la carpeta
			$folderName = basename($folder);
			$companys 	=  $companys.'<option value="' . htmlspecialchars($folderName) . '">' . htmlspecialchars($folderName) . '</option>';
		}


		//Lista de parametros sql
		$dir 		= "../../public/public/resource/file_sql/";
		$files 		= glob($dir . 'actualizar_parametro*.sql'); // Busca archivos que empiecen con 'parametro' y terminen en .sql
		$sqlfile 	= "";
		$sqlfile    .= '<option selected value="actualizar_parametro_demo">actualizar_parametro_demo</option>';
		foreach ($files as $file) {
			$fileName = basename($file);
			$sqlfile  .= '<option value="' . htmlspecialchars($fileName) . '">' . htmlspecialchars($fileName) . '</option>';
		}


        echo '
        <div style="padding:20px; max-width:600px; margin:auto; border:1px solid #d9d9d9; border-radius:5px;">
            <h2 style="color:#1890ff;">🚀 Subir parámetros y archivos ZIP</h2>
            <form method="post" action="' . base_url('core_merge/submitprocesapp') . '" enctype="multipart/form-data">
                <div style="margin-bottom:15px;">
                    <label>Nombre de carpeta ftp:</label><br>
					<select name="company_name" style="width:100%; padding:8px; border:1px solid #d9d9d9; border-radius:4px;">
						'.$companys.'
					</select>                    
                </div>
                <div style="margin-bottom:15px;">
                    <label>Nombre de archivo .sql de parametro:</label><br>                    
					<select name="param_file" style="width:100%; padding:8px; border:1px solid #d9d9d9; border-radius:4px;">
						'.$sqlfile.'
					</select> 
                </div>
                <div style="margin-bottom:15px;">
                    <label>Archivo ZIP:</label><br>
                    <input type="file" name="zip_file" accept=".zip" style="width:100%;">
                </div>
                <button type="submit" style="background:#1890ff; color:#fff; border:none; padding:10px 20px; border-radius:4px;">
                    📤 Subir y procesar
                </button>
            </form>
        </div>
        ';
    }

    function submitprocesapp()
    {
		//Seguridad: solo administrador autenticado
		if(($deny = $this->merge_denyAccess()) !== null) return $deny;

        helper(['filesystem']);
		$companyName = $this->request->getPost('company_name');
		$paramFile   = $this->request->getPost('param_file');
		$zipFile     = $this->request->getFile('zip_file');

		//Validar nombres recibidos (evita rutas fuera de la carpeta de compañías)
		if(!$this->merge_isSafeName($companyName) || !$this->merge_isSafeName($paramFile))
		{
			return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>❌ Nombre de compañía o de archivo inválido.</div>";
		}

		// Crear carpeta de trabajo
		$kkPath = WRITEPATH . 'uploads/';
		if (!is_dir($kkPath)) {
			mkdir($kkPath, 0755, true);
		}

		$descomprimido = false;

		// 👉 Si viene archivo ZIP
		if ($zipFile && $zipFile->isValid()) {
			$zipFile->move($kkPath);
			$zipFilePath = $kkPath . $zipFile->getName();

			$zip = new \ZipArchive;
			if ($zip->open($zipFilePath) === TRUE) {
				//Seguridad (Zip Slip): rechazar el ZIP si alguna entrada sale de la carpeta de trabajo o es un enlace
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$entryName = $zip->getNameIndex($i);
					if (substr($entryName, -1) === '/') continue;
					if (\App\Libraries\core_web_deploy\core_deploy_package::safeRelativePath($entryName) === null || \App\Libraries\core_web_deploy\core_deploy_package::isSymlink($zip, $i)) {
						$zip->close();
						unlink($zipFilePath);
						return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>❌ ZIP rechazado: contiene rutas peligrosas.</div>";
					}
				}
				$zip->extractTo($kkPath);
				$zip->close();
				unlink($zipFilePath);

				echo "<div style='padding:15px; background:#f6ffed; border-left:5px solid #52c41a; margin:20px 0;'>
						✅ Archivos descomprimidos y ZIP eliminado correctamente.
					  </div>";
				$descomprimido = true;
			} else {
				echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						❌ Error al descomprimir el archivo ZIP.
					  </div>";
				return;
			}
		} 
		else 
		{
			echo "<div style='padding:10px; background:#fffbe6; border-left:5px solid #faad14; margin:10px 0;'>
					⚠ No se subió ZIP. Usando archivos existentes en carpeta de trabajo.
				  </div>";
		}

		// ✅ Si hay archivos existentes o se descomprimió, hacer copias:
		// 1️⃣ Archivo individual Routes.php
		$archivoOrigen  = $kkPath . 'app/Config/Routes.php';
		$archivoDestino = $kkPath . '../../../' . $companyName . '/app/Config/Routes.php';
		if (file_exists($archivoOrigen)) {
			copy($archivoOrigen, $archivoDestino);
			echo "<div style='padding:10px;background:#e6f7ff;border-left:5px solid #1890ff;margin:10px 0;'>
					✔ Archivo copiado a {$archivoDestino}
				  </div>";
		} else {
			echo "<div style='padding:10px;background:#fff1f0;border-left:5px solid #ff4d4f;margin:10px 0;'>
					❌ Archivo no existe: {$archivoOrigen}
				  </div>";
		}

		// ✅ Carpetas a copiar
		$carpetas = ['Controllers', 'Models', 'Libraries', 'Helpers', 'Views'];

		foreach ($carpetas as $carpeta) {
			$origen  = $kkPath . 'app/' . $carpeta;
			$destino = $kkPath . '../../../' . $companyName . '/app/' . $carpeta;

			if (is_dir($origen)) {
				if (!is_dir($destino)) {
					mkdir($destino, 0755, true);
				}

				$this->recurse_copy($origen, $destino);

				echo "<div style='padding:10px;background:#e6f7ff;border-left:5px solid #1890ff;margin:10px 0;'>
						✔ Carpeta copiada a {$destino}
					  </div>";
			} else {
				echo "<div style='padding:10px;background:#fff1f0;border-left:5px solid #ff4d4f;margin:10px 0;'>
						❌ Carpeta no existe: {$origen}
					  </div>";
			}
		}

		// ✅ Elimina carpeta app de trabajo
		//$this->eliminarDirectorio($kkPath . 'app');
		//echo "<div style='padding:10px;background:#f6ffed;border-left:5px solid #52c41a;margin:10px 0;'>
		//		✔ Carpeta temporal eliminada.
		//	  </div>";


		// Buscar la base de datos destino
		// Leer archivo destino
		$ruta = PATH_FILE_OF_APP . "/../../../public/resource/file_sql/" . $paramFile."";
		
		if (!file_exists($ruta)) {
			return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						❌ ERROR: El archivo destino no existe. {$ruta}
					</div>";
		}

		$sqlStringDestino = file_get_contents($ruta);
		if ($sqlStringDestino === false) {
			return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
						❌ ERROR: No se pudo leer el archivo destino.
					</div>";
		}

		// Obtener nombre de base de datos destino
		$pattern = '/\/\*BD:\s*(.*?)\s*\*\//';		
		if (preg_match($pattern, $sqlStringDestino, $matches)) {
			$targetNameDB 	= trim($matches[1]);	
			$targetNameDB	= explode(":",$targetNameDB)[0];
		} else {
			echo "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>
					⚠ No se encontró el marcador destino <code>/*BD: ... */</code>.
				  </div>";
		}
		
		//Obtener el dominio
		$url 	= base_url(); // Ej: https://dominio.com/proyecto/carpeta/
		$parsed = parse_url($url);
		$domain = $parsed['scheme'] . '://' . $parsed['host'];
		if (isset($parsed['port'])) {
			$domain .= ':' . $parsed['port'];
		}

		


		// ✅ Mostrar enlace final
		echo "<div style='padding:15px; background:#e6f7ff; border-left:5px solid #1890ff; margin:20px 0; font-size:1.1em;'>
				✔ <strong>Nombre de la compañía:</strong> {$companyName}
			  </div>";
		echo "<div style='padding:15px; background:#e6f7ff; border-left:5px solid #1890ff; margin:20px 0; font-size:1.1em;'>
				✔ <strong>Base de datos:</strong> {$targetNameDB}
			  </div>";
		echo "<div style='padding:15px; background:#e6f7ff; border-left:5px solid #1890ff; margin:20px 0; font-size:1.1em;'>
				✔ <strong>Archivo:</strong> {$paramFile}
			  </div>";
			  
		echo "<div style='padding:15px; background:#f0f5ff; border-left:5px solid #40a9ff; margin:20px 0;'>
				🔗 <a href='" . base_url('core_merge/submitapp')."' >👉 Clic aquí volver a cargar</a>
			  </div>";
			  
			  
		//echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
		//		🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_aplicar_parameter?sourceName=actualizar_parametro_001_development_posme.sql&targetName=' . $paramFile) . "&syncStructure=1'>👉 01) Clic aquí para procesar estructuras siteground</a>
		//	  </div>";	
		
		echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
				🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_data_insert_and_update?sourceName=dbno63gzawe8bk&targetName=' . $targetNameDB) . "'>👉 02) Clic aquí para procesar insert and update siteground</a>
			  </div>";
		echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
				🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_poeme_data_onlyinsert?sourceName=dbno63gzawe8bk&targetName=' . $targetNameDB) . "'>👉 03) Clic aquí para procesar only insert siteground</a>
			  </div>";
		echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
				🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_data_delete?sourceName=dbno63gzawe8bk&targetName=' . $targetNameDB) . "'>👉 04) Clic aquí para procesar delete siteground</a>
			  </div>";
		echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
				🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_aplicar_parameter?sourceName=actualizar_parametro_001_development_posme.sql&targetName=' . $paramFile) . "&syncStructure=0'>👉 05) Clic aquí para procesar parámetros de siteground</a>
			  </div>";  
		echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
				🔗 <a target='_blank' href='" . $domain."/v4posme/".$companyName."/public'>👉 06) Ingresar</a>
			  </div>
			  </br></br>
			  ";
			  
		echo "<div style='padding:15px; background:#77DD77; border-left:5px solid yellow; margin:20px 0;'>
				🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_initialize/2')."'>👉 07) Clic aquí para procesar limpieza de archivos siteground</a>
			  </div>";
			  
		//echo "<div style='padding:15px; background:#f0f5ff; border-left:5px solid #40a9ff; margin:20px 0;'>
		//		🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_structure/2') . "'>👉 01) Clic aquí para procesar estructuras</a>
		//	  </div>";			  
		//echo "<div style='padding:15px; background:#f0f5ff; border-left:5px solid #40a9ff; margin:20px 0;'>
		//		🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_data_insert_and_update/2') . "'>👉 02) Clic aquí para procesar insert and update</a>
		//	  </div>";		
		//echo "<div style='padding:15px; background:#f0f5ff; border-left:5px solid #40a9ff; margin:20px 0;'>
		//		🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_poeme_data_onlyinsert/2') . "'>👉 03) Clic aquí para procesar only insert</a>
		//	  </div>";		
		//echo "<div style='padding:15px; background:#f0f5ff; border-left:5px solid #40a9ff; margin:20px 0;'>
		//		🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_data_delete/2') . "'>👉 04) Clic aquí para procesar delete</a>
		//	  </div>";
		//echo "<div style='padding:15px; background:#f0f5ff; border-left:5px solid #40a9ff; margin:20px 0;'>
		//		🔗 <a target='_blank' href='" . base_url('core_merge/merge_of_posme_merge_to_posme_aplicar_parameter?sourceName=actualizar_parametro_001_development_posme.sql&targetName=' . $paramFile) . ".sql&syncStructure=0'>👉 05) Clic aquí para procesar parámetros</a>
		//	  </div>";  
		
    }
	
	
	protected function recurse_copy($src, $dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while (false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					$this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
				} else {
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	
	protected function eliminarDirectorio($dir) {
		if (!file_exists($dir)) {
			return true;
		}
		if (!is_dir($dir)) {
			return unlink($dir);
		}
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if (!$this->eliminarDirectorio($dir . DIRECTORY_SEPARATOR . $item)) return false;
		}
		return rmdir($dir);
	}


	/* =====================================================================
	 * SEGURIDAD DE core_merge
	 * core_merge viaja a todas las instalaciones; sus métodos solo pueden
	 * usarlos administradores autenticados (tb_role.isAdmin = 1).
	 * ===================================================================== */

	/**
	 * Verifica sesión y rol administrador para los métodos heredados (salida HTML).
	 *
	 * @return string|null Mensaje HTML de rechazo o null si tiene acceso.
	 */
	protected function merge_denyAccess()
	{
		if(!$this->core_web_authentication->isAuthenticated())
		{
			$this->response->setStatusCode(401);
			return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>❌ ".USER_NOT_AUTENTICATED."</div>";
		}
		$dataSession = $this->session->get();
		if(!isset($dataSession["role"]) || (int)$dataSession["role"]->isAdmin !== 1)
		{
			$this->response->setStatusCode(403);
			return "<div style='padding:10px; background:#fff1f0; border-left:5px solid #ff4d4f; margin:10px 0;'>❌ ".NOT_ACCESS_CONTROL."</div>";
		}
		return null;
	}

	/**
	 * Nombre de base de datos o identificador SQL seguro.
	 *
	 * @param mixed $value Valor recibido.
	 * @return bool
	 */
	protected function merge_isSafeIdentifier($value)
	{
		return is_string($value) && preg_match('/^[A-Za-z0-9_]{1,64}$/', $value) === 1;
	}

	/**
	 * Nombre de archivo o carpeta sin rutas (sin "/", "\" ni "..").
	 *
	 * @param mixed $value Valor recibido.
	 * @return bool
	 */
	protected function merge_isSafeName($value)
	{
		return is_string($value) && $value !== '' && preg_match('/^[A-Za-z0-9_\-\.]{1,150}$/', $value) === 1 && strpos($value, '..') === false;
	}


	/* =====================================================================
	 * CONSOLA DE DESPLIEGUE REMOTO (core_merge/deploy)
	 * Sincroniza una instalación remota de posMe: archivos de la aplicación,
	 * estructura de la base de datos y datos de configuración.
	 * Requiere: administrador + define('DEPLOY_CONSOLE_ENABLED', true) en
	 * app/Config/Constants.php del equipo local (en los clientes no existe,
	 * así que la consola queda apagada allí).
	 * ===================================================================== */

	/**
	 * Valida que la consola esté habilitada y que el usuario sea administrador.
	 *
	 * @return array Datos de sesión.
	 */
	protected function deploy_guard()
	{
		if(!defined('DEPLOY_CONSOLE_ENABLED') || DEPLOY_CONSOLE_ENABLED !== true)
		throw new \Exception("La consola de despliegue no está habilitada en esta instalación.");

		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);

		$dataSession = $this->session->get();
		if(!isset($dataSession["role"]) || (int)$dataSession["role"]->isAdmin !== 1)
		throw new \Exception(NOT_ACCESS_CONTROL);

		@set_time_limit(600);
		//Liberar la sesión para no bloquear otras pestañas durante procesos largos
		session_write_close();
		return $dataSession;
	}

	/**
	 * Respuesta JSON estándar con el token CSRF renovado.
	 *
	 * @param array  $data    Datos.
	 * @param string $message Mensaje.
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	protected function deploy_json($data = [], $message = SUCCESS)
	{
		return $this->response->setJSON(array(
			'error'   => false,
			'message' => $message,
			'data'    => $data,
			'csrf'    => csrf_hash()
		));
	}

	/**
	 * Respuesta JSON de error.
	 *
	 * @param \Throwable $ex Excepción.
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	protected function deploy_jsonError($ex)
	{
		return $this->response->setJSON(array(
			'error'   => true,
			'message' => $ex->getMessage(),
			'csrf'    => csrf_hash()
		));
	}

	/**
	 * Valor POST (texto) recortado.
	 *
	 * @return string
	 */
	protected function deploy_post($name, $default = '')
	{
		$value = helper_RequestGetValue($this->request->getPost($name), $default);
		return is_string($value) ? trim($value) : $default;
	}

	/**
	 * Pantalla de la consola de despliegue.
	 *
	 * @return string
	 */
	function deploy()
	{
		try
		{
			$dataSession = $this->deploy_guard();

			$dataView["companies"]   = $this->core_web_deploy->getCompanies();
			$dataView["tables"]      = $this->core_web_deploy->getProfileTables();
			$dataView["csrfName"]    = csrf_token();
			$dataView["csrfHash"]    = csrf_hash();
			$dataView["chunkSize"]   = \App\Libraries\core_web_deploy\core_deploy_rules::CHUNK_SIZE;
			$dataView["maxUpload"]   = \App\Libraries\core_web_deploy\core_deploy_rules::MAX_UPLOAD_BYTES;
			$dataView["userName"]    = $dataSession["user"]->nickname;

			$dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]      = $this->core_web_notification->get_message();
			$dataSession["head"]         = /*--inicio view*/ view('core_merge/deploy_head',$dataView);//--finview
			$dataSession["body"]         = /*--inicio view*/ view('core_merge/deploy_body',$dataView);//--finview
			$dataSession["script"]       = /*--inicio view*/ view('core_merge/deploy_script',$dataView);//--finview
			$dataSession["footer"]       = "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
		}
		catch(\Throwable $ex)
		{
			return "<div style='font-family:sans-serif; padding:14px; margin:20px; background:#fdecec; color:#c62828; border-radius:8px;'>".htmlspecialchars($ex->getMessage())."</div>";
		}
	}

	/**
	 * Perfil de conexión de una compañía (sin credenciales) + datos de su script.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_profile()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$company     = $this->core_web_deploy->getCompany($companyKey);
			$profile     = $this->core_web_deploy->getProfilePublic($dataSession["user"]->companyID, $companyKey);
			return $this->deploy_json(array('company' => $company, 'profile' => $profile));
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Guarda el perfil de conexión (credenciales cifradas; vacías = sin cambio).
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_profile_save()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$input       = array();
			foreach(array('agentUrl','transportProtocol','ftpHost','ftpPort','ftpUser','ftpPassword','ftpRootDir','ftpAppDir','dbMode','dbHost','dbPort','dbName','dbUser','dbPassword') as $field)
			{
				$input[$field] = $this->deploy_post($field);
			}
			$tableModes = json_decode((string)$this->request->getPost("tableModes"), true);
			if(is_array($tableModes))
			$input["tableModes"] = $tableModes;

			$this->core_web_deploy->saveProfile($dataSession["user"]->companyID, $dataSession["user"]->userID, $companyKey, $input);
			$profile = $this->core_web_deploy->getProfilePublic($dataSession["user"]->companyID, $companyKey);
			return $this->deploy_json(array('profile' => $profile), "Conexión guardada.");
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Genera un secreto nuevo para el agente; se muestra una sola vez.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_secret()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$secret      = $this->core_web_deploy->generateAgentSecret($dataSession["user"]->companyID, $dataSession["user"]->userID, $companyKey);
			return $this->deploy_json(array(
				'secret'      => $secret,
				'configPath'  => 'writable/'.\App\Libraries\core_web_deploy\core_deploy_rules::AGENT_DIR.'/agent_config.php',
				'configFile'  => \App\Libraries\core_web_deploy::agentConfigContent($secret)
			), "Secreto generado. Cópielo ahora: no se volverá a mostrar.");
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Instala el agente en el servidor por FTP/SFTP.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_install()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$this->core_web_deploy->getCompany($companyKey);
			$profile     = $this->core_web_deploy->getProfileSecret($dataSession["user"]->companyID, $companyKey);
			$uploaded    = $this->core_web_deploy->installAgent($profile, $this->deploy_post("includeCode") === "1");
			return $this->deploy_json(array('uploaded' => $uploaded), "Agente instalado.");
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Prueba las conexiones y valida que el destino sea la compañía seleccionada.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_test()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$company     = $this->core_web_deploy->getCompany($companyKey);
			$profile     = $this->core_web_deploy->getProfileSecret($dataSession["user"]->companyID, $companyKey);
			$checks      = array();
			$agentInfo   = null;

			//Agente
			if(!empty($profile->agentUrl))
			{
				try
				{
					$agentInfo = $this->core_web_deploy->agent($profile)->call('ping');
					$checks[]  = array('group' => 'Agente', 'label' => 'Agente responde (v'.$agentInfo['agentVersion'].', PHP '.$agentInfo['php'].')', 'ok' => true);
					$checks[]  = array('group' => 'Agente', 'label' => 'Base de datos del servidor: '.$agentInfo['dbName'].' (esperada: '.$company['dbName'].')', 'ok' => $agentInfo['dbName'] === $company['dbName']);
					$checks[]  = array('group' => 'Agente', 'label' => 'MySQL '.$agentInfo['dbVersion'], 'ok' => true);
					$checks[]  = array('group' => 'Agente', 'label' => 'Extensiones zip/mysqli/zlib', 'ok' => $agentInfo['extensions']['zip'] && $agentInfo['extensions']['mysqli'] && $agentInfo['extensions']['zlib']);
					$notWritable = array_keys(array_filter($agentInfo['writable'], function($ok){ return !$ok; }));
					$checks[]  = array('group' => 'Agente', 'label' => count($notWritable) ? 'Sin permiso de escritura: '.implode(', ', $notWritable) : 'Permisos de escritura correctos', 'ok' => count($notWritable) === 0);
					$checks[]  = array('group' => 'Agente', 'label' => 'Espacio libre: '.($agentInfo['diskFree'] === null ? 'no disponible' : number_format($agentInfo['diskFree'] / 1048576, 2).' MB'), 'ok' => $agentInfo['diskFree'] === null || $agentInfo['diskFree'] > 209715200);
					$checks[]  = array('group' => 'Agente', 'label' => $agentInfo['locked'] ? 'Hay un despliegue en curso: '.$agentInfo['locked'] : 'Sin despliegues en curso', 'ok' => !$agentInfo['locked']);
				}
				catch(\Throwable $exAgent)
				{
					$checks[] = array('group' => 'Agente', 'label' => $exAgent->getMessage(), 'ok' => false);
				}
			}
			else
			{
				$checks[] = array('group' => 'Agente', 'label' => 'Sin URL del agente configurada', 'ok' => false);
			}

			//FTP / SFTP (opcional)
			if(!empty($profile->ftpHost) && $profile->transportProtocol !== 'none')
			{
				try
				{
					$result = $this->core_web_deploy->transport($profile)->test($this->core_web_deploy->remoteAppDir($profile));
					foreach($result['checks'] as $check)
					$checks[] = array('group' => strtoupper($profile->transportProtocol), 'label' => $check['label'], 'ok' => $check['ok']);
				}
				catch(\Throwable $exFtp)
				{
					$checks[] = array('group' => strtoupper($profile->transportProtocol), 'label' => $exFtp->getMessage(), 'ok' => false);
				}
			}

			//MySQL directo (opcional)
			if($this->core_web_deploy->isDirect($profile))
			{
				try
				{
					$db       = $this->core_web_deploy->directDb($profile);
					$dbName   = $db->query("SELECT DATABASE() AS d, VERSION() AS v")->getRow();
					$checks[] = array('group' => 'MySQL directo', 'label' => 'Conectado a '.$dbName->d.' (MySQL '.$dbName->v.')', 'ok' => true);
					$checks[] = array('group' => 'MySQL directo', 'label' => 'Coincide con la cabecera /*BD:*/ ('.$company['dbName'].')', 'ok' => $dbName->d === $company['dbName']);
				}
				catch(\Throwable $exDb)
				{
					$checks[] = array('group' => 'MySQL directo', 'label' => \App\Libraries\core_web_deploy\core_deploy_crypto::mask($exDb->getMessage(), $this->core_web_deploy->secretsOf($profile)), 'ok' => false);
				}
			}

			return $this->deploy_json(array('checks' => $checks, 'agent' => $agentInfo));
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Recibe el ZIP, lo valida, aplica la lista blanca y genera el paquete limpio.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_package()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$company     = $this->core_web_deploy->getCompany($companyKey);
			//fromLocalApp = 1: en lugar de adjuntar un archivo, se comprime la carpeta app/ de este equipo
			$fromLocalApp = $this->deploy_post("fromLocalApp") === "1";
			$zipFile      = $fromLocalApp ? null : $this->request->getFile('zip_file');

			if(!$fromLocalApp)
			{
				if(!$zipFile || !$zipFile->isValid())
				throw new \Exception("Seleccione un archivo ZIP válido.");
				if(strtolower($zipFile->getClientExtension()) !== 'zip')
				throw new \Exception("El archivo debe tener extensión .zip.");
				if($zipFile->getSize() > \App\Libraries\core_web_deploy\core_deploy_rules::MAX_UPLOAD_BYTES)
				throw new \Exception("El ZIP supera el tamaño máximo permitido.");
			}

			//Reutilizar el despliegue en preparación (conserva análisis de estructura/datos) si es de la misma compañía
			$state        = null;
			$prevDeployID = $this->deploy_post("deployID");
			if($prevDeployID !== "" && preg_match('/^[0-9]{14}_[a-f0-9]{8}$/', $prevDeployID))
			{
				try
				{
					$prevState = $this->core_web_deploy->readState($prevDeployID);
					if($prevState["companyKey"] === $companyKey && empty($prevState["steps"]))
					$state = $prevState;
				}
				catch(\Throwable $exPrev)
				{
					$state = null;
				}
			}
			if(!$state)
			$state = $this->core_web_deploy->createDeploy($companyKey, $dataSession["user"]->nickname);

			$deployID = $state["deployID"];
			$dir      = $this->core_web_deploy->deployDir($deployID);
			if($fromLocalApp)
			\App\Libraries\core_web_deploy\core_deploy_package::zipLocalApp(APPPATH, $dir.'/source.zip');
			else
			$zipFile->move($dir, 'source.zip', true);

			$report = \App\Libraries\core_web_deploy\core_deploy_package::inspect($dir.'/source.zip');
			$this->core_web_deploy->log($deployID, 'paquete', ($fromLocalApp ? 'ZIP generado de la carpeta app/ local' : 'ZIP recibido').' ('.$report['size'].' bytes, sha256 '.$report['sha256'].') por '.$dataSession["user"]->nickname.' para '.$companyKey);

			$build = null;
			if(count($report['errors']) === 0)
			{
				$build = \App\Libraries\core_web_deploy\core_deploy_package::build($dir.'/source.zip', $dir.'/package.zip', $report, array('deployID' => $deployID, 'company' => $companyKey, 'user' => $dataSession["user"]->nickname));
				$this->core_web_deploy->log($deployID, 'paquete', 'Paquete limpio: '.count($build['manifest']['files']).' archivos, sha256 '.$build['sha256'].', errores de sintaxis: '.count($build['lintErrors']));
			}
			@unlink($dir.'/source.zip');

			//Listas para la vista (los autorizados solo se cuentan)
			$lists = array('preserved' => array(), 'ignored' => array(), 'rejected' => array());
			foreach($report['files'] as $file)
			{
				if(isset($lists[$file['category']]) && count($lists[$file['category']]) < 300)
				$lists[$file['category']][] = array('path' => $file['relative'], 'reason' => $file['reason']);
			}

			$state["package"] = array(
				'ok'         => count($report['errors']) === 0 && $build !== null && count($build['lintErrors']) === 0,
				'sha256'     => $build ? $build['sha256'] : null,
				'size'       => $build ? $build['size'] : 0,
				'files'      => $build ? count($build['manifest']['files']) : 0,
				'chunks'     => $build ? (int)ceil($build['size'] / \App\Libraries\core_web_deploy\core_deploy_rules::CHUNK_SIZE) : 0,
				'source'     => $report['sha256']
			);
			$this->core_web_deploy->writeState($deployID, $state);

			return $this->deploy_json(array(
				'deployID'   => $deployID,
				'company'    => $company,
				'package'    => $state["package"],
				'prefix'     => $report['prefix'],
				'totals'     => $report['totals'],
				'errors'     => $report['errors'],
				'lintErrors' => $build ? $build['lintErrors'] : array(),
				'lists'      => $lists
			));
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Crea un despliegue sin paquete (cuando no se actualizan archivos).
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_new()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$this->core_web_deploy->getCompany($companyKey);
			$state = $this->core_web_deploy->createDeploy($companyKey, $dataSession["user"]->nickname);
			$this->core_web_deploy->log($state["deployID"], 'inicio', 'Despliegue sin paquete creado por '.$dataSession["user"]->nickname.' para '.$companyKey);
			return $this->deploy_json(array('deployID' => $state["deployID"]));
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Carga estado + perfil + compañía de un despliegue validando que coincidan.
	 *
	 * @return array [state, profile, company]
	 */
	protected function deploy_context($dataSession)
	{
		$deployID = \App\Libraries\core_web_deploy\core_deploy_files::assertDeployID($this->deploy_post("deployID"));
		$state    = $this->core_web_deploy->readState($deployID);
		$company  = $this->core_web_deploy->getCompany($state["companyKey"]);
		$profile  = $this->core_web_deploy->getProfileSecret($dataSession["user"]->companyID, $state["companyKey"]);
		return array($state, $profile, $company);
	}

	/**
	 * Compara la estructura local con la remota y guarda el plan.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_schema()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			list($state, $profile, $company) = $this->deploy_context($dataSession);

			$local  = \App\Libraries\core_web_deploy\core_deploy_schema::snapshot(db_connect());
			$remote = $this->core_web_deploy->remoteSnapshot($profile);
			if(!isset($remote['tables'], $remote['views'], $remote['functions'], $remote['procedures'], $remote['triggers'], $remote['errors']))
			throw new \Exception("El servidor remoto no devolvió la estructura de la base de datos. Pruebe las conexiones y reinstale el agente.");
			if(isset($remote['schema']) && $remote['schema'] !== $company['dbName'])
			throw new \Exception("La base remota (".$remote['schema'].") no coincide con la cabecera de la compañía (".$company['dbName'].").");

			$plan = \App\Libraries\core_web_deploy\core_deploy_schema::diff($local, $remote);
			$plan["localVersion"]  = $local['version'];
			$plan["remoteVersion"] = $remote['version'];
			$plan["errors"]        = array_merge($local['errors'], $remote['errors']);
			$state["plan"]         = $plan;
			$this->core_web_deploy->writeState($state["deployID"], $state);
			$this->core_web_deploy->log($state["deployID"], 'estructura', 'Plan generado: '.count($plan['steps']).' pasos, '.count($plan['extras']).' objetos solo en destino, hash '.$plan['hash']);

			return $this->deploy_json($plan);
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Analiza los datos de configuración (tablas + scripts) y prepara el paquete de datos.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_data()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			list($state, $profile, $company) = $this->deploy_context($dataSession);

			$requested  = json_decode((string)$this->request->getPost("tableModes"), true);
			$tableModes = $this->core_web_deploy->resolveTableModes(is_array($requested) ? $requested : array());
			$runScripts = $this->deploy_post("runScripts") === "1";

			$summary = $this->core_web_deploy->prepareData($profile, $state["deployID"], $company, $tableModes, $runScripts);
			$state["data"] = array('tableModes' => $tableModes, 'runScripts' => $runScripts, 'sha256' => $summary['sha256'], 'size' => $summary['size'], 'chunks' => (int)ceil(max(1, $summary['size']) / \App\Libraries\core_web_deploy\core_deploy_rules::CHUNK_SIZE));
			$this->core_web_deploy->writeState($state["deployID"], $state);

			$totals = array('insert' => 0, 'update' => 0, 'delete' => 0);
			foreach($summary['tables'] as $item)
			{
				if($item['counts'])
				foreach($totals as $k => $v)
				$totals[$k] += $item['counts'][$k];
			}
			$this->core_web_deploy->log($state["deployID"], 'datos', 'Análisis: '.json_encode($totals).', scripts: '.($runScripts ? 'sí' : 'no'));
			$summary['totals'] = $totals;
			return $this->deploy_json($summary);
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Ejecuta un paso del despliegue. Acciones:
	 * begin, upload, extract, apply_files, schema_apply, data_upload, data_apply,
	 * verify, finish, rollback_files, data_restore.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_step()
	{
		$state   = null;
		$profile = null;
		try
		{
			$dataSession = $this->deploy_guard();
			list($state, $profile, $company) = $this->deploy_context($dataSession);
			$deployID = $state["deployID"];
			$action   = $this->deploy_post("action");
			$secrets  = $this->core_web_deploy->secretsOf($profile);
			$options  = isset($state["options"]) ? $state["options"] : array('files' => false, 'schema' => false, 'data' => false);
			$useAgent = $options['files'] || (($options['schema'] || $options['data']) && !$this->core_web_deploy->isDirect($profile));
			$result   = array();

			switch($action)
			{
				case "begin":
					$options = array(
						'files'  => $this->deploy_post("files") === "1",
						'schema' => $this->deploy_post("schema") === "1",
						'data'   => $this->deploy_post("data") === "1"
					);
					if(!$options['files'] && !$options['schema'] && !$options['data'])
					throw new \Exception("Seleccione al menos una opción de sincronización.");
					if($options['files'] && (!$state["package"] || !$state["package"]["ok"]))
					throw new \Exception("El paquete no está listo (tiene errores o no se cargó).");
					if($options['schema'] && !$state["plan"])
					throw new \Exception("Primero analice la estructura.");
					if($options['data'] && !$state["data"])
					throw new \Exception("Primero analice los datos.");

					$this->core_web_deploy->lockCompany($state["companyKey"], $deployID);
					$state["options"] = $options;
					$useAgent = $options['files'] || (($options['schema'] || $options['data']) && !$this->core_web_deploy->isDirect($profile));
					if($useAgent)
					{
						$ping = $this->core_web_deploy->agent($profile)->call('ping');
						if($ping['dbName'] !== $company['dbName'])
						throw new \Exception("El servidor responde con la base ".$ping['dbName']." y la compañía espera ".$company['dbName'].". Despliegue bloqueado.");
						$this->core_web_deploy->agent($profile)->call('begin', array('deployID' => $deployID));
					}
					$this->core_web_deploy->log($deployID, 'inicio', 'Opciones: '.json_encode($options).' por '.$dataSession["user"]->nickname);
					$result = array('options' => $options);
					break;

				case "upload":
				case "data_upload":
					$kind  = $action === "upload" ? "package" : "data";
					$file  = $this->core_web_deploy->deployDir($deployID).($kind === "package" ? '/package.zip' : '/data.json.gz');
					$index = (int)$this->deploy_post("index");
					$result = $this->core_web_deploy->agent($profile)->uploadChunk($deployID, $kind, $file, $index);
					if($result['done'])
					$this->core_web_deploy->log($deployID, 'transferencia', ucfirst($kind).' transferido en '.$result['total'].' bloques; hash verificado por el servidor.');
					break;

				case "extract":
					$result = $this->core_web_deploy->agent($profile)->call('extract', array('deployID' => $deployID));
					$this->core_web_deploy->log($deployID, 'descompresion', 'Extraídos '.$result['extracted'].' archivos y verificados contra el manifiesto.');
					break;

				case "apply_files":
					$result = $this->core_web_deploy->agent($profile)->call('apply_files', array('deployID' => $deployID));
					$this->core_web_deploy->log($deployID, 'archivos', 'Actualizados '.$result['updated'].', nuevos '.$result['created'].', sin cambios '.$result['unchanged'].', errores '.count($result['errors']));
					foreach($result['errors'] as $error)
					$this->core_web_deploy->log($deployID, 'archivos', 'ERROR '.$error['path'].': '.$error['error']);
					break;

				case "schema_apply":
					if(!$state["plan"])
					throw new \Exception("No hay plan de estructura.");
					$ids        = json_decode((string)$this->request->getPost("ids"), true);
					$ids        = is_array($ids) ? $ids : array();
					$statements = array();
					foreach($state["plan"]["steps"] as $step)
					{
						if(in_array($step['id'], $ids, true))
						foreach($step['sql'] as $n => $sql)
						$statements[] = array('id' => $step['id'].'#'.$n, 'sql' => $sql);
					}
					if(count($statements) === 0)
					throw new \Exception("No se recibieron pasos válidos del plan.");
					$result = $this->core_web_deploy->remoteExec($profile, $deployID, $statements);
					foreach($result['results'] as $item)
					$this->core_web_deploy->log($deployID, 'estructura', ($item['ok'] ? 'OK ' : 'ERROR ').$item['id'].' ('.$item['ms'].' ms)'.($item['ok'] ? '' : ': '.$item['error']), $secrets);
					break;

				case "data_apply":
					$result = $this->core_web_deploy->isDirect($profile)
						? $this->core_web_deploy->applyDataDirect($profile, $deployID)
						: $this->core_web_deploy->agent($profile)->call('data_apply', array('deployID' => $deployID));
					$this->core_web_deploy->log($deployID, 'datos', ($result['ok'] ? 'COMMIT ' : 'ROLLBACK ').json_encode($result['tables']).' scripts '.json_encode($result['scripts']).($result['error'] ? ' error: '.$result['error'] : ''), $secrets);
					break;

				case "verify":
					$result = array();
					if($options['files'])
					$result['files'] = $this->core_web_deploy->agent($profile)->call('verify_files', array('deployID' => $deployID));
					if($options['schema'])
					{
						$local  = \App\Libraries\core_web_deploy\core_deploy_schema::snapshot(db_connect());
						$remote = $this->core_web_deploy->remoteSnapshot($profile);
						$after  = \App\Libraries\core_web_deploy\core_deploy_schema::diff($local, $remote);
						$result['schema'] = array('pending' => count($after['steps']), 'summary' => $after['summary']);
					}
					$this->core_web_deploy->log($deployID, 'verificacion', json_encode($result));
					break;

				case "finish":
					if($useAgent)
					$this->core_web_deploy->agent($profile)->call('finish', array('deployID' => $deployID));
					$this->core_web_deploy->unlockCompany($state["companyKey"], $deployID);
					$status = $this->deploy_post("status") === "ok" ? "COMPLETADO" : "CON ERRORES";
					$this->core_web_deploy->markLastDeploy($dataSession["user"]->companyID, $profile, $deployID, $status);
					$this->core_web_deploy->log($deployID, 'fin', 'Despliegue '.$status);
					$result = array('status' => $status);
					break;

				case "rollback_files":
					$this->core_web_deploy->agent($profile)->call('begin', array('deployID' => $deployID));
					$result = $this->core_web_deploy->agent($profile)->call('rollback_files', array('deployID' => $deployID));
					$this->core_web_deploy->agent($profile)->call('finish', array('deployID' => $deployID));
					$this->core_web_deploy->log($deployID, 'reversion', 'Archivos restaurados '.$result['restored'].', eliminados '.$result['removed']);
					break;

				case "data_restore":
					if($this->core_web_deploy->isDirect($profile))
					{
						$result = $this->core_web_deploy->restoreDataDirect($profile, $deployID);
					}
					else
					{
						$this->core_web_deploy->agent($profile)->call('begin', array('deployID' => $deployID));
						$result = $this->core_web_deploy->agent($profile)->call('data_restore', array('deployID' => $deployID));
						$this->core_web_deploy->agent($profile)->call('finish', array('deployID' => $deployID));
					}
					$this->core_web_deploy->log($deployID, 'reversion', 'Datos restaurados: '.json_encode($result['restored']));
					break;

				default:
					throw new \Exception("Acción desconocida.");
			}

			$state["steps"][] = array('action' => $action, 'on' => helper_getDateTime());
			$this->core_web_deploy->writeState($deployID, $state);
			return $this->deploy_json($result);
		}
		catch(\Throwable $ex)
		{
			if($state)
			{
				try
				{
					$this->core_web_deploy->log($state["deployID"], 'error', $ex->getMessage(), $profile ? $this->core_web_deploy->secretsOf($profile) : array());
				}
				catch(\Throwable $exLog)
				{
				}
			}
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Devuelve el token CSRF vigente (petición GET, no la valida el filtro).
	 * La pantalla lo pide cuando una petición POST responde 403 porque el token
	 * cambió en otra pestaña o venció, y reintenta la operación una vez.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_token()
	{
		try
		{
			$this->deploy_guard();
			return $this->deploy_json(array());
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Limpieza de servidor: el agente de la compañía elegida lista las instalaciones
	 * posMe de su hosting y cuánto ocupa cada categoría. No borra nada.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_cleanup_scan()
	{
		try
		{
			$dataSession = $this->deploy_guard();
			$companyKey  = $this->deploy_post("company");
			$categories  = json_decode((string)$this->request->getPost("categories"), true);
			$profile     = $this->core_web_deploy->getProfileSecret($dataSession["user"]->companyID, $companyKey);
			$result      = $this->core_web_deploy->cleanupScan($profile, is_array($categories) ? $categories : array());
			return $this->deploy_json($result);
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Limpieza de servidor: borra las categorías elegidas en una instalación del hosting
	 * del agente. Trabaja por tramos de tiempo: la pantalla repite mientras complete = false.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_cleanup_apply()
	{
		try
		{
			$dataSession  = $this->deploy_guard();
			$companyKey   = $this->deploy_post("company");
			$installation = $this->deploy_post("installation");
			$categories   = json_decode((string)$this->request->getPost("categories"), true);

			// Confirmación escrita obligatoria: la limpieza no tiene reversa
			if($this->deploy_post("confirm") !== "LIMPIAR")
			throw new \Exception("Escriba LIMPIAR para confirmar.");
			if(!is_array($categories) || !$categories)
			throw new \Exception("Seleccione al menos una categoría.");

			$profile = $this->core_web_deploy->getProfileSecret($dataSession["user"]->companyID, $companyKey);
			$secrets = $this->core_web_deploy->secretsOf($profile);
			$result  = $this->core_web_deploy->cleanupApply($profile, $installation, $categories);
			$this->core_web_deploy->cleanupLog("agente ".$companyKey." | ".$installation." | ".implode(",", $categories)." | ".($result["complete"] ? "completa" : "parcial")." | ".json_encode($result["categories"])." | por ".$dataSession["user"]->nickname, $secrets);
			return $this->deploy_json($result);
		}
		catch(\Throwable $ex)
		{
			return $this->deploy_jsonError($ex);
		}
	}

	/**
	 * Descarga el informe (log) de un despliegue.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function deploy_log()
	{
		try
		{
			$this->deploy_guard();
			$deployID = \App\Libraries\core_web_deploy\core_deploy_files::assertDeployID(helper_RequestGetValue($this->request->getGet("deployID"), ""));
			$text     = $this->core_web_deploy->readLog($deployID);
			return $this->response
				->setHeader('Content-Type', 'text/plain; charset=utf-8')
				->setHeader('Content-Disposition', 'attachment; filename="despliegue_'.$deployID.'.log"')
				->setBody($text);
		}
		catch(\Throwable $ex)
		{
			return $this->response->setStatusCode(400)->setBody($ex->getMessage());
		}
	}
	
	

	
}

?>
