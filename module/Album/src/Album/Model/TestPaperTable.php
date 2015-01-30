<?php
namespace Album\Model;

use Album\Model\TestPaper;
use Zend\Db\TableGateway\TableGateway;

class TestPaperTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getTestPaper($id)
    {
        $id = (int) $id;
        $result = $this->tableGateway->select(array(
            'id' => $id
        ));
        $row = $result->current();
        if (! $row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getTestPaperByTeacher($uid)
    {
        $uid = (int) $uid;
        $result = $this->tableGateway->select(array(
            'uid' => $uid
        ))->toArray();
        return $result;
    }

    public function saveTestPaper($testPaper)
    {
        $data = array(
            'uid' => $testPaper->uid,
            'year' => $testPaper->year,
            'termNum' => $testPaper->termNum,
            'unitNum' => $testPaper->unitNum,
            'type' => $testPaper->type,
            'questionAmount' => $testPaper->questionAmount,
            'questionType' => $testPaper->questionType,
            'created' => $testPaper->created,
            'createTime' => time()
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