<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\TestPaperForm;
use Album\Model\TestPaper;
use Zend\debug\Debug;
use Album\Form\QuestionForm;
use Album\Model\Question;

/**
 * TestPaperController
 *
 * @author
 *
 * @version
 *
 */
class CarWashController extends AbstractActionController
{
    protected $TestPaperTable;
    protected $QuestionTable;
    public function getTestPaperTable(){
        if(!$this->TestPaperTable){
            $this->TestPaperTable = $this->getServiceLocator()->get('TestPaperTable');
            return $this->TestPaperTable;
        }
    }
    public function getQuestionTable(){
        if(!$this->QuestionTable){
        	$this->QuestionTable = $this->getServiceLocator()->get('QuestionTable');
        	return $this->QuestionTable;
        }
    }
    public function indexAction()
    {
        $TestPapers = $this->getTestPaperTable()->fetchAll();
        return new ViewModel(array(
        	'TestPapers'=>$TestPapers,
        ));
    }
        
    }

