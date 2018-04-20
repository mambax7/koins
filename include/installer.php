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

$mydirname = basename(dirname(__DIR__));

eval('
function xoops_module_install_' . $mydirname . '($module)
{
    return koins_installer($module, "' . $mydirname . '", "install");
}
function xoops_module_update_' . $mydirname . '($module)
{
    return koins_installer($module, "' . $mydirname . '", "update");
}
');

/**
 * @param \XoopsModule $module
 * @param $mydirname
 * @param $event
 * @return bool
 */
function koins_installer($module, $mydirname, $event)
{
    if ('update' === $event) {
        global $msgs;
        $ret =& $msgs;
    } else {
        global $ret;
    }

    if (!is_array($ret)) {
        $ret = [];
    }
    $mid = $module->getVar('mid');

    $tplfileHandler = xoops_getHandler('tplfile');
    $tplPath        = dirname(__DIR__) . '/templates';

    if ($handler = @opendir($tplPath . '/')) {
        while (false !== ($file = readdir($handler))) {
            if (0 === strncmp($file, '.', 1)) {
                continue;
            }

            $filePath = $tplPath . '/' . $file;

            if (is_file($filePath) && '.tpl' === substr($file, -4)) {
                $mtime   = (int)(@filemtime($filePath));
                $tplfile = $tplfileHandler->create();
                $tplfile->setVar('tpl_source', file_get_contents($filePath), true);
                $tplfile->setVar('tpl_refid', $mid);
                $tplfile->setVar('tpl_tplset', 'default');
                $tplfile->setVar('tpl_file', $file);
                $tplfile->setVar('tpl_desc', '', true);
                $tplfile->setVar('tpl_module', $mydirname);
                $tplfile->setVar('tpl_lastmodified', $mtime);
                $tplfile->setVar('tpl_lastimported', 0);
                $tplfile->setVar('tpl_type', 'module');

//                if (!$tplfileHandler->insert($tplfile)) {
//                    $ret[] = '<span style="color:#ff0000;">ERROR: Could not insert template <b>' . htmlspecialchars($file, ENT_QUOTES | ENT_HTML5) . '</b> to the database.</span><br>';
//                } else {
//                    $tplid = $tplfile->getVar('tpl_id');
//                    $ret[] = 'Template <b>' . htmlspecialchars($file, ENT_QUOTES | ENT_HTML5) . '</b> added to the database. (ID: <b>' . $tplid . '</b>)<br>';
//                    // generate compiled file
//                    require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
//                    require_once XOOPS_ROOT_PATH . '/class/template.php';
//
//                    if (!xoops_template_touch($tplid)) {
//                        $ret[] = '<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b>.</span><br>';
//                    } else {
//                        $ret[] = 'Template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b> compiled.</span><br>';
//                    }
//                }
            }
        }
        closedir($handler);
    }

    require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    xoops_template_clear_module_cache($mid);

    return true;
}
