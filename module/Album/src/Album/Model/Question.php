<?php
namespace Album\Model;
class Question {
    public $id;
    public $tid;//testPaper ID
    public $questionNum;
    public $questionType;//etc 听力、阅读、选择
    public $knowledge_id;
    public $tag;
    public $grade;
    public $edit_time;
    public $edit_count;
    public $total;
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->questionNum = (isset($data['questionNum2'])) ? $data['questionNum2'] : 1;
        $this->questionType = (isset($data['questionType'])) ? $data['questionType'] : null;
        $this->knowledge_id= (isset($data['knowledge_id'])) ? $data['knowledge_id'] : null;
        $this->tag = (isset($data['tag'])) ? $data['tag'] : null; 
        $this->grade = (isset($data['grade'])) ? $data['grade'] : null;
        
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}