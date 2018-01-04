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
 * Class Koins_Class_PartsManager
 */
class Koins_Class_PartsManager
{
    protected static $plates = null;
    protected static $icons  = null;

    /**
     * @return array|null
     */
    public static function getPlates()
    {
        if (null === self::$plates) {
            self::$plates   = self::_getFiles('plate');
            $xoops2Plate    = self::$plates['xoops2.png'];
            $xoopsCubePlate = self::$plates['xoopscube.png'];
            unset(self::$plates['xoops2.png'], self::$plates['xoopscube.png']);
            self::$plates['xoopscube.png'] = $xoopsCubePlate;
            self::$plates['xoops2.png']    = $xoops2Plate;
        }

        return self::$plates;
    }

    /**
     * @return array|null
     */
    public static function getIcons()
    {
        if (null === self::$icons) {
            self::$icons = self::_getFiles('icon');
            $noneIcon    = self::$icons['none.png'];
            unset(self::$icons['none.png']);
            self::$icons['none.png'] = $noneIcon;
        }

        return self::$icons;
    }

    /**
     * @param $plateName
     * @return string
     */
    public static function getPlatePath($plateName)
    {
        return KOINS_PATH . '/images/plates/' . $plateName;
    }

    /**
     * @param $iconName
     * @return string
     */
    public static function getIconPath($iconName)
    {
        return KOINS_PATH . '/images/icons/' . $iconName;
    }

    /**
     * @param string $type
     * @return array
     */
    protected static function _getFiles($type = 'icon')
    {
        if ('plate' === $type) {
            $dirpath = KOINS_PATH . '/images/plates';
            $dirurl  = KOINS_URL . '/images/plates';
        } else {
            $dirpath = KOINS_PATH . '/images/icons';
            $dirurl  = KOINS_URL . '/images/icons';
        }

        $files = [];
        $dir   = dir($dirpath);

        while ($file = $dir->read()) {
            if (is_dir($file)) {
                continue;
            }
            if ('.png' !== substr($file, -4)) {
                continue;
            }

            $filePath  = $dirpath . '/' . $file;
            $imageinfo = getimagesize($filePath);
            $imgWidth  = $imageinfo[0];
            $imgHeight = $imageinfo[1];

            $files[$file] = [
                'name'     => $file,
                'title'    => substr($file, 0, -4),
                'url'      => $dirurl . '/' . $file,
                'width'    => $imgWidth,
                'height'   => $imgHeight,
                'boxwidth' => $imgWidth + 20,
            ];
        }

        $dir->close();

        return $files;
    }
}
