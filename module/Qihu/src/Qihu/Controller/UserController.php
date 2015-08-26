<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Controller
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: UserController.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Qihu\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Exception\InvalidArgumentException;
use Zend\View\Model\JsonModel;
use Qihu\Auth\Http;

/**
 * 奇虎(360)的身份验证访问控制器
 * 
 * @name UserController
 */
class UserController extends AbstractActionController
{
    /**
     * 
     * @var Http
     */
    protected $auth;
    
    /**
     * 默认动作
     */
    public function indexAction()
    {   
        $action = $this->params()->fromQuery('action');
        if (!method_exists($this, $this->getMethodFromAction($action))) {
            throw new InvalidArgumentException("The query paramater '{$action}' invalid.");
        }
        
        return $this->forward()->dispatch('Qihu\Controller\User', array('action' => $action));
    }

    /**
     * 验证奇虎(360)用户信息
	 */
    public function verifyAction()
    {
        $auth = $this->getAuthHttpObject();
        if (true === $auth->authenticate($this->params()->fromQuery())) {
        	return new JsonModel($auth->getResponseArray());
        } 
        
        return new JsonModel();
    }
    
    /**
     * 返回奇虎(360)的身份验证对象
     * 
     * @return Http
     */
    public function getAuthHttpObject() {
        if (!$this->auth instanceof Http) {
            $this->setAuthHttpObject($this->serviceLocator->get('Qihu\Auth\Http'));
        }
        
        return $this->auth;
    }
    
    /**
     * 设置奇虎(360)的身份验证对象
     * 
     * @param Http $auth
     * @return void
     */
    public function setAuthHttpObject(Http $auth) {
        $this->auth = $auth;
    }
}
