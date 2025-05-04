<!-- ./ page heading -->
<script>				
	$(document).ready(function(){
		var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  
		$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtStartOn').val(moment().format("YYYY-MM-DD"));
		$("#txtStartOn").datepicker("update");																		
		$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtEndOn').val(moment().format("YYYY-MM-DD"));		
		$("#txtEndOn").datepicker("update");						
		
		$(document).on("click","#print-btn-report",function(){
			var startOn					=	$("#txtStartOn").val();	
			var endOn					=	$("#txtEndOn").val();
			var inventoryCategoryID		=	$("#txtInventoryCategoryID").val();	
			var warehouseID				=	$("#txtWarehouseID").val();	
			var providerID				=	$("#txtProviderID").val();	
			var itemID					=	$("#txtItemID").val();	
			
			if(!( startOn == "" || endOn == "" ) ){
				fnWaitOpen();
				window.location	= "<?php echo base_url(); ?>/app_cxp_report/detail_purchase/viewReport/true/startOn/"+
							startOn+"/endOn/"+endOn+"/inventoryCategoryID/"+
							inventoryCategoryID+"/warehouseID/"+
							warehouseID+"/entityIDProviderID/"+
							providerID+"/itemID/"+
							itemID;
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}
			
		});
		
		$(document).on("click","#btnClearItem",function(){
			$("#txtItemID").val("0");
			$("#txtItemName").val("TODOS");
		});
		
		$(document).on("click","#btnSearchItem",function(){
				var url_redirect		= "__app_inventory_item__add__callback__fnObtenerListadoProductos__comando__pantalla_abierta_desde_la_compra";			
				url_redirect 			= encodeURIComponent(url_redirect);			
				var url_request 		= "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteItem/SELECCIONAR_ITEM_PAGINATED/true/"+encodeURI('{\"providerID\"|\"'+"0"+'\",\"currencyID\"|\"'+ "-1" +'\"}' ) + "/true/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup;
				

				objWindowSearchProduct 					= window.open(url_request,"MsgWindow","width=900,height=450");
				objWindowSearchProduct.onCompleteItem 	= onCompleteItem; 
				
		});	
		
		
	});	

	function onCompleteItem(objResponse)
	{
		for(var i = 0 ; i < objResponse.length ; i++)
		{
			$("#txtItemID").val(objResponse[0][1]);
			$("#txtItemName").val(objResponse[0][2]+" | "+ objResponse[0][3]);
		}
	}
</script>