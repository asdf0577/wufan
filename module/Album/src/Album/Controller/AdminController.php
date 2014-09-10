<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * AdminController
 *
 * @author
 *
 * @version
 *
 */
class AdminController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated AdminController::indexAction() default action
        return new ViewModel();
    }
    public function registerAction(){
        return new ViewModel();
    }
    public function loginAction(){
    	return new ViewModel();
    }
    
    
}