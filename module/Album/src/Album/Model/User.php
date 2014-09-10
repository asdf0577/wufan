<?php
namespace Album\Model;
class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $time_reg;
    
    public function setPassword($clear_password){
        $this->password=md5($clear_password);
    }
    public function exchangeArray($data){
         $this->id=(isset($data['id']))?$data['id']:null; 
        $this->name=(isset($data['name']))?$data['name']:null;
        $this->email=(isset($data['email']))?$data['email']:null; 
        $this->time_reg=(isset($data['time_reg']))?$data['time_reg']:null; 
       if(isset($data["password"])){
           $this->setPassword($data["password"]);
         
       }
        
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
}