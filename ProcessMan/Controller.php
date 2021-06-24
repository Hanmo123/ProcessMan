<?php

namespace ProcessMan;

use ProcessMan\Process;

use Workerman\Worker;
use Workerman\Connection\AsyncUdpConnection;

class Controller
{
    public function __construct()
    {
        $this->socketId = md5(uniqid());
        // 生成唯一的 SocketID

        $worker = new Worker('udp://127.0.0.1:9500');
        // 控制器启动一个 Worker 保存进程、管道和 TcpConnection 实例
        $worker->onMessage = function ($conn, $data) use ($worker) {
            // 收到命令 开始处理
            $dataArray = json_decode($data, true);
            $worker->proc[] = new Process($worker, $this->_tempEvent[$dataArray['id']], $dataArray['id'], $dataArray['cmd'], $dataArray['option']);
        };
        // 保存 Worker 对象
        $this->worker = $worker;
    }

    public function createProcess($cmd, $onCreated = NULL, $onStdin = NULL, $onStdout = NULL, $onStderr = NULL, $id = NULL, $option = [])
    {
        if (!@$id) {
            // 未提供 ID 生成唯一 ID
            $id = md5(uniqid());
        }

        $this->_tempEvent[$id] = [
            'onCreated' => $onCreated,
            'onStdin'   => $onStdin,
            'onStdout'  => $onStdout,
            'onStderr'  => $onStderr,
        ];
        // 保存事件 后续操作需读取

        $conn = new AsyncUdpConnection('udp://127.0.0.1:9500');
        $conn->onConnect = function ($conn) use ($cmd, $id, $option) {
            $conn->close(json_encode([
                'type'      => 'createProcess',
                // 'key'       => '__TODO__ KEY',
                'id'        => $id,
                'cmd'       => $cmd,
                'option'    => $option
            ]));
        };
        $conn->connect();
        // 向 Worker 发送命令
    }

    public function onStartEvent($func)
    {
        $this->worker->onWorkerStart = $func;
        // 设置控制器启动事件
    }

    public static function start()
    {
        Worker::runAll();
        // 启动控制器
    }
}
