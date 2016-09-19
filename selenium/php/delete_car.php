<?php

use Facebook\WebDriver\WebDriverBy;


$driver->get('http://ci.scotchbox.local/car/carlist');

$token = null;
try {
    $driver->wait(10, 500)->until(
        function () use ($driver, $token) {
            $element = $driver->findElement(WebDriverBy::name('cars_app_csrf_token'));
            $token = $element->getAttribute("value");
            return trim($token) != "";
        }
    );
    $s++;
}catch(Exception $e){
    print "[delete_car:1] Never loaded dynamic token." . PHP_EOL;
    $f++;
}

try {
    $elements = $driver->findElements(WebDriverBy::cssSelector("#passenger_list input[type='submit'][value='Delete']"));
    $elements[count($elements) -1]->click();
    $s++;
}catch(Exception $e){
    print "[delete_car:2] Missing last delete button." . PHP_EOL;
    $f++;
}

try{
    $driver->wait(10, 500)->until(
        function() use($driver, $token) {
            $element = $driver->findElement(WebDriverBy::name('cars_app_csrf_token'));
            $value = trim($element->getAttribute("value"));
            return $value != "" && $value != $token;
        }
    );
    $s++;
}catch(Exception $e){
    print "[delete_car:3] Never loaded dynamic token." . PHP_EOL;
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
    print "[delete_car:4] Cannot find flash message." . PHP_EOL;
    $f++;
}

try{
    $flash = $driver->findElement(WebDriverBy::id("flash_message"));
    if(strpos($flash->getText(), "Success! Removed a car") == -1) throw new Exception();
    $s++;
}catch(Exception $e){
    print "[delete_car:5] Cannot find success message." . PHP_EOL;
    $f++;
}










