<?php

require __DIR__ . "/../vendor/autoload.php";

//connect to database
$oDb = new PDO("sqlite:" . __DIR__ . "/../products.sqlite");

$oApp = new \Slim\Slim(array(
        'view' => new \PHPView\PHPView(),
        'templates.path' => __DIR__ . "/../views" ));
                       
$oApp->get("/", function(){
    renderProduct(1);
});

$oApp->get("/about", function()use($oApp){
   $oApp->render("about.phtml"); 
});

$oApp->get("/contact", function()use($oApp){
   $oApp->render("contact.phtml"); 
});

$oApp->get("/privacy", function()use($oApp){
   $oApp->render("privacy.phtml"); 
});

$oApp->get("/products/:productID", function($nId){
    renderProduct($nId);
});


$oApp->run();

function renderProduct($nId){
    global $oApp, $oDb;
    // fetching product
    $oStmt = $oDb->prepare("SELECT * FROM products WHERE productID = :id");
    $oStmt->bindParam("id", $nId);
    $oStmt->execute();
    $aProduct = $oStmt->fetchAll(PDO::FETCH_OBJ);
    
    //fetching images
    $oStmt = $oDb->prepare("SELECT * FROM images WHERE productID = :id");
    $oStmt->bindParam("id", $nId);
    $oStmt->execute();
    $aImages = $oStmt->fetchAll(PDO::FETCH_OBJ);

    //fetching offers
    $oStmt = $oDb->prepare("SELECT * FROM offers WHERE productID = :id");
    $oStmt->bindParam("id", $nId);
    $oStmt->execute();
    $aOffers = $oStmt->fetchAll(PDO::FETCH_OBJ);

    //fetching list of products for the bar across the bottom
    $oStmt = $oDb->prepare("SELECT * FROM products");
    $oStmt->execute();
    $aProducts = $oStmt->fetchAll(PDO::FETCH_OBJ);    
    
    // render template with data
    $oApp->render("product.phtml", array("product"=>$aProduct[0], "images"=>$aImages, "offers"=>$aOffers, "products"=>$aProducts));   
}