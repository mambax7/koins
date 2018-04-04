<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    Koins
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2009 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 *
 */

if (!defined('KOINS_LOADED')) {
    die('Koins has not been loaded.');
}

/**
 * Class koins_controller_apply
 */
class Koins_controller_apply extends Koins_Abstract_Controller
{
    protected $params = [];

    /**
     * koins_controller_apply constructor.
     */
    public function __construct()
    {
        parent::__construct();

        global $xoopsUser;

        if (!$xoopsUser->isAdmin()) {
            Koins::redirect(_KOINS_ERR_NO_PERMISSION);
        }

        if (!isset($_SESSION['koins_params']) or !is_array($_SESSION['koins_params'])) {
            Koins::redirect(_KOINS_ERR_PARAMS_LOST);
        }

        $this->params = $_SESSION['koins_params'];
    }

    public function main()
    {
        if ('confirm' === Koins::$Action) {
            $this->_validateTicket();
            $this->_confirm();
        } elseif ('apply' === Koins::$Action) {
            $this->_validateTicket();
            $this->_apply();
        } else {
            $this->_default();
        }
    }

    protected function _default()
    {
        $modules       = [];
        $exclusions    = ['.', '..', 'CVS'];
        $modulesDir    = XOOPS_ROOT_PATH . '/modules';
        $moduleHandler = xoops_getHandler('module');

        if ($handler = opendir($modulesDir)) {
            while (false !== ($dirname = readdir($handler))) {
                if (in_array($dirname, $exclusions)) {
                    continue;
                }
                if (!is_dir($modulesDir . '/' . $dirname)) {
                    continue;
                }
                if (!file_exists($modulesDir . '/' . $dirname . '/xoops_version.php')) {
                    continue;
                }

                $module = $moduleHandler->getByDirname($dirname);

                if (!is_object($module)) {
                    $module = $moduleHandler->create();
                    $module->loadInfoAsVar($dirname, false);
                }

                $mytrustdirnamePath = $modulesDir . '/' . $dirname . '/mytrustdirname.php';
                $isD3Module         = file_exists($mytrustdirnamePath);

                if ($isD3Module) {
                    $iconPath    = $modulesDir . '/' . $dirname . '/module_icon.png';
                    $iconUrl     = XOOPS_URL . '/modules/' . $dirname . '/module_icon.png';
                    $iconExists  = file_exists($iconPath);
                    $isRenamable = true;
                    $iconDir     = $modulesDir . '/' . $dirname;
                    $iconName    = 'module_icon.png';
                    $iconExt     = 'png';

                    if (!file_exists($iconPath)) {
                        require $mytrustdirnamePath;
                        $iconPath    = null;
                        $iconUrl     = XOOPS_URL . '/modules/' . $dirname . '/' . $module->getInfo('image');
                        $iconExists  = true;
                        $isRenamable = false;
                        unset($mytrustdirname);
                    }
                } else {
                    $iconPath    = $modulesDir . '/' . $dirname . '/' . $module->getInfo('image');
                    $iconUrl     = XOOPS_URL . '/modules/' . $dirname . '/' . $module->getInfo('image');
                    $iconExists  = file_exists($iconPath);
                    $isRenamable = true;
                    $iconDir     = $modulesDir . '/' . $dirname;
                    $iconName    = basename($module->getInfo('image'));
                    $iconExt     = pathinfo($module->getInfo('image'), PATHINFO_EXTENSION);
                }

                if (file_exists($iconDir . '/old_' . $iconName)) {
                    $number = 1;
                    while (file_exists($iconDir . '/old' . $number . '_' . $iconName)) {
                        ++$number;
                    }

                    $renamedOldIcon = 'old' . $number . '_' . $iconName;
                } else {
                    $renamedOldIcon = 'old_' . $iconName;
                }

                $modules[$dirname] = [
                    'name'             => $dirname,
                    'title'            => $module->getInfo('name'),
                    'icon'             => $module->getInfo('image'),
                    'icon_exists'      => $iconExists,
                    'icon_path'        => $iconPath,
                    'icon_url'         => $iconUrl,
                    'icon_ext'         => $iconExt,
                    'is_d3module'      => $isD3Module,
                    'is_renamable'     => $isRenamable,
                    'renamed_old_icon' => $renamedOldIcon,
                ];
            }
        }

        $_SESSION['koins_modules'] = $modules;

        $this->data['ticket']  = $GLOBALS['xoopsSecurity']->createToken();
        $this->data['modules'] = $modules;
        $this->_view();
    }

    protected function _confirm()
    {
        $dirname = Koins::post('dirname');

        $module                          = $_SESSION['koins_modules'][$dirname];
        $_SESSION['koins_target_module'] = $module;

        unset($_SESSION['koins_modules']);

        $this->data['module'] = $module;

        $params                 = $this->params;
        $params['action']       = 'viewimage';
        $query                  = http_build_query($params);
        $this->data['new_icon'] = sprintf('%s/index.php?%s', KOINS_URL, $query);
        $this->data['ticket']   = Koins_Class_Ticket::issue();

        $this->_view();
    }

    protected function _apply()
    {
        $renameIcon = Koins::post('rename_icon');

        $module = $_SESSION['koins_target_module'];
        unset($_SESSION['koins_target_module']);
        unset($_SESSION['koins_params']);

        if ($module['icon_exists']) {
            if ($module['is_d3module']) {
                if ($module['is_renamable']) {
                    $type = 1;
                } else {
                    $type = 3;
                }
            } else {
                $type = 1;
            }
        } else {
            $type = 2;
        }

        if (1 == $type) { // Rename and Replace
            if ($module['icon_exists'] and $renameIcon and $module['is_renamable']) {
                $newIconPath = dirname($module['icon_path']) . '/' . $module['renamed_old_icon'];
                rename($module['icon_path'], $newIconPath);
            } elseif ($module['icon_exists'] and !$renameIcon) {
                unlink($module['icon_path']);
            }

            $iconPath = $module['icon_path'];
        } elseif (2 == $type) { // Create New Icon
            $iconPath = $module['icon_path'];
        } elseif (3 == $type) {
            $iconPath = XOOPS_ROOT_PATH . '/modules/' . $module['name'] . '/module_icon.png';
        }

        $generator = new Koins_Class_IconGenerator();

        if ($module['icon_ext'] != $this->params['img_type']) {
            $generator->setImageType($module['icon_ext']);
        }

        $this->_generateImg($generator);
        $result = $generator->saveImage($iconPath);

        if ($result) {
            Koins::redirect(_KOINS_ICON_CREATION_IS_SUCCESS);
        } else {
            $_SESSION['koins_errors'] = [_KOINS_ERR_ICON_CREATION_FAILED];
            $query                    = http_build_query($this->params);
            header('Location: ' . KOINS_URL . '/index.php?' . $query);
        }
    }

    protected function _validateTicket()
    {
        $ticket = Koins::post('ticket');

        if (!Koins_Class_Ticket::check($ticket)) {
            $_SESSION['koins_errors'] = [_KOINS_ERR_TICKET];
            $query                    = http_build_query($this->params);
            header('Location: ' . KOINS_URL . '/index.php?' . $query);
            die;
        }
    }
}
