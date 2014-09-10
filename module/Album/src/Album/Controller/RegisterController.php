<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\User;


/**
 * registerController
 *
 * @author
 *
 * @version
 *
 */
class RegisterController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
         $form=$this->getServiceLocator()->get('RegisterForm'); 
          return new ViewModel(array('form'=>$form) );
    }
    public function processAction(){
        if(!$this->request->isPost()){
            return $this->redirect()->toRoute(Null,array('controller'=>'register','action'=>'index'));
        }
        $post=$this->request->getPost();
        $form=$this->getServiceLocator()->get('RegisterForm'); 
        $form->setData($post);
        if(!$form->isValid()){
            $model=new ViewModel(array('error'=>true,'form'=>$form));
            $model->setTemplate('album/register/index');
            return $model;
        }
        $this->createUser($form->getData());
        return $this->redirect()->toRoute(null,array('controller'=>'register','action'=>'confirm'));
    }
    public function confirmAction(){
        return $this->redirect()->toRoute(Null,array('controller'=>'login','action'=>'index'));
    }
    public function createUser(array $data){
       
        $user=new User();
        $user->exchangeArray($data);
        $userTable=$this->getServiceLocator()->get('\Album\Model\UserTable');
        $userTable->saveUser($user);
        return true;
    }
}