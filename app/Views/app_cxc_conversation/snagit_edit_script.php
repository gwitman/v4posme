<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            tab: 						1,
			message: 					'',
            timer: 						null,
			entityID:					<?php echo $entityID; ?> ,
			guardando: 					false,			
			mostrarAlerta: 				false,
			
			//Tab 1 
			objListNotification: 		[],			
			
			//Tab 2 
			txtTab2ListEmployer:		[],
			txtTab2CustomerName:		'',
			txtTab2CustomerPhone:		'',
			txtTab2ListEmployerAsigned: [],
			
			//Tab 3
			txtTab3CustomerPhone:		'',
			txtTab3CustomerMessage:		''	
			
			
        }
    },
    methods: {
        // TAB 1
        async cargarListado() {
			try {
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationNotification_ByCustomer', {
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
		// TAB 2
		async cargarDatosDePantalla(){
			try {
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationCustomer_ByCustomer', {
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
				// 🟢 CASO 3: success = true con datos
				this.mensaje 					= '';
				this.txtTab2CustomerName		= json.objNatural.firstName;
				this.txtTab2CustomerPhone		= json.objCustomer.phoneNumber;				
				this.txtTab2ListEmployer		= json.objListEmployer;
				this.txtTab2ListEmployerAsigned = json.objListEmployerAsigned.map(
					item => Number(item.entityID)
				);
				this.txtTab3CustomerPhone		= json.objCustomer.phoneNumber;
				
				// 🔄 REFRESH obligatorio
				this.$nextTick(() => {
					$('#selectpickerMultiple').selectpicker('refresh')
				})
				
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexión con el servidor';
                this.objListNotification 	= [];
            }
		},
        async fnGuardarCliente() {
            this.guardando = true;
			
			await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: 					this.entityID,
						txtTab2CustomerName: 		this.txtTab2CustomerName,
						txtTab2CustomerPhone:		this.txtTab2CustomerPhone,
						txtTab2ListEmployerAsigned:	this.txtTab2ListEmployerAsigned
					})
			});		
			

            this.message 		= '';
			this.mostrarAlerta 	= false;
            this.guardando 		= false;
        },
		// TAB 3
        async fnGuardarNotification() {
            this.guardando = true;
			
			await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationNotification_ByCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: 					this.entityID,
						txtTab3CustomerMessage: 	this.txtTab3CustomerMessage
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
		this.cargarDatosDePantalla();

        //wg-// refresco cada 3 segundos
        //wg-this.timer = setInterval(() => {
        //wg-    this.cargarListado();
        //wg-}, 3000);
		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>