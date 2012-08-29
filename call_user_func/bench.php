<?php
define('ITERATION', isset($argv[1]) ? $argv[1] : 1000);
printf("\033[33m--> Number of iterations : %d\033[0m" . PHP_EOL, ITERATION);

$iterator = new DirectoryIterator(__DIR__ . '/cases');
$global_bench = array();

foreach(new IteratorIterator($iterator) as $file) {
    $file = basename($file);
    $path = __DIR__ . DIRECTORY_SEPARATOR . 'cases' . DIRECTORY_SEPARATOR . $file;

    if(false === in_array($file, array('.', '..')) && true === is_file($path)) {
        printf("\033[32m---> %s\033[0m" . PHP_EOL, $file);

        $case_start = microtime(true);
        $global_bench[$file] = include $path;
        $case_end = microtime(true);

        printf("\033[32m---> Case duration : %.5fs\033[0m" . PHP_EOL . PHP_EOL, ($case_end - $case_start));
    }
}

printf(
    '+-%s-+-%s-+--------------+--------------+-----------------------+' . PHP_EOL,
    str_pad('',  35, '-'),
    str_pad('',  35, '-')
);

printf(
    '| %35s VS %-34s | %12s | %12s | %s |' . PHP_EOL,
    'X',
    'Y',
    'X_ = AVG(X)',
    'Y_ = AVG(Y)',
    '(1 - (Y_ / X_)) * 100'
);

printf(
    '+-%s-+-%s-+--------------+--------------+-----------------------+' . PHP_EOL,
    str_pad('',  35, '-'),
    str_pad('',  35, '-')
);

foreach($global_bench as $case => $average) {
    foreach($global_bench as $case_comp => $average_comp) {
        $case = basename($case, '.php');
        $case_comp = basename($case_comp, '.php');

        if($case !== $case_comp) {
            printf(
                '| %35s | %35s | %11ss | %11ss | %20s%% |' . PHP_EOL,
                $case,
                $case_comp,
                round($average, 5),
                round($average_comp, 5),
                round((1 - ($average_comp / $average)) * 100, 2)
            );
        }
    }

    printf(
        '+-%s-+-%s-+--------------+--------------+-----------------------+' . PHP_EOL,
        str_pad('',  35, '-'),
        str_pad('',  35, '-')
    );
}