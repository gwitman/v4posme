<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            tab: 						1,
			
            timer: 						null,
			entityID:					<?php echo $entityID; ?> ,
			
			mensaje:					'',		
			guardando: 					false,			
			mostrarAlerta: 				false,
			
			objListNotification: 		[],
			message: 					'',
			txtCustomerName:			'',
			txtCustomerPhone:			''
        }
    },
    methods: {
        // TAB 1
        async cargarListado() {
			try {
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationByCustomer', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							entityID: this.entityID
						})
				});				
				  const json 			= await res.json();
				// 🔴 CASO 1: success 	= false
				if (json.success === false) {
					this.objListNotification 	= [];
					this.mensaje 				= 'Ocurrió un error al cargar la información';
					return;
				}
				
				
				// 🟡 CASO 2: success = true pero sin datos
				if (json.success === true && (!json.data || json.data.length === 0)) {
					this.objListNotification 	= [];
					this.mensaje 				= 'No hay datos disponibles';
					return;
				}
				
				// 🟢 CASO 3: success = true con datos
				this.mensaje 				= '';
				this.objListNotification 	= json.data; // 🔥 aquí Vue limpia y vuelve a renderizar
				
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexión con el servidor';
                this.objListNotification 	= [];
            }
			
        },
		// TAB 3
        async fnGuardarCliente() {
            this.guardando = true;
			
			await fetch('<?php echo base_url(); ?>/app_cxc_api/setCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: 			this.entityID,
						txtCustomerName: 	this.txtCustomerName,
						txtCustomerPhone:	this.txtCustomerPhone
					})
			});		
			

            this.message 		= '';
			this.mostrarAlerta 	= false;
            this.guardando 		= false;
        },
		// TAB 3
        async fnGuardarNotification() {
            this.guardando = true;
			
			await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationByCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: 	this.entityID,
						message: 	this.message
					})
			});		
			

            this.message 	= '';
            this.guardando 	= false;
            
        }
    },
    beforeUnmount() {
        clearInterval(this.timer);
    },
	mounted() {
		
		 // carga inicial
        this.cargarListado();

        // refresco cada 3 segundos
        this.timer = setInterval(() => {
            this.cargarListado();
        }, 3000);
		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>