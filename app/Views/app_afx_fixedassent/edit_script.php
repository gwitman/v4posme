				<!-- ./ page heading -->
				<script>
					var site_url 	  = "<?php echo base_url(); ?>";
					
					$(document).ready(function(){	
						$('#txtDate').datepicker({
				            format: "yyyy-mm-dd"
				        });
				        $('#txtDate').val();
				        $("#txtDate").datepicker("update");
				        $('.txt-numeric').mask('000,000.00', {
				            reverse: true
				        });
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-afx-fixedassent" ).attr("method","POST");
								$( "#form-new-afx-fixedassent" ).attr("action","<?php echo base_url(); ?>/app_afx_fixedassent/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-afx-fixedassent" ).submit();
								}
								
						});
						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_afx_fixedassent/delete",
									data 		: {companyID : <?php echo $objFA->companyID;?>, branchID : <?php echo $objFA->branchID;?> , fixedAssentID : <?php echo $objFA->fixedAssentID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_afx_fixedassent/index";
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
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentFA->componentID."/componentItemID/".$objFA->fixedAssentID; ?>","blanck");
						});
						//Buscar el Asignado A
						$(document).on("click","#btnSearchEmployee",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Asignado A
						$(document).on("click","#btnClearEmployee",function(){
									$("#txtAsignedEmployeeID").val("");
									$("#txtAsignedEmployeeDescripcion").val("");
						});

						//Buscar Cliente
						$(document).on("click","#btnSearchCustomer",function(){
							var url_redirect	= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_fixedassent";
							url_redirect 		= encodeURIComponent(url_redirect);
							var url_request 	= "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/33/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/false/"+url_redirect+"/1/1/10/";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteCustomer = onCompleteCustomer; 
						});
						//Eliminar Cliente
						$(document).on("click","#btnClearCustomer",function(){
									$("#txtAsignedCustomerID").val("");
									$("#txtAsignedCustomerDescripcion").val("");
						});

						//Buscar area
						$(document).on("click","#btnSearchArea",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCatalog->componentID; ?>/onCompleteArea/SELECCIONAR_ACTIVOFIJO_AREA/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteArea = onCompleteArea; 
						});
						//Eliminar area
						$(document).on("click","#btnClearArea",function(){
							$("#txtAreaID").val("");
							$("#txtAreaDescripcion").val("");
						});

						//Buscar proyecto
						$(document).on("click","#btnSearchProyect",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCatalog->componentID; ?>/onCompleteProyect/SELECCIONAR_ACTIVOFIJO_PROYECTO/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteProyect = onCompleteProyect; 
						});
						//Eliminar proyecto
						$(document).on("click","#btnClearProyect",function(){
							$("#txtProyectID").val("");
							$("#txtProyectDescripcion").val("");
						});

						$("#txtCountryID").change(function(){
							fnWaitOpen();
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								data 		: { catalogItemID : $(this).val() },
								url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByState",
								success:function(data){
									console.info("call app_catalog_api/getCatalogItemByState")
									fnWaitClose();
									
									
									$("#txtStateID").html("");
									$("#txtStateID").append("<option value=''>N/D</option>");
									$("#txtStateID").select2();
									$("#txtCityID").html("");
									$("#txtCityID").select2();
									
									
									if(data.catalogItems == null)
									return;
									
									$.each(data.catalogItems,function(i,obj){
										$("#txtStateID").append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
									});
								},
								error:function(xhr,data){									
									fnShowNotification(data.message,"error");
									fnWaitClose();
								}
							});
						});

						$("#txtStateID").change(function(){
							fnWaitOpen();
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								data 		: { catalogItemID : $(this).val() },
								url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByCity",
								success:function(data){
									console.info("call app_catalog_api/getCatalogItemByCity");
									fnWaitClose();
									$("#txtCityID").html("");
									$("#txtCityID").append("<option value=''>N/D</option>");
									$("#txtCityID").select2();
									
									if(data.catalogItems == null)
									return;
									
									$.each(data.catalogItems,function(i,obj){
										$("#txtCityID").append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
									});
								},
								error:function(xhr,data){									
									fnShowNotification(data.message,"error");
									fnWaitClose();
								}
							});
						});
						
						<?php echo getBahavioSession($company->type,'tb_fixed_assent','scriptJsReady','',$objListCompanyPageSetting) ?>
					});
					function onCompleteEmployee(objResponse){
						console.info("CALL onCompleteEmployee");
						
						$("#txtAsignedEmployeeID").val(objResponse[0][2]);
						$("#txtAsignedEmployeeDescripcion").val(objResponse[0][3] + " / " + objResponse[0][4]);
						
					}

					function onCompleteCustomer(objResponse){
						console.info("CALL onCompleteCustomer");
						if(objResponse != undefined)
						{
							$("#txtAsignedCustomerID").val(objResponse[0][1]);
							$("#txtAsignedCustomerDescripcion").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
						}
					}

					function onCompleteArea(objResponse)
					{
						console.info("CALL onCompleteArea");

						$("#txtAreaID").val(objResponse[0][0]);
						$("#txtAreaDescripcion").val(objResponse[0][1] + " | " + objResponse[0][2]);
					}

					function onCompleteProyect(objResponse)
					{
						console.info("CALL onCompleteProyect");

						$("#txtProyectID").val(objResponse[0][0]);
						$("#txtProyectDescripcion").val(objResponse[0][1] + " | " + objResponse[0][2]);
					}


					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Categoria
						if($("#txtCategoryID").val() == ""){
							fnShowNotification("Seleccionar la Categoria","error",timerNotification);
							result = false;
						}
						//Tipo
						if($("#txtTypeID").val() == ""){
							fnShowNotification("Seleccionar el Tipo","error",timerNotification);
							result = false;
						}
						//Nombre
						if($("#txtName").val() == ""){
							fnShowNotification("Escribir el Nombre","error",timerNotification);
							result = false;
						}
						
						return result;
						
					}
				</script>

