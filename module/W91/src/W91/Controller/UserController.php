<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package W91
 * @namespace W91\Controller
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: UserController.php 40626 2014-01-29 08:43:17Z zhangweiwen $
 */

namespace W91\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * 91无线的身份验证访问控制器
 * 
 * @name UserController
 */
class UserController extends AbstractActionController
{
    /**
     * 
     */
    public function indexAction()
    {   
        $action = $this->params()->fromQuery('action');
        
        return $this->forward()->dispatch('W91\Controller\User', array('action' => $action));
    }

    /**
     * 
	 */
    public function verifyAction()
    {
        $w91 = $this->serviceLocator->get('W91\Auth\Http'); /* @var $w91 \W91\Auth\Http */
        if (true === $w91->authenticate($this->params()->fromQuery())) {
        	return new JsonModel($w91->getResponse());
        }
        
        return new JsonModel(array('verify'));
    }
}
