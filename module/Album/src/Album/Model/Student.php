<?php
namespace Album\Model;
class StudentPaper {
	public $id;
	public $class;
	public $name;
//1��һ��ѧ�����������ı�����
//2����Ԫ���������������
//3��������������������   

��ѧ��
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