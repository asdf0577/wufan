<?php
namespace Album\Form;
use Zend\InputFilter\InputFilter;

class loginFilter extends InputFilter{
    public function __construct(){
       $this->add(array(
       	'name'=>'email',
        'required'=>'true',
        'validators'=>array(
       	                    array('name'=>'emailAddress',
       	                          'options'=>array(
        	                                   'dominte'=>'true',
        )
        )
       )   
       ));
       
      
       $this->add(array(
       		'name' => 'password',
       		'required' => true,
       ));
       
       
    }
}