<?php
namespace Album\Model;
class TestPaper {
	public $id;
	public $year;
	public $termNum;
	public $unitNum;
	public $questionAmount;
	public $created;//是否創建，是則取消創建頁面
	public $createtime;
	public $questionType;//varchar (255) 用逗號分開 20,20,30,10,
	
	//听力1 数量 分值
	//听力2 数量 分值
	//单选1 数量 分值
	//完形1 数量 分值
	//阅读1 数量 分值
	//拼写1 数量 分值 
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