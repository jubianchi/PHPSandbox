<?php
namespace tests\unit\jubianchi\Config;

use mageekguy\atoum;
use jubianchi\Config\Table as TestedClass;

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';
require_once dirname(__DIR__) . '/../../../vendor/mageekguy/atoum/scripts/runner.php';

class Table extends atoum\test {
    public function testConstants() {
        $this
            ->string(TestedClass::TYPE_NAME)->isEqualTo('table')
            ->string(TestedClass::CLASS_ATTR)->isEqualTo('class')
            ->string(TestedClass::COLS_ATTR)->isEqualTo('columns')
            ->string(TestedClass::ROWS_ATTR)->isEqualTo('rows')
        ;
    }

    public function testGetType() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->string($object->getType())->isEqualTo(TestedClass::TYPE_NAME)
        ;
    }

    public function testGetFields() {
        $this
            ->if($object = new TestedClass())
            ->then
                ->array($object->getFields())->isIdenticalTo(array(TestedClass::CLASS_ATTR, TestedClass::ROWS_ATTR, TestedClass::COLS_ATTR))
        ;
    }

    public function testBuild() {
        $this
            ->if($object = new TestedClass())

            ->and($builder = new \mock\Symfony\Component\Config\Definition\Builder\NodeBuilder())
            ->and($root = new \mock\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition(uniqid()))
            ->and($node = new \mock\Symfony\Component\Config\Definition\Builder\NodeDefinition(uniqid()))

            ->and($builder->getMockController()->scalarNode = function() use($node) { return $node; })
            ->and($builder->getMockController()->arrayNode = function() use($root) { return $root; })

            ->and($root->getMockController()->children = function() use($builder) { return $builder; })
            ->and($root->getMockController()->end = function() use($builder) { return $builder; })

            ->and($node->getMockController()->end = function() use($builder) { return $builder; })
            ->and($node->getMockController()->isRequired = function() use($node) { return $node; })

            ->then
                ->variable($object->build($root))->isNull()
                ->mock($root)
                    ->call('children')->once()
        ;
    }
}