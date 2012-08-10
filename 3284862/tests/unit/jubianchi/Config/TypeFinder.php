<?php
namespace tests\unit\jubianchi\Config;

use mageekguy\atoum;
use jubianchi\Config\TypeFinder as TestedClass;

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';
require_once dirname(__DIR__) . '/../../../vendor/mageekguy/atoum/scripts/runner.php';

/**
 * @ignore on
 */
class BazType implements \jubianchi\Config\Type {
    static function getType()
    {
        return 'baz';
    }

    static function getFields()
    {
        return array('foo', 'bar');
    }

    static function build(\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root)
    {
    }
}

class TypeFinder extends atoum\test {
    public function testSetAdapter() {
        $this
            ->if($object = new TestedClass())
            ->and($adapter = new \mageekguy\atoum\adapter())
            ->then
                ->object($object->setAdapter($adapter))->isIdenticalTo($object)
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }

    public function testGetAdapter() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->getAdapter())->isInstanceOf('\\mageekguy\\atoum\\adapter')
        ;
    }

    public function testGetValidTypes() {
        $this
            ->if($object = new TestedClass())
            ->and($adapter = new \mageekguy\atoum\test\adapter())
            ->and($object->setAdapter($adapter))
            ->and($adapter->get_declared_classes = array())
            ->then
                ->array($object->getValidTypes())->isEqualTo(array())
                ->adapter($adapter)
                    ->call('get_declared_classes')->once()

            ->if($adapter->get_declared_classes = array('Foo\\Bar', 'Bar\\Foo'))
            ->then
                ->array($object->getValidTypes())->isEqualTo(array())
                ->adapter($adapter)
                    ->call('is_subclass_of')->withArguments('Foo\\Bar', '\\jubianchi\\Config\\Type')->once()
                    ->call('is_subclass_of')->withArguments('Bar\\Foo', '\\jubianchi\\Config\\Type')->once()

            ->if($object = new TestedClass())
            ->then
                ->array($object->getValidTypes())->isEqualTo(array(
                    'tests\\unit\\jubianchi\\Config\\BazType' => 'baz'
                ))
        ;
    }

    public function testGetClassFromType() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->string($object->getClassFromType('baz'))->isEqualTo('tests\\unit\\jubianchi\\Config\\BazType')
                ->variable($object->getClassFromType(uniqid()))->isNull()
        ;
    }

    public function testGetValidFields() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->array($object->getValidFields())->isEqualTo(array('foo', 'bar'))

            ->if($object = new TestedClass())
            ->and($adapter = new \mageekguy\atoum\test\adapter())
            ->and($object->setAdapter($adapter))
            ->and($adapter->get_declared_classes = array())
            ->then
                ->array($object->getValidFields())->isEqualTo(array())
        ;
    }
}