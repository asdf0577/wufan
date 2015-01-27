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
use Zend\Validator\InArray;
use Album\Model\User;

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
    protected $tAuthservice; 
    
    public function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('TestPaperAuthService()');
        }
        return $this->authservice;
    }
    
    public function getTAuthService()
    {
        if(!$this->tAuthservice){
            $this->tAuthservice = $this->getServiceLocator()->get('AuthService()');
            $this->tAuthservice = $this->getServiceLocator()->get('AuthService()');
        }
        return $this->tAuthservice;
    }
    
   
    
    
    
    
    
    
    // 主页
    public function indexAction()
    {
        $form = new StudentForm("student");
        $form->get('name')->setAttributes(array('value'=>"请输入姓名",'id'=>'name'));
        $form->get('studentNum')->setAttributes(array('value'=>"请输入密码",'id'=>'studentNum',));
        $form ->remove('id');
        $form ->remove('class');
        $form ->remove('gender');
        $registerForm=$this->getServiceLocator()->get('RegisterForm');
       
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/testPaper2-layout');
        $viewContent = new ViewModel(array(
            'form' => $form,
            'registerForm'=>$registerForm,
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
                
                $role = $adapter->getResultRowObject()->role;
                
                
                if(in_array($role,array('student','manager','supmanager'))){
                    return $this->redirect()->toRoute(Question,array('action'=>'index'));
                }
                if($role == 'teacher'){
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
    public function registerAction(){
        if($this->request->isPost()){
            $post=$this->request->getPost();
//             debug::dump($post);
//             die();
            $form=$this->getServiceLocator()->get('RegisterForm');
            $form->setData($post);
            if(!$form->isValid()){
                $model=new ViewModel(array('error'=>true,'form'=>$form));
                $model->setTemplate('album/register/index');
                return $model;
            }
            $user=new User();
            $user->exchangeArray($form->getData());
//             debug::dump($user);
//             die();
            $userTable=$this->getServiceLocator()->get('UserTable');
            $userTable->saveUser($user);
            return $this->redirect()->toRoute(Null,array('controller'=>'logining','action'=>'index'));
            
        }else{
            return $this->redirect()->toRoute(Null,array('controller'=>'logining','action'=>'index'));
        }
    }
    
    
    public function teacherLoginAction(){
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
            $auth = $this->getTAuthService();
            $adapter = $auth->getAdapter();
    
            $adapter->setIdentity($this->request->getPost('name'))
            ->setCredential($this->request->getPost('studentNum'));
            $result=$auth->authenticate();
            if($result->isValid()){
    
    
    
                $auth->getStorage()->write($adapter->getResultRowObject());
    
                $role = $adapter->getResultRowObject()->role;
    
                if(in_array($role,array('student','manager','supmanager'))){
                    return $this->redirect()->toRoute(Question,array('action'=>'index'));
                }
                if($role == 'teacher'){
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
}