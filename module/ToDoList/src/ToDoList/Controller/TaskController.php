<?php

namespace ToDoList\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use ToDoList\Form\TaskForm;
use ToDoList\Form\CommentForm;
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
        $tasks_tmp = $this->taskTable->fetchAll();
        $counts_tmp = $this->commentTable->getCountsByTasks();
        $tasks = array();
        foreach ($tasks_tmp as $task){
            $tasks[] = array(
                'task' => $task,
                'comments_count' => isset($counts_tmp[$task->id])? $counts_tmp[$task->id]: 0,
            );
        }
        $form = new TaskForm;
        return new ViewModel(array('form' => $form, 'tasks' => $tasks));
    }
    
    public function viewAction() {
        $id = $this->params('id');
        $task = $this->taskTable->get($id);
        $comments = $this->commentTable->fetchAllByTaskId($id);
        $form = new CommentForm;
        return new ViewModel(array(
            'task' => $task,
            'comments' => $comments,
            'form' => $form,
        ));
    }
    
    public function addAction() {
        if($this->getRequest()->isPost()){
            $form = new TaskForm;
            $task = new Task;
            $form->setInputFilter($task->getInputFilter());
            $data = $this->getRequest()->getPost();
            $data->deadline = $this->convertDate('Y-m-d', $data->deadline);
            $form->setData($data);
            if($form->isValid()){
                $task->exchangeArray($form->getData());
                if($this->taskTable->save($task)){
                    $this->respond['ok'] = 1;
                    $this->respond['msg'][] = 'Новая задача добавлена';
                    $this->respond['data'] = $this->taskTable->getLast();
                    $this->respond['data']->deadline = $this->convertDate('d.m.Y', $this->respond['data']->deadline);
                }else{
                    $this->respond['msg'][] = 'Ошибка при добавлении задачи';
                }
            }else{
                $this->respond['msg'] = $form->getInputFilter()->getInvalidInput();
            }
        }
        return new JsonModel($this->respond);
    }
    
    public function updateAction() {
        if($this->getRequest()->isPost()){
            $id = (int)  $this->params('id');
            $task = $this->taskTable->get($id);
            $task->exchangeArray($this->getRequest()->getPost());
            $task->deadline = $this->convertDate('Y-m-d', $task->deadline);
            $form = new TaskForm();
            $form->setInputFilter($task->getInputFilter());
            $form->bind($task);
            if($form->isValid()){
                if($this->taskTable->save($task)){
                    $this->respond['ok'] = 1;
                    $this->respond['msg'][] = 'Статус задачи изменен';
                }else{
                    $this->respond['msg'][] = 'Ошибка при изменении статуса задачи';
                }
            }else{
                $this->respond['msg'][] = 'Данные не прошли валидацию';
            }
//            var_dump($form->getData()); die();
        }
        return new JsonModel($this->respond);
    }
    
    protected function convertDate($format, $date_str){
        $date = new \DateTime($date_str);
        return $date->format($format);
    }

}
