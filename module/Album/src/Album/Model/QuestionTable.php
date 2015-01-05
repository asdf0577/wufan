<?php
namespace Album\Model;

use Album\Model\Question;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
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
        $row = $this->tableGateway->select(array('id' =>$id))->current();
        if(!$row){ throw new \Exception(" id does not exist");}
        return $row;
    }
    public function saveQuestions($question)
    {
        $data = array(
            'tid' => $question->tid,
        	'questionNum' => $question->questionNum,
        	'knowledge_id' =>$question->knowledge_id,
            'tag' => $question->tag,
            'grade' => $question->grade,
            
        );
        $id = (int)$question->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }else{
            if($this->getquestion($id)){
                $data['edit_time'] = date('Y-m-d H:i:s');
                  $this->tableGateway->update($data,array('id' => $id));
                
            }else 
            {
                throw new \Exception("Form id does not exist");
            }
        }
    }
    
    public function update($question){
        $id = (int)$question->id;
        $data = array(
        		'tid' => $question->tid,
        		'questionNum' => $question->questionNum,
        		'knowledge_id' =>$question->knowledge_id,
        		'tag' => $question->tag,
        		'grade' => $question->grade,
                'edit_time'=>date('Y-m-d H:i:s')
        );
        $this->tableGateway->update($data,array('id' => $id));
    }
    public function delete($tid){
        $tid = (int)$tid;
        $this->tableGateway->delete(array('tid'=>$tid));
    }
    
    
    
}