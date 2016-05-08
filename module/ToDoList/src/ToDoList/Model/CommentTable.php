<?php
namespace ToDoList\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
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
                ->order(array('date DESC'))
                ->where("task_id = $task_id");
        $result = $this->tableGateway->selectWith($select);
        $items = array();
        foreach ($result as $item){
            $items[] = $item;
        }
        return $items;
    }
    
    public function getLastByTaskId($task_id) {
        $select = new Select;
        $select->from($this->tableGateway->getTable())
                ->order(array('id DESC'))
                ->where("task_id = $task_id")
                ->limit(1);
        $result = $this->tableGateway->selectWith($select);
        return $result->current();
    }
    
    public function getCountsByTasks() {
        $adapter = $this->tableGateway->getAdapter();
        $table = $this->tableGateway->getTable();
        $sql = "SELECT task_id, COUNT(id) AS cnt FROM $table GROUP BY task_id";
        $stm = $adapter->query($sql);
        $result = $stm->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $items = array();
        foreach ($resultSet as $row){
            $items[$row->task_id] = $row->cnt;
        }
        return $items;
    }
}
