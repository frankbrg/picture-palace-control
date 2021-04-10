<?php

namespace App\Services\Item;

abstract class AbstractItem 
{
    protected $controllerPath;
    protected $name;
    protected $slug;
 
    public function getControllerPath() {
        return $this->controllerPath;
    }

    public function getName() {
        return $this->name;
    }

    public function getSlug() {
        return $this->slug;
    }
}