<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
const { createApp } = Vue;

createApp({
	data() {
		return {
			mensaje:			'Seleccione un usuario y presione Consultar.',
			mostrarAlerta:		true,
			loading:			false,
			userID:				'<?php echo $userIDDefault; ?>',
			objListData:		[],
			statistic:			[],
			counterTransaction:	0,
			colores: ['#696cff', '#71dd37', '#03c3ec', '#ffab00', '#ff3e1d', '#8592a3', '#233446']
		}
	},
	methods: {
		porcentaje(valor) {
			const max = Math.max(...this.statistic.map(s => parseInt(s.counter || 0)), 1);
			return Math.round((parseInt(valor || 0) / max) * 100);
		},
		formatFecha(fecha) {
			// El campo last_activity viene en formato YmdHis (ej: 20260113094530)
			if (!fecha) return '-';
			const s = String(fecha);
			if (s.length === 14) {
				const y = s.substr(0,4), mo = s.substr(4,2), d = s.substr(6,2);
				const h = s.substr(8,2), mi = s.substr(10,2), se = s.substr(12,2);
				return `${d}/${mo}/${y} ${h}:${mi}:${se}`;
			}
			return s;
		},
		async cargarListado() {
			try {
				if (!this.userID || this.userID === '0') {
					this.mensaje = 'Debe seleccionar un usuario.';
					this.mostrarAlerta = true;
					return;
				}

				this.loading = true;

				const formData = new FormData();
				formData.append('userID', this.userID);

				const res = await fetch('<?php echo base_url(); ?>/core_acount_logsession/getData', {
					method: 'POST',
					body: formData
				});

				const json = await res.json();

				// CASO 1: error
				if (json.success === false) {
					this.objListData		= [];
					this.statistic			= [];
					this.counterTransaction	= 0;
					this.mensaje			= json.message || 'Error al cargar los datos.';
					this.mostrarAlerta		= true;
					return;
				}

				// CASO 2: sin datos
				const sinSesiones	= (!json.data || json.data.length === 0);
				const sinStats		= (!json.statistic || json.statistic.length === 0);
				if (json.success === true && sinSesiones && sinStats) {
					this.objListData		= [];
					this.statistic			= [];
					this.counterTransaction	= 0;
					this.mensaje			= 'El usuario seleccionado no tiene sesiones ni transacciones.';
					this.mostrarAlerta		= true;
					return;
				}

				// CASO 3: con datos
				this.mostrarAlerta		= false;
				this.mensaje			= '';
				this.objListData		= json.data || [];
				this.statistic			= json.statistic || [];
				this.counterTransaction	= json.counterTransaction || 0;

			} catch (error) {
				console.error(error);
				this.mensaje		= 'Error de conexión con el servidor.';
				this.mostrarAlerta	= true;
				this.objListData	= [];
				this.statistic		= [];
			} finally {
				this.loading = false;
			}
		}
	},
	mounted() {
		document.getElementById('app').style.visibility = 'visible';
		if (this.userID && this.userID !== '0') {
			this.cargarListado();
		}
	}
}).mount('#app');
</script>
