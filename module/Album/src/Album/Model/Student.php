<?php
namespace Album\Model;
class StudentPaper {
	public $id;
	public $cid;//对应的class的id
	public $name;
	public function exchangeArray($data)
	{

		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->cid = (isset($data['cid'])) ? $data['cid'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;

	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}