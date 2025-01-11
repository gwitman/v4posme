<script>
	//https://localhost/posmev4/app_rrhh_gps/edit/txtCompanyName/Demo4/txtUserName/abc
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
                title: location.Name +  "(" + location.createdOn + ")"
            });
        });
    }
	
	$(document).ready(function() {
		// Refresca la página cada 5 minutos, evitando el caché
		setInterval(function() {			
			window.location.href = window.location.origin + window.location.pathname + window.location.search + '/time/' + new Date().getTime();
		}, 300000);
	});

</script>

<!-- Link API Google Maps (PosMe API Key) -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV_kkjf0B6tCsFekM2x3wcqacvGm4ASx0&loading=async&callback=fnCargarMapa"></script>
