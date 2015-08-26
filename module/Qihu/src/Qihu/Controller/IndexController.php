<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Controller
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: IndexController.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Qihu\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * 奇虎(360)的默认控制器
 * 
 * @name IndexController
 */
class IndexController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated IndexController::indexAction() default action
        return new ViewModel();
    }
}