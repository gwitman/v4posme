<script>
	$(document).ready(function(){	
		var urlPrinter = '<?php  echo $objParameterMasive; ?>';
		
		$(document).on("click","#btnPrinter",function(){
			
			var listaValores 				= objTableListView.fnGetData();	
			var listeProductos 				= "0-0-0-0-0-0";
			$("#ListView input").each(function(i,o) {
				if($( this ).val() == 0 || $( this ).val() == "")
				{}
				else{
					
					//console.info($( this ).val());
					//console.info($( this ).data("codigo"));
					listeProductos = listeProductos + "|"+ $(this).data("itemid") + "-" + $( this ).val() +"-"+ $(this).data("codigo") +"-"+ $(this).data("name").replaceAll("/",".") +"-"+$(this).data("barcode")+"-"+$(this).data("price");
				}
  				
			});
			if(listeProductos == "0-0-0-0-0-0"){
				fnShowNotification("Seleccionar el Registro...","error");
				return;
			}
			console.info(listeProductos);
			var url = urlPrinter+"/listItem/"+listeProductos;
			window.open(url, "_blank");	
			
			/*
			var listaValoresSeleccionados 	= jLinq.from(listaValores).where(function(obj){ 
				console.info(obj); return obj[7] == "Si"; }).select();
			*/	
			
			/*
			if(listaValoresSeleccionados.length > 0){			
				var listeProductos = "0";
				listaValoresSeleccionados.forEach(function(o,a){ 
					listeProductos = listeProductos + "|"+ o[1];
				});
				
				console.info(listeProductos);				
			}
			*/
			
		}); 
		$(document).on("click","#ListView tbody tr",function(a,i){
			
			
			//var objind_ = objTableListView.fnGetPosition(this);			
			//var objdat_ = objTableListView.fnGetData(objind_);	
			//var newValue = "";
			//if(objdat_[6] == "No"){
			//	newValue = "Si";
			//	$(this).addClass("success");
			//}
			//else{
			//	newValue = "No";
			//	$(this).removeClass("success");
			//	
			//}
			
			//objTableListView.fnUpdate(newValue, objind_, 6 );
			
			
		
		});
		
	});
	
				
</script>
