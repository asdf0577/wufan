<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ImageUploadForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->addElements();
    }
    public function addElements()
    {
        $label = new Element('label');
        $label->setLabel('Image Description');
        $label->setAttributes(array(
        		'type'=>'text',
        ));
        $this->add($label);
        
        $imageupload = new Element('imageUpload');
        $imageupload->setLabel('Image Upload');
        $imageupload->setAttributes(array(
        		'type'=>'file',
        ));
        $this->add($imageupload);
        
        $submit = new Element('submit');
        $submit->setAttributes(array(
        		'type' => 'submit',
                'value' => 'upload now'
        ));
        $this->add($submit);
    }
    
}