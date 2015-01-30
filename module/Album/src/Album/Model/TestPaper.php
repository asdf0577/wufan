<?php
namespace Album\Model;
class TestPaper {
	public $id;
	public $uid;
	public $year;
	public $termNum;
	public $unitNum;
	public $type;
	public $questionAmount;
	public $questionType;//varchar (255) 用逗號分開 20,20,30,10,
	public $created;//是否創建，是則取消創建頁面
	public $createtime;
		public function exchangeArray($data)
	{

		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->uid = (isset($data['uid'])) ? $data['uid'] : null;
		$this->year = (isset($data['year'])) ? $data['year'] : null; 
		$this->termNum = (isset($data['termNum'])) ? $data['termNum'] : null;
		$this->unitNum = (isset($data['unitNum'])) ? $data['unitNum'] : null;
		$this->type = (isset($data['type'])) ? $data['type'] : null;
		$this->questionAmount = (isset($data['questionAmount'])) ? $data['questionAmount'] : null;
		$this->questionType = (isset($data['QuestionTypeInput'])) ? $data['QuestionTypeInput'] : null;
		$this->created = (isset($data['created'])) ? $data['created'] : "0";

	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
		
		
		
}