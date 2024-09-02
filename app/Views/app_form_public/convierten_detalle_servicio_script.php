<!-- ./ page heading -->
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery.loading.min.js"></script>


<script>
	$(document).ready(function(){

		var filter1 = "";
		var filter2 = "";
		var filter3 = "";
		var filter4 = "";
		var filter5 = "";
		var filter6 = "";
		var filter7 = "";
		var filter8 = "";
		var filter9 = "";
		var filter10 = "";
		var filter11 = "";
		var filter12 = "";
		var filter13 = "";
		var filter14 = "";
		var filter15 = "";
		var filter16 = "";
		var filter17 = "";
		var filter18 = "";
		let data={};

		$("#btnSave").on('click',function(event) {
			var valorSelect = $("#txtTransactionMasterReference1").val();
			if (valorSelect === "") {
				$("#toast-ex").addClass("bg-danger", "animate__fadeIn");
				$('.toast-body').text("Debe especificar un valor para continuar...");
				let toastAnimation = new bootstrap.Toast($("#toast-ex"));
				toastAnimation.show();
			}else{
				$(".spinner-overlay-posme").show();
				$('#frmPublic').attr("method","POST");
				$('#frmPublic').attr("action","<?php echo base_url(); ?>/app_form_public/save/new");
				$('#frmPublic').submit();
			}
			
		});

		<?php
		if(isset($result)){
		?>
			$("#toast-ex").addClass("bg-success", "animate__fadeIn");
			$('.toast-body').text("Se ha guardado con exito los valores con número de registro").append('<h1 class="text-white"><?= $result ?></h1>');
			let toastAnimation = new bootstrap.Toast($("#toast-ex"));
			toastAnimation.show();
		<?php
		}		
		?>
		$.ajax({
            url: 'https://posme.net/v4posme/convierten/public/app_public_catalog_api/getPublicCatalogDetailFilter', 
            type: 'get', 
			crossDomain: true,
			data: {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue' :'TipoDeServicio',
			},
			success: function(response) {
                response.forEach(function(elemento) {
					$('#txtTransactionMasterReference1').append('<option value="' + elemento.label + '">' + elemento.label + '</option>');
				});
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown);
            }
        });			

		
		$('#txtTransactionMasterReference1').change(function() {			
			filter1 = $(this).val();
			let data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'ServicioExistente',
				'filter1':filter1,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_ServicioExistente'
			};
			llenarComboBox(filter1,data, 'txtTransactionMasterReference2');
		});
		$('#txtTransactionMasterReference2').change(function() {
			filter2 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'Servicio',
				'filter1':filter1,
				'filter2':filter2,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_Servicio'
			};
			llenarComboBox(filter2,data, 'txtTransactionMasterReference3');
		});
		$('#txtTransactionMasterReference3').change(function() {
			filter3 = $(this).val();
			let data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'Tecnologia',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_Tecnologia'
			};
			llenarComboBox(filter3,data, 'txtTransactionMasterReference4');
		});
		$('#txtTransactionMasterReference4').change(function() {
			filter4 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'VelocidadInternet',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_VelocidadInternet'
			};
			llenarComboBox(filter4,data, 'txtTransactionMasterReference5');
		});
		$('#txtTransactionMasterReference5').change(function() {
			filter5 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'GestionMovil',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'filter5':filter5,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_GestionMobile'
			};
			llenarComboBox(filter4,data, 'txtTransactionMasterReference6');
		});
		$('#txtTransactionMasterReference6').change(function() {
			filter6 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'PlanMovil',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'filter5':filter5,
				'filter6':filter6,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_PlanMobile'
			};
			llenarComboBox(filter4,data, 'txtTransactionMasterReference7');
		});
		$('#txtTransactionMasterReference7').change(function() {
			filter7 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'TipoLinea',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'filter5':filter5,
				'filter6':filter6,
				'filter7':filter7,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_TipoDeLinea'
			};
			llenarComboBox(filter7,data, 'txtTransactionMasterReference8');
		});
		$('#txtTransactionMasterReference8').change(function() {
			filter8 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'OperadorDonante',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'filter5':filter5,
				'filter6':filter6,
				'filter7':filter7,
				'filter8':filter8,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_OperadorDonante'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter8,data, 'txtTransactionMasterReference9');
		});
		$('#txtTransactionMasterReference9').change(function() {
			filter9 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'TipoMigracion',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'filter5':filter5,
				'filter6':filter6,
				'filter7':filter7,
				'filter8':filter8,
				'filter9':filter9,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_TipoMigracion'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter9,data, 'txtTransactionMasterReference10');
		});
		$('#txtTransactionMasterReference10').change(function() {
			filter10 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'LineasMovilesAdicionales',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter5':filter5,
				'filter6':filter6,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_LineasMobileAdicionales'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter6,data, 'txtTransactionMasterReference11');
		});
		$('#txtTransactionMasterReference11').change(function() {
			filter11 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'GestionMovilAdicional1',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter5':filter5,
				'filter6':filter6,
				'filter11':filter11,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_GestionMovileAdicional1'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter11,data, 'txtTransactionMasterReference12');
		});
		$('#txtTransactionMasterReference12').change(function() {
			filter12 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'PlanMovilAdicional1',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'filter5':filter5,
				'filter6':filter6,
				'filter11':filter11,
				'filter12':filter12,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_PlanMobileAdicional1'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter12,data, 'txtTransactionMasterReference13');
		});
		$('#txtTransactionMasterReference13').change(function() {
			filter13 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'RGUsFijos',
				'filter1':filter1,
				'filter2':filter2,
				'filter3':filter3,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_RguFijos'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter4,data, 'txtTransactionMasterReference14');
		});
		$('#txtTransactionMasterReference14').change(function() {
			filter14 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'RGUsMoviles',
				'filter6':filter6,
				'filter11':filter11,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_RguMobile'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter6,data, 'txtTransactionMasterReference15');
		});
		$('#txtTransactionMasterReference15').change(function() {
			filter15 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'RentaFijo',
				'filter2':filter2,
				'filter3':filter3,
				'filter4':filter4,
				'filter5':filter5,
				'filter11':filter11,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_RentaFijo'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter4,data, 'txtTransactionMasterReference16');
		});
		$('#txtTransactionMasterReference16').change(function() {
			filter16 = $(this).val();
			data = {
				'catalogName':'tb_transaction_master_campos_cascada_detalles_servicion',
				'fieldValue':'RentaMovil',
				'filter4':filter4,
				'filter7':filter7,
				'filter13':filter13,
				'keyFildValue':'convierten_tb_transaction_master_campos_cascada_detalles_servicion_RentaMobile'
			};
			var currentSelectId = $(this).attr('id');
			llenarComboBox(filter13,data, 'txtTransactionMasterReference17');
		});
	});

	function llenarComboBox(valorSeleccionado,data, idselect){
		let $currentSelect = $('#' + idselect); 
		let $allSelects = $('select'); 
		let index = $allSelects.index($currentSelect); 
		$allSelects.slice(index + 1).each(function() {
			$(this).empty().append('<option value="">Seleccione una opción</option>');
		});

		if (valorSeleccionado) {
			$.ajax({
				url: 'https://posme.net/v4posme/convierten/public/app_public_catalog_api/getPublicCatalogDetailFilter', 
				type: 'GET',
				data: data,
				success: function(respuesta) {
					$currentSelect.empty();
					$currentSelect.append('<option value="">Seleccione una opción</option>');
					respuesta.forEach(function(elemento) {
						if (typeof elemento.label === "undefined") {
							$currentSelect.append('<option value="">Buscando elementos...</option>');
						}else{
							$currentSelect.append('<option value="' + elemento.label + '">' + elemento.label + '</option>');
						}
					});
				},
				error: function(error) {
					console.log('Error:', error);
				}
			});
		} else {
			$currentSelect.empty();
			$currentSelect.append('<option value="">Seleccione una opción</option>');
		}
	}
</script>