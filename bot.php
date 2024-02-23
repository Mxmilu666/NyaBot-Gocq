<?php
use Swoole\Coroutine;
use Swoole\Coroutine\Http\Client;
use function Swoole\Coroutine\run;
date_default_timezone_set('Asia/Shanghai');
require './inc/config.php';
echo"[NyaBot]欢迎使用呀!". PHP_EOL;
echo"[NyaBot]Github开源地址:https://github.com/Mxmilu666/NyaBot". PHP_EOL;
function connect_ws($ip, $port, $token) {
    $client = new Client($ip, $port);
    $client->setHeaders([
        'Authorization' => 'Bearer ' . $token // 配置token
    ]);
    if (!$client->upgrade('/')) {
        echo "[NyaBot]呜呜呜连接WS服务器怎么失败了! 错误原因：" . $client->errCode . PHP_EOL;
        return false;
    }
    echo "[NyaBot]好哦,连接成功啦!". PHP_EOL;
    return $client;
}
require './inc/core.class.php';
$list = glob('./class/*.php');
    foreach ($list as $file) {
        $file = explode('/', $file)['2'];
        require './class/' . $file;
    }
echo"[NyaBot]核心库/全局函数加载成功啦!". PHP_EOL;
echo"[NyaBot]正在连接WS服务器ing..". PHP_EOL;
run(function () use ($ip,$port,$token){
    $client=connect_ws($ip,$port,$token);
    while (true) {
        $ws_data = $client->recv();
        if (empty($ws_data)) {
            echo "[NyaBot]网络怎么中断了,但是正在重连啦!".PHP_EOL;
            $client->close();
            Swoole\Coroutine\System::sleep(5);
            $client=connect_ws($ip,$port,$token);
            //break;
        } else {
            $op_data = json_decode($ws_data->data, true);
            if (isset($op_data['post_type'])) {
                switch ($op_data['post_type']) {
                    case 'meta_event'://心跳通知
                        //echo '心跳：' . $op_data['time'] . PHP_EOL;
                        break;
                    case 'notice'://群通知
                        break;
                    case 'request'://好友通知
                        break;
                    case 'message'://接收消息
                        switch ($op_data['message_type']) {
                            case 'private'://私聊消息
                                echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $op_data['user_id'] . ']' . '收到私聊消息：' . $op_data['message'] . PHP_EOL;
                                break;
                            case 'group'://群聊消息
                                echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $op_data['group_id'] . '][' . $op_data['user_id'] . ']' . '收到群聊消息：' . $op_data['message'] . PHP_EOL;
                                break;
                        }
                        Coroutine::create(function () use ($client,$op_data) {
                            $list = glob('./plugins/*.php');
                            foreach ($list as $file) {
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