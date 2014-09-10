<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\ImageUpload;
use Zend\Stdlib\ArrayUtils;
use ZendGData\HttpClient;
use ZendGdata\Photos;
use ZendGdata\ClientLogin;
/**
 * MediaManagerController
 *
 * @author
 *
 * @version
 *
 */
class MediaController extends AbstractActionController
{

    protected $storage;
    protected $authservice;
    protected $photos;
    const GOOGLE_USER_ID = 'asdf0577@hotmail.com';
    const GOOGLE_PASSWORD = 'zerg0571,./';
    /**
     * The default action - show the home page
     */
    
    public function getAuthservice()
    {
        if(!$this->authservice)
        {
            $this->authservice = $this->getServiceLocator()->get('AuthService()');
        }
        return $this->authservice;
    }
    
    public function getFileUploadLocation()
    {
        $config = $this->getServiceLocator()->get('config');
        if($config instanceof Traversable)
        {
            $config = ArrayUtils::iteratorToArray($config); //转换一个iterator 到 array
        }
        if(!empty($config['module_config']['image_upload_location']))
        {
            return $config['module_config']['image_upload_location'];
        }
        else 
        {
            return FALSE;
        }
    }
    
    public function indexAction()
    {
         $uploadTable = $this->getServiceLocator()->get('Album\Model\ImageUploadTable');
         $userTable = $this->getServiceLocator()->get('Album\Model\UserTable');
         $userEmail = $this->getAuthservice()->getStorage()->read();
         $user = $userTable->getUserByEmail($userEmail);
         echo "welcome :". $userEmail;
         return new ViewModel(array(
             'myUploads' => $uploadTable->getUploadByUserId($user->id),
         ));
    }
    
    public function generateThumbnail($imageFileName)
    {
        $uploadPath = 'E:/movie/photos';
        $sourceImageFileName = $uploadPath. '/' .$imageFileName;
        $thumbnailFileName = 'tn_' . $imageFileName;
        echo $sourceImageFileName;
        
        
        
        $imageThumb = $this->getServiceLocator()->get('WebinoImageThumb');
        $thumb = $imageThumb->create($sourceImageFileName,$options=array());
        $thumb->resize(75,75);
        $thumb->save($uploadPath. '/' .$thumbnailFileName);
        
        return $thumbnailFileName;
        
    }
    
    public function processuploadAction()
    {
        $userTable = $this->getServiceLocator()->get('Album\Model\UserTable');
        $userEmail = $this->getAuthservice()->getStorage()->read();
        $user = $userTable->getUserByEmail($userEmail);
        $form = $this->getServiceLocator()->get('ImageUploadForm');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $upload = new ImageUpload();
            $uploadFile = $this->params()->fromFiles('imageUpload');
            $form->setData($request->getPost());
            
            if($form->isValid())
            {
                $uploadPath = $this->getFileUploadLocation();
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('E:\movie\photos');
                
                if ($adapter->receive($uploadFile['name']))
                {
                     $exchange_data = array();
                    $exchange_data['label'] = $request->getPost()->get('label');
                    $exchange_data['filename'] = $uploadFile['name'];
                    $exchange_data['thumbnail'] = $this->generateThumbnail($uploadFile['name']);
                    $exchange_data['user_id'] = $user->id;

                    $upload->exchangeArray($exchange_data);
                    
                    $uploadTable = $this->getServiceLocator()->get('Album\Model\ImageUploadTable');
                    $uploadTable->saveUpload($upload);
                    
                    return $this->redirect()->toRoute('media'); 
                }
            }
        }
    }
    
    public function uploadAction()
    {
        $form = $this->getServiceLocator()->get('ImageUploadForm');
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;  
    }
    
    
    public function showImageAction()
    {
        $uploadId = $this->params()->fromRoute('id');
        $uploadTable = $this->getServiceLocator()->get('Album\Model\ImageUploadTable');
        $upload = $uploadTable->getUpload($uploadId);
        
        
        if ($this->params()->fromRoute('subaction')=='thumb')
          {
           $filename = "E:/Movie/photos/".$upload->thumbnail;
          
        }
        else{
            $filename = "E:/Movie/photos/".$upload->filename;
        } 
        $file = file_get_contents($filename);
        
        //Directly return the Response
        
        $response = $this->getEvent()->getResponse();
        $response->getHeaders()->addHeaders(array(
        		'Content-Type' => 'application/octet-stream',
        		'Content-Disposition' => 'attachment;filename="' .$upload->filename . '"',
        
        ));
        $response->setContent($file);
        
        return $response;
       
        
        
    }
    
    public function viewAction()
    {
        $uploadId = $this->params()->fromRoute('id');
        $uploadTable = $this->getServiceLocator()->get('Album\Model\ImageUploadTable');
        $upload = $uploadTable->getUpload($uploadId);
        return new ViewModel(array('upload' => $upload));
    }
    
  
        
        
    }
