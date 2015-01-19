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
use Zend\Validator\InArray;

class QuestionController extends AbstractActionController
{

    protected $TestPaperTable;

    protected $QuestionTable;

    protected $QuestionTypeTable;

    protected $authservice;
    
    protected $WrongQuestionClassTable;
    
    protected $WrongQuestionUserTable;
    
    public function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('TestPaperAuthService()');
        }
        return $this->authservice;
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
    
    public function getWrongQuestionUserTable()
    {
        if (! $this->WrongQuestionUserTable) {
            $this->WrongQuestionUserTable = $this->getServiceLocator()->get('WrongQuestionUserTable');
            return $this->WrongQuestionUserTable;
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
                if($TestPaperID['status']==1){
                $TestPapers[$key] =  $testPaperTable->getTestPaper($TestPaperID['tid']);}
            }
            return array('TestPapers'=>$TestPapers,'identity'=>$identity,);
        
        }    
    
    }
        
//教师查看成绩统计页面
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
    
    
    
    //创建以班级为单位的错题列表，@todo 为ACL 加入班级名
    public function createAclAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $tid = $_POST['tid'];
            $string = $_POST['cid'];
            $cids = explode(',', $string);

//             debug::dump($cids);
//             debug::dump($tid);
//             die();
            $sm = $this->getServiceLocator();
            $testPaper = $sm->get('TestPaperTable')->getTestPaper($tid);
            $classNameTable = $sm->get('ClassNameTable');
            $wrongQusetionClass = new WrongQuestionClass();
            $wrongQusetionClassTable = $this->getWrongQuestionClassTable();
            $testPaperAcl = new TestPaperAcl();
            $testPaperAclTable = $sm->get('TestPaperAclTable');
            
            $aclData = array(
            'tid'=>$tid,
            'uid'=>$_POST['uid'],   
            );
            $data= array(
                'tid' => $tid,
            );
            
            foreach ($cids as $cid){
                for($i=1;$i<=$testPaper->questionAmount;$i++){
                    $data['cid'] = $cid;
                    $data['question_num'] = $i;
                    $wrongQusetionClass->exchangeArray($data);
                    $wrongQusetionClassTable->createQuestionClass($wrongQusetionClass);
                }
                $class_name = $classNameTable->getClassName($cid);
                $aclData['cid'] = $cid;
                $aclData['class_name'] = $class_name['year']."年-".$class_name['name'];
                $testPaperAcl->exchangeArray($aclData);
//                 debug::dump($testPaperAcl);
//                 die();
                $testPaperAclTable->addTestPaperAcl($testPaperAcl);
                
            }
            echo"添加ACL成功";
            die();
//          return $this->redirect()->toRoute(TestPaper,array('action'=>'index'));
        }
    }
    
    public function changeAclStatusAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $tid = $_POST['tid'];
            $uid = $_POST['uid'];
            if(isset($_POST['classCheck'])){
                $cids = $_POST['classCheck'];
            }else{
                $cids=array();
            }
            $sm = $this->getServiceLocator();
            $testPaperAclTable = $sm->get('TestPaperAclTable');
            $results = $testPaperAclTable->getTestPaperByTestPaper($uid,$tid);
            $addCid = array();
            foreach ($results as $key=>$value){
                if($value['status']==0){
                $addCid[$key] = $value['cid'];}
            }
            //交集
            $addArray = array_intersect($cids, $addCid);
            if(!empty($addArray)){
            foreach ($addArray as $cid){
                $testPaperAclTable->changeAclStatus($status=1,$cid,$tid);
            }
            }
            $subCid = array();
            foreach ($results as $key=>$value){
                if($value['status']==1){
                    $subCid [$key] = $value['cid'];}
            }
            $subArray = array_diff($subCid, $cids);
            
            if(!empty($subArray)){
            foreach ($subArray as $cid){
                $testPaperAclTable->changeAclStatus($status=0,$cid,$tid);
            }
            }
            echo"success";
            die();
        
        }
    }
    
    public function deleteAclAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $tid = $_POST['tid'];
            $uid = $_POST['uid'];
            $string = $_POST['cid'];
            $cids = explode(',', $string);
            $sm = $this->getServiceLocator();
            
            $wrongQusetionClassTable = $this->getWrongQuestionClassTable();
            $wrongQusetionUserTable = $this->getWrongQuestionUserTable();
            $testPaperAclTable = $sm->get('TestPaperAclTable');
    
            foreach ($cids as $cid){
                //删除学生记录
                $wrongQusetionUserTable->deleteByClassAndTestPaper($cid,$tid);
                //删除班级记录
                $wrongQusetionClassTable->deleteByClassAndTestPaper($cid,$tid);
                //删除ACL
                $testPaperAclTable->deleteByClassAndTestPaper($cid,$tid,$uid);
                
        }
            echo"删除ACL成功";
            die();
    }
    
    }
    
    
    //问题提交功能
    public function submitAction()
    {
        //获取身份判断
        $auth = $this->getAuthService();
        $identity = $auth->getIdentity();
        $role = $identity->role;
        
        
        //获取试卷再获取试题
        $tid = (int) $this->params()->fromRoute('id');
        $testPaper = $this->getTestPaperTable()->getTestPaper($tid); // 根据tid获取试卷
        $questions = $this->getQuestionTable()->getQuestions($tid); // 根据tid获取试题
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
            $studentsArray = $this->intersect($cid, $tid);           
            $viewContent = new ViewModel(array(
            'questionNames' => $questionNames,
            'testPaper' => $testPaper,
            'questions' => $questions,
            'students'=> $studentsArray,
            'form'=>$form,
        ));
            $viewContent->setTemplate('album/question/student-manager-submit');
            
            //如果是超级管理员、教师，获取班级列表    
                if (in_array($role, array(
                    'supmanager',
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
        //siderbar
        $viewSidebar = new ViewModel(array(
            'questionNames' => $questionNames
        ));
        $viewSidebar->setTemplate('album/test-paper/sidebar');
        
        $view->addChild($viewContent, 'content');
        $view->addChild($viewSidebar, 'sidebar');
        return $view;
    }
    
    
    
 
    
    public function insertQuestionClass($tid,$sid,$qid,$cid){
        if(!isset($cid)){
            $cid = $this->getServiceLocator()->get('StudentTable')->getStudent($sid)->cid;
        }
        
       
    }
  
    public function addProcessAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            //获取班级id,试卷id，学生id， 
            $tid = $_POST['tid']; //testPaper_id
            $sid = $_POST['sid']; //student_id
            
            //先判断是否存在该试卷该学生错题记录
            $WrongQuestionUserTable = $this->getWrongQuestionUserTable();
            
            $result = $WrongQuestionUserTable->getQuestionUser($tid,$sid);
            $sm = $this->getServiceLocator();
                if(!$result){
                    //如果不存在，获取提交的错题编号、班级
                    $cid = $sm->get('StudentTable')->getStudent($sid)->cid;
                     //question_num_id
                    $inputQuestions = $_POST['inputQuestions'];
                    $nums = explode(",", $inputQuestions);
                    //获取班级错题table，在班级错体表中更新数据
                    $WrongQuestionClassTable = $this->getWrongQuestionClassTable();
                    for($i=0;$i<sizeof($nums)-1;$i++){
                        $WrongQuestionClassTable->addWrongQuestionClass($tid, $cid,$nums[$i], $sid);
                    }
                    //获取学生错题表
                    $data = array(
                        'sid' => $sid,
                        'qids' => $inputQuestions,
                        'tid' => $tid,
                        'cid'=>$cid,
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
    public function updateProcessAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            //获取班级id,试卷id，学生id， 
            $sm = $this->getServiceLocator();
            $tid = $_POST['tid']; //testPaper_id
            $sid = $_POST['sid']; //student_id
            $cid = $sm->get('StudentTable')->getStudent($sid)->cid;
            $inputQuestions = $_POST['inputQuestions'];
            $WrongQuestionUserTable = $this->getWrongQuestionUserTable();
            $result = $WrongQuestionUserTable->getQuestionUser($tid,$sid);
           
                    $inputArray = explode(",", $inputQuestions);
                    $oldData = explode(",", $result->qids);
                    $addArray = array_diff($inputArray, $oldData);
                    $subArray = array_diff($oldData, $inputArray);
                    $WrongQuestionClassTable = $sm->get('WrongQuestionClassTable')   ;
                    if(!empty($addArray)){
                        for($i=0;$i<sizeof($addArray);$i++){
                            $WrongQuestionClassTable->addWrongQuestionClass($tid, $cid,$addArray[$i], $sid);
                        }
                        echo"增加成功";
                    }
                    if(!empty($subArray)){
                       
                        foreach ($subArray as $question_num){
                            $WrongQuestionClassTable->subWrongQuestionClass($tid, $cid, $question_num, $sid);
                        }
                        echo"删除成功";
                    }
                    $WrongQuestionUserTable->update($tid,$cid,$inputQuestions,$sid);
                    die();
            }
            }

    
    //获取已经提交过答案名单的同学
    public function intersect($cid,$tid){
        //获取提交过错误名单同学列表，获取同班学生列表
        $wrongQuestionUser = $this->getWrongQuestionUserTable()->getQuestionUserByClass($tid,$cid);
        $students = $this->getServiceLocator()->get('StudentTable')->getStudentsByClass($cid);
        $studentsArray = array();
        //如果在错误列表中存在该班级同学，则为输出的学生名单标记提交属性为1，未提交为0
        if($wrongQuestionUser){
            //找出错题列表中的学生名单，组成一个集合
            $wrongUsers = array();
            for($i=0;$i<count($wrongQuestionUser);$i++){
                $wrongUsers[$i] = $wrongQuestionUser[$i]['sid'];
            }
            
           //考虑使用array_intersect 和 array_diff
            //循环全班学生列表，如果该学生在错题名单中，则设置提交属性为1
            foreach ($students as $student){
                    if(in_array($student['id'], $wrongUsers)){
                        $studentsArray[$student['id']]= array(
                            'id'=>$student['id'],
                            'name'=>$student['name'],
                            'submit'=>1,
                        );
                    } 
                    //如果不在，如果该学生不在错题名单中，则设置提交属性为0
                    else{
                        $studentsArray[$student['id']]= array(
                            'id'=>$student['id'],
                            'name'=>$student['name'],
                            'submit'=>0,);
                    }
    }}else{
        foreach ($students as $student){
            $studentsArray[$student['id']]= array(
                'id'=>$student['id'],
                'name'=>$student['name'],
                'submit'=>0,);
            
        }
        
    }
    return $studentsArray;
            }
            
        //获取学生    
    public function getStudentsAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $cid = $_POST['cid'];
            $tid = $_POST['tid'];
            $studentArray = $this->intersect($cid, $tid);
            echo json_encode($studentArray);
            die();
        } else {
            echo "error";
        }
    }
    
     public function getQuestionDataAction(){

         $request = $this->getRequest();
         if ($request->isPost()) {
             $sid = $_POST['sid'];
             $tid = $_POST['tid'];
             $questionData = $this->getWrongQuestionUserTable()->getQuestionData($tid,$sid);
             $question = explode(",", $questionData['qids']);
             //删除最后一个元素
             array_pop($question);
//              debug::dump($question);
//              debug::dump($questionData);
//              debug::dump($questionData);
             echo json_encode($question);
             die();
         } else {
             echo "error";
         }
     }
    
}
