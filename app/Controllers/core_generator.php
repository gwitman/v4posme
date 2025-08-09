<?php

namespace App\Controllers;

class core_generator extends _BaseController
{
    // Definir el orden deseado de los métodos
    protected $orderedMethods = [
        'add',
        'edit',
        'delete',
        'updateElement',
        'insertElement',
        'save',
        'index',
        'searchTransactionMaster',
        'viewRegisterFormatoPaginaTicket'
    ];

    public function create()
    {
        //AUTENTICADO
        if (!$this->core_web_authentication->isAuthenticated())
            throw new \Exception(USER_NOT_AUTENTICATED);
        $dataSession = $this->session->get();

        //PERMISO SOBRE LA FUNCTION
        if (APP_NEED_AUTHENTICATION == true) {
/*            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

            if (!$permited)
                throw new \Exception(NOT_ACCESS_CONTROL);*/

            $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if ($resultPermission == PERMISSION_NONE)
                throw new \Exception(NOT_ALL_INSERT);

        }

        $dataView['objComponents'] = $this->Component_Model->get_findAll();

        //Renderizar Resultado
        $dataSession["notification"]= $this->core_web_error->get_error($dataSession["user"]->userID);
        $dataSession["message"] 	= $this->core_web_notification->get_message();
        $dataSession["head"] 		= /*--inicio view*/ view('core_generator/news_head', $dataView);//--finview
        $dataSession["body"] 		= /*--inicio view*/ view('core_generator/news_body', $dataView);//--finview
        $dataSession["script"] 		= /*--inicio view*/ view('core_generator/news_script', $dataView);//--finview
        $dataSession["footer"] 		= "";
        return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
    }

    public function save()
    {
        // Obtener datos del formulario
        $nombreController   = $this->request->getPost("txtControllerName");
        $componentID        = $this->request->getPost("txtComponentName");
        $tituloFormulario   = $this->request->getPost("txtTitle");

        // Validación básica
        if (!$nombreController) {
            return $this->response->setStatusCode(400)->setBody("Debes proporcionar un nombre para el controlador.");
        }
        if (!$tituloFormulario) {
            return $this->response->setStatusCode(400)->setBody("Debes proporcionar un nombre para el formulario.");
        }

        // Obtener información del componente
        $component     = $this->Component_Model->get_byPk($componentID);
        if ($component == null) {
            $this->core_web_notification->set_message("Componente no encontrado", "error");
            return redirect()->back()->withInput();
        }

        $nombreComponente = $component->name;
        $nombreController = $this->sanitizeControllerName($nombreController);
        if (!$nombreController) {
            $this->core_web_notification->set_message("Nombre de controlador no válido", "error");
            return redirect()->back()->withInput();
        }

        // Rutas de archivos
        $controllerPath     = APPPATH . 'Controllers/' . strtolower($nombreController) . '.php';
        $viewsDir           = APPPATH . 'Views/' . strtolower($nombreController);
        $generadorDir       = PATH_FILE_OF_APP_ROOT . '/file_template';

        try {
            // Crear estructura básica
            $this->createViewsDirectory($viewsDir);
            $this->createBaseController($controllerPath, $nombreController);

            // Procesar métodos y vistas
            $this->processControllerMethods($controllerPath, $nombreController, $nombreComponente, $generadorDir);
            $this->processViews($nombreController, $tituloFormulario, $generadorDir, $viewsDir);

            // Generar mensaje con rutas
            $rutasGeneradas = [
                "{$nombreController}/index",
                "{$nombreController}/add",
                "{$nombreController}/delete",
                "{$nombreController}/delete/(:any)/(:any)",
                "{$nombreController}/save/(:any)",
                "{$nombreController}/edit/(:any)/(:any)",
                "{$nombreController}/viewRegisterFormatoPaginaTicket"
            ];

            // Preparar mensaje de éxito con rutas
            $mensajeExito = "Controlador generado exitosamente. ";

            // Guardar rutas en sesión para mostrarlas en la vista
            session()->setFlashdata('generated_routes', $rutasGeneradas);
            $this->core_web_notification->set_message(false, $mensajeExito);

            return redirect()->to(base_url('core_generator/create'));

        } catch (\Exception $e) {
            $this->core_web_notification->set_message(true,"Error al generar controlador: " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Sanitiza y valida el nombre del controlador
     */
    protected function sanitizeControllerName(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
        return strtolower($name);
    }

    /**
     * Crea el directorio de vistas si no existe
     */
    protected function createViewsDirectory(string $viewsDir): bool
    {
        if (!is_dir($viewsDir)) {
            return mkdir($viewsDir, 0755, true);
    }
        return true;
    }

    /**
     * Crea el controlador base si no existe
     */
    protected function createBaseController(string $path, string $name): bool
    {
        if (!file_exists($path)) {
            $template = "<?php\n\nnamespace App\Controllers;\n\nclass {$name} extends _BaseController\n{\n    // Métodos se agregarán aquí\n}\n";
            return file_put_contents($path, $template) !== false;
        }
        return true;
    }

    /**
     * Procesa y agrega métodos al controlador en el orden especificado
     */
    protected function processControllerMethods(string $controllerPath, string $name, string $nombreComponente, string $generatorDir): void
    {
        $controllerCode = file_get_contents($controllerPath);
        $methodFiles = glob($generatorDir.'/file_new_controller' . '/*.php');

        // Procesar primero los métodos en el orden definido
        foreach ($this->orderedMethods as $methodName) {
            $file = $generatorDir.'/file_new_controller' . '/' . $methodName . '.php';

            if (!file_exists($file)) {
                continue; // Saltar si no existe el archivo para este método
            }

            if (strpos($controllerCode, "function {$methodName}(") !== false) {
                continue; // Ya existe el método
            }

            $methodCode = $this->prepareMethodCode($file, $name, $nombreComponente);
            $controllerCode = $this->insertMethodIntoController($controllerCode, $methodCode);
        }

        // Procesar los métodos restantes que no están en la lista ordenada
        foreach ($methodFiles as $file) {
            $methodName = basename($file, '.php');

            // Si ya está en nuestra lista ordenada, lo saltamos (ya lo procesamos)
            if (in_array($methodName, $this->orderedMethods)) {
                continue;
            }

            if (strpos($controllerCode, "function {$methodName}(") !== false) {
                continue; // Ya existe el método
            }

            $methodCode = $this->prepareMethodCode($file, $name, $nombreComponente);
            $controllerCode = $this->insertMethodIntoController($controllerCode, $methodCode);
        }

        file_put_contents($controllerPath, $controllerCode);
    }
    /**
     * Prepara el código del método para inserción
     */
    protected function prepareMethodCode(string $file, string $name, string $nameComponent): string
    {
        if (!file_exists($file)) {
            throw new \RuntimeException("El archivo de método no existe: {$file}");
        }

        $methodCode = file_get_contents($file);
        $methodName = basename($file, '.php');

        // Validar y normalizar nombres
        $normalizedName         = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $name));
        $normalizedComponent    = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $nameComponent));

