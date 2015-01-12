<?php
namespace Album\Model;

use Album\Model\TestPaper;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
class ClassNameTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll(){
        $resultSet = $this->tableGateway->select()->toArray();
        return $resultSet;
    }
    
    public function getClassByTeacher($uid){
      $resultSet = $this->tableGateway->select(array('uid'=>$uid))->toArray();
        return $resultSet;
    }
    
    
    public function getClassName($id){
        $id=(int)$id;
        $result=$this->tableGateway->select(array('id'=>$id));
        $row=$result->current();
        if(!$row)
        {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function saveClassName($className)
    {
        $data = array(
            'name'=>$className->name,
            'year'=>$className->year,//如何转换格式
            'class_type'=>$className->classType,
            'student_amount'=>0,
            'create_time'=>time(),
            
        );
        $id = (int)$className->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }else{
            if($this->getClassName($id)){
                $updateData = array(
                		'name'=>$className->name,
                		'studentAmout'=>$className->studentAmout,
                		'updateTime'=>date(),
                
                );
                
                $this->tableGateway->update($updateData,array('id' => $id));
            }else 
            {
                throw new \Exception("Form id does not exist");
            }
        }
    }
    public function delete($tid){
    	$tid = (int)$tid;
    	$this->tableGateway->delete(array('id'=>$tid));
    }
    
    public function studentAmount($cid,$type){
        $cid=(int)$cid;
        $result=$this->getClassName($cid);
       /*  debug::dump($result["student_amount"]+2);
        die(); */
        if($type == 'add'){
         $result =array('student_amount' => $result["student_amount"]+1,);}
         elseif($type =='sub'){
         $result =array('student_amount' => $result["student_amount"]-1,);;
         }
        $this->tableGateway->update( $result,array('id' => $cid));   
    }
    
}