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
 * 添加积分-消费者
 */

require_once('../php-amqplib/amqp.inc');
require_once('../config/config.php');

// 创建连接
$conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);

// 获取信道
$channel = $conn->channel();

// 定义交换器
$channel->exchange_declare( 'upload-pictures',
                                'fanout',
                              false,
                               true,
                           false);

// 声明队列
$channel->queue_declare('add-points',
                        false,
                        true,
                       false,
                     false);

// 绑定队列
$channel->queue_bind('add-points', 'upload-pictures');

//
$consumer = function($msg){};

// 开始消费
$channel->basic_consume($queue,
                        $consumer_tag,
                false,
                 false,
               false,
                 false,
                        $consumer);

$channel->close();
$conn->close();
?>