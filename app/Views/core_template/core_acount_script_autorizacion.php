<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
<script>
$(document).ready(function () {
	setInterval(function () {
		$.ajax({
			url: '[[URL_AUTORIZATION]]', 
			type: 'GET',            
			dataType: 'json',
			success: function (response) {

				console.log(response.data[0].user_data);
				if (response.data[0].user_data === '[[NICKNAME]] create session' ) {
					window.location.href = '[[URL_REDIRECT]]';
				}
			},
			error: function (error) {
				console.error('Error en la petición AJAX', error);
			}
		});
	}, 5000); // 5000 ms = 5 segundos
});
</script>