<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Documentos - posMe</title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="berp" />
		<!--
		posme:2026-11-09 Administrador de archivos elFinder 2.1.70 (antes 2.0 rc1 + Plupload).
		Página independiente: usa su propio jQuery 3 / jQuery UI 1.13, sin afectar al resto del sistema.
		Respaldo de la versión anterior: core_elfinder/backup_20261109/
		-->
		<?php $urlElFinder = APP_URL_RESOURCE_CSS_JS."/resource/js/elfinder-2.1"; ?>
		<link href="<?php echo $urlElFinder; ?>/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
		<link href="<?php echo $urlElFinder; ?>/css/elfinder.min.css" rel="stylesheet" />
		<link href="<?php echo $urlElFinder; ?>/css/theme.css" rel="stylesheet" />
		<style>
			html, body { margin: 0; padding: 0; height: 100%; background: #ffffff; font-family: Arial, Helvetica, sans-serif; }
			#elfinder { border: 0; border-radius: 0; }
		</style>

		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery-3.7.1.min.js"></script>
		<script src="<?php echo $urlElFinder; ?>/jquery-ui/jquery-ui.min.js"></script>
		<script src="<?php echo $urlElFinder; ?>/js/elfinder.min.js"></script>
		<script src="<?php echo $urlElFinder; ?>/js/i18n/elfinder.es.js"></script>
	</head>
	<body>
		<div id="elfinder"></div>

		<script>
			var urlBaseElFinder	= '<?php echo $urlElFinder; ?>/';
			var urlRootElFinder	= '<?php echo rtrim(base_url(),'/'); ?>/core_elfinder/load_elfinder/componentID/<?php echo $componentID; ?>/componentItemID/<?php echo $componentItemID; ?>';

			$(function() {
				var elf = $('#elfinder').elfinder({
					baseUrl			: urlBaseElFinder,
					cssAutoLoad		: false,
					lang			: 'es',
					url				: urlRootElFinder,	// conector (REQUERIDO)
					height			: $(window).height(),
					resizable		: false,
					rememberLastDir	: false,
					// Subida por partes para archivos grandes (el servidor limita a 20 MB por archivo)
					uploadMaxChunkSize : 2097152,
					uiOptions		: {
						toolbar : [
							['back', 'forward'],
							['reload'],
							['home', 'up'],
							['mkdir', 'upload'],
							['open', 'download'],
							['quicklook', 'info'],
							['copy', 'cut', 'paste'],
							['rm'],
							['duplicate', 'rename', 'resize'],
							['search'],
							['view', 'sort'],
							['fullscreen', 'help']
						]
					}
				}).elfinder('instance');

				// Ajustar el alto del control al de la ventana
				$(window).on('resize', function() {
					elf.resize('100%', $(window).height());
				});
			});
		</script>
	</body>
</html>
