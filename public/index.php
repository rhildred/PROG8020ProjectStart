<?php

require __DIR__ . "/../vendor/autoload.php";

$oApp = new \Slim\Slim(array(
        'view' => new \PHPView\PHPView(),
        'templates.path' => __DIR__ . "/../views" ));
                       
$oApp->get("/", function()use($oApp){
   $oApp->render("index.phtml"); 
});

$oApp->run();