<?php
namespace tests\units\Hoa\Praspel;

use atoum;
use Hoa\Praspel\Asserter as TestedClass;

require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../bootstrap.php';

class Asserter extends atoum
{
    public function testClass()
    {
        $this
            ->testedClass->isSubclassOf('\\mageekguy\\atoum\\asserter')
        ;
    }

    public function test__construct()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->getGenerator())->isInstanceOf('\\mageekguy\\atoum\\asserter\\generator')
                ->object($object->getSpecification())->isInstanceOf('\\Hoa\\Praspel\\Model\\Specification')
            ->if($generator = new \mock\mageekguy\atoum\asserter\generator())
            ->and($specification = new \mock\Hoa\Praspel\Model\Specification())
            ->and($object = new TestedClass($specification, $generator))
            ->then
                ->object($object->getGenerator())->isIdenticalTo($generator)
                ->object($object->getSpecification())->isIdenticalTo($specification)
        ;
    }
}