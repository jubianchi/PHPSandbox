<?php
namespace jubianchi\Config;

use mageekguy\atoum\adapter;

class TypeFinder {
    private $adapter;

    public function setAdapter(adapter $adapter) {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter() {
        if(null === $this->adapter) {
            $this->setAdapter(new adapter());
        }

        return $this->adapter;
    }

    /**
     * This should be changed to a better strategy!!!
     *  * Use a classmap
     *  * Use Symfony2 tagged services
     *
     * @return array
     */
    public function getValidTypes() {
        $classes = $this->getAdapter()->get_declared_classes();
        $types = array();

        foreach($classes as $class) {
            if($this->getAdapter()->is_subclass_of($class, '\\jubianchi\\Config\\Type')) {
                $types[$class] = $class::getType();
            }
        }

        return $types;
    }

    /**
     * @param string $type
     *
     * @return mixed|null
     */
    public function getClassFromType($type) {
        $class = array_search($type, $this->getValidTypes());

        return false !== $class ? $class : null;
    }

    /**
     * @return array
     */
    public function getValidFields() {
        $fields = array();

        foreach($this->getValidTypes() as $class => $type) {
            $fields = array_unique(array_merge($class::getFields(), $fields));
        }

        return $fields;
    }
}