        // Reemplazos múltiples
        $replacements = [
            'nombre_controlador'    => $normalizedName,
            'nombre_componente'     => $normalizedComponent,
            // Puedes añadir más reemplazos si los necesitas
        ];

        foreach ($replacements as $search => $replace) {
            $methodCode = str_replace($search, $replace, $methodCode);
        }

        // Manejo de firma personalizada
        preg_match('/\/\/\s*@signature\s+(.+)/', $methodCode, $match);
        $methodSignature = $match[1] ?? "{$methodName}()";

        // Limpiar comentario de firma
        $methodCode     = preg_replace('/\/\/\s*@signature\s+.*\n/', '', $methodCode);

        return "\n    public function {$methodSignature}\n    {\n" .
            $this->indentCode($methodCode, 2) . "\n    }\n";
    }

    /**
     * Inserta un método en el controlador
     */
    protected function insertMethodIntoController(string $controllerCode, string $methodCode): string
    {
        return preg_replace('/}\s*$/', $methodCode . "}\n", $controllerCode);
    }

    /**
     * Procesa y genera las vistas
     */
    protected function processViews(string $name, string $tituloFormulario, string $generatorDir, string $viewsDir): void
    {
        $viewsFolder = $generatorDir.'/file_new_view';
        $viewFiles = glob($viewsFolder . '/*.php');

        foreach ($viewFiles as $viewFile) {
            $viewName = basename($viewFile, '.php');
            $viewContent = file_get_contents($viewFile);

            // Reemplazos con formato {variable}
            $replacements = [
                '{nombre_controlador}' => strtolower($name),
                '{titulo_formulario}' => $tituloFormulario,
                '{fecha_actual}' => date('Y-m-d') // Ejemplo de otro placeholder útil
            ];

            foreach ($replacements as $search => $replace) {
                $viewContent = str_replace($search, $replace, $viewContent);
            }

            $destPath = "{$viewsDir}/{$viewName}.php";
            file_put_contents($destPath, $viewContent);
        }
    }

    /**
     * Indenta el código correctamente
     */
    protected function indentCode(string $code, int $level = 1): string
    {
        $indent = str_repeat('    ', $level);
        return $indent . str_replace("\n", "\n{$indent}", trim($code));
    }

    protected function generarMensajeRutas(string $nombreControlador): string
    {
        $rutasComunes = [
            "match(['get', 'post'], '{$nombreControlador}/index', '{$nombreControlador}::index')",
            "match(['get', 'post'], '{$nombreControlador}/add', '{$nombreControlador}::add')",
            "match(['get', 'post'], '{$nombreControlador}/delete', '{$nombreControlador}::delete')",
            "match(['get', 'post'], '{$nombreControlador}/delete/(:any)/(:any)', '{$nombreControlador}::delete/$1/$2')",
            "match(['get', 'post'], '{$nombreControlador}/save/(:any)', '{$nombreControlador}::save/$1')",
            "match(['get', 'post'], '{$nombreControlador}/edit/(:any)/(:any)', '{$nombreControlador}::edit/$1/$2')",
            "match(['get', 'post'], '{$nombreControlador}/viewRegisterFormatoPaginaTicket', '{$nombreControlador}::viewRegisterFormatoPaginaTicket')"
        ];

        $mensaje    = "Agrega estas rutas en tu archivo app/Config/Routes.php:\n\n";
        $mensaje    .= "\$routes->group('', ['namespace' => 'App\Controllers'], function(\$routes) use (\$nombreControlador) {\n";

        foreach ($rutasComunes as $ruta) {
            $mensaje .= "    \$routes->{$ruta};\n";
        }

        $mensaje .= "});\n\n";
        $mensaje .= "O copia y pega esto directamente:\n\n";
        $mensaje .= implode("\n", array_map(function($ruta) {
            return "\$routes->{$ruta};";
        }, $rutasComunes));

        return $mensaje;
    }
}