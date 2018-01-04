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

$modversion['name']        = _KOINS_NAME;
$modversion['description'] = _KOINS_DESC;
$modversion['dirname']     = basename(__DIR__);
$modversion['version']     = '1.00';
$modversion['credits']     = 'Hidehito NOZAWA aka Suin <http://suin.asia>';
$modversion['author']      = 'Hidehito NOZAWA aka Suin <http://suin.asia>';
$modversion['license']     = 'GPL see LICENSE';
$modversion['image']       = 'images/logo.png';

$modversion['hasMain'] = 1;

$modversion['hasAdmin'] = 1;
// $modversion['adminindex'] = 'admin/index.php';
// $modversion['adminmenu']  = 'admin/menu.php';

$modversion['onInstall'] = 'class/installer.php';
$modversion['onUpdate']  = 'class/installer.php';

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_KOINS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_KOINS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_KOINS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_KOINS_SUPPORT, 'link' => 'page=support'],
];
