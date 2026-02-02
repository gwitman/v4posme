<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
			mensaje:						'',
			mostrarAlerta: 					true,		
			
			txtInboxID:						0,
			txtEmployeID:					<?php echo $objEmployerIDDefault; ?>,
			txtStartOn:						(new Date(new Date().getFullYear(),new Date().getMonth(),1).toISOString().slice(0, 10)),
			txtFinishOn:					(new Date().toISOString().slice(0, 10)),
			txtWorkflowStatusID:			'0',
			
			counterRegister:				0,
			noLeidas:						0,
			noLeidasPorcentage:				0,
			
			sinContestar:					0,
			sinContestarPorcentage:			0,
			
			conContestar:					0,
			conContestarPorcentage:			0,
			objListConversation: 			[],
        }
    },
    methods: {	
		async verConversacion(conversationID)
		{
			window.location.href = '<?php echo base_url()."/app_cxc_conversation/edit/entityID/"; ?>'+conversationID;
		},
		async fnAplicarBusqueda()
		{
			this.cargarListado();
		},
        async cargarListado() {
			try {
				
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationConversation_Report', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							txtStartOn: 			this.txtStartOn,
							txtFinishOn: 			this.txtFinishOn,
							txtEntityIDEmployer: 	this.txtEmployeID,
							txtInboxID:				this.txtInboxID,
							txtWorkflowStatusID:	this.txtWorkflowStatusID
						})
				});				
				
				const json 			= await res.json();
				// 🔴 CASO 1: success 	= false
				if (json.success === false) {
					this.objListConversation 	= [];
					this.counterRegister 		= 0 ;
					this.noLeidas 				= 0 ;
					this.noLeidasPorcentage		= 0;
					this.sinContestar 			= 0;
					this.sinContestarPorcentage	= 0;
					this.conContestar 			= 0;
					this.conContestarPorcentage	= 0;											
					this.mensaje 				= 'Ocurrió un error al cargar la información';
					return;
				}
				
				
				// 🟡 CASO 2: success = true pero sin datos
				if (json.success === true && (!json.data || json.data.length === 0)) {
					this.objListConversation 	= [];
					this.mensaje 				= 'No hay datos disponibles';
					this.counterRegister 		= 0 ;
					this.noLeidas 				= 0 ;
					this.noLeidasPorcentage		= 0;
					this.sinContestar 			= 0;
					this.sinContestarPorcentage	= 0;
					this.conContestar 			= 0;
					this.conContestarPorcentage	= 0;					
					this.mostrarAlerta			= false;
					return;
				}
				
				// 🟢 CASO 3: success = true con datos
				this.mostrarAlerta			= false;
				this.mensaje 				= '';		
				this.counterRegister 		= json.count ;	
				this.noLeidas 				= json.noLeidas ;				
				this.sinContestar 			= json.sinContestar ;
				this.conContestar 			= json.conContestar ;	
				
				if(json.count > 0)
				{
					this.noLeidasPorcentage		= Number(((json.noLeidas / json.count) * 100).toFixed(2));
					this.sinContestarPorcentage	= Number(((json.sinContestar / json.count) * 100).toFixed(2));
					this.conContestarPorcentage	= Number(((json.conContestar / json.count) * 100).toFixed(2));					
				}
				else
				{
					this.noLeidasPorcentage		= 0;
					this.sinContestarPorcentage	= 0;
					this.conContestarPorcentage	= 0;
				}
				
				this.objListConversation 	= json.data; 	// 🔥 aquí Vue limpia y vuelve a renderizar 				
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexión con el servidor';
                this.objListConversation 	= [];
            }
			
        }
    },
    
	
	
	mounted() {
		
		 // carga inicial
        this.cargarListado();

		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>