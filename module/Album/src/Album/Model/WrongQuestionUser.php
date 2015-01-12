<?php
namespace Album\Model;
class WrongQuestionUser {
    public $id;
    public $sid;//student_id
    public $tid;//testPaper_id
    public $qid;//testPaper_id
    public $submit_time;//testPaper_id
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->sid = (isset($data['sid'])) ? $data['sid'] : null;
        $this->qid = (isset($data['qid'])) ? $data['qid'] : null;
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}