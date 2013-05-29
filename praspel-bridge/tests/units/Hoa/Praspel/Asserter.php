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
            ->if($generator = new \mock\mageekguy\atoum\asserter\generator())
            ->and($object = new TestedClass($generator))
            ->then
                ->object($object->getGenerator())->isIdenticalTo($generator)
        ;
    }
}