<?php

namespace ToDoList\Controller\Plagin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ConvertDate extends AbstractPlugin {
    
    public function __invoke($format, $date_str) {
        $date = new \DateTime($date_str);
        return $date->format($format);
    }
}
