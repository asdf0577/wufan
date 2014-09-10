<?php
namespace Album\Form;
use Zend\InputFilter\InputFilter;

class registerFilter extends InputFilter{
    public function __construct(){
      
       
       $this->add(array(
       		'name'=>'label',
       		'required'=>'true',
       		'filter'=>array(
       				array('name'=>'StripTags',
       						'options'=>array(
       								'dominte'=>'true',
       						)
       				)
       		),
           'validators'=>array(
           		array('name'=>'Stringlength',
           				'options'=>array(
           						'encoding'=>'UTF-8',
           				        'min'=>'2',
           				        'max'=>'140',
           				)
           		)
           )
       ));
            
       
    }
}