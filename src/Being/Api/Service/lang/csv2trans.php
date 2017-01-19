<?php

require __DIR__ . '/../../../Services/App/LanguagePackageService.php';

use Being\Services\App\LanguagePackageService;

$file = __DIR__ . '/lang.csv';
list($errors, $languageData) = LanguagePackageService::parseCSVFile($file);

foreach ($languageData as $data) {
    if ($data['device'] == LanguagePackageService::SERVER) {
        $language = $data['language'];
        $dir = __DIR__ . '/' . $language;
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $file = $dir . '/message.php';
        $langData = [];
        if (file_exists($file)) {
            $langData = require $file;
        }
        $keyMap = null;
        foreach ($data['keyMap'] as $k => $v) {
            if (substr($k, 0, 5) == 'code.') {
                $keyMap[intval(substr($k, 5))] = $v;
            }
        }
        if (is_array($keyMap)) {
            $langData = array_merge($langData, $keyMap);
        }
        $varContent = var_export($langData, true);
        $varContent = str_replace(['array (', "),\n", "\n)"], ['[', "],\n", "\n]"], $varContent);
        $varContent = '<?php' . PHP_EOL . PHP_EOL . 'return ' . $varContent . ';' . PHP_EOL;
        file_put_contents($file, $varContent);
    }
}

if (!empty($errors)) {
    print_r($errors);
    echo "\n";
}
echo "done\n";
