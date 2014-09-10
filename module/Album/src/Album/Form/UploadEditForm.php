<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\View\Helper\FormElement;
use Zend\Form\Element;

class UploadEditForm extends Form
{
    public function __construct($name=null)
    {
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        
        $this->add(array(
        	'name' => 'label',
            'attribute' => array(
        	    'type' => 'text',
        ),
            'options' => array(
            	'label' => 'File Description',
            ),
        ));
        $this->add(array(
        	'name' => 'submit',
            'attributes' => array(
        	   'type' => 'submit',
                'value' => 'Update Document'
        ),
        ));
        
      $this->addElements();
        
      
    }
    public function addElements()
    {
        $filename= new Element\Hidden('filename');
        $this->add($filename);
         $userId= new Element\hidden('user_id');
        $this->add($userId); 
    }
}