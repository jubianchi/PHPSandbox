#!/usr/bin/env php
<?php
require_once __DIR__ . '/config.php';

$worker = new GearmanWorker();
$worker->addServer(GEARMAN_SERVER_IP);

$socket = null;

$worker->addFunction(
    'atoum',
    function(GearmanJob $job) {
        $workload = $job->workload();

        echo 'Received job: ' . $job->handle() . PHP_EOL;
        echo 'Workload: ' . $workload . PHP_EOL;

        $pipes = array();
        $proc = proc_open(
            'vendor/bin/atoum -ft -d ' . 'tests/',
            array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "w")
            ),
            $pipes,
            __DIR__ . '/../3284862/'
        );

        stream_set_blocking($pipes[1], 0);

        $read = array($pipes[1]);
        $write = array($pipes[1]);
        $expect = array($pipes[1]);

        $status = proc_get_status($proc);
        while($status['running'] === true) {
            $result = stream_select($read, $write, $expect, 1);
            if($result) {
                $cnt = stream_get_contents($pipes[1]);
                $job->sendData($cnt);
                writeSocket($cnt, GEARMAN_SERVER_IP, GEARMAN_SERVER_PORT);
                print $cnt;
            }

            $status = proc_get_status($proc);
        }

        return $status['exitcode'] ?: 0;
    }
);

while(true) {
    print 'Waiting for job...' . PHP_EOL;

    $ret= $worker->work();
    if($worker->returnCode() != GEARMAN_SUCCESS) {
        print 'End of job (Status : ' . $worker->returnCode() . ')' . PHP_EOL;
        break;
    }
}

function getSocket($address, $port) {
    $socket = socket_create( AF_INET, SOCK_STREAM, 0 );
    if ( $socket === FALSE ) {
        throw new Exception(
            "socket_create failed: reason: " . socket_strerror( socket_last_error() ));
    }

    $result = socket_connect($socket, $address, $port);
    if ($result === false) {
        throw new Exception("socket_connect() failed.\nReason: ($result) " .
            socket_strerror(socket_last_error($socket)));
    }
    return $socket;
}

function writeSocket($stmt, $address, $port) {
    $socket = getSocket($address, $port);
    //socket_write($socket, $stmt, strlen($stmt));
    socket_send($socket, $stmt, strlen($stmt), MSG_DONTROUTE);
}
