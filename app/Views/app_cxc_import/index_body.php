<div id="app" class="pv2 pv2-has-mbar cim<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>"></div>

<?php
    // La plantilla vive aquí y se monta dentro de $(function(){...}) (ver index_script):
    // theme-genyx/js/app.js aplica Uniform a los select presentes al cargar, y eso rompe los controles de Vue.
    // Todos los valores del archivo se pintan con llaves de Vue (texto escapado); nunca con v-html.
?>
<script type="text/x-template" id="cimTemplate">
    <form autocomplete="off" @submit.prevent>

        <!-- Encabezado -->
        <div class="pv2-card pv2-header">
            <div class="pv2-header-info">
                <div class="pv2-header-icon"><i class="bi bi-people" aria-hidden="true"></i></div>
                <div>
                    <p class="pv2-header-title">Importación de clientes</p>
                    <p class="pv2-header-sub">Cargue un archivo Excel, valide los datos e importe los clientes en lotes</p>
                </div>
            </div>
            <div class="pv2-actions">
                <button type="button" class="btn btn-default" :disabled="descargandoPlantilla" @click="descargarPlantilla">
                    <i class="bi bi-download" aria-hidden="true"></i> {{ descargandoPlantilla ? 'Preparando...' : 'Descargar plantilla' }}
                </button>
            </div>
        </div>

        <!-- (1) Archivo -->
        <div class="pv2-card">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-file-earmark-excel" aria-hidden="true"></i></span> Archivo de clientes</p>
            <p class="cim-muted">
                Columnas: Código, <strong>Nombre</strong> (obligatoria), Apellido, Teléfono, País, Departamento, Municipio, Dirección,
                Nombre comercial, Nombre legal, Tipo de identificación y Cédula. Archivos .xlsx, .xls o .csv de máximo 5 MB y {{ entero(maxRows) }} filas.
            </p>

            <label class="cim-drop" :class="{ 'is-loading': leyendo, 'is-disabled': ocupado }">
                <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                <span>{{ leyendo ? 'Leyendo archivo...' : (archivo ? 'Cambiar archivo' : 'Seleccione un archivo .xlsx, .xls o .csv') }}</span>
                <input type="file" accept=".xlsx,.xls,.csv" :disabled="ocupado" @change="leerArchivo" aria-label="Seleccionar archivo de clientes">
            </label>

            <div v-if="errorArchivo" class="cim-alert is-error" role="alert"><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>{{ errorArchivo }}</span></div>

            <template v-if="filas.length > 0">
                <div class="cim-file-info">
                    <span><i class="bi bi-file-earmark-check" aria-hidden="true"></i> {{ archivo }}</span>
                    <span class="pv2-count">{{ entero(filas.length) }} filas</span>
                    <button type="button" class="btn btn-default cim-btn-danger-soft" :disabled="ocupado" @click="quitarArchivo"><i class="bi bi-x-lg" aria-hidden="true"></i> Quitar</button>
                </div>
                <div v-if="columnasFaltantes.length > 0" class="cim-alert is-warn">
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <span>El archivo no tiene estas columnas, se enviarán vacías: {{ columnasFaltantes.join(', ') }}.</span>
                </div>
                <div v-if="columnasIgnoradas.length > 0" class="cim-alert is-info">
                    <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                    <span>Columnas no reconocidas (se ignoran): {{ columnasIgnoradas.join(', ') }}.</span>
                </div>
            </template>
        </div>

        <!-- Valores válidos (catálogos) -->
        <div class="pv2-card cim-catalog">
            <button type="button" class="cim-collapse" :aria-expanded="mostrarCatalogos ? 'true' : 'false'" aria-controls="cimCatalogos" @click="mostrarCatalogos = !mostrarCatalogos">
                <span class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-list-check" aria-hidden="true"></i></span> Valores válidos</span>
                <i class="bi" :class="mostrarCatalogos ? 'bi-chevron-up' : 'bi-chevron-down'" aria-hidden="true"></i>
            </button>
            <div v-show="mostrarCatalogos" id="cimCatalogos" class="cim-catalog-body">
                <p class="cim-muted">Escriba en el archivo el nombre tal como aparece aquí. País, departamento y municipio deben corresponder entre sí.</p>

                <p class="cim-subtitle">Tipos de identificación</p>
                <div v-if="catalogos.identificationType.length === 0" class="pv2-empty">No hay tipos de identificación configurados.</div>
                <div v-else class="cim-chips">
                    <span v-for="item in catalogos.identificationType" :key="item.id" class="cim-chip">{{ item.name }}</span>
                </div>

                <p class="cim-subtitle">Países</p>
                <div v-if="catalogos.country.length === 0" class="pv2-empty">No hay países configurados.</div>
                <div v-else class="cim-chips">
                    <span v-for="item in catalogos.country" :key="item.id" class="cim-chip">{{ item.name }}</span>
                </div>

                <p class="cim-subtitle">Departamentos y municipios</p>
                <div class="cim-catalog-filters">
                    <div class="form-group">
                        <label class="pv2-label" for="cimCatPais">País</label>
                        <select id="cimCatPais" class="form-control" v-model="catPais">
                            <option value="">Todos los países</option>
                            <option v-for="item in catalogos.country" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="cimCatDepto">Departamento</label>
                        <select id="cimCatDepto" class="form-control" v-model="catDepto">
                            <option value="">Todos los departamentos</option>
                            <option v-for="item in deptosDelPais" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="cimCatTexto">Buscar</label>
                        <input type="search" id="cimCatTexto" class="form-control" maxlength="80" autocomplete="off" placeholder="Nombre..." v-model="catTexto">
                    </div>
                </div>
                <div class="cim-catalog-lists">
                    <div>
                        <p class="cim-list-title">Departamentos <span class="pv2-count">{{ entero(deptosFiltrados.length) }}</span></p>
                        <div v-if="deptosFiltrados.length === 0" class="pv2-empty">Sin coincidencias.</div>
                        <div v-else class="cim-chips cim-chips-scroll">
                            <span v-for="item in deptosFiltrados.slice(0, limiteLista)" :key="item.id" class="cim-chip">{{ item.name }}</span>
                        </div>
                        <span v-if="deptosFiltrados.length > limiteLista" class="pv2-help">Mostrando {{ entero(limiteLista) }} de {{ entero(deptosFiltrados.length) }}; use el buscador.</span>
                    </div>
                    <div>
                        <p class="cim-list-title">Municipios <span class="pv2-count">{{ entero(municipiosFiltrados.length) }}</span></p>
                        <div v-if="municipiosFiltrados.length === 0" class="pv2-empty">Sin coincidencias.</div>
                        <div v-else class="cim-chips cim-chips-scroll">
                            <span v-for="item in municipiosFiltrados.slice(0, limiteLista)" :key="item.id" class="cim-chip">{{ item.name }}</span>
                        </div>
                        <span v-if="municipiosFiltrados.length > limiteLista" class="pv2-help">Mostrando {{ entero(limiteLista) }} de {{ entero(municipiosFiltrados.length) }}; use el buscador.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- (2) Vista previa, validación e importación -->
        <template v-if="filas.length > 0">
            <div class="pv2-kpis" aria-live="polite">
                <div class="pv2-kpi k-info">
                    <div class="pv2-kpi-icon"><i class="bi bi-list-ol" aria-hidden="true"></i></div>
                    <div class="pv2-kpi-body"><span class="pv2-kpi-label">Filas</span><span class="pv2-kpi-value">{{ entero(filas.length) }}</span></div>
                </div>
                <div class="pv2-kpi k-ok">
                    <div class="pv2-kpi-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
                    <div class="pv2-kpi-body"><span class="pv2-kpi-label">Válidas</span><span class="pv2-kpi-value">{{ validado ? entero(totalValidas) : 'Sin validar' }}</span></div>
                </div>
                <div class="pv2-kpi" :class="totalErrores > 0 ? 'is-negative' : 'k-warn'">
                    <div class="pv2-kpi-icon"><i class="bi bi-x-octagon" aria-hidden="true"></i></div>
                    <div class="pv2-kpi-body"><span class="pv2-kpi-label">Con error</span><span class="pv2-kpi-value">{{ validado ? entero(totalErrores) : 'Sin validar' }}</span></div>
                </div>
                <div v-if="validado" class="pv2-kpi k-purple">
                    <div class="pv2-kpi-icon"><i class="bi bi-person-check" aria-hidden="true"></i></div>
                    <div class="pv2-kpi-body"><span class="pv2-kpi-label">Importadas</span><span class="pv2-kpi-value">{{ entero(totalImportadas) }}</span></div>
                </div>
                <div v-else class="pv2-kpi k-warn">
                    <div class="pv2-kpi-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></div>
                    <div class="pv2-kpi-body"><span class="pv2-kpi-label">Con advertencias</span><span class="pv2-kpi-value">{{ entero(totalAvisos) }}</span></div>
                </div>
            </div>

            <div v-if="!validado && totalAvisos > 0" class="cim-alert is-warn">
                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                <span>Hay {{ entero(totalAvisos) }} filas con posibles problemas (marcadas en amarillo). La revisión definitiva la hace el servidor al validar.</span>
            </div>
            <div v-if="errorValidacion" class="cim-alert is-error" role="alert">
                <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                <span>{{ errorValidacion }}</span>
                <button type="button" class="btn btn-default cim-btn-soft" :disabled="!puedeValidar" @click="validar">Reintentar</button>
            </div>
            <div v-if="importacionIniciada && !importando" class="cim-alert is-info">
                <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                <span>Ya se importaron filas de este archivo: no se puede volver a validar. Para corregir filas con error, cárguelas en un archivo nuevo.</span>
            </div>

            <div class="pv2-card">
                <div class="pv2-detail-head">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-table" aria-hidden="true"></i></span> Vista previa</p>
                    <div class="cim-toolbar">
                        <label class="cim-check">
                            <input type="checkbox" v-model="soloErrores"> <span>Solo filas con error</span>
                        </label>
                        <button type="button" class="btn btn-default cim-btn-soft" :disabled="!puedeValidar" @click="validar">
                            <i class="bi bi-shield-check" aria-hidden="true"></i> {{ validando ? 'Validando...' : (validado ? 'Validar de nuevo' : 'Validar') }}
                        </button>
                        <button type="button" class="btn btn-primary" :disabled="!puedeImportar" :title="motivoBloqueo" @click="confirmarImportacion">
                            <i class="bi bi-cloud-upload" aria-hidden="true"></i> Importar {{ entero(totalPendientesImportar) }} filas válidas
                        </button>
                    </div>
                </div>
                <p v-if="motivoBloqueo && !ocupado" class="cim-block-reason">{{ motivoBloqueo }}</p>

                <div v-if="filasFiltradas.length === 0" class="pv2-empty"><i class="bi bi-emoji-smile" aria-hidden="true"></i>{{ soloErrores ? 'No hay filas con error.' : 'No hay filas para mostrar.' }}</div>
                <template v-else>
                    <div class="pv2-table-wrap">
                        <table class="pv2-table cim-table">
                            <caption class="sr-only">Filas del archivo, página {{ pagina }} de {{ paginas }}</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Fila</th>
                                    <th scope="col">Estado</th>
                                    <th v-for="col in columnas" :key="col.key" scope="col">{{ col.titulo }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fila in filasPagina" :key="fila.row" class="row_razon" :class="{ 'has-error': esFilaConError(fila), 'is-done': fila.estado === 'importada' }">
                                    <td data-label="Fila" class="pv2-doc">{{ entero(fila.row) }}</td>
                                    <td data-label="Estado" class="cim-col-estado">
                                        <span class="cim-cell">
                                            <span class="cim-badge" :class="claseEstado(fila.estado)">{{ textoEstado(fila.estado) }}</span>
                                            <span v-if="fila.customerNumber" class="cim-cell-code">{{ fila.customerNumber }}</span>
                                            <span v-for="mensaje in fila.erroresGenerales" :key="mensaje" class="cim-cell-msg is-error">{{ mensaje }}</span>
                                            <span v-if="fila.mensaje && fila.estado !== 'importada'" class="cim-cell-msg is-error">{{ fila.mensaje }}</span>
                                        </span>
                                    </td>
                                    <td v-for="col in columnas" :key="col.key" :data-label="col.titulo"
                                        :class="{ 'cim-cell-error': fila.errores[col.key], 'cim-cell-warn': !fila.errores[col.key] && !validado && fila.avisos[col.key] }">
                                        <span class="cim-cell">
                                            <span class="cim-cell-value">{{ fila.valores[col.key] }}</span>
                                            <span v-if="fila.errores[col.key]" class="cim-cell-msg is-error"><i class="bi bi-x-circle" aria-hidden="true"></i> {{ fila.errores[col.key] }}</span>
                                            <span v-else-if="!validado && fila.avisos[col.key]" class="cim-cell-msg is-warn"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i> {{ fila.avisos[col.key] }}</span>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="paginas > 1" class="cim-pager">
                        <button type="button" class="cim-icon-btn" aria-label="Página anterior" title="Anterior" :disabled="pagina <= 1" @click="pagina--"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
                        <span>Página {{ entero(pagina) }} de {{ entero(paginas) }} · {{ entero(filasFiltradas.length) }} filas</span>
                        <button type="button" class="cim-icon-btn" aria-label="Página siguiente" title="Siguiente" :disabled="pagina >= paginas" @click="pagina++"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
                    </div>
                </template>

                <div v-if="importacionIniciada && !importando" class="cim-after">
                    <button type="button" class="btn btn-default cim-btn-soft" @click="descargarResultados"><i class="bi bi-file-earmark-arrow-down" aria-hidden="true"></i> Descargar resultados</button>
                </div>
            </div>
        </template>

        <!-- Barra inferior del teléfono -->
        <div v-if="filas.length > 0" class="pv2-mbar">
            <div class="pv2-mbar-info">
                <p class="pv2-mbar-label">{{ entero(filas.length) }} filas</p>
                <p class="pv2-mbar-label">{{ validado ? entero(totalValidas) + ' válidas · ' + entero(totalErrores) + ' con error' : 'Sin validar' }}</p>
            </div>
            <button v-if="!validado" type="button" class="btn btn-primary" :disabled="!puedeValidar" @click="validar"><i class="bi bi-shield-check" aria-hidden="true"></i> {{ validando ? 'Validando...' : 'Validar' }}</button>
            <button v-else type="button" class="btn btn-primary" :disabled="!puedeImportar" @click="confirmarImportacion"><i class="bi bi-cloud-upload" aria-hidden="true"></i> Importar {{ entero(totalPendientesImportar) }}</button>
        </div>
    </form>

    <!-- Modal de progreso: Bootstrap 3 del tema, teletransportado a body para quedar sobre el fondo oscuro -->
    <teleport to="body">
        <div class="modal fade" id="cimModalProgreso" tabindex="-1" role="dialog" aria-labelledby="cimModalTitulo" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog cim-modal-dialog" role="document">
                <div class="modal-content pv2 cim">
                    <div class="modal-header cim-modal-header">
                        <div class="cim-modal-head-info">
                            <span class="cim-modal-icon"><i class="bi" :class="importando ? 'bi-hourglass-split' : 'bi-check2-all'" aria-hidden="true"></i></span>
                            <div>
                                <h4 class="modal-title" id="cimModalTitulo">{{ importando ? 'Importando clientes' : 'Importación finalizada' }}</h4>
                                <p class="cim-modal-sub">{{ importando ? 'No cierre ni recargue esta página' : resumenFinal }}</p>
                            </div>
                        </div>
                        <button v-if="!importando" type="button" class="cim-icon-btn cim-modal-close" aria-label="Cerrar" title="Cerrar" @click="cerrarModal"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
                    </div>
                    <div class="modal-body cim-modal-body">
                        <div class="progress cim-progress">
                            <div class="progress-bar" :class="progreso.fallidos > 0 ? 'progress-bar-warning' : 'progress-bar-success'" role="progressbar"
                                 aria-valuemin="0" aria-valuemax="100" :aria-valuenow="porcentaje" :style="{ width: porcentaje + '%' }">
                                {{ porcentaje }}%
                            </div>
                        </div>
                        <p class="cim-progress-text">{{ entero(progreso.procesadas) }} de {{ entero(progreso.total) }}</p>

                        <div class="cim-counters">
                            <div class="cim-counter is-ok"><span class="cim-counter-label">Importados</span><span class="cim-counter-value">{{ entero(progreso.importados) }}</span></div>
                            <div class="cim-counter is-bad"><span class="cim-counter-label">Fallidos</span><span class="cim-counter-value">{{ entero(progreso.fallidos) }}</span></div>
                            <div class="cim-counter"><span class="cim-counter-label">Pendientes</span><span class="cim-counter-value">{{ entero(progreso.total - progreso.procesadas) }}</span></div>
                        </div>

                        <div v-if="progreso.detenido" class="cim-alert is-error" role="alert"><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>{{ progreso.detenido }}</span></div>
                        <div v-if="cancelando && importando" class="cim-alert is-warn"><i class="bi bi-hourglass" aria-hidden="true"></i><span>Cancelando: se detendrá al terminar el lote actual.</span></div>

                        <p class="cim-subtitle">Registro</p>
                        <div class="cim-log" role="log" aria-live="polite" aria-relevant="additions">
                            <p v-if="log.length === 0" class="cim-muted">Preparando...</p>
                            <p v-for="item in log" :key="item.id" class="cim-log-item" :class="'is-' + item.tipo">{{ item.texto }}</p>
                        </div>
                    </div>
                    <div class="modal-footer cim-modal-footer">
                        <button v-if="importando" type="button" class="btn btn-default cim-btn-danger-soft" :disabled="cancelando" @click="cancelarImportacion">
                            <i class="bi bi-stop-circle" aria-hidden="true"></i> {{ cancelando ? 'Cancelando...' : 'Cancelar' }}
                        </button>
                        <template v-else>
                            <button type="button" id="cimBtnResultados" class="btn btn-primary" @click="descargarResultados"><i class="bi bi-file-earmark-arrow-down" aria-hidden="true"></i> Descargar resultados</button>
                            <button type="button" class="btn btn-default" @click="cerrarModal">Cerrar</button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</script>
