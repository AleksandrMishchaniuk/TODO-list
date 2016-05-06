<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ToDoList\Controller\Task' => 'ToDoList\Controller\TaskController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'todolist' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/[:controller[:action[/:id]]]',
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
        'template_path_stack' => array(
            'ToDoList' => __DIR__ . '/../view',
        ),
    ),
);
