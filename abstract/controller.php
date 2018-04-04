<?php

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
abstract class Koins_Abstract_Controller
{
    protected $template = null;
    protected $data     = [];
    protected $config   = [];

    /**
     * koins_abstract_controller constructor.
     */
    public function __construct()
    {
        global $xoopsModuleConfig;
        $this->config         = $xoopsModuleConfig;
        $this->data['config'] = $this->config;
        $this->data['url']    = KOINS_URL;
    }

    public function main()
    {
    }

    protected function _view()
    {
        if (!$this->template) {
            $this->template = 'koins_' . Koins::$controller . '_' . Koins::$action . '.tpl';
        }

        global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin;

        require_once XOOPS_ROOT_PATH . '/header.php';
        $GLOBALS['xoopsOption']['template_main'] =& $this->template;
        $this->_escapeHtml($this->data);
        $xoopsTpl->assign('koins', $this->data);
        require_once XOOPS_ROOT_PATH . '/footer.php';
    }

    /**
     * @param $vars
     */
    protected function _escapeHtml(&$vars)
    {
        foreach ($vars as $key => &$var) {
            if (preg_match('/_raw$/', $key)) {
                continue;
            } elseif (is_array($var)) {
                $this->_escapeHtml($var);
            } elseif (!is_object($var)) {
                $var = Koins::escapeHtml($var);
            }
        }
    }

    /**
     * @param $generator
     */
    protected function _generateImg(&$generator)
    {
        $platePath = Koins_Class_PartsManager::getPlatePath($this->params['plate']);
        $iconPath  = Koins_Class_PartsManager::getIconPath($this->params['icon']);

        $generator->setImageType($this->params['img_type']);
        $generator->importPlateImg($platePath);
        $generator->importIconImg($iconPath);
        $generator->setFontColor(0, 0, 0);

        if ('xoops2.png' === $this->params['plate']) {
            $generator->setFontTypeGothic();
            $generator->setIconPostion(32, 3);
            $lineWidth = $generator->getUpLineWidth($this->params['upline']);
            $lineX     = 45 - (int)($lineWidth / 0.55);
            //$lineX = 39 - (int)($lineWidth / 2); //for non-gothic font
            $generator->setLinePosistion($lineX, 39, 100, 100);
            $generator->incuseUpline($this->params['upline']);
        } elseif ('xoopscube.png' === $this->params['plate']) {
            $generator->setIconPostion(100, 100);
            $generator->setLinePosistion(40, 6, 40, 14);
            $generator->incuseUpline($this->params['upline']);
            $generator->incuseLowline($this->params['lowline']);
        } else {
            $generator->setFontTypeGothic();
            $generator->setLinePosistion(31, 5, 31, 17);
            $generator->incuseUpline($this->params['upline']);
            $generator->incuseLowline($this->params['lowline']);
        }
    }
}
