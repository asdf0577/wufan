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
    public function getQuestionClassByTestPaperAndClass($tid,$cid)
    {
        $tid=(int)$tid;
        $cid=(int)$cid;
        $result = $this->tableGateway->select(function (select $select) use ($tid,$cid)
         {
            $select->where(array('tid'=>$tid,
                'cid'=>$cid,
            ));
         })->toArray();
        
        
        return $result;
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
    //增加记录
    public function addWrongQuestionClass($tid,$cid,$question_num,$sid){
      
        $result = $this->tableGateway->select(function (select $select) use ($tid,$cid,$question_num,$sid){
            //问题错在这里
            $select->where(array('tid'=>$tid,
                                 'cid'=>$cid,
                                 'question_num'=>$question_num,
                                
            ));
           
        })->current();
        if($result){
//             debug::dump($result);
//             die();
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
    public function subWrongQuestionClass($tid,$cid,$question_num,$sid){
        $result = $this->tableGateway->select(function (select $select) use ($tid,$cid,$question_num){
            $select->where(array('tid'=>$tid,
                                 'cid'=>$cid,
                                 'question_num'=>$question_num,
            ));
        })->current();
        if($result){
            $total = $result->total;
            $total = $total-1;
            $sids = $result->total_user;    
            $repalceSid = ",".$sid;     
            $length = strlen($repalceSid);//计算将要替换字符的长度
            $count=strpos($sids,$repalceSid);//找到将要替换字符的位置
            $total_user=substr_replace($sids,"",$count,$length);//字符串，将要替换的内容，开始的位置，将要替换的长度
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
        $tid = (int)$tid;
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
    //删除该班级下的所有试题记录
    public function deleteByClass($cid){
        $cid = (int) $cid;
        $this->tableGateway->delete(array(
            'cid'=>$cid,
        ));
    }
    
    
    
}