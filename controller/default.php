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
 * Class koins_controller_default
 */
class koins_controller_default extends Koins_Abstract_Controller
{
    protected $plates = [];
    protected $icons  = [];
    protected $errors = [];

    protected $defaultPlateName = null;
    protected $defaultIconName  = null;
    protected $defaultImgType   = null;
    protected $gdAvailable      = true;

    /**
     * koins_controller_default constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->plates = Koins_Class_PartsManager::getPlates();
        $this->icons  = Koins_Class_PartsManager::getIcons();

        $defaultPlate           = reset($this->plates);
        $defaultIcon            = reset($this->icons);
        $this->defaultPlateName = $defaultPlate['name'];
        $this->defaultIconName  = $defaultIcon['name'];
        $this->defaultImgType   = 'png';

        $this->params['plate']    = Koins::get('plate', $this->defaultPlateName);
        $this->params['icon']     = Koins::get('icon', $this->defaultIconName);
        $this->params['img_type'] = Koins::get('img_type', $this->defaultImgType);
        $this->params['upline']   = Koins::get('upline', 'koins');
        $this->params['lowline']  = Koins::get('lowline', 'icon maker');

        $this->data['plates'] =& $this->plates;
        $this->data['icons']  =& $this->icons;
        $this->data['errors'] =& $this->errors;
        $this->data['params'] =& $this->params;
    }

    public function main()
    {
        if ($this->_hasError()) {
            $this->_default();
        } elseif (Koins::get('download')) {
            $this->_download();
        } elseif (Koins::get('apply2module')) {
            $this->_apply2module();
        } elseif ('viewimage' === Koins::$Action) {
            $this->_viewimage();
        } else {
            $this->_default();
        }
    }

    /**
     * @return bool
     */
    protected function _hasError()
    {
        $this->_validatePlate();
        $this->_validateIcon();
        $this->_validateImgType();

        if (isset($_SESSION['koins_errors'])) {
            $this->errors = array_merge($this->errors, $_SESSION['koins_errors']);
            unset($_SESSION['koins_errors']);
        }

        return (count($this->errors) > 0);
    }

    protected function _default()
    {
        $params           = $this->params;
        $params['action'] = 'viewimage';
        $query            = http_build_query($params);
        $iconUrl          = sprintf('%s/index.php?%s', KOINS_URL, $query);

        $this->data['newicon']['url'] = $iconUrl;

        if (Koins::get('plate')) {
            $this->data['generated'] = true;
        }

        $this->_view();
    }

    protected function _viewimage()
    {
        $generator = new Koins_Class_IconGenerator();
        $this->_generateImg($generator);
        $generator->render();
    }

    protected function _download()
    {
        $generator = new Koins_Class_IconGenerator();
        $this->_sendHeader();
        $this->_generateImg($generator);
        $generator->render();
    }

    protected function _apply2module()
    {
        $_SESSION['koins_params'] = $this->params;
        header('Location: ' . KOINS_URL . '/index.php?controller=apply');
    }

    protected function _validatePlate()
    {
        $plateNames = array_keys($this->plates);

        if (!in_array($this->params['plate'], $plateNames)) {
            $this->errors[]        = _KOINS_ERR_NO_PLATE;
            $this->params['plate'] = $this->defaultPlateName;
        }
    }

    protected function _validateIcon()
    {
        $iconNames = array_keys($this->icons);

        if (!in_array($this->params['icon'], $iconNames)) {
            $this->errors[]        = _KOINS_ERR_NO_ICON;
            $this->params['icons'] = $this->defaultIconName;
        }
    }

    protected function _validateImgType()
    {
        $imgTypes = ['png', 'gif'];

        if (!in_array($this->params['img_type'], $imgTypes)) {
            $this->errors[]           = _KOINS_ERR_NO_IMGTYPE;
            $this->params['img_type'] = $this->defaultImgType;
        }
    }

    protected function _sendHeader()
    {
        $filename = $this->_getFileName();

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
     * @param $generator
     */
    protected function _saveImage(&$generator)
    {
        $uploadDir = XOOPS_ROOT_PATH . '/uploads/koins';

        if (!file_exists($uploadDir)) {
            if (@!mkdir($uploadDir)) {
                Koins::redirect(KOINS_URL, _KOINS_ERR_MKDIR);
            }
        }

        $fileName = date('YmdHis') . $this->_getFileName();
        $filePath = $uploadDir . '/' . $fileName;

        if (!$generator->saveImage($filePath)) {
            Koins::redirect(KOINS_URL, _KOINS_ERR_SAVE_ICON);
        }

        $koins             = new stdClass;
        $koins->iconUrl    = XOOPS_URL . '/uploads/koins/' . $fileName;
        $koins->iconPath   = $filePath;
        $_SESSION['koins'] = $koins;
    }

    /**
     * @return string
     */
    protected function _getFileName()
    {
        return $this->params['upline'] . '_logo.' . $this->params['img_type'];
    }
}
