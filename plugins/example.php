<?php

if ($op_data['message'] === '/test') {
    $Info = explode(' ', substr($op_data['message'], strpos($op_data['message'], "/bmclapi")));

    if (@$Info['1'] == NULL) {
        if ($Info['0'] == $op_data['message']) {
            $inc->group_send_reply($op_data['group_id'],"你好呀");
            $inc->group_send_msg($op_data['group_id'],"你好呀");
        }
    }
}