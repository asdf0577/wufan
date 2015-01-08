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
use Album\Form\KnowledgeForm;
/**
 * TestPaperController
 *
 * @author
 *
 * @version
 *
 */
class TestPaperController extends AbstractActionController
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
    
    // 主页
    public function indexAction()
    {
        $auth = $this->getAuthService();
        if($auth->hasIdentity()){
            $identity = $auth->getIdentity();
            $TestPapers = $this->getTestPaperTable()->fetchAll();
            
            return array(
                'TestPapers' => $TestPapers,
                'identity'=>$identity,
            );
        
        }
        else{
            return $this->redirect()->toRoute(Logining,array('action'=>'index'));
            }
        
    }
    // 添加试卷
    public function addAction()
    {
        
        /* 通过下拉列表1选择考试科目后读取该科目下的题型 */
        $questionType = $this->getQuestionTypeTable()->getQuestionTypes(0);
        $questionTypeArray = array();
        // 将获取的题型从二维数组转一维数组
        foreach ($questionType as $Type) {
            $questionTypeArray[$Type['id']] = $Type['name'];
        }
        // 实例化一个新的TestPaper 表格，并把题型一维数组放入下拉列表2中
        $form = new TestPaperForm('TestPaper');
        
        $form->get('testPaperType')->setValueOptions($questionTypeArray); // 在這裏注入 select的 option
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $testPaper = new TestPaper();
            $form->setValidationGroup('year', 'termNum', 'unitNum', 'questionAmount');
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                // @TODO 试题类型存入数据库
                $validData = $form->getData();
                $validData['QuestionTypeInput'] = $_POST['QuestionTypeInput'];
                $testPaper->exchangeArray($validData);
                $testPaperTable = $this->getTestPaperTable();
                $testPaperTable->saveTestPaper($testPaper);
                return $this->redirect()->toRoute('TestPaper', array(
                    'action' => index
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTerminal(true);
        return $view;
    }
    // 获取试题类型
    public function getTypesAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $fid = $_POST['fid'];
            $questionTypeTable = $this->getServiceLocator()->get('questionTypeTable');
            $questionType = $questionTypeTable->getQuestionTypes($fid);
            echo json_encode($questionType);
            die();
        } else {
            echo "error";
        }
    }

    public function getKnowledgeTypeAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $fid = $_POST['fid'];
            $KnowledgeType = $this->getKnowledgeTable()->getKnowledges($fid);
            echo json_encode($KnowledgeType);
            die();
        } else {
            echo "error";
        }
    }
    // 创建试题
    public function createAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $TestPaperTable = $this->getTestPaperTable();
        $testPaper = $TestPaperTable->getTestPaper($id);
        $question = new Question();
        if ($testPaper->created == "0") {
            $total = $testPaper->questionAmount;
            $a = $testPaper->questionType;
            $b = explode(',', $a); // 分离出题目的格式“题型：起始数-终止数，题型：起始数-终止数”
            $count = count($b); // 计算题型总数
            for ($i = 0; $i < $count - 1; $i ++) { // 这里要减1，
                list ($type, $num) = explode(":", $b[$i]); // 分离出“题型和起始数-终止数”
                list ($start, $end) = explode("-", $num); // 分离出“起始数和终止数”
                for ($qstart = $start; $qstart <= $end; $qstart ++) {
                $questionData = array(
                    'tid'=>$id,
                    'questionNum'=>$qstart,
                    'questionType'=>$type,
                    
                );
                
                
                $question->exchangeArray($questionData);
                $QuestionTable = $this->getServiceLocator()->get('QuestionTable'); // Ϊʲô���ﲻ���á�
                $QuestionTable->saveQuestions($question);
                }}
               
            
            $testPaper->created = 1;
            $TestPaperTable->saveTestPaper($testPaper);
            return $this->redirect()->toRoute('TestPaper');
        } else {
            echo "This TestPaper has been created by（） on（）, please do not create it twice ";
            die();
        }
    }
    
    // 编辑试题
    public function editAction()
    
    {
        $tid = $this->params()->fromRoute('id');
        $TestPaper = $this->getTestPaperTable()->getTestPaper($tid);
        $Questions = $this->getQuestionTable()->getQuestions($tid);
        $questionTypeTable = $this->getQuestionTypeTable(); // 根据tid获取试题
                                                            // debug::dump($Questions);
        
        for ($i = 0; $i < count($Questions); $i ++) {
            if(!$Questions[$i]['knowledge_id']){
                $Questions[$i]['knowledgeName'] = "点击选择知识点";
                $Questions[$i]['knowledgeCN_Name'] = "";
            }else{
            $id = $Questions[$i]['knowledge_id'];
            $ktable = $this->getServiceLocator()->get('KnowledgeTable');
            $knowledge = $ktable->getKnowledge($id);
            $Questions[$i]['knowledgeName'] = $knowledge->name;
            $Questions[$i]['knowledgeCN_Name'] = $knowledge->cn_name;}
        }
        // 获取该试卷的试题类型
        // 解读试题类型
        $questionType = $TestPaper->questionType;
        $typeList = explode(',', $questionType);
        $count = count($typeList);
        $questionNames = array();
        for ($i = 0; $i < $count - 1; $i ++) {
            list ($type, $numInfo) = explode(":", $typeList[$i]);
            $questionNames[$i] = $questionTypeTable->getQuestionType($type)->name;
        }
        
        $form = new QuestionForm("question");
        /* $form->bind($Questions); */
        $form->get('submit')->setAttribute('value', 'Edit');
        
        /* 通过下拉列表1选择考试科目后读取该科目下的题型 */
        $Knowledges = $this->getKnowledgeTable()->getKnowledges(0);
        $KnowledgeArray = array();
        // 将获取的题型从二维数组转一维数组
        foreach ($Knowledges as $Type) {
            $conjure = "";
            $conjure = $Type['name'] . "-" . $Type['cn_name'];
            $KnowledgeArray[$Type['id']] = $conjure;
            $conjure = "";
        }
        $form2 = new KnowledgeForm('KnowledgeForm');
        
        $form2->get('knowledgeType')->setValueOptions($KnowledgeArray);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/testPaper2-layout');
        $viewContent = new ViewModel(array(
            'TestPaper' => $TestPaper,
            'Questions' => $Questions,
            'form' => $form,
            'form2' => $form2
        ));
        $viewContent->setTemplate('album/test-paper/edit');
        $viewSidebar = new ViewModel(array(
            'questionNames' => $questionNames
        ));
        $viewSidebar->setTemplate('album/test-paper/sidebar');
        $view->addChild($viewContent, 'content');
        $view->addChild($viewSidebar, 'sidebar');
        return $view;
    }
    // 编辑处理
    public function editprocessAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
