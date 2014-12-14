<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * GrammarController
 *
 * @author
 *
 * @version
 *
 */
class GrammarController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated GrammarController::indexAction() default action
        return new ViewModel();
    }
}