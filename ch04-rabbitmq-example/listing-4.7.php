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
 * 上传图片-生成者
 */

require_once('../php-amqplib/amqp.inc');
require_once('../config/config.php');

// 创建连接
$conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);

// 获取信道
$channel = $conn->channel();

// 声明交换器
$channel->exchange_declare('upload-pictures',
                               'fanout',
                             false,
                             true,
                          false);

$metadata = json_encode(array(
                        'image_id' => $image_id,
                        'user_id' => $user_id,
                        'image_path' => $image_path
                       ));

// 消息实例
$msg = new AMQPMessage($metadata,
    array('content_type' => 'application/json',
          'delivery_mode' => 2));

// 发布消息
$channel->basic_publish($msg, 'upload-pictures');

$channel->close();
$conn->close();

?>