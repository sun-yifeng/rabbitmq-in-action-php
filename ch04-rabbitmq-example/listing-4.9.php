<?php
###############################################
# RabbitMQ in Action
#
# Requires: php-amqplib
#
# Author: Alvaro Videla
# (C)2010
###############################################

/**
 * 添加积分-回调函数
 */

require_once('../php-amqplib/amqp.inc');
require_once('../config/config.php');

$conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);

$channel = $conn->channel();

// 添加积分
function add_points_to_user($user_id){
    echo sprintf("Adding points to user: %s\n", $user_id);
}

$consumer = function($msg){

    if($msg->body == 'quit'){
        $msg->delivery_info['channel']->
        basic_cancel($msg->delivery_info['consumer_tag']);
    }

    $meta = json_decode($msg->body, true);

    // 添加积分
    add_points_to_user($meta['user_id']);

    $msg->delivery_info['channel']->
    basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->close();
$conn->close();

?>