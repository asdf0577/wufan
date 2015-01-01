<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class GrammarForm extends Form{
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
        $name->setLabel('Grammar Name');
        $this->add($name);
        
        
        $grammarType = new Element\Select('grammarType');
        $grammarType->setLabel('GrammarType');
        $grammarType->setAttribute('multiple', 'multiple   ');
        $grammarType->setAttribute('class', 'grammarSelect');
        $this->add($grammarType);
        
        $grammarType2 = new Element\Select('grammarType2');
        $grammarType2->setLabel('Second Category');
        $grammarType2->setAttribute('multiple', 'multiple   ');
        $grammarType2->setAttribute('class', 'grammarSelect');
        $this->add($grammarType2);
        
        $grammarType3 = new Element\Select('grammarType3');
        $grammarType3->setLabel('Third Category');
        $grammarType3->setAttribute('multiple', 'multiple   ');
        $grammarType3->setAttribute('class', 'grammarSelect');
        $this->add($grammarType3);
        
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