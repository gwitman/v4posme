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
            objUtilityFacturas:   [],
            objUtilityCostOfSales: [],
            objUtilityGastos:     [],
            detalleAbierto: null,
            detalleAbonoAbierto: null,
            detalleCashOutAbierto: null,
            detalleGastoAbierto: null,
            detalleProductoAbierto: null,
            detalleCreditoAbierto: null,
            detalleCostoAbierto: null,
            startOn:        '<?php echo date("Y-m-01"); ?>',
            endOn:          '<?php echo date("Y-m-d"); ?>',
            filterTransaction: '<?php echo (isset($company) && $company->type == "gymJalapa") ? "23" : "19"; ?>',
            filterCustomer: '',
            filterItem:     '',
            userName:       '<?php echo $userName; ?>',
            password:       '<?php echo $password; ?>',
            companyID:      '<?php echo $companyID; ?>',
            userID:         '<?php echo $userID; ?>'
        }
    },
    computed: {
        isMobile() {
            return window.innerWidth < 768;
        },
        isProductView() {
            return ['productSalesAmount', 'productSalesQuantity', 'productInventoryQuantity', 'productInventoryZero'].includes(this.filterTransaction);
        },
        isProductSalesView() {
            return ['productSalesAmount', 'productSalesQuantity'].includes(this.filterTransaction);
        },
        isCashOutView() {
            return ['30', '38'].includes(this.filterTransaction);
        },
        isUtilityView() {
            return this.filterTransaction === 'utility';
        },
        isDocumentView() {
            return ['19', '23'].includes(this.filterTransaction);
        },
        // Vista de crédito: Intereses y Capital (comparten estructura Documento/Cliente/Monto/Referencia)
        isCreditView() {
            return ['interest', 'capital'].includes(this.filterTransaction);
        },
        // Vista de costo de venta (maestro-detalle por producto)
        isCostView() {
            return this.filterTransaction === 'cost';
        },
        hasData() {
            if (this.isUtilityView) return this.utilityHasData;
            if (this.isCostView) return this.costGroupedData.length > 0;
            return this.objListData.length > 0;
        },
        utilityHasData() {
            return this.objUtilityFacturas.length > 0 || this.objUtilityCostOfSales.length > 0 || this.objUtilityGastos.length > 0;
        },
        creditTitle() {
            return this.filterTransaction === 'interest' ? 'Intereses' : 'Capital';
        },
        costGroupedData() {
            if (this.filterTransaction !== 'cost') return [];
            const map = {};
            this.objListData.forEach(item => {
                const key = item.transactionMasterID;
                if (!map[key]) {
                    map[key] = {
                        transactionMasterID: item.transactionMasterID,
                        Documento:           item.Documento,
                        Fecha:               item.Fecha,
                        Cliente:             item.Cliente,
                        Monto:               0,
                        detalle: []
                    };
                }
                map[key].Monto += parseFloat(item.SubMonto || 0);
                map[key].detalle.push({
                    Codigo:   item.Codigo,
                    Producto: item.Producto,
                    Cantidad: item.Cantidad,
                    SubMonto: item.SubMonto
                });
            });
            return Object.values(map);
        },
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
        productGridTitle() {
            const titles = {
                productSalesAmount: 'Productos Vendidos (Montos)',
                productSalesQuantity: 'Productos Vendidos (Cantidad)',
                productInventoryQuantity: 'Productos en Inventario (Cantidad)',
                productInventoryZero: 'Productos en 0'
            };
            return titles[this.filterTransaction] || '';
        },
        productValueLabel() {
            return this.filterTransaction === 'productSalesAmount' ? 'Monto' : 'Cantidad';
        },
        productValueField() {
            return this.filterTransaction === 'productSalesAmount' ? 'Monto' : 'Cantidad';
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
            if (this.isProductView) {
                if (this.filterTransaction === 'productSalesAmount') {
                    return this.objListData.reduce((sum, item) => sum + parseFloat(item.Monto || 0), 0);
                }
                return this.objListData.reduce((sum, item) => sum + parseFloat(item.Cantidad || 0), 0);
            }
            return this.objListData.reduce((sum, item) => sum + parseFloat(item.Monto || 0), 0);
        },
        totalClientes() {
            if (this.filterTransaction == '19') {
                const customers = [...new Set(this.groupedData.map(g => g.Cliente).filter(c => c))];
                return customers.length;
            }
            if (this.isProductView) return 0;
            const customers = [...new Set(this.objListData.map(item => item.Cliente || '').filter(c => c))];
            return customers.length;
        },
        totalProductos() {
            if (this.filterTransaction == '19') {
                const productos = [...new Set(this.objListData.map(item => item.Producto || '').filter(p => p))];
                return productos.length;
            }
            if (this.isProductView) {
                return this.objListData.length;
            }
            return 0;
        },
        utilityTotalFacturas() {
            return this.objUtilityFacturas.reduce((sum, item) => sum + parseFloat(item.SubMonto || 0), 0);
        },
        utilityTotalCostOfSales() {
            return this.objUtilityCostOfSales.reduce((sum, item) => sum + parseFloat(item.SubMonto || 0), 0);
        },
        utilityTotalGastos() {
            return this.objUtilityGastos.reduce((sum, item) => sum + parseFloat(item.Monto || 0), 0);
        },
        utilityTotalEgresos() {
            return this.utilityTotalCostOfSales + this.utilityTotalGastos;
        },
        utilityNeta() {
            return this.utilityTotalFacturas - this.utilityTotalEgresos;
        },
        // Totales para Intereses / Capital
        creditTotalMonto() {
            return this.objListData.reduce((sum, item) => sum + parseFloat(item.Monto || 0), 0);
        },
        creditTotalClientes() {
            const customers = [...new Set(this.objListData.map(item => item.Cliente || '').filter(c => c))];
            return customers.length;
        },
        // Totales para Costo de Venta
        costTotalMonto() {
            return this.objListData.reduce((sum, item) => sum + parseFloat(item.SubMonto || 0), 0);
        },
        costTotalDocumentos() {
            return this.costGroupedData.length;
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
            this.objUtilityFacturas   = [];
            this.objUtilityCostOfSales = [];
            this.objUtilityGastos     = [];
            this.detalleAbierto = null;
            this.detalleAbonoAbierto = null;
            this.detalleCashOutAbierto = null;
            this.detalleGastoAbierto = null;
            this.detalleProductoAbierto = null;
            this.detalleCreditoAbierto = null;
            this.detalleCostoAbierto = null;
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
        toggleDetalleAbono(idx) {
            this.detalleAbonoAbierto = this.detalleAbonoAbierto === idx ? null : idx;
        },
        toggleDetalleCashOut(idx) {
            this.detalleCashOutAbierto = this.detalleCashOutAbierto === idx ? null : idx;
        },
        toggleDetalleGasto(idx) {
            this.detalleGastoAbierto = this.detalleGastoAbierto === idx ? null : idx;
        },
        toggleDetalleProducto(idx) {
            this.detalleProductoAbierto = this.detalleProductoAbierto === idx ? null : idx;
        },
        toggleDetalleCredito(idx) {
            this.detalleCreditoAbierto = this.detalleCreditoAbierto === idx ? null : idx;
        },
        toggleDetalleCosto(idx) {
            this.detalleCostoAbierto = this.detalleCostoAbierto === idx ? null : idx;
        },
        async cargarListado() {
            try {
                this.loading = true;
                this.mostrarAlerta = false;
                this.mensaje = '';
                this.detalleAbierto = null;
                this.detalleCashOutAbierto = null;
                this.detalleGastoAbierto = null;
                this.detalleProductoAbierto = null;
                this.detalleCreditoAbierto = null;
                this.detalleCostoAbierto = null;

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
                    this.objUtilityFacturas    = [];
                    this.objUtilityCostOfSales = [];
                    this.objUtilityGastos      = [];
                    this.mensaje        = json.message || 'Error al cargar datos';
                    this.mostrarAlerta  = true;
                    return;
                }

                // Utilidad retorna estructura diferente
                if (this.filterTransaction === 'utility') {
                    this.objUtilityFacturas    = json.objDataFacturas || [];
                    this.objUtilityCostOfSales = json.objDataCostOfSales || [];
                    this.objUtilityGastos      = json.objDataGasto || [];
                    this.objListData           = [];

                    if (this.objUtilityFacturas.length === 0 && this.objUtilityCostOfSales.length === 0 && this.objUtilityGastos.length === 0) {
                        this.mensaje        = 'No hay datos para el rango seleccionado.';
                        this.mostrarAlerta  = true;
                    }
                    return;
                }

                // Resto de transacciones usan json.data
                this.objUtilityFacturas    = [];
                this.objUtilityCostOfSales = [];
                this.objUtilityGastos      = [];

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
                this.objUtilityFacturas    = [];
                this.objUtilityCostOfSales = [];
                this.objUtilityGastos      = [];
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