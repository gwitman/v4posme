<div id="heading" class="page-header">
    <h1><i class="icon20 i-dashboard"></i> Google Maps</h1>
</div>

<div class="row"> 
	<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="icon"><i class="icon20 i-tags-2"></i></div> 
					<h4>FILTRO DE BUSQUEDA</h4>
					<a href="#" class="minimize"></a>
					<div class="w-right" style="margin-right:20px">
						<button id="print-btn-report" class="btn btn-primary btn-full tip" title="Ver Reporte" rel="panel-body"><i class="icon20 i-file gap-right0"></i></button>
					</div>
				</div>
				<!-- End .panel-heading -->
			
				<div class="panel-body" >
					<form class="form-horizontal pad15 pad-bottom0" role="form">		
					
						<div class="form-group">
							<label class="col-lg-4 control-label" for="selectFilter">Compañia</label>
							<div class="col-lg-8"> 
								<div class="col-lg-1">
								</div>
								<div class="col-lg-11">
									<select name="txtCompanyName" id="txtCompanyName" class="select2">
											<option value="0">TODAS</option>
											<?php
											
											if($objListCompany)
											foreach($objListCompany as $i)
											{
												if($txtCompanyName == $i->companyName  )
													echo "<option value='".$i->companyName."' selected >".$i->companyName."</option>";
												else 
													echo "<option value='".$i->companyName."'  >".$i->companyName."</option>";
												
											
											}
											?>
									</select>
								</div>													
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-4 control-label" for="selectFilter">Colaborador</label>
							<div class="col-lg-8"> 
								<div class="col-lg-1">
								</div>
								<div class="col-lg-11">
									<select name="txtUserName" id="txtUserName" class="select2">
											<option value="0" >TODAS</option>
											<?php
										
											if($objListUser)
											foreach($objListUser as $i){
												if($txtUserName == $i->userName  )
													echo "<option value='".$i->userName."' selected >".$i->userName."</option>";
												else 
													echo "<option value='".$i->userName."'  >".$i->userName."</option>";
												
											}
											?>
									</select>
								</div>													
							</div>
						</div>
						
					</form>
				</div><!-- End .panel-body -->
			</div><!-- End .widget -->	
	<div>
<div>

</br>

<div id="map" style="height: 500px; width: 100%;"></div>



<script>

    var Locations 	= JSON.parse('<?php echo json_encode($objListRegisteredLocations) ?>');
    var Markets 	= [];
	
    function fnCargarMapa() {
        var coord = {
            lat: parseFloat(Locations[0].Latitude),
            lng: parseFloat(Locations[0].Longitude)
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: coord
        });
       
        Locations.forEach(location => {
            var mari = new google.maps.Marker ({
                position: {lat: parseFloat(location.Latitude), lng: parseFloat(location.Longitude)},
                map: map,
                title: location.Name + " " + location.companyName + "  ("+location.createdOn+")" 
				//descripcion : "descripcion",
				//label: "label"
				//icon: 'icono-inicial.png',
            });
			
			Markets.push(mari);
			
        });
		
		
		// Refresca la página cada 5 minutos, evitando el caché
		setInterval(function() {			
			 
			 var idex = 0 ;
			 Locations.forEach(location => {
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_mobile_api/getPositionGps",
					data 		: {						
							txtCompanyName: location.companyName,
							txtUserName: 	location.Name,
							txtIndex: 		idex
					},
					success		: function(data){					
						if(data.error == false && data.data.length > 0 )
						{
							
							var coord2 = {
								lat: parseFloat(data.data[0].Latitude),
								lng: parseFloat(data.data[0].Longitude)
							};							
							Markets[data.index].setPosition(coord2); 	// Actualiza la posición del marcador							
						}
					},
					error:function(xhr,data){						
						console.info("complete data error");																		
					}
				});
				
				idex++;
			});
			
			 
			 
		}, 10000);
		
		
		
    }
	
	$(document).ready(function(){
		$(document).on("click","#print-btn-report",function(){
							
			var txtCompanyName 			=	$("#txtCompanyName").val();
			var txtUserName	 			=	$("#txtUserName").val();
			
			
			if(!(txtCompanyName == ""  ) )
			{
				fnWaitOpen();
				txtCompanyName 	= txtCompanyName.replaceAll("/", "X2F");
				txtCompanyName 	= txtCompanyName.replaceAll(":", "X3A");
				txtCompanyName 	= txtCompanyName.replaceAll(" ", "X4Z");
				window.location	= "<?php echo base_url(); ?>/app_rrhh_gps/index/txtCompanyName/"+txtCompanyName+"/txtUserName/"+txtUserName;
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}
			
		});
		
		
		$("#txtCompanyName").change(function(){
			fnWaitOpen();
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data 		: { companyName : $(this).val() },
				url  		: "<?php echo base_url(); ?>/app_mobile_api/getUserByCompany",
				success:function(data){
					console.info("call app_mobile_api/getUserByCompany")
					fnWaitClose();
					
					if(data.catalogItems == null)
					return;
					
					
					$("#txtUserName").html("");
					$("#txtUserName").append("<option value='0'>TODOS</option>");
					
					
					$.each(data.catalogItems,function(i,obj){						
						$("#txtUserName").append("<option value='"+obj.userName+"'>"+obj.userName+"</option>");
					});
					
				},
				error:function(xhr,data){									
					fnShowNotification(data.message,"error");
					fnWaitClose();
				}
			});
		});
		
		
		

		
	});	

</script>