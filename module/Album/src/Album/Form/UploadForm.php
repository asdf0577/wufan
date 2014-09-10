<?php
namespace Album\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class UploadForm extends Form{
    
    public function __construct($name=null){
        parent::__construct('Register');//这句话是什么意思
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->addElements();
    }
    public function addElements(){
        $id=new Element\Text('id');
        $id->setLabel('id');
        $this->add($id);
        
       $filename=new Element\Text('filename');
        $filename->setLabel('filename');
        $this->add($filename); 
        
        $label=new Element\Text('label');
        $label->setLabel('label');
        $this->add($label); 
        
        $userId=new Element\Text('userId');
        $userId->setLabel('userId');
        $this->add($userId);
        
        $fileUpload=new Element\file('fileUpload');
        $fileUpload->setLabel('fileUpload');
        $this->add($fileUpload); 
        
        $submit=new Element\submit('submit');
        $submit->setValue('Upload');
        $this->add($submit);
     
     /*    $userList= new Element\Select('userlist');
        $userList->setLabel('Choose User');
        $this->add($userList);  */
    }
    
    
    
    
}