<div id="app" class="pv2 pv2-has-mbar prb<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>"></div>

<?php
    // La plantilla vive aquí y se monta dentro de $(function(){...}) (ver index_script):
    // theme-genyx/js/app.js aplica Uniform a todo select presente al cargar, y eso rompe los controles de Vue.
    // Los valores se pintan siempre con {{ }}, nunca con v-html.
?>
<script type="text/x-template" id="prbTemplate">
    <form autocomplete="off" @submit.prevent>

        <!-- Encabezado -->
        <div class="pv2-card pv2-header">
            <div class="pv2-header-info">
                <div class="pv2-header-icon"><i class="icon20 i-table"></i></div>
                <div>
                    <p class="pv2-header-title">
                        {{ tituloEncabezado }}
                        <span v-if="hayVista" class="pv2-chip">{{ etiquetaModo }}</span>
                        <span v-if="dirty" class="pv2-chip">Sin guardar</span>
                    </p>
                    <p class="pv2-header-sub">{{ hayVista ? vistaEtiqueta : 'Elija una vista o abra una de sus consultas' }}</p>
                </div>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="prb-tabs" role="tablist">
            <button type="button" role="tab" class="prb-tab" :class="{ 'is-active': seccion === 'vistas' }" :aria-selected="seccion === 'vistas'" @click="seccion = 'vistas'">1. Vistas</button>
            <button type="button" role="tab" class="prb-tab" :class="{ 'is-active': seccion === 'configurar' }" :aria-selected="seccion === 'configurar'" :disabled="!hayVista" @click="seccion = 'configurar'">2. Configurar</button>
            <button type="button" role="tab" class="prb-tab" :class="{ 'is-active': seccion === 'consultas' }" :aria-selected="seccion === 'consultas'" @click="seccion = 'consultas'">3. Mis consultas <span class="pv2-count">{{ consultas.length }}</span></button>
        </div>

        <div v-if="errorGeneral" class="prb-alert is-error" role="alert">{{ errorGeneral }}</div>
        <div v-if="cargandoConfig" class="prb-loading">Cargando configuración...</div>

        <!-- (1) Vistas -->
        <div v-show="seccion === 'vistas'" class="pv2-card">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Vistas disponibles</p>
            <div class="form-group">
                <label class="pv2-label" for="prbBuscarVista">Buscar vista</label>
                <input type="text" id="prbBuscarVista" class="form-control" autocomplete="off" placeholder="Escriba parte del nombre o la descripción" v-model="textoVista">
            </div>

            <div v-if="cargandoVistas" class="prb-loading">Cargando vistas...</div>
            <div v-else-if="errorVistas" class="prb-alert is-error" role="alert">
                {{ errorVistas }}
                <div><button type="button" class="btn btn-default" @click="cargarVistas">Reintentar</button></div>
            </div>
            <div v-else-if="vistas.length === 0" class="pv2-empty">No tiene vistas disponibles para reportes.</div>
            <div v-else-if="vistasFiltradas.length === 0" class="pv2-empty">Ninguna vista coincide con la búsqueda.</div>
            <div v-else class="prb-vistas">
                <button v-for="vista in vistasFiltradas" :key="vista.dataViewID" type="button" class="prb-vista"
                        :class="{ 'is-active': vista.dataViewID == dataViewID }" :disabled="cargandoConfig" @click="seleccionarVista(vista)">
                    <span class="prb-vista-nombre">{{ vista.label }}</span>
                    <span v-if="vista.description" class="prb-vista-desc">{{ vista.description }}</span>
                </button>
            </div>
        </div>

        <!-- (2) Configurar y resultado -->
        <div v-show="seccion === 'configurar' && hayVista">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-cog"></i></span> Configurar reporte</p>

                <div v-for="aviso in avisos" :key="aviso" class="prb-alert is-warn">{{ aviso }}</div>

                <!-- El nombre de la consulta se captura en el modal Guardar (#prbNombre) -->

                <!-- Modo consulta: la configuración es un resumen -->
                <div v-if="esConsulta" class="prb-bloque">
                    <dl class="prb-resumen">
                        <dt>Mostrar</dt>
                        <dd>{{ resumen.columnas.join(', ') }}</dd>
                        <template v-if="resumen.agrupar.length > 0">
                            <dt>Agrupar por</dt>
                            <dd>{{ resumen.agrupar.join(', ') }}</dd>
                        </template>
                        <template v-if="resumen.orden.length > 0">
                            <dt>Ordenar por</dt>
                            <dd>{{ resumen.orden.join(', ') }}</dd>
                        </template>
                    </dl>
                </div>

                <template v-else>
                    <!-- Mostrar, con agrupación y cálculo por columna -->
                    <div class="prb-bloque">
                        <div class="prb-bloque-head">
                            <p class="prb-bloque-title">Mostrar <span class="pv2-count">{{ columnas.length }}</span></p>
                            <div class="prb-agregar">
                                <select class="form-control" aria-label="Agregar columna" v-model="campoNuevo" @change="agregarColumna">
                                    <option value="">Agregar columna...</option>
                                    <option v-for="campo in catalogoDisponible" :key="campo.name" :value="campo.name">{{ campo.label }}</option>
                                </select>
                                <button type="button" class="btn btn-default" :disabled="catalogoDisponible.length === 0" @click="agregarTodas">Todas</button>
                            </div>
                        </div>
                        <p v-if="columnas.length === 0" class="prb-vacio">Agregue las columnas que desea ver.</p>
                        <div class="prb-bloque-lista">
                        <div v-for="(columna, indice) in columnas" :key="columna.uid" class="prb-fila">
                            <div class="prb-campo prb-campo-ancho">
                                <label class="pv2-label" :for="columna.uid + 't'">Título ({{ etiquetaCatalogo(columna.name) }})</label>
                                <input type="text" class="form-control" :id="columna.uid + 't'" maxlength="150" autocomplete="off" v-model="columna.label">
                            </div>
                            <div class="prb-campo">
                                <label class="pv2-label" :for="columna.uid + 'c'">Agrupar / calcular</label>
                                <select class="form-control" :id="columna.uid + 'c'" :value="columna.modo" @change="cambiarModo(columna, $event.target.value)">
                                    <option v-for="opcion in opcionesModo(columna.name)" :key="opcion.value" :value="opcion.value">{{ opcion.label }}</option>
                                </select>
                            </div>
                            <div class="prb-campo">
                                <label class="pv2-label" :for="columna.uid + 'y'">Tipo</label>
                                <select class="form-control" :id="columna.uid + 'y'" :disabled="tieneFuncion(columna)" v-model="columna.type">
                                    <option value="text">Texto</option>
                                    <option value="number">Número</option>
                                    <option value="date">Fecha</option>
                                </select>
                            </div>
                            <div class="prb-campo">
                                <label class="pv2-label" :for="columna.uid + 'a'">Alineación</label>
                                <select class="form-control" :id="columna.uid + 'a'" v-model="columna.align">
                                    <option value="left">Izquierda</option>
                                    <option value="right">Derecha</option>
                                </select>
                            </div>
                            <div class="prb-botones">
                                <button type="button" class="prb-btn-icon" aria-label="Subir columna" title="Subir" :disabled="indice === 0" @click="moverColumna(indice, -1)">&#9650;</button>
                                <button type="button" class="prb-btn-icon" aria-label="Bajar columna" title="Bajar" :disabled="indice === columnas.length - 1" @click="moverColumna(indice, 1)">&#9660;</button>
                                <button type="button" class="prb-btn-icon is-danger" aria-label="Quitar columna" title="Quitar" @click="quitarColumna(indice)">&#10005;</button>
                            </div>
                        </div>
                        </div>
                        <div v-if="avisoMoneda" class="prb-alert is-warn">Está sumando importes sin agrupar por moneda: el resultado puede mezclar monedas distintas.</div>
                    </div>

                    <!-- Ordenar -->
                    <div class="prb-bloque">
                        <div class="prb-bloque-head">
                            <p class="prb-bloque-title">Ordenar <span class="pv2-count">{{ orden.length }}</span></p>
                            <button type="button" class="btn btn-default" :disabled="opcionesOrdenLibres.length === 0" @click="agregarOrden"><i class="icon16 i-plus"></i> Agregar orden</button>
                        </div>
                        <p v-if="orden.length === 0" class="prb-vacio">Sin orden definido.</p>
                        <div class="prb-bloque-lista">
                        <div v-for="(item, indice) in orden" :key="item.uid" class="prb-fila">
                            <div class="prb-campo prb-campo-ancho">
                                <label class="pv2-label" :for="item.uid">Campo</label>
                                <select class="form-control" :id="item.uid" v-model="item.clave">
                                    <option v-for="opcion in opcionesOrdenDe(item)" :key="opcion.clave" :value="opcion.clave">{{ opcion.label }}</option>
                                </select>
                            </div>
                            <div class="prb-botones">
                                <button type="button" class="prb-btn-icon" :class="{ 'is-active': item.dir === 'ASC' }" aria-label="Ascendente" title="Ascendente" :aria-pressed="item.dir === 'ASC'" @click="item.dir = 'ASC'">&#9650;</button>
                                <button type="button" class="prb-btn-icon" :class="{ 'is-active': item.dir === 'DESC' }" aria-label="Descendente" title="Descendente" :aria-pressed="item.dir === 'DESC'" @click="item.dir = 'DESC'">&#9660;</button>
                                <button type="button" class="prb-btn-icon is-danger" aria-label="Quitar orden" title="Quitar" @click="orden.splice(indice, 1)">&#10005;</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </template>

                <!-- Filtros: en modo consulta solo se editan los valores -->
                <div class="prb-bloque">
                    <div class="prb-bloque-head">
                        <p class="prb-bloque-title">Filtros <span class="pv2-count">{{ filtros.length }}</span></p>
                        <button v-if="!esConsulta" type="button" class="btn btn-default" :disabled="catalogo.length === 0" @click="agregarFiltro"><i class="icon16 i-plus"></i> Agregar filtro</button>
                    </div>
                    <p v-if="filtros.length === 0" class="prb-vacio">Sin filtros.</p>
                    <div class="prb-bloque-lista">
                    <div v-for="(filtro, indice) in filtros" :key="filtro.uid" class="prb-fila">
                        <div class="prb-campo prb-campo-ancho">
                            <template v-if="esConsulta">
                                <span class="pv2-label">Campo</span>
                                <div class="prb-campo-texto">{{ etiquetaCatalogo(filtro.column) }} ({{ etiquetaOperador(filtro) }})</div>
                            </template>
                            <template v-else>
                                <label class="pv2-label" :for="filtro.uid + 'c'">Campo</label>
                                <select class="form-control" :id="filtro.uid + 'c'" :value="filtro.column" @change="cambiarCampoFiltro(filtro, $event.target.value)">
                                    <option v-for="campo in catalogo" :key="campo.name" :value="campo.name">{{ campo.label }}</option>
                                </select>
                            </template>
                        </div>
                        <div v-if="!esConsulta" class="prb-campo">
                            <label class="pv2-label" :for="filtro.uid + 'o'">Operador</label>
                            <select class="form-control" :id="filtro.uid + 'o'" v-model="filtro.operator" @change="cambiarOperadorFiltro(filtro)">
                                <option v-for="operador in operadoresDe(filtro.column)" :key="operador.value" :value="operador.value">{{ operador.label }}</option>
                            </select>
                        </div>
                        <div class="prb-campo">
                            <label class="pv2-label" :for="filtro.uid + (tipoCampo(filtro.column) === 'date' ? 'vd' : 'v')">{{ filtro.operator === 'between' ? 'Desde' : 'Valor' }}</label>
                            <!-- Fecha: "Fecha manual" usa el calendario nativo; las demás opciones las resuelve el servidor al ejecutar -->
                            <select v-if="tipoCampo(filtro.column) === 'date'" class="form-control prb-fecha-tipo" :id="filtro.uid + 'vd'" v-model="filtro.dinamico">
                                <option value="">Fecha manual</option>
                                <option v-for="opcion in opcionesFecha(filtro, 'value')" :key="opcion.value" :value="opcion.value">{{ opcion.label }}</option>
                            </select>
                            <input v-if="!(tipoCampo(filtro.column) === 'date' && filtro.dinamico)" class="form-control" :id="filtro.uid + 'v'" autocomplete="off"
                                   :aria-label="tipoCampo(filtro.column) === 'date' ? 'Fecha' : null"
                                   :class="{ 'pv2-num': tipoCampo(filtro.column) === 'number' }"
                                   :type="tipoCampo(filtro.column) === 'date' ? 'date' : 'text'"
                                   :inputmode="tipoCampo(filtro.column) === 'number' ? 'decimal' : 'text'"
                                   v-model="filtro.value" @blur="formatearValorFiltro(filtro, 'value')">
                        </div>
                        <div v-if="filtro.operator === 'between'" class="prb-campo">
                            <label class="pv2-label" :for="filtro.uid + (tipoCampo(filtro.column) === 'date' ? 'wd' : 'w')">Hasta</label>
                            <select v-if="tipoCampo(filtro.column) === 'date'" class="form-control prb-fecha-tipo" :id="filtro.uid + 'wd'" v-model="filtro.dinamico2">
                                <option value="">Fecha manual</option>
                                <option v-for="opcion in opcionesFecha(filtro, 'value2')" :key="opcion.value" :value="opcion.value">{{ opcion.label }}</option>
                            </select>
                            <input v-if="!(tipoCampo(filtro.column) === 'date' && filtro.dinamico2)" class="form-control" :id="filtro.uid + 'w'" autocomplete="off"
                                   :aria-label="tipoCampo(filtro.column) === 'date' ? 'Fecha' : null"
                                   :class="{ 'pv2-num': tipoCampo(filtro.column) === 'number' }"
                                   :type="tipoCampo(filtro.column) === 'date' ? 'date' : 'text'"
                                   :inputmode="tipoCampo(filtro.column) === 'number' ? 'decimal' : 'text'"
                                   v-model="filtro.value2" @blur="formatearValorFiltro(filtro, 'value2')">
                        </div>
                        <div v-if="!esConsulta" class="prb-botones">
                            <button type="button" class="prb-btn-icon is-danger" aria-label="Quitar filtro" title="Quitar" @click="filtros.splice(indice, 1)">&#10005;</button>
                        </div>
                    </div>
                    </div>
                    <span v-if="esConsulta" class="pv2-help">Los valores que cambie aquí solo se usan para esta consulta; no se guardan.</span>
                    <span v-else class="pv2-help">Un filtro sin valor se guarda con la consulta y se llena al usarla; mientras esté vacío no se aplica.</span>
                </div>

                <!-- Validación -->
                <div v-if="erroresConfig.length > 0" id="prbErrores" class="prb-alert is-error" role="alert" style="margin-top:12px">
                    <ul><li v-for="error in erroresConfig" :key="error">{{ error }}</li></ul>
                </div>

                <!-- Acciones -->
                <div class="prb-barra">
                    <button v-if="esConsulta" type="button" class="btn btn-default" @click="pasarAEdicion"><i class="icon16 i-pencil-4"></i> Editar configuración</button>
                    <button type="button" class="btn btn-primary pv2-hide-xs" :disabled="generando" @click="generar"><i class="icon16 i-play-2"></i> {{ generando ? 'Generando...' : 'Generar' }}</button>
                    <button v-if="!esConsulta" type="button" class="btn btn-default" :disabled="guardando" @click="abrirModalGuardar"><i class="icon16 i-checkmark-4"></i> {{ guardando ? 'Guardando...' : 'Guardar como consulta recurrente' }}</button>
                    <button type="button" class="btn btn-default" :disabled="exportando" @click="exportar"><i class="icon16 i-file-excel"></i> {{ exportando ? 'Exportando...' : 'Exportar a Excel' }}</button>
                    <button type="button" class="btn btn-default" :disabled="generando || !configValida" :title="configValida ? 'Abre el resultado paginado en otra pestaña' : 'Complete la configuración para poder generar'" @click="abrirNuevaPagina"><i class="icon16 i-new-tab"></i> Abrir en nueva página</button>
                </div>
            </div>

            <!-- Resultado -->
            <div class="pv2-card" id="prbResultado">
                <div class="pv2-detail-head">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-table"></i></span> Resultado</p>
                    <span v-if="generado" class="pv2-count">{{ rango }}</span>
                </div>

                <div v-if="errorResultado" class="prb-alert is-error" role="alert">{{ errorResultado }}</div>
                <div v-if="resultStale" class="prb-alert is-warn">La configuración cambió. Presione Generar para actualizar el resultado.</div>
                <div v-if="generando" class="prb-loading">Generando reporte...</div>

                <div v-if="!generado && !generando && !errorResultado" class="pv2-empty">Configure el reporte y presione Generar.</div>
                <div v-else-if="generado && filas.length === 0 && !generando" class="pv2-empty">No hay datos para los criterios seleccionados.</div>
                <div v-else-if="generado && filas.length > 0" class="pv2-table-wrap">
                    <table class="pv2-table">
                        <thead>
                            <tr>
                                <th v-for="col in columnasResultado" :key="col.key" :class="{ 'prb-right': col.align === 'right' }">{{ col.label }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(fila, indice) in filas" :key="indice" class="row_razon">
                                <td v-for="col in columnasResultado" :key="col.key" :data-label="col.label" :class="{ 'prb-right': col.align === 'right' }"><span>{{ celda(fila, col) }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="generado && filas.length > 0" class="prb-paginacion">
                    <div class="prb-paginacion-grupo">
                        <label class="pv2-label" for="prbPageSize" style="margin:0">Filas por página</label>
                        <select id="prbPageSize" class="form-control" :disabled="generando" :value="pageSize" @change="cambiarTamanio($event.target.value)">
                            <option v-for="tamanio in tamanios" :key="tamanio" :value="tamanio">{{ tamanio }}</option>
                        </select>
                    </div>
                    <div class="prb-paginacion-grupo">
                        <button type="button" class="prb-btn-icon" aria-label="Página anterior" title="Anterior" :disabled="generando || page <= 1" @click="irAPagina(page - 1)">&#9664;</button>
                        <span>Página {{ page }}</span>
                        <button type="button" class="prb-btn-icon" aria-label="Página siguiente" title="Siguiente" :disabled="generando || !hayPaginaSiguiente" @click="irAPagina(page + 1)">&#9654;</button>
                    </div>
                </div>
                <div v-if="generado && limitePantalla" class="prb-alert is-info" style="margin-top:10px">Se alcanzó el máximo de {{ maxRowsTexto }} filas en pantalla. Use Exportar a Excel para obtener más.</div>
            </div>
        </div>

        <!-- (3) Mis consultas -->
        <div v-show="seccion === 'consultas'" class="pv2-card">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list"></i></span> Mis consultas</p>

            <div v-if="cargandoConsultas" class="prb-loading">Cargando consultas...</div>
            <div v-else-if="errorConsultas" class="prb-alert is-error" role="alert">
                {{ errorConsultas }}
                <div><button type="button" class="btn btn-default" @click="cargarConsultas">Reintentar</button></div>
            </div>
            <div v-else-if="consultas.length === 0" class="pv2-empty">Aún no tiene consultas guardadas.</div>
            <div v-else class="pv2-table-wrap">
                <table class="pv2-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Vista</th>
                            <th>Modificada</th>
                            <th class="prb-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="consulta in consultas" :key="consulta.reportBuilderID" class="row_razon">
                            <td data-label="Nombre">
                                <div>
                                    <button type="button" class="prb-link" :disabled="cargandoConfig" @click="abrirConsulta(consulta.reportBuilderID, 'consulta')">{{ consulta.name }}</button>
                                    <div v-if="consulta.isShared || !consulta.isOwner || consulta.dashboardName || consulta.viewTypeName" class="prb-badges">
                                        <span v-if="consulta.isShared" class="prb-badge is-ok">Compartida</span>
                                        <span v-if="!consulta.isOwner" class="prb-badge is-muted">de {{ consulta.ownerNickname }}</span>
                                        <span v-if="consulta.viewTypeName" class="prb-badge">{{ consulta.viewTypeName }}</span>
                                        <span v-if="consulta.dashboardName" class="prb-badge is-info">{{ consulta.dashboardName }} · Orden {{ consulta.orden }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Vista"><span>{{ nombreVista(consulta.dataViewID) }}</span></td>
                            <td data-label="Modificada"><span>{{ fechaConsulta(consulta.modifiedOn) }}</span></td>
                            <td class="prb-td-acciones">
                                <div class="prb-acciones-fila">
                                    <button type="button" class="btn btn-default" :disabled="cargandoConfig" @click="abrirConsulta(consulta.reportBuilderID, 'edicion')"><i class="icon16 i-pencil-4"></i> Editar</button>
                                    <button type="button" class="btn btn-default" :disabled="cargandoConfig" @click="duplicarConsulta(consulta)"><i class="icon16 i-copy"></i> Duplicar</button>
                                    <button type="button" class="btn btn-danger" :disabled="cargandoConfig" @click="eliminarConsulta(consulta)"><i class="icon16 i-remove"></i> Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Barra inferior del teléfono -->
        <div v-if="seccion === 'configurar' && hayVista" class="pv2-mbar">
            <div class="pv2-mbar-info">
                <p class="pv2-mbar-label">Resultado</p>
                <p class="pv2-mbar-label">{{ generado ? rango : 'Sin generar' }}</p>
            </div>
            <button type="button" class="btn btn-primary" :disabled="generando" @click="generar"><i class="icon16 i-play-2"></i> {{ generando ? 'Generando...' : 'Generar' }}</button>
        </div>
    </form>

    <!-- Modal Guardar consulta: modal Bootstrap 3 del tema, teletransportado a body para quedar sobre el fondo oscuro -->
    <teleport to="body">
        <div class="modal fade" id="prbModalGuardar" tabindex="-1" role="dialog" aria-labelledby="prbModalGuardarTitulo" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog prb-modal-dialog" role="document">
                <form class="modal-content pv2 prb" autocomplete="off" @submit.prevent="confirmarGuardar">
                    <div class="modal-header prb-modal-header">
                        <h4 class="modal-title" id="prbModalGuardarTitulo">Guardar consulta</h4>
                        <button type="button" class="prb-btn-icon" aria-label="Cerrar" title="Cerrar" :disabled="guardando" @click="cerrarModalGuardar">&#10005;</button>
                    </div>
                    <div class="modal-body prb-modal-body">
                        <div v-if="erroresModal.length > 0" id="prbErroresModal" class="prb-alert is-error" role="alert">
                            <ul><li v-for="error in erroresModal" :key="error">{{ error }}</li></ul>
                        </div>
                        <div v-for="aviso in avisosPresentacion" :key="aviso" class="prb-alert is-warn">{{ aviso }}</div>
                        <div v-if="catalogos.errors.length > 0" class="prb-alert is-info">Algunas listas no se pudieron cargar: {{ catalogos.errors.join(' ') }}</div>

                        <div class="form-group">
                            <label class="pv2-label" for="prbNombre">Nombre de la consulta <span class="pv2-req">*</span></label>
                            <input type="text" id="prbNombre" class="form-control" maxlength="150" autocomplete="off" placeholder="Ej.: Ventas del mes" v-model="nombre">
                        </div>

                        <div class="prb-modal-grid">
                            <div class="form-group">
                                <label class="pv2-label" for="prbVisibilidad">Visibilidad</label>
                                <select id="prbVisibilidad" class="form-control" v-model.number="formGuardar.isShared">
                                    <option :value="0">Solo para mí</option>
                                    <option v-if="esAdminCompania" :value="1">Para todos los usuarios</option>
                                </select>
                                <span v-if="!esAdminCompania && compartidaOriginal" class="pv2-help">Esta consulta estaba compartida; solo un administrador puede compartirla, al guardar quedará solo para usted.</span>
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="prbTipoVista">Tipo de vista</label>
                                <select id="prbTipoVista" class="form-control" v-model.number="formGuardar.viewTypeCatalogItemID">
                                    <option :value="0">— Ninguno —</option>
                                    <option v-for="item in catalogos.viewType" :key="item.catalogItemID" :value="item.catalogItemID">{{ item.display }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="prbDashboard">Dashboard</label>
                                <select id="prbDashboard" class="form-control" v-model.number="formGuardar.dashboardCatalogItemID">
                                    <option :value="0">— Ninguno —</option>
                                    <option v-for="item in catalogos.dashboard" :key="item.catalogItemID" :value="item.catalogItemID">{{ item.display }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="prbOrdenDashboard">Orden</label>
                                <input type="number" id="prbOrdenDashboard" class="form-control pv2-num" min="0" step="1" inputmode="numeric" autocomplete="off" v-model="formGuardar.orden">
                            </div>
                        </div>

                        <!-- Presentación en el dashboard: solo si eligió uno -->
                        <template v-if="formGuardar.dashboardCatalogItemID">
                            <p class="prb-subtitulo">Presentación en el dashboard</p>
                            <div class="prb-modal-grid">
                                <div class="form-group">
                                    <label class="pv2-label" for="prbTipoTarjeta">Tipo de tarjeta <span class="pv2-req">*</span></label>
                                    <select id="prbTipoTarjeta" class="form-control" v-model.number="formGuardar.cardTypeCatalogItemID">
                                        <option :value="0">Seleccione...</option>
                                        <option v-for="item in catalogos.cardType" :key="item.catalogItemID" :value="item.catalogItemID">{{ item.display }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="prbAncho">Ancho <span class="pv2-req">*</span></label>
                                    <select id="prbAncho" class="form-control" v-model.number="formGuardar.widthCatalogItemID">
                                        <option :value="0">Seleccione...</option>
                                        <option v-for="item in catalogos.width" :key="item.catalogItemID" :value="item.catalogItemID">{{ item.display }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="prbTituloTarjeta">Título</label>
                                    <input type="text" id="prbTituloTarjeta" class="form-control" maxlength="150" autocomplete="off" placeholder="Si se deja vacío se usa el nombre" v-model="formGuardar.title">
                                </div>
                            </div>

                            <!-- Gráfico -->
                            <template v-if="tipoTarjetaModal === 'grafico'">
                                <div class="prb-modal-grid">
                                    <div class="form-group">
                                        <label class="pv2-label" for="prbTipoGrafico">Tipo de gráfico <span class="pv2-req">*</span></label>
                                        <select id="prbTipoGrafico" class="form-control" v-model.number="formGuardar.chartTypeCatalogItemID">
                                            <option :value="0">Seleccione...</option>
                                            <option v-for="item in catalogos.chartType" :key="item.catalogItemID" :value="item.catalogItemID">{{ item.display }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="pv2-label" for="prbCampoEtiqueta">Campo etiqueta <span class="pv2-req">*</span></label>
                                        <select id="prbCampoEtiqueta" class="form-control" v-model="formGuardar.labelUid">
                                            <option value="">Seleccione...</option>
                                            <option v-for="opcion in columnasPresentacion" :key="opcion.uid" :value="opcion.uid">{{ opcion.label }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="pv2-label" for="prbCampoSerie">Campo serie (opcional)</label>
                                        <select id="prbCampoSerie" class="form-control" v-model="formGuardar.seriesUid">
                                            <option value="">— Ninguno —</option>
                                            <option v-for="opcion in columnasPresentacion" :key="opcion.uid" :value="opcion.uid">{{ opcion.label }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="pv2-label">Campos valor <span class="pv2-req">*</span> (uno o varios)</span>
                                    <div class="prb-checks">
                                        <label v-for="opcion in columnasPresentacion" :key="opcion.uid" class="prb-check">
                                            <input type="checkbox" :value="opcion.uid" v-model="formGuardar.valueUids"> {{ opcion.label }}
                                        </label>
                                    </div>
                                </div>
                            </template>

                            <!-- Indicador -->
                            <div v-if="tipoTarjetaModal === 'indicador'" class="prb-modal-grid">
                                <div class="form-group">
                                    <label class="pv2-label" for="prbCampoIndicador">Campo valor principal <span class="pv2-req">*</span></label>
                                    <select id="prbCampoIndicador" class="form-control" v-model="formGuardar.indicatorUid">
                                        <option value="">Seleccione...</option>
                                        <option v-for="opcion in columnasPresentacion" :key="opcion.uid" :value="opcion.uid">{{ opcion.label }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="prbFormatoIndicador">Formato</label>
                                    <select id="prbFormatoIndicador" class="form-control" v-model="formGuardar.indicatorFormat">
                                        <option v-for="formato in formatosIndicador" :key="formato.value" :value="formato.value">{{ formato.label }}</option>
                                    </select>
                                </div>
                            </div>
                            <span class="pv2-help">Los campos salen de las columnas de "Mostrar" en su orden actual.</span>
                        </template>
                    </div>
                    <div class="modal-footer prb-modal-footer">
                        <button type="button" class="btn btn-default" :disabled="guardando" @click="cerrarModalGuardar">Cancelar</button>
                        <button type="submit" class="btn btn-primary" :disabled="guardando"><i class="icon16 i-checkmark-4"></i> {{ guardando ? 'Guardando...' : 'Guardar' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </teleport>
</script>
