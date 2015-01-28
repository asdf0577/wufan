<?php
namespace Album\Model;

use Album\Model\Knowledge;
use Zend\Db\TableGateway\TableGateway;

class KnowledgeTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll(){
        $result = $this->tableGateway->select();
        $rowset = $result->toArray();
        return $rowset;
    }
    public function getKnowledges($fid)
    {
        $fid=(int)$fid;
        $result=$this->tableGateway->select(array('fid'=>$fid));
        $rowset=$result->toArray();
        if(!$result){ throw new \Exception("当前类型下没有二级分类");}
        return $rowset;
    }
    public function getKnowledge($id){
        $id = (int)$id;
        $row = $this->tableGateway->select(array('id' =>$id))->current();
//         if(!$row){ throw new \Exception("Form id does not exist");}
        return $row;
    }
    public function saveKnowledge($questionType) 
    {
        //@todo 还未完成
        
        $data = array(
            'fid' => $questionType->fid,
            'name' => $questionType->name,
            'cn_name' => $questionType->cn_name,
            'grade' => $questionType->grade,
            'questionType' => $questionType->questionType,
            'path' => $questionType->path,
            
        );
        $id = (int)$questionType->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }else{
            if($this->getquestion($id)){
                $this->tableGateway->update($data,array('id' => $id));
            }else 
            {
                throw new \Exception("Form id does not exist");
            }
        }
    }
    public function delete($id){
        $id = (int)$id;
        $this->tableGateway->delete(array('id'=>$id));
    }
    
    
    
}