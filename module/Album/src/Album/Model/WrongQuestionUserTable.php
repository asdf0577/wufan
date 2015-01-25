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
           $select->where(array(
                'tid'=>$tid,
                'sid'=>$sid,
            ));
        })->current();
        if($result){
            return $result;
        }
    }
    public function getQuestionData($tid,$sid){
    //查询是否存在记录
        $result = $this->tableGateway->select(function (select $select) use ($tid,$sid){
           $select->where(array(
                'tid'=>$tid,
                'sid'=>$sid,
            ))->columns(array('qids'));;
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
    
    public  function getTestPaperByUser($sid){
        $result = $this->tableGateway->select(array('sid'=>$sid))->toArray();
        return $result;
    }
    
    
    
    //保存记录
    public function saveWrongQuestion($question)
    {
        $data = array(
            'tid' => $question->tid,
            'cid' => $question->cid,
            'sid' => $question->sid,
            'qids' => $question->qids,
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
    //更新记录
    public function update($tid,$cid,$qids,$sid){
    
        $result = $this->tableGateway->select(function (select $select) use ($tid,$cid,$sid){
            //问题错在这里
            $select->where(array(
                'tid'=>$tid,
                'cid'=>$cid,
                'sid'=>$sid,
    
            ));
             
        })->current();
        if($result){
            $this->tableGateway->update(array(
                'qids'=>$qids,
                'edit_time'=>date('Y-m-d H:i:s'),
            ),array('id'=>$result->id));
            return"更新成功";
        }
        else{
            throw  new \Exception("id does not exist") ;
        }
    
    }
    
    //根据学生id删除记录
    public function deleteByStudent($sid){
        $sid = (int)$sid;
        $this->tableGateway->delete(array('sid'=>$sid));
    }
    
    public function deleteByTestPaper($tid){
        $tid = (int)$tid;
        $this->tableGateway->delete(array('tid'=>$tid));
    }
    
    public function deleteByClassAndTestPaper($cid,$tid){
        $tid = (int) $tid;
        $cid = (int) $cid;
        $this->tableGateway->delete(array(
            'tid' => $tid,
            'cid'=>$cid,
        ));
    }
    
}