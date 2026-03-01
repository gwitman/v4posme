<script>
const { createApp } = Vue;

createApp({
    data() {
        return {           
            timer: 							null,
			mensaje:						'',
			mostrarAlerta: 					true,			
			intervaloAjax: 					null,
			sonidoReproducido:				false,
			permisoParaVerTodasLasActivas:	<?php echo $permisoParaVerTodasLasActivas; ?>,
			parametroNoDebeVerFoto:			<?php echo $objParameterCONVERSATION_LIST_CONVERSATION_NOT_PHOTE; ?>,
			parametroNoDebeEscucharSonido:	<?php echo $objParameterCONVERSATION_LIST_CONVERSATION_NOT_BELL; ?>,
			marcarLeido:					false,
			marcarCerrados:					false,
			nuevaConversacion:				false,
			activeTab: 						'LISTA DE CONVERSACIONES MIAS', // Por defecto activo
			txtCustomerFind: 				'',			
			objListConversation: 			[],			
        }
    },
	
	computed: {
        objListConversationFilter() {			
            if (!this.txtCustomerFind) {
                return this.objListConversation;
            }

            const texto = this.txtCustomerFind.toLowerCase();			
            return this.objListConversation.filter(item => 
				 ['firstName', 'customerNumber', 'phoneNumber','identification','lastMessage','subCategoria' ].some(key =>
					item[key]?.toString().toLowerCase().includes(texto.toLowerCase())
				)
	
            );
        }
    },

    methods: {	
		playChatNotification() {
			const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

			const oscillator = audioCtx.createOscillator();
			const gainNode   = audioCtx.createGain();

			oscillator.type = "sine";
			oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
			oscillator.frequency.exponentialRampToValueAtTime(
				1320,
				audioCtx.currentTime + 0.15
			);

			gainNode.gain.setValueAtTime(0.0001, audioCtx.currentTime);
			gainNode.gain.exponentialRampToValueAtTime(
				0.15,
				audioCtx.currentTime + 0.05
			);
			gainNode.gain.exponentialRampToValueAtTime(
				0.0001,
				audioCtx.currentTime + 0.6
			);

			oscillator.connect(gainNode);
			gainNode.connect(audioCtx.destination);

			oscillator.start();
			oscillator.stop(audioCtx.currentTime + 0.6);
		},
		verificarMensajesNoLeidos(objListConversation)
		{
			const hayNoLeidos = objListConversation.some(
				obj => Number(obj.messgeConterNotRead) > 0
			);

			
			if (hayNoLeidos) {
				this.playChatNotification();
				this.sonidoReproducido = true;
			}

			if (!hayNoLeidos) {
				this.sonidoReproducido = false;
			}
		},
        // TAB 1
		handleClick(tab) {
		  // Cambiar la pestaña activa
		  this.activeTab = tab;

		  // Ejecutar AJAX según la pestaña
		  if(tab === 'LISTA DE CLIENTES PARA CREAR CONVERSACIONES') 
		  {
			this.mostrarAlerta = true;
			this.cargarListado();
            this.detenerAjax();
		  } 
		  else
		  {
			this.mostrarAlerta = true;
			this.cargarListado();
			this.iniciarAjax();
		  }
		},
		async fnAplicarCambiosConversaciones()
		{
			offcanvas.hide();
			this.mostrarAlerta = true;
			
			if(this.nuevaConversacion == true )
			{
				window.location.href = '<?php echo base_url()."/app_cxc_conversation/edit/entityID/0"; ?>';
				return;
			}
			
			const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationConversation_Tools', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						txtMarcarTodosComoLeido: 			this.marcarLeido,
						txtCerrarTodasLasConversaciones:	this.marcarCerrados
					})
			});	
			this.marcarLeido 	= false;
			this.marcarCerrados = false;			
			this.mostrarAlerta 	= false;
			
		},
		async fnLimpiarCambiosConversaciones(){
			this.marcarLeido 	= false;
			this.marcarCerrados = false;
		},
        async cargarListado() {
			try {
				
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationConversation_ByEmployer', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							entityID: this.activeTab
						})
				});				
				
				const json 			= await res.json();
				// 🔴 CASO 1: success 	= false
				if (json.success === false) {
					this.objListConversation 	= [];
					this.mensaje 				= 'Ocurrió un error al cargar la información';
					return;
				}
				
				
				// 🟡 CASO 2: success = true pero sin datos
				if (json.success === true && (!json.data || json.data.length === 0)) {
					this.objListConversation 	= [];
					this.mensaje 				= 'No hay datos disponibles';
					this.mostrarAlerta			= false;
					return;
				}
				
				// 🟢 CASO 3: success = true con datos		
				var resultData = json.data.map(item => {
					
					// Procesar identification
					if (item.identification && item.identification.length > 10) {
						item.identification = item.identification.slice(-10);
					}

					// Procesar phoneNumber
					if (item.phoneNumber && item.phoneNumber.length > 10) {
						item.phoneNumber = item.phoneNumber.slice(-10);
					}

					// Procesar firstName: remover "posMeConnect_" y limitar a 30 caracteres
					if (item.firstName && typeof item.firstName === 'string') {
						item.firstName = item.firstName.replace(/posMeConnect_/g, '');
						if (item.firstName.length > 30) {
							item.firstName = item.firstName.substring(0, 30);
						}
					}

					// Procesar lastMessage: limitar palabras largas a 30 caracteres
					if (item.lastMessage && typeof item.lastMessage === 'string') {
						item.lastMessage = item.lastMessage.split(' ').map(palabra => {
							if (palabra.length > 30) {
								return palabra.substring(0, 30);
							}
							return palabra;
						}).join(' ');
					}

					return item;
				});

				
				this.mostrarAlerta			= false;
				this.mensaje 				= '';				
				this.objListConversation 	= resultData; 	// 🔥 aquí Vue limpia y vuelve a renderizar 				
				this.verificarMensajesNoLeidos(resultData) 	//this.playChatNotification();
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexión con el servidor';
                this.objListConversation 	= [];
            }
			
        },
		iniciarAjax() {
            if (this.intervaloAjax) return;

			var second 	= 10 ;
			var time 	= second * 1000;
            this.cargarListado();
            this.intervaloAjax = setInterval(() => {
                this.cargarListado();
            }, time);
        },
        detenerAjax() {
            if (this.intervaloAjax) {
                clearInterval(this.intervaloAjax);
                this.intervaloAjax = null;
                console.log('AJAX detenido');
            }
        }
    },
    
	watch: {
        txtCustomerFind(nuevoValor) {
			
			console.log('🔍 Buscando:', nuevoValor);
            if (nuevoValor && nuevoValor.length > 0) {
                this.detenerAjax();
            } else {
                this.iniciarAjax();
            }
        }
    },
	
	mounted() {
		
		 // carga inicial
        this.iniciarAjax();

        //wg-// refresco cada 3 segundos
        //wg-this.timer = setInterval(() => {
        //wg-    this.cargarListado();
        //wg-}, 3000);
		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>