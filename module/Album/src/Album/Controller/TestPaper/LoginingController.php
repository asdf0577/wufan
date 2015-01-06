<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\TestPaperForm;
use Album\Model\TestPaper;
use Album\Model\QuestionType;
use Zend\debug\Debug;
use Album\Form\QuestionForm;
use Album\Form\ClassManagerForm;
use Album\Model\Question;
use Album\Model\ClassName;

/**
 * TestPaperController
 *
 * @author asdf0577
 *        
 * @version 0.3
 *         
 */
class LoginingController extends AbstractActionController
{

    protected $ClassNameTable;

    protected $StudentTable;

    public function getClassNameTable()
    {
        if (! $this->ClassNameTable) {
            $this->ClassNameTable = $this->getServiceLocator()->get('ClassNameTable');
            return $this->ClassNameTable;
        }
    }

    public function getStudentTable()
    {
        if (! $this->StudentTable) {
            $this->StudentTable = $this->getServiceLocator()->get('StudentTable');
            return $this->StudentTable;
        }
    }
    // 主页
    public function indexAction()
    {
        $ClassName = $this->getClassNameTable()->fetchAll();
        return array('ClassName'=>$ClassName);
    }
   
}
    
