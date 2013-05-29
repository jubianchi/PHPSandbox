<?php
namespace tests\units\Hoa\Praspel;

use atoum;
use Hoa\Praspel\Test as TestedClass;

require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../bootstrap.php';

class Test extends atoum
{
    public function testClass()
    {
        $this
            ->testedClass->isSubclassOf('\\mageekguy\\atoum\\test')
        ;
    }

    public function test__construct()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->getPraspel())->isInstanceOf('\\Hoa\\Praspel')
            ->if($praspel = new \mock\Hoa\Praspel())
            ->and($object = new TestedClass($praspel))
            ->then
                ->object($object->getPraspel())->isIdenticalTo($praspel)
            ->if($manager = new \mock\mageekguy\atoum\test\assertion\manager())
            ->and($object = new TestedClass(null, null, null, null, $manager, null))
            ->then
                ->mock($manager)->call('setHandler')->withArguments('praspel')->once()
                ->mock($manager)->call('setHandler')->withArguments('ensures')->once()
                ->mock($manager)->call('setHandler')->withArguments('requires')->once()
        ;
    }

    public function testGetSetPraspel()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->setPraspel())->isIdenticalTo($object)
                ->object($object->getPraspel())->isInstanceOf('\\Hoa\\Praspel')
            ->if($praspel = new \mock\Hoa\Praspel())
            ->then
                ->object($object->setPraspel($praspel))->isIdenticalTo($object)
                ->object($object->getPraspel())->isIdenticalTo($praspel)
        ;
    }

    public function testSetAssertionManager()
    {
        $this
            ->if($manager = new \mock\mageekguy\atoum\test\assertion\manager())
            ->and($object = new TestedClass(null, null, null, null, $manager, null))
            ->then
                ->mock($manager)->call('setHandler')->withArguments('praspel')->once()
                ->mock($manager)->call('setHandler')->withArguments('ensures')->once()
                ->mock($manager)->call('setHandler')->withArguments('requires')->once()
        ;
    }

    public function testHandlers()
    {
        $this
            ->if($praspel = new \mock\Hoa\Praspel())
            ->and($object = new TestedClass($praspel, null, null))
            ->then
                ->object($ensures = $object->ensures(uniqid()))->isInstanceOf('\\Hoa\\Praspel\\Asserters\\Ensures')
                ->object($ensures->getGenerator())->isIdenticalTo($object->getAsserterGenerator())
                ->object($requires = $object->requires(uniqid()))->isInstanceOf('\\Hoa\\Praspel\\Asserters\\Requires')
                ->object($requires->getGenerator())->isIdenticalTo($object->getAsserterGenerator())
        ;
    }
}