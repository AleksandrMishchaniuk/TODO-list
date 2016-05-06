<?php
namespace ToDoList\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ToDoList\Controller\TaskController;

 class TaskControllerFactory implements FactoryInterface
 {
     /**
      * Create service
      *
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
     public function createService(ServiceLocatorInterface $serviceLocator)
     {
        $sm = $serviceLocator->getServiceLocator();
        $taskTable = $sm->get('ToDoList/TaskTable');
        $commentTable = $sm->get('ToDoList/CommentTable');
        return new TaskController($taskTable, $commentTable);
     }
 }