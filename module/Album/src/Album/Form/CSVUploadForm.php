<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class CSVUploadForm extends Form
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
        
        $CSVupload = new Element('CSVUpload');
        $CSVupload->setLabel('CSV Upload');
        $CSVupload->setAttributes(array(
        		'type'=>'file',
        ));
        $CSVupload->setAttributes(array(
        		'multiple'=>true,
        ));
        
        $this->add($CSVupload);
        
        $submit = new Element('submit');
        $submit->setAttributes(array(
        		'type' => 'submit',
                'value' => 'upload now'
        ));
        $this->add($submit);
    }
    
}