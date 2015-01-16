<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class InputQuestionForm extends Form{
    public function __construct($name=null){
        parent::__construct($name);
        $this->addElements();
       
    }
    public function addElements(){
         
        
        $sid = new Element\Select('sid');
        $sid->setEmptyOption('选择学生');
        $this->add($sid);
        
        $cid = new Element\Select('cid');
        $cid->setEmptyOption('选择班级');
        $cid->setAttribute('id', 'classChange');
        $this->add($cid);
        
        $question = new Element\Textarea('question');
        $this->add($question);
        
        
        $submit=new Element\submit('submit');
        $submit->setValue('Submit');
        $this->add($submit);
    }
    
}