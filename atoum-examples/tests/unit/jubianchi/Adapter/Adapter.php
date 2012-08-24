<?php
namespace tests\unit\jubianchi\Adapter;

use
    mageekguy\atoum,
    jubianchi\Adapter\Adapter as TestedClass
;

require_once __DIR__ . '/../../../../vendor/autoload.php';

class Adapter extends atoum\test
{
    public function testInvoke()
    {
        $this
            ->if($object = new TestedClass())
            ->and($method = uniqid())
            ->then
                ->exception(
                    function() use($object, $method) {
                        $object->invoke($method);
                    }
                )
                    ->isInstanceOf('\\RuntimeException')
                    ->hasMessage(sprintf('%s is not callable', $method))
        ;
    }

    public function test__call()
    {
        $this
            ->if($object = new \mock\jubianchi\Adapter\Adapter())
            ->and($object->getMockController()->invoke = function() {})
            ->and($method = uniqid())
            ->then
                ->variable($object->$method())
                ->mock($object)
                    ->call('invoke')->withArguments($method)->once()

            ->if($method = uniqid())
            ->then
                ->variable($object->$method($arg = uniqid(), $otherArg = uniqid()))
                ->mock($object)
                    ->call('invoke')->withArguments($method, array($arg, $otherArg))->once()
        ;
    }
}