<!-- ===== Expediente de Taller: estilos ===== -->
<style>
	.afx-timeline{position:relative;padding:10px 0 10px 10px;}
	.afx-timeline:before{content:'';position:absolute;top:0;bottom:0;left:28px;width:3px;background:linear-gradient(#e0e6f5,#cdd6ef);border-radius:3px;}
	.afx-timeline-item{position:relative;margin:0 0 22px 0;padding-left:62px;}
	.afx-timeline-badge{position:absolute;left:10px;top:8px;width:38px;height:38px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;box-shadow:0 2px 6px rgba(0,0,0,.25);z-index:2;}
	.afx-card{background:#fff;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;transition:transform .15s ease,box-shadow .15s ease;}
	.afx-card:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(0,0,0,.14);}
	.afx-card-header{padding:12px 16px;background:#f8f9fc;}
	.afx-order-num{font-weight:700;font-size:16px;color:#2e3a59;margin-right:10px;}
	.afx-badge{display:inline-block;color:#fff;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.4px;}
	.afx-fecha{font-size:13px;color:#6c7a94;font-weight:600;}
	.afx-card-body{padding:14px 16px;}
	.afx-meta{display:flex;flex-direction:column;margin-bottom:10px;}
	.afx-meta-label{font-size:11px;color:#8a94a6;text-transform:uppercase;letter-spacing:.3px;margin-bottom:3px;}
	.afx-meta-value{font-weight:600;font-size:13px;color:#fff;padding:4px 10px;border-radius:6px;display:inline-block;width:fit-content;}
	.afx-tag-info{background:#36b9cc;}
	.afx-tag-warning{background:#f6a821;}
	.afx-tag-success{background:#1cc88a;}
	.afx-nota{margin-top:8px;background:#fff8e1;border-left:4px solid #f6a821;padding:8px 12px;border-radius:6px;color:#7a5c00;font-size:13px;}
	.afx-bloque{margin-top:10px;padding:10px 14px;border-radius:8px;}
	.afx-bloque-title{font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;}
	.afx-bloque-text{font-size:13px;color:#444;white-space:pre-line;line-height:1.5;}
	.afx-bloque-problema{background:#fdf2f2;border-left:4px solid #e74a3b;}
	.afx-bloque-problema .afx-bloque-title{color:#c0392b;}
	.afx-bloque-solucion{background:#e8f8f1;border-left:4px solid #1cc88a;}
	.afx-bloque-solucion .afx-bloque-title{color:#13855c;}
	.afx-sub-title{font-weight:700;color:#2e3a59;font-size:13px;margin-bottom:8px;border-bottom:2px solid #eef1f8;padding-bottom:5px;}
	.afx-problema{display:flex;align-items:flex-start;background:#fdf2f2;border-radius:8px;padding:10px 12px;margin-bottom:8px;border-left:4px solid #e74a3b;}
	.afx-problema-icon{color:#e74a3b;font-size:16px;margin-right:10px;margin-top:2px;}
	.afx-problema-text{font-size:13px;color:#444;font-weight:500;}
	.afx-solucion{display:inline-block;margin-top:6px;background:#e8f8f1;color:#13855c;font-size:12px;font-weight:600;padding:3px 10px;border-radius:20px;}
	.afx-sin-problemas{background:#e8f8f1;color:#13855c;border-radius:8px;padding:10px 12px;font-size:13px;font-weight:600;}
</style>

<!-- ===== Expediente de Taller: Vue ===== -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
	(function(){
		const { createApp } = Vue;
		createApp({
			data(){
				return {
					objListOrders : [],
					mensaje       : 'Cargando expediente...',
					mostrarAlerta : true,
					esError       : false,
					cargado       : false
				}
			},
			methods:{
				async cargarExpediente(){
					if(this.cargado) return;
					this.cargado = true;
					try{
						const params = new URLSearchParams();
						params.append('companyID', document.getElementById('txtCompanyID').value);
						params.append('fixedAssentID', document.getElementById('txtFixedAssentID').value);

						const res = await fetch(site_url + '/app_afx_fixedassent/getWorkshopHistory',{
							method : 'POST',
							headers: {'Content-Type':'application/x-www-form-urlencoded'},
							body   : params.toString()
						});
						const json = await res.json();

						if(json.success === false){
							this.objListOrders = [];
							this.esError       = true;
							this.mostrarAlerta = true;
							this.mensaje       = 'No se pudo cargar el expediente: ' + (json.message || '');
							return;
						}
						if(json.success === true && (!json.data || json.data.length === 0)){
							this.objListOrders = [];
							this.esError       = false;
							this.mostrarAlerta = true;
							this.mensaje       = 'Este activo fijo no tiene ordenes de taller registradas.';
							return;
						}
						this.objListOrders = json.data;
						this.esError       = false;
						this.mostrarAlerta = false;
						this.mensaje       = '';
					}catch(error){
						console.error(error);
						this.objListOrders = [];
						this.esError       = true;
						this.mostrarAlerta = true;
						this.mensaje       = 'Error de conexion al cargar el expediente.';
					}
				},
				formatFecha(fecha){
					if(!fecha) return 'N/D';
					var d = new Date((fecha+'').replace(' ','T'));
					if(isNaN(d.getTime())) return fecha;
					var meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
					var h = (fecha+'').indexOf(':') > -1 ? (' ' + ('0'+d.getHours()).slice(-2) + ':' + ('0'+d.getMinutes()).slice(-2)) : '';
					return d.getDate() + ' ' + meses[d.getMonth()] + ' ' + d.getFullYear() + h;
				},
				colorEstado(estado){
					var paleta = ['#4e73df','#1cc88a','#36b9cc','#f6a821','#e74a3b','#845ef7','#20c997','#fd7e14'];
					if(!estado) return '#858796';
					var hash = 0;
					for(var i=0;i<estado.length;i++){ hash = estado.charCodeAt(i) + ((hash<<5)-hash); }
					return paleta[Math.abs(hash) % paleta.length];
				}
			},
			mounted(){
				var self = this;
				//Cargar al hacer click en el item del dropdown "Expediente"
				$(document).on('click','#btnClickExpediente',function(){
					self.cargarExpediente();
				});
				document.getElementById('appExpediente').style.visibility = 'visible';
			}
		}).mount('#appExpediente');
	})();
</script>
