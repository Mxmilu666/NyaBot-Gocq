<?php
use Swoole\Coroutine;
use Swoole\Coroutine\Http\Client;
use function Swoole\Coroutine\run;
date_default_timezone_set('Asia/Shanghai');
require './inc/config.php';
echo"[NyaBot-Gocq]欢迎使用NyaBot-Gocq!". PHP_EOL;
echo"[NyaBot-Gocq]Github开源地址:https://github.com/Mxmilu666/NyaBot-Gocq". PHP_EOL;
require './inc/core.class.php';
$list = glob('./class/*.class.php');
    foreach ($list as $file) {
        $file = explode('/', $file)['2'];
        require './class/' . $file;
    }
echo"[NyaBot-Gocq]全局函数加载成功!". PHP_EOL;
echo"[NyaBot-Gocq]正在连接WS服务器ing..". PHP_EOL;
run(function (){
    include './inc/config.php';
    $inc = new inc($config['ip'], $config['port'], $config['token']);
    $client = $inc->connect_ws();
    if ($client->getStatusCode() == '403') {
        echo "[NyaBot-Gocq]Toekn怎么错误,检查一下吧：" . $client->getStatusCode() . '/' . $client->errCode.PHP_EOL;
    } else if ($client->getStatusCode() == '-1' or $client->errCode == '114') {
        echo "[NyaBot-Gocq]网络连接失败了呢,检查一下吧".PHP_EOL;
    } else {
        echo "[NyaBot-Gocq]连接ws服务端成功：" . ' 你的BOT_QQ是：' . json_decode(@$client->recv()->data, true)['self_id'] .PHP_EOL;
    }
    while ($client->getStatusCode() != '403') {
        $ws_data = $client->recv();
        if (empty($ws_data)) {
            echo "[NyaBot-Gocq]网络怎么中断了,但是正在重连!".PHP_EOL;
            $client->close();
            Swoole\Coroutine\System::sleep(5);
            $client = $inc->connect_ws();
            //break;
        } else {
            $op_data = json_decode($ws_data->data, true);
            if (isset($op_data['post_type'])) {
                switch ($op_data['post_type']) {
                    case 'meta_event'://心跳通知
                        //echo '心跳：' . $op_data['time'] . PHP_EOL;
                        break;
                    case 'notice'://群通知s
                        break;
                    case 'request'://好友通知
                        break;
                    case 'message'://接收消息
                        //$inc->update_op_message($op_data);
                        switch ($op_data['message_type']) {
                            case 'private'://私聊消息
                                echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $op_data['user_id'] . ']' . '收到私聊消息：' . $op_data['message'] . PHP_EOL;
                                break;
                            case 'group'://群聊消息
                                echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $op_data['group_id'] . '][' . $op_data['user_id'] . ']' . '收到群聊消息：' . $op_data['message'] . PHP_EOL;
                                break;
                        }
                        Coroutine::create(function () use ($client, $op_data,$inc) {
                            foreach (glob('./plugins/*.php') as $file) {
                                $inc->update_op_message($op_data,Swoole\Coroutine::getCid());
                                $file = explode('/', $file)['2'];
                                require './plugins/' . $file;
                            }
                    });
                        break;
                }
            }
        }
    }
});