<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    Koins
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2009 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2.0
 *
 */

class koins
{
    /**
     * Names cosist of [a-z0-9_]
     * Those are usually used for file names.
     */
    public static $_controller;
    public static $_action;

    /**
     * Names cosist of [A-Za-z0-9]
     * Those are usually used for class or method names.
     */
    public static $Controller;
    public static $Action;

    /**
     * Names cosist of [a-z0-9]
     * Those are usually used for template file names.
     */
    public static $controller;
    public static $action;

    public static function setup()
    {
        if (defined('KOINS_LOADED')) {
            return;
        }

        define('KOINS_DIR', basename(__DIR__));
        define('KOINS_URL', sprintf('%s/modules/%s', XOOPS_URL, KOINS_DIR));
        define('KOINS_PATH', sprintf('%s/modules/%s', XOOPS_ROOT_PATH, KOINS_DIR));

        spl_autoload_register([__CLASS__, 'autoload']);

        define('KOINS_LOADED', true);
    }

    public static function execute()
    {
        $controller = self::get('controller', 'default');
        $action     = self::get('action', 'default');

        self::$Controller = self::putintoClassParts($controller);
        self::$Action     = self::putintoClassParts($action);
        self::$Action[0]  = strtolower(self::$Action[0]);

        self::$controller = strtolower(self::$Controller);
        self::$action     = strtolower(self::$Action);

        self::$_controller = self::putintoPathParts(self::$Controller);
        self::$_action     = self::putintoPathParts(self::$Action);

        $class    = 'Koins_Controller_' . self::$Controller;
        $instance = new $class();
        $instance->main();

        unset($instance);
    }

    /**
     * @param $blockName
     * @return mixed
     */
    public static function block($blockName)
    {
        $class    = 'Koins_Blocks_' . $blockName;
        $instance = new $class($blockName);
        $result   = $instance->main();
        unset($instance);

        return $result;
    }

    /**
     * @param $class
     */
    public static function autoload($class)
    {
        if (class_exists($class, false)) {
            return;
        }
        if (!preg_match('/^Koins_/', $class)) {
            return;
        }

        $parts = explode('_', $class);
        $parts = array_map([__CLASS__, 'putintoPathParts'], $parts);

        $module = array_shift($parts);

        $class = implode('/', $parts);
        $path  = sprintf('%s/%s.php', KOINS_PATH, $class);

        if (!file_exists($path)) {
            return;
        }

        require $path;
    }

    /**
     * Usefull functions
     * @param      $name
     * @param null $default
     * @return null|string
     */
    public static function get($name, $default = null)
    {
        $request = isset($_GET[$name]) ? $_GET[$name] : $default;
        if (get_magic_quotes_gpc() and !is_array($request)) {
            $request = stripslashes($request);
        }

        return $request;
    }

    /**
     * @param      $name
     * @param null $default
     * @return null|string
     */
    public static function post($name, $default = null)
    {
        $request = isset($_POST[$name]) ? $_POST[$name] : $default;
        if (get_magic_quotes_gpc() and !is_array($request)) {
            $request = stripslashes($request);
        }

        return $request;
    }

    /**
     * @param $str
     * @return array|mixed|string
     */
    public static function putintoClassParts($str)
    {
        $str = preg_replace('/[^a-z0-9_]/', '', $str);
        $str = explode('_', $str);
        $str = array_map('trim', $str);
        $str = array_diff($str, ['']);
        $str = array_map('ucfirst', $str);
        $str = implode('', $str);

        return $str;
    }

    /**
     * @param $str
     * @return bool|mixed|string
     */
    public static function putintoPathParts($str)
    {
        $str = preg_replace('/[^a-zA-Z0-9]/', '', $str);
        $str = preg_replace('/([A-Z])/', '_$1', $str);
        $str = strtolower($str);
        $str = substr($str, 1, strlen($str));

        return $str;
    }

    /**
     * @param $string
     * @return string
     */
    public static function escapeHtml($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    /**
     * @param      $msg
     * @param null $url
     */
    public static function redirect($msg, $url = null)
    {
        if (!$url) {
            $url = KOINS_URL;
        }

        redirect_header($url, 3, $msg);
    }
}
