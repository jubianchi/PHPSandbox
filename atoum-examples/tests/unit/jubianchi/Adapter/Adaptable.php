<?php
namespace tests\unit\jubianchi\Adapter;

use
    mageekguy\atoum,
    jubianchi\Adapter\Adaptable as TestedClass
;

require_once __DIR__ . '/../../../../vendor/autoload.php';


class Adaptable extends atoum\test
{

    public function testSetAdapter()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->setAdapter($adapter = new \jubianchi\Adapter\Test\Adapter()))->isIdenticalTo($object)
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }

    public function testGetAdapter()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->getAdapter())->isInstanceOf('\\jubianchi\Adapter\\AdapterInterface')

            ->if($object->setAdapter($adapter = new \jubianchi\Adapter\Test\Adapter()))
            ->then
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }
}