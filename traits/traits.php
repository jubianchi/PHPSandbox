<?php
namespace jubianchi;

use jubianchi\Traits\Foo;

require_once __DIR__ . '/Traits/Foo.php';

class Bar
{
    use Foo;
}

xdebug_start_code_coverage();

$b = new Bar();
$b->foo();

var_dump(xdebug_get_code_coverage());

$reflector = new \ReflectionClass('\\jubianchi\\Bar');
var_dump(
    $reflector->getMethod('foo')->getFileName(),
    $reflector->getMethod('foo')->getStartLine(),
    $reflector->getMethod('foo')->getEndLine()
);
