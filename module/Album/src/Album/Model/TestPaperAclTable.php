<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
class TestPaperAclTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //用于教师查看名下所有的试题页面
    public function getTestPaperByTeacher($uid)
    {
        $uid = (int) $uid;
        $result = $this->tableGateway->select(array(
            'uid' => $uid
        ));
        if (! $result) {
            throw new \Exception("Could not find row $cid");
        }
        return $result;
    }

    public function getTestPaperByClass($cid)
    {
        $cid = (int) $cid;
        $result = $this->tableGateway->select(array(
            'cid' => $cid
        ));
        if (! $result) {
            throw new \Exception("Could not find row $cid");
        }
        return $result;
    }
    
    public function getTestPaperByTestPaper($uid,$tid){
        $result = $this->tableGateway->select(function (select $select) use ($uid,$tid){
            //问题错在这里
            $select->where(array(
                'tid'=>$tid,
                'uid'=>$uid,
            ));
             
        })->toArray();
        return $result;
    }

    public function addTestPaperAcl($acl)
    {
        $data = array(
            'tid'=> $acl->tid,
            'uid'=> $acl->uid,
            'cid'=> $acl->cid,
            'class_name'=>$acl->class_name,
            'status'=>1,
        )
        ;
        $id = (int) $acl->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->gettestPaper($id)) {
                $this->tableGateway->update($data, array(
                    'id' => $id
                ));
            } else {
                throw new \Exception("Form id does not exist");
            }
        }
    }

    public function delete($tid)
    {
        $tid = (int) $tid;
        $this->tableGateway->delete(array(
            'id' => $tid
        ));
    }
    
    public function deleteByClass($cid)
    {
        $cid = (int) $cid;
        $this->tableGateway->delete(array(
            'cid'=>$cid,
        ));
    }
    public function deleteByTestPaper($tid)
    {
        $tid = (int) $tid;
        $this->tableGateway->delete(array(
            'tid'=>$tid,
        ));
    }
    public function deleteByClassAndTestPaper($cid,$tid,$uid)
    {
        $tid = (int) $tid;
        $uid = (int) $uid;
        $cid = (int) $cid;
        $this->tableGateway->delete(array(
            'tid' => $tid,
            'uid'=>$uid,
            'cid'=>$cid,
        ));
    }
    //试卷对班级可见/不可见
    public function changeAclStatus($status,$cid,$tid)
    {
        $status = (int)$status;
        $cid = (int)$cid;
        $tid = (int)$tid;
        $data['status']=$status;
        $this->tableGateway->update($data,array('cid'=>$cid,'tid'=>$tid));
    }
    
}