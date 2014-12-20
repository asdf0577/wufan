<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Album\Model\Album;
use Album\Form\AlbumForm;     
use Zend\View\Model\JsonModel;
use Album\Form\InputForm;

class AlbumController extends AbstractActionController
{
protected $albumTable;
    
    public function getAlbumTable(){
	if(!$this->albumTable){
	    $sm=$this->getServiceLocator();
	    $this->albumTable=$sm->get('Album\Model\AlbumTable');
	    return $this->albumTable;
	}
}
    public function indexAction()
    {
        $paginator=$this->getAlbumTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page',1));
        $paginator->setItemCountPerPage(10);
        return new ViewModel(array(
        	'paginator'=>$paginator,
        ));
    }
    public function registerAction(){
        return new ViewModel();
    }
    public function addAction(){
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');
        $request=$this->getRequest();
         if ($request->isPost()) {
        	 $album = new Album();
        	/* $form->setInputFilter($album->getInputFilter()); */
            $post=array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray () );
        	$form->setData($post); 
            if ($form->isValid()) {
        		$validdata=$album->exchangeArray($form->getData());
        		$this->getAlbumTable()->saveAlbum($validdata);
        		if(!empty($post['isAjax'])){
        		    return new JsonModel(array(
        		    	'status'=>true,
        		        'redirect'=>$this->toRoute('album',array('action'=>'index',)),
        		        'formData'=>$validdata,
        		    ));
        		}
        		else{
        		    return $this->redirect()->toRoute('album',array('action'=>'index'));
        		}
                $sm=$this->getServiceLocator();
                $gc=$sm->get('ApplicationConfig');
                $upload_dir=$gc['upload_dir'];
                //����ͼƬ������Ƕ���ͼƬ��ע���ӡ$post����Ϣ�鿴�ļ��ϴ��ĸ�ʽ
                $requestData=(array)$post;
                move_uploaded_file($requestData['image']['tmp_name'], $upload_dir.'/'.$requestData['image']['name']);
        		// Redirect to list of albums 
        		
        	}
        	else{
        	    if(!empty($post['isAjax'])){
        	        return new JsonModel(array(
        	        	'status'=>false,
        	            'formErrors'=>$form->getMessages(),
        	            'formData'=>$form->getData(),
        	        ));
        	    }
        	} 
        	
        } 
        return array('form' => $form);
     }
    public function editAction()
     {
     	$id = (int) $this->params()->fromRoute('id', 0);
     	if (!$id) {
     		return $this->redirect()->toRoute('album', array(
     				'action' => 'add'
     		));
     	}
     
     	// Get the Album with the specified id.  An exception is thrown
     	// if it cannot be found, in which case go to the index page.
     	try {
     	  
     		$album = $this->getAlbumTable()->getAlbum($id);
     	}
     	catch (\Exception $ex) {
     		return $this->redirect()->toRoute('album', array(
     				'action' => 'index'
     		));
     	}
     
     	$form  = new AlbumForm();
        $form->bind($album); 
     	$form->get('submit')->setAttribute('value', 'Edit');
     
     	$request = $this->getRequest();
     	if ($request->isPost()) {
     	  
     		/* $form->setInputFilter($album->getInputFilter());//���������� */
     		$form->setData($request->getPost());//��ȡ�?�ύ�����
     
     		if ($form->isValid()) {
     			
     			$this->getAlbumTable()->saveAlbum($form->getData());//
     		
     			// Redirect to list of albums
     			return $this->redirect()->toRoute('album');
     		}
     	}
     
     	return array(
     			'id' => $id,
     			'form' => $form,
     	);
     }
    public function deleteAction(){
        $id=(int)$this->params()->fromRoute('id',0);
        if(!$id){
            return $this->redirect()->toRoute('album');
        }
        $request=$this->getRequest();
        if($request->isPost()){
            $del=$request->getPost('del','No');
            if($del=='Yes'){
                $id=(int)$request->getPost('id');
                $this->getAlbumTable()->delete($id);
            }
            return $this->redirect()->toRoute('album');
        }
        return array(
        	'id'=>$id,
            'album'=>$this->getAlbumTable()->getAlbum($id),
        );
    }
    public function testAction(){
        $formmanager=$this->serviceLocator->get('Zend\Form\FormElementManager');
        $formmanager->get('module\form\AlbumForm');
    }
    public function inputformAction(){
        $form=new InputForm();
        $request=$this->getRequest();
        
        if($request->isPost()){
            $data=$request->getPost();
            $form->setData($data);
            if($form->isValid()){
                $validatedData=$form->getData();
            }
            else{
                $message=$form->getMessages();
            }
        }
        return array('form'=>$form);
    }
    public function uploadProgressAction()
    {
    	$id = $this->params()->fromQuery('id', null);
    	$progress = new \Zend\ProgressBar\Upload\SessionProgress();
    	return new \Zend\View\Model\JsonModel($progress->getProgress($id));
    }
}