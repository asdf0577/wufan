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
       /*  QuestionNum */
        $questionNum = new Element\Hidden('questionNum[]');
        $this->add($questionNum);
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
        $grade->setValueOptions(array(
        	                '1'=>'2',
        	                '2'=>'3',
        	                '3'=>'4',
        	                '4'=>'5',
        ));
        $this->add($grade);
        /* submit */
        $submit = new Element\Submit('submit');
        $submit->setAttribute('value','保存')
               ->setAttribute('id','submitbutton');
        $this->add($submit);
        
    }


}