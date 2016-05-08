<?php

namespace ToDoList\Model;

use Zend\InputFilter\InputFilter;

class Comment {
    public $id;
    public $name;
    public $text;
    public $date;
    public $task_id;
    protected $inputFilter;

    public function exchangeArray($data){
        foreach ($data as $key => $val){
            if(property_exists($this, $key)){
                $this->$key = $val;
            }
        }
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function getInputFilter() {
        if (!$this->inputFilter){
            $filter = new InputFilter();
            
            $filter->add(array(
                'name' => 'name',
                'required' => TRUE,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[-\w ]{1,255}$/u',
                        ),
                    ),
                ),
            )); 
            
            $filter->add(array(
                'name' => 'text',
                'required' => TRUE,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                    array(
                        'name' => 'HtmlEntities',
                        'options' => array(
                            'quotestyle' => ENT_QUOTES,
                        ),
                    ),
                ),
            ));
            
            $this->inputFilter = $filter;
        }
        return $this->inputFilter;
    }
    
}
