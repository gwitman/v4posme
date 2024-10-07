<!-- ./ page heading -->
<script>		
	
	
	$(document).ready(function(){							 
		
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});

		//Archivos
		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objPublicCatalog->publicCatalogID; ?>","blanck");
		});
		
		
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-invoice" ).attr("method","POST");
				$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_public_catalog/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-invoice" ).submit();
				}
				
		});
		
		//Nueva factura
		$(document).on("click","#btnNewShare",function(){
		
			
			var objRow 						= {};
			objRow.checked 					= false;
			objRow.publicCatalogID 			= 0;
			objRow.publicCatalogDetailID	= 0;
			objRow.parentCatalogDetailID	= 0;
			objRow.name 					= "";
			objRow.parentName 				= "";
			
			
			
			var tmpl = $($("#tmpl_row_document").html());
			
			tmpl.find("#txtPublicCatalogDetail_publicCatalogID").attr("value",objRow.publicCatalogID);
			tmpl.find("#txtPublicCatalogDetail_publicCatalogDetailID").attr("value",objRow.publicCatalogDetailID);
			tmpl.find("#txtPublicCatalogDetail_parentPublicCatalogDetailID").attr("value",objRow.parentCatalogDetailID);			
			tmpl.find("#txtPublicCatalogDetail_Name").attr("value",objRow.name);
			tmpl.find("#txtPublicCatalogDetail_ParentName").attr("value",objRow.parentName);
			
			
			$("#body_tb_transaction_master_detail").append(tmpl);
			refreschChecked();
			
		});
		
		//Eliminar factura
		$(document).on("click","#btnDeleteShare",function(){
			console.info("btnDeleteShare");
			$(".txtCheckedIsActive").each(function(i,obj){
					if($(obj).attr("checked") == "checked"){
						$(obj).parents("tr").first().remove();
					}
			});	
			
		});
		
		
		$(document).on("click","#btnDelete",function(){							
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_public_catalog/delete",
					data 		: {publicCatalogID : <?php echo $objPublicCatalog->publicCatalogID;?> },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_public_catalog/index";
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						fnWaitClose();
						fnShowNotification("Error 505","error");
					}
				});
			});
		});
		
		
		
	});
	
	
	
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		
		//Validar Cliente
		if($("#txtName").val() == ""){
			fnShowNotification("Escribir nombre del catalogo","error",timerNotification);
			result = false;
		}
	
		
		return result;
	}
	
	
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		//$('.txtDebit').mask('000,000.00', {reverse: true});
		//$('.txtCredit').mask('000,000.00', {reverse: true});
		$('.txt-numeric').mask('000,000.00', {reverse: true});
	}
	
</script>