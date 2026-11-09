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
    const MESSAGE_CARD_NOT_FOUND  = 'La tarjeta solicitada no esta disponible.';
    const MESSAGE_GENERIC_ERROR   ='No se pudo completar la operacion. Intente de nuevo o contacte al administrador.';
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
            // Catalogos del modal Guardar (JSON para la vista); un catalogo con error llega vacio y se informa en errors
            $dataView["builderCatalogs"]    = $this->encodeForScript($this->core_web_report_builder->loadBuilderCatalogs($dataSession["user"]->companyID));
            $dataView["isAdminCompany"]     = $this->core_web_report_builder->isAdminCompany($dataSession);

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

            $result = $this->runQueryPage($context, $companyID, $limits, $page, $pageSize, $maxRows, true);

            return $this->successResponse([
                'columns'       => $result['columns'],
                'rows'          => $result['rows'],
                'page'          => $page,
                'pageSize'      => $pageSize,
                'hasMore'       => $result['hasMore'],
                'total'         => $result['total'],
                'totalIsCapped' => $result['totalIsCapped'],
                'maxRows'       => $maxRows,
                'pageSizeMax'   => $limits['pageSizeMax'],
            ]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Crea (sin ID) o actualiza (con ID) una consulta recurrente. Conserva los filtros sin valor y las
     * fechas dinamicas sin resolver: se resuelven cada vez que se usa la consulta.
     * Ademas guarda visibilidad (isShared, solo administrador), tipo de vista, dashboard, orden y la
     * presentacion (displayJson). Al editar no cambia el dueno: un administrador puede editar las
     * compartidas de otros usuarios.
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
            $isAdminCompany  = $this->core_web_report_builder->isAdminCompany($dataSession);
            $canEditShared   = $this->canEditSharedOfOthers($dataSession);
            $reportBuilderID = 0;
            $ownerID         = $userID;

            if ($isEdit) {
                $reportBuilderID = $this->toPositiveInt($rawID);
                // update_app_posme_editable devuelve true aunque ninguna fila coincida
                $row = ($reportBuilderID < 1) ? null : $this->Report_Builder_Model->get_rowEditableByPK($companyID, $userID, $reportBuilderID, $canEditShared);
                if (!$row) {
                    throw new \Exception(self::MESSAGE_QUERY_NOT_FOUND);
                }
                $ownerID = (int) $row->userID;
            }

            $name   = $this->core_web_report_builder->normalizeQueryName($body['name'] ?? null);
            $config = $this->requireConfigArray($body['config'] ?? null);
            $view   = $this->requireView($dataSession, $config['dataViewID'] ?? null);
            $fields = $this->getCatalogFields($dataSession, $view->dataViewID);
            $normal = $this->core_web_report_builder->validateConfig($config, $fields, ['keepEmptyFilters' => true]);
            $extra  = $this->readDisplaySettings($body, $normal, $companyID, $isAdminCompany);

            // El nombre es unico por dueno: al editar una consulta ajena (administrador) se valida con el dueno
            if ($this->Report_Builder_Model->existsName($companyID, $ownerID, $name, $reportBuilderID, $this->getAvailableViewIDs($dataSession))) {
                throw new \Exception(($ownerID === $userID) ? 'Ya tiene una consulta con ese nombre.' : 'El dueno de la consulta ya tiene otra con ese nombre.');
            }

            $configJson = $this->encodeConfig($normal);

            if ($isEdit) {
                // Sin userID en los datos: el dueno no cambia
                $this->Report_Builder_Model->update_app_posme_editable($companyID, $userID, $reportBuilderID, $canEditShared, array_merge([
                    'name'       => $name,
                    'dataViewID' => (int) $view->dataViewID,
                    'configJson' => $configJson,
                    'modifiedOn' => helper_getDateTime(),
                ], $extra));
            } else {
                $reportBuilderID = $this->insertQuery($dataSession, (int) $view->dataViewID, $name, $configJson, $extra);
            }

            return $this->successResponse(['reportBuilderID' => (int) $reportBuilderID]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Mis consultas (pestana 3): solo las del usuario que las creo, esten o no compartidas.
     * Las compartidas de otros usuarios solo se ven en los dashboards. Se ocultan las de vistas
     * no disponibles para el flavor de la empresa o el menu del usuario, porque no se podrian abrir.
     *
     * @return mixed JSON con queries.
     */
    function listQueries()
    {
        try {
            $access         = $this->authorize('index');
            $dataSession    = $access['session'];
            $companyID      = (int) $dataSession["user"]->companyID;
            $queries        = [];
            $rows           = $this->Report_Builder_Model->get_rowsEditableByUser($companyID, (int) $dataSession["user"]->userID, $this->canEditSharedOfOthers($dataSession));
            $viewIDs        = $this->getAvailableViewIDs($dataSession);
            $catalogs       = $this->core_web_report_builder->loadBuilderCatalogs($companyID);

            foreach ($rows as $row) {
                if (!in_array((int) $row->dataViewID, $viewIDs, true)) {
                    continue;
                }

                $viewType  = $this->core_web_report_builder->findCatalogItem((int) $row->viewTypeCatalogItemID, $catalogs['viewType']);
                $dashboard = $this->core_web_report_builder->findCatalogItem((int) $row->dashboardCatalogItemID, $catalogs['dashboard']);

                $queries[] = [
                    'reportBuilderID'        => (int) $row->reportBuilderID,
                    'name'                   => $row->name,
                    'dataViewID'             => (int) $row->dataViewID,
                    'modifiedOn'             => $row->modifiedOn,
                    'isOwner'                => (int) $row->isOwner === 1,
                    'ownerNickname'          => (string) $row->ownerNickname,
                    'isShared'               => (int) $row->isShared === 1,
                    'viewTypeCatalogItemID'  => (int) $row->viewTypeCatalogItemID,
                    'viewTypeName'           => $viewType ? $viewType['display'] : '',
                    'dashboardCatalogItemID' => (int) $row->dashboardCatalogItemID,
                    'dashboardName'          => $dashboard ? $dashboard['display'] : '',
                    'orden'                  => (int) $row->orden,
                ];
            }

            return $this->successResponse(['queries' => $queries]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Abre una consulta recurrente editable (propia, o compartida si es administrador). Una consulta no
     * editable o inexistente da la misma respuesta, y los campos que ya no estan en el catalogo se quitan
     * y se informan en warnings.
     *
     * @return mixed JSON con query y warnings.
     */
    function loadQuery()
    {
        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            $row         = $this->requireEditableQuery($dataSession, $body['reportBuilderID'] ?? null);
            $view        = $this->requireVisibleQueryView($dataSession, $row);
            $fields      = $this->getCatalogFields($dataSession, $view->dataViewID);

            $config = json_decode((string) $row->configJson, true);
            if (!is_array($config)) {
                $config = [];
            }
            $config['dataViewID'] = (int) $row->dataViewID;

            $result = $this->core_web_report_builder->reconcileConfig($config, $fields);

            // La presentacion guardada se devuelve tal cual; si no es JSON valido se trata como sin presentacion
            $display         = json_decode((string) $row->displayJson, true);
            $displayWarnings = is_array($display) ? $this->core_web_report_builder->checkDisplayColumns($display, $result['config']) : [];
            // Las claves son posicionales (c0, c1...): si se quitaron columnas, las restantes cambian de clave
            $savedColumns = (isset($config['columns']) && is_array($config['columns'])) ? count($config['columns']) : 0;
            $loadColumns  = (isset($result['config']['columns']) && is_array($result['config']['columns'])) ? count($result['config']['columns']) : 0;
            if (is_array($display) && $savedColumns !== $loadColumns) {
                $displayWarnings[] = 'Cambiaron las columnas de la consulta: revise los campos de la presentacion antes de guardar.';
            }

            return $this->successResponse([
                'query'    => [
                    'reportBuilderID'        => (int) $row->reportBuilderID,
                    'name'                   => $row->name,
                    'dataViewID'             => (int) $row->dataViewID,
                    'config'                 => $result['config'],
                    'isOwner'                => (int) $row->userID === (int) $dataSession["user"]->userID,
                    'isShared'               => (int) $row->isShared === 1,
                    'viewTypeCatalogItemID'  => (int) $row->viewTypeCatalogItemID,
                    'dashboardCatalogItemID' => (int) $row->dashboardCatalogItemID,
                    'orden'                  => (int) $row->orden,
                    'display'                => is_array($display) ? $display : null,
                ],
                'warnings'        => $result['warnings'],
                'displayWarnings' => $displayWarnings,
            ]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Duplica una consulta editable. La copia pertenece al usuario actual, es privada (isShared = 0) y
     * conserva tipo de vista, dashboard, orden y presentacion.
     *
     * @return mixed JSON con reportBuilderID.
     */
    function duplicateQuery()
    {
        try {
            $access      = $this->authorize('add');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            $row         = $this->requireEditableQuery($dataSession, $body['reportBuilderID'] ?? null);
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

            $reportBuilderID = $this->insertQuery($dataSession, (int) $view->dataViewID, $name, (string) $row->configJson, [
                'isShared'               => 0,
                'viewTypeCatalogItemID'  => (int) $row->viewTypeCatalogItemID,
                'dashboardCatalogItemID' => (int) $row->dashboardCatalogItemID,
                'orden'                  => (int) $row->orden,
                'displayJson'            => ($row->displayJson === null || $row->displayJson === '') ? null : (string) $row->displayJson,
            ]);

            return $this->successResponse(['reportBuilderID' => (int) $reportBuilderID]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Elimina (soft delete) una consulta editable (propia, o compartida si es administrador).
     *
     * @return mixed JSON estandar.
     */
    function deleteQuery()
    {
        try {
            $access      = $this->authorize('delete');
            $dataSession = $access['session'];
            $body        = $this->readBody();
            // delete_app_posme_editable devuelve true aunque ninguna fila coincida
            $row = $this->requireEditableQuery($dataSession, $body['reportBuilderID'] ?? null);

            $this->Report_Builder_Model->delete_app_posme_editable($dataSession["user"]->companyID, $dataSession["user"]->userID, $row->reportBuilderID, $this->canEditSharedOfOthers($dataSession));

            return $this->successResponse([]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Pagina limpia a ancho completo con el resultado de la configuracion actual (sin guardar). Recibe
     * por POST de un formulario normal (target _blank) los campos config (JSON en texto) y dataViewID.
     * Solo valida igual que generate: la tabla paginada la llena el JS de la vista llamando a generate,
     * que vuelve a validar y resuelve las fechas dinamicas en cada pagina.
     *
     * @return mixed Vista app_builder_report/view_result (con errorMessage si algo falla).
     */
    function viewResult()
    {
        $dataView = [
            "company"            => null,
            "companyPageSetting" => null,
            "useMobile"          => 0,
            "title"              => "Constructor de Reportes",
            "viewLabel"          => "",
            "dataViewID"         => 0,
            "configJson"         => "null",
            "errorMessage"       => "",
        ];

        try {
            $access      = $this->authorize('index');
            $dataSession = $access['session'];

            $config = json_decode((string) $this->request->getPost("config"), true);
            $config = $this->requireConfigArray($config);
            if ($this->toPositiveInt($this->request->getPost("dataViewID")) !== $this->toPositiveInt($config['dataViewID'] ?? null)) {
                throw new \Exception(self::MESSAGE_VIEW_NOT_FOUND);
            }

            // Misma validacion que generate (vista, campos, filtros y SQL de la vista); no ejecuta la consulta
            $context = $this->buildQueryContext($dataSession, $access['permission'], $config);

            $dataView["company"]            = $dataSession["company"];
            $dataView["companyPageSetting"] = $dataSession["companyPageSetting"];
            $dataView["useMobile"]          = $dataSession["user"]->useMobile;
            $dataView["title"]              = "Constructor de Reportes - " . $context['view']->label;
            $dataView["viewLabel"]          = (string) $context['view']->label;
            $dataView["dataViewID"]         = (int) $context['view']->dataViewID;
            // Se envia la configuracion validada SIN resolver: cada llamada a generate resuelve las fechas dinamicas
            $dataView["configJson"]         = $this->encodeForScript($context['savedConfig']);

            return view('app_builder_report/view_result', $dataView);
        } catch (\Throwable $ex) {
            $session = $this->session->get();
            if (empty($session["user"])) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $dataView["errorMessage"] = $this->resolveClientMessage($ex);
            return view('app_builder_report/view_result', $dataView);
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

    /**
     * Tarjetas de un dashboard: consultas activas de la compania asignadas a ese dashboard, propias o
     * compartidas, ordenadas por orden y reportBuilderID. Solo exige sesion: los usuarios del dashboard
     * pueden no tener el menu del Builder. Se descartan, sin fallar, las tarjetas con presentacion
     * invalida o cuya vista de datos no esta disponible para la compania (tb_company_dataview por
     * companyID y flavorID). Un dashboard que no existe en el catalogo devuelve cards vacio.
     * Entrada (POST JSON): {dashboard: name del item del catalogo "Builder Report Dashboard"}.
     *
     * @return mixed JSON con cards: [{reportBuilderID, name, orden, title, cardType, width, chartType, display}].
     */
    function getDashboardCards()
    {
        try {
            $access      = $this->authorizeDashboard();
            $dataSession = $access['session'];
            $companyID   = (int) $dataSession["user"]->companyID;
            $userID      = (int) $dataSession["user"]->userID;
            $body        = $this->readBody();
            $builder     = $this->core_web_report_builder;
            $catalogs    = $builder->loadBuilderCatalogs($companyID);
            $dashboard   = $builder->findDashboardByName($body['dashboard'] ?? null, $catalogs['dashboard']);
            $cards       = [];

            if ($dashboard === null) {
                return $this->successResponse(['cards' => $cards]);
            }

            $rows      = $this->Report_Builder_Model->get_rowsByDashboard($companyID, $userID, (int) $dashboard['catalogItemID']);
            $viewCache = [];

            foreach ((is_array($rows) ? $rows : []) as $row) {
                $display = json_decode((string) $row->displayJson, true);
                $card    = $builder->describeCard($display, $catalogs);
                if ($card === null) {
                    continue;
                }

                $dataViewID = (int) $row->dataViewID;
                if (!array_key_exists($dataViewID, $viewCache)) {
                    $viewCache[$dataViewID] = $this->findCompanyView($dataSession, $dataViewID) !== null;
                }
                if (!$viewCache[$dataViewID]) {
                    continue;
                }

                $title   = trim((string) ($display['title'] ?? ''));
                $cards[] = [
                    'reportBuilderID' => (int) $row->reportBuilderID,
                    'name'            => (string) $row->name,
                    'orden'           => (int) $row->orden,
                    'title'           => ($title !== '') ? $title : (string) $row->name,
                    'cardType'        => $card['cardType'],
                    'cardKind'        => $card['cardKind'],
                    'width'           => $card['width'],
                    'chartType'       => $card['chartType'],
                    'display'         => $display,
                ];
            }

            return $this->successResponse(['cards' => $cards]);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
        }
    }

    /**
     * Datos de una tarjeta de dashboard: ejecuta la consulta guardada (pagina 1) igual que generate, con
     * la configuracion reconciliada contra las columnas actuales de la vista y las fechas dinamicas
     * resueltas con la fecha del servidor. Filas segun el tipo: Indicador 1, Grafico hasta 100, Tabla 10
     * (sin superar REPORT_BUILDER_PAGE_SIZE_MAX). Solo la Tabla calcula total.
     * Entrada (POST JSON): {reportBuilderID}.
     *
     * @return mixed JSON con columns, rows, display, cardType, chartType, width, title y, en Tabla, total y totalIsCapped.
     */
    function getCardData()
    {
        try {
            $access      = $this->authorizeDashboard();
            $dataSession = $access['session'];
            $companyID   = (int) $dataSession["user"]->companyID;
            $body        = $this->readBody();
            $builder     = $this->core_web_report_builder;
            $id          = $this->toPositiveInt($body['reportBuilderID'] ?? null);

            $row = ($id < 1) ? null : $this->Report_Builder_Model->get_rowVisibleByPK($companyID, (int) $dataSession["user"]->userID, $id);
            if (!$row || (int) $row->dashboardCatalogItemID < 1) {
                throw new \Exception(self::MESSAGE_CARD_NOT_FOUND);
            }

            $display = json_decode((string) $row->displayJson, true);
            $card    = $builder->describeCard($display, $builder->loadBuilderCatalogs($companyID));
            if ($card === null) {
                throw new \Exception(self::MESSAGE_CARD_NOT_FOUND);
            }

            // Regla del usuario (2026-11-08): en una tarjeta compartida todos ven los datos de toda la compania
            // ({filterPermission} = PERMISSION_ALL). Una tarjeta privada solo la ve su creador y conserva su permiso.
            $permission = ((int) $row->isShared === 1) ? PERMISSION_ALL : $access['permission'];

            $limits   = $this->readLimits($companyID);
            $context  = $this->buildSavedQueryContext($dataSession, $permission, $row);
            $maxRows  = $limits['maxRowsScreen'];
            $pageSize = min($builder->resolveCardPageSize($card['cardKind'], $limits['pageSizeMax']), $maxRows);
            $isTable  = $card['cardKind'] === $builder::CARD_TYPE_TABLE;
            $result   = $this->runQueryPage($context, $companyID, $limits, 1, $pageSize, $maxRows, $isTable);
            $title    = trim((string) ($display['title'] ?? ''));

            $data = [
                'columns'   => $result['columns'],
                'rows'      => $result['rows'],
                'display'   => $display,
                'cardType'  => $card['cardType'],
                'cardKind'  => $card['cardKind'],
                'chartType' => $card['chartType'],
                'width'     => $card['width'],
                'title'     => ($title !== '') ? $title : (string) $row->name,
            ];
            if ($isTable) {
                $data['total']         = $result['total'];
                $data['totalIsCapped'] = $result['totalIsCapped'];
            }

            return $this->successResponse($data);
        } catch (\Throwable $ex) {
            return $this->failResponse($ex);
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
     * Acceso de los endpoints de dashboard: exige sesion y POST, pero no el menu del Builder. El filtro
     * de datos de la vista ({filterPermission}) usa el permiso "index" del Builder si el usuario lo tiene;
     * si no, el mas restrictivo (PERMISSION_ME: solo los registros creados por el usuario), para no
     * mostrar datos de otros usuarios a quien no tiene permiso para verlos.
     *
     * @return array [session, permission]
     * @throws \Exception Si no hay sesion o el verbo no es POST.
     */
    protected function authorizeDashboard()
    {
        if (!$this->core_web_authentication->isAuthenticated()) {
            throw new \Exception(USER_NOT_AUTENTICATED);
        }
        if (strtolower((string) $this->request->getMethod()) !== 'post') {
            throw new \Exception('Metodo no permitido.');
        }

        $dataSession = $this->session->get();
        $permission  = PERMISSION_ME;

        try {
            $access     = $this->authorize('index');
            $permission = $access['permission'];
        } catch (\Exception $ex) {
            // Sin menu o sin permiso del Builder: se conserva PERMISSION_ME
            $permission = PERMISSION_ME;
        }

        // authorize ya cierra la sesion cuando tiene exito; si fallo se cierra aqui
        if (session_status() === PHP_SESSION_ACTIVE) {
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

        $view = $this->findCompanyView($dataSession, $id);
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
     * Vista activa del catalogo (callerID 3) disponible para la compania: existe en tb_company_dataview
     * con la companyID y el flavorID exactos de la sesion. No revisa el menu del usuario (requiredAddress):
     * eso lo agrega requireView para el Builder.
     *
     * @param array $dataSession Sesion.
     * @param int   $dataViewID  Vista.
     * @return object|null Fila de la vista, o null si no esta disponible.
     */
    protected function findCompanyView(array $dataSession, $dataViewID)
    {
        $id = (int) $dataViewID;
        if ($id < 1) {
            return null;
        }

        $view = $this->Report_Builder_View_Model->get_rowByDataViewID($id, (int) $dataSession["user"]->companyID, (int) $dataSession["company"]->flavorID);
        return $view ? $view : null;
    }

    /**
     * Indica si el usuario puede abrir, editar, duplicar o eliminar consultas compartidas de otros
     * usuarios desde Mis consultas. Regla del usuario (2026-11-08): nadie puede; en la pestana 3 cada
     * usuario solo ve y edita las que creo. Las compartidas de otros solo se ven en los dashboards.
     * El administrador de la compania conserva unicamente el permiso de marcar una consulta como compartida.
     *
     * @param array $dataSession Sesion.
     * @return bool Siempre false con la regla vigente.
     */
    protected function canEditSharedOfOthers(array $dataSession)
    {
        return false;
    }

    /**
     * Consulta guardada que el usuario puede editar: solo la propia (ver canEditSharedOfOthers).
     * No editable o inexistente dan la misma respuesta.
     *
     * @param array $dataSession     Sesion.
     * @param mixed $reportBuilderID Consulta solicitada.
     * @return object Fila de la consulta.
     * @throws \Exception Si no existe o no es editable.
     */
    protected function requireEditableQuery(array $dataSession, $reportBuilderID)
    {
        $id = $this->toPositiveInt($reportBuilderID);
        if ($id < 1) {
            throw new \Exception(self::MESSAGE_QUERY_NOT_FOUND);
        }

        $row = $this->Report_Builder_Model->get_rowEditableByPK($dataSession["user"]->companyID, $dataSession["user"]->userID, $id, $this->canEditSharedOfOthers($dataSession));
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
     * sin valor y resuelve las fechas dinamicas con la fecha del servidor (helper_getDateTime_Object
     * aplica APP_HOUR_DIFERENCE_PHP).
     *
     * @param array $dataSession Sesion.
     * @param mixed $permission  Valor de urlPermissionCmd "index" de este controlador.
     * @param mixed $config      Configuracion recibida.
     * @return array [view, config (fechas resueltas, lista para ejecutar), savedConfig (validada, con tokens dinamicos), baseSql]
     * @throws \Exception Si algo no es valido.
     */
    protected function buildQueryContext(array $dataSession, $permission, $config)
    {
        $config = $this->requireConfigArray($config);
        $view   = $this->requireView($dataSession, $config['dataViewID'] ?? null);
        $fields = $this->getCatalogFields($dataSession, $view->dataViewID);
        $normal = $this->core_web_report_builder->validateConfig($config, $fields, []);

        return $this->composeQueryContext($dataSession, $permission, $view, $normal);
    }

    /**
     * Contexto de una consulta guardada para ejecutarla (tarjetas de dashboard). La vista debe estar
     * disponible para la compania (findCompanyView); la configuracion guardada se reconcilia con las
     * columnas actuales de la vista y luego se valida igual que generate (los filtros sin valor se omiten).
     *
     * @param array  $dataSession Sesion.
     * @param mixed  $permission  Permiso con el que se filtra la vista ({filterPermission}).
     * @param object $row         Fila de tb_report_builder.
     * @return array Igual que buildQueryContext.
     * @throws \Exception Si la vista no esta disponible o la consulta ya no es valida.
     */
    protected function buildSavedQueryContext(array $dataSession, $permission, $row)
    {
        $view = $this->findCompanyView($dataSession, (int) $row->dataViewID);
        if (!$view) {
            throw new \Exception(self::MESSAGE_CARD_NOT_FOUND);
        }

        $fields = $this->getCatalogFields($dataSession, $view->dataViewID);
        $config = json_decode((string) $row->configJson, true);
        if (!is_array($config)) {
            $config = [];
        }
        $config['dataViewID'] = (int) $row->dataViewID;

        $reconciled = $this->core_web_report_builder->reconcileConfig($config, $fields);
        $normal     = $this->core_web_report_builder->validateConfig($reconciled['config'], $fields, []);

        return $this->composeQueryContext($dataSession, $permission, $view, $normal);
    }

    /**
     * Parte comun de buildQueryContext y buildSavedQueryContext: resuelve las fechas dinamicas con la
     * fecha del servidor (helper_getDateTime_Object aplica APP_HOUR_DIFERENCE_PHP) y el SQL de la vista.
     *
     * @param array  $dataSession Sesion.
     * @param mixed  $permission  Permiso con el que se filtra la vista.
     * @param object $view        Fila de la vista.
     * @param array  $normal      Configuracion validada (con tokens dinamicos).
     * @return array [view, config (fechas resueltas), savedConfig, baseSql]
     * @throws \Exception Si el SQL de la vista no esta disponible o no es valido.
     */
    protected function composeQueryContext(array $dataSession, $permission, $view, array $normal)
    {
        return [
            'view'        => $view,
            'config'      => $this->core_web_report_builder->resolveDynamicFilters($normal, helper_getDateTime_Object()),
            'savedConfig' => $normal,
            'baseSql'     => $this->resolveViewSql($dataSession, $permission, $view),
        ];
    }

    /**
     * Ejecuta una pagina de un contexto ya validado (lo comparten generate y getCardData). Pide una fila
     * de mas para saber si hay otra pagina y, si se indica, cuenta el total hasta maxRows.
     *
     * @param array $context   Resultado de buildQueryContext o buildSavedQueryContext.
     * @param int   $companyID Empresa de la sesion.
     * @param array $limits    Resultado de readLimits.
     * @param int   $page      Pagina (desde 1), ya validada.
     * @param int   $pageSize  Filas por pagina, ya validado.
     * @param int   $maxRows   Tope de filas navegables.
     * @param bool  $withCount true para ejecutar tambien el conteo.
     * @return array [columns, rows, hasMore, total, totalIsCapped]; sin conteo total es null y totalIsCapped false.
     * @throws \Throwable Errores de la BD o de tiempo de espera.
     */
    protected function runQueryPage(array $context, $companyID, array $limits, $page, $pageSize, $maxRows, $withCount)
    {
        $offset  = ($page - 1) * $pageSize;
        $builder = $this->core_web_report_builder;
        $db      = db_connect();
        $this->extendTimeLimit($limits['timeoutSeconds'] * 3);

        // Se pide una fila de mas para saber si hay otra pagina
        $select = $builder->buildSelect($context['baseSql'], $context['config'], $companyID, $pageSize + 1, $offset);
        $rows   = $builder->execute($db, $select['sql'], $select['binds'], $limits['timeoutSeconds']);

        $total = null;
        if ($withCount) {
            $count     = $builder->buildCount($context['baseSql'], $context['config'], $companyID, $maxRows);
            $countRows = $builder->execute($db, $count['sql'], $count['binds'], $limits['timeoutSeconds']);
            $total     = isset($countRows[0]['total']) ? (int) $countRows[0]['total'] : 0;
        }

        return [
            'columns'       => $select['columns'],
            'rows'          => array_slice($rows, 0, $pageSize),
            'hasMore'       => count($rows) > $pageSize && ($offset + $pageSize) < $maxRows,
            'total'         => ($total === null) ? null : min($total, $maxRows),
            'totalIsCapped' => ($total !== null) && $total > $maxRows,
        ];
    }

    /**
     * Lee y valida los datos de visibilidad y presentacion de saveQuery. companyID y userID nunca salen
     * del POST.
     *
     * @param array $body           Cuerpo recibido.
     * @param array $config         Configuracion normalizada: sus columnas definen las claves validas del display.
     * @param int   $companyID      Empresa de la sesion.
     * @param bool  $isAdminCompany Administrador de la compania.
     * @return array [isShared, viewTypeCatalogItemID, dashboardCatalogItemID, orden, displayJson]
     * @throws \Exception Si algo no es valido.
     */
    protected function readDisplaySettings(array $body, array $config, $companyID, $isAdminCompany)
    {
        $builder  = $this->core_web_report_builder;
        $isShared = $this->toFlag($body['isShared'] ?? 0);

        // Regla de seguridad: aunque la interfaz oculte la opcion, solo un administrador comparte
        if ($isShared === 1 && !$isAdminCompany) {
            throw new \Exception('Solo un administrador de la compania puede compartir consultas con todos.');
        }

        $viewTypeID  = $this->toNonNegativeInt($body['viewTypeCatalogItemID'] ?? 0, 'El tipo de vista no es valido.');
        $dashboardID = $this->toNonNegativeInt($body['dashboardCatalogItemID'] ?? 0, 'El dashboard no es valido.');
        $orden       = $this->toNonNegativeInt($body['orden'] ?? 0, 'El orden debe ser un numero entero mayor o igual a 0.');
        $display     = $body['display'] ?? null;

        if ($display !== null && !is_array($display)) {
            throw new \Exception('La presentacion de la consulta tiene un formato invalido.');
        }

        $catalogs = $builder->loadBuilderCatalogs($companyID);
        if ($viewTypeID > 0 && $builder->findCatalogItem($viewTypeID, $catalogs['viewType']) === null) {
            throw new \Exception('El tipo de vista no es valido.');
        }
        if ($dashboardID > 0 && $builder->findCatalogItem($dashboardID, $catalogs['dashboard']) === null) {
            throw new \Exception('El dashboard no es valido.');
        }

        // Sin dashboard ni presentacion no se guarda displayJson
        $displayJson = null;
        if ($dashboardID > 0 || (is_array($display) && count($display) > 0)) {
            $displayJson = $this->encodeConfig($builder->validateDisplay(is_array($display) ? $display : [], $config, $catalogs));
        }

        return [
            'isShared'               => $isShared,
            'viewTypeCatalogItemID'  => $viewTypeID,
            'dashboardCatalogItemID' => $dashboardID,
            'orden'                  => $orden,
            'displayJson'            => $displayJson,
        ];
    }

    /**
     * Bandera 0/1: "1", 1, true y "true" son 1; cualquier otro valor es 0.
     *
     * @param mixed $value Valor recibido.
     * @return int
     */
    protected function toFlag($value)
    {
        return ($value === true || $value === 1 || $value === '1' || $value === 'true') ? 1 : 0;
    }

    /**
     * Entero mayor o igual a 0; null y '' equivalen a 0.
     *
     * @param mixed  $value   Valor recibido.
     * @param string $message Mensaje si no es valido.
     * @return int
     * @throws \Exception Si no es un entero no negativo.
     */
    protected function toNonNegativeInt($value, $message)
    {
        if ($value === null || $value === '' || $value === 0 || $value === '0') {
            return 0;
        }
        $number = $this->toPositiveInt($value);
        if ($number < 1 || $number > 2147483647) {
            throw new \Exception($message);
        }
        return $number;
    }

    /**
     * JSON seguro para incrustar en un <script> o atributo (escapa < > & ' ").
     *
     * @param mixed $data Datos.
     * @return string JSON.
     * @throws \RuntimeException Si no se puede codificar.
     */
    protected function encodeForScript($data)
    {
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        if ($json === false) {
            throw new \RuntimeException('No se pudo codificar la informacion de la pantalla: ' . json_last_error_msg());
        }
        return $json;
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
     * @param array  $extra       Opcional: isShared, viewTypeCatalogItemID, dashboardCatalogItemID, orden, displayJson.
     * @return int reportBuilderID.
     */
    protected function insertQuery(array $dataSession, $dataViewID, $name, $configJson, array $extra = [])
    {
        $user = $dataSession["user"];
        $now  = helper_getDateTime();

        // El dueno y la empresa siempre salen de la sesion: van despues de $extra para que no se sobrescriban
        return $this->Report_Builder_Model->insert_app_posme(array_merge($extra, [
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
        ]));
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
