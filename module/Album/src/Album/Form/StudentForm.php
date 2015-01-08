<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class StudentForm extends Form{
    public function __construct($name=null){
        parent::__construct($name);
        $this->addElements();
       
    }
    public function addElements(){
        
        $id=new Element\Text('id');
        $id->setLabel('id');
        $this->add($id);
        
        $name=new Element\Text('name');
        $name->setLabel('学生姓名');
        $this->add($name);
        
        $studentNum=new Element\Text('studentNum');
        $studentNum->setLabel('学号');
        $this->add($studentNum);
        
        /* classType */
        $class = new Element\Select('class');
        $class->setLabel('班级');
        $class->setEmptyOption('选择班级');
        $this->add($class);
        
      
        $gender = new Element\Select('gender');
        $gender ->setLabel('性別');
        $gender->setEmptyOption('选择性别');
        $gender->setValueOptions(array('1'=>'男',
                                          '2'=>'女',
                                            ));
        $this->add($gender);
        
        $submit=new Element\submit('submit');
        $submit->setValue('Submit');
        $this->add($submit);
    }
    
}