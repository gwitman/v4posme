<script >
	$(document).ready(function(){
			var autoclose 	= <?php echo $autoclose;?>;
			var multiselect = <?php echo $multiselect;?>;

			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
			
					
				
					
					var viewName 	= '<?php echo $viewname;?>';
					let data		= [];
					
					
					if(multiselect == false)
					{
						data.push(objTableListView.fnGetData(objRowTableListView));
					}
					else
					{
						var objListaFilasSeleccionadas 	= $('tr.row-selected');
						for(var i = 0 ; i < objListaFilasSeleccionadas.length ; i++){
							var ipush 	= {};
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