<?php
use Swoole\Coroutine\Http\Client;
class inc{
    private $ip;
    private $port;
    private $token;
    private $op_data;
    private $op_message;
    public function __construct($ip,$port,$token){
        $this->ip = $ip;
        $this->port = $port;
        $this->token = $token;
    }
    public function connect_ws()
    {
        $client = new Client($this->ip, $this->port);
        $client->setHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $client->upgrade('/');
        return $this->op_data=$client;
    }
    public function update_op_message($op_data){
        $this->op_message=$op_data;
    }
    //发送群聊信息
    public function group_send_msg($group_id,$message){
    $op_data=$this->op_message;
    $sendjson = json_encode(
            [
                'action' => 'send_group_msg',
                'params' =>
                    [
                        'group_id' => $group_id,
                        'message' =>  $message
                    ]
            ]
        );
    $this->op_data->push($sendjson);
    echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $group_id . ']'. '发送群聊消息：' . $message . PHP_EOL;
}
    //发送群聊回复信息
    public function group_send_reply($group_id,$message) {
    $op_data=$this->op_message;
    $sendjson = json_encode([
        'action' => 'send_msg',
        'params' => [
            'group_id' => $group_id,
            "message" => [
            [
                'type' => 'text',
                'data' => [
                    'text' => $message
                ]
            ] ,
            [
                'type' => 'reply',
                'data' => array(
                    'id' => $op_data['message_id'])
            ] ]
        ]
    ]);
        $this->op_data->push($sendjson);
        echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $group_id . ']'. '发送群聊回复消息：' . $message . PHP_EOL;
}
    //发送私聊信息
    public function private_send_msg($user_id,$message){
    $op_data=$this->op_message;
    $sendjson = json_encode(
            [
                'action' => 'send_private_msg',
                'params' =>
                    [
                        'user_id' => $user_id,
                        'message' =>  $message
                    ]
            ]
        );
    $this->op_data->push($sendjson);
    echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $user_id . ']'. '发送私聊消息：' . $message . PHP_EOL;
}
    //发送私聊回复信息
    public function private_send_reply($user_id,$message) {
        $op_data=$this->op_message;
        $sendjson = json_encode([
            'action' => 'send_msg',
            'params' => [
                'user_id' => $user_id,
                "message" => [
                [
                    'type' => 'text',
                    'data' => [
                        'text' => $message
                    ]
                ] ,
                [
                    'type' => 'reply',
                    'data' => array(
                        'id' => $op_data['message_id'])
                ] ]
            ]
        ]);
            $this->op_data->push($sendjson);
            echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $user_id . ']'. '发送私聊回复消息：' . $message . PHP_EOL;
    }
}