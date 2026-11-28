<?php
//posme:2026-11-28
namespace App\Libraries;

use CodeIgniter\Router\RouteCollection;

/**
 * Colección de rutas de posMe con el registro de rutas optimizado.
 *
 * RouteCollection::create() (CodeIgniter 4.3.1) revisa si la ruta ya existe con
 * dot_array_search('*.route.' . $from, ...), que recorre TODAS las rutas ya registradas
 * cada vez que se agrega una. Con las miles de rutas explícitas de app/Config/Routes.php
 * eso es O(n²): unos 2.1 s en cada petición, en todas las pantallas.
 *
 * Esta clase hace exactamente lo mismo que create() del núcleo, pero la verificación de
 * "ruta existente" usa un índice [verbo][from] (isset, O(1)). Los casos poco comunes
 * (callable en arreglo, hostname, subdominio) se delegan intactos al núcleo y luego se
 * reconstruye el índice. No se modifica system/: se registra en app/Config/Services.php.
 */
class core_web_route_collection extends RouteCollection
{
    /**
     * Índice de rutas registradas: [verbo][from] => true.
     *
     * @var array<string, array<string, bool>>
     */
    protected $pmIndiceFrom = [];

    /**
     * Igual que RouteCollection::create(), con la búsqueda de duplicados por índice.
     *
     * @param array|Closure|string $to
     */
    protected function create(string $verb, string $from, $to, ?array $options = null)
    {
        //Casos que dependen de miembros privados del núcleo: se delegan sin cambios.
        $opcionesUnidas = array_merge($this->currentOptions ?? [], $options ?? []);
        if (
            (is_array($to) && count($to) === 2)
            || ! empty($opcionesUnidas['hostname'])
            || ! empty($opcionesUnidas['subdomain'])
        ) {
            parent::create($verb, $from, $to, $options);
            $this->pmReconstruirIndice($verb);

            return;
        }

        $overwrite = false;
        $prefix    = $this->group === null ? '' : $this->group . '/';

        $from = esc(strip_tags($prefix . $from));

        // While we want to add a route within a group of '/',
        // it doesn't work with matching, so remove them...
        if ($from !== '/') {
            $from = trim($from, '/');
        }

        $options = $opcionesUnidas;

        // Route priority detect
        if (isset($options['priority'])) {
            $options['priority'] = abs((int) $options['priority']);

            if ($options['priority'] > 0) {
                $this->prioritizeDetected = true;
            }
        }

        // Are we offsetting the binds?
        if (isset($options['offset']) && is_string($to)) {
            $to = preg_replace('/(\$\d+)/', '$X', $to);

            for ($i = (int) $options['offset'] + 1; $i < (int) $options['offset'] + 7; $i++) {
                $to = preg_replace_callback(
                    '/\$X/',
                    static fn ($m) => '$' . $i,
                    $to,
                    1
                );
            }
        }

        // Replace our regex pattern placeholders with the actual thing
        foreach ($this->placeholders as $tag => $pattern) {
            $from = str_ireplace(':' . $tag, $pattern, $from);
        }

        // If is redirect, No processing
        if (! isset($options['redirect']) && is_string($to)) {
            // If no namespace found, add the default namespace
            if (strpos($to, '\\') === false || strpos($to, '\\') > 0) {
                $namespace = $options['namespace'] ?? $this->defaultNamespace;
                $to        = trim($namespace, '\\') . '\\' . $to;
            }
            $to = '\\' . ltrim($to, '\\');
        }

        $name = $options['as'] ?? $from;

        helper('array');

        //No sobrescribir un 'from' ya registrado (Routes.php es la fuente de verdad).
        //El núcleo lo busca recorriendo todas las rutas; aquí se consulta el índice.
        $fromExists = isset($this->pmIndiceFrom[$verb][$from]);
        if ((isset($this->routes[$verb][$name]) || $fromExists) && ! $overwrite) {
            return;
        }

        $this->routes[$verb][$name] = [
            'route' => [$from => $to],
        ];

        $this->routesOptions[$verb][$from] = $options;
        $this->pmIndiceFrom[$verb][$from]  = true;

        // Is this a redirect?
        if (isset($options['redirect']) && is_numeric($options['redirect'])) {
            $this->routes['*'][$name]['redirect'] = $options['redirect'];
        }
    }

    /**
     * Limpia las rutas y también el índice.
     */
    public function resetRoutes()
    {
        parent::resetRoutes();
        $this->pmIndiceFrom = [];
    }

    /**
     * Reconstruye el índice de un verbo a partir de las rutas registradas.
     *
     * @param string $verb Verbo HTTP (get, post, *, ...)
     *
     * @return void
     */
    protected function pmReconstruirIndice(string $verb)
    {
        $this->pmIndiceFrom[$verb] = [];

        foreach ($this->routes[$verb] ?? [] as $ruta) {
            foreach (array_keys($ruta['route'] ?? []) as $fromRuta) {
                $this->pmIndiceFrom[$verb][$fromRuta] = true;
            }
        }
    }
}
