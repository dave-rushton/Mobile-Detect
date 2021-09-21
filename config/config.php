<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

class config
{
    //S9DEMO
    public $salt = "S{0vp!";
    public $connStr = 'mysql:host=localhost;dbname=totbgifts';
    public $user = 'root';
    public $password = '';
    public $host = 'localhost';
    public $dbname = 'blankcms';
    public $dbConn = '';

    public $customerName = 'DEMO CMS PHP 8.0';
    public $webRoot = 'http://localhost/totbgifts/';
    public $pwRoot = 'http://localhost/totbgifts/admin/';
    public $docRoot = 'C:/xampp/htdocs/totbgifts/';
    public $adminEmail = '';

    public $SmtpServer = "mail.seventy9.co.uk";
    public $SmtpPort = "25";
    public $SmtpUser = "forms@seventy9.co.uk";
    public $SmtpPass = "PWHGr0up!";

    //
    // url setup
    //
    public $productsURL = 'shop';
    public $articlesURL = 'home/';



    public $enablewebp = false;
    public $quality = 50;

    //
    // image sizes
    //
    public $galleryImageSizes = '169-130';
    public $articleImageSizes = '169-130';
    public $peopleImageSizes = '';
    public $placesImageSizes = '';
    public $productImageSizes = '169-130';

    public $fileVersion = 1;

    public $PAYPAL_LIVE_CLIENT_KEY = 'Aa51AKmMipuybYlN05fTWHDX3J-5mO7nuGSA4E_Ep4t52hcRQd_ekWibwcm4_Y6jEMSixAGxERtyTj2A'; // PayPal Standard
    public $PAYPAL_LIVE_SECRET_KEY = 'EI7MKpFfFLijb5ghUw0LCe-7nMONGAHXYTMK5JFGMEZQ51jNfHVidc1my3HbOc3IsGCi1z3-qBtXaAxV'; // PayPal Standard
    public $PAYPAL_TEST_CLIENT_KEY = 'AUfgn3cuYFVMjj8tL6xQRJv8TQ7QqBxb_VLBK-6ZAghxpllOsiDZ52i6mCkb732gh1e9ljhpzMypfeWu'; // PayPal Standard
    public $PAYPAL_TEST_SECRET_KEY = 'EMpOqebbveyWuJQvs6BYei9lMBgqlAmdGEkeSbh-9gZSFaScvj3qEaaRVuxT9v-gUvJLJux3_yfYiHtP'; // PayPal Standard
}
