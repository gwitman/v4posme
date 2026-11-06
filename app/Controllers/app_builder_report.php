<?php
//posme:2026-11-06
namespace App\Controllers;

use App\Libraries\core_web_ai_tools;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Constructor de Reportes (Builder Report).
 *
 * El navegador solo envia una configuracion; el SQL lo genera core_web_report_builder sobre las vistas
 * curadas de callerID = 3. companyID, userID, branchID y flavor salen SOLO de la sesion.
 *
 * Los mensajes de \Exception simple son propios (validacion, permisos, configuracion) y llegan al
 * usuario. Cualquier otro error, incluido DatabaseException que extiende \Error, se registra en el log
 * y el usuario recibe un mensaje generico sin SQL, rutas ni nombres de tabla.
 */
class app_builder_report extends _BaseController
{
    const MESSAGE_VIEW_NOT_FOUND  = 'La vista solicitada no existe o no esta disponible.';
    const MESSAGE_QUERY_NOT_FOUND = 'La consulta solicitada no existe o no esta disponible.';
    const MESSAGE_GENERIC_ERROR   = 'No se pudo completar la operacion. Intente de nuevo o contacte al administrador.';
    const EXCEL_CONTENT_TYPE      = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    /**
     * Pantalla principal.
     *
     * @return mixed Vista del masterpage.
     */
    function index()
    {
        try {
            $access      = $this->authorize('index', false);
            $dataSession = $access['session'];

            $dataView["company"]            = $dataSession["company"];
            $dataView["companyPageSetting"] = $dataSession["companyPageSetting"];
            $dataView["title"]              = "Constructor de Reportes";
            $dataView["useMobile"]          = $dataSession["user"]->useMobile;

            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = "";
            $dataSession["head"]         = /*--inicio view*/ view('app_builder_report/index_head', $dataView);//--finview
            $dataSession["footer"]       = "";
            $dataSession["script"]       = /*--inicio view*/ view('app_builder_report/index_script', $dataView);//--finview
            $dataSession["body"]         = /*--inicio view*/ view('app_builder_report/index_body', $dataView);//--finview

            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Throwable $ex) {
            $session = $this->session->get();
            if (empty($session["user"])) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]   = $session;
            $data["exception"] = new \Exception($this->resolveClientMessage($ex));
            $data["urlLogin"]  = base_url();
            $data["urlIndex"]  = base_url() . "/app_builder_report/index";
            $data["urlBack"]   = base_url() . "/app_builder_report/index";

            return view("core_template/email_error_general", $data);
        }
    }

    /**
     * Vistas del catalogo a las que el usuario tiene acceso.
     *
     * @return mixed JSON con views.
     */
    function getViews()
    {
        try {
            $access      = $this->authorize('index');
            $views       = [];

            foreach ($this->getAvailableViews($access['session']) as $row) {
                $views[] = [
                    'dataViewID'  => (int) $row->dataViewID,
                    'label'       => $row->label,
                    'description' => $row->description,
                ];
            }

            return $this->successResponse(['views' => $views]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Campos, operadores y funciones de una vista.
     *
     * @return mixed JSON con columns, operators, functions y pageSizeMax.
     */
    function getColumns()
    {
        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            $view        = $this->requireView($dataSession, $body['dataViewID'] ?? null);
            $fields      = $this->getCatalogFields($dataSession, $view->dataViewID);
            $limits      = $this->readLimits($dataSession["user"]->companyID);

            return $this->successResponse([
                'columns'     => $this->core_web_report_builder->getCatalogColumns($fields),
                'operators'   => $this->core_web_report_builder->getOperators(),
                'functions'   => $this->core_web_report_builder->getFunctions(),
                'pageSizeMax' => $limits['pageSizeMax'],
            ]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Genera una pagina del resultado.
     *
     * @return mixed JSON con columns, rows, page, pageSize, hasMore, total, totalIsCapped, maxRows y pageSizeMax.
     */
    function generate()
    {
        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];
            $companyID   = (int) $dataSession["user"]->companyID;
            $body        = $this->readBody();
            $limits      = $this->readLimits($companyID);
            $context     = $this->buildQueryContext($dataSession, $access['permission'], $body['config'] ?? null);

            $maxRows  = $limits['maxRowsScreen'];
            $page     = array_key_exists('page', $body) ? $this->toPositiveInt($body['page']) : 1;
            $pageSize = array_key_exists('pageSize', $body) ? $this->toPositiveInt($body['pageSize']) : $limits['pageSizeMax'];

            if ($page < 1) {
                throw new \Exception('La pagina solicitada no es valida.');
            }
            if ($pageSize < 1 || $pageSize > $limits['pageSizeMax']) {
                throw new \Exception('El tamano de pagina debe estar entre 1 y ' . $limits['pageSizeMax'] . '.');
            }
            // Equivale a offset + pageSize <= maxRows, sin riesgo de desbordar con una pagina enorme
            if ($page > intdiv($maxRows, $pageSize)) {
                throw new \Exception('Solo se pueden navegar ' . number_format($maxRows) . ' filas. Agregue filtros o exporte a Excel.');
            }

            $offset  = ($page - 1) * $pageSize;
            $builder = $this->core_web_report_builder;
            $db      = db_connect();
            $this->extendTimeLimit($limits['timeoutSeconds'] * 3);

            // Se pide una fila de mas para saber si hay otra pagina
            $select = $builder->buildSelect($context['baseSql'], $context['config'], $companyID, $pageSize + 1, $offset);
            $rows   = $builder->execute($db, $select['sql'], $select['binds'], $limits['timeoutSeconds']);

            $count     = $builder->buildCount($context['baseSql'], $context['config'], $companyID, $maxRows);
            $countRows = $builder->execute($db, $count['sql'], $count['binds'], $limits['timeoutSeconds']);
            $total     = isset($countRows[0]['total']) ? (int) $countRows[0]['total'] : 0;

            return $this->successResponse([
                'columns'       => $select['columns'],
                'rows'          => array_slice($rows, 0, $pageSize),
                'page'          => $page,
                'pageSize'      => $pageSize,
                'hasMore'       => count($rows) > $pageSize && ($offset + $pageSize) < $maxRows,
                'total'         => min($total, $maxRows),
                'totalIsCapped' => $total > $maxRows,
                'maxRows'       => $maxRows,
                'pageSizeMax'   => $limits['pageSizeMax'],
            ]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Crea (sin ID) o actualiza (con ID) una consulta recurrente del usuario. Conserva los filtros sin
     * valor: el valor se indica cada vez que se usa la consulta.
     *
     * @return mixed JSON con reportBuilderID.
     */
    function saveQuery()
    {
        try {
            $body            = $this->readBody();
            $rawID           = $body['reportBuilderID'] ?? null;
            $isEdit          = !($rawID === null || $rawID === '' || $rawID === 0 || $rawID === '0');
            $access          = $this->authorize($isEdit ? 'edit' : 'add');
            $dataSession     = $access['session'];
            $companyID       = (int) $dataSession["user"]->companyID;
            $userID          = (int) $dataSession["user"]->userID;
            $reportBuilderID = 0;

            if ($isEdit) {
                $reportBuilderID = $this->toPositiveInt($rawID);
                // update_app_posme devuelve true aunque ninguna fila coincida
                if ($reportBuilderID < 1 || !$this->Report_Builder_Model->get_rowByPK($companyID, $userID, $reportBuilderID)) {
                    throw new \Exception(self::MESSAGE_QUERY_NOT_FOUND);
                }
            }

            $name   = $this->core_web_report_builder->normalizeQueryName($body['name'] ?? null);
            $config = $this->requireConfigArray($body['config'] ?? null);
            $view   = $this->requireView($dataSession, $config['dataViewID'] ?? null);
            $fields = $this->getCatalogFields($dataSession, $view->dataViewID);
            $normal = $this->core_web_report_builder->validateConfig($config, $fields, ['keepEmptyFilters' => true]);

            if ($this->Report_Builder_Model->existsName($companyID, $userID, $name, $reportBuilderID, $this->getAvailableViewIDs($dataSession))) {
                throw new \Exception('Ya tiene una consulta con ese nombre.');
            }

            $configJson = $this->encodeConfig($normal);

            if ($isEdit) {
                $this->Report_Builder_Model->update_app_posme($companyID, $userID, $reportBuilderID, [
                    'name'       => $name,
                    'dataViewID' => (int) $view->dataViewID,
                    'configJson' => $configJson,
                    'modifiedOn' => helper_getDateTime(),
                ]);
            } else {
                $reportBuilderID = $this->insertQuery($dataSession, (int) $view->dataViewID, $name, $configJson);
            }

            return $this->successResponse(['reportBuilderID' => (int) $reportBuilderID]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Consultas recurrentes del usuario. Se ocultan las de vistas no disponibles para el flavor de la
     * empresa o el menu del usuario, porque no se podrian abrir.
     *
     * @return mixed JSON con queries.
     */
    function listQueries()
    {
        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];
            $queries     = [];
            $rows        = $this->Report_Builder_Model->get_rowsByUser($dataSession["user"]->companyID, $dataSession["user"]->userID);
            $viewIDs     = $this->getAvailableViewIDs($dataSession);

            foreach ($rows as $row) {
                if (!in_array((int) $row->dataViewID, $viewIDs, true)) {
                    continue;
                }

                $queries[] = [
                    'reportBuilderID' => (int) $row->reportBuilderID,
                    'name'            => $row->name,
                    'dataViewID'      => (int) $row->dataViewID,
                    'modifiedOn'      => $row->modifiedOn,
                ];
            }

            return $this->successResponse(['queries' => $queries]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Abre una consulta recurrente. Una consulta ajena o inexistente da la misma respuesta, y los campos
     * que ya no estan en el catalogo se quitan y se informan en warnings.
     *
     * @return mixed JSON con query y warnings.
     */
    function loadQuery()
    {
        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            $row         = $this->requireOwnQuery($dataSession, $body['reportBuilderID'] ?? null);
            $view        = $this->requireVisibleQueryView($dataSession, $row);
            $fields      = $this->getCatalogFields($dataSession, $view->dataViewID);

            $config = json_decode((string) $row->configJson, true);
            if (!is_array($config)) {
                $config = [];
            }
            $config['dataViewID'] = (int) $row->dataViewID;

            $result = $this->core_web_report_builder->reconcileConfig($config, $fields);

            return $this->successResponse([
                'query'    => [
                    'reportBuilderID' => (int) $row->reportBuilderID,
                    'name'            => $row->name,
                    'dataViewID'      => (int) $row->dataViewID,
                    'config'          => $result['config'],
                ],
                'warnings' => $result['warnings'],
            ]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Duplica una consulta del usuario.
     *
     * @return mixed JSON con reportBuilderID.
     */
    function duplicateQuery()
    {
        try {
            $access      = $this->authorize('add');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            $row         = $this->requireOwnQuery($dataSession, $body['reportBuilderID'] ?? null);
            $view        = $this->requireVisibleQueryView($dataSession, $row);
            $rawName     = $body['name'] ?? null;

            if (is_string($rawName) && trim($rawName) !== '') {
                $name = $this->core_web_report_builder->normalizeQueryName($rawName);
                if ($this->Report_Builder_Model->existsName($dataSession["user"]->companyID, $dataSession["user"]->userID, $name, 0, $this->getAvailableViewIDs($dataSession))) {
                    throw new \Exception('Ya tiene una consulta con ese nombre.');
                }
            } else {
                $name = $this->buildCopyName($dataSession, (string) $row->name);
            }

            $reportBuilderID = $this->insertQuery($dataSession, (int) $view->dataViewID, $name, (string) $row->configJson);

            return $this->successResponse(['reportBuilderID' => (int) $reportBuilderID]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Elimina (soft delete) una consulta del usuario.
     *
     * @return mixed JSON estandar.
     */
    function deleteQuery()
    {
        try {
            $access      = $this->authorize('delete');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            // delete_app_posme devuelve true aunque ninguna fila coincida
            $row = $this->requireOwnQuery($dataSession, $body['reportBuilderID'] ?? null);

            $this->Report_Builder_Model->delete_app_posme($dataSession["user"]->companyID, $dataSession["user"]->userID, $row->reportBuilderID);

            return $this->successResponse([]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Exporta el resultado a .xlsx usando el mismo generador y validacion de generate.
     * El archivo se lee a memoria para poder borrar el temporal antes de responder: DownloadResponse con
     * ruta lo leeria despues del finally. CodeIgniter::gatherOutput limpia los buffers de salida al
     * recibir un DownloadResponse, y el Toolbar no lo modifica.
     *
     * @return mixed Archivo .xlsx, o JSON con el error.
     */
    function exportExcel()
    {
        $tempPath = null;
        $writer   = null;

        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];
            $companyID   = (int) $dataSession["user"]->companyID;
            $body        = $this->readBody();
            $limits      = $this->readLimits($companyID);
            $context     = $this->buildQueryContext($dataSession, $access['permission'], $body['config'] ?? null);
            $builder     = $this->core_web_report_builder;
            $db          = db_connect();
            $maxRows     = $limits['maxRowsExport'];
            $batchSize   = $builder::EXPORT_BATCH_SIZE;

            // Cada lote tiene su propio limite de tiempo en la BD; este tope cubre la exportacion completa
            $this->extendTimeLimit($limits['timeoutSeconds'] * (int) ceil(($maxRows + 1) / $batchSize));

            $tempPath = tempnam(sys_get_temp_dir(), 'prb');
            if ($tempPath === false) {
                throw new \RuntimeException('No se pudo crear el archivo temporal.');
            }

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToFile($tempPath);
            $writer->getCurrentSheet()->setName($builder->sanitizeSheetName($context['view']->label));

            // Encabezados: se escriben aunque no haya filas
            $styles  = [];
            $columns = $builder->buildSelect($context['baseSql'], $context['config'], $companyID, 1, 0)['columns'];
            $header  = [];
            foreach ($columns as $column) {
                $header[] = $this->createExcelCell($builder->buildExcelHeaderCell($column['label'], $column['align']), true, $styles);
            }
            $writer->addRow(WriterEntityFactory::createRow($header));

            // Se pide el limite + 1 para detectar si el resultado se trunca
            $written   = 0;
            $offset    = 0;
            $truncated = false;

            while (true) {
                $want = min($batchSize, ($maxRows + 1) - $offset);
                if ($want <= 0) {
                    break;
                }

                $select = $builder->buildSelect($context['baseSql'], $context['config'], $companyID, $want, $offset);
                $rows   = $builder->execute($db, $select['sql'], $select['binds'], $limits['timeoutSeconds']);

                foreach ($rows as $row) {
                    if ($written >= $maxRows) {
                        $truncated = true;
                        break 2;
                    }
                    $cells = [];
                    foreach ($columns as $column) {
                        $cells[] = $this->createExcelCell($builder->buildExcelCell($row[$column['key']] ?? null, $column), false, $styles);
                    }
                    $writer->addRow(WriterEntityFactory::createRow($cells));
                    $written++;
                }

                if (count($rows) < $want) {
                    break;
                }
                $offset += count($rows);
            }

            if ($truncated) {
                $note = $builder->buildExcelHeaderCell('Resultado limitado a ' . number_format($maxRows) . ' filas', 'left');
                $writer->addRow(WriterEntityFactory::createRow([$this->createExcelCell($note, true, $styles)]));
            }

            $writer->close();
            $writer = null;

            $binary = file_get_contents($tempPath);
            if ($binary === false || $binary === '') {
                throw new \RuntimeException('No se pudo leer el archivo generado.');
            }

            $response = $this->response->download($builder->sanitizeFileName($context['view']->label), $binary);
            $response->setHeader('Content-Type', self::EXCEL_CONTENT_TYPE);
            if ($truncated) {
                $response->setHeader('X-Report-Truncated', '1');
            }

            return $response;
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        } finally {
            if ($writer !== null) {
                try {
                    // Cierra para que OpenSpout borre su carpeta temporal
                    $writer->close();
                } catch (\Throwable $ex) {
                    log_message('error', 'app_builder_report: no se cerro el archivo de exportacion: ' . $ex->getMessage());
                }
            }
            if (is_string($tempPath) && is_file($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    /* =================================================================================
       AUXILIARES
    ================================================================================= */

    /**
     * Autenticacion y permisos. Se aplican siempre, sin depender de APP_NEED_AUTHENTICATION, porque el
     * valor de permiso filtra los datos de la vista.
     *
     * @param string $command     index, add, edit o delete: otro nombre haria que urlPermissionCmd devolviera null.
     * @param bool   $requirePost true rechaza otro verbo y cierra la sesion para no bloquear otras peticiones.
     * @return array [session, permission]
     * @throws \Exception Si no tiene acceso.
     */
    protected function authorize($command, $requirePost = true)
    {
        if (!in_array($command, ['index', 'add', 'edit', 'delete'], true)) {
            throw new \Exception(NOT_ACCESS_FUNCTION);
        }
        if (!$this->core_web_authentication->isAuthenticated()) {
            throw new \Exception(USER_NOT_AUTENTICATED);
        }

        $dataSession = $this->session->get();

        if ($requirePost && strtolower((string) $this->request->getMethod()) !== 'post') {
            throw new \Exception('Metodo no permitido.');
        }

        $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
        if (!$permited) {
            throw new \Exception(NOT_ACCESS_CONTROL);
        }

        $permission = $this->core_web_permission->urlPermissionCmd(get_class($this), $command, URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
        if ($permission === null || (string) $permission === PERMISSION_NONE) {
            throw new \Exception(NOT_ACCESS_FUNCTION);
        }

        if ($requirePost) {
            session_write_close();
        }

        return ['session' => $dataSession, 'permission' => $permission];
    }

    /**
     * Cuerpo JSON de la peticion. Se lee aqui y no con helper_RequestGetValue, que trata "0" como vacio.
     *
     * @return array Cuerpo; vacio si no es JSON valido.
     */
    protected function readBody()
    {
        $body = $this->request->getJSON(true);
        return is_array($body) ? $body : [];
    }

    /**
     * Entero positivo estricto.
     *
     * @param mixed $value Valor recibido.
     * @return int El entero, o 0 si no es valido.
     */
    protected function toPositiveInt($value)
    {
        if (is_int($value)) {
            return $value > 0 ? $value : 0;
        }
        if (is_string($value) && ctype_digit($value) && strlen($value) <= 18) {
            return (int) $value;
        }
        return 0;
    }

    /**
     * Configuracion recibida.
     *
     * @param mixed $config Valor recibido.
     * @return array
     * @throws \Exception Si no es un objeto JSON.
     */
    protected function requireConfigArray($config)
    {
        if (!is_array($config)) {
            throw new \Exception('Debe indicar la configuracion del reporte.');
        }
        return $config;
    }

    /**
     * Vistas del catalogo disponibles: las del flavor de la empresa cuyo menu tiene permitido el usuario.
     *
     * @param array $dataSession Sesion.
     * @return array Filas (objetos) de las vistas disponibles.
     */
    protected function getAvailableViews(array $dataSession)
    {
        $addresses = core_web_ai_tools::getAddressesSesion($dataSession);
        $rows      = $this->Report_Builder_View_Model->get_rowsActive((int) $dataSession["user"]->companyID, (int) $dataSession["company"]->flavorID);

        return array_values(array_filter($rows, function ($row) use ($addresses) {
            return $this->core_web_report_builder->hasRequiredAddress($row->requiredAddress, $addresses);
        }));
    }

    /**
     * IDs de las vistas disponibles para el usuario.
     *
     * @param array $dataSession Sesion.
     * @return array Enteros con cada dataViewID.
     */
    protected function getAvailableViewIDs(array $dataSession)
    {
        return array_map('intval', array_column($this->getAvailableViews($dataSession), 'dataViewID'));
    }

    /**
     * Vista del catalogo activo a la que el usuario tiene acceso. Vista inexistente y vista sin
     * permiso dan la misma respuesta.
     *
     * @param array $dataSession Sesion.
     * @param mixed $dataViewID  Vista solicitada.
     * @return object Fila de la vista.
     * @throws \Exception Si no existe o no tiene acceso.
     */
    protected function requireView(array $dataSession, $dataViewID)
    {
        $id = $this->toPositiveInt($dataViewID);
        if ($id < 1) {
            throw new \Exception(self::MESSAGE_VIEW_NOT_FOUND);
        }

        $view = $this->Report_Builder_View_Model->get_rowByDataViewID($id, (int) $dataSession["user"]->companyID, (int) $dataSession["company"]->flavorID);
        if (!$view) {
            throw new \Exception(self::MESSAGE_VIEW_NOT_FOUND);
        }

        $addresses = core_web_ai_tools::getAddressesSesion($dataSession);
        if (!$this->core_web_report_builder->hasRequiredAddress($view->requiredAddress, $addresses)) {
            throw new \Exception(self::MESSAGE_VIEW_NOT_FOUND);
        }

        return $view;
    }

    /**
     * Consulta guardada del usuario. Ajena o inexistente dan la misma respuesta.
     *
     * @param array $dataSession     Sesion.
     * @param mixed $reportBuilderID Consulta solicitada.
     * @return object Fila de la consulta.
     * @throws \Exception Si no existe o es ajena.
     */
    protected function requireOwnQuery(array $dataSession, $reportBuilderID)
    {
        $id = $this->toPositiveInt($reportBuilderID);
        if ($id < 1) {
            throw new \Exception(self::MESSAGE_QUERY_NOT_FOUND);
        }

        $row = $this->Report_Builder_Model->get_rowByPK($dataSession["user"]->companyID, $dataSession["user"]->userID, $id);
        if (!$row) {
            throw new \Exception(self::MESSAGE_QUERY_NOT_FOUND);
        }

        return $row;
    }

    /**
     * Vista de una consulta guardada. Sin acceso a la vista se responde igual que consulta inexistente.
     *
     * @param array  $dataSession Sesion.
     * @param object $row         Fila de la consulta.
     * @return object Fila de la vista.
     * @throws \Exception Si la vista no esta disponible.
     */
    protected function requireVisibleQueryView(array $dataSession, $row)
    {
        try {
            return $this->requireView($dataSession, (int) $row->dataViewID);
        } catch (\Exception $ex) {
            throw new \Exception(self::MESSAGE_QUERY_NOT_FOUND);
        }
    }

    /**
     * Campos del catalogo que el usuario puede usar. El modelo incluye los de costo (isCost = 1);
     * se quitan aqui con la regla de core_web_ai_tools::puedeVerCostos().
     *
     * @param array $dataSession Sesion.
     * @param int   $dataViewID  Vista.
     * @return array Filas permitidas.
     */
    protected function getCatalogFields(array $dataSession, $dataViewID)
    {
        $this->core_web_ai_tools->init($dataSession);

        return $this->core_web_report_builder->stripCostFields(
            $this->Report_Builder_View_Model->get_fieldsByDataViewID($dataViewID),
            $this->core_web_ai_tools->puedeVerCostos() === true
        );
    }

    /**
     * Valida la configuracion y resuelve el SQL de la vista para generar o exportar. Omite los filtros
     * sin valor.
     *
     * @param array $dataSession Sesion.
     * @param mixed $permission  Valor de urlPermissionCmd "index" de este controlador.
     * @param mixed $config      Configuracion recibida.
     * @return array [view, config, baseSql]
     * @throws \Exception Si algo no es valido.
     */
    protected function buildQueryContext(array $dataSession, $permission, $config)
    {
        $config = $this->requireConfigArray($config);
        $view   = $this->requireView($dataSession, $config['dataViewID'] ?? null);
        $fields = $this->getCatalogFields($dataSession, $view->dataViewID);
        $normal = $this->core_web_report_builder->validateConfig($config, $fields, []);

        return [
            'view'    => $view,
            'config'  => $normal,
            'baseSql' => $this->resolveViewSql($dataSession, $permission, $view),
        ];
    }

    /**
     * SQL de la vista segun la empresa y su flavorID exacto. A diferencia de core_web_view, no hay
     * respaldo sin flavor: una vista de otro flavor no esta disponible para la empresa.
     *
     * @param array  $dataSession Sesion.
     * @param mixed  $permission  Valor de urlPermissionCmd "index".
     * @param object $view        Fila de la vista.
     * @return string SQL resuelto.
     * @throws \Exception Si no hay SQL para la empresa o no cumple el contrato.
     */
    protected function resolveViewSql(array $dataSession, $permission, $view)
    {
        $user      = $dataSession["user"];
        $companyID = (int) $user->companyID;
        $flavorID  = (int) $dataSession["company"]->flavorID;

        $companyView = $this->Company_Data_View_Model->get_rowBy_companyIDDataViewIDAndFlavor($companyID, $view->dataViewID, CALLERID_REPORT_BUILDER, $view->componentID, $flavorID);
        if (!$companyView) {
            throw new \Exception(self::MESSAGE_VIEW_NOT_FOUND);
        }

        return $this->core_web_report_builder->resolveBaseSql($companyView->sqlScript, $companyID, $user->userID, $user->branchID, $permission);
    }

    /**
     * Parametros de empresa del Constructor de Reportes, normalizados contra sus topes.
     *
     * @param int $companyID Empresa.
     * @return array [maxRowsScreen, pageSizeMax, maxRowsExport, timeoutSeconds]
     * @throws \Exception Si falta un parametro.
     */
    protected function readLimits($companyID)
    {
        try {
            return $this->core_web_report_builder->normalizeLimits(
                $this->core_web_parameter->getParameter("REPORT_BUILDER_MAX_ROWS_SCREEN", $companyID)->value,
                $this->core_web_parameter->getParameter("REPORT_BUILDER_PAGE_SIZE_MAX", $companyID)->value,
                $this->core_web_parameter->getParameter("REPORT_BUILDER_MAX_ROWS_EXPORT", $companyID)->value,
                $this->core_web_parameter->getParameter("REPORT_BUILDER_TIMEOUT_SECONDS", $companyID)->value
            );
        } catch (\Exception $ex) {
            throw new \Exception('Configuracion incompleta del Constructor de Reportes: ' . $ex->getMessage());
        }
    }

    /**
     * Amplia el tiempo de PHP para que el limite de la BD actue primero: en Windows max_execution_time
     * cuenta tiempo real, y un corte de PHP es un error fatal que no llega al catch.
     *
     * @param int $seconds Segundos estimados de consulta.
     * @return void
     */
    protected function extendTimeLimit($seconds)
    {
        @set_time_limit(((int) $seconds) + 60);
    }

    /**
     * Inserta una consulta con los datos de auditoria de la sesion.
     *
     * @param array  $dataSession Sesion.
     * @param int    $dataViewID  Vista.
     * @param string $name        Nombre validado.
     * @param string $configJson  Configuracion en JSON.
     * @return int reportBuilderID.
     */
    protected function insertQuery(array $dataSession, $dataViewID, $name, $configJson)
    {
        $user = $dataSession["user"];
        $now  = helper_getDateTime();

        return $this->Report_Builder_Model->insert_app_posme([
            'companyID'  => (int) $user->companyID,
            'userID'     => (int) $user->userID,
            'dataViewID' => (int) $dataViewID,
            'name'       => $name,
            'configJson' => $configJson,
            'createdBy'  => (int) $user->userID,
            'createdOn'  => $now,
            'createdIn'  => $this->request->getIPAddress(),
            'createdAt'  => (int) $user->branchID,
            'modifiedOn' => $now,
            'isActive'   => 1,
        ]);
    }

    /**
     * Configuracion en JSON. Sin JSON_UNESCAPED_UNICODE: la conexion es utf8 de 3 bytes y un caracter de
     * 4 bytes en un titulo o filtro solo se guarda bien escapado como \uXXXX.
     *
     * @param array $config Configuracion normalizada.
     * @return string JSON.
     * @throws \RuntimeException Si no se puede codificar.
     */
    protected function encodeConfig(array $config)
    {
        $json = json_encode($config);
        if ($json === false) {
            throw new \RuntimeException('No se pudo codificar la configuracion: ' . json_last_error_msg());
        }
        return $json;
    }

    /**
     * Nombre por defecto de una copia: "Copia de <name>", recortado y sin repetir.
     *
     * @param array  $dataSession Sesion.
     * @param string $name        Nombre original.
     * @return string Nombre disponible.
     * @throws \Exception Si no hay nombre disponible.
     */
    protected function buildCopyName(array $dataSession, $name)
    {
        $companyID = $dataSession["user"]->companyID;
        $userID    = $dataSession["user"]->userID;
        $base      = 'Copia de ' . $name;
        $viewIDs   = $this->getAvailableViewIDs($dataSession);

        for ($i = 1; $i <= 100; $i++) {
            $suffix    = ($i === 1) ? '' : ' (' . $i . ')';
            $candidate = trim(mb_substr($base, 0, 150 - mb_strlen($suffix, 'UTF-8'), 'UTF-8')) . $suffix;
            if (!$this->Report_Builder_Model->existsName($companyID, $userID, $candidate, 0, $viewIDs)) {
                return $candidate;
            }
        }

        throw new \Exception('No se encontro un nombre disponible para la copia. Indique uno.');
    }

    /**
     * Crea la celda de OpenSpout a partir de la descripcion pura, reutilizando estilos.
     *
     * @param array $cell   Resultado de buildExcelCell.
     * @param bool  $bold   Negrita.
     * @param array $styles Estilos ya creados (por referencia).
     * @return \OpenSpout\Common\Entity\Cell
     */
    protected function createExcelCell(array $cell, $bold, array &$styles)
    {
        $key = (string) $cell['format'] . '|' . $cell['align'] . '|' . ($bold ? '1' : '0');

        if (!isset($styles[$key])) {
            $styleBuilder = new StyleBuilder();
            $styleBuilder->setCellAlignment($cell['align']);
            if ($bold) {
                $styleBuilder->setFontBold();
            }
            if ($cell['format'] !== null) {
                $styleBuilder->setFormat($cell['format']);
            }
            $styles[$key] = $styleBuilder->build();
        }

        return WriterEntityFactory::createCell($cell['value'], $styles[$key]);
    }

    /**
     * Mensaje seguro para el usuario. Solo pasan los \Exception simples, que son mensajes propios o de
     * core_web_parameter; las subclases (OpenSpout incluye rutas en los suyos) y los \Error se ocultan.
     *
     * @param \Throwable $ex Error.
     * @return string
     */
    protected function resolveClientMessage(\Throwable $ex)
    {
        if (get_class($ex) === 'Exception') {
            return $ex->getMessage();
        }

        log_message('error', 'app_builder_report: ' . get_class($ex) . ': ' . $ex->getMessage());
        return self::MESSAGE_GENERIC_ERROR;
    }

    /**
     * Respuesta JSON de error.
     *
     * @param \Throwable $ex Error.
     * @return mixed
     */
    protected function failResponse(\Throwable $ex)
    {
        return $this->response->setJSON([
            'error'   => true,
            'message' => $this->resolveClientMessage($ex),
        ]);
    }

    /**
     * Respuesta JSON correcta.
     *
     * @param array $data Datos adicionales.
     * @return mixed
     */
    protected function successResponse(array $data)
    {
        return $this->response->setJSON(array_merge([
            'error'   => false,
            'message' => SUCCESS,
        ], $data));
    }
}
