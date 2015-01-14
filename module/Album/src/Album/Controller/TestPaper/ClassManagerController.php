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
class ClassManagerController extends AbstractActionController
{
    protected $authservice;
    
    protected $ClassNameTable;

    protected $StudentTable;

    public function getClassNameTable()
    {
        if (! $this->ClassNameTable) {
            $this->ClassNameTable = $this->getServiceLocator()->get('ClassNameTable');
            return $this->ClassNameTable;
        }
    }
    
    public function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('TestPaperAuthService()');
        }
        return $this->authservice;
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
        $auth = $this->getAuthService();
        $identity = $auth->getIdentity();
        $uid = $identity->id;
        $ClassName = $this->getClassNameTable()->getClassByTeacher($uid);
        return array(
            'ClassName'=>$ClassName,
            'identity'=>$identity,
        );
    }
    // 添加
    public function addAction()
    {
        $form = new ClassManagerForm('ClassManager');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $className = new ClassName();
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $validData = $form->getData();
                $className->exchangeArray($validData);
                $classNameTable = $this->getClassNameTable;
                $classNameTable->saveClassName($className);
                return $this->redirect()->toRoute('ClassManager', array(
                    'action' => index
                ));
            }
        }
        $viewModel = new viewModel();
        $viewModel->setVariables(array(
            'form' => $form
        ));
        return $viewModel;
    }
    
    public function detailAction(){
        $cid = (int) $this->params()->fromRoute('id');
        if (!$cid) {
        	return $this->redirect()->toRoute('ClassManager', array(
        			'action' => 'index'
        	));
        }
        $className =$this->getClassNameTable()->getClassName($cid);
        $students = $this->getStudentTable()->getStudentsByClass($cid);
        return new ViewModel(array(
            'students'=>$students,
            'className'=>$className,));
    }
    // 编辑
    public function editAction()
    {
        // @TODO 试题知识点联动
        $tid = $this->params()->fromRoute('id');
        $TestPaper = $this->getTestPaperTable()->getTestPaper($tid);
        $Questions = $this->getQuestionTable()->getQuestions($tid);
        /*
         * debug::dump($TestPaper); debug::dump($Questions);
         */
        $form = new QuestionForm();
        /* $form->bind($Questions); */
        $form->get('submit')->setAttribute('value', 'Edit');
        return new ViewModel(array(
            'TestPaper' => $TestPaper,
            'Questions' => $Questions,
            'form' => $form
        ));
    }
    // 删除
    public function deleteAction()
    {
        $tid = (int) $this->params()->fromRoute('id');
        $this->getQuestionTable()->delete($tid);
        $this->getTestPaperTable()->delete($tid);
        return $this->redirect()->toRoute('TestPaper');
    }
}
    
