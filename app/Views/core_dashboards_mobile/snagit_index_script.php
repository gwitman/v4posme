<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            mensaje:        'Seleccione los filtros y presione Consultar para ver los datos.',
            mostrarAlerta:  true,
            loading:        false,
            objListData:    [],
            detalleAbierto: null,
            startOn:        '<?php echo date("Y-m-01"); ?>',
            endOn:          '<?php echo date("Y-m-d"); ?>',
            filterTransaction: '19',
            filterCustomer: '',
            filterItem:     '',
            userName:       '<?php echo $userName; ?>',
            password:       '<?php echo $password; ?>',
            companyID:      '<?php echo $companyID; ?>',
            userID:         '<?php echo $userID; ?>'
        }
    },
    computed: {
        groupedData() {
            if (this.filterTransaction != '19') return [];
            const map = {};
            this.objListData.forEach(item => {
                const key = item.transactionMasterID;
                if (!map[key]) {
                    map[key] = {
                        transactionMasterID:    item.transactionMasterID,
                        Documento:              item.Documento,
                        Fecha:                  item.Fecha,
                        Cliente:                item.Cliente,
                        Monto:                  parseFloat(item.Monto || 0),
                        detalle: []
                    };
                }
                map[key].detalle.push({
                    Codigo:     item.Codigo,
                    Producto:   item.Producto,
                    Cantidad:   item.Cantidad,
                    SubMonto:   item.SubMonto
                });
            });
            return Object.values(map);
        },
        totalDocumentos() {
            if (this.filterTransaction == '19') {
                return this.groupedData.length;
            }
            return this.objListData.length;
        },
        totalMonto() {
            if (this.filterTransaction == '19') {
                return this.groupedData.reduce((sum, g) => sum + g.Monto, 0);
            }
            return this.objListData.reduce((sum, item) => sum + parseFloat(item.Monto || 0), 0);
        },
        totalClientes() {
            if (this.filterTransaction == '19') {
                const customers = [...new Set(this.groupedData.map(g => g.Cliente).filter(c => c))];
                return customers.length;
            }
            const customers = [...new Set(this.objListData.map(item => item.Cliente || '').filter(c => c))];
            return customers.length;
        },
        totalProductos() {
            if (this.filterTransaction == '19') {
                const productos = [...new Set(this.objListData.map(item => item.Producto || '').filter(p => p))];
                return productos.length;
            }
            return 0;
        }
    },
    watch: {
        startOn() { this.limpiarResultados(); },
        endOn() { this.limpiarResultados(); },
        filterTransaction() { this.limpiarResultados(); },
        filterCustomer() { this.limpiarResultados(); },
        filterItem() { this.limpiarResultados(); }
    },
    methods: {
        limpiarResultados() {
            this.objListData    = [];
            this.detalleAbierto = null;
            this.mensaje        = 'Los filtros han cambiado. Presione Consultar para actualizar.';
            this.mostrarAlerta  = true;
        },
        formatMoney(value) {
            return parseFloat(value || 0).toLocaleString('es-NI', { style: 'currency', currency: 'NIO', minimumFractionDigits: 2 });
        },
        formatFecha(fecha) {
            if (!fecha) return '-';
            const d = new Date(fecha);
            if (isNaN(d)) return fecha;
            return d.toLocaleDateString('es-NI', { day: '2-digit', month: '2-digit', year: 'numeric' });
        },
        toggleDetalle(idx) {
            this.detalleAbierto = this.detalleAbierto === idx ? null : idx;
        },
        async cargarListado() {
            try {
                this.loading = true;
                this.mostrarAlerta = false;
                this.mensaje = '';
                this.detalleAbierto = null;

                const formData = new FormData();
                formData.append('userName', this.userName);
                formData.append('password', this.password);
                formData.append('startOn', this.startOn);
                formData.append('endOn', this.endOn);
                formData.append('customerName', this.filterCustomer);
                formData.append('itemName', this.filterItem);
                formData.append('transactionID', this.filterTransaction);

                const res = await fetch('<?php echo base_url(); ?>/core_dashboards_mobile/getReportData', {
                    method: 'POST',
                    body: formData
                });

                const json = await res.json();

                if (json.success === false) {
                    this.objListData    = [];
                    this.mensaje        = json.message || 'Error al cargar datos';
                    this.mostrarAlerta  = true;
                    return;
                }

                if (json.success === true && (!json.data || json.data.length === 0)) {
                    this.objListData    = [];
                    this.mensaje        = 'No hay datos para el rango seleccionado.';
                    this.mostrarAlerta  = true;
                    return;
                }

                this.objListData = json.data;

            } catch (error) {
                console.error(error);
                this.mensaje        = 'Error de conexión al servidor.';
                this.mostrarAlerta  = true;
                this.objListData    = [];
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {
        document.getElementById('app').style.visibility = 'visible';
        this.cargarListado();
    }
}).mount('#app');
</script>
