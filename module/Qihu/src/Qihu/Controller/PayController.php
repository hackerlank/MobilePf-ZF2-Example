<?php
namespace Qihu\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * PayController
 *
 * @author
 *
 * @version
 *
 */
class PayController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated PayController::indexAction() default action
        return new ViewModel();
    }
}