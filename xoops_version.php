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

$modversion = [
    'version'       => '1.01',
    'module_status' => 'Beta 1',
    'release_date'  => '2018/04/15',
    'name'          => _KOINS_NAME,
    'description'   => _KOINS_DESC,
    'dirname'       => basename(__DIR__),
    'credits'       => 'Hidehito NOZAWA aka Suin <http://suin.asia>, Mamba, XOOPS Development Team',
    'author'        => 'Hidehito NOZAWA aka Suin <http://suin.asia>',
    'license'       => 'GPL see LICENSE',
    'image'         => 'assets/images/logoModule.png',
    'hasMain'       => 1,
    'hasAdmin'      => 1,
    // 'adminindex' => 'admin/index.php',
    // 'adminmenu'  => 'admin/menu.php',
    'onInstall'     => 'include/installer.php',
    'onUpdate'      => 'include/installer.php',
    // ------------------- Min Requirements -------------------
    'min_php'       => '5.5',
    'min_xoops'     => '2.5.9',
    'min_admin'     => '1.2',
    'min_db'        => ['mysql' => '5.5'],
];

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_KOINS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_KOINS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_KOINS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_KOINS_SUPPORT, 'link' => 'page=support'],
];

//  ------------------- Templates -----------------------------//
$modversion['templates'] = [
    ['file' => 'koins_default_default.tpl', 'description' => ''],
    ['file' => 'koins_module_default.tpl', 'description' => ''],
    ['file' => 'koins_module_confirm.tpl', 'description' => ''],
];






