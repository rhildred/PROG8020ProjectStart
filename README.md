# [PROG8020 Project 1 Start](https://github.com/rhildred/PROG8020ProjectStart/)
This is a somewhat complete product page. It uses Google rich snippets for products, and gets the products, image urls and offer's from a sqlite database that is committed as part of the project. Composer is used to update the dependencies from the composer.json file:

```

{
    "require": {
        "slim/slim": "^2.6",
        "rhildred/slimphpviews": "dev-master",
        "rhildred/editable": "dev-master"
    }
}

```

1. With the code, you need to do a `composer update` in the same folder as the composer.json file. 
2. Then `cd www` and run `php -S localhost:8000`

This project is meant to be pushed on to openshift. To this end there is an `.openshift/action_hooks/deploy` file:

```

#!/bin/bash
# .openshift/action_hooks/deploy
( unset GIT_DIR ; cd $OPENSHIFT_REPO_DIR ; composer update )

```

The code itself revolves around rendering .phtml and markdown templates from the `/views` folder as well as static assets from the www folder. See the www/index.php:

```

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

$oApp->get("/success", function()use($oApp){
   $oApp->render("success.phtml"); 
});

$oApp->get("/failure", function()use($oApp){
   $oApp->render("failure.phtml"); 
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

```

There is also an inquiry form in the views/contact.phtml that is based on a web service that I am providing my students:

```

        <form action="https://rich-hildred.rhcloud.com/Mailer/56519ee51ea119ef4e20e8">
            <input type="email" name="email" placeholder="Reply email address" />
            <br />
            <input type="text" name="subject" placeholder="Subject of your inquiry" />
            <br />
            <textarea name="message" placeholder="Your Inquiry"></textarea>
            <br />
            <button type="submit" class="btn btn-primary btn-md">Send</button>
            <button type="reset" class="btn btn-primary btn-md">Reset</button>
            <br />
        </form>

```

If you surf to [rich-hildred.rhcloud.com](https://rich-hildred.rhcloud.com/login) you can set up your own url to put in the form action with a google plus oauth2 login. The inquiry form uses the `/success` and `/failure` endpoints in the above index.phtml. Alternatively you could use the `rhildred/mailer` composer package to make your own endpoint to send mail. I also have a composer package rhildred/bitbucket-bug-report, which can be used to create a bug report on bitbucket for inquiries.

The idea is that this can be used in an introductory modern php class to scaffold a student's site based on some other subject and content.
