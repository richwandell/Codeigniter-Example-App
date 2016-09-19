<?php

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\WebDriverBy;


$driver->get('http://ci.scotchbox.local/car/carlist');

$f = 0; $s = 0; $token = null;
try {
    $driver->wait(10, 500)->until(
        function () use ($driver, $token) {
            $element = $driver->findElement(WebDriverBy::name('cars_app_csrf_token'));
            $token = $element->getAttribute("value");
            return trim($token) != "";
        }
    );
    $s++;
}catch(TimeOutException $e){
    print "[add_car:1] Never loaded dynamic token." . PHP_EOL;
    $f++;
}

try {
    $element = $driver->findElement(WebDriverBy::id("car_name_value"));
    $s++;
}catch(NoSuchElementException $e){
    print "[add_car:2] Missing input." . PHP_EOL;
    $f++;
}

try{
    if(!isset($element)) throw new Exception();
    $element->click();
    $s++;
}catch(Exception $e){
    print "[add_car:3] Cannot click element." . PHP_EOL;
    $f++;
}

try {
    if(!isset($element)) throw new Exception();
    $element->sendKeys("this is a car name 12345");
    $s++;
}catch(Exception $e){
    print "[add_car:4] Cannot type in input box." . PHP_EOL;
    $f++;
}

try {
    if(!isset($element)) throw new Exception();
    $element->submit();
    $s++;
}catch(Exception $e){
    print "[add_car:5] Cannot submit form." . PHP_EOL;
    $f++;
}

try {
    if(!is_null($token)) throw new Exception();
    $driver->wait(10, 500)->until(
        function () use ($driver, $token) {
            $element = $driver->findElement(WebDriverBy::name('cars_app_csrf_token'));
            $value = $element->getAttribute("value");
            return trim($value) != $token && trim($value) != "";
        }
    );
    $s++;
}catch(Exception $e){
    print "[add_car:6] Cannot find dynamic content." . PHP_EOL;
    $f++;
}

try {
    $driver->wait(10, 500)->until(
        function() use($driver) {
            $flash = $driver->findElement(WebDriverBy::id("flash_message"));
            $height = $flash->getCSSValue("height");
            return $height == 20;
        }
    );
    $s++;
}catch(Exception $e){
    print "[add_car:7] Cannot find flash message." . PHP_EOL;
    $f++;
}

try{
    $flash = $driver->findElement(WebDriverBy::id("flash_message"));
    if(strpos($flash->getText(), "Success! Added a new car") == -1) throw new Exception();
    $s++;
}catch(Exception $e){
    print "[add_car:8] Cannot find success message." . PHP_EOL;
    $f++;
}






