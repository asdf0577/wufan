<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\User;
use Zend\Authentication\AuthenticationService;
class UsermanagerController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
      $UserTable=$this->getServiceLocator()->get('Album\Model\UserTable');
      
        $users=$UserTable->fetchAll();
       /*  print($users); */
        return new ViewModel(array('users'=>$users));
        
    }
    public function addAction(){
        $form=$this->getServiceLocator()->get('registerForm');
        $form->setInputFilter($this->getServiceLocator()->get('registerFilter'));
        return new ViewModel(array('form'=>$form));
    }
    public function addProcessAction(){
        if(!$this->request->isPost()){
            return $this->redirect()->toRoute(Null,array('controller'=>'register','action'=>'index'));
        }
        $post=$this->request->getPost();
        $form=$this->getServiceLocator()->get('RegisterForm'); 
        $form->setData($post);
        echo"helloworld";
        /* if(!$form->isValid()){
            ;
            $model=new ViewModel(array('error'=>true,'form'=>$form));
            
            return $model;
        } */
        if($form->isValid())
        {
            echo"helloworld2";
        $data=$form->getData();
        $user=new User();
        $user->exchangeArray($data);
        $this->getServiceLocator()->get('Album\Model\UserTable')->saveUser($user);
        return $this->redirect()->toRoute(null,array('controller'=>'usermanager','action'=>'index'));
        }
    }
    public function editAction(){
        
        $id=$this->params()->fromRoute('id');
        $userTable=$this->getServiceLocator()->get('Album\Model\UserTable');
        $form=$this->getServiceLocator()->get('UserEditForm');
        $form->get('submit')->setAttribute('value', 'edit');
        $user=$userTable->getUser($id);
        $form->bind($user); 
        
      
        return new ViewModel(array('user_id'=>$id,'form'=>$form));
        
                
         }
    public function processAction(){
       $post = $this->request->getPost();
       $userTable = $this->getServiceLocator()->get('Album\Model\UserTable');
       $id=$this->params()->fromRoute('id');
        $user = $userTable->getUser($this->params()->fromRoute('id')); 
       $form = $this->getServiceLocator()->get('UserEditForm');
        $form->bind($user); 
       $form->setData($post);
       if($form->isvalid()){
       $this->getServiceLocator()->get('Album\Model\UserTable')->updateUser($form->getData(),$id);
       return $this->redirect()->toRoute(Null,array('controller'=>'Usermanager','action'=>'index'));} 
    }
    public function deleteAction(){
        $id=$this->params()->fromRoute('id');
        $userTable=$this->getServiceLocator()->get('Album\Model\UserTable');
        $userTable->delete($id);
        return $this->redirect()->toRoute(Null,array('controller'=>'Usermanager','action'=>'index'));
    }
    public function panelAction(){
    
    $auth= new AuthenticationService();
    if($auth->hasIdentity()){
        return new ViewModel(array('identity'=>$auth->getIdentity()));
    }
}
}