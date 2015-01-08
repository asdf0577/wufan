<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Album\Form\TestPaperForm;
use Album\Model\TestPaper;
use Album\Model\QuestionType;
use Zend\debug\Debug;
use Album\Form\QuestionForm;
use Album\Form\ClassManagerForm;
use Album\Model\Question;
use Album\Model\ClassName;
use Album\Form\StudentForm;

/**
 * TestPaperController
 *
 * @author asdf0577
 *        
 * @version 0.3
 *         
 */
class LoginingController extends AbstractActionController
{

    protected $authservice;
    
    public function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('TestPaperAuthService()');
        }
        return $this->authservice;
    }
    
    // 主页
    public function indexAction()
    {
        $form = new StudentForm();
        $form->get('name')->setAttributes(array('value'=>"请输入姓名",'id'=>'name'));
        $form->get('studentNum')->setAttributes(array('value'=>"请输入学号",'id'=>'studentNum',));
        $form ->remove('id');
        $form ->remove('class');
        $form ->remove('gender');
        
       
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/testPaper2-layout');
        $viewContent = new ViewModel(array(
            'form' => $form,
        ));
        $viewContent->setTemplate('album/logining/index.phtml');
        $view->addChild($viewContent, 'content');
        return $view;
    }
   public function processAction(){
    if(!$this->request->isPost()){
        	return $this->redirect()->toRoute(Logining,array('action'=>'index'));
        }
        $post=$this->request->getPost();
        $form = new StudentForm();
        $form ->remove('id');
        $form ->remove('class');
        $form ->remove('gender');
        
        $form->setData($post);
        if($form->isValid()){
//             debug::dump($form->getData());
//             die();
            $auth = $this->getAuthService();
            $adapter = $auth->getAdapter();
            
             $adapter->setIdentity($this->request->getPost('name'))
            ->setCredential($this->request->getPost('studentNum'));
            $result=$auth->authenticate();
            if($result->isValid()){
                
                $auth->getStorage()->write($adapter->getResultRowObject());
                if($adapter->getResultRowObject()->role == 'student'){
                    return $this->redirect()->toRoute(TestPaper,array('action'=>'index'));
                }
                
            }

            else{
                //@todo 修改登陆错误语言
                foreach ($result->getMessages() as $message) {
                    echo "$message\n";
               \Zend\Debug\Debug::dump("姓名或者学号错误");
            }   
       }
    }}
    public function logoutAction(){
        $auth = $this->getAuthService();
        $auth->clearIdentity();
        return $this->redirect()->toRoute(Logining,array('action'=>'index'));
    }
    public function confirmAction(){
        $auth = $this->getAuthService();
        if($auth->hasIdentity()){
            $identity = $auth->getIdentity();
            return  array('identity'=>$identity);
        }
    }
    
    public function panelAction(){
        $auth = $this->getAuthService();
        if($auth->hasIdentity()){
            $identity = $auth->getIdentity();
            return  array('identity'=>$identity);
        }      
    }
    
}