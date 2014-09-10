<?php
namespace Album\Model;
use Zend\Db\TableGateway\TableGateway;
use Album\Model\StoreOrder;
class StoreOrderTable
{
    
    protected $tableGateway;
    protected $productTableGateway;
    
public function __construct(TableGateway $tableGateway, TableGateway $productTableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->productTableGateway = $productTableGateway;
    }
 public function saveOrder(StoreOrder $order)
    {
        $data = array(
            'store_product_id' => $order->store_product_id, 
        	'qty' => $order->qty,        	
            'total'  => $order->total,
        	'status'  => $order->status,
        	 'first_name' => $order->first_name, 
        	 'last_name'  => $order->last_name, 
        	'email'  => $order->email,
        	'ship_to_street' => $order->ship_to_street,
        	'ship_to_city'  => $order->ship_to_city,
        );

        $id = (int)$order->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getOrder($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Order ID does not exist');
            }
        }
    }
    public function getOrder($id)
    {
    	$id=(int)$id;
    	$result=$this->tableGateway->select(array('id'=>$id));
    	$row=$result->current();
    	if(!$row)
    	{
    		throw new \Exception("Could not find row $id");
    	}
    	$productId = $row->store_product_id;
    	$productRowset = $this->productTableGateway->select(array('id' => $productId));
    	$product = $productRowset->current();
    	if(!empty($product)){
    	    $row->setProduct($product);
    	}
    	
    	return $row;
    }
   
    public function fetchAll(){
        $resultSet=$this->tableGateway->select();
        return $resultSet;
    }
    public function delete($id){
       $this->tableGateway->delete(array('id'=>$id));
              
    }
    public function getOrderByProduct($Product_id) {
    	
    	$rowset=$this->tableGateway->select(array('Product_id'=>$Product_id));
    	$row=$rowset->current();
    	if(!$row){
    	    throw new \Exception("Could not find the row $Product_id");
    	}
    	return $row;
    }
    public function getProduct($orderId)
    {
        $orderId = (int)$orderId;
        $order = $this->getOrder($orderId);
        $productId = $order0->product_id;
        $rowset = $this->productTableGateway->select(array(
        	'id' => $productId,
        ));
        $row = $rowset->current();
        if(!row)
        {
            throw new \Exception("could not find row $orderId");
        }
        return $row;
        
    }
    
    
    
}