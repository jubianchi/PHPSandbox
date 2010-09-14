<?php
/**
 * Copyright 2010 jubianchi.fr
 * 
 * @author jubianchi
 */

require_once('../Caller.php');

function foo() {
	$c = Caller::get();
	
	echo '<pre>';
	echo "\n" . 'Caller::isFunction()';
	var_dump(Caller::isFunction());
	echo 'Caller::isClassMethod()';
	var_dump(Caller::isClassMethod());
	
	echo 'Caller::get()';
	var_dump($c);
}
function fooTrace() {
	$t = Caller::trace();
	
	echo 'Caller::trace()';
	var_dump($t);
	echo '</pre><hr />';
}
function bar() {
	foo();
	fooTrace();	
}

$closure = function() {
	foo();
	fooTrace();
};

class Buz {
	function foo() {
		foo();
		fooTrace();
	}
	function bar() {
		$b = new Baz();
		$b -> bar();
	}
}
class Baz {
	function foo() {
		foo();
		fooTrace();
	}
	function bar() {
		bar();
	}
	public static function buz() {
		foo();
		fooTrace();
	}
}

echo 'Caller::get() : ';
$c = Caller::get();
var_dump($c);
echo '<hr />';

echo 'foo() : ';
foo();
fooTrace();

echo 'bar(1, \'foo\', 3) : ';
bar(1, 'foo', 3);

echo '$closure() : ';
$closure();

$buz = new Buz();
echo '$buz -> foo() : ';
$buz -> foo();
echo '$buz -> bar() : ';
$buz -> bar();

$baz = new Baz();
echo '$baz -> foo() : ';
$baz -> foo();
echo '$baz -> bar() : ';
$baz -> bar();
echo 'Baz::buz() : ';
Baz::buz();
?>