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
		$sourceName 		= $this->request->getGet('sourceName');
		$targetName 		= $this->request->getGet('targetName');
		
		if ($sourceName !== null || $targetName !== null ) 
		{
			echo "Sincronizndo BASE; ".$sourceName." ---> ".$targetName."</br>";			
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
			if ($currentParent != "." && $currentParent != ".." && $currentParent != "index.html") {
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
				}

				$documentoPath = $path_file_of_app . "/public/resource/file_company/company_" . APP_COMPANY . "/component_1/component_item_0";
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

	
	
	
	function merge_of_posme_merge_to_posme_aplicar_parameter()
	{
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
        helper(['filesystem']);
		$companyName = $this->request->getPost('company_name');
		$paramFile   = $this->request->getPost('param_file');
		$zipFile     = $this->request->getFile('zip_file');

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
	
	
	public function recurse_copy($src, $dst) {
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

	
	public function eliminarDirectorio($dir) {
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
	
	

	
}

?>
