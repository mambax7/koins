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
 * Class Koins_Class_IconGenerator
 */
class Koins_Class_IconGenerator
{
    protected $letters     = [];
    protected $plateImg    = null;
    protected $iconImg     = null;
    protected $targetImg   = null;
    protected $fontLayer   = null;
    protected $fontColor   = null;
    protected $plateWidth  = null;
    protected $plateHeight = null;
    protected $iconWidth   = null;
    protected $iconHeight  = null;
    protected $iconX       = 0;
    protected $iconY       = 0;
    protected $upX         = 0;
    protected $upY         = 0;
    protected $lowX        = 0;
    protected $lowY        = 0;
    protected $fontType    = 'eurostyle';
    protected $imageType   = 'png';

    /**
     * Koins_Class_IconGenerator constructor.
     */
    public function __construct()
    {
        $this->_setupLetters();
        $this->_decodeLetters();
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return (function_exists('getimagesize')
                and function_exists('imagecreatefrompng')
                    and function_exists('imagecreatetruecolor')
                        and function_exists('imagecolorallocate')
                            and function_exists('imagefill')
                                and function_exists('imagecolortransparent')
                                    and function_exists('imagecopymerge')
                                        and function_exists('imagecopy')
                                            and function_exists('imagepng')
                                                and function_exists('imagettftext')
                                                    and function_exists('imageantialias')
                                                        and function_exists('imagesetpixel')
                                                            and function_exists('imagedestroy'));
    }

    /**
     * @param $imageType
     */
    public function setImageType($imageType)
    {
        $this->imageType = $imageType;
    }

    /**
     * @param $plateImg
     */
    public function importPlateImg($plateImg)
    {
        $imageSize         = getimagesize($plateImg);
        $this->plateWidth  = $imageSize[0];
        $this->plateHeight = $imageSize[1];
        $this->plateImg    = imagecreatefrompng($plateImg);
        $this->targetImg   = imagecreatetruecolor($imageSize[0], $imageSize[1]);
        $this->fontLayer   = imagecreatetruecolor($imageSize[0], $imageSize[1]);
        $black             = imagecolorallocate($this->fontLayer, 1, 1, 1);
        imagefill($this->fontLayer, 0, 0, $black);
        imagecolortransparent($this->fontLayer, $black);
    }

