<?php
namespace Album\Model;
class StudentPaper {
	public $id;
	public $class;
	public $name;
//1按一个学生来查找他的薄弱项
//2按单元测试来排序错题数
//3按具体体题来查找做错   

的学生
	public function exchangeArray($data)
	{

		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->year = (isset($data['year'])) ? $data['year'] : null; 
		$this->termNum = (isset($data['termNum'])) ? $data['termNum'] : null;
		$this->unitNum = (isset($data['unitNum'])) ? $data['unitNum'] : null;
		$this->questionAmount = (isset($data['questionAmount'])) ? $data['questionAmount'] : null;
		$this->created = (isset($data['created'])) ? $data['created'] : "0";

	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}