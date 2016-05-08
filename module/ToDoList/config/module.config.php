<?php
return array(
    'controllers' => array(
        'factories' => array(
            'ToDoList\Controller\Task' => 'ToDoList\Factory\TaskControllerFactory',
            'ToDoList\Controller\Comment' => 'ToDoList\Factory\CommentControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'todolist' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/[:controller[/:action[/:id]]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                    ),
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'ToDoList\Controller',
                        'controller'    => 'Task',
                        'action'        => 'list',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'todo-list/layout'  => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'ToDoList' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
