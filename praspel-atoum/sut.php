<?php

class Sut
{
    /**
     * @requires x: 0..256 and y: 'foo';
     * @ensures  \result: false;
     */
    public function m1($x, $y)
	{
		return ($x <= 256 && $y === 'foo');
	}

    /**
     * @requires x: 'foo';
     * @ensures  \result: 'bar';
     */
    public function m2($x)
	{
		return $x === 'foo' ? 'bar' : 'baz';
	}
}
