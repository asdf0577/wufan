<?php
namespace Album\Model;
class TestPaperAcl
{
    public $id;
    public $tid;
    public $cid;//class_id
    public $uid;//teacher_id

    //@todo 增加 学科 学年 筛选选项
    
    public function exchangeArray($data)
    {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->cid = (isset($data['cid'])) ? $data['cid'] : null;
        $this->uid = (isset($data['uid'])) ? $data['uid'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}