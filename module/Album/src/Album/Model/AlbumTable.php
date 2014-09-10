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
        /*创建一个构造函数，这个函数将在AlbumTable 类被实例化后强制执行  */
        //依赖注入一个$tableGateway,在module的serviceManager里配置过，
        public function __construct(TableGateway $tableGateway)
        {
            $this->tableGateway=$tableGateway;
        }
        public function fetchAll($paginated=false)
        {
         if($paginated){
             
             $select= new select('albums');//创建一个针对album表的Select对象
             $resultSetPrototype= new ResultSet();//创建一个新的结果集基于Album实体
             $resultSetPrototype->setArrayObjectPrototype(new album());
             $paginatorAdapter= new DbSelect(//创建一个新的paginator适配器对象
                 $select, //设置好的select对象
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
                'artist'=>$album->artist,//可以改成数组的形式看看
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
    
    
