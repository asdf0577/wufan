<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class ClassManagerForm extends Form{
    public function __construct($name=null){
        parent::__construct($name);
        $this->addElements();
       
    }
    public function addElements(){
        $id=new Element\Text('id');
        $id->setLabel('id');
        $this->add($id);
        
        $name=new Element\Text('name');
        $name->setLabel('班级名称');
        $this->add($name);
        
        
//         year
        $year = new Element\Select('year');
        $year ->setLabel('入学时间');
        $this->add($year);
        
        
        
        $classCheck = new Element\MultiCheckbox('classCheck');
        $this->add($classCheck);    
        
        
        $classSelect = new Element\Select('classSelect');
        $classSelect->setLabel('classSelect');
        $classSelect->setAttribute('multiple', 'multiple   ');
        $classSelect->setAttribute('class', 'classSelect');
        $classSelect->setAttribute('id', 'classSelect');
        $this->add($classSelect);
        
        
        $classSelected = new Element\Select('classSelected');
        $classSelected->setLabel('classSelected');
        $classSelected->setAttribute('multiple', 'multiple   ');
        $classSelected->setAttribute('class', 'classSelect');
        $classSelected->setAttribute('id', 'classSelected');
        $this->add($classSelected);
        
        
        
        
        /* classType */
        $classType = new Element\Select('classType');
        $classType ->setLabel('班级属性');
        $classType->setValueOptions(array('1'=>'小学',
                                          '2'=>'初中',
                                          '3'=>'高中',
                                          '4'=>'大学',
                                          '5'=>'校外',
                                          '6'=>'其他',
                                            ));
        $this->add($classType);
        
        $submit=new Element\submit('submit');
        $submit->setAttribute('id', 'classSelectSubmit')->setAttribute('value', ' 保存');
        $this->add($submit);
    }
    
}