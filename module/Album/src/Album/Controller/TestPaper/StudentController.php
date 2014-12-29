<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\debug\Debug;
use Album\Model\ClassName;
use Album\Model\Student;

/**
 * TestPaperController
 *
 * @author asdf0577
 *        
 * @version 0.3
 *         
 */
class StudentController extends AbstractActionController
{

    protected $ClassNameTable;

    protected $StudentTable;
    
    protected $sm;
    
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
        $Students = $this->getStudentTable()->fetchAll();
        return new ViewModel(array(
            'Students' => $Students
        ));
    }
    // 添加
    public function addAction()
    {
        $sm = $this->getServiceLocator();
        $classTable = $this->getClassNameTable();
        $className = $classTable->fetchAll();
        $classNameArray = array();
        
        foreach ($className as $Type) {
        	$classNameArray[$Type['id']] = $Type['name'];
        }
        
       /*  debug::dump($classNameArray);
        die(); */
        
        $form = $sm->get('StudentForm');
        
        
        $form->get('class')->setValueOptions($classNameArray);
        
        
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $data = $request->getPost();
            $Student = new Student;
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $validData = $form->getData();
                $Student->exchangeArray($validData);
                $cid = $Student->cid;
                
                $StudentTable = $this->getStudentTable();
                $StudentTable->saveStudent($Student);
                $type = 'add';
                $classTable->studentAmount($cid,$type);
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
        $students = $this->getStudentTable()->getStudentsByClass($cid);
        return new ViewModel(array('students'=>$students));
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
         $id = (int) $this->params()->fromRoute('id');
         $studentTable = $this->getStudentTable();
         $cid =  $studentTable->getStudent($id)->cid;//两次调用this->getStudentTable 会出错
         $studentTable->delete($id); 
         $type = 'sub';
         $this->getClassNameTable()->studentAmount($cid,$type); 
        return $this->redirect()->toRoute('ClassManager',array('action'=>'detail','id'=>$cid));
    }
}
    
