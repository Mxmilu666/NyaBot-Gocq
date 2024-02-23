<?php

if ($op_data['message'] === '/test') {
    $Info = explode(' ', substr($op_data['message'], strpos($op_data['message'], "/bmclapi")));

    if (@$Info['1'] == NULL) {
        if ($Info['0'] == $op_data['message']) {
            
            $responseData = json_decode($cli->body, true);
            group_send_msg($client,$op_data['group_id'],"你好呀");
        }
    }
}