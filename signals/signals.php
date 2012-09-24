<?php
declare(ticks = 1);

// Signals declaraion mapping (maps PHP's constants to their name)
// Additionaly, you'll find the signal code to use with the kill command :
// $ kill -N PID (N is the signal code and PID the process ID as returned by the script)
$signals = array(
    SIGHUP        => 'SIGHUP',  //1
    SIGINT        => 'SIGINT',  //2
    SIGQUIT       => 'SIGQUIT', //3
    SIGILL        => 'SIGILL',  //4
    SIGTRAP       => 'SIGTRAP', //5
    SIGABRT       => 'SIGABRT', //6
    SIGIOT        => 'SIGIOT',
    SIGBUS        => 'SIGBUS',
    SIGFPE        => 'SIGFPE',  //8
    SIGKILL       => 'SIGKILL', //9
    SIGUSR1       => 'SIGUSR1',
    SIGSEGV       => 'SIGSEGV', //11
    SIGUSR2       => 'SIGUSR2',
    SIGPIPE       => 'SIGPIPE', //13
    SIGALRM       => 'SIGALRM', //14
    SIGTERM       => 'SIGTERM', //15
    SIGSTKFLT     => 'SIGSTKFLT',
    SIGCLD        => 'SIGCLD',
    SIGCHLD       => 'SIGCHLD',
    SIGCONT       => 'SIGCONT',
    SIGSTOP       => 'SIGSTOP',
    SIGTSTP       => 'SIGTSTP',
    SIGTTIN       => 'SIGTTIN',
    SIGTTOU       => 'SIGTTOU',
    SIGURG        => 'SIGURG',
    SIGXCPU       => 'SIGXCPU',
    SIGXFSZ       => 'SIGXFSZ',
    SIGVTALRM     => 'SIGVTALRM',
    SIGPROF       => 'SIGPROF',
    SIGWINCH      => 'SIGWINCH',
    SIGPOLL       => 'SIGPOLL',
    SIGIO         => 'SIGIO',
    SIGPWR        => 'SIGPWR',
    SIGSYS        => 'SIGSYS',
    SIGBABY       => 'SIGBABY'
);

// Simple closure to hide errors coming from PHP
$silent = function($callback, $error) use($handler) {
    $reporting = error_reporting();
    error_reporting(~$error);

    $callback();

    error_reporting($reporting);
};

// A very simple ANSI CLI formatter
$format = function($string, $style) {
    $styles = array(
        'info'      => "\033[0;32m",
        'warning'   => "\033[0;33m",
        'error'     => "\033[0;31m"
    );

    return $styles[$style] . $string . "\033[0m";
};

// Another ANSI CLI formatting function (clears the line)
$reset = function($string) {
    return "\r\033[K" . $string;
};

// The signals handler
$handler = function($signal) use($signals, $format, $reset) {
    print $reset($format(date('Y-m-d H:i:s') . ' - Caught ' . $signals[$signal], 'error')) . PHP_EOL;
    exit($signal);
};

// Script initialization
$silent(
    function() use($signals, $handler, $format) {
        print $format('Process ID : ' . posix_getpid(), 'warning') . PHP_EOL;

        // Register an handler for each declared signal
        foreach($signals as $sig => $name) {
            if(false === pcntl_signal($sig, $handler)) {
                print $format('✗', $style = 'error');
            } else {
                print $format('✓', $style = 'info');
            }

            print $format(' : ' . $name, $style);

            print PHP_EOL;
        }
    },
    E_WARNING
);

// A simple loop...
$i = 0;
while(true) {
    $str = str_pad('>', $i, '-', STR_PAD_LEFT);
    $str = str_pad($str, 40, '-', STR_PAD_RIGHT);

    print "\r" . $str;

    if(++$i > 40) {
        $i = 0;
    }
    
    if(fgets(STDIN) == "\n") {
        echo 'ok';
    }	

    sleep(1);
}
