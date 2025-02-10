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

			<a class="navbar-brand" href="javascript(0);" style="color:#fff">
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
					<li><a href="javascript:void(0)" id="collapse-nav" class="act act-primary tip" title="Collapse navigation"><i class="icon16 i-arrow-left-7"></i></a></li>
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
						<li><a href="javascript:void(0)"><i class="icon16 i-quill-3"></i>...</a></li>
						<li><a href="javascript:void(0)"><i class="icon16 "></i>...</a></li>
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

</body>
<script>
	selectMenu("<?php echo current_url(); ?>");
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