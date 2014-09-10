<?php
namespace Album\Model;

use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Album\Model\Upload;

class UploadTable{
    protected $tableGateway;
    protected $uploadsSharingTableGateway;
    
    public function __construct(TableGateway $tableGateway,TableGateway $uploadsSharingTableGateway)
    {
        $this->tableGateway=$tableGateway;
        $this->uploadsSharingTableGateway=$uploadsSharingTableGateway;
    }
    public function fetchAll(){
        $resultset=$this->tableGateway->select();
        return $resultset;
    }
    public function SaveUpload(Upload $Upload){
        $data=array(
        	'filename'=>$Upload->filename,
            'label'=>$Upload->label,
            'user_id'=>$Upload->user_id,
        );
      $id=(int)$Upload->id;
        if($id==0){
             $this->tableGateway->insert($data);
        } 
        else{
            if($this->getUpload($id)){
                $this->tableGateway->update($data,array('id'=>$id));
            }
            else{
                throw new \Exception('Upload ID does not exist');
            }
                    }
        
    }
    public function getUpload($id){
        $id=(int)$id;
        $result=$this->tableGateway->select(array('id'=>$id));
        $row=$result->current();
        if(!$row){
            throw new \Exception("could not find row $id");
        }
        return $row;
    }
    public function getUploadsByUserId($user_id){
        $id=(int)$user_id;
        $rowset=$this->tableGateway->select(array('user_id'=>$id));
        return $rowset;
    }
    public function deleteUpload($id){
        $id=(int)$id;
        $this->tableGateway->delete(array('id'=>$id));
    }
    public function addSharing($uploadId,$userId){
        $data=array('upload_id'=>(int)$uploadId,
                    'user_id'=>(int)$userId,
        );
        $this->uploadsSharingTableGateway->insert($data);
    }
    public function deleteSharing($id){
        $id=(int)$id;
        $this->uploadsSharingTableGateway->delete(array('id'=>$id));
    }
    public function getSharingUser($uploadId){
        $uploadId=(int)$uploadId;
        $rowset=$this->uploadsSharingTableGateway->select(array(
        	'upload_id'=>$uploadId,
        ));
        return $rowset;
    }
    public function getSharedUploadsForUserID($userId){
        $userId = (int) $userId;
        $rowset = $this->uploadsSharingTableGateway->select
        (function(select $select) use ($userId)
            {
        	    $select->columns(array())
        	    ->where(array('uploads_sharing.user_id'=>$userId))
        	    ->join('upload','uploads_sharing.upload_id=upload.id');
        	}
        );
       /*  $rowset->buffer(); */
        return $rowset;
    }
}