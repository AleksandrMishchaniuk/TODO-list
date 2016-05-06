<?php

namespace ToDoList\Form;
use Zend\Form\Form;

class CommentForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Comment');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'comment_form');
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
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Текст задачи',
                'min' => 1,
                'max' => 255,
            ),
        ));
        
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
            'name' => 'task_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Добавить комментарий',
                'class' => 'btn btn-primary',
            ),
        ));
    }
    
}