//             debug::dump($_POST['questionNum2']);
//             die();
            $validQuestion = array(
                'id' => (int) $_POST['id'],
                'questionNum' => (int) $_POST['questionNum2'],
                'grade' => (int) $_POST['grade'],
                'knowledge_id' => (int) $_POST['knowledge_id']
                
            // @todo content tag etc.
                        );
            $question = new Question();
            $question->exchangeArray($validQuestion);
            $QuestionTable = $this->getServiceLocator()->get('QuestionTable');
            $results = $QuestionTable->update($question);
            echo json_encode($results);
            die();
            // $result = $QuestionTable->saveQuestions($question);
            // echo $result;
//             return $this->redirect()->toRoute('TestPaper', array(
//                 'action' => index)
//             );
        }
    }
    // 试卷题型
    public function QuestionTypeAction()
    {
        $QuestionTypeTable = $this->getQuestionTypeTable();
        $QuestionType = $QuestionTypeTable->fetchAll();
        debug::dump($QuestionType);
        die();
        return new ViewModel();
    }
    // 删除试题
    public function deleteAction()
    {
        $tid = (int) $this->params()->fromRoute('id');
        $this->getQuestionTable()->delete($tid);
        $this->getTestPaperTable()->delete($tid);
        return $this->redirect()->toRoute('TestPaper');
    }
    
    /*
     * public function washAction() { $form = new ClassManagerForm(); $form->get('name')->setLabel('手机号'); $form->get('classType')->setLabel('汽车颜色'); $color = array( '1'=>'紅', '2'=>'黄', '3'=>'蓝', ); $form->get('classType')->setvalueoptions($color); $form->get('submit')->setValue('预约洗车 '); return new ViewModel(array( 'form' => $form )); }
     */
    // 创建试题类型
    public function createTypeAction()
    {
        // @TODO 创建试题类型
        $questionTypeTable = $this->getServiceLocator()->get('questionTypeTable');
        $questionType = $questionTypeTable->getQuestionTypes(0);
        $questionTypeArray = array();
        foreach ($questionType as $Type) {
            $questionTypeArray[$Type['id']] = $Type['name'];
        }
        
        $form = new TestPaperForm('QuestionType');
        $form->get('testPaperType')->setValueOptions($questionTypeArray);
        $form->remove('termNum');
        $form->remove('unitNum');
        $form->remove('questionAmount');
        $form->remove('questionTypeSelect');
        $form->remove('questionType');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $QuestionType = new QuestionType();
                $QuestionType->exchangeArray($form->getData());
                /*
                 * debug::dump($QuestionType); die();
                 */
                $QuestionTypeTable = $this->getServiceLocator()->get('QuestionTypeTable');
                $QuestionTypeTable->saveQuestionType($QuestionType);
                echo "succesful";
                die();
            }
        }
        
        return new viewmodel(array(
            'form' => $form
        ));
    }

    public function detailAction()
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
        
        
        
        //创建答题提交表格 
        
//         debug::dump($identity);
//         debug::dump($students);
//         die();
        
        $view = new ViewModel(array(
            'questionNames' => $questionNames,
            'testPaper' => $testPaper,
            'questions' => $questions,
            'students'=>$students,
            'form'=>$form,
        ));
        return $view;
    }
}
    
