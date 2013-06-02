<?php

namespace {

require_once '/Users/jubianchi/repositories/jubianchi/PHPSandbox/praspel-atoum/Sut.php';

}

namespace tests\units {

class Sut extends \Hoathis\Atoum\Test {

    /**
     * Method: \Sut::m1.
     * Location: /Users/jubianchi/repositories/jubianchi/PHPSandbox/praspel-atoum/Sut.php#9.
     * Hash: 13f9fafc76636c6e4d20127309235c95.
     * Specification:
     * 
     *     @requires x: 0..256 and y: 'foo';
     *     @ensures  \result: false;
     */

    public function test m1 n°1 ( ) {
        $this
            ->if($this->requires['x']->in = $this->generator->boundint(0, 256)->generate())
            ->and($this->requires['y']->in = 'foo')
            ->and($this->ensures['\result']->in = true)
            ->then
                ->praspel->verdict(new \Sut());

        return;
    }

    public function test m1 n°2 ( ) {

        $this
            ->if($this->requires['x']->in = $this->generator->boundint(0, 256)->generate())
            ->and($this->requires['y']->in = 'foo')
            ->then
                ->praspel->verdict(new \Sut());

        return;
    }

	/**
	 * @dataProvider praspelDataProvider
	 */
	public function test m1 n°3 ( $x, $y ) {
		$this
			->if($object = new \Sut())
			->then
				->boolean($object->m1($x, $y))->isFalse();

		return;
	}

	public function praspelDataProvider ( ) {
		return $this->generator->provide(
			$this->generator->array()
				->ofSize(2)
				->with($this->generator->smallint),
			5
		);
	}

    /**
     * Method: \Sut::m2.
     * Location: /Users/jubianchi/repositories/jubianchi/PHPSandbox/praspel-atoum/Sut.php#19.
     * Hash: 88fb4c1fc219316e6dacf4e6e0be4a92.
     * Specification:
     * 
     *     @requires x: 'foo';
     *     @ensures  \result: 'bar';
     */

    public function test m2 n°1 ( ) {

        $this
            ->if($this->requires['x']->in = 'foo')
            ->and($this->ensures['\result']->in = 'bar')
            ->then
                ->praspel->verdict(new \Sut());

        return;
    }

    public function test m2 n°2 ( ) {

        $this
            ->if($this->requires['x']->in = 'foo')
            ->then
                ->praspel->verdict(new \Sut());

        return;
    }
}

}
