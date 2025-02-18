<script >
	$(document).ready(function(){
			var autoclose 	= <?php echo $autoclose;?>;
			var multiselect = <?php echo $multiselect;?>;

			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
			
					
				
					
					var viewName 	= '<?php echo $viewname;?>';
					let data		= [];
					
					
					if(multiselect === false)
					{
                        //obtener valores de cantidad si existe input o select
                        let findQuantity = $(objRowTableListView).find('.quantity_inline');
                        console.log(findQuantity);
                        if (findQuantity.length > 0) {
                            let cantidad;
                            let findDataIndex   = $(findQuantity[0]);
                            let index           = 0;
                            if (findQuantity.hasClass("select2")){
                                index       = $(findQuantity[1]).data('index');
                                cantidad    = parseFloat(findQuantity.select2('data').text);
                            }else{
                                index       = findDataIndex.data('index');
                                cantidad    = parseFloat(findQuantity.val());
                            }
                            if (index>0){
                                if (cantidad !== null && cantidad !== undefined && cantidad !== '') {
                                    objTableListView.fnUpdate(cantidad, objRowTableListView, index);
                                }
                            }
                        }
						data.push(objTableListView.fnGetData(objRowTableListView));
					}
					else
					{
						var objListaFilasSeleccionadas 	= $('tr.row-selected');
						for(var i = 0 ; i < objListaFilasSeleccionadas.length ; i++){
							var ipush 	= {};
                            //obtener valores de cantidad si existe input o select
                            let findQuantity = $(objListaFilasSeleccionadas[i]).find('.quantity_inline');
                            if (findQuantity.length > 0) {
                                let cantidad;
                                let findDataIndex   = $(findQuantity[0]);
                                let index           = findDataIndex.data('index');
                                if (findQuantity.hasClass("select2")){
                                    cantidad = parseFloat(findQuantity.select2('data').text);
                                }else{
                                    cantidad= parseFloat(findQuantity.val());
                                }
                                if (cantidad !== null && cantidad !== undefined && cantidad !== '') {
                                    objTableListView.fnUpdate(cantidad, i, index);
                                }
                            }

							ipush 		= objTableListView.fnGetData(objListaFilasSeleccionadas[i]);
							data.push(ipush);
						}	
					}
					if(objRowTableListView == undefined)
					window.opener.<?php echo $fnCallback;?>(undefined); 
					else
					window.opener.<?php echo $fnCallback;?>(data); 

					
					if(autoclose == true){
						window.close();
					}
			});

			$(window).unload(function() {				
				window.opener.openedSearchWindow = false;
			});
	});     
</script>