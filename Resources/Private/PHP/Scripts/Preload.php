<?php
// first of all determine which file to load

$context = '';

if (!empty($_SERVER['SCRIPT_NAME'])) {
    switch ($_SERVER['SCRIPT_NAME']) {
        case '/typo3/index.php':
            $context = 'Backend';
            break;
        case '/index.php':
            $context = 'Frontend';
            break;
        case './typo3/cli_dispatch.phpsh':
            $context = 'Cli';
            break;
    }
}

$preLoadFile = 'typo3temp/Cache/Code/cache_preload/' . $context . '.php';

if (!empty($context) && file_exists($preLoadFile)) {
    require($preLoadFile);
}
