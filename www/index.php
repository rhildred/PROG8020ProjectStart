<?php

require __DIR__ . "/../vendor/autoload.php";

//connect to database
$oDb = new PDO("sqlite:" . __DIR__ . "/../products.sqlite");

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

$oApp->get("/products/:productID", function($nId) use($oApp, $oDb){
    
    // fetching product
    $oStmt = $oDb->prepare("SELECT * FROM products WHERE productID = :id");
    $oStmt->bindParam("id", $nId);
    $oStmt->execute();
    $aProducts = $oStmt->fetchAll(PDO::FETCH_OBJ);
    
    //fetching images
    $oStmt = $oDb->prepare("SELECT * FROM images WHERE productID = :id");
    $oStmt->bindParam("id", $nId);
    $oStmt->execute();
    $aImages = $oStmt->fetchAll(PDO::FETCH_OBJ);

    $oApp->render("product.phtml", array("product"=>$aProducts[0], "images"=>$aImages)); 
});


$oApp->run();