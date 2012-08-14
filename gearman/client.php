#!/usr/bin/env php
<?php
require_once __DIR__ . '/config.php';

$client = new GearmanClient();
$client->addServer(GEARMAN_SERVER_IP);

if(($socket = socket_create(AF_INET, SOCK_STREAM, 0)) < 0)
{
    echo "failed to create socket: " . socket_strerror($socket)."\n";
    exit($socket);
}

if(($ret = socket_bind($socket, GEARMAN_SERVER_IP, GEARMAN_SERVER_PORT)) < 0)
{
    echo "failed to bind socket: ".socket_strerror($ret)."\n";
    exit(abs($ret));
}

if(($ret = socket_listen($socket, 0 )) < 0)
{
    echo "failed to listen to socket: ".socket_strerror($ret)."\n";
    exit(abs($ret));
}

socket_set_nonblock($socket);

$task = $client->addTaskBackground('atoum', 'atoum');
$client->runTasks();

echo "waiting for clients to connect\n";

while (true)
{
    $connection = @socket_accept($socket);
    if ($connection === false)
    {
        usleep(100);
    } elseif ($connection > 0) {
        $pid = pcntl_fork();

        if ($pid === -1)
        {
            echo "fork failure!\n";
            break;
        } elseif ($pid === 0) {
            socket_close($socket);
            $buff = '';
            socket_recv($connection, $buff, 65536, MSG_EOF);
            socket_close($connection);
            print $buff;
        } else {
            socket_close($connection);
        }
    } else {
        echo "error: " . socket_strerror($connection);
        break;
    }
}