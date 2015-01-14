<?php
namespace Album\Model;

use Album\Model\wrong_question_user;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
class WrongQuestionUserTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
  
    public function getQuestionTypes($fid)
    {
        $fid=(int)$fid;
        $result=$this->tableGateway->select(array('fid'=>$fid));
        $rowset=$result->toArray();
        return $rowset;
    }
    
    public function getQuestionUser($tid,$sid){
    //查询是否存在记录
        $result = $this->tableGateway->select(function (select $select) use ($tid,$sid){
            //问题错在这里
           $select->where(array(
                'tid'=>$tid,
                'sid'=>$sid,
            ));
        })->current();
        if($result){
            return $result;
        }
    }
    public function getQuestionUserByClass($tid,$cid){
    //查询是否存在记录
        $result = $this->tableGateway->select(function (select $select) use ($tid,$cid){
            //问题错在这里
            $select->where(array(
                'tid'=>$tid,
                'cid'=>$cid,
            ))->columns(array('sid',));
        })->toArray();
        if($result){
            return $result;
        }else{
            return null;
        }
    }
    
    public function getQuestionType($id){
        $id = (int)$id;
        $row = $this->tableGateway->select(array('id' =>$id))->current();
        if(!$row){ throw new \Exception("Form id does not exist");}
        return $row;
    }
    public function saveWrongQuestion($question)
    {
        $data = array(
            'tid' => $question->tid,
            'cid' => $question->cid,
            'sid' => $question->sid,
            'qid' => $question->qid,
            'submit_time'=>date('Y-m-d H:i:s'),
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
    public function delete($id){
        $id = (int)$id;
        $this->tableGateway->delete(array('id'=>$id));
    }
    
    
    
}