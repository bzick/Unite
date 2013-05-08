#!/usr/bin/env php
<?php

$files = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
);

foreach ($files as $file) {
    if (file_exists($file)) {
        require $file;
        define('UNITE_COMPOSER', $file);
        break;
    }
}

if (!defined('UNITE_COMPOSER')) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
            'curl -sS http://getcomposer.org/installer | php' . PHP_EOL .
            'php composer.phar install' . PHP_EOL
    );
}

$unite = new Unite(new Unite\Request());

$unite->run();