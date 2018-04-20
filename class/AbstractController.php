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
 * Class AbstractController
 * @package XoopsModules\Koins
 */
abstract class AbstractController
{
    protected $template = null;
    protected $data     = [];
    protected $config   = [];

    /**
     * Koins\AbstractController constructor.
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

    protected function view()
    {
        if (!$this->template) {
            $this->template = 'koins_' . Koins\MyKoins::$controller . '_' . Koins\MyKoins::$action . '.tpl';
        }

        global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin;

        $GLOBALS['xoopsOption']['template_main'] = $this->template;
        $this->escapeHtml($this->data);
        $xoopsTpl->assign('koins', $this->data);
//        require_once XOOPS_ROOT_PATH . '/header.php';

        require_once XOOPS_ROOT_PATH . '/footer.php';
    }

    /**
     * @param $vars
     */
    protected function escapeHtml(&$vars)
    {
        foreach ($vars as $key => &$var) {
            if (preg_match('/_raw$/', $key)) {
                continue;
            } elseif (is_array($var)) {
                $this->escapeHtml($var);
            } elseif (!is_object($var)) {
                $var = Koins\MyKoins::escapeHtml($var);
            }
        }
    }

    /**
     * @param Koins\IconGenerator $generator
     */
    protected function generateImg($generator)
    {
        $platePath = Koins\PartsManager::getPlatePath($this->params['plate']);
        $iconPath  = Koins\PartsManager::getIconPath($this->params['icon']);

        $generator->setImageType($this->params['img_type']);
        $generator->importPlateImg($platePath);
        $generator->importIconImg($iconPath);
        $generator->setFontColor(0, 0, 0);

        if ('xoops2.png' === $this->params['plate']) {
            $generator->setFontTypeArial();
            $generator->setIconPosition(32, 3);
            $lineWidth = $generator->getUpLineWidth($this->params['upline']);
            $lineX     = 45 - (int)($lineWidth / 0.55);
            $generator->setLinePosition($lineX, 39, 100, 100);
            $generator->useUpline($this->params['upline']);
        } elseif ('xoopscube.png' === $this->params['plate']) {
            $generator->setFontTypeDot();
            $generator->setIconPosition(100, 100);
            $generator->setLinePosition(40, 6, 40, 14);
            $generator->useUpline($this->params['upline']);
            $generator->useLowline($this->params['lowline']);
        } elseif ('Blank.png' === $this->params['plate']) {
            $generator->setFontTypeGothic();
            $generator->setIconPosition(15, 10);
            $lineWidth = $generator->getUpLineWidth($this->params['upline']);
            $lineX     = 30 - (int)($lineWidth / 0.55);
            $generator->setLinePosition($lineX, 45, 55, 55);
            $generator->useUpline($this->params['upline']);
//            $generator->useLowline($this->params['lowline']);
        } else {
            $generator->setFontTypeGothic();
            $generator->setLinePosition(35, 5, 35, 17);
            $generator->useUpline($this->params['upline']);
            $generator->useLowline($this->params['lowline']);
        }
    }
}
