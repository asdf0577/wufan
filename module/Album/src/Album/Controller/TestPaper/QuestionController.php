<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\TestPaperAcl;
use Zend\debug\Debug;
use Album\Form\InputQuestionForm;
use Album\Model\WrongQuestionClass;
use Album\Model\WrongQuestionUser;
use Album\Model\WrongQuestionClassTable;
use Zend\Captcha\Dumb;

class QuestionController extends AbstractActionController
{

protected $TestPaperTable;

    protected $QuestionTable;

    protected $QuestionTypeTable;

    protected $KnowledgeTable;
    
    protected $authservice;
    
    protected $WrongQuestionClassTable;
    
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
    public function getWrongQuestionClassTable()
    {
        if (! $this->WrongQuestionClassTable) {
            $this->WrongQuestionClassTable = $this->getServiceLocator()->get('WrongQuestionClassTable');
            return $this->WrongQuestionClassTable;
        }
    }
    
 
  
    
    //主页
    public function indexAction()
    {
        $auth = $this->getAuthService();
        if($auth->hasIdentity()){
            $identity = $auth->getIdentity();
            $TestPaperIDs = $this->getServiceLocator()->get('TestPaperAclTable')->getTestPaperByClass($identity->cid);
            $testPaperTable = $this->getTestPaperTable();
            $TestPapers = array();
            foreach ($TestPaperIDs as $key => $TestPaperID){
                $TestPapers[$key] =  $testPaperTable->getTestPaper($TestPaperID['tid']);
            }
            return array('TestPapers'=>$TestPapers,'identity'=>$identity,);
        
        }    
    
    }
        

    public function teacherAction(){
        $auth = $this->getAuthService();
        if($auth->hasIdentity()){
            $identity = $auth->getIdentity();
            $TestPaperIDs = $this->getServiceLocator()->get('TestPaperAclTable')->getTestPaperByTeacher($identity->id);
            $testPaperTable = $this->getTestPaperTable();
            $TestPapers = array();
            foreach ($TestPaperIDs as $key => $TestPaperID){
                $TestPapers[$key] =  $testPaperTable->getTestPaper($TestPaperID['tid']);
            }
            return array('TestPapers'=>$TestPapers,'identity'=>$identity,);
        
        }
    }    
    
    
    public function analysisAction(){
        $auth = $this->getAuthService();
        $identity = $auth->getIdentity();
        $tid = (int) $this->params()->fromRoute('id');
        $WrongQuestionClassTable = $this->getWrongQuestionClassTable();
        $TestPapers = $WrongQuestionClassTable->getQuestionClassByTestPaper($tid);
        
//         debug::dump($TestPapers);
//         die();
        return array('TestPapers'=>$TestPapers,
            'identity'=>$identity,
        );
    }
    
    
    
    //创建以班级为单位的错题列表，@todo 长长的名字怎么精简 
    public function createQuestionAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $tid = $_POST['tid'];
            $sm = $this->getServiceLocator();
            $testPaper = $sm->get('TestPaperTable')->getTestPaper($tid);
            
            $wrongQusetionClass = new WrongQuestionClass();
            $wrongQusetionClassTable = $this->getWrongQuestionClassTable();
            $testPaperAcl = new TestPaperAcl();
            $testPaperAclTable = $sm->get('TestPaperAclTable');
            
            $aclData = array(
            'tid'=>$tid,
            'uid'=>$_POST['uid'],   
            );
            $classes = $_POST['classCheck'];
            $data= array(
                'tid' => $tid,
            );
            
