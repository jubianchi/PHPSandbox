<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/hoa/core/Core.php';

from('Hoathis')
    -> import('Atoum.Praspel.Generator');

class A {

    /**
     * @requires x: 0..256 and y: 'foo';
     * @ensures  \result: boolean();
     */
    public function m1 ( $x, $y ) { }

    /**
     * @requires x: 'foo';
     * @ensures  \result: 'bar';
     */
    public function m2 ( $x ) { }
}

$generator = new Hoathis\Atoum\Praspel\Generator();
echo $generator->generate(new \ReflectionClass('A'));