<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\debug\Debug;
use Album\Model\ClassName;
use Album\Model\Student;
use Album\Form\CSVUploadForm;

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
 
    public function addProcessAction(){ 
        $request = $this->getRequest();
        if ($request->isPost()) {
//            
            
            $data = $request->getPost();
            $Student = new Student;
            $Student->exchangeArray($data);
//             debug::dump($Student);
//             die();
           
            $StudentTable = $this->getStudentTable();
            $StudentTable->saveStudent($Student);
            //@todo这里要加一个判断，如果成功添加学生，才进行下一步
            if(!isset($_POST['sid'])){
               $cid = $Student->cid;
                $type = 'add';
                $classTable = $this->getClassNameTable();
                $classTable->studentAmount($cid,$type);
            }
            echo"添加完成";
            die();
        }
    }
    // 删除
    
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
    public function deleteAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
              $password = $_POST['password'];
              $uid = $_POST['uid'];
              $studentTable = $this->getStudentTable();
              $user =  $studentTable->getStudent($uid);
              //校验密码，确认是用户操作
              if(md5($password) == $user->password){
                          $sid = $_POST['sid'];
                          if(isset($_POST['cid'])){
                              $cid = $_POST['cid'];
                          }else{
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
    
