<?php
namespace Album\Model;

use Album\Model\QuestionType;
use Zend\Db\TableGateway\TableGateway;

class QuestionTypeTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll(){
        $result = $this->tableGateway->select();
        $rowset = $result->toArray();
        return $rowset;
    }
    public function getQuestionTypes($fid)
    {
        $fid=(int)$fid;
        $result=$this->tableGateway->select(array('fid'=>$fid));
        $rowset=$result->toArray();
        return $rowset;
    }
    public function getQuestionType($id){
        $id = (int)$id;
        $row = $this->tableGateway->select(array('id' =>$id))->current();
        if(!$row){ throw new \Exception("Form id does not exist");}
        return $row;
    }
    public function saveQuestionType($questionType)
    {
        
        $data = array(
            'fid' => $questionType->fid,
            'name' => $questionType->tname,
            'path' => $questionType->path,
            
        );
        $id = (int)$questionType->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }else{
            if($this->getquestion($id)){
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