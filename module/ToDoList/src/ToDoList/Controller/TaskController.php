<?php

namespace ToDoList\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ToDoList\Form\TaskForm;

class TaskController extends AbstractActionController
{
    public function listAction()
    {
        $form = new TaskForm;
        return array('form' => $form);
    }

}
