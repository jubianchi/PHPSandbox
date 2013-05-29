<?php
namespace tests\units\Hoa\Praspel\Asserters;

use atoum;
use Hoa\Praspel\Asserters\Praspel as TestedClass;

require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../../bootstrap.php';

class Praspel extends atoum
{
    public function testEnsures()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->ensures())->isInstanceOf('\\Hoa\\Praspel\\Asserter')
        ;
    }

    public function testRequires()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->requires())->isInstanceOf('\\Hoa\\Praspel\\Asserter')
        ;
    }

    public function testVerdict()
    {
        $this
            ->if($generator = new \mock\mageekguy\atoum\asserter\generator())
            ->and($spec = new \mock\Hoa\Praspel\Model\Specification())
            ->and($this->calling($spec)->verdict = false)
            ->and($object = new TestedClass($spec, $generator))
            ->then
                ->exception(function() use ($object) {
                    $object->verdict();
                })
                    ->hasMessage('verdict failed!')
            ->if($message = uniqid())
            ->then
                ->exception(function() use ($object, $message) {
                    $object->verdict($message);
                })
                    ->hasMessage($message)
            ->if($this->calling($spec)->verdict = true)
            ->then
                ->object($object->verdict())->isIdenticalTo($object)
        ;
    }

    public function testSpecification()
    {
        $this
            ->if($generator = new \mock\mageekguy\atoum\asserter\generator())
            ->and($praspel = new \mock\Hoa\Praspel())
            ->and($object = new TestedClass(null, $generator))
            ->then
                ->object($ensures = $object->ensures(uniqid()))->isInstanceOf('\\Hoa\\Praspel\\Asserters\\Ensures')
                ->object($ensures->getGenerator())->isIdenticalTo($object->getGenerator())
                ->object($requires = $object->requires(uniqid()))->isInstanceOf('\\Hoa\\Praspel\\Asserters\\Requires')
                ->object($requires->getGenerator())->isIdenticalTo($object->getGenerator())
        ;
    }
}
