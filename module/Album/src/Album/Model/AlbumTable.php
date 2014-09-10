<?php 
namespace Album\Model;
use Zend\Db\TableGateway\TableGateway;
use zend\db\sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\ResultSet\ResultSet;
use album\model\album;
class AlbumTable {
        protected $tableGateway;
        /*����һ�����캯���������������AlbumTable �౻ʵ������ǿ��ִ��  */
        //����ע��һ��$tableGateway,��module��serviceManager�����ù���
        public function __construct(TableGateway $tableGateway)
        {
            $this->tableGateway=$tableGateway;
        }
        public function fetchAll($paginated=false)
        {
         if($paginated){
             
             $select= new select('albums');//����һ�����album���Select����
             $resultSetPrototype= new ResultSet();//����һ���µĽ��������Albumʵ��
             $resultSetPrototype->setArrayObjectPrototype(new album());
             $paginatorAdapter= new DbSelect(//����һ���µ�paginator����������
                 $select, //���úõ�select����
                 $this->tableGateway->getAdapter(),
                 $resultSetPrototype);
             $paginator= new Paginator($paginatorAdapter);
             return $paginator;
             
         }        
            $resultSet=$this->tableGateway->select();
            return $resultSet;
        }
        public function getAlbum($id)
        {
            $id=(int)$id; 
            $result=$this->tableGateway->select(array('id'=>$id));
            $row=$result->current();
            if(!$row)
            {
                throw new \Exception("Could not find row $id");
            }
            return $row;
        }
        public function saveAlbum(Album $album)
        {
            $data=array(
            	'title'=>$album->title,
                'artist'=>$album->artist,//���Ըĳ��������ʽ����
            );
            $id=(int)$album->id;
            if($id==0)
            {
                $this->tableGateway->insert($data);
            }
            else
            {
                if ($this->getAlbum($id)) 
                {
                	$this->tableGateway->update($data,array('id'=>$id));
                }
                else
                {
                    throw new \Exception("Form id dose not exist");
                } 
                    
            }
            
        }
        public function delete($id)
        {
            $id=(int)$id;
            $this->tableGateway->delete(array('id'=>$id));
        }
    }
    
    
