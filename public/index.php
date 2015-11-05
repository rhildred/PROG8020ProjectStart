<?php

require __DIR__ . "/../vendor/autoload.php";

$oApp = new \Slim\Slim(array(
        'view' => new \PHPView\PHPView(),
        'templates.path' => __DIR__ . "/../views" ));
                       
$oApp->get("/", function()use($oApp){
   $oApp->render("index.phtml"); 
});

$oApp->get("/about", function()use($oApp){
   $oApp->render("about.phtml"); 
});

$oApp->get("/contact", function()use($oApp){
   $oApp->render("contact.phtml"); 
});


$oApp->run();