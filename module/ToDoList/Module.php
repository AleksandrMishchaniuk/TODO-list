<?php

namespace ToDoList;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use ToDoList\Model\Task;
use ToDoList\Model\TaskTable;
use ToDoList\Model\Comment;
use ToDoList\Model\CommentTable;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'ToDoList/TaskTable' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $rs = new ResultSet();
                    $rs->setArrayObjectPrototype(new Task());
                    $tableGateway = new TableGateway('tasks', $dbAdapter, NULL, $rs);
                    return new TaskTable($tableGateway);
                },
                'ToDoList/CommentTable' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $rs = new ResultSet();
                    $rs->setArrayObjectPrototype(new Comment());
                    $tableGateway = new TableGateway('comments', $dbAdapter, NULL, $rs);
                    return new CommentTable($tableGateway);
                },
            ),
        );
    }
    
    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller      = $e->getTarget();
            $controller->layout('todo-list/layout');
        }, 100);
    }

}
