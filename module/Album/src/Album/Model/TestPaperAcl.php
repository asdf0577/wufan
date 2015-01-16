<?php
namespace Album\Model;
class TestPaperAcl
{
    public $id;
    public $tid;
    public $cid;//class_id
    public $uid;//teacher_id
    public $class_name;//班级名称
    public $status;//控制对学生是否可见

    //@todo 增加 学科 学年 筛选选项
    
    public function exchangeArray($data)
    {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tid = (isset($data['tid'])) ? $data['tid'] : null;
        $this->cid = (isset($data['cid'])) ? $data['cid'] : null;
        $this->uid = (isset($data['uid'])) ? $data['uid'] : null;
        $this->class_name = (isset($data['class_name'])) ? $data['class_name'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}