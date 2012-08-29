<?php
require_once __DIR__ . '/resources/Foo.php';

$bench = array();

for($i = 0; $i < ITERATION; $i++)
{
    $start = microtime(true);

    $foo = new Foo();
    $foo->cufa();

    $end = microtime(true);
    $bench[$i] = $end - $start;
}

printf("\033[33m----> Total time : %.5fs\033[0m" . PHP_EOL, array_sum($bench));
printf("\033[33m----> Average time : %.5fs\033[0m" . PHP_EOL, array_sum($bench) / ITERATION);
printf("\033[33m----> Max time : %.5fs\033[0m" . PHP_EOL, max($bench));
printf("\033[33m----> Min time : %.5fs\033[0m" . PHP_EOL, min($bench));

return array_sum($bench);
