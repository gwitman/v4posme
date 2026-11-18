<div id="app" class="pv2 pv2-theme-wa pv2-has-mbar adv<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>"></div>

<?php
    // La plantilla vive aquí y se monta dentro de $(function(){...}) (ver index_script):
    // theme-genyx/js/app.js aplica Uniform a todo select presente al cargar, y eso rompe los controles de Vue.
    // Los valores se pintan con llaves de Vue o v-text; v-html solo con texto ya escapado (vista previa).
    // Nunca escribir tokens de variable con llaves dobles literales aquí: Vue los interpretaría.
?>
<script type="text/x-template" id="advTemplate">
    <form autocomplete="off" @submit.prevent>

        <!-- Encabezado -->
        <div class="pv2-card pv2-header adv-header">
            <div class="pv2-header-info">
                <div class="pv2-header-icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></div>
                <div>
                    <p class="pv2-header-title">
                        Campañas WhatsApp
                        <span class="pv2-chip">{{ provider === 'meta' ? 'Meta Cloud API' : 'Evolution API' }}</span>
                    </p>
                    <p class="pv2-header-sub">Envíe promociones y recordatorios de cobro a sus clientes</p>
                </div>
            </div>
            <div class="adv-header-kpi" aria-live="polite">
                <span class="adv-header-kpi-label">Costo estimado</span>
                <span class="adv-header-kpi-value">USD {{ fmt(costoEstimado) }}</span>
            </div>
        </div>

        <div v-if="!config.enabled" class="adv-alert is-warn" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            <span>Las campañas están deshabilitadas para esta empresa (parámetro CXC_ADVERTISING_ENABLED). Puede preparar y revisar la campaña, pero no enviarla.</span>
        </div>

        <!-- Pasos -->
        <nav class="adv-steps" aria-label="Pasos de la campaña">
            <button v-for="item in pasos" :key="item.num" type="button" class="adv-step"
                    :class="{ 'is-active': paso === item.num, 'is-done': paso > item.num }"
                    :aria-current="paso === item.num ? 'step' : null"
                    :disabled="enviando || resultado !== null" @click="irAPaso(item.num)">
                <span class="adv-step-num" aria-hidden="true"><i v-if="paso > item.num" class="bi bi-check-lg"></i><template v-else>{{ item.num }}</template></span>
                <span class="adv-step-text">
                    <span class="adv-step-title">{{ item.titulo }}</span>
                    <span class="adv-step-sub">{{ item.sub }}</span>
                </span>
            </button>
        </nav>

        <div class="adv-layout">
            <div class="adv-main">

                <!-- (1) Mensaje -->
                <div v-show="paso === 1" class="pv2-card">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-chat-text" aria-hidden="true"></i></span> Mensaje</p>

                    <div v-if="provider === 'meta'" class="adv-alert is-info">
                        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                        <span>Con Meta Cloud API se envía una plantilla aprobada (paso 3). El título y el texto libre no se usan; la imagen sí, si la plantilla tiene encabezado de imagen.</span>
                    </div>

                    <div class="form-group">
                        <label class="pv2-label" for="advCaption">Título (aparece en negrita al inicio)</label>
                        <input type="text" id="advCaption" class="form-control" maxlength="150" autocomplete="off" placeholder="Ej.: Promoción de temporada" :disabled="provider === 'meta'" v-model="caption">
                    </div>

                    <!-- Imagen -->
                    <div class="form-group">
                        <span class="pv2-label">Imagen (opcional)</span>
                        <div class="adv-image">
                            <div v-if="urlImage" class="adv-image-thumb">
                                <img :src="urlImage" alt="Imagen de la campaña">
                            </div>
                            <div class="adv-image-actions">
                                <label class="btn btn-default adv-btn-soft adv-file" :class="{ 'is-disabled': subiendoImagen }">
                                    <i class="bi bi-image" aria-hidden="true"></i> {{ subiendoImagen ? 'Subiendo...' : (urlImage ? 'Cambiar imagen' : 'Subir imagen') }}
                                    <input type="file" accept="image/png,image/jpeg,image/webp" :disabled="subiendoImagen" @change="subirImagen" aria-label="Seleccionar imagen">
                                </label>
                                <button v-if="urlImage" type="button" class="btn btn-default adv-btn-danger-soft" :disabled="subiendoImagen" @click="quitarImagen"><i class="bi bi-trash" aria-hidden="true"></i> Quitar</button>
                                <span class="pv2-help">PNG, JPG o WEBP, máximo 5 MB.</span>
                                <span v-if="imagenNombre" class="pv2-help">Archivo: {{ imagenNombre }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Editor -->
                    <div class="form-group" :class="{ 'adv-disabled': provider === 'meta' }">
                        <label class="pv2-label" for="advBody">Texto del mensaje</label>
                        <div class="adv-editor">
                            <div class="adv-toolbar" role="toolbar" aria-label="Formato del texto">
                                <button type="button" class="adv-tool" title="Negrita" aria-label="Negrita" :disabled="provider === 'meta'" @click="aplicarFormato('*')"><i class="bi bi-type-bold" aria-hidden="true"></i></button>
                                <button type="button" class="adv-tool" title="Cursiva" aria-label="Cursiva" :disabled="provider === 'meta'" @click="aplicarFormato('_')"><i class="bi bi-type-italic" aria-hidden="true"></i></button>
                                <button type="button" class="adv-tool" title="Tachado" aria-label="Tachado" :disabled="provider === 'meta'" @click="aplicarFormato('~')"><i class="bi bi-type-strikethrough" aria-hidden="true"></i></button>
                                <span class="adv-tool-sep" aria-hidden="true"></span>
                                <button type="button" class="adv-tool" title="Emojis" aria-label="Mostrar emojis" :aria-expanded="mostrarEmojis" :disabled="provider === 'meta'" @click="mostrarEmojis = !mostrarEmojis"><i class="bi bi-emoji-smile" aria-hidden="true"></i></button>
                                <span class="adv-counter" :class="{ 'is-over': body.length > 3500 }" aria-live="polite">{{ body.length }} / 4096</span>
                            </div>
                            <div v-if="mostrarEmojis" class="adv-emojis" role="group" aria-label="Emojis">
                                <button v-for="emoji in emojis" :key="emoji" type="button" class="adv-emoji" :aria-label="'Insertar ' + emoji" @click="insertarTexto(emoji)" v-text="emoji"></button>
                            </div>
                            <textarea id="advBody" ref="editor" class="form-control adv-textarea" rows="8" maxlength="4096" autocomplete="off"
                                      :placeholder="placeholderBody" :disabled="provider === 'meta'" v-model="body"></textarea>
                        </div>
                        <span class="pv2-help">Use *negrita*, _cursiva_ y ~tachado~. Toque una variable para insertarla donde está el cursor.</span>
                    </div>

                    <!-- Variables -->
                    <div class="adv-vars">
                        <p class="adv-subtitle">Variables del cliente</p>
                        <p v-if="variables.length === 0" class="adv-muted">No hay variables configuradas.</p>
                        <div class="adv-chips">
                            <button v-for="variable in variables" :key="variable.name" type="button" class="adv-chip-var"
                                    :disabled="provider === 'meta'" :title="variable.label" @click="insertarVariable(variable.name)">
                                <i class="bi bi-person-badge" aria-hidden="true"></i> <span v-text="variable.label"></span> <code v-text="token(variable.name)"></code>
                            </button>
                        </div>
                        <template v-if="columnasVariables.length > 0">
                            <p class="adv-subtitle">Columnas del Excel</p>
                            <div class="adv-chips">
                                <button v-for="columna in columnasVariables" :key="columna" type="button" class="adv-chip-var is-excel"
                                        :disabled="provider === 'meta'" @click="insertarVariable(columna)">
                                    <i class="bi bi-file-earmark-spreadsheet" aria-hidden="true"></i> <code v-text="token(columna)"></code>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- (2) Destinatarios -->
                <div v-show="paso === 2">
                    <div class="pv2-card adv-source" :class="{ 'is-on': useExcel }">
                        <div class="adv-source-head">
                            <label class="adv-switch">
                                <input type="checkbox" v-model="useExcel">
                                <span class="adv-switch-ui" aria-hidden="true"></span>
                                <span class="adv-source-title"><i class="bi bi-file-earmark-excel" aria-hidden="true"></i> Desde archivo Excel</span>
                            </label>
                            <button type="button" class="btn btn-default adv-btn-soft" :disabled="descargandoPlantilla" @click="descargarPlantilla"><i class="bi bi-download" aria-hidden="true"></i> {{ descargandoPlantilla ? 'Preparando...' : 'Descargar plantilla' }}</button>
                        </div>
                        <p class="adv-muted">Columnas: <strong>telefono</strong> (obligatoria), mensaje, imagen y cualquier otra, que podrá usar como variable. Máximo {{ entero(limiteExcel) }} filas por archivo.</p>

                        <div v-if="useExcel">
                            <label class="adv-drop" :class="{ 'is-loading': leyendoExcel }">
                                <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                                <span>{{ leyendoExcel ? 'Leyendo archivo...' : (excelArchivo ? 'Cambiar archivo' : 'Seleccione un archivo .xlsx, .xls o .csv') }}</span>
                                <input type="file" accept=".xlsx,.xls,.csv" :disabled="leyendoExcel" @change="leerExcel" aria-label="Seleccionar archivo Excel">
                            </label>

                            <div v-if="errorExcel" class="adv-alert is-error" role="alert"><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>{{ errorExcel }}</span></div>

                            <template v-if="excelRows.length > 0">
                                <div class="adv-file-info">
                                    <span><i class="bi bi-file-earmark-check" aria-hidden="true"></i> {{ excelArchivo }}</span>
                                    <span class="pv2-count">{{ entero(excelRows.length) }} filas</span>
                                    <button type="button" class="btn btn-default adv-btn-danger-soft" @click="quitarExcel"><i class="bi bi-x-lg" aria-hidden="true"></i> Quitar</button>
                                </div>
                                <div v-if="excelTruncado" class="adv-alert is-warn"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i><span>El archivo tenía {{ entero(excelTotalLeido) }} filas y el sistema acepta como máximo {{ entero(excelRows.length) }} por archivo; solo se tomaron las primeras {{ entero(excelRows.length) }}. Divida el archivo para enviar el resto.</span></div>
                                <div class="adv-chips">
                                    <span v-for="columna in excelColumnas" :key="columna" class="adv-chip-col" :class="{ 'is-key': columna === 'telefono' }" v-text="columna"></span>
                                </div>
                                <div class="pv2-table-wrap adv-excel-preview">
                                    <table class="pv2-table">
                                        <caption class="sr-only">Primeras filas del archivo</caption>
                                        <thead>
                                            <tr><th v-for="columna in excelColumnas" :key="columna" scope="col">{{ columna }}</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(fila, indice) in excelRows.slice(0, 5)" :key="indice" class="row_razon">
                                                <td v-for="columna in excelColumnas" :key="columna" :data-label="columna"><span class="adv-cell">{{ fila[columna] }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pv2-card adv-source" :class="{ 'is-on': useCustomers }">
                        <div class="adv-source-head">
                            <label class="adv-switch">
                                <input type="checkbox" v-model="useCustomers">
                                <span class="adv-switch-ui" aria-hidden="true"></span>
                                <span class="adv-source-title"><i class="bi bi-people" aria-hidden="true"></i> Clientes registrados</span>
                            </label>
                        </div>
                        <p class="adv-muted">Solo clientes con teléfono y que aceptan promociones por WhatsApp. Si un teléfono también viene en el Excel, se usa la fila del Excel.</p>

                        <div v-if="useCustomers" class="adv-filters">
                            <label class="adv-check">
                                <input type="checkbox" v-model="filters.onlyBalance"> <span>Solo con saldo pendiente</span>
                            </label>
                            <label class="adv-check">
                                <input type="checkbox" v-model="filters.onlyMora"> <span>Solo con mora</span>
                            </label>
                            <div class="form-group adv-filter-cat">
                                <label class="pv2-label" for="advCategoria">Categoría</label>
                                <select id="advCategoria" class="form-control" v-model.number="filters.categoryID">
                                    <option :value="0">Todas las categorías</option>
                                    <option v-for="categoria in categorias" :key="categoria.catalogItemID" :value="Number(categoria.catalogItemID)">{{ categoria.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Alcance y prueba -->
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-sliders" aria-hidden="true"></i></span> Alcance y prueba</p>
                        <div class="adv-grid">
                            <div class="form-group">
                                <label class="pv2-label" for="advCampaignMax">Cantidad máxima de mensajes</label>
                                <input type="number" id="advCampaignMax" class="form-control pv2-num" min="1" :max="config.maxMessage" step="1" inputmode="numeric" autocomplete="off"
                                       :placeholder="placeholderMax" v-model="campaignMax">
                                <span class="pv2-help">Si hay más destinatarios válidos, se eligen al azar.</span>
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="advTestPhone">Teléfono de prueba (opcional)</label>
                                <input type="tel" id="advTestPhone" class="form-control" maxlength="20" inputmode="tel" autocomplete="off"
                                       placeholder="50588887777" v-model.trim="testPhone">
                                <span class="pv2-help">Con código de país, ej. 50588887777. Envía 1 solo mensaje con los datos del primer destinatario.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (3) Proveedor -->
                <div v-show="paso === 3">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-send" aria-hidden="true"></i></span> Proveedor de envío</p>
                        <div class="adv-providers" role="radiogroup" aria-label="Proveedor">
                            <label class="adv-provider" :class="{ 'is-active': provider === 'evolution' }">
                                <input type="radio" name="advProvider" value="evolution" v-model="provider">
                                <span class="adv-provider-icon is-evo" aria-hidden="true"><i class="bi bi-phone"></i></span>
                                <span class="adv-provider-body">
                                    <span class="adv-provider-name">Evolution API</span>
                                    <span class="adv-provider-desc">Mensaje libre con texto, imagen y variables desde su número conectado.</span>
                                    <span class="adv-provider-cost">USD {{ fmt(config.costEvolution) }} <small>costo fijo por campaña</small></span>
                                </span>
                                <i class="bi bi-check-circle-fill adv-provider-check" aria-hidden="true"></i>
                            </label>
                            <label class="adv-provider" :class="{ 'is-active': provider === 'meta' }">
                                <input type="radio" name="advProvider" value="meta" v-model="provider">
                                <span class="adv-provider-icon is-meta" aria-hidden="true"><i class="bi bi-meta"></i></span>
                                <span class="adv-provider-body">
                                    <span class="adv-provider-name">Meta Cloud API</span>
                                    <span class="adv-provider-desc">API oficial; requiere una plantilla aprobada por Meta.</span>
                                    <span class="adv-provider-cost">USD {{ fmt(costoMetaEstimado) }} <small>{{ entero(mensajesEstimados) }} × USD {{ fmt4(config.costMeta) }}{{ useCustomers && !resumenVigente ? ' + clientes' : '' }}</small></span>
                                </span>
                                <i class="bi bi-check-circle-fill adv-provider-check" aria-hidden="true"></i>
                            </label>
                        </div>
                        <span class="pv2-help">El costo es estimado; el monto final se confirma en el resumen.</span>
                    </div>

                    <!-- Plantilla de Meta -->
                    <div v-if="provider === 'meta'" class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-card-text" aria-hidden="true"></i></span> Plantilla</p>
                            <div class="adv-segment" role="group" aria-label="Origen de la plantilla">
                                <button type="button" class="adv-segment-btn" :class="{ 'is-active': plantillaModo === 'lista' }" :aria-pressed="plantillaModo === 'lista'" @click="cambiarModoPlantilla('lista')">Aprobadas</button>
                                <button type="button" class="adv-segment-btn" :class="{ 'is-active': plantillaModo === 'manual' }" :aria-pressed="plantillaModo === 'manual'" @click="cambiarModoPlantilla('manual')">Escribir manual</button>
                            </div>
                        </div>

                        <div v-if="!config.wabaConfigured && plantillaModo === 'lista'" class="adv-alert is-warn"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i><span>No está configurado WHATSAP_META_WABA_ID: no se pueden consultar las plantillas. Escriba la plantilla manualmente.</span></div>

                        <!-- Lista de aprobadas -->
                        <template v-if="plantillaModo === 'lista'">
                            <div class="adv-row">
                                <div class="form-group adv-grow">
                                    <label class="pv2-label" for="advPlantilla">Plantilla aprobada</label>
                                    <select id="advPlantilla" class="form-control" :disabled="cargandoPlantillas || plantillas.length === 0" :value="plantillaClave" @change="seleccionarPlantilla($event.target.value)">
                                        <option value="">{{ plantillas.length === 0 ? 'Cargue las plantillas...' : 'Seleccione...' }}</option>
                                        <option v-for="(item, indice) in plantillas" :key="item.name + '|' + item.language" :value="String(indice)">{{ item.name }} ({{ item.language }}) · {{ item.category }}</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-default adv-btn-soft adv-row-btn" :disabled="cargandoPlantillas" @click="cargarPlantillas"><i class="bi bi-arrow-clockwise" aria-hidden="true"></i> {{ cargandoPlantillas ? 'Cargando...' : (plantillas.length ? 'Recargar' : 'Cargar plantillas') }}</button>
                            </div>
                            <div v-if="errorPlantillas" class="adv-alert is-error" role="alert"><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>{{ errorPlantillas }}</span></div>
                            <div v-if="plantillasCargadas && plantillas.length === 0 && !errorPlantillas" class="pv2-empty">No hay plantillas aprobadas en su cuenta de Meta.</div>
                        </template>

                        <!-- Manual -->
                        <div v-else class="adv-grid">
                            <div class="form-group">
                                <label class="pv2-label" for="advTplNombre">Nombre de la plantilla <span class="pv2-req">*</span></label>
                                <input type="text" id="advTplNombre" class="form-control" maxlength="512" autocomplete="off" placeholder="Ej.: promo_noviembre" v-model.trim="template.name">
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="advTplIdioma">Idioma <span class="pv2-req">*</span></label>
                                <input type="text" id="advTplIdioma" class="form-control" maxlength="10" autocomplete="off" placeholder="es" v-model.trim="template.language">
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="advTplHeader">Encabezado</label>
                                <select id="advTplHeader" class="form-control" v-model="template.headerType">
                                    <option value="">Sin encabezado de imagen</option>
                                    <option value="IMAGE">Imagen</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="pv2-label" for="advTplTipo">Tipo de variables</label>
                                <select id="advTplTipo" class="form-control" v-model="template.variableType" @change="regenerarMapeoManual">
                                    <option value="positional">Numeradas (1, 2, 3...)</option>
                                    <option value="named">Con nombre</option>
                                </select>
                            </div>
                            <div v-if="template.variableType === 'positional'" class="form-group">
                                <label class="pv2-label" for="advTplCantidad">Cantidad de variables</label>
                                <input type="number" id="advTplCantidad" class="form-control pv2-num" min="0" max="20" step="1" inputmode="numeric" autocomplete="off" v-model.number="manualCantidad" @change="regenerarMapeoManual">
                            </div>
                            <div v-else class="form-group">
                                <label class="pv2-label" for="advTplNombres">Nombres de variables (separados por coma)</label>
                                <input type="text" id="advTplNombres" class="form-control" autocomplete="off" placeholder="nombre, saldo" v-model="manualNombres" @change="regenerarMapeoManual">
                            </div>
                        </div>

                        <!-- Detalle de la plantilla elegida -->
                        <div v-if="template.name" class="adv-tpl-info">
                            <span class="adv-badge"><i class="bi bi-tag" aria-hidden="true"></i> {{ template.name }}</span>
                            <span class="adv-badge">{{ template.language || 'sin idioma' }}</span>
                            <span class="adv-badge">{{ template.variableType === 'named' ? 'Variables con nombre' : 'Variables numeradas' }}</span>
                            <span v-if="template.headerType" class="adv-badge is-info">Encabezado {{ template.headerType }}</span>
                        </div>

                        <div v-if="template.headerType && template.headerType !== 'IMAGE'" class="adv-alert is-warn"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i><span>La plantilla tiene encabezado {{ template.headerType }}: esta pantalla solo envía encabezados de imagen; puede fallar en Meta.</span></div>
                        <div v-if="template.headerType === 'IMAGE' && !hayImagen" class="adv-alert is-error" role="alert"><i class="bi bi-image" aria-hidden="true"></i><span>La plantilla requiere una imagen de encabezado: súbala en el paso 1 o agregue la columna imagen al Excel.</span></div>

                        <!-- Mapeo -->
                        <template v-if="template.name">
                            <p class="adv-subtitle">Valores de las variables</p>
                            <p v-if="template.mapping.length === 0" class="adv-muted">La plantilla no tiene variables.</p>
                            <div v-for="item in template.mapping" :key="item.key" class="adv-map-row">
                                <div class="adv-map-key"><code v-text="token(item.key)"></code></div>
                                <div class="adv-map-field">
                                    <label class="pv2-label" :for="'advMapS' + item.key">Origen</label>
                                    <select class="form-control" :id="'advMapS' + item.key" :value="item.source" @change="cambiarOrigenMapeo(item, $event.target.value)">
                                        <option value="variable">Variable del cliente</option>
                                        <option value="excel" :disabled="columnasVariables.length === 0">Columna del Excel</option>
                                        <option value="fixed">Texto fijo</option>
                                    </select>
                                </div>
                                <div class="adv-map-field adv-grow">
                                    <label class="pv2-label" :for="'advMapV' + item.key">Valor <span class="pv2-req">*</span></label>
                                    <select v-if="item.source === 'variable'" class="form-control" :id="'advMapV' + item.key" v-model="item.value">
                                        <option value="">Seleccione...</option>
                                        <option v-for="variable in variables" :key="variable.name" :value="variable.name">{{ variable.label }}</option>
                                    </select>
                                    <select v-else-if="item.source === 'excel'" class="form-control" :id="'advMapV' + item.key" v-model="item.value">
                                        <option value="">Seleccione...</option>
                                        <option v-for="columna in columnasVariables" :key="columna" :value="columna">{{ columna }}</option>
                                    </select>
                                    <input v-else type="text" class="form-control" :id="'advMapV' + item.key" maxlength="500" autocomplete="off" v-model="item.value">
                                </div>
                            </div>
                        </template>
                        <div v-else-if="plantillaModo === 'lista' && plantillas.length > 0" class="pv2-empty">Seleccione una plantilla para configurar sus variables.</div>
                    </div>
                </div>

                <!-- (4) Resumen y envío -->
                <div v-show="paso === 4">
                    <div v-if="previsualizando" class="pv2-card adv-loading" aria-live="polite"><span class="adv-spinner" aria-hidden="true"></span> Calculando destinatarios y mensajes...</div>
                    <div v-if="errorPreview" class="adv-alert is-error" role="alert">
                        <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                        <span>{{ errorPreview }}</span>
                        <button type="button" class="btn btn-default adv-btn-soft" @click="previsualizar">Reintentar</button>
                    </div>

                    <!-- Resultado del envío -->
                    <div v-if="resultado" class="pv2-card adv-result" role="status">
                        <div class="adv-result-icon" aria-hidden="true"><i class="bi bi-check2-circle"></i></div>
                        <p class="adv-result-title">{{ resultado.testMode ? 'Mensaje de prueba enviado a la cola' : 'Campaña enviada a la cola' }}</p>
                        <p class="adv-result-sub">Se encolaron <strong>{{ entero(resultado.totalQueued) }}</strong> mensajes<span v-if="resultado.queue"> en la cola <code>{{ resultado.queue }}</code></span>. Se enviarán de forma gradual{{ summary && summary.estimatedMinutes ? ' (aprox. ' + entero(summary.estimatedMinutes) + ' min)' : '' }}.</p>
                        <button type="button" class="btn btn-primary adv-btn-wa" @click="nuevaCampania"><i class="bi bi-plus-lg" aria-hidden="true"></i> Nueva campaña</button>
                    </div>

                    <template v-if="summary && !previsualizando && !resultado">
                        <div v-if="resumenVencido" class="adv-alert is-warn"><i class="bi bi-arrow-repeat" aria-hidden="true"></i><span>La campaña cambió después del último cálculo.</span><button type="button" class="btn btn-default adv-btn-soft" @click="previsualizar">Actualizar resumen</button></div>
                        <div v-if="summary.totalInput === 0" class="pv2-empty"><i class="bi bi-inbox" aria-hidden="true"></i>No se encontraron destinatarios con los criterios seleccionados.</div>

                        <div class="pv2-kpis">
                            <div class="pv2-kpi k-ok">
                                <div class="pv2-kpi-icon"><i class="bi bi-send-check" aria-hidden="true"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Mensajes a enviar</span><span class="pv2-kpi-value">{{ entero(summary.valid) }}</span></div>
                            </div>
                            <div class="pv2-kpi" :class="totalDescartados > 0 ? 'is-negative' : 'k-info'">
                                <div class="pv2-kpi-icon"><i class="bi bi-funnel" aria-hidden="true"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Depurados</span><span class="pv2-kpi-value">{{ entero(totalDescartados) }}</span></div>
                            </div>
                            <div class="pv2-kpi k-info">
                                <div class="pv2-kpi-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Excel / Clientes</span><span class="pv2-kpi-value">{{ entero(summary.fromExcel) }} / {{ entero(summary.fromCustomers) }}</span></div>
                            </div>
                            <div class="pv2-kpi k-purple">
                                <div class="pv2-kpi-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Costo USD</span><span class="pv2-kpi-value">{{ fmt(summary.cost) }}</span></div>
                            </div>
                        </div>

                        <div class="adv-detail-chips">
                            <span class="adv-badge">Recibidos: {{ entero(summary.totalInput) }}</span>
                            <span class="adv-badge">Sin teléfono: {{ entero(summary.empty) }}</span>
                            <span class="adv-badge">Inválidos: {{ entero(summary.invalid) }}</span>
                            <span class="adv-badge">Duplicados: {{ entero(summary.duplicate) }}</span>
                            <span class="adv-badge" :class="{ 'is-bad': summary.overLimit > 0 }">Exceden máximo: {{ entero(summary.overLimit) }}</span>
                            <span class="adv-badge">Máximo por campaña: {{ entero(summary.maxMessage) }}</span>
                            <span v-if="Number(summary.notSelected) > 0" class="adv-badge">No seleccionados: {{ entero(summary.notSelected) }}</span>
                            <span v-if="!config.allowWithoutPayment" class="adv-badge">Mensajes gratuitos: {{ entero(mensajesGratis) }}</span>
                            <span v-if="modoPrueba" class="adv-badge is-info"><i class="bi bi-phone" aria-hidden="true"></i> Modo prueba</span>
                            <span v-if="pagoDisponible && !modoPrueba" class="adv-badge is-ok"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Pago aprobado disponible</span>
                        </div>

                        <div v-if="seleccionAleatoria" class="adv-alert is-info"><i class="bi bi-shuffle" aria-hidden="true"></i><span>Seleccionados {{ entero(summary.valid) }} de {{ entero(summary.validTotal) }} válidos al azar; el envío elegirá su propia muestra aleatoria.</span></div>
                        <div v-if="modoPrueba" class="adv-alert is-warn"><i class="bi bi-phone" aria-hidden="true"></i><span>Modo prueba: 1 mensaje a +{{ telefonoPrueba }} con los datos del primer destinatario (sin costo de campaña)</span></div>
                        <div v-if="mostrarPagar" class="adv-alert is-warn">
                            <i class="bi bi-credit-card" aria-hidden="true"></i>
                            <span>Esta campaña supera {{ entero(mensajesGratis) }} mensajes gratuitos: realice el pago para enviarla. Si ya pagó, verifique el pago.</span>
                            <button type="button" class="btn btn-default adv-btn-soft" :disabled="verificandoPago || previsualizando" @click="verificarPago"><i class="bi bi-arrow-repeat" aria-hidden="true"></i> {{ verificandoPago ? 'Verificando...' : 'Verificar pago' }}</button>
                        </div>

                        <div v-if="!summary.enabled" class="adv-alert is-error" role="alert"><i class="bi bi-lock-fill" aria-hidden="true"></i><span>El envío está bloqueado: las campañas están deshabilitadas para esta empresa.</span></div>
                        <div v-if="summary.overLimit > 0" class="adv-alert is-error" role="alert"><i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i><span>La campaña supera el máximo de {{ entero(summary.maxMessage) }} mensajes. Reduzca los destinatarios (filtros o archivo) para poder enviar.</span></div>
                        <div v-if="summary.valid > 0" class="adv-alert is-info"><i class="bi bi-clock-history" aria-hidden="true"></i><span>Los mensajes se envían de forma gradual para proteger su número: tiempo estimado {{ entero(summary.estimatedMinutes) }} min.</span></div>

                        <!-- Muestra -->
                        <div v-if="sample.length > 0" class="pv2-card">
                            <div class="pv2-detail-head">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-chat-dots" aria-hidden="true"></i></span> Muestra de mensajes</p>
                                <span class="pv2-count">{{ sample.length }}</span>
                            </div>
                            <div class="adv-samples">
                                <div v-for="(item, indice) in sample" :key="indice" class="adv-sample">
                                    <div class="adv-sample-head">
                                        <span class="adv-sample-name">{{ item.nombre || 'Sin nombre' }}</span>
                                        <span class="adv-sample-phone">+{{ item.telefono }}</span>
                                        <span class="adv-badge" :class="item.origen === 'excel' ? 'is-ok' : 'is-info'">{{ item.origen === 'excel' ? 'Excel' : 'Cliente' }}</span>
                                    </div>
                                    <div class="adv-wa-chat is-mini">
                                        <div class="adv-bubble">
                                            <img v-if="item.urlImage" :src="item.urlImage" alt="" class="adv-bubble-img" loading="lazy">
                                            <div class="adv-bubble-text" v-html="formatearWhatsapp(item.mensaje || '')"></div>
                                            <span class="adv-bubble-time">{{ horaActual }} <i class="bi bi-check2-all" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Depurados -->
                        <div class="pv2-card">
                            <div class="pv2-detail-head">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="bi bi-funnel" aria-hidden="true"></i></span> Registros depurados</p>
                                <span class="pv2-count">{{ entero(discardedFiltrados.length) }}</span>
                            </div>
                            <div v-if="discarded.length === 0" class="pv2-empty"><i class="bi bi-emoji-smile" aria-hidden="true"></i>No se depuró ningún registro.</div>
                            <template v-else>
                                <div class="form-group adv-filter-motivo">
                                    <label class="pv2-label" for="advMotivo">Filtrar por motivo</label>
                                    <select id="advMotivo" class="form-control" v-model="filtroMotivo">
                                        <option value="">Todos los motivos</option>
                                        <option v-for="motivo in motivos" :key="motivo" :value="motivo">{{ motivo }}</option>
                                    </select>
                                </div>
                                <div class="pv2-table-wrap">
                                    <table class="pv2-table">
                                        <caption class="sr-only">Registros depurados</caption>
                                        <thead>
                                            <tr><th scope="col">Teléfono</th><th scope="col">Nombre</th><th scope="col">Origen</th><th scope="col">Motivo</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, indice) in discardedPagina" :key="indice" class="row_razon">
                                                <td data-label="Teléfono"><span>{{ item.telefono || '(vacío)' }}</span></td>
                                                <td data-label="Nombre"><span>{{ item.nombre || '-' }}</span></td>
                                                <td data-label="Origen"><span>{{ item.origen === 'excel' ? 'Excel' : 'Cliente' }}</span></td>
                                                <td data-label="Motivo"><span class="adv-badge is-bad">{{ item.motivo }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="paginasDescartados > 1" class="adv-pager">
                                    <button type="button" class="adv-icon-btn" aria-label="Página anterior" title="Anterior" :disabled="paginaDescartados <= 1" @click="paginaDescartados--"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
                                    <span>Página {{ paginaDescartados }} de {{ paginasDescartados }}</span>
                                    <button type="button" class="adv-icon-btn" aria-label="Página siguiente" title="Siguiente" :disabled="paginaDescartados >= paginasDescartados" @click="paginaDescartados++"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
                                </div>
                                <span v-if="discarded.length >= 1000" class="pv2-help">Se muestran como máximo 1,000 registros depurados.</span>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Errores del paso -->
                <div v-if="erroresPaso.length > 0" id="advErrores" class="adv-alert is-error" role="alert">
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                    <ul><li v-for="error in erroresPaso" :key="error">{{ error }}</li></ul>
                </div>

                <!-- Navegación -->
                <div v-if="!resultado" class="adv-nav">
                    <button type="button" class="btn btn-default adv-btn-soft" :disabled="paso === 1 || enviando || pagando" @click="anterior"><i class="bi bi-arrow-left" aria-hidden="true"></i> Anterior</button>
                    <button v-if="paso < 4" type="button" class="btn btn-primary adv-btn-wa" @click="siguiente">Siguiente <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                    <div v-else class="adv-nav-actions">
                        <button v-if="mostrarPagar" type="button" class="btn btn-primary adv-btn-pay" :disabled="!puedePagar" @click="pagar">
                            <i class="bi bi-credit-card" aria-hidden="true"></i> {{ pagando ? 'Redirigiendo...' : 'Pagar USD ' + fmt(summary ? summary.cost : 0) }}
                        </button>
                        <button type="button" class="btn adv-btn-wa" :class="mostrarPagar ? 'btn-default' : 'btn-primary adv-btn-send'" :disabled="!puedeEnviar" :title="motivoBloqueo" @click="enviar">
                            <i class="bi bi-whatsapp" aria-hidden="true"></i> {{ enviando ? 'Enviando...' : (modoPrueba ? 'Enviar prueba' : 'Enviar campaña') }}
                        </button>
                    </div>
                </div>
                <p v-if="paso === 4 && !resultado && motivoBloqueo && !previsualizando" class="adv-block-reason">{{ motivoBloqueo }}</p>
            </div>

            <!-- Vista previa estilo WhatsApp -->
            <aside class="adv-aside" aria-label="Vista previa del mensaje">
                <div class="adv-phone">
                    <div class="adv-phone-bar">
                        <span class="adv-phone-avatar" aria-hidden="true"><i class="bi bi-shop"></i></span>
                        <span class="adv-phone-title">
                            <span class="adv-phone-name">{{ empresa }}</span>
                            <span class="adv-phone-status">Vista previa</span>
                        </span>
                    </div>
                    <div class="adv-wa-chat">
                        <div v-if="!textoVistaPrevia && !imagenVistaPrevia" class="adv-wa-empty">Escriba su mensaje para ver cómo lo recibirá el cliente.</div>
                        <div v-else class="adv-bubble">
                            <img v-if="imagenVistaPrevia" :src="imagenVistaPrevia" alt="Imagen del mensaje" class="adv-bubble-img">
                            <div v-if="textoVistaPrevia" class="adv-bubble-text" v-html="formatearWhatsapp(textoVistaPrevia)"></div>
                            <span class="adv-bubble-time">{{ horaActual }} <i class="bi bi-check2-all" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <p class="adv-aside-note"><i class="bi bi-info-circle" aria-hidden="true"></i> {{ notaVistaPrevia }}</p>
            </aside>
        </div>

        <!-- Barra inferior del teléfono -->
        <div v-if="!resultado" class="pv2-mbar">
            <div class="pv2-mbar-info">
                <p class="pv2-mbar-label">Paso {{ paso }} de 4 · {{ pasos[paso - 1].titulo }}</p>
                <p class="pv2-mbar-label">USD {{ fmt(costoEstimado) }}</p>
            </div>
            <button v-if="paso < 4" type="button" class="btn btn-primary adv-btn-wa" @click="siguiente">Siguiente <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
            <button v-else-if="mostrarPagar" type="button" class="btn btn-primary adv-btn-pay" :disabled="!puedePagar" @click="pagar"><i class="bi bi-credit-card" aria-hidden="true"></i> {{ pagando ? 'Redirigiendo...' : 'Pagar' }}</button>
            <button v-else type="button" class="btn btn-primary adv-btn-wa" :disabled="!puedeEnviar" @click="enviar"><i class="bi bi-whatsapp" aria-hidden="true"></i> {{ enviando ? 'Enviando...' : (modoPrueba ? 'Enviar prueba' : 'Enviar') }}</button>
        </div>
    </form>
</script>
