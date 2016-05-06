<?php
namespace ToDoList\Model;

use Zend\Db\TableGateway\TableGateway;
use Exception;
use Zend\Db\Sql\Select;

use ToDoList\Model\Comment;

class CommentTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function save(Comment $item) {
        $data = array();
        foreach ($item as $key => $val){
            if($key !== 'id'){
                $data[$key] = $val;
            }
        }
        $id = (int)$item->id;
        if(!$id){
            $this->tableGateway->insert($data);
            return TRUE;
        }else{
            if($this->get($id)){
                $this->tableGateway->update($data, array('id' => $id));
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
    
    public function fetchAllByTaskId($task_id) {
        $select = new Select;
        $select->from($this->tableGateway->getTable())
                ->order(array('status DESC', 'deadline DESC'))
                ->where("tash_id = $task_id");
        return $this->tableGateway->selectWith($select);
    }
    
    public function getCountByTaskId($task_id) {
        $adapter = $this->tableGateway->getAdapter();
        $table = $this->tableGateway->getTable();
        $sql = "SELECT COUNT(*) AS cnt FROM $table WHERE task_id=$task_id";
        $stm = $adapter->query($sql);
        $result = $stm->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $item = NULL;
        foreach ($resultSet as $row){
            $item = $row->cnt;
        }
        return $item;
    }
}
