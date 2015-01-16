<?php
namespace Album\Model;

use Album\Model\wrong_question_user;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\debug;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
class WrongQuestionClassTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getQuestionClassByTestPaper($tid)
    {
        $tid=(int)$tid;
        $result=$this->tableGateway->select(array('tid'=>$tid));
        $rowset=$result->toArray();
        return $rowset;
    }
    //创建记录
    public function createQuestionClass($question)
    {
        $data = array(
            'tid' => $question->tid,
            'cid' => $question->cid,
            'qid' => $question->qid,
            'question_num' => $question->question_num,
            'total' => $question->total,
            'total_user' => $question->total_user,
        );
      
            $this->tableGateway->insert($data);  
     
        
    }
    //更新记录
    public function updateWrongQuestionClass($tid,$cid,$question_num,$sid){
      
        $result = $this->tableGateway->select(function (select $select) use ($tid,$cid,$question_num,$sid){
            //问题错在这里
            $select->where(array('tid'=>$tid,
                                 'cid'=>$cid,
                                 'question_num'=>$question_num,
                                
            ));
           
        })->current();
        if($result){
            $total = $result->total;
            $total = $total+1;
            $total_user = $result->total_user.",".$sid;
            $this->tableGateway->update(array(
                'total_user'=>$total_user,
                'total'=>$total,
            ),array('id'=>$result->id));
        }
        else{
            throw  new \Exception("id does not exist") ;
        }
        
    }
    
    
    //删除该试卷下所有记录
    public function deleteByTestPaper($tid){
        $id = (int)$id;
        $this->tableGateway->delete(array('tid'=>$tid));
    }
    //删除该试卷以班级为单位的所有记录
    public function deleteByClassAndTestPaper($cid,$tid){
        $tid = (int) $tid;
        $cid = (int) $cid;
        $this->tableGateway->delete(array(
            'tid' => $tid,
            'cid'=>$cid,
        ));
    }
    
    
    
}