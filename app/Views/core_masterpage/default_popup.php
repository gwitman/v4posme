<!--vista del sistema para los popup-->
<!--la diferencia con la principal es que no muestra el menu izquierdo-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>posMe</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		
		<link href='<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_400_800_700.css' rel='stylesheet' type='text/css'>		
		<link href='<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_droid_sans_400_700.css' rel='stylesheet' type='text/css' />

		 <!--[if lt IE 9]>
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_400.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_700.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_open_sans_800.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_droid_sans_400.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/family_droid_sans_700.css" rel="stylesheet" type="text/css" />
		<![endif]-->

		<!-- Core stylesheets do not remove -->
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/bootstrap/bootstrap.css" 		rel="stylesheet" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/bootstrap/bootstrap-theme.css" rel="stylesheet" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/icons.css" 					rel="stylesheet" />

		<!-- Plugins stylesheets -->
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/uniform/uniform.default.css" rel="stylesheet" /> 
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/tables/datatables/jquery.dataTables.css" rel="stylesheet" />
        <link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/switch/bootstrapSwitch.css" rel="stylesheet" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/select2/select2.css" rel="stylesheet" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.css" rel="stylesheet" /> 
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/genyx-theme/jquery.ui.progressbar.css" rel="stylesheet" /> 
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/genyx-theme/jquery.ui.genyx.css" rel="stylesheet" />
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/style.css" rel="stylesheet" /> 
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/app.css" 																		rel="stylesheet" /> 
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/custom.css" 																	rel="stylesheet" /> 
		<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/genyx-app.css" 																			rel="stylesheet" /> 
		
		<!--[if IE 8]><link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->

		<!-- Force IE9 to render in normal mode -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/html5shiv.js"></script>
		  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/respond.min.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" 	href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" 	href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" 		href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" 					href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" 									href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/images/ico/favicon.ico">

		<!-- Le javascript
		================================================== -->
		<!-- Important plugins put in all pages -->
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery-1-9-1.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery-migrate-1.2.1.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery-ui-1-10-2.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/bootstrap/bootstrap.js"></script>  		
  
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/conditionizr.min.js"></script>  
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/core/jrespond/jRespond.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery.genyxAdmin.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/animated-progress-bar/jquery.progressbar.js"></script>
        <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/switch/bootstrapSwitch.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/jquery.tmpl.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
		<!-- Form plugins --> 
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/uniform/jquery.uniform.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/tables/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/select2/select2.js"></script> 

		<!-- Init plugins -->
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/app.js"></script><!-- Core js functions -->
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/pages/domready.js"></script><!-- Init plugins only for page -->
		
	
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-utilis.js"></script>  
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>  
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-app-init.js"></script>  
	
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/jquery.isloading.min.js"></script>  
	
		
		

	
	</head>
	<body>
				<div style="background:#fff" >
					<?php echo $message; ?>
					
					<?php echo $head; ?>
					
					<?php echo $body; ?>
				</div>			
	</body>
	<?php echo $script;?>
	
</html>