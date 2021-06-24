<?php

use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Timer;

require_once __DIR__ . '/vendor/autoload.php';
$worker = new Worker();
$worker->onWorkerStart = function ($worker) {
    $desc = [
        ['pipe', 'r'],
        ['pipe', 'w'],
        ['pipe', 'w'],
    ];

    $worker->process = proc_open('php _exec.php', $desc, $pipes);
    $worker->pipes = $pipes;
    stream_set_blocking($pipes[0], 0);
    $worker->stdout = new TcpConnection($pipes[1]);
    $worker->stderr = new TcpConnection($pipes[2]);
    $worker->stdout->onMessage = $worker->stderr->onMessage = function ($conn, $data) {
        echo $data;
    };
    Timer::add(1, function () {
        global $worker;
        fwrite($worker->pipes[0], mt_rand().PHP_EOL);
    });
};
Worker::runAll();
