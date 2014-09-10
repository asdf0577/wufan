<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class RegisterForm extends Form{
    public function __construct($name=null){
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
        
        $email=new Element\Text('email');
        $email->setLabel('Email');
        $this->add($email);
        
        $password=new Element\password('password');
        $password->setLabel('password');
        $password->setAttribute('value', "pls input the password");
        $this->add($password);
        
        $confirm_password=new Element\password('confirm_password');
        $confirm_password->setLabel('confirm_password');
        $this->add($confirm_password);
        
        $submit=new Element\submit('submit');
        $submit->setValue('Submit');
        $this->add($submit);
    }
    
}