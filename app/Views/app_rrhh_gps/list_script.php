<div id="heading" class="page-header">
    <h1><i class="icon20 i-dashboard"></i> Google Maps</h1>
</div>

<div class="pv2<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- filtro -->
    <div class="pv2-card">
        <div class="pv2-detail-head">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Filtro de búsqueda</p>
            <button type="button" id="print-btn-report" class="btn btn-primary" title="Ver mapa"><i class="icon16 i-file"></i> Ver mapa</button>
        </div>

        <form role="form" autocomplete="off" onsubmit="return false;">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="pv2-label" for="txtCompanyName">Compañia</label>
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
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="pv2-label" for="txtUserName">Colaborador</label>
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
    </div>
    <!-- /filtro -->

    <!-- mapa -->
    <div class="pv2-card">
        <div class="pv2-detail-head">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-dashboard"></i></span> Ubicaciones <span class="pv2-count" id="gpsCount">0</span></p>
        </div>
        <div class="pv2-empty" id="gpsSinDatos" style="display:none">
            <i class="icon20 i-dashboard"></i>
            No hay ubicaciones para este filtro.
        </div>
        <div id="map" style="height: 60vh; min-height: 320px; width: 100%;"></div>
    </div>
    <!-- /mapa -->
</div>

<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    //Se imprime como literal JSON (sin JSON.parse): un apostrofo en un nombre no rompe el script
    var Locations   = <?php echo json_encode($objListRegisteredLocations, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?> || [];
    var Markets     = [];

    function fnCargarMapa() {
        document.getElementById('gpsCount').textContent = Locations.length;

        //Sin ubicaciones: aviso en lugar del mapa
        if(Locations.length == 0){
            document.getElementById('map').style.display         = 'none';
            document.getElementById('gpsSinDatos').style.display = 'block';
            return;
        }

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
            });

            Markets.push(mari);
        });

        //Actualiza la posicion de los marcadores cada 10 segundos
        setInterval(function() {

            var idex = 0 ;
            Locations.forEach(location => {
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_mobile_api/getPositionGps",
                    data        : {
                            txtCompanyName: location.companyName,
                            txtUserName:    location.Name,
                            txtIndex:       idex
                    },
                    success     : function(data){
                        if(data.error == false && data.data.length > 0 )
                        {
                            var coord2 = {
                                lat: parseFloat(data.data[0].Latitude),
                                lng: parseFloat(data.data[0].Longitude)
                            };
                            Markets[data.index].setPosition(coord2);    // Actualiza la posicion del marcador
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
        pv2MobileSelects(".pv2 select.select2");

        $(document).on("click","#print-btn-report",function(){

            var txtCompanyName  = $("#txtCompanyName").val();
            var txtUserName     = $("#txtUserName").val();

            if(!(txtCompanyName == ""  ) )
            {
                fnWaitOpen();
                txtCompanyName  = txtCompanyName.replaceAll("/", "X2F");
                txtCompanyName  = txtCompanyName.replaceAll(":", "X3A");
                txtCompanyName  = txtCompanyName.replaceAll(" ", "X4Z");
                window.location = "<?php echo base_url(); ?>/app_rrhh_gps/index/txtCompanyName/"+txtCompanyName+"/txtUserName/"+txtUserName;
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
                data        : { companyName : $(this).val() },
                url         : "<?php echo base_url(); ?>/app_mobile_api/getUserByCompany",
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

                    //En telefono el combo sigue nativo: select2 solo se refresca en PC
                    if(!pv2IsMobile()){
                        $("#txtUserName").select2();
                    }
                },
                error:function(xhr,data){
                    fnShowNotification(data.message,"error");
                    fnWaitClose();
                }
            });
        });
    });
</script>
