<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class OrderForm extends Form
{
    public function __construct()
    {
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->addElements();
        
    }
    
    public function addElements()
    {
        $quantity = new Element('qty');
        $quantity->setLabel('quantity');
        $quantity->setAttributes(array(
        		'type' => 'text',
                'id' => 'qty',
                'class'=>'qty',
                'required' => 'required',
                'value' =>'1'
        ));
        $this->add($quantity);
        
        $submit = new Element('submit');
        $submit->setLabel('Purchase');
        $submit->setAttributes(array(
        		'type'=>'submit',
                'value'=>'Purchase'
        ));
        $this->add($submit);
        
      
        
        
        
    }
    
    
}