<?php
namespace Album\Model;
class ClassName {
	public $id;
	public $name;
	public $classType;
	public $year;
	public $studentAmout;//学生总数
	public $updateTime;//修改时间
	public $createTime;//创建时间
	
	
	public function exchangeArray($data)
	{

		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->classType = (isset($data['classType'])) ? $data['classType'] : null;
		$this->year = (isset($data['year'])) ? $data['year'] : null;
		$this->studentAmout = (isset($data['studentAmout'])) ? $data['studentAmout'] : null;

	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}