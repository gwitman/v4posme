<div class="container">
    <div class="row">
        <form class="form-horizontal" id="form-generator" role="form">
            <div class="form-group">
                <label class="col-lg-3 control-label" for="txtControllerName">Nombre del controlador</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="txtControllerName" id="txtControllerName" placeholder="Nombre del controlador">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="txtTitle">Titulo del formulario</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="txtTitle" id="txtTitle" placeholder="Titulo del formulario">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="txtComponentName">Componente</label>
                <div class="col-lg-9">
                    <select name="txtComponentName" id="txtComponentName" class="select2">
                        <option></option>
                        <?php
                        if (isset($objComponents))
                            foreach ($objComponents as $k=>$ws) {
                                if ($k==0) echo "<option value='" . $ws->componentID . "' selected>" . $ws->name . "</option>";
                                echo "<option value='" . $ws->componentID . "'>" . $ws->name . "</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-success btn-block">Generar</button>
                </div>
            </div>
        </form>
        <?php if (session('generated_routes')): ?>
            <div class="alert alert-success mt-3">
                <h5>Rutas generadas:</h5>
                <ul class="mb-0">
                    <?php foreach (session('generated_routes') as $ruta): ?>
                        <li><code><?= $ruta ?></code></li>
                    <?php endforeach ?>
                </ul>
                <button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyRoutes()">
                    Copiar rutas
                </button>
            </div>

            <script>
                function copyRoutes() {
                    const routes = <?= json_encode(session('generated_routes')) ?>;
                    const textToCopy = routes.map(r => {
                        // Extraer nombre del controlador (primera parte)
                        const controllerName = r.split('/')[0];

                        // Extraer nombre del método (segunda parte)
                        const methodParts = r.split('/').slice(1);
                        // Manejar parámetros
                        let handler = methodParts[0] || 'index';
                        let paramsCount = 0;

                        // Contar parámetros en la ruta
                        const paramMatches = r.match(/\(:any\)/g);
                        if (paramMatches) {
                            paramsCount = paramMatches.length;
                            // Construir parte del handler con parámetros ($1, $2...)
                            handler += '/' + Array.from({length: paramsCount}, (_, i) => `$${i+1}`).join('/');
                        }

                        return `$routes->match(['get','post'], '${r}', '${controllerName}::${handler}');`;
                    }).join('\n');

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        //alert('Rutas copiadas al portapapeles');
                        Swal.fire({
                            title: 'Copiar!',
                            text: 'Rutas copiadas al portapapeles',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        })
                    });
                }
            </script>
        <?php endif ?>
    </div>
</div>