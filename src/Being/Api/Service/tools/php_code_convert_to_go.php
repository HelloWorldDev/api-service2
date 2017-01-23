<?php

require __DIR__ . '/code_convert.php';

$constStr = '';
foreach ($phpCodes[0] as $i => $name) {
    $name = snack2camel($name);
    $constStr .= "\tCode$name = {$phpCodes[1][$i]}\n";
}

echo $constStr;
