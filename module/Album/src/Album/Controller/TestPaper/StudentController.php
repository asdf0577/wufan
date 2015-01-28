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
    // 添加学生
 
    public function addProcessAction(){ 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $Student = new Student;
            $Student->exchangeArray($data);
            $StudentTable = $this->getStudentTable();
            $result =  $StudentTable->saveStudent($Student);
            //@todo这里要加一个判断，如果成功添加学生，才进行下一步
            if(!isset($_POST['sid'])){
               $cid = $Student->cid;
                $type = 'add';
                $classTable = $this->getClassNameTable();
                $classTable->studentAmount($cid,$type);
            }
            if($result == TRUE){
            echo $result;
            }
            die();
        }
    }
    // 删除方法
    
    private function delete($sid,$cid){
        $sm = $this->getServiceLocator();
        $WrongQuestionClassTable = $sm->get('WrongQuestionClassTable');
        $WrongQuestionUserTable = $sm->get('WrongQuestionUserTable');
        //删除WrongQuestionClass中的记录
        $testPapers = $WrongQuestionUserTable->getTestPaperByUser($sid);
        if($testPapers){
            foreach ($testPapers as $testPaper){
                $questionNum = explode(",",$testPaper['qids']);
                $qids = array_filter($questionNum);
                foreach($qids as $qid){
                    $WrongQuestionClassTable->subWrongQuestionClass($testPaper['tid'],$cid,$qid,$sid);
                }
            }
            //删除WrongQuestionUser中的记录
            $WrongQuestionUserTable->deleteByStudent($sid);
        }
        //Class的学生总数-1
        $sm->get('ClassNameTable')->studentAmount($cid,"sub");
        //删除此人
        $sm->get('StudentTable')->delete($sid);
        echo"删除完成";
        die();
    }
    //删除学生
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
              if(md5($password) == $user->password){
                          $sid = $_POST['sid'];
                          if(isset($_POST['cid'])){
                              $cid = $_POST['cid'];
                          }else{
                          $studentTable = $this->getStudentTable();
                          $cid =  $studentTable->getStudent($sid)->cid;}
                          $classNameTable = $this->getClassNameTable();
                          $uid = $classNameTable->getClassName($cid)->uid;
                          //确认该用户具备资格
                          if($uid == $_POST['uid']){
                              debug::dump("success");
                             $this->delete($sid, $cid);
                          }else{
                              echo"该用户不具备删除权限" ;
                              die();
                          }
              }else{
                  echo"密码错误";
                  die();
              }
             
    }
    
    } 
   
    
    //以班级号获取班内学生名单
    public function getStudentsByClassAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $cid = $_POST['cid'];
            $uid = $_POST['uid'];
            $classNameTable = $this->getClassNameTable();
            $class = $classNameTable->getClassName($cid);
            $uidAcl = explode(',', $class->comrade);
            //如果用户的id 不在本班级的comrade中，跳出
            if(in_array($uid, $uidAcl)){
                $students = $this->getStudentTable()->getStudentsByClass($cid);
    
                echo json_encode($students);
                die();
    
            }else{
                echo "用户ID不在本班级许可范围内";
                die();
            }
        } else {
            echo "error";
        }
    }
    public function getStudentsByTestPaperAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $sids = array_filter(explode(',',$_POST['totalUser']));
            
            $studentArray = array();
            $studentTable = $this->getStudentTable();
            foreach ($sids as $key=>$sid){
                $studentArray[$key] = array(
                    'sid' => $sid,
                    'name'=> $studentTable->getStudent($sid)->name,
                );
            }
//             debug::dump($studentArray);
                echo json_encode($studentArray);
                die();
        } else {
            echo "error";
        }
    }
    
}
    
