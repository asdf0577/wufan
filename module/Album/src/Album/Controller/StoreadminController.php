<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\StoreProduct;

/**
 * StoreadminController
 *
 * @author
 *
 * @version
 *
 */
class StoreadminController extends AbstractActionController
{
    
   
    

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated StoreadminController::indexAction() default action
        return new ViewModel();
    }

    public function addProductAction()
    {
    	$form = $this->getServiceLocator()->get('ProductForm');
    	return new ViewModel(array(
    			'form' => $form
    	));
    }
    public function deleteProductAction()
    {
    }
    
    public function processuploadAction()
    {   
        $sm = $this->getServiceLocator();
        $form = $sm->get('ProductForm');
        $request= $this->getRequest();
        if($request->isPost())
        {
            $product = new StoreProduct();
           
            $uploadFile = $this->params()->fromFiles('image');
            $data = $request->getPost();
            $form->setData($request->getPost());
          
            if($form->isValid())
            {
                
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination('E:\movie\photos');
              
            if ($adapter->receive($uploadFile['name']))
                {
                   
                    $exchange_data = array();
                    $exchange_data['name'] = $request->getPost()->get('name');
                    $exchange_data['filename'] = $uploadFile['name'];
                    $exchange_data['thumbnail'] = $this->generateThumbnail($uploadFile['name']); 
                    $exchange_data['small'] = $this->generateSmall($uploadFile['name']); 
                    $exchange_data['desc'] = $request->getPost()->get('desc');
                    $exchange_data['cost'] = $request->getPost()->get('cost');

                    $product->exchangeArray($exchange_data);
                     
                    $productTable = $sm->get('Album\Model\StoreProductTable');
                    
                    $productTable->saveStoreProduct($product);    
                    return $this->redirect()->toRoute('store'); 
                }
            }
        }
        
    }
    public function generateThumbnail($imageFileName)
    {
    	$uploadPath = 'E:/movie/photos';
    	$sourceImageFileName = $uploadPath. '/' .$imageFileName;
    	$thumbnailFileName = 'tn_' . $imageFileName;
    	$imageThumb = $this->getServiceLocator()->get('WebinoImageThumb');
    	$thumb = $imageThumb->create($sourceImageFileName,$options=array());
    	$thumb->resize(75,75);
    	$thumb->save($uploadPath. '/' .$thumbnailFileName);
    	return $thumbnailFileName;
    
    }
    public function generateSmall($imageFileName)
    {
        
    	$uploadPath = 'E:/movie/photos';
    	$sourceImageFileName = $uploadPath. '/' .$imageFileName;
    	$smallFileName = $imageFileName.'_small';
    	$imageThumb = $this->getServiceLocator()->get('WebinoImageThumb');
    	$thumb = $imageThumb->create($sourceImageFileName,$options=array());
    	$thumb->resize(150,150);
    	$thumb->save($uploadPath. '/' .$smallFileName);
    	return $smallFileName;
    
    } 
    
    
}