<?php
namespace tests\unit\jubianchi\Config;

use mageekguy\atoum;
use jubianchi\Config\Validator as TestedClass;

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';
require_once dirname(__DIR__) . '/../../../vendor/mageekguy/atoum/scripts/runner.php';

/**
 * @ignore on
 */
class FooType implements \jubianchi\Config\Type {
    static function getType()
    {
        return 'foo';
    }

    static function getFields()
    {
        return array('foo', 'bar');
    }

    static function build(\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root)
    {
    }
}

/**
 * @ignore on
 */
class BarType implements \jubianchi\Config\Type {
    static function getType()
    {
        return 'bar';
    }

    static function getFields()
    {
        return array('foo', 'bar');
    }

    static function build(\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root)
    {
    }
}

class Validator extends atoum\test {
    public function testGetNewBuilder() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($builder = $object->getNewBuilder())->isInstanceOf('\\Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder')
                ->object($object->getNewBuilder())->isNotIdenticalTo($builder)
        ;
    }

    public function testGetNewProcessor() {
        $this
            ->if($object = new TestedClass())
            ->then
            ->object($processor = $object->getNewProcessor())->isInstanceOf('\\Symfony\\Component\\Config\\Definition\\Processor')
            ->object($object->getNewProcessor())->isNotIdenticalTo($processor)
        ;
    }

    public function testGetBuilder() {
        $this
            ->if($object = new \mock\jubianchi\Config\Validator())
            ->then
                ->object($builder = $object->getBuilder())->isInstanceOf('\\Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder')
                ->object($object->getBuilder())->isIdenticalTo($builder)
                ->mock($object)
                    ->call('getNewBuilder')->once()
        ;
    }

    public function testGetRoot() {
        $this
            ->if($object = new \mock\jubianchi\Config\Validator())
            ->and($object->getMockController()->getBuilder = $builder = new \mock\Symfony\Component\Config\Definition\Builder\TreeBuilder())
            ->then
                ->object($root = $object->getRoot())->isInstanceOf('\\Symfony\\Component\\Config\\Definition\\Builder\\ArrayNodeDefinition')
                ->object($object->getRoot())->isIdenticalTo($root)
                ->mock($object)
                    ->call('getBuilder')->once()
                ->mock($builder)
                    ->call('root')->withArguments(\jubianchi\Config\Configuration::ROOT_NAME)
        ;
    }

    public function testGetPrototype() {
        $this
            ->if($object = new \mock\jubianchi\Config\Validator())
            ->and($object->getMockController()->getRoot = $root = new \mock\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition(uniqid()))
            ->then
                ->object($prototype = $object->getPrototype())->isInstanceOf('\\Symfony\\Component\\Config\\Definition\\Builder\\NodeDefinition')
                ->object($object->getPrototype())->isIdenticalTo($prototype)
                ->mock($object)
                    ->call('getRoot')->once()
                ->mock($root)
                    ->call('prototype')->withArguments('array')
        ;
    }

    public function testValidate() {
        $this
            ->if($object = new \mock\jubianchi\Config\Validator())
            ->and($object->getMockController()->getBuilder = $builder = new \mock\Symfony\Component\Config\Definition\Builder\TreeBuilder())
            ->and($finder = new \mock\jubianchi\Config\TypeFinder())
            ->and($finder->getMockController()->getValidTypes = array(
                'tests\\unit\\jubianchi\Config\\FooType' => 'foo',
                'tests\\unit\\jubianchi\Config\\BarType' => 'bar'
            ))
            ->then
                ->variable($object->validate(array(), $finder))->isNull()
                ->mock($object)
                    ->call('process')->withArguments(array(), $builder)->once()

            ->if($object->getMockController()->getPrototype = $prototype = new \mock\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition(uniqid()))
            ->and($finder->getMockController()->getValidFields = array('foo', 'bar'))
            ->and($prototype->getMockController()->append = function() use($prototype) { return $prototype; })
            ->then
                ->variable($object->validate(array(), $finder))->isNull()
                ->mock($prototype)
                    ->call('append')->withArguments(new \Symfony\Component\Config\Definition\Builder\VariableNodeDefinition('foo'))->once()
                    ->call('append')->withArguments(new \Symfony\Component\Config\Definition\Builder\VariableNodeDefinition('bar'))->once()

            ->if($finder->getMockController()->getValidTypes = array())
            ->then
                ->exception(function() use($object, $finder) {
                        $object->validate(array(), $finder);
                    })
                    ->isInstanceOf('\\RuntimeException')
                    ->hasMessage('No valid types found')
        ;
    }

    public function testProcess() {
        $this
            ->if($object = new \mock\jubianchi\Config\Validator())
            ->and($object->getMockController()->getNewProcessor = $processor = new \mock\Symfony\Component\Config\Definition\Processor())
            ->and($builder = new \mock\Symfony\Component\Config\Definition\Builder\TreeBuilder())
            ->and($builder->root(uniqid()))
            ->then
                ->variable($object->process(array(), $builder))->isNull()
                ->mock($processor)
                    ->call('process')->withArguments($builder->buildTree(), array())->once()
        ;
    }
}