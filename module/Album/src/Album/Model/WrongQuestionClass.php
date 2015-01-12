<?php
namespace Album\Model;
class WrongQuestionClass {
    public $id;
    public $cid;//class_id
    public $tid;//testPaper_id
    public $qid;//question_id
    public $question_num;//question_num
    public $total;//total num of wrong question
    public $total_user;//total num of wrong question

    public function exchangeArray($data)
    {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->cid = (isset($data['cid'])) ? $data['cid'] : null;
        $this->qid = (isset($data['qid'])) ? $data['qid'] : null;
        $this->question_num = (isset($data['question_num'])) ? $data['question_num'] : null;
        $this->total = (isset($data['total'])) ? $data['total'] : null;
        $this->total_user = (isset($data['total_user'])) ? $data['total_user'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}