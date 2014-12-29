<?php
namespace Album\Model;

use Album\Model\Student;
use Zend\Db\TableGateway\TableGateway;

class StudentTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getStudent($id){
        $id=(int)$id;
        $result=$this->tableGateway->select(array('id'=>$id));
        $row=$result->current();
        if(!$row)
        {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function getStudentsByClass($cid){
        $cid=(int)$cid;
        $resultSet=$this->tableGateway->select(array('cid'=>$cid));
        return $resultSet;
    }
    public function saveStudent($Student)
    {
        $data = array(
                'name'=>$Student->name,            
                'gender'=>$Student->gender,            
                'cid'=>$Student->cid,            
                'studentNum'=>$Student->studentNum,            
                'password'=>md5($Student->studentNum),            
        );
        $id = (int)$Student->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }else{
            if($this->gettestPaper($id)){
                $this->tableGateway->update($data,array('id' => $id));
            }else 
            {
                throw new \Exception("Form id does not exist");
            }
        }
    }
    public function delete($id){
    	$id = (int)$id;
    	$this->tableGateway->delete(array('id'=>$id));
    }
    
    
    
}