<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class StoreProductTable
{
    protected $tableGateway;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveStoreProduct(StoreProduct $storeProduct){
    	$data=array(
    			'name'=>$storeProduct->name,
    			'desc'=>$storeProduct->desc,
    			'cost'=>$storeProduct->cost,
    			'filename'=>$storeProduct->filename,
    			'thumbnail'=>$storeProduct->thumbnail,
    			'small'=>$storeProduct->small,
    	    
    	);
    	$id=(int)$storeProduct->id;
    	if($id==0){
    		$this->tableGateway->insert($data);}
    		else{
    			if($this->getOrder($id)){
    	            $this->tableGateway->update($data,array('id'=>$id));}
    				else{
    					throw new \Exception("could not find the row $id");
    				}
    
    		}
    }
    public function getProduct($id)
    {
    	$id=(int)$id;
    	$result=$this->tableGateway->select(array('id'=>$id));
    	$row=$result->current();
    	if(!$row)
    	{
    		throw new \Exception("Could not find row $id");
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
    public function getProductByName($name) {
    	 
    	$rowset=$this->tableGateway->select(array('name'=>$name));
    	$row=$rowset->current();
    	if(!$row){
    		throw new \Exception("Could not find the row $name");
    	} 
    	return $row;
    }
    
    
    
    
    
    
    
    
}