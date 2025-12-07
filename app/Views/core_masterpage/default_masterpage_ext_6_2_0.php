<!--vista principal del sistema-->
<?php
	use App\Libraries\core_web_parameter;
?>

<!DOCTYPE html>
<html lang="en">
<head>

   <meta charset="utf-8">
   <title>Pantalla de Facturación - ExtJS Profesional</title>
	

   <link rel="stylesheet" type="text/css"
          href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-ext-6.2.0/build/classic/theme-crisp/resources/theme-crisp-all.css">  
    <script type="text/javascript"
            src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-ext-6.2.0/build/ext-all.js"></script>
    <script type="text/javascript"
            src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-ext-6.2.0/build/classic/theme-crisp/theme-crisp.js"></script>			

</head>
<body>
	<!-- ./ page heading -->
	<script>
	Ext.onReady(function() {
		
		// Función para aplicar estilos directos y evitar problemas con UI faltantes
		function applyButtonStyle(config, bgColor) {
			config.style = 'background-color: ' + bgColor + ' !important; color: white !important;';
			config.ui = 'default-toolbar'; // Usamos la UI nativa que sí se carga
			return config;
		}

		Ext.create('Ext.container.Viewport', {
			layout: {
				type: 'vbox',
				align: 'stretch' 
			},
			items: [
				// FILA 1: BARRA DE HERRAMIENTAS (Toolbar)
				Ext.create('Ext.toolbar.Toolbar', {
					height: 50,
					cls: 'toolbar-factura', 
					
					items: [
						// BOTÓN 1: NUEVA FACTURA (Verde)
						applyButtonStyle({
							text: 'Nueva Factura',
							iconCls: 'x-fa fa-file-invoice', 
							scale: 'medium'
						}, '#4CAF50'),
						
						// BOTÓN 2: ELIMINAR FACTURA (Rojo)
						applyButtonStyle({
							text: 'Eliminar Factura',
							iconCls: 'x-fa fa-trash', 
							scale: 'medium'
						}, '#F44336'),
						
						// BOTÓN 3: IMPRIMIR FACTURA (Azul)
						applyButtonStyle({
							text: 'Imprimir Factura',
							iconCls: 'x-fa fa-print', 
							scale: 'medium'
						}, '#2196F3'),
						
						'->', 
						
						// BOTÓN 4: ACTUALIZAR (Sin color fuerte, pero con UI nativa)
						{
							text: 'Actualizar',
							iconCls: 'x-fa fa-sync', 
							ui: 'default-toolbar',
							scale: 'medium',
							handler: function() { 
								Ext.toast({ html: 'Acción: **Actualizando** datos', title: 'Operación' });
							}
						}
					]
				}),
				
				// FILA 2: Contenido Principal (Relleno)
				{
					xtype: 'panel',
					flex: 1, 
					title: 'Área de Contenido Principal',
					html: '<div style="padding: 20px; text-align: center; color: #777;">Si los íconos NO APARECEN, debe verificar la ruta de la librería en la línea 15 de este archivo.</div>'
				}
			]
		});
	});
	</script>


</body>
</html>