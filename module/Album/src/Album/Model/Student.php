<?php
namespace Album\Model;
class Student {
	public $id;
	public $cid;//对应的class的id
	public $name;
	public $gender;
	public $studentNum;
	public $role; //角色
	public $password;
	
	public function exchangeArray($data)
	{

		$this->id = (isset($data['sid'])) ? $data['sid'] : null;
		$this->cid = (isset($data['cid'])) ? $data['cid'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->studentNum = (isset($data['studentNum'])) ? $data['studentNum'] : null;
		$this->gender = (isset($data['gender'])) ? $data['gender'] : null;
		$this->role = (isset($data['role'])) ? $data['role'] : null;

	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}