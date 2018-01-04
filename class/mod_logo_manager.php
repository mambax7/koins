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

if (!defined('KOINS_LOADED')) {
    die('Koins has not been loaded.');
}

/**
 * Class Koins_Class_ModLogoManager
 */
class Koins_Class_ModLogoManager
{
    /**
     * @return array
     */
    public function getModuleList()
    {
        $xoopsModulePath = XOOPS_ROOT_PATH . '/modules';
        $files           = [];
        $dir             = dir($xoopsModulePath);
        $iconExts        = ['.gif', '.png'];

        while ($file = $dir->read()) {
            $filePath = $xoopsModulePath . '/' . $file;
            if ('.' == substr($file, 0, 1)) {
                continue;
            }
            if (is_file($filePath)) {
                continue;
            }
            if (!file_exists($filePath . '/xoops_version.php')) {
                continue;
            }

            $reportingLevel = error_reporting();
            error_reporting(0);
            require $filePath . '/xoops_version.php';
            error_reporting($reportingLevel);

            $iconPath = $filePath . '/' . $modversion['image'];
            unset($modversion);

            $thisExt = substr($iconPath, -4);
            if (!in_array($thisExt, $iconExts)) {
                continue;
            }

            $files[] = $file;
        }

        $dir->close();

        return $files;
    }
}
