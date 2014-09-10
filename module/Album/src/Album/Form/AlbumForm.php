<?php

namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter;

class AlbumForm extends Form
{
	public function __construct($name = null,$options=array())
	{
		// we want to ignore the name passed
		parent::__construct($name,$options);
		/* $this->setAttribute('method', 'post'); */
		$this->addElements();
	    $this->addinputFilter();
	
	}
	public function addElements(){
	    $id=new Element\Text('id');
	    $id->setLabel('id');
	    $this->add($id);
	    
	    $title=new Element\Text('title');
	    $title->setLabel('Title');
	    $this->add($title);
	    
	    $artist=new Element\Text('artist');
	    $artist->setLabel('Artist');
	    $this->add($artist);
	       
	    $file=new Element\File('image-file');
	    $file->setLabel('avatar Image Upload')
	         ->setAttribute('id', 'image-file')
	         ->setAttribute('multiple', true);
	    $this->add($file);
	    
	    $submit=new Element\Submit('submit');
	    $submit->setAttribute('value', 'go')
	           ->setAttribute('id', 'submitbutton');
	    $this->add($submit);
	}
	public function addInputFilter(){
	    $inputFilter=new InputFilter\InputFilter();
	    $fileinput=new InputFilter\FileInput('image-file');
	    $fileinput->setRequired(true);
	    $fileinput->getValidatorChain()
	              ->attachByName('filesize',array('max'=>204800))
	              ->attachByName('filemimetype',array('mimeType'=>'image/png,image/x-png'))
	              ->attachByName('fileimagesize',array('maxWidth'=>100,'maxHeight'=>100)); 
	  $fileinput->getFilterChain()->attachByName('filerenameupload',array('target'=>'./data/tmpupload/avatar.png',
	                                                                       'randomize'=>true,));
	              
	    $inputFilter->add($inputFilter);
	    $this->setInputFilter($inputFilter);          
	}
}