<?php
namespace Album\Model;
class QuestionType {
    public $id;
    public $fid;
    public $tname;
    public $path;
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->fid = (isset($data['testPaperType'])) ? $data['testPaperType'] : null;
        $this->tname = (isset($data['QuestionTypeInput'])) ? $data['QuestionTypeInput'] : null;
        $this->path = (isset($data['path'])) ? $data['path'] : null;
        
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}