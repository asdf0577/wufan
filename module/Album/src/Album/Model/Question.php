<?php
namespace Album\Model;
class Question {
    public $id;
    public $tid;
    public $questionNum;
    public $questionType;
    public $grammaType;
    public $content;
    public $grade;
    public $total;
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->questionNum = (isset($data['questionNum[]'])) ? $data['questionNum[]'] : null;
        $this->questionType = (isset($data['questionType'])) ? $data['questionType'] : null;
        $this->grammaType = (isset($data['grammaType[]'])) ? $data['grammaType[]'] : null;
        $this->content = (isset($data['content[]'])) ? $data['content[]'] : null; 
        $this->grade = (isset($data['grade[]'])) ? $data['grade[]'] : null;
        
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}