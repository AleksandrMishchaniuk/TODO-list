<?php
namespace ToDoList\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ToDoList\Controller\CommentController;

 class CommentControllerFactory implements FactoryInterface
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
        $commentTable = $sm->get('ToDoList/CommentTable');
        return new CommentController($commentTable);
     }
 }