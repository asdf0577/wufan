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
        
        $classCheck = new Element\MultiCheckbox('classCheck');
        $this->add($classCheck);    
        
        
//         $carNum=new Element\Text('carNum');
//         $carNum->setLabel('车牌号');
//         $this->add($carNum);
        
//         $address=new Element\Text('address');
//         $address->setLabel('车辆停放地点');
//         $this->add($address);
        
        
//         $year = new Element\Month('year');
//         $year->setOptions(array(
//         		'label'    => '入学时间',
//         )
            
//         );
//         $this->add($year);
        
//         $time = new Element\Time('time');
//         $time->setOptions(array(
//         		'label'    => '用车时间',
//         )
//         );
//         $this->add($time);
        
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
        $submit->setValue('Submit');
        $this->add($submit);
    }
    
}