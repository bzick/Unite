#!/usr/bin/php
<?php

require_once __DIR__."/../vendor/autoload.php";

$options = Taste\CLI::getArgv();
$taste = new Taste\Runner($options);
$taste->load($options[0]);
$taste->run();

?>