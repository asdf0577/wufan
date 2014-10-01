<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\StoreOrder;
use Album\Form\OrderForm;
/**
 * StoreController
 *
 * @author
 *
 * @version
 *
 */
class StoreController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
   
    public function indexAction()
    {
        $storeProductTable = $this->getServiceLocator()->get('Album\Model\StoreProductTable');
        $storeProducts = $storeProductTable->fetchAll();
        $form =new OrderForm;
        $form->add(
        		array(
        				'name' => 'product_id',
        				'attributes' => array(
        						'type'  => 'hidden',
        						        				),
        		));
        
        
        
        return new ViewModel(array(
        	'storeProducts' => $storeProducts,
            'form' =>$form
        ));    }
    
  
         public function showimageAction()
        {
        	$id = $this->params()->fromRoute('id');
        	$ProductTable = $this->getServiceLocator()->get('Album\Model\StoreProductTable');
            $product = $ProductTable->getProduct($id);
        	if ($this->params()->fromRoute('subaction')=='thumb')
        	{
        		$filename = "E:/Movie/photos/".$product->thumbnail;
        
        	}
        	else{
        		$filename = "E:/Movie/photos/".$product->filename;
        	}
        	$file = file_get_contents($filename);
        
        	//Directly return the Response
            
        	$response = $this->getEvent()->getResponse();
        	$response->getHeaders  ()->addHeaders(array(
        			'Content-Type' => 'application/octet-stream',
        			'Content-Disposition' => 'attachment;filename="' .$product->filename . '"',
        
        	));
        	$response->setContent($file);
        	/* print_r($response);
        	exit(); */
        
        	return $response; 
        	 
        
        
        } 
        
        
        
    public function productDetailAction()
    {
        $sm = $this->getServiceLocator();
        $StoreProductTable = $sm->get('Album\Model\StoreProductTable');
        $id = $this->params()->fromRoute('id');
        $id2=$id+1;
        $product = $StoreProductTable->getProduct($id);
       
         $form =new OrderForm; 
         $form->add(
             array(
             		'name' => 'product_id',
             		'attributes' => array(
             				'type'  => 'hidden',
             				'value' => $product->id
             		),
         ));
        
        
        
      return new ViewModel(array(
          'Product' => $product,
          'form' => $form ,
      ))  ;
        
        
    }
    
    public function shoppingCartAction()
    {
        $sm = $this->getServiceLocator();
        $request = $this->getRequest();
        
        $productId = $request->getPost()->get('product_id');
       
        $quantity = $request->getPost()->get('qty');
        
        $orderTable = $sm->get('Album\Model\StoreOrderTable');
        $productTable = $sm->get('Album\Model\StoreProductTable');
        $product = $productTable->getProduct($productId);
        // Store Order
        $newOrder = new StoreOrder($product);
         $newOrder->setQuantity($quantity);
        
        $orderId = $orderTable->saveOrder($newOrder);
         
        $order = $orderTable->getOrder($orderId);
         $viewModel  = new ViewModel(
        		array(
        				'order' => $order,
        				'productId' => $order->getProduct()->id,
        				'productName' => $order->getProduct()->name,
        				'productQty' => $order->qty,
        				'unitCost' => $order->getProduct()->cost,
        				'total'=> $order->total,
        				'orderId'=> $order->id,
        		)
        );
        return $viewModel;     
        
    }
    
    public function paymentConfirmAction()
    {
        
    }
    
    public function paymentCancelAction()
    {
        
    }
    
   public function paypalExpressCheckoutAction()
   {
       
   }
}