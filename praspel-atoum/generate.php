<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/hoa/core/Core.php';

from('Hoathis')
	-> import('Atoum.Praspel.Generator');

$if = $_SERVER['argv'][1];
$cls = basename($if, '.php');
$of = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : $cls . 'Test.php';

require_once $if;

$generator = new Hoathis\Atoum\Praspel\Generator();
file_put_contents($of, $generator->generate(new \ReflectionClass($cls)));