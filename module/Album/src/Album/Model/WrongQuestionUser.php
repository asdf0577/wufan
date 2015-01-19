<?php
namespace Album\Model;
class WrongQuestionUser {
    public $id;
    public $sid;//student_id
    public $cid;//student_id
    public $tid;//testPaper_id
    public $qids;//wrong_question_ids
    public $submit_time;//testPaper_id
    public $edit_time;//testPaper_id
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->cid = (isset($data['cid'])) ? $data['cid'] : null;
        $this->sid = (isset($data['sid'])) ? $data['sid'] : null;
        $this->qids = (isset($data['qids'])) ? $data['qids'] : null;
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}