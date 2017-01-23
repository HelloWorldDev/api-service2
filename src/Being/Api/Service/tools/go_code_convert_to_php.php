<?php

require __DIR__ . '/code_convert.php';

$constStr = '';
foreach ($phpCodes[0] as $i => $name) {
    $constStr .= "\tconst $name = {$phpCodes[1][$i]};\n";
}


$contentOut = preg_replace('/\{.+?\}/s', "{\n$constStr}", $contentPHP);

file_put_contents($phpCodeFile, $contentOut);
