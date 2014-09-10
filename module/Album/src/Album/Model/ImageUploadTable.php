<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;
use Album\Model\ImageUpload;

class ImageUploadTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway=$tableGateway;
    }
    
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function saveUpload(ImageUpload $upload)
    
    {
          $data = array(
          	'filename' => $upload->filename,
            'thumbnail' => $upload->thumbnail,
            'label' => $upload->label,
            'user_id' => $upload->user_id,  
          );

          $id = (int)$upload->id;
          if($id == 0){
              $this->tableGateway->insert($data);
              
          }
          
          else {
              if ($this->getUpload($id)){
                  $this->tableGateway->update($data,array('id' => $id));
              }else {
                  throw new \Exception('upload ID does not exist');
              }
          }
    }
    
    public function getUpload($id)
    {
        $uploadId = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $uploadId) );
        $row = $rowset->current();
        if(!$row){
            throw new \Exception("could not find row $uploadId");
        }
        return $row;
    }
    public function getUploadByUserId($user_id)
    {
        $userId = (int)$user_id;
        $rowset = $this->tableGateway->select(array('user_id' => $userId));
        return $rowset;
    }
    public function deleteUpload($id)
    {
        $uploadId = (int)$id;
        $this->tableGateway->delete(array('id' => $uploadId));
    }
    
}