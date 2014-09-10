<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class UserEditForm extends Form{
    public function __construct($name = null,$options=array()){
        /* $this->setAttribute('method','post'); */
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->addElements();
       
       
    }
     public function addElements(){
        
        $id=new Element\Text('id');
        $id->setLabel('id');
        $this->add($id);
        
        $name=new Element\Text('name');
        $name->setLabel('Name');
        $this->add($name);
        
        $email=new Element\text('email');
        $email->setLabel('Email');
        $this->add($email);
        
     $submit=new Element\Submit('submit');
        $submit->setValue('Update');
        $this->add($submit); 
    } 
    
   
}