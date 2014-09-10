<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\RegisterForm;
use Album\Form\registerFilter;
/**
 * LoginController
 *
 * @author
 *
 * @version
 *
 */
class LoginController extends AbstractActionController
{
    protected $authservice;//这行代码是这个意思
    
    public function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('AuthService()');
        }
        return $this->authservice;
    } 
    
  public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        
        return $this->redirect()->toRoute('album/login');
    }
    public function indexAction(){
        $form= new RegisterForm();
        $form->get('email')->setAttributes(array('value'=>"Pls input the email address",
                                                  'id'=>'email'));
        $form->get('password')->setAttributes(array('value'=>"Pls input the email address",
        		'id'=>'password'));
        $form->remove('id');
        $form->remove('name');
        $form->remove('confirm_password');
        return new ViewModel(array('form'=>$form));
    }

    
    public function processAction(){
        if(!$this->request->isPost()){
        	return $this->redirect()->toRoute(Null,array('controller'=>'register','action'=>'index'));
        }
        $post=$this->request->getPost();
        $form=new RegisterForm();
        $form->remove('id');
        $form->remove('name');
        $form->remove('confirm_password');
        /* $inputFilter=new registerFilter();
        $inputFilter->remove('name');
        $inputFilter->remove('confirm_password');
        $form->setInputFilter($inputFilter); */
        $form->setData($post);
        if($form->isValid()){
            /* $columns = array('email'=> $this->request->getPost('email'),'password'=>md5($this->request->getPost('password'))); */
       $this->getAuthService()->getAdapter()->setIdentity($this->request->getPost('email'))
                                            ->setCredential($this->request->getPost('password'));
       $result=$this->getAuthService()->authenticate();
              
       if($result->isValid()){
        
       $this->getAuthService()->getStorage()->write($this->request->getPost('email')); 
       return $this->redirect()->toRoute(Null,array('controller'=>'login','action'=>'confirm'));
            }
            
          else{
          
            return $this->redirect()->toRoute(Null,array('controller'=>'login','action'=>'index')); }   }}
            

    
        
    
    public function confirmAction(){
       $email=$this->getServiceLocator()->get('AuthService()')->getStorage()->read();
        $viewModel= new ViewModel(array('email'=>$email));
        return $viewModel; 
    }
    
}