            foreach ($classes as $class){
                for($i=1;$i<=$testPaper->questionAmount;$i++){
                    $data['cid'] = $class;
                    $data['question_num'] = $i;
                    $wrongQusetionClass->exchangeArray($data);
                    $wrongQusetionClassTable->createQuestionClass($wrongQusetionClass);
                }
                $aclData['cid'] = $class;
                $testPaperAcl->exchangeArray($aclData);
                $testPaperAclTable->addTestPaperAcl($testPaperAcl);
                
            }
         return $this->redirect()->toRoute(TestPaper,array('action'=>'index'));
        }
    }
    
    public function submitAction()
    {
        //获取身份判断
        $auth = $this->getAuthService();
        $identity = $auth->getIdentity();
        $role = $identity->role;
        
        
        //获取试卷再获取试题
        $id = (int) $this->params()->fromRoute('id');
        $testPaper = $this->getTestPaperTable()->getTestPaper($id); // 根据tid获取试卷
        $questions = $this->getQuestionTable()->getQuestions($id); // 根据tid获取试题
        $questionTypeTable = $this->getQuestionTypeTable();
        
        // 获取该试卷的试题类型
        $questionType = $testPaper->questionType;
        // 解读试题类型
        $typeList = explode(',', $questionType);
        $count = count($typeList);
        $questionNames = array();
        for ($i = 0; $i < $count - 1; $i ++) {
            list ($type, $numInfo) = explode(":", $typeList[$i]);
            $questionNames[$i] = $questionTypeTable->getQuestionType($type)->name;
        }
        $sm = $this->getServiceLocator();
        //实例化一个提交错题的表单
        $form = new InputQuestionForm('InputQuestionForm');
        //创建student版视图
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/testPaper2-layout');
        //为视图添加内容
        $viewContent = new ViewModel(array(
            'questionNames' => $questionNames,
            'testPaper' => $testPaper,
            'questions' => $questions,
//             'students'=>$students,
            'form'=>$form,
        ));
        
//         debug::dump($viewContent);
//         die();
        $viewContent->setTemplate('album/question/submit');
       
      
        
        
        
        if (in_array($role, array(
            'superStudentManager',
            'manager',
            'teacher',
        ))){
            //获取登陆学生的班级id
            $cid = $identity->cid;
            //获取同班学生列表
            $studentTable = $sm->get('studentTable');
            $students = $studentTable->getStudentsByClass($cid);
            $studentsArray = array();
            foreach ($students as $value){
                $studentsArray[$value['id']] = $value['name'];
            }
            $form->get('sid')->setValueOptions($studentsArray);
              $viewContent = new ViewModel(array(
            'questionNames' => $questionNames,
            'testPaper' => $testPaper,
            'questions' => $questions,
            'students'=>$students,
            'form'=>$form,
        ));
            $viewContent->setTemplate('album/question/student-manager-submit');
            
            //如果是超级管理员、教师，获取班级列表    
                if (in_array($role, array(
                    'SuperStudentManager',
                    'teacher',
                ))){
                    $classes = $sm->get('ClassNameTable')->fetchAll();
                    $classArray = array();
                    foreach ($classes as $value){
                        $classArray[$value['id']] = $value['name'];
                    }
                    $form ->get('cid')->setValueOptions($classArray);
                    $viewContent->setTemplate('album/question/super-student-manager-submit');
                }
            
        }
        
        
       
//         $viewContent['form'] = $form;
       
        //siderbar
        $viewSidebar = new ViewModel(array(
            'questionNames' => $questionNames
        ));
        $viewSidebar->setTemplate('album/test-paper/sidebar');
        
        $view->addChild($viewContent, 'content');
        $view->addChild($viewSidebar, 'sidebar');
        return $view;
        
        
            
    }
  
    public function addProcessAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            //先判断是否存在该试卷该学生错题记录
            $sm = $this->getServiceLocator();
            $WrongQuestionUserTable = $sm->get('WrongQuestionUserTable');
            //获取班级id,试卷id，学生id，
           
            $tid = $_POST['tid']; //testPaper_id
            $sid = $_POST['sid']; //student_id
            
            $result = $WrongQuestionUserTable->getQuestionUser($tid,$sid);
            if(!$result){
                //如果不存在，获取提交的错题编号、班级
                $inputQuestions = $_POST['inputQuestions']; //question_num_id
                $cid = $_POST['cid']; //class_id
                $nums = explode(",", $inputQuestions);
              
                //获取班级错题table，在班级错体表中添加数据
                $WrongQuestionClass = new WrongQuestionClass();
                $WrongQuestionClassTable = $this->getWrongQuestionClassTable();
                for($i=0;$i<sizeof($nums)-1;$i++){
                    $WrongQuestionClassTable->updateWrongQuestionClass($tid, $cid,$nums[$i], $sid);
                }
                //获取学生错题表
                            $data = array(
                                'sid' => $sid,
                                'qid' => $inputQuestions,
                                'tid' => $tid,
                
                            );
                 
                            $WrongQuestionUser = new WrongQuestionUser();
                            $WrongQuestionUser->exchangeArray($data);
                            $WrongQuestionUserTable->saveWrongQuestion($WrongQuestionUser);
                echo"success";
                die();
                }
                else {
                    echo "已经存在记录";
                    die();
                }
                
                
                
            }
            
            
           
    }
}
