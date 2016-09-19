<?php

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;


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
}catch(TimeOutException $e){
    print "[view_car_details:1] Never loaded dynamic token." . PHP_EOL;
    $f++;
}

try {
    $element = $driver->findElement(WebDriverBy::id("passenger_list"));
    $trs = $element->findElements(WebDriverBy::tagName("tr"));
    $tds = $trs[count($trs) - 1]->findElements(WebDriverBy::tagName("td"));
    $element = $tds[0]->findElement(WebDriverBy::tagName("a"));
    $search_text = $element->getText();
    $s++;
}catch(Exception $e){
    echo $e->getTraceAsString() . PHP_EOL;
    print "[view_car_details:2] Missing car anchor tag." . PHP_EOL;
    $f++;
}

try{
    if(!isset($element)) throw new Exception();
    $element->click();
    $s++;
}catch(Exception $e){
    print "[view_car_details:3] Cannot click element." . PHP_EOL;
    $f++;
}

try{
    $driver->wait(10, 500)
        ->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className("jumbotron")));
    $s++;
}catch(Exception $e){
    print "[view_car_details:4] Never loaded page with jumbotron." . PHP_EOL;
    $f++;
}

try{
    $elements = $driver->findElements(WebDriverBy::cssSelector(".jumbotron label"));
    $text = trim($elements[0]->getText());
    if(strpos($text, "Number of Passengers:") == -1) throw new Exception();
    $s++;
}catch(Exception $e){
    print "[view_car_details:5] Passenger text missing." . PHP_EOL;
    $f++;
}

try{
    if(!isset($search_text)) throw new Exception();
    $element = $driver->findElement(WebDriverBy::cssSelector(".jumbotron h1"));
    $text = trim($element->getText());
    if(strpos($text, $search_text) == -1) throw new Exception();
    $s++;
}catch(Exception $e){
    print "[view_car_details:6] Car text missing." . PHP_EOL;
    $f++;
}
