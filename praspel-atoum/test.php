<?php
namespace {
    class SUT {
        public function M1() {
            return true;
        }

        public function M2() {
            return 'bar';
        }
    }
}

namespace tests\units {
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/vendor/hoa/core/Core.php';
    require_once __DIR__ . '/vendor/atoum/atoum/scripts/runner.php';

    from('Hoathis')
        ->import('Atoum.Test.Asserter.Praspel')
        ->import('Atoum.Test');

    class SUT extends \Hoathis\Atoum\Test {

        public function test M1 n°1 ( ) {

            $this
                ->if($this->requires['x']->in = realdom()->boundinteger(0, 256))
                ->and($this->requires['y']->in = realdom()->const('foo'))
                ->and($this->ensures['\result']->in = realdom()->boolean())
                ->then
                    ->praspel->verdict(new \SUT())
            ;
        }

        public function testM2n°1 ( ) {

            $this
                ->if($this->requires['x']->in = realdom()->const('foo'))
                ->and($this->ensures['\result']->in = realdom()->const('bar'))
                ->then
                    ->praspel->verdict(new \SUT())
            ;
        }

        public function testFoobarn°1 ( ) {

            $this
                ->if($this->requires['x']->in = realdom()->const('foo'))
                ->and($this->ensures['\result']->in = realdom()->const('bar'))
                ->then
                    ->praspel->verdict(new \SUT(), "M2")
            ;
        }
    }
}
