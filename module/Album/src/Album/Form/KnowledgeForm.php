<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class KnowledgeForm extends Form{
    public function __construct($name=null){
        parent::__construct($name);
        $this->addElements();
       
    }
    public function addElements(){
        
        $id=new Element\Text('id');
        $id->setLabel('id');
        $this->add($id);
        
        $fid=new Element\Text('fid');
        $fid->setLabel('fid');
        $this->add($fid);
        
        $name=new Element\Text('name');
        $name->setLabel('Knowledge Name');
        $this->add($name);
        
        
        $knowledgeType = new Element\Select('knowledgeType');
        $knowledgeType->setLabel('knowledgeType');
        $knowledgeType->setAttribute('multiple', 'multiple   ');
        $knowledgeType->setAttribute('class', 'knowledgeSelect');
        $this->add($knowledgeType);
        
        $knowledgeType2 = new Element\Select('knowledgeType2nd');
        $knowledgeType2->setLabel('Second Category');
        $knowledgeType2->setAttribute('multiple', 'multiple   ');
        $knowledgeType2->setAttribute('id', 'knowledgeSelect2nd');
        $this->add($knowledgeType2);
        
        $knowledgeType3 = new Element\Select('knowledgeType3rd');
        $knowledgeType3->setLabel('Third Category');
        $knowledgeType3->setAttribute('multiple', 'multiple   ');
        $knowledgeType3->setAttribute('id', 'knowledgeSelect3rd');
        $this->add($knowledgeType3);
        
        $knowledgeType4 = new Element\Select('knowledgeType4');
        $knowledgeType4->setLabel('Forth Category');
        $knowledgeType4->setAttribute('multiple', 'multiple   ');
        $knowledgeType4->setAttribute('id', 'knowledgeSelect4');
        $this->add($knowledgeType4);
        
        /* classType */
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