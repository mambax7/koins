<?php namespace XoopsModules\Koins;

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

use  XoopsModules\Koins;

/**
 * Class Ticket
 * @package XoopsModules\Koins
 */
class Ticket
{
    /**
     * @param int $timeout
     * @return string
     */
    public static function issue($timeout = 180)
    {
        $expire = time() + (int)$timeout;
        $token  = md5(uniqid('', true) . mt_rand());

        if (isset($_SESSION['koins_tickets']) && is_array($_SESSION['koins_tickets'])) {
            if (count($_SESSION['koins_tickets']) >= 5) {
                asort($_SESSION['koins_tickets']);
                $_SESSION['koins_tickets'] = array_slice($_SESSION['koins_tickets'], -4, 4);
            }

            $_SESSION['koins_tickets'][$token] = $expire;
        } else {
            $_SESSION['koins_tickets'] = [$token => $expire];
        }

        return $token;
    }

    /**
     * @param $stub
     * @return bool
     */
    public static function check($stub)
    {
        if (!isset($_SESSION['koins_tickets'][$stub])) {
            return false;
        }
        if (time() >= $_SESSION['koins_tickets'][$stub]) {
            return false;
        }

        unset($_SESSION['koins_tickets'][$stub]);

        return true;
    }
}