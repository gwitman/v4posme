<script>
	$(document).ready(function() {
		//Regresar
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});
		//Comando Guardar
		$(document).on("click", "#btnAcept", function() {
			fnWaitOpen();
			$("#form-new-account-type").attr("method", "POST");
			$("#form-new-account-type").attr("action", "<?php echo base_url(); ?>/app_accounting_indicators/save/edit");
			$("#form-new-account-type").submit();
		});
		//Comando Eliminar
		$(document).on("click", "#btnDelete", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
				fnWaitOpen();
				$.ajax({
					cache: false,
					dataType: 'json',
					type: 'POST',
					url: "<?php echo base_url(); ?>/app_accounting_indicators/delete",
					data: {
						companyID: <?php echo $objIndicator->companyID; ?>,
						indicatorID: <?php echo $objIndicator->indicadorID; ?>
					},
					success: function(data) {
						console.info("complete delete success");
						fnWaitClose();
						if (data.error) {
							fnShowNotification(data.message, "error");
						} else {
							window.location = "<?php echo base_url(); ?>/app_accounting_indicators/index";
						}
					},
					error: function(xhr, data) {
						console.info("complete delete error");
						fnWaitClose();
						fnShowNotification("Error 505", "error");
					}
				});
			});
		});


	});
</script>
<script>
	(function(g, u, i, d, e, s) {
		g[e] = g[e] || [];
		var f = u.getElementsByTagName(i)[0];
		var k = u.createElement(i);
		k.async = true;
		k.src = 'https://static.userguiding.com/media/user-guiding-' + s + '-embedded.js';
		f.parentNode.insertBefore(k, f);
		if (g[d]) return;
		var ug = g[d] = {
			q: []
		};
		ug.c = function(n) {
			return function() {
				ug.q.push([n, arguments])
			};
		};
		var m = ['previewGuide', 'finishPreview', 'track', 'identify', 'triggerNps', 'hideChecklist', 'launchChecklist'];
		for (var j = 0; j < m.length; j += 1) {
			ug[m[j]] = ug.c(m[j]);
		}
	})(window, document, 'script', 'userGuiding', 'userGuidingLayer', '744100086ID');
</script>
<script>
	//window.userGuiding.identify(userId*, attributes)

	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
		email: '<?php echo get_cookie("email"); ?>',
		name: '<?php echo get_cookie("email"); ?>',
		created_at: 1644403436643,
	});
	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>