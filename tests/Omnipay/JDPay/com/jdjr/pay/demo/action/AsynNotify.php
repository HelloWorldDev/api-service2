<?php

require __DIR__ . '/common.php';

jdpay_log('notify');
jdpay_log($_POST);
jdpay_log($_GET);
jdpay_log(file_get_contents('php://input'));