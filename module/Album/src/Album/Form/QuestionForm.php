<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;
class QuestionForm extends Form
{
    public function __construct($name = null,$options=array())
    {
    	parent::__construct($name,$options);
    	$this->addElements();
}

    public function addElements(){
       /*  ID */
        $id=new Element\Hidden('id');
        $this->add($id);
        /* unitNum */
        $tid = new Element\Hidden('tid');
        $this->add($tid);    
       /*  QuestionNum create*/
        $questionNum = new Element\Hidden('questionNum[]');
        $this->add($questionNum);
        
        /*  QuestionNum edit*/
        
        $questionNum2 = new Element\Hidden('questionNum2');
        $this->add($questionNum2);
        
        /* GrammaType */
        $grammaType = new Element\Select('grammaType[]');
        $grammaType->setValueOptions(array( 
                                       '1'=>'ADJECTIVES', 
                                       '2'=>'ADVERBS' ,
                                       '3'=>'DETERMINERS', 
        ));
        $this->add($grammaType);
        /* content */
        $content = new Element\text('content[]');
        $this->add($content);
        /* grade */
        $grade = new Element\select('grade[]');
        $grade->setEmptyOption('默认为空');
        $grade->setValueOptions(array(
        	                '1'=>'1星',
        	                '2'=>'2星',
        	                '3'=>'3星',
        	                '4'=>'4星',
        	                '5'=>'5星',
        ));
        $this->add($grade);
        /* submit */
        $submit = new Element\Submit('submit');
        $submit->setAttribute('value','保存')
               ->setAttribute('id','submitbutton');
        $this->add($submit);
        
    }


}