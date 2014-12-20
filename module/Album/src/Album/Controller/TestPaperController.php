<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\TestPaperForm;
use Album\Model\TestPaper;
use Zend\debug\Debug;
use Album\Form\QuestionForm;
use Album\Model\Question;

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
    
    public function getTestPaperTable(){
        if(!$this->TestPaperTable){
            $this->TestPaperTable = $this->getServiceLocator()->get('TestPaperTable');
            return $this->TestPaperTable;
        }
    }
    public function getQuestionTable(){
        if(!$this->QuestionTable){
        	$this->QuestionTable = $this->getServiceLocator()->get('QuestionTable');
        	return $this->QuestionTable;
        }
    }
    public function indexAction()
    {
        $TestPapers = $this->getTestPaperTable()->fetchAll();
        return new ViewModel(array(
        	'TestPapers'=>$TestPapers,
        ));
    }
    public function addAction()
    {
       /* 增加试卷  */
        $questionTypeTable = $this->getServiceLocator()->get('questionTypeTable');
        $questionType = $questionTypeTable->getQuestionTypes(0);
        $questionTypeArray = array();
        //二维数组转一维数组
            foreach ($questionType as $Type){
               $questionTypeArray[$Type['id']] = $Type['name'];
            }   
        $form = new TestPaperForm();
        $form->get('testPaperType')->setValueOptions($questionTypeArray);// 在這裏注入 select的 option 
        $request = $this->getRequest();
        
        if($request->isPost()){
            debug::dump($request->getPost());
            die();
            $testPaper = new TestPaper();
            
            $form->setData($request->getPost());
            
            if($form->isValid()){
                // TODO 试题类型存入数据库
               /*  $testPaper->exchangeArray($form->getData());
                debug::dump($testPaper);
                die();  
                $testPaperTable = $this->getTestPaperTable();
                $testPaperTable->saveTestPaper($testPaper);
                
                return $this->redirect()->toRoute('TestPaper'); */
            }
            
        }
        $viewModel = new viewModel();
        $viewModel->setVariables(array('form' => $form));
        return $viewModel;
    }
    
    public function getTypesAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $fid = $_POST['fid'];
            $questionTypeTable = $this->getServiceLocator()->get('questionTypeTable');
            $questionType = $questionTypeTable->getQuestionTypes($fid);
            echo json_encode($questionType);
            die();
        }
        else{
            echo "error";
        }
    }
    
    public function createAction(){
        // TODO 试题知识点联动
        $id = (int)$this->params()->fromRoute('id');
        $testPaper = $this->getTestPaperTable()->getTestPaper($id);
        if($testPaper->created == "0"){
        $form = new QuestionForm();
        
       
        return new ViewModel(array(
            'testPaper'=>$testPaper,
            'form'=>$form,
        ));}
        else{
            echo "This TestPaper has been created , please don't create it twice ";
            die();
        }  
    }
    public function editAction(){
        // TODO 试题知识点联动
        $tid = $this->params()->fromRoute('id');
        $TestPaper = $this->getTestPaperTable()->getTestPaper($tid);
        $Questions = $this->getQuestionTable()->getQuestions($tid);
        /* debug::dump($TestPaper);
        debug::dump($Questions); */
        $form = new QuestionForm();
       /*  $form->bind($Questions);  */
        $form->get('submit')->setAttribute('value', 'Edit');
        return new ViewModel(array(
            'TestPaper' =>$TestPaper,
            'Questions'=>$Questions,
            'form'=>$form,));
    }
    public function processAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $form = new QuestionForm();
        	$question = new Question();
        	$questionNum = $_POST['questionNum'];
        	$grammaType = $_POST['grammaType'];
        	$content = $_POST['content'];
        	$grade = $_POST['grade'];
            $tid = $_POST['tid'];
        	$items = array();
        	for($i=0;$i<count($questionNum);$i++){
        		$items[$i] = array(
        				"tid"=> $_POST['tid'],
        				"questionNum[]" => $questionNum[$i],
        				"grammaType[]" => $grammaType[$i],
        				"content[]" => $content[$i],
        				"grade[]" => $grade[$i],
        		);
        	}
        	/* debug::dump($items);
        	die(); */
        	foreach ($items as $item){
        		$form->setData($item);
        		if($form->isValid()){
        		    
        			$question->exchangeArray($form->getData());
        		/*  	debug::dump($question);
        		 	die(); */
        			$QuestionTable = $this->getServiceLocator()->get('QuestionTable');//Ϊʲô���ﲻ���á�
          			$QuestionTable->saveQuestions($question);
        			
        		
        		}
        		else{
        			echo"wrong";
        			die();
        		    
        		}
        		 
        	}
        	$id = $tid;
        	$TestPaperTable = $this->getTestPaperTable();
            $TestPaper = $TestPaperTable->getTestPaper($id);
            $TestPaper->created = 1 ;
            $TestPaperTable->saveTestPaper($TestPaper);
            

        	return $this->redirect()->toRoute('TestPaper');  
        	
        
        }
    } 
    public function editprocessAction(){
        $request = $this->getRequest();
        if($request->isPost()){
        	$form = new QuestionForm();
        	$question = new Question();
        	$questionNum = $_POST['questionNum'];
        	$grammaType = $_POST['grammaType'];
        	$content = $_POST['content'];
        	$grade = $_POST['grade'];
            $tid = $_POST['tid'];
        	$items = array();
        	for($i=0;$i<count($questionNum);$i++){
        		$items[$i] = array(
        				"id"=>$_POST['id'],
        				"tid"=> $tid,
        				"questionNum[]" => $questionNum[$i],
        				"grammaType[]" => $grammaType[$i],
        				"content[]" => $content[$i],
        				"grade[]" => $grade[$i],
        		);
        	}
        	foreach ($items as $item){
        		$form->setData($item);
        		if($form->isValid()){
        
        			$question->exchangeArray($form->getData());
        			/*
        			 debug::dump($question);
        			die();   */
        			$QuestionTable = $this->getServiceLocator()->get('QuestionTable');
        			$QuestionTable->saveQuestions($question);
        			return $this->redirect()->toRoute(Null,array('controller'=>'TestPaper','action'=>'edit','id'=>$tid));
        			
        		}
        	}
        
        }
    }
    //试卷题型
    public function QuestionTypeAction(){
        $QuestionTypeTable = $this->getServiceLocator()->get('QuestionTypeTable');
        $QuestionType = $QuestionTypeTable->fetchAll();
        debug::dump($QuestionType);
        die();
        return new ViewModel();
    }
    public function a(){
    
    }
    public function deleteAction(){
    	$tid = (int)$this->params()->fromRoute('id');
    	$this->getQuestionTable()->delete($tid);
    	$this->getTestPaperTable()->delete($tid);
    	return $this->redirect()->toRoute('TestPaper');
    }
    public function washAction(){
    	$form = new QuestionForm();
    
    	return new ViewModel(array('form'=>$form,));
    
    }
    public function testAction(){
    	return new viewmodel();
    }
    public function dragAction(){
    	return new viewmodel();
    }
    public function createQuestionTypeAction(){
    	// @TODO 创建试题类型
    }
    }
    
