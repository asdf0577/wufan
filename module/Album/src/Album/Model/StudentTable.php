<?php
namespace Album\Model;

use Album\Model\Student;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
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
       $result = $this->tableGateway->select(function (select $select) use ($cid){
//             //问题错在这里
            $select->where(array(
                'cid'=>$cid,
            ))->order('studentNum ASC');
        })->toArray();
        return $result;
        
        
    }
    public function saveStudent($Student)
    {
        $gender = $Student->gender;
        switch ($gender){
            case "男":$gender=1;break;
            case "女":$gender=2;break;
        }
        $data = array(
                'name'=>$Student->name,            
                'gender'=>$gender,            
                'cid'=>$Student->cid,            
                'studentNum'=>$Student->studentNum,            
                'role'=>$Student->role,            
                'password'=>md5($Student->studentNum),            
        );
        $id = (int)$Student->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }else{
            if($this->getStudent($id)){
                $this->tableGateway->update($data,array('id' => $id));
            }else 
            {
                throw new \Exception("该id不存在");
            }
        }
    }
    public function delete($id){
    	$id = (int)$id;
    	$this->tableGateway->delete(array('id'=>$id));
    }
    
    
    
}