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
class TestPaperController extends AbstractActionController
{

    protected $TestPaperTable;

    protected $QuestionTable;

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
    //主页
    public function indexAction()
    {
        $TestPapers = $this->getTestPaperTable()->fetchAll();
        return new ViewModel(array(
            'TestPapers' => $TestPapers
        ));
    }
    //添加试卷
    public function addAction()
    {
        
        /* 通过下拉列表1选择考试科目后读取该科目下的题型 */
        $questionTypeTable = $this->getServiceLocator()->get('questionTypeTable');
        $questionType = $questionTypeTable->getQuestionTypes(0);
        $questionTypeArray = array();
        // 将获取的题型从二维数组转一维数组
        foreach ($questionType as $Type) {
            $questionTypeArray[$Type['id']] = $Type['name'];
        }
        // 实例化一个新的TestPaper 表格，并把题型一维数组放入下拉列表2中
        $form = new TestPaperForm('TestPaper');
        $multiOptions = array(
            '选择科目' => $questionTypeArray
        );
        
        $form->get('testPaperType')->setMultiOptions($multiOptions); // 在這裏注入 select的 option
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
        $viewModel = new viewModel();
        $viewModel->setVariables(array(
            'form' => $form
        ));
        return $viewModel;
    }
    //获取试题类型
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
    //创建试题
    public function createAction()
    {
        // @TODO 试题知识点联动
        $id = (int) $this->params()->fromRoute('id');
        $testPaper = $this->getTestPaperTable()->getTestPaper($id);
        if ($testPaper->created == "0") {
            $form = new QuestionForm();
            
            return new ViewModel(array(
                'testPaper' => $testPaper,
                'form' => $form
            ));
        } else {
            echo "This TestPaper has been created , please don't create it twice ";
            die();
        }
    }
    //编辑试题
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
    //创建处理
    public function processAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new QuestionForm();
            $question = new Question();
            $questionNum = $_POST['questionNum'];
            $grammaType = $_POST['grammaType'];
            $content = $_POST['content'];
            $grade = $_POST['grade'];
            $tid = $_POST['tid'];
            $items = array();
            for ($i = 0; $i < count($questionNum); $i ++) {
                $items[$i] = array(
                    "tid" => $_POST['tid'],
                    "questionNum[]" => $questionNum[$i],
                    "grammaType[]" => $grammaType[$i],
                    "content[]" => $content[$i],
                    "grade[]" => $grade[$i]
                );
            }
            /*
             * debug::dump($items); die();
             */
            foreach ($items as $item) {
                $form->setData($item);
                if ($form->isValid()) {
                    
                    $question->exchangeArray($form->getData());
                    /*
                     * debug::dump($question); die();
                     */
                    $QuestionTable = $this->getServiceLocator()->get('QuestionTable'); // Ϊʲô���ﲻ���á�
                    $QuestionTable->saveQuestions($question);
                } else {
                    echo "wrong";
                    die();
                }
            }
            $id = $tid;
            $TestPaperTable = $this->getTestPaperTable();
            $TestPaper = $TestPaperTable->getTestPaper($id);
            $TestPaper->created = 1;
            $TestPaperTable->saveTestPaper($TestPaper);
            
            return $this->redirect()->toRoute('TestPaper');
        }
    }
    //编辑处理
    public function editprocessAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new QuestionForm();
            $question = new Question();
            $questionNum = $_POST['questionNum'];
            $grammaType = $_POST['grammaType'];
            $content = $_POST['content'];
            $grade = $_POST['grade'];
            $tid = $_POST['tid'];
            $items = array();
            for ($i = 0; $i < count($questionNum); $i ++) {
                $items[$i] = array(
                    "id" => $_POST['id'],
                    "tid" => $tid,
                    "questionNum[]" => $questionNum[$i],
                    "grammaType[]" => $grammaType[$i],
                    "content[]" => $content[$i],
                    "grade[]" => $grade[$i]
                );
            }
            foreach ($items as $item) {
                $form->setData($item);
                if ($form->isValid()) {
                    
                    $question->exchangeArray($form->getData());
                    /*
                     * debug::dump($question); die();
                     */
                    $QuestionTable = $this->getServiceLocator()->get('QuestionTable');
                    $QuestionTable->saveQuestions($question);
                    return $this->redirect()->toRoute(Null, array(
                        'controller' => 'TestPaper',
                        'action' => 'edit',
                        'id' => $tid
                    ));
                }
            }
        }
    }
    // 试卷题型
    public function QuestionTypeAction()
    {
        $QuestionTypeTable = $this->getServiceLocator()->get('QuestionTypeTable');
        $QuestionType = $QuestionTypeTable->fetchAll();
        debug::dump($QuestionType);
        die();
        return new ViewModel();
    }
    //删除试题
    public function deleteAction()
    {
        $tid = (int) $this->params()->fromRoute('id');
        $this->getQuestionTable()->delete($tid);
        $this->getTestPaperTable()->delete($tid);
        return $this->redirect()->toRoute('TestPaper');
    }

    public function washAction()
    {
        $form = new QuestionForm();
        
        return new ViewModel(array(
            'form' => $form
        ));
    }
    //创建试题类型
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
}
    
