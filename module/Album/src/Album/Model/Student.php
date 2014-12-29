<?php
namespace Album\Model;
class Student {
	public $id;
	public $cid;//对应的class的id
	public $name;
	public $gender;
	public $studentNum;
	public $password;
	
	public function exchangeArray($data)
	{

		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->cid = (isset($data['class'])) ? $data['class'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->studentNum = (isset($data['studentNum'])) ? $data['studentNum'] : null;
		$this->gender = (isset($data['gender'])) ? $data['gender'] : null;

	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}