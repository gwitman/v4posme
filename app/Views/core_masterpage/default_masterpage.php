<!--vista principal del sistema-->
<?php
	use App\Libraries\core_web_parameter;
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>posMe</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="application-name" content="dsemp" />

	<!-- CSS
		================================================== 
		-->

	<link href='<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_400_800_700.css' rel='stylesheet' type='text/css'>
	<link href='<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_droid_sans_400_700.css' rel='stylesheet' type='text/css' />

	<!--[if lt IE 9]>
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_400.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_700.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_800.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_droid_sans_400.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_droid_sans_700.css" rel="stylesheet" type="text/css" />
	<![endif]-->


	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/bootstrap/bootstrap.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/bootstrap/bootstrap-theme.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/icons.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/uniform/uniform.default.css" rel="stylesheet" />
    <?php
    if (isset($datatable_V2_2_2) && $datatable_V2_2_2){
        echo '<link href="'.APP_URL_RESOURCE_CSS_JS.'/resource/js/datatable222/datatables.css" rel="stylesheet" />';
    }else{
        echo '<link href="'.APP_URL_RESOURCE_CSS_JS.'/resource/theme-genyx/js/plugins/tables/datatables/jquery.dataTables.css" rel="stylesheet" />';
    }
    ?>

	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/switch/bootstrapSwitch.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/select2/select2.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/genyx-theme/jquery.ui.progressbar.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/genyx-theme/jquery.ui.genyx.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/style.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/app_css.php" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/custom.css" rel="stylesheet" />
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/genyx-app.css" rel="stylesheet" />
	
	<!--[if IE 8]>
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<!--[if lt IE 9]>
		  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/html5shiv.js"></script>
		  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/respond.min.js"></script>
	<![endif]-->


	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/favicon.ico">

	<!-- javascript
		==================================================
	-->
	
	 <?php
    if (isset($jquery_V1_12_4) && $jquery_V1_12_4){
        echo '<script src="'.APP_URL_RESOURCE_CSS_JS.'/resource/js/jquery-1.12.4.min.js"></script>';
    }else{
        echo '<script src="'.APP_URL_RESOURCE_CSS_JS.'/resource/theme-genyx/js/jquery-1-9-1.min.js"></script>';
    }
    ?>
	
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery-migrate-1.2.1.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery-ui-1-10-2.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/bootstrap/bootstrap.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/conditionizr.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/core/jrespond/jRespond.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery.genyxAdmin.js?v7"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/animated-progress-bar/jquery.progressbar.js"></script>

	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/switch/bootstrapSwitch.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery.tmpl.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/uniform/jquery.uniform.min.js"></script>
	<!--Validacion de DataTable V2.2.2-->

    <?php
    if (isset($datatable_V2_2_2) && $datatable_V2_2_2){
        echo '<script src="'.APP_URL_RESOURCE_CSS_JS.'/resource/js/datatable222/datatables.js"></script>';
    }else{
        echo '<script src="'.APP_URL_RESOURCE_CSS_JS.'/resource/theme-genyx/js/plugins/tables/datatables/jquery.dataTables.min.js"></script>';
    }
    ?>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/select2/select2.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/app.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/pages/domready.js"></script>

	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-utilis.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-app-init.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/jquery.isloading.min.js"></script>
		

</head>

<body <?php echo getBehavio($company->type, "default_masterpage", "backgroundImage", ""); ?>>

	<header id="header">
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">

			<a class="navbar-brand" href="javascript:void(0);" style="color:#fff">
				<?php echo $company->name; ?>
				<span class="badge badge-success"> (usuario: <?php echo $user->nickname; ?>)</span>

				<?php
				if ($parameterLabelSistem != "") {
					echo "</br>";
					echo $parameterLabelSistem;
				}
				?>
				<?php echo $mensajeLogin ?>
			</a>

			<button type="button" class="navbar-toggle btn-danger" data-toggle="collapse" data-target="#navbar-to-collapse">
				<span class="sr-only">Toggle Derecho Menu</span>
				<i class="icon16 i-arrow-8"></i>
			</button>

			<div class="collapse navbar-collapse" id="navbar-to-collapse">
				<ul class="nav navbar-nav pull-right">
				
					<li class="dropdown">	
						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">		
							<i class="icon24 i-IE"></i>									
						</a>	
						<ul class="dropdown-menu" role="menu">
							<li role="presentation" >
								<a href="javascript:void(0);" class="" id="link-tramites" >
									<i class="icon16 i-bell-2"></i> tramites 
								</a>
							</li>
							<!--
							<li role="presentation">
								<a href="http://localhost/posmev4/index.php/core_notification/index" class="">
								<i class="icon16 i-bell-2"></i> visita agendada </a>
							</li>
							-->							
						</ul>
					</li>


					<?php echo $notification; ?>
					<?php echo $menuRenderTop; ?>
				</ul>
			</div><!--/.nav-collapse -->
		</nav>
	</header> <!-- End #header  -->

	<div class="main">
		<aside id="sidebar">
			<div class="side-options">
				<ul>
					<li><a href="javascript:void(0);" id="collapse-nav" class="act act-primary tip" title="Collapse navigation"><i class="icon16 i-arrow-left-7"></i></a></li>
				</ul>
			</div>

			<div class="sidebar-wrapper">
				<nav id="mainnav">
					<ul class="nav nav-list">
						<?php echo $menuRenderLeft; ?>
					</ul>
				</nav>
			</div>
		</aside>


		<section id="content">
			<div class="wrapper">
				<div class="crumb">
					<ul class="breadcrumb">
						<li><a href="javascript:void(0);"><i class="icon16 i-quill-3"></i>...</a></li>
						<li><a href="javascript:void(0);"><i class="icon16 "></i>...</a></li>
					</ul>
				</div>

				<div class="container-fluid">
					<?php echo $message; ?>

					<?php echo $head; ?>

					<?php echo $body; ?>

					<?php echo $footer; ?>

				</div> <!-- End .container-fluid  -->
			</div> <!-- End .wrapper  -->
		</section>
	</div><!-- End .main  -->



	<div id="main_content">
	</div>
	
	
	<div id="modalDialogHtmlTramites" style="display:none">
		<h3>Acceso a tramites</h3>
		</br>
		
		
		<table style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); font-family:Arial, sans-serif;">
		  
		  <tr style="border-bottom:1px solid #eee;">
			<td style="padding:5px; font-weight:bold; width:200px; color:#333;text-align:left">Tipo de Trámite</td>
			<td style="padding:5px; color:#555;text-align:left">
				<select name="txtTypeProcedureID" id="txtTypeProcedureID" class="select2" >
						<option value="">Seleccionar</option>
						<?php echo $menuRenderProcedure; ?>
						
				</select>
			</td>
		  </tr>
		  <tr style="border-bottom:1px solid #eee;">
			<td style="padding:5px; font-weight:bold; color:#333;text-align:left">Texto del Trámite</td>
			<td style="padding:5px; color:#555;text-align:left">
				<input type="text" name="txtTextProcedure" id="txtTextProcedure"></input>
			</td>
		  </tr>
		  <tr>
			<td style="padding:5px; font-weight:bold; color:#333;text-align:left">Examinar Entidad</td>
			<td style="padding:5px; color:#555;text-align:left">
				
				<table>
					<tr>
						<td>
							<input type="hidden" id="txtProcedureEntityID" name="txtProcedureEntityID" value="">
							<input type="hidden" id="txtProcedureEntityName" name="txtProcedureEntityName" value="">
							<input class="form-control" readonly="" id="txtProcedureEntityDescription" type="text" value="">
						</td>
						<td>
							<button class="btnCerrarModalOpcionesTramites" id="btnCerrarModalOpcionesTramites" style="padding:8px 20px;"  >Limpiar</button>
						</td>
						<td>
							<button class="btnAceptarModalOpcionesTramites" id="btnAceptarModalOpcionesTramites" style="padding:8px 20px;"  >Buscar</button>
						</td>
					</tr>
				</table>				
			</td>
		  </tr>
		</table>
		
		</br>

			
	</div>
	<?php
		helper_getHtmlOfModalDialog(
			"ModalOpcionesTramites","modalDialogHtmlTramites",
			"fnAceptarModalDialogHtmlTramites",true,true,
			"max-width: 600px;"
		);
	?>
	

</body>
<script>
	var varCoreUserName 	= '<?php echo $user->nickname; ?>';
	var varCoreRoleName 	= '<?php echo $role->name; ?>';
	var varCoreFlavorID 	= '<?php echo $company->flavorID; ?>';
	var varCoreCompanyType 	= '<?php echo $company->type; ?>';
	var varCoreCompanyName 	= '<?php echo $company->name; ?>';
	
	selectMenu("<?php echo current_url(); ?>");
	
	
	$(document).on("click", "#link-tramites", function(e) {	  
	  mostrarModal("ModalOpcionesTramites");
	});
	
	$(document).on("click", "#btnCerrarModalOpcionesTramites", function(e) {	  
	  fnClearValues();
	});
	
	$(document).on("click", "#btnAceptarModalOpcionesTramites", function(e) {	
		
		if($("#txtTypeProcedureID").val() == "")
			return;
		
		let componentID 	= $("#txtTypeProcedureID").val().split("|")[0];
		let dataViewName 	= $("#txtTypeProcedureID").val().split("|")[1];		
		let typeOpen		= $("#txtTypeProcedureID").val().split("|")[2];
		let url 			= $("#txtTypeProcedureID").val().split("|")[3];
		
		let url_request =
			"<?php echo base_url(); ?>/core_view/showviewbynamepaginate"+
			"/"+componentID+
			"/onCompleteDocument/"+dataViewName+"/true/"+
			encodeURI(
				'{'+
				'\"transactionID\"|\"'+0+'\"'+
				'}'
			) +
			"/false/not_redirect_when_empty/1/1/25/";
			
		window.open(url_request, "MsgWindow", "width=900,height=450");
		window.onCompleteDocument = onCompleteDocument;
			
	});
	
	function fnAceptarModalDialogHtmlTramites()
	{
		if($("#txtTypeProcedureID").val() == "")
			return;
		
		let componentID 					= $("#txtTypeProcedureID").val().split("|")[0];
		let dataViewName 					= $("#txtTypeProcedureID").val().split("|")[1];
		let typeOpen						= $("#txtTypeProcedureID").val().split("|")[2];
		let url 							= $("#txtTypeProcedureID").val().split("|")[3];
		
		let txtTextProcedure 				= $("#txtTextProcedure").val();
		let txtProcedureEntityID 			= $("#txtProcedureEntityID").val();
		let txtProcedureEntityName 			= $("#txtProcedureEntityName").val();
		let txtProcedureEntityDescription 	= $("#txtProcedureEntityDescription").val();
		
		
		
		if(typeOpen == "_blank")
		{
			window.open("<?php echo base_url(); ?>"+"/"+url, "_blank");
		}
		else if(typeOpen == "_self")
		{
			window.location.href 				= "<?php echo base_url(); ?>"+"/"+url;
		}
		else
		{
			window.location.href 				= "<?php echo base_url(); ?>"+"/"+url;
		}
			
		
	}
	
	function onCompleteDocument(objResponse){
		console.info("CALL onCompleteDocument");
		let documento 	= objResponse[0][2];
		let id			= objResponse[0][1];		
		$('#txtProcedureEntityID').val(id);
		$('#txtProcedureEntityName').val(documento);
		$('#txtProcedureEntityDescription').val(documento);
		
	}
	
	function fnClearValues(){
		
		$('#txtProcedureEntityID').val('');
		$('#txtProcedureEntityName').val('');
		$('#txtProcedureEntityDescription').val('');
	
	}
	
</script>
<?php echo $script; ?>

<?php

$activateNotification 	= helper_getParameterFiltered($companyParameter, "CORE_RUN_NOTIFICATION")->value;
$timeFrequency 		  	= helper_getParameterFiltered($companyParameter, "CORE_TIME_FRECUENCY_NOTIFICATION")->value;
$userName 				= $user->nickname;
$baseUrl 				= base_url() . "/app_notification/getNotificationShowInApp/" . urlencode($userName);

if (strcmp(strtolower($activateNotification), "true") == 0) {
?>
	<script>
		$(document).ready(function() {
			fnShowExpiredRegisters('<?php echo $baseUrl ?>', '<?php echo $userName ?>', '<?php echo $timeFrequency; ?>');
		});
	</script>

<?php
}
?>

</html>