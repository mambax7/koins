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

use  XoopsModules\Koins;

require  dirname(dirname(__DIR__)) . '/mainfile.php';

Koins\MyKoins::setup();
Koins\MyKoins::execute();
