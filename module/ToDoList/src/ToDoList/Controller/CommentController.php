<?php

namespace ToDoList\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\Form\FormInterface;
use ToDoList\Form\CommentForm;
use ToDoList\Model\Comment;

class CommentController extends AbstractActionController
{
    protected $respond;
    protected $commentTable;

    public function __construct($commentTable) {
        $this->commentTable = $commentTable;
        $this->respond = array(
            'ok' => 0,
            'msg' => array(),
            'data' => array(),
        );
    }
    
    public function addAction()
    {
        if($this->getRequest()->isPost()){
            $form = new CommentForm;
            $comment = new Comment;
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()){
                $comment->exchangeArray($form->getData());
                if($this->commentTable->save($comment)){
                    $this->respond['ok'] = 1;
                    $this->respond['msg'][] = 'Ваш комментарий добавлен';
                    $this->respond['data'] = $this  ->commentTable
                                                    ->getLastByTaskId($form->get('task_id')->getValue());
                    $this->respond['data']->date = $this->convertDate('d.m.Y H:i', $this->respond['data']->date);
                }else{
                    $this->respond['msg'][] = 'Ошибка при добавлении комментария';
                }
            }else{
                $this->respond['msg'] = $form->getInputFilter()->getInvalidInput();
            }
        }
        return new JsonModel($this->respond);
    }

}
