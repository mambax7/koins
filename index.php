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

use XoopsModules\Koins;
//use XoopsModules\Koins\Constants;


$GLOBALS['xoopsOption']['template_main'] = 'koins_default_default.tpl';
require_once __DIR__ . '/header.php';


\XoopsModules\Koins\MyKoins::setup();
\XoopsModules\Koins\MyKoins::execute();

//require_once XOOPS_ROOT_PATH . '/footer.php';
