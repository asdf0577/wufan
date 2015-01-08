<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\TestPaperForm;
use Album\Model\TestPaper;
use Album\Model\QuestionType;
use Zend\debug\Debug;
use Album\Form\QuestionForm;
use Album\Model\Question;
use Album\Form\ClassManagerForm;

/**
 * TestPaperController
 *
 * @author
 *
 *
 *
 *
 * @version
 *
 *
 *
 *
 */
class QuestionController extends AbstractActionController
{

protected $TestPaperTable;

    protected $QuestionTable;

    protected $QuestionTypeTable;

    protected $KnowledgeTable;
    
    protected $authservice;
    
    public function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('TestPaperAuthService()');
        }
        return $this->authservice;
    }

    public function getKnowledgeTable()
    {
        if (! $this->KnowledgeTable) {
            $this->KnowledgeTable = $this->getServiceLocator()->get('KnowledgeTable');
            return $this->KnowledgeTable;
        }
    }

    public function getTestPaperTable()
    {
        if (! $this->TestPaperTable) {
            $this->TestPaperTable = $this->getServiceLocator()->get('TestPaperTable');
            return $this->TestPaperTable;
        }
    }

    public function getQuestionTable()
    {
        if (! $this->QuestionTable) {
            $this->QuestionTable = $this->getServiceLocator()->get('QuestionTable');
            return $this->QuestionTable;
        }
    }

    public function getQuestionTypeTable()
    {
        if (! $this->QuestionTypeTable) {
            $this->QuestionTypeTable = $this->getServiceLocator()->get('QuestionTypeTable');
            return $this->QuestionTypeTable;
        }
    }
    
    //主页
    public function indexAction()
    {
        
        $TestPapers = $this->getTestPaperTable()->fetchAll();
        return array('TestPapers'=>$TestPapers);
    }
    
 public function submitAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $testPaper = $this->getTestPaperTable()->getTestPaper($id); // 根据tid获取试卷
        $questions = $this->getQuestionTable()->getQuestions($id); // 根据tid获取试题
        $questionTypeTable = $this->getQuestionTypeTable();
        // 获取该试卷的试题类型
        // 解读试题类型
        $questionType = $testPaper->questionType;
        $typeList = explode(',', $questionType);
        $count = count($typeList);
        $questionNames = array();
        for ($i = 0; $i < $count - 1; $i ++) {
            list ($type, $numInfo) = explode(":", $typeList[$i]);
            $questionNames[$i] = $questionTypeTable->getQuestionType($type)->name;
        }
        $sm = $this->getServiceLocator();
        
        //获取登陆学生（@todo教师）的班级id
        $auth = $this->getAuthService();
        
        $identity = $auth->getIdentity();
        $cid = $identity->cid;
        
        //获取班级列表（教师用）@todo 为班级表增加教师字段，按教师获取班级列表；
        $form = $sm->get('InputQuestionForm');
        $classes = $sm->get('ClassNameTable')->fetchAll();
        $classArray = array();
        foreach ($classes as $key=>$value){
            $classArray[$key] = $value['name'];
        }
        $form ->get('cid')->setValueOptions($classArray);
        //获取同班学生列表
      
        $studentTable = $sm->get('studentTable');
       
       
        $students = $studentTable->getStudentsByClass($cid);
        $studentsArray = array();
        foreach ($students as $key=>$value){
            $studentsArray[$key] = $value['name'];
        }
       
        $form->get('sid')->setValueOptions($studentsArray);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/myaccount');
        //content
        $viewContent = new ViewModel(array(
            'questionNames' => $questionNames,
            'testPaper' => $testPaper,
            'questions' => $questions,
            'students'=>$students,
            'form'=>$form,
        ));
        $viewContent->setTemplate('album/question/submit');
        //siderbar
        $viewSidebar = new ViewModel(array(
            'questionNames' => $questionNames
        ));
        $viewSidebar->setTemplate('album/test-paper/sidebar');
        
        $view->addChild($viewContent, 'content');
        $view->addChild($viewSidebar, 'sidebar');
        return $view;
        
        
            
    }
  
}
    
