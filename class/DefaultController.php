<?php namespace XoopsModules\Koins;

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

if (!defined('KOINS_LOADED')) {
    die('Koins has not been loaded.');
}

/**
 * Class Koins\DefaultController
 */
class DefaultController extends Koins\AbstractController
{
    protected $plates = [];
    protected $icons  = [];
    protected $errors = [];

    protected $defaultPlateName = null;
    protected $defaultIconName  = null;
    protected $defaultImgType   = null;
    protected $gdAvailable      = true;

    /**
     * Koins\DefaultController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->plates = Koins\PartsManager::getPlates();
        $this->icons  = Koins\PartsManager::getIcons();

        $defaultPlate           = reset($this->plates);
        $defaultIcon            = reset($this->icons);
        $this->defaultPlateName = $defaultPlate['name'];
        $this->defaultIconName  = $defaultIcon['name'];
        $this->defaultImgType   = 'png';

        $this->params['plate']    = Koins\MyKoins::get('plate', $this->defaultPlateName);
        $this->params['icon']     = Koins\MyKoins::get('icon', $this->defaultIconName);
        $this->params['img_type'] = Koins\MyKoins::get('img_type', $this->defaultImgType);
        $this->params['upline']   = Koins\MyKoins::get('upline', 'KOINS');
        $this->params['lowline']  = Koins\MyKoins::get('lowline', 'Icon Maker');

        $this->data['plates'] = $this->plates;
        $this->data['icons']  = $this->icons;
        $this->data['errors'] = $this->errors;
        $this->data['params'] = $this->params;
    }

    public function main()
    {
        if ($this->hasError()) {
            $this->getDefault();
        } elseif (Koins\MyKoins::get('download')) {
            $this->download();
        } elseif (Koins\MyKoins::get('apply2module')) {
            $this->apply2module();
        } elseif ('viewimage' === Koins\MyKoins::$Action) {
            $this->viewImage();
        } else {
            $this->getDefault();
        }
    }

    /**
     * @return bool
     */
    protected function hasError()
    {
        $this->validatePlate();
        $this->validateIcon();
        $this->validateImgType();

        if (isset($_SESSION['koins_errors'])) {
            $this->errors = array_merge($this->errors, $_SESSION['koins_errors']);
            unset($_SESSION['koins_errors']);
        }

        return (count($this->errors) > 0);
    }

    protected function getDefault()
    {
        $params           = $this->params;
        $params['action'] = 'viewimage';
        $query            = http_build_query($params);
        $iconUrl          = sprintf('%s/index.php?%s', KOINS_URL, $query);

        $this->data['newicon']['url'] = $iconUrl;

        if (Koins\MyKoins::get('plate')) {
            $this->data['generated'] = true;
        }

        $this->view();
    }

    protected function viewImage()
    {
        $generator = new Koins\IconGenerator();
        $this->generateImg($generator);
        $generator->render();
    }

    protected function download()
    {
        $generator = new Koins\IconGenerator();
        $this->sendHeader();
        $this->generateImg($generator);
        $generator->render();
    }

    protected function apply2module()
    {
        $_SESSION['koins_params'] = $this->params;
        header('Location: ' . KOINS_URL . '/index.php?controller=module');
    }

    protected function validatePlate()
    {
        $plateNames = array_keys($this->plates);

        if (!in_array($this->params['plate'], $plateNames)) {
            $this->errors[]        = _KOINS_ERR_NO_PLATE;
            $this->params['plate'] = $this->defaultPlateName;
        }
    }

    protected function validateIcon()
    {
        $iconNames = array_keys($this->icons);

        if (!in_array($this->params['icon'], $iconNames)) {
            $this->errors[]        = _KOINS_ERR_NO_ICON;
            $this->params['icons'] = $this->defaultIconName;
        }
    }

    protected function validateImgType()
    {
        $imgTypes = ['png', 'gif'];

        if (!in_array($this->params['img_type'], $imgTypes)) {
            $this->errors[]           = _KOINS_ERR_NO_IMGTYPE;
            $this->params['img_type'] = $this->defaultImgType;
        }
    }

    protected function sendHeader()
    {
        $filename = $this->getFileName();

        if (preg_match('/MSIE ([0-9]\.[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-control: no-cache');
            header('Pragma: no-cache');
        }
    }

    /**
     * @param Koins\IconGenerator $generator
     */
    protected function saveImage($generator)
    {
        $uploadDir = XOOPS_ROOT_PATH . '/uploads/koins';

        if (!file_exists($uploadDir)) {
            if (@!mkdir($uploadDir)) {
                Koins\MyKoins::redirect(KOINS_URL, _KOINS_ERR_MKDIR);
            }
        }

        $fileName = date('YmdHis') . $this->getFileName();
        $filePath = $uploadDir . '/' . $fileName;

        if (!$generator->saveImage($filePath)) {
            Koins\MyKoins::redirect(KOINS_URL, _KOINS_ERR_SAVE_ICON);
        }

        $koins             = new \stdClass;
        $koins->iconUrl    = XOOPS_URL . '/uploads/koins/' . $fileName;
        $koins->iconPath   = $filePath;
        $_SESSION['koins'] = $koins;
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return $this->params['upline'] . '_logo.' . $this->params['img_type'];
    }
}
