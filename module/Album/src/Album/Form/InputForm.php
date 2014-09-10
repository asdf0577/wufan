<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter;
use Zend\Captcha;
class InputForm extends Form{
    public function __construct($name = null,$options=array())
    {
    	// we want to ignore the name passed
    	parent::__construct($name,$options);
    	/* $this->setAttribute('method', 'post'); */
    	$this->addElements();
    	
    
    }
    public function addElements(){
        $id=new Element('id');
        $id->setLabel('ID');
        $id->setAttributes(array(
        	'type'=>'text',
        ));
        $this->add($id);
        
        $username= new Element('username');
        $username->setLabel('Username');
        $username->setAttributes(array(
        		'type'=>'text',
        ));
        $this->add($username);
        
        $password= new Element\Password('password');
        $password->setLabel('Password');
        $this->add($password);
        
        $email= new element\Email('email');
        $email->setLabel('Email');
        $this->add($email);
        
        $captcha=new Element\Captcha('captcha');
        $captcha->setCaptcha(new captcha\Dumb() );
        $captcha->setLabel('captcha');
        $this->add($captcha);
        
        $avatar= new Element\File('image-file');
        $avatar->setLabel('Avatar image upload')
                ->setAttributes(array('id'=> 'image-file',
                                       'multiple'=>true,));//html5 多文件支持
        $this->add($avatar);   
        
        $csrf=new Element\Csrf('security');
        $this->add($csrf);
        $reset=new Element\Button('reset');
        $reset ->setvalue('Resent')->setLabel('Reset');    
        $this->add($reset);
        $date=new Element\Date('date');
        $this->add($date);  
        $color=new Element\Color('color');
        $this->add($color);
        
        $submit=new Element\Submit('submit');
        $submit->setValue('Submit');
        $this->add($submit);
        
    }
    
    
    
}