<script>
	//https://localhost/posmev4/app_rrhh_gps/edit/txtCompanyName/Demo4/txtUserName/abc
    var Locations = JSON.parse('<?php echo json_encode($objListRegisteredLocations) ?>');    
    function fnCargarMapa() {
        var coord = {
            lat: parseFloat(Locations[0].Latitude),
            lng: parseFloat(Locations[0].Longitude)
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: coord
        });
       
        Locations.forEach(location => {
            new google.maps.Marker ({
                position: {lat: parseFloat(location.Latitude), lng: parseFloat(location.Longitude)},
                map: map,
                title: location.Name +  "(" + location.createdOn + ")" 
				//icon: 'icono-inicial.png',
            });
        });
    }
	
	$(document).ready(function() {
		// Refresca la página cada 5 minutos, evitando el caché
		setInterval(function() {			
			window.location.href = window.location.origin + window.location.pathname + window.location.search + '/time/' + new Date().getTime();
		}, 300000);
	});




	/*
	let mapa;
        let marcador;

        // Coordenadas iniciales
        const coordenadasIniciales = { lat: 37.7749, lng: -122.4194 };

        // Inicialización del mapa
        function initMap() {
            mapa = new google.maps.Map(document.getElementById('mapa'), {
                center: coordenadasIniciales,
                zoom: 14,
            });

            // Crear el marcador inicial
            marcador = new google.maps.Marker({
                position: coordenadasIniciales,
                map: mapa,
                icon: 'icono-marcador.png', // Ícono personalizado (opcional)
            });

            // Simular movimiento del marcador
            simularMovimiento();
        }

        // Simulación de movimiento
        function simularMovimiento() {
            const trayecto = [
                { lat: 37.7750, lng: -122.4195 },
                { lat: 37.7751, lng: -122.4196 },
                { lat: 37.7752, lng: -122.4197 },
                { lat: 37.7753, lng: -122.4198 },
            ];

            let indice = 0;

            const intervalo = setInterval(() => {
                if (indice < trayecto.length) {
                    const nuevaPosicion = trayecto[indice];
                    marcador.setPosition(nuevaPosicion); // Actualiza la posición del marcador
                    mapa.panTo(nuevaPosicion); // Centra el mapa en la nueva posición
                    indice++;
                } else {
                    clearInterval(intervalo); // Detiene la simulación
                }
            }, 1000); // Actualización cada 1 segundo
        }
	*/
</script>

<!-- Link API Google Maps (PosMe API Key) -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV_kkjf0B6tCsFekM2x3wcqacvGm4ASx0&loading=async&callback=fnCargarMapa"></script>



        