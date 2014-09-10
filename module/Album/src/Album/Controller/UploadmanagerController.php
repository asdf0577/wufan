<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\RegisterForm;
use Album\Form\UploadForm;
use Album\Model\Upload;

/**
 * NewZendController
 *
 * @author
 *
 * @version
 *
 */
class UploadmanagerController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    protected  $authservice;
    protected $storage;
    
    protected function getAuthService()
    {
        if(!$this->authservice){
            $this->authservice = $this->getServiceLocator()->get('AuthService()');
        }
        return $this->authservice;
    }
    public function indexAction()
    {
    	$sm=$this->getServiceLocator();
    	
       	$userEmail= $this->getAuthService()->getStorage()->read();
    	$userTable=$sm->get('Album\Model\UserTable');
    	$user=$userTable->getUserByEmail($userEmail);
    	
    	$uploadTable=$this->getServiceLocator()->get('Album\Model\UploadTable');
    	$sharedUploads=$uploadTable->getSharedUploadsForUserId($user->id);
    	
    	  foreach($sharedUploads as $sharedUpload) {
    		$uploadOwner = $userTable->getUser($user->id);
    		$sharedUploadInfo = array();
    		$sharedUploadInfo['label'] = $sharedUpload->label;
    		$sharedUploadInfo['owner'] = $uploadOwner->name;
    		$sharedUploadsList[$sharedUpload->id] = $sharedUploadInfo;
    	} 
     
    	
    	$viewModel = new ViewModel(array(
    			'user_email' =>$userEmail,
    			'myupload'=> $uploadTable->getUploadsByUserId($user->id),
    	        'sharedUploadsList'=>$sharedUploadsList
    	     
    	));
    	return $viewModel;
    } 
    public function uploadAction(){
        $form = $this->getServiceLocator()->get('UploadForm');
        $userEmail= $this->getAuthService()->getStorage()->read();
        $form->remove('$userList');
    	return new ViewModel(array('form'=>$form,'userEmail'=>$userEmail,));
    }
    public function uploadprocessAction(){
    	$sm=$this->getServiceLocator();
    	$userEmail= $this->getAuthService()->getStorage()->read();
    	$userTable=$sm->get('Album\Model\UserTable');
    	$user=$userTable->getUserByEmail($userEmail);
    	$form=$sm->get('UploadForm') ;
    	$form->remove('userList');
    	$request=$this->getRequest();
    	if($request->isPost()){
    	    $upload= new Upload();
    	    $uploadFile=$this->params()->fromfiles('fileUpload');
    	    $post=$this->request->getPost();
    	    $form->setData($post);
    	    if($form->isValid()){
    	        $adapter = new \Zend\File\Transfer\Adapter\Http();
    	        $adapter->setDestination('E:\movie');
    	       
    	         if($adapter->receive($uploadFile['name'])){
    	             echo"good";
    	        	$exchange_data = array();
    	        	$exchange_data['label'] = $request->getPost()->get('label');
    	        	$exchange_data['filename'] = $uploadFile['name'];
    	        	$exchange_data['user_id'] = $user->id;
    	        	$upload->exchangeArray($exchange_data);
    	        	$uploadTable=$this->getServiceLocator()->get('Album\Model\UploadTable');
    	        	$uploadTable->SaveUpload($upload);
    	        	return $this->redirect()->toRoute(Null,array('controller'=>'Uploadmanager','action'=>'index'));
    	        
    	        }}}}
    public function loginAction()
    {
    	$form=$this->getServiceLocator()->get('RegisterForm');
    	return new ViewModel(array('form'=>$form));
    }
    public function processAction()
    {
    	if(!$this->request->isPost()){
    		return $this->redirect()->toRoute(Null,array('controller'=>'register','action'=>'index'));
    	}
    	$post=$this->request->getPost();
    	$form=new RegisterForm();
    	$form->remove('id');
    	$form->remove('name');
    	$form->remove('confirm_password');
    	$form->setData($post);
    
    	if(!$form->isValid()){
    		$model=new ViewModel(array('error'=>'true','form'=>$form));
    		$model->setTemplate('Album\uploadmanager\login');
    		return $model;}
    		$sm=$this->getServiceLocator();
    		$authService=$this->getAuthService();
    		$authService->getAdapter()->setIdentity($this->request->getPost('email'))
    		->setCredential($this->request->getPost('password'));
    		$result=$authService->authenticate();
    		if($result->isValid()){
    			$authService->getStorage()->write($this->request->getPost('email'));
    			return $this->redirect()->toRoute(Null,array('controller'=>'Uploadmanager','action'=>'index'));
    		}
    		else {
    			return $this->redirect()->toRoute(Null,array('controller'=>'login','action'=>'index'));
    		}
    		 
    
    }
    public function deleteAction(){
    	$id=$this->params()->fromRoute('id');
    	$uploadTable=$this->getServiceLocator()->get('Album\Model\UploadTable');
    	$file=$uploadTable->getUpload($id);
    	$filePath='E:\movie';
    	$uploadTable->deleteUpload($id);
    	unlink($filePath."/".$file->filename); 
    	return $this->redirect()->toRoute(Null,array('controller'=>'uploadmanager','action'=>'index'));
    }
    
    public function deleteshareAction()
    {
        $id=$this->params()->fromRoute('id');
        $uploadTable=$this->getServiceLocator()->get('Album\Model\UploadTable');
        $uploadTable->deleteSharing($id);
       
       return $this->redirect()->toRoute(Null,array('controller'=>'uploadmanager','action'=>'index')); 
    }
    public function processUploadSharingAction(){
        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Album\Model\UserTable');
        $uploadTable = $sm->get('Album\Model\UploadTable');
        $form = $sm->get('UploadForm');
        $request = $this->getRequest();
        if($request->isPost()){
            $userId = $request->getPost()->get('user_id');
            $uploadId = $request->getPost()->get('upload_id');
            $uploadTable->addSharing($uploadId,$userId);
            return $this->redirect()->toRoute(Null,array('controller'=>'uploadmanager','action' => 'edit','id' => $uploadId));
        }
    }
    public function editAction(){
        //È¡³öupload
        $uploadid=$this->params()->fromRoute('id');
        $sm=$this->getServiceLocator();
        $uploadTable=$sm->get('Album\Model\UploadTable');
        $userTable=$sm->get('Album\Model\UserTable');
        
        //upload edit form
        $upload=$uploadTable->getUpload($uploadid);
        $userId=$upload->user_id;
        $form=$sm->get('UploadEditForm');
        $form->bind($upload);

        //shared Users List
        $sharedUsers = array();
        $rowset=$uploadTable->getSharingUser($uploadid);
        foreach ($rowset as $sharedUserRow){
            $user = $userTable->getUser($sharedUserRow->user_id);
            $sharedUsers[$sharedUserRow->id] = $user->name;
        }
       
        //Add Sharing
        $uploadShareForm = $sm->get('UploadShareForm');
        $allUsers = $userTable->fetchAll();
        $userList = array();
        foreach ($allUsers as $user){
            $userList[$user->id] = $user->name;
        }
        echo $uploadid;
        $uploadShareForm->get('upload_id')->setValue($uploadid);
        $uploadShareForm->get('user_id')->setValueOptions($userList);
        
        return new ViewModel(array(
            'upload_id'=>$uploadid,
            'form'=>$form,
            'sharedUsers' => $sharedUsers,
            'uploadShareForm' => $uploadShareForm,));
    }
    public function editprocessAction(){
        if(!$this->request->isPost()){
        	return $this->redirect()->toRoute(Null,array('controller'=>'uploadmanager','action'=>'index'));
        }
        $post=$this->request->getPost();
        $form=$this->getServiceLocator()->get('UploadEditForm');
        $form->setData($post);
        if($form->isValid()){
            $uploadTable = $this->getServiceLocator()->get('Album\Model\UploadTable');
            $upload= new Upload();
            $data['id']=$this->params()->fromRoute('id');
            $data['label'] = $this->request->getPost()->get('label');
            $data['filename'] = $this->request->getPost()->get('filename');
            $data['user_id'] = $this->request->getPost()->get('user_id');
            $upload->exchangeArray($data);
            $uploadTable->SaveUpload($upload);
            
            return $this->redirect()->toRoute(Null,array('controller' => 'uploadmanager','action' => 'index'));
        }
        
    }
    public function fileDownloadAction()
    {
        $uploadId = $this->params()->fromRoute('id');
        $uploadTable = $this->getServiceLocator()->get('Album\Model\UploadTable');
        $upload = $uploadTable->getUpload($uploadId);
        
        $file = file_get_contents("E:/Movie/".$upload->filename);
        
        $response = $this->getEvent()->getResponse();
        $response->getHeaders()->addHeaders(array(
        	'Content-Type' => 'application/octet-stream',
            'content-Disposition' => 'attachment;filename="'.$upload->filename.'"',
        ));
        $response->setContent($file);
        return  $response;
    }
}