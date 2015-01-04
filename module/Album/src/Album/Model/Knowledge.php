<?php
namespace Album\Model;
class Knowledge {
    public $id;
    public $fid;
    public $name;
    public $cname;
    public $grade;
    public $questionType;
    public $path;
   
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->fid = (isset($data['fid'])) ? $data['fid'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->cname = (isset($data['cname'])) ? $data['cname'] : null;
        $this->grade = (isset($data['grade'])) ? $data['grade'] : null;
        $this->questionType = (isset($data['questionType'])) ? $data['questionType'] : null;
        $this->path = (isset($data['path'])) ? $data['path'] : null;
        
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}