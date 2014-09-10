<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;
class ChatForm extends Form{
    
    public function __construct()
    {
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->addElements();
    }
    public function addElements(){

        $message = new Element('message');
        $message->setAttributes(array(
        		'type' => 'text',
        		'id' => 'messageText',
        		'required' => 'required'
        ));
        $this->add($message);
        
        $submit = new Element('submit');
        $submit->setAttributes(array(
        		'type'=>'submit',
        		'value'=>'Send'
        ));
        $this->add($submit);
        
        $refresh = new Element\Button('refresh');
        $refresh->setLabel('refresh');
        $refresh->setAttributes( array(
        		'type'  => 'button',
        		'id' => 'btnRefresh',
        		'value' => 'Refresh'
        ));
        $this->add($refresh); 
    }
    
    
    
}