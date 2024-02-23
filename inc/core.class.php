<?php
//发送群聊普通信息
function group_send_msg($client,$group_id,$message){
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
    $client->push($message);
    echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $group_id . ']'. '发送群聊消息：' . $message . PHP_EOL;
}
//发送群聊回复信息
function group_send_reply($client,$group_id,$message_id,$message) {
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
                    'id' => $message_id)
            ] ]
        ]
    ]);
        $client->push($sendjson);
        echo '[' . date('Y.n.j-H:i:s') . ']' . '[' . $group_id . ']'. '发送群聊回复消息：' . $message . PHP_EOL;
}