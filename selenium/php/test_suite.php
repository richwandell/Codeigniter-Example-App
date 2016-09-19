<?php

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
require '../../vendor/autoload.php';

$host = 'http://localhost:4444/wd/hub';

//run all tests in firefox
$capabilities = DesiredCapabilities::firefox();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);

$f = 0; $s = 0;

require 'add_car.php';
require 'delete_car.php';
require 'view_car_details.php';
$driver->close();
print "[firefox] " . ($f + $s) . " tests run. {$s} passed. {$f} failed." . PHP_EOL;

//run all tests in chrome
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);

$f = 0; $s = 0;

require 'add_car.php';
require 'delete_car.php';
require 'view_car_details.php';
$driver->close();
print "[chrome] " . ($f + $s) . " tests run. {$s} passed. {$f} failed." . PHP_EOL;