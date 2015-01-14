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
//        debug::dump($data);
//        die();
      
            $this->tableGateway->insert($data);  
     
        
    }
    
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
//             debug::dump($result->id);
//             debug::dump($result->total);
//             debug::dump($result->total_user);
//             die();
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
    
    
    
    public function delete($id){
        $id = (int)$id;
        $this->tableGateway->delete(array('id'=>$id));
    }
    
    
    
}