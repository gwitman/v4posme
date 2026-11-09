<?php
//posme:2026-11-09
namespace App\Libraries;

/**
 * Sesión para el conector elFinder 2.1 apoyada en la sesión de CodeIgniter 4.
 *
 * elFinder, por defecto, abre y cierra la sesión nativa de PHP (session_start /
 * session_write_close) varias veces por petición, lo que choca con el manejador
 * de CodeIgniter. Esta clase guarda los datos de elFinder (token CSRF, cachés)
 * dentro de la sesión de CodeIgniter, bajo una sola llave, sin tocar el resto.
 * No se registra en _BaseController: solo la usa core_elfinder::load_elfinder.
 */
class core_web_elfinder_session implements \elFinderSessionInterface
{
    /** Llave de la sesión de CodeIgniter donde se guardan los datos de elFinder */
    const SESSION_KEY = 'posmeElFinder';

    /** @var \CodeIgniter\Session\Session */
    protected $session;

    /** @var array Copia local de los datos de elFinder */
    protected $data = array();

    function __construct()
    {
        $this->session = \Config\Services::session();
        $data          = $this->session->get(self::SESSION_KEY);
        $this->data    = is_array($data) ? $data : array();
    }

    /**
     * Inicia la sesión (CodeIgniter ya la tiene abierta).
     *
     * @return $this
     */
    function start()
    {
        return $this;
    }

    /**
     * Cierra la sesión (la cierra CodeIgniter al terminar la petición).
     *
     * @return $this
     */
    function close()
    {
        return $this;
    }

    /**
     * Devuelve un valor guardado o el valor por defecto.
     *
     * @param string $key   Llave; admite notación "a.b" como elFinderSession
     * @param mixed  $empty Valor por defecto; si se indica, el tipo del valor debe coincidir
     * @return mixed
     */
    function get($key, $empty = null)
    {
        $data = $this->data;
        foreach (explode('.', $key) as $part) {
            if (!is_array($data) || !array_key_exists($part, $data)) {
                return $empty;
            }
            $data = $data[$part];
        }

        if ($empty !== null && gettype($data) !== gettype($empty)) {
            return $empty;
        }

        return $data;
    }

    /**
     * Guarda un valor y lo persiste en la sesión de CodeIgniter.
     *
     * @param string $key  Llave; admite notación "a.b"
     * @param mixed  $data Valor
     * @return $this
     */
    function set($key, $data)
    {
        $ref   = &$this->data;
        $parts = explode('.', $key);
        $last  = array_pop($parts);
        foreach ($parts as $part) {
            if (!isset($ref[$part]) || !is_array($ref[$part])) {
                $ref[$part] = array();
            }
            $ref = &$ref[$part];
        }
        $ref[$last] = $data;
        unset($ref);

        $this->session->set(self::SESSION_KEY, $this->data);
        return $this;
    }

    /**
     * Elimina un valor y lo persiste en la sesión de CodeIgniter.
     *
     * @param string $key Llave; admite notación "a.b"
     * @return $this
     */
    function remove($key)
    {
        $ref   = &$this->data;
        $parts = explode('.', $key);
        $last  = array_pop($parts);
        foreach ($parts as $part) {
            if (!isset($ref[$part]) || !is_array($ref[$part])) {
                return $this;
            }
            $ref = &$ref[$part];
        }
        unset($ref[$last]);
        unset($ref);

        $this->session->set(self::SESSION_KEY, $this->data);
        return $this;
    }
}
