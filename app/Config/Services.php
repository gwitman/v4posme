<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    /**
     * Colección de rutas con el registro optimizado (índice en lugar de dot_array_search).
     * Evita ~2 s por petición al cargar las miles de rutas de app/Config/Routes.php.
     * Si la librería aún no existe en la instalación (deploy/upgrade a medias), usa la del núcleo.
     *
     * @return \CodeIgniter\Router\RouteCollection
     */
    public static function routes(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('routes');
        }

        $clase = class_exists(\App\Libraries\core_web_route_collection::class)
            ? \App\Libraries\core_web_route_collection::class
            : \CodeIgniter\Router\RouteCollection::class;

        return new $clase(static::locator(), config('Modules'));
    }
}
