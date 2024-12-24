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
			
			
			$builder 	= $dbDestino->table($tabla);		
			$builder->upsertBatch($recordSet);
		
		}
		$recordSet = $dbDestino->query("SET FOREIGN_KEY_CHECKS=1;");
		
	}
	function merge_of_posme_merge_to_poeme_data_onlyinsert()
	{
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
		
		//https://codeigniter.com/user_guide/dbmgmt/forge.html
		//https://codeigniter.com/userguide3/database/utilities.html			
		
		$this->dbOrigen							= \Config\Database::connect("merge");
		$this->sourceName						= DB_BDNAME_MERGE;
		$this->dbDestino						= \Config\Database::connect();
		$this->targetName						= DB_BDNAME;
		
		
		$this->dbConectTarget 					= $this->dbDestino;
		$this->dbConectSource					= $this->dbOrigen;
		//$this->dbConectInformationSchema		= \Config\Database::connect("information_schema");		
		$this->forgeOrigen						= \Config\Database::forge($this->dbOrigen);	
		$this->forgeTarget						= \Config\Database::forge($this->dbConectTarget);	
		
		$sourceName						= $this->sourceName;
		$targetName						= $this->targetName;
		$dbConectTarget 				= $this->dbConectTarget;
		$dbConectSource					= $this->dbConectSource;
		//$dbConectInformationSchema		= $this->dbConectInformationSchema;
		$forge 							= $this->forgeTarget;
		
		
		$sql 			= "";
		$sql			= "select 'eliminado archivos .sql y .pdf' as x";		
		echo $sql." SUCCESS";
		$dbConectTarget->query($sql);
		
		
		echo "<br/>";	
		echo "creando carpetas";
		echo "<br/>";
		
		
		//Crear carpetas		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_1";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_1/component_item_0";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_76";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_77";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_78";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_79";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_78";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_80";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_81";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_82";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_78";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_83";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_84";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_85";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_86";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_87";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_88";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_89";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_90";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_91";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_92";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_93";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_94";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_95";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_96";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_97";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_98";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
		$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_99";		
		if (!file_exists($documentoPath))
		{
			mkdir($documentoPath, 0755);
			chmod($documentoPath, 0755);
		}
		
			
		//Eliminar archivos de writable/logs
		$dir 	= opendir(PATH_FILE_OF_APP."/../../../writable/logs");
		while ($current = readdir($dir))
		{
			if( $current != "." && $current != ".." && $current != "index.html" ) 
			{
				unlink(PATH_FILE_OF_APP."/../../../writable/logs/".$current);
			}
		}
		
		
		//Eliminar archivos de writable/uploads
		$dir 	= opendir(PATH_FILE_OF_APP."/../../../writable/uploads");
		while ($current = readdir($dir))
		{
			if( $current != "." && $current != ".." && $current != "index.html" ) 
			{
				unlink(PATH_FILE_OF_APP."/../../../writable/uploads/".$current);
			}
		}
		
		//Eliminar archivos de debubar/debugbar
		$dir 	= opendir(PATH_FILE_OF_APP."/../../../writable/debugbar");
		while ($current = readdir($dir))
		{
			if( $current != "." && $current != ".." && $current != "index.html" ) 
			{
				unlink(PATH_FILE_OF_APP."/../../../writable/debugbar/".$current);
			}
		}
		
		//Eliminar archivos de: company_2/*.sql *.pdf *.doc *.zip *.etc 
		//-------------------------------------------------
		//-------------------------------------------------
		$dir 	= opendir(PATH_FILE_OF_APP."/company_".APP_COMPANY);
		$files 	= array();
		while ($current = readdir($dir))
		{
			if( $current != "." && $current != "..") {
				
				
				//componente 				
				if(is_dir(PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current)) 
				{
					
					//recorrer item 
					$dir2 	= opendir(PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current."/");
					while ($current2 = readdir($dir2))
					{
						if( $current2 != "." && $current2 != "..") 
						{			
							
							if(is_dir(PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current."/".$current2)) 
							{
								
								//archivos
								//recorrer archivo
								$dir3 	= opendir(PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current."/".$current2."/");
								while ($current3 = readdir($dir3))
								{
									if( $current3 != "." && $current3 != "..") 
									{
										
										if(!is_dir(PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current."/".$current2."/".$current3)) 
										{
											$fileLast 			= PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current."/".$current2."/".$current3;
											$fileLastExtention 	= pathinfo($fileLast, PATHINFO_EXTENSION);
											
											echo "</br>";
											echo "</br>";
											echo "</br>";
											echo "Scaner :".$fileLast."</br>";
																						
											$fechaCreate 	= \DateTime::createFromFormat('Y-m-d',date("Y-m-d",filectime($fileLast)));		
											$fechaNow  		= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  	
											$diff 			= $fechaNow->diff($fechaCreate);
											$daysDiff		= $diff->days;
											$daysDiff		= intval($daysDiff);
											
											
											echo "Fecha ahora:".print_r($fechaNow,true)."</br>";
											echo "Fecha archivo:".print_r($fechaCreate,true)."</br>";
											echo "Fecha archivo:".$daysDiff."</br>";
											
											if(strtoupper($fileLastExtention) == strtoupper("sql"))
											{										
												echo "Archivos SQL No se eliminaran:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("csv"))
											{
												unlink($fileLast);											
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("zip"))
											{
												unlink($fileLast);											
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("txt"))
											{
												unlink($fileLast);											
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("doc"))
											{
												unlink($fileLast);											
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("docx"))
											{
												unlink($fileLast);											
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("mp4"))
											{
												unlink($fileLast);											
												echo "Archivo eliminado:".$fileLast."</br>";
											}
												
											if(strtoupper($fileLastExtention) == strtoupper("pdf"))
											{
												unlink($fileLast);
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("m4a"))
											{
												unlink($fileLast);
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("xlsx"))
											{
												unlink($fileLast);
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											if(strtoupper($fileLastExtention) == strtoupper("rtf"))
											{
												unlink($fileLast);
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											
											if(
												(
													strtoupper($fileLastExtention) == strtoupper("jpg") ||
													strtoupper($fileLastExtention) == strtoupper("jpeg") ||
													strtoupper($fileLastExtention) == strtoupper("png") 
												)
												&& 
												(											
													(
														$current == "component_8" && 
														DELETE_FILE_COMPONENT_8_tb_user == "true" 
													) ||
													$current == "component_16" /*tb_journal*/  || 
													$current == "component_18" /*0_CORE*/  ||
													$current == "component_19" /*0_TRANSACCIONES*/  ||
													$current == "component_20" /*tb_transaction*/  ||
													$current == "component_21" /*0-INVENTARIO*/  ||
													$current == "component_22" /*0-COMPRAS*/  ||
													$current == "component_23" /*0-VENTAS*/  ||
													$current == "component_24" /*0-FACTURACION*/  ||
													$current == "component_25" /*0-CXC*/  ||
													$current == "component_26" /*0-CXP*/  ||
													$current == "component_27" /*0-RRHH*/  ||
													$current == "component_28" /*0-PLANILLA*/  ||
													$current == "component_29" /*0-BANCO*/  ||
													$current == "component_30" /*0-ACTIVOS-FIJOSS*/  ||
													$current == "component_31" /*tb_warehouse*/  ||
													$current == "component_32" /*tb_item_category*/  ||
													$current == "component_33" /*tb_item*/  ||
													$current == "component_36" /*tb_customer*/  ||
													$current == "component_38" /*tb_provideer*/  ||
													$current == "component_40" /*tb_fixed_assent*/  ||
													$current == "component_64" /*tb_transaction_master_share*/  ||
													$current == "component_66" /*tb_transaction_master_share_capital*/ ||  
													$current == "component_76" /*tb_remember*/   ||
													$current == "component_91" /*tb_transaction_master_proforma*/  ||
													$current == "component_92" /*tb_public_catalog*/  ||
													$current == "component_93" /*tb_cash_box_session*/  ||
													$current == "component_94" /*0-CALENDARIO*/  ||
													$current == "component_95" /*tb_notification_citas*/  || 
													$current == "component_96" /*tb_transaction_master_accounting_expenses*/  ||
													$current == "component_97" /*tb_transaction_master_workshop_pedido*/  ||
													$current == "component_98" /*tb_transaction_master_workshop_taller*/  ||
													$current == "component_99" /*tb_transaction_master_workshop_garantias*/  
													
												)
											)
											{
												unlink($fileLast);
												echo "Archivo eliminado:".$fileLast."</br>";
											}
											
											
												
											
										}
											
									}
								}
								//fin while archivos de component item 
								
								//eliminar directorio								
								$pathDirectoryComponentItem = PATH_FILE_OF_APP."/company_".APP_COMPANY."/".$current."/".$current2;
								$pathDirectoryComponent 	= $current;
								if
								(	
									 
									 $pathDirectoryComponent == "component_16"  /*tb_journal_entry*/ || 
									 $pathDirectoryComponent == "component_33"  /*tb_item*/ ||  
									 $pathDirectoryComponent == "component_34"  /*tb_transaction_master_otherinput*/ ||  
									 $pathDirectoryComponent == "component_35"  /*tb_transaction_master_otheroutput*/ ||  
									 $pathDirectoryComponent == "component_36"  /*tb_customer*/ || 
									 $pathDirectoryComponent == "component_37"  /*tb_entity_phone*/ || 	
									 $pathDirectoryComponent == "component_38"  /*tb_provider*/ || 	
									 $pathDirectoryComponent == "component_39"  /*tb_employee*/ || 	
									 $pathDirectoryComponent == "component_40"  /*tb_fixed_assent*/ || 	
									 $pathDirectoryComponent == "component_41"  /*tb_transaction_master_requestgeneral*/ ||  
									 $pathDirectoryComponent == "component_42"  /*tb_transaction_master_transferoutput*/ ||  
									 $pathDirectoryComponent == "component_43"  /*tb_transaction_master_transferinput*/ ||  
									 $pathDirectoryComponent == "component_44"  /*tb_transaction_master_internalpurchaserequest*/ ||  
									 $pathDirectoryComponent == "component_45"  /*tb_transaction_master_purchaseorden*/ ||  
									 $pathDirectoryComponent == "component_46"  /*tb_transaction_master_purchase*/ ||  
									 $pathDirectoryComponent == "component_47"  /*tb_list_price*/ ||  									 
									 $pathDirectoryComponent == "component_48"  /*tb_transaction_master_billing*/ ||  
									 $pathDirectoryComponent == "component_49"  /*tb_transaction_master_billing_revertion*/ ||  
									 $pathDirectoryComponent == "component_50"  /*tb_transaction_master_info_billing*/ ||  
									 $pathDirectoryComponent == "component_51"  /*tb_transaction_master_client_note_debito*/ ||  
									 $pathDirectoryComponent == "component_52"  /*tb_transaction_master_client_note_credito*/ ||  
									 $pathDirectoryComponent == "component_53"  /*tb_transaction_master_returns_provider*/ ||  
									 $pathDirectoryComponent == "component_54"  /*tb_credit_line*/ ||  
									 $pathDirectoryComponent == "component_55"  /*tb_transaction_master_pay_billing*/ ||  
									 $pathDirectoryComponent == "component_56"  /*tb_transaction_master_inputunpost*/ ||  
									 $pathDirectoryComponent == "component_60"  /*tb_transaction_master_detail_returns_provider*/ ||  
									 $pathDirectoryComponent == "component_64"  /*tb_transaction_master_share*/ ||  
									 $pathDirectoryComponent == "component_65"  /*tb_transaction_master_cancel_invoice*/ ||  
									 $pathDirectoryComponent == "component_66"  /*tb_transaction_master_share_capital*/ ||  
									 $pathDirectoryComponent == "component_71"  /*tb_transaction_master_provisioned*/ ||  
									 $pathDirectoryComponent == "component_72"  /*tb_employee_calendar_pay*/ ||  									 
									 $pathDirectoryComponent == "component_74"  /*tb_transaction_master_rrhh_adelantos*/ ||  
									 $pathDirectoryComponent == "component_75"  /*tb_transaction_master_rrhh_payroll*/ ||  
									 $pathDirectoryComponent == "component_78"  /*tb_customer_consultas_sin_riesgo*/ ||  
									 $pathDirectoryComponent == "component_80"  /*tb_transaction_master_inputcash*/ ||  
									 $pathDirectoryComponent == "component_81"  /*tb_transaction_master_outputcash*/ ||  
									 $pathDirectoryComponent == "component_82"  /*tb_item_masive*/ ||  									 
									 $pathDirectoryComponent == "component_83"  /*tb_transaction_master_examen_lab*/ ||  
									 $pathDirectoryComponent == "component_84"  /*tb_transaction_master_attendance*/ ||  
									 $pathDirectoryComponent == "component_85"  /*tb_transaction_master_denomination*/ ||  
									 $pathDirectoryComponent == "component_86"  /*tb_transaction_master_inventory_ajust*/ ||  
									 $pathDirectoryComponent == "component_87"  /*tb_transaction_master_rrhh_asistencia*/ ||  
									 $pathDirectoryComponent == "component_88"  /*tb_transaction_master_med_asistencia*/ ||  
									 $pathDirectoryComponent == "component_91"  /*tb_transaction_master_proforma*/  
									
								)
								{
									echo "Limpiando el siguiente componente: ".$pathDirectoryComponentItem."</br>";
									deleteDir($pathDirectoryComponentItem);
								}
									
							}
						}
					}
					//fin while component item 
					
				}
			}
		}
		//fin while component
		
		echo "SUCCESS";
		
		
	}
	
	
}
