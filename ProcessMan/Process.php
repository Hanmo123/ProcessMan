<?php

namespace ProcessMan;

use Workerman\Connection\TcpConnection;

class Process
{
    private static $desc = [
        ['pipe', 'r'],
        ['pipe', 'w'],
        ['pipe', 'w'],
    ];
    // proc_open 函数所需要的 Description

    public function __construct($worker, $event, $id, $cmd, $option)
    {
        $process = proc_open($cmd, static::$desc, $pipes);
        // 启动进程 开启管道

        stream_set_blocking($pipes[0], 0);
        // 设置管道 0 为非阻塞模式

        $this->proc = [
            'process'   => $process,
            'pipes'     => $pipes,
            'stdout'    => new TcpConnection($pipes[1]),
            'stderr'    => new TcpConnection($pipes[2]),
            'event'     => $event
        ];
        // 保存必要参数

        $this->proc['stdout']->onMessage = function ($conn, $data) use ($event) {
            $event['onStdout']($this, $data);
        };
        // 收到 STDOUT 时触发事件
        $this->proc['stderr']->onMessage = function ($conn, $data) use ($event) {
            $event['onStderr']($this, $data);
        };
        // 收到 STDERR 时触发事件

        // $worker->_proc[$id] = $this->proc;

        $event['onCreated']($this);
        // 触发 进程创建 事件
    }

    public function input($data)
    {
        if (@$this->proc['event']['onStdin']) {
            // 事件已被注册 触发事件
            if (!$this->proc['event']['onStdin']($this, $data) === false) {
                // 事件未被取消 将标准输入写入管道
                fwrite($this->proc['pipes'][0], $data . PHP_EOL);
            }
        }else{
            // 事件未被注册 直接输入
            fwrite($this->proc['pipes'][0], $data . PHP_EOL);
        }
    }

    public function test()
    {
        echo 2;
    }
}
