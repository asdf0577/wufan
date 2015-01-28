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
use Zend\Validator\InArray;
use Album\Form\CSVUploadForm;

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
    // 添加班级
    public function addAction()
    {
        $form = new ClassManagerForm('ClassManager');

        $auth = $this->getAuthService();
        $identity = $auth->getIdentity();
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $className = new ClassName();
            $className->exchangeArray($data);
            $classNameTable = $this->getServiceLocator()->get('ClassNameTable');
            $classNameTable->saveClassName($className);
            return $this->redirect()->toRoute('ClassManager', array(
                'action' => index
            ));
            }
        $viewModel = new viewModel(array(
            'form' => $form,
            'identity'=>$identity,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
    
//     public function detailAction(){
//         $cid = (int) $this->params()->fromRoute('id');
//         if (!$cid) {
//         	return $this->redirect()->toRoute('ClassManager', array(
//         			'action' => 'index'
//         	));
//         }
//         $className =$this->getClassNameTable()->getClassName($cid);
//         $students = $this->getStudentTable()->getStudentsByClass($cid);
//         return new ViewModel(array(
//             'students'=>$students,
//             'className'=>$className,));
//     }
    // 编辑
//     public function editAction()
//     {
//         // @TODO 试题知识点联动
//         $tid = $this->params()->fromRoute('id');
//         $TestPaper = $this->getTestPaperTable()->getTestPaper($tid);
//         $Questions = $this->getQuestionTable()->getQuestions($tid);
//         /*
//          * debug::dump($TestPaper); debug::dump($Questions);
//          */
//         $form = new QuestionForm();
//         /* $form->bind($Questions); */
//         $form->get('submit')->setAttribute('value', 'Edit');
//         return new ViewModel(array(
//             'TestPaper' => $TestPaper,
//             'Questions' => $Questions,
//             'form' => $form
//         ));
//     }
    // 删除班级
    public function deleteAction()
    {   
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $password = $_POST['password'];
            $uid = $_POST['uid'];
            $sm =$this->getServiceLocator();
            $userTable = $sm->get('UserTable');
            $user = $userTable->getUser($uid);
            //校验密码，确认是用户操作
            if(md5($password ) == $user->password){
                $cid = $_POST['cid'];
                $classNameTable = $this->getClassNameTable();
                //查找班级，确认是用户名下的班级
                $class =  $classNameTable->getClassName($cid);
                if($class->uid == $uid){
                    
                    
                    $wrongQuestionUserTable = $sm->get('WrongQuestionUserTable');
                    $wrongQuestionClassTable = $sm->get('WrongQuestionClassTable');
//                     $students = $studentTable->getStudentsByClass($cid);
                    $studentTable = $this->getStudentTable();
                    $students = $studentTable->getStudentsByClass($cid);
//                     debug::dump($students);
//                     die();
                        //循环该班级的学生
                    foreach ($students as $student){
                        //删除学生错题表
                        $wrongQuestionUserTable->deleteByStudent($student['id']);
                        //删除学生
                        $studentTable->delete($student['id']);
                    }
                        //删除ACL
                    $sm->get('TestPaperAclTable')->deleteByClass($cid);
                        //删除班级错题表
                    $wrongQuestionClassTable->deleteByClass($cid);
                    $classNameTable->delete($cid);
                     echo"删除成功";
                    die();
                }else{
                    echo"班级不存在";
                    die();
                }
            }else{
                echo"密码错误";
                die();
            }
        }
    }
   
    //解除安全模式（删除班级下面的学生不再需要输入密码）
    public function safeModeAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $password = $_POST['password'];
            $uid = $_POST['uid'];
            $sm =$this->getServiceLocator();
            $userTable = $sm->get('UserTable');
            $user = $userTable->getUser($uid);
            //校验密码，确认是用户操作
            if(md5($password) == $user->password){
                echo"true";
                die();
            }
            else{
                echo "false";
                die();
            }
    }
   
  
    
}
//以CSV格式批量添加学生名单
public function csvAction(){

    $form = new CSVUploadForm('CSV');
    $request = $this->getRequest();
    if ($request->isPost()) {
        $uploadFile = $this->params()->fromFiles('CSVUpload');
        if($uploadFile){
            $studentList =array();
            $n=0;
            ini_set('auto_detect_line_endings', TRUE);
            $handle = fopen($uploadFile['tmp_name'], 'r');
            while( ($data = fgetcsv($handle,1000,","))!==false){
                for($i=0;$i<count($data);$i++){
                    $studentList[$n][$i] = iconv('gb2312', 'utf-8', $data[$i]);
                }
                $n++;
            }
            fclose($handle);
            ini_set('auto_detect_line_endings', FALSE);
            echo json_encode($studentList);
            die();
        }else{
            debug::dump("NOfiel");
            die();
        }
    }
    $view = new ViewModel(array("form"=>$form));
    $view->setTerminal(true);
    return $view;
}
}   
