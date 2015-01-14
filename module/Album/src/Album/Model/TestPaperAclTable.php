<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

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

    public function addTestPaperAcl($acl)
    {
        $data = array(
            'tid'=> $acl->tid,
            'uid'=> $acl->uid,
            'cid'=> $acl->cid,
        )
        ;
        $id = (int) $testPaper->id;
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
    
    
    
}