<?php
namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ProductForm extends Form
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
        
        // ��Ʒ����
        $name = new Element('name');
        $name->setLabel('name');
        $name->setAttributes(array(
        		'type'=>'text'
        ));
        $this->add($name);
        
        //��ƷͼƬ       �������Ӷ���ͼƬ����
        $image = new Element('image');
        $image->setLabel('Image Upload');
        $image->setAttributes(array(
        		'type'=>'file',
        ));
        $this->add($image);
        
        //��Ʒ����
                
        $desc = new Element('desc');
        $desc->setLabel('desc');
        $desc->setAttributes(array(
        		'type'=>'text'
        ));
        $this->add($desc);
        
        //��Ʒ�۸�
        $cost = new Element('cost');
        $cost->setLabel('cost');
        $cost->setAttributes(array(
        		'type'=>'text'
        ));
        $this->add($cost);
        
        //�ύ
        $submit = new Element('submit');
        $submit->setAttributes(array(
        		'type' => 'submit',
        		'value' => 'upload now'
        ));
        $this->add($submit);
        
    }
    
    
    
}