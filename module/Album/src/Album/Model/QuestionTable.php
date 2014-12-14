<?php
namespace Album\Model;

use Album\Model\Question;
use Zend\Db\TableGateway\TableGateway;

class QuestionTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function getQuestions($tid)
    {
        $tid=(int)$tid;
        $result=$this->tableGateway->select(array('tid'=>$tid));
        $rowset=$result->toArray();
        return $rowset;
    }
    public function getquestion($id){
        $id = (int)$id;
        $row = $this->tableGateway->select(array('id' =>$id));
        if(!$row){ throw new \Exception("Form id does not exist");}
        return $row;
    }
    public function saveQuestions($question)
    {
        $data = array(
            'tid' => $question->tid,
        	'questionNum' => $question->questionNum,
        	'grammaType' => $question->grammaType,
            'content' => $question->content,
            'grade' => $question->grade,
            
        );
        $id = (int)$question->id;
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
    public function delete($tid){
        $tid = (int)$tid;
        $this->tableGateway->delete(array('tid'=>$tid));
    }
    
    
    
}