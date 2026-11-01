<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-invoice-billing-list.css?v=3" rel="stylesheet" />
<script>
	var pmListUseMobile = '<?php echo $useMobile; ?>';
</script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-invoice-billing-list.js?v=1"></script>



<?php
	helper_getCssWidthInvoiceMobile();
	helper_getHtmlOfPageLanding();
?>

<div id="heading" class="page-header pm-list-heading">
	<h1><?php echo $titleTable; ?></h1>
</div>

<!-- Barra: fecha + busqueda por numero + acciones (los ids los usa list_script.php) -->
<div class="row pm-list">
	<div id="email" class="col-lg-12">
		<div class="pm-list-barra" id="pmListBarra">

			<form class="pm-list-busqueda" role="search" onsubmit="$('#btnSearchTransaction').trigger('click'); return false;" >
				<div id="datepicker" class="input-group date pm-list-fecha" data-date="<?php echo $objFecha->format("Y-m-d"); ?>" data-date-format="yyyy-mm-dd" >
					<input size="16"  class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="<?php echo $objFecha->format("Y-m-d"); ?>" aria-label="Fecha">
					<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
				</div>

				<div class="pm-list-buscar">
					<input type="text" class="form-control" id="txtSearchTransaction" placeholder="Buscar por número…" autocomplete="off" aria-label="Buscar por número" >
					<button type="button" class="btn" id="btnSearchTransaction" title="Buscar" ><i class="icon16 i-search-2"></i></button>
				</div>
			</form>

			<div class="pm-list-acciones">
				<a href="javascript:void(0);" class="btn pm-btn-vistas" id="btnView"><i class="icon16 i-grid-2"></i> VISTAS</a>
				<a href="javascript:void(0);" class="btn pm-btn-duplicar" id="btnDuplicar"><i class="icon16 i-copy"></i> DUPLICAR</a>
				<a href="javascript:void(0);" class="btn pm-btn-editar" id="btnEdit"><i class="icon16 i-pencil-4"></i> EDITAR</a>
				<a href="javascript:void(0);" class="btn pm-btn-eliminar"  id="btnEliminar"><i class="icon16 i-remove"></i> ELIMINAR</a>
				<a href="javascript:void(0);" class="btn pm-btn-nuevo" id="btnNuevo"><i class="icon16 i-plus"></i> NUEVA FACTURA</a>
			</div>

		</div>
	</div>
</div>


<?php if($useMobile != "1"){?>
<div class="row">
	<div class="col-lg-<?php echo ($objParameterShowPreview == "true" ? 7 : 12)?>">
		<div class="panel panel-default pm-list-panel">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-table"></i></div>
				<h4>...</h4>
				<a href="javascript:void(0);" class="minimize"></a>
			</div>
			<div class="panel-body" style="<?php echo getBehavio($company->type,"app_invoice_billing","bodyListInvoice",""); ?>" >

 <?php }?>