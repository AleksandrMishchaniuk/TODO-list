<?php
namespace ToDoList\Model;

use Zend\Db\TableGateway\TableGateway;
use Exception;
use Zend\Db\Sql\Select;

use ToDoList\Model\Task;

class TaskTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function save(Task $item) {
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
    
    public function get($id) {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if(!$row){
            return FALSE;
        }
        return $row;
    }
    
    public function fetchAll() {
        $select = new Select;
        $select->from($this->tableGateway->getTable())->order(array('status DESC', 'deadline DESC'));
        return $this->tableGateway->selectWith($select);
    }
}