    /**
     * @param $iconImg
     */
    public function importIconImg($iconImg)
    {
        $imageSize        = getimagesize($iconImg);
        $this->iconWidth  = $imageSize[0];
        $this->iconHeight = $imageSize[1];
        $this->iconImg    = imagecreatefrompng($iconImg);
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     */
    public function setFontColor($r = 0, $g = 0, $b = 0)
    {
        $this->fontColor = imagecolorallocate($this->targetImg, $r, $g, $b);
    }

    public function setFontTypeDot()
    {
        $this->fontType = 'arial';
    }

    public function setFontTypeGothic()
    {
        //$this->fontType = 'gothic';
        $this->fontType = 'arial';
        $this->_setupLettersImg();
    }

    /**
     * @param $upX
     * @param $upY
     * @param $lowX
     * @param $lowY
     */
    public function setLinePosistion($upX, $upY, $lowX, $lowY)
    {
        $this->upX  = $upX;
        $this->upY  = $upY;
        $this->lowX = $lowX;
        $this->lowY = $lowY;
    }

    /**
     * @param $string
     */
    public function incuseUpline($string)
    {
        if ('eurostyle' === $this->fontType) {
            $this->_incuseDot($string, $this->upX, $this->upY);
        } else {
            $this->_incuseGothic($string, $this->upX, $this->upY);
        }
    }

    /**
     * @param $string
     */
    public function incuseLowline($string)
    {
        if ('eurostyle' === $this->fontType) {
            $this->_incuseDot($string, $this->lowX, $this->lowY);
        } else {
            $this->_incuseGothic($string, $this->lowX, $this->lowY);
        }
    }

    /**
     * @param $string
     * @return int
     */
    public function getUpLineWidth($string)
    {
        $string  = strtolower($string);
        $letters = str_split($string);
        $width   = 0;

        foreach ($letters as $letter) {
            $letterMap = isset($this->letters[$letter]) ? $this->letters[$letter] : $this->letters['?'];
            $width     = $width + count($letterMap[0]);
        }

        $dividingSpace = count($letters) - 1;

        return $width + $dividingSpace;
    }

    /**
     * @param $x
     * @param $y
     */
    public function setIconPostion($x, $y)
    {
        $this->iconX = $x;
        $this->iconY = $y;
    }

    public function render()
    {
        $this->_mergeImages();
        $this->_renderTargetImg();
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function saveImage($filePath)
    {
        $this->_mergeImages();

        return $this->_saveTargetImg($filePath);
    }

    protected function _renderTargetImg()
    {
        if ('gif' === $this->imageType) {
            header('Content-type: image/gif');
            imagegif($this->targetImg);
        } else {
            header('Content-type: image/png');
            imagepng($this->targetImg);
        }
        imagedestroy($this->targetImg);
    }

    /**
     * @param $filePath
     * @return bool
     */
    protected function _saveTargetImg($filePath)
    {
        if ('gif' === $this->imageType) {
            $ret = imagegif($this->targetImg, $filePath);
        } else {
            $ret = imagepng($this->targetImg, $filePath);
        }

        imagedestroy($this->targetImg);

        return $ret;
    }

    protected function _mergeImages()
    {
        imagecopymerge($this->targetImg, $this->plateImg, 0, 0, 0, 0, $this->plateWidth, $this->plateHeight, 100);
        imagecopymerge($this->targetImg, $this->fontLayer, 0, 0, 0, 0, $this->plateWidth, $this->plateHeight, 100);
        imagecopy($this->targetImg, $this->iconImg, $this->iconX, $this->iconY, 0, 0, $this->iconWidth, $this->iconHeight);

        imagedestroy($this->plateImg);
        imagedestroy($this->iconImg);
        imagedestroy($this->fontLayer);
    }

    /**
     * @param $string
     * @param $x
     * @param $y
     */
    protected function _incuseGothic($string, $x, $y)
    {
        $this->publisher_createLogo($string);
        $string = strtolower($string);

        /*
                $letters = str_split($string);

                foreach ($letters as $letter) {
                    if ( !isset($this->letters[$letter]) ) $letter = '?';
                    if ( !file_exists($this->letters[$letter]) ) continue;

                    $letterImg    = imagecreatefrompng($this->letters[$letter]);
                    $letterSize   = getimagesize($this->letters[$letter]);
                    $letterWidth  = $letterSize[0];
                    $letterHeight = $letterSize[1];

                    imagecopy($this->fontLayer, $letterImg, $x, $y, 0, 0, $letterWidth, $letterHeight);
                    imagedestroy($letterImg);

                    $x = $x + $letterWidth;
                }
        */
    }

    /**
     * @param $title
     * @return bool
     */
    protected function publisher_createLogo($title)
    {
        if (!extension_loaded('gd')) {
            return false;
        }
        $required_functions = ['imagecreatetruecolor', 'imagecolorallocate', 'imagefilledrectangle', 'imagejpeg', 'imagedestroy', 'imageftbbox'];
        foreach ($required_functions as $func) {
            if (!function_exists($func)) {
                return false;
            }
        }

        $dirname = $GLOBALS['xoopsModule']->getVar('dirname');

        if (!file_exists($imageBase = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/images/plates/xoops2.png') || !file_exists($font = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/images/VeraBd.ttf')) {
            return false;
        }

        $imageModule = imagecreatefrompng($imageBase);
        //$imageModule = $this->$plateImg;

        //Erase old text
        $grey_color = imagecolorallocate($imageModule, 237, 237, 237);
        imagefilledrectangle($imageModule, 5, 35, 85, 46, $grey_color);

        // Write text
        $text_color      = imagecolorallocate($imageModule, 0, 0, 0);
        $space_to_border = (80 - strlen($title) * 6.5) / 2;
        imagefttext($imageModule, 8.5, 0, $space_to_border, 45, $text_color, $font, ucfirst($title), []);

        imagefttext($this->fontLayer, 8.5, 0, $space_to_border, 45, $text_color, $font, ucfirst($title), []);
        //imagettftext ($this->fontLayer, 8.5, 0, $space_to_border, 45, $text_color, $font, ucfirst($title), array());

        //        imagecopy($this->fontLayer, $letterImg, $x, $y, 0, 0, $letterWidth, $letterHeight);
        //              imagedestroy($letterImg);

        // Set transparency color
        $white = imagecolorallocatealpha($imageModule, 255, 255, 255, 127);
        imagefill($imageModule, 0, 0, $white);
        imagecolortransparent($imageModule, $white);

        //        imagecopymerge($this->targetImg, $this->plateImg, 0, 0, 0, 0, $this->plateWidth, $this->plateHeight, 100);
        //              imagecopymerge($this->targetImg, $this->fontLayer, 0, 0, 0, 0, $this->plateWidth, $this->plateHeight, 100);
        imagecopy($imageModule, $this->iconImg, $this->iconX, $this->iconY, 0, 0, $this->iconWidth, $this->iconHeight);

        imagepng($imageModule, XOOPS_ROOT_PATH . '/modules/' . $dirname . '/images/module_logo.png');

        imagepng($this->fontLayer, XOOPS_ROOT_PATH . '/modules/' . $dirname . '/images/fontLayer.png');

        imagedestroy($imageModule);

        return true;
    }

    /**
     * @param $string
     * @param $x
     * @param $y
     */
    protected function _incuseDot($string, $x, $y)
    {
        $string  = strtolower($string);
        $letters = str_split($string);

        foreach ($letters as $letter) {
            $letterMap = isset($this->letters[$letter]) ? $this->letters[$letter] : $this->letters['?'];

            foreach ($letterMap as $yy => $line) {
                foreach ($line as $xx => $dot) {
                    if (true === $dot) {
                        imagesetpixel($this->fontLayer, $x + $xx, $y + $yy, $this->fontColor);
                    }
                }
            }

            $x = $x + $xx + 2;
        }
    }

    protected function _setupLettersImg()
    {
        $letterDir          = KOINS_PATH . '/images/letters';
        $this->letters['a'] = "$letterDir/a.png";
        $this->letters['b'] = "$letterDir/b.png";
        $this->letters['c'] = "$letterDir/c.png";
        $this->letters['d'] = "$letterDir/d.png";
        $this->letters['e'] = "$letterDir/e.png";
        $this->letters['f'] = "$letterDir/f.png";
        $this->letters['g'] = "$letterDir/g.png";
        $this->letters['h'] = "$letterDir/h.png";
        $this->letters['i'] = "$letterDir/i.png";
        $this->letters['j'] = "$letterDir/j.png";
        $this->letters['k'] = "$letterDir/k.png";
        $this->letters['l'] = "$letterDir/l.png";
        $this->letters['m'] = "$letterDir/m.png";
        $this->letters['n'] = "$letterDir/n.png";
        $this->letters['o'] = "$letterDir/o.png";
        $this->letters['p'] = "$letterDir/p.png";
        $this->letters['q'] = "$letterDir/q.png";
        $this->letters['r'] = "$letterDir/r.png";
        $this->letters['s'] = "$letterDir/s.png";
        $this->letters['t'] = "$letterDir/t.png";
        $this->letters['u'] = "$letterDir/u.png";
        $this->letters['v'] = "$letterDir/v.png";
        $this->letters['w'] = "$letterDir/w.png";
        $this->letters['x'] = "$letterDir/x.png";
        $this->letters['y'] = "$letterDir/y.png";
        $this->letters['z'] = "$letterDir/z.png";
        $this->letters['1'] = "$letterDir/unknown.png";
        $this->letters['2'] = "$letterDir/unknown.png";
        $this->letters['3'] = "$letterDir/unknown.png";
        $this->letters['4'] = "$letterDir/unknown.png";
        $this->letters['5'] = "$letterDir/unknown.png";
        $this->letters['6'] = "$letterDir/unknown.png";
        $this->letters['7'] = "$letterDir/unknown.png";
        $this->letters['8'] = "$letterDir/unknown.png";
        $this->letters['9'] = "$letterDir/unknown.png";
        $this->letters['0'] = "$letterDir/unknown.png";
        $this->letters['.'] = "$letterDir/unknown.png";
        $this->letters['_'] = "$letterDir/unknown.png";
        $this->letters['-'] = "$letterDir/unknown.png";
        $this->letters['/'] = "$letterDir/unknown.png";
        $this->letters[' '] = "$letterDir/space.png";
        $this->letters['?'] = "$letterDir/unknown.png";
    }

    protected function _setupLetters()
    {
        $this->letters['a'] = '****/*  */****/*  */*  *';
        $this->letters['b'] = '****/*  */*** /*  */****';
        $this->letters['c'] = '****/*   /*   /*   /****';
        $this->letters['d'] = '*** /*  */*  */*  */*** ';
        $this->letters['e'] = '****/*   /*** /*   /****';
        $this->letters['f'] = '****/*   /*** /*   /*   ';
        $this->letters['g'] = '****/*   /* **/*  */****';
        $this->letters['h'] = '*  */*  */****/*  */*  *';
        $this->letters['i'] = '*/*/*/*/*';
        $this->letters['j'] = '****/   */   */*  */****';
        $this->letters['k'] = '*  */* * /**  /* * /*  *';
        $this->letters['l'] = '*   /*   /*   /*   /****';
        $this->letters['m'] = '*****/* * */* * */* * */* * *';
        $this->letters['n'] = '*  */** */* **/*  */*  *';
        $this->letters['o'] = '****/*  */*  */*  */****';
        $this->letters['p'] = '****/*  */****/*   /*   ';
        $this->letters['q'] = '****/*  */*  */* **/****';
        $this->letters['r'] = '****/*  */*** /*  */*  *';
        $this->letters['s'] = '****/*   /****/   */****';
        $this->letters['t'] = '*****/  *  /  *  /  *  /  *  ';
        $this->letters['u'] = '*  */*  */*  */*  */****';
        $this->letters['v'] = '*   */*   */*   */ * * /  *  ';
        $this->letters['w'] = '* * */* * */* * */* * */*****';
        $this->letters['x'] = '*   */ * * /  *  / * * /*   *';
        $this->letters['y'] = '*  */*  */****/  * /  * ';
        $this->letters['z'] = '****/  * / *  /*   /****';
        $this->letters['1'] = ' */**/ */ */ *';
        $this->letters['2'] = '****/   */****/*   /****';
        $this->letters['3'] = '****/   */ ***/   */****';
        $this->letters['4'] = '*  */*  */****/   */   *';
        $this->letters['5'] = '****/*   /****/   */****';
        $this->letters['6'] = '****/*   /****/*  */****';
        $this->letters['7'] = '****/*  */   */   */   *';
        $this->letters['8'] = '****/*  */****/*  */****';
        $this->letters['9'] = '****/*  */****/   */****';
        $this->letters['0'] = '****/*  */*  */*  */****';
        $this->letters['.'] = ' / / / /*';
        $this->letters['_'] = '   /   /   /   /***';
        $this->letters['-'] = '   /   /***/   /   ';
        $this->letters['/'] = '  * /  * /  *  / *  / *  ';
        $this->letters[' '] = ' / / / / ';
        $this->letters['?'] = '****/****/****/****/****';
    }

    protected function _decodeLetters()
    {
        foreach ($this->letters as &$letter) {
            $lines = explode('/', $letter);

            foreach ($lines as &$line) {
                $dots = str_split($line);

                foreach ($dots as &$dot) {
                    $dot = ('*' === $dot);
                }

                $line = $dots;
            }

            $letter = $lines;
        }
    }
}
