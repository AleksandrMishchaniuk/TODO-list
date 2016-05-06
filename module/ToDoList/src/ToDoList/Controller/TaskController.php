<?php

namespace ToDoList\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use ToDoList\Form\TaskForm;
use ToDoList\Model\Task;


class TaskController extends AbstractActionController
{
    protected $respond;
    protected $taskTable;
    protected $commentTable;

    public function __construct($taskTable, $commentTable) {
        $this->taskTable = $taskTable;
        $this->commentTable = $commentTable;
        $this->respond = array(
            'ok' => 0,
            'msg' => array(),
            'data' => array(),
        );
    }
    
    public function listAction(){
        $form = new TaskForm;
        return new ViewModel(array('form' => $form));
    }
    
    public function addAction() {
        if($this->getRequest()->isPost()){
            $form = new TaskForm;
            $task = new Task;
            $form->setInputFilter($task->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()){
                $task->exchangeArray($form->getData());
                $this->taskTable->save($task);
                $this->respond['ok'] = 1;
                $this->respond['msg'][] = 'Новая задача добавлена';
            }else{
                $this->respond['msg'] = $form->getInputFilter()->getInvalidInput();
            }
        }
        return new JsonModel($this->respond);
    }

}
