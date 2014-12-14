<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Translator;
class TestPaperForm extends Form
{
    public function __construct($name = null,$options=array())
    {
    	parent::__construct($name,$options);
    	$this->addElements();
}

    public function addElements(){
       /*  ID */
        $id=new Element\Text('id');
        $this->add($id);
       /*  year */
        $year=new Element\text('year');
        $year->setLabel('学年');
        $this->add($year);
        /* termNum */
        $termNum = new Element\select('termNum');
        $termNum->setLabel('学期');
        $termNum ->setValueOptions(array(
        	                           '1'=>'1',
        	                           '2'=>'2',
        	                           '3'=>'3',
        	                           '4'=>'4',
        	                           '5'=>'5',
        	                           '6'=>'6',
                                                       
        ));
        $this->add($termNum);  
        /* unitNum */
        $unitNum = new Element\select('unitNum');
        $unitNum->setLabel('单元');
        $unitNum ->setValueOptions(array(
        	                           '1'=>'1',
        	                           '2'=>'2',
        	                           '3'=>'3',
        	                           '4'=>'4',
        	                           '5'=>'5',
        	                           '6'=>'6',
        	                           '7'=>'7',
        	                           '8'=>'8',
        	                           '9'=>'9',
        	                           '10'=>'10',
                                                       
        ));
        $this->add($unitNum);    
       /*  QuestionAmount */
             
        $questionAmount = new Element\text('questionAmount');
        $questionAmount->setLabel('试题总数');
        $this->add($questionAmount);
     
        /* QuestionType */
        $questionType = new Element\Select('questionType');
        $questionType->setAttribute('class', 'selectType');
        $questionType->setAttribute('id', 'selectType1');
        $questionType->setAttribute('multiple', 'multiple   ');
        $questionType->setValueOptions(array(
        		'listen'=>'听力',
        		'select'=>'选择',
        		'cloze'=>'完形填空',
        		'reading'=>'阅读理解',
        		'writing'=>'作文',
        ));
        $this->add($questionType);
        $questionTypeselect = new Element\Select('questionTypeselect');
        $questionTypeselect->setAttribute('class', 'selectType');
        $questionTypeselect->setAttribute('id', 'selectType2');
        $questionTypeselect->setAttribute('multiple', 'multiple   ');
        $questionTypeselect->setValueOptions(array(
        ));
        $this->add($questionTypeselect);
        /*QuestionTypeInput  */
        
        /* submit */
        $submit = new Element\Submit('submit');
        $submit->setAttribute('value',' 创建 ')
               ->setAttribute('id','submitbutton');
        $this->add($submit);
        
    }


}