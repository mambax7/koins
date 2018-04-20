<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use XoopsModules\Koins;
use XoopsModules\Koins\Common;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';

include __DIR__ . '/preloads/autoloader.php';
$moduleDirName = basename(__DIR__);

$helper       = Koins\Helper::getInstance();
$utility      = new Koins\Utility();
$configurator = new Common\Configurator();
$copyright    = $configurator->modCopyright;

$modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;
require_once __DIR__ . '/include/common.php';
$db = \XoopsDatabaseFactory::getDatabaseConnection();

$myts       = \MyTextSanitizer::getInstance();
$stylesheet = "modules/{$moduleDirName}/assets/css/style.css";
if (file_exists($GLOBALS['xoops']->path($stylesheet))) {
    $GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("www/{$stylesheet}"));
}

// Load language files
$helper->loadLanguage('main');
$helper->loadLanguage('modinfo');
