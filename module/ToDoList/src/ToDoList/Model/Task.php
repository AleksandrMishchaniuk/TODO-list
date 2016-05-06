<?php

namespace ToDoList\Model;

use Zend\InputFilter\InputFilter;

class Task {
    public $id;
    public $text;
    public $status;
    public $deadline;
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
            
            $filter->add(array(
                'name' => 'status',
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[01]$/',
                        ),
                    ),
                ),
            ));
            
            $filter->add(array(
                'name' => 'deadline',
                'validators' => array(
                    array(
                        'name' => 'Date',
                    ),
                ),
            ));
            
            $this->inputFilter = $filter;
        }
        return $this->inputFilter;
    }
    
}
