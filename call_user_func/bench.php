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
    '+-%s-+-%s-+----------+----------+---------------------+' . PHP_EOL,
    str_pad('',  40, '-'),
    str_pad('',  40, '-')
);

printf(
    '| %40s VS %39s | %8s | %8s | %s |' . PHP_EOL,
    '',
    '',
    'X',
    'Y',
    '(1 - (Y / X)) * 100'
);

printf(
    '+-%s-+-%s-+----------+----------+---------------------+' . PHP_EOL,
    str_pad('',  40, '-'),
    str_pad('',  40, '-')
);

foreach($global_bench as $case => $average) {
    foreach($global_bench as $case_comp => $average_comp) {
        if($case !== $case_comp) {
            printf(
                '| %40s | %40s | %.5fs | %.5fs | %18s%% |' . PHP_EOL,
                $case,
                $case_comp,
                $average,
                $average_comp,
                round((1 - ($average_comp / $average)) * 100, 2)
            );
        }
    }

    printf(
        '+-%s-+-%s-+----------+----------+---------------------+' . PHP_EOL,
        str_pad('',  40, '-'),
        str_pad('',  40, '-')
    );
}