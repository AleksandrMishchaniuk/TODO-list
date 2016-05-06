<?php

namespace ToDoList\Form;
use Zend\Form\Form;

class TaskForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Task');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'task_form');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal');
//        
//        $this->add(array(
//            'name' => 'id',
//            'attributes' => array(
//                'type' => 'hidden',
//                'value' => 0,
//            ),
//        ));
        
        $this->add(array(
            'name' => 'text',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Текст задачи',
                'min' => 1,
            ),
        ));
        
        $this->add(array(
            'name' => 'status',
            'attributes' => array(
                'type' => 'hidden',
                'value' => 0,
            ),
        ));
        
        $this->add(array(
            'name' => 'deadline',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control datapicker',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Дата выполнения',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Добавить задачу',
                'class' => 'btn btn-primary',
            ),
        ));
    }
    
}
