<?php
namespace Album\Model;
use Zend\Db\TableGateway\TableGateway;
use Album\Model\User;
class UserTable{
    protected $tableGateway;
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway=$tableGateway;
    }
    public function saveUser( $user){
        $data=array(
            'email'=>$user->email,
            'name'=>$user->name,
            'password'=>$user->password,
            'time_reg'=>date('Y-m-d H:i:s'),
        );  
        $id=(int)$user->id;
        if($id==0){
            $this->tableGateway->insert($data);}
        else{
            if($this->getUser($id)){
                $data2=array(
                		'email'=>$user->email,
                		'name'=>$user->name,
                		
                );
            $this->tableGateway->update($data,array('id'=>$id));}
            else{
                throw new \Exception("could not find the row $id");
            }
            
        }
    }
   public function updateUser($post,$id){
      
    $this->tableGateway->update(array(
       	'name'=>$post->name,
        'email'=>$post->email,   
       ),
        array('id'=>$id));
   }
    public function getUser($id)
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
    public function getUserByEmail($userEmail) {
    	
    	$rowset=$this->tableGateway->select(array('email'=>$userEmail));
    	$row=$rowset->current();
    	if(!$row){
    	    throw new \Exception("Could not find the row $userEmail");
    	}
    	return $row;
    }
    
    
    
    
}