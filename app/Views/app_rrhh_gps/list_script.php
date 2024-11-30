<div id="heading" class="page-header">
    <h1><i class="icon20 i-dashboard"></i> Google Maps</h1>
</div>

<div id="map" style="height: 700px; width: 100%;"></div>


<script>

    var Locations = JSON.parse('<?php echo json_encode($objListRegisteredLocations) ?>');
    
    function fnCargarMapa() {
        var coord = {
            lat: parseFloat(Locations[0].Latitude),
            lng: parseFloat(Locations[0].Longitude)
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: coord
        });
       
        Locations.forEach(location => {
            new google.maps.Marker ({
                position: {lat: parseFloat(location.Latitude), lng: parseFloat(location.Longitude)},
                map: map,
                title: location.Name
            });
        });
    }

</script>