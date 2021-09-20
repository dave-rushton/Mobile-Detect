<?php

class fbPixels extends db
{

    function displayPixelsCode($Pix_ID = NULL, $Action = 'PageView')
    {

        $outputString = '';

        if (!is_null($Pix_ID)) {

            $outputString .= '<!-- Facebook Pixel Code -->';
            $outputString .= '<script>';
            $outputString .= '!function(f,b,e,v,n,t,s)';
            $outputString .= '{if(f.fbq)return;n=f.fbq=function(){n.callMethod?';
            $outputString .= 'n.callMethod.apply(n,arguments):n.queue.push(arguments)};';
            $outputString .= 'if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";';
            $outputString .= 'n.queue=[];t=b.createElement(e);t.async=!0;';
            $outputString .= 't.src=v;s=b.getElementsByTagName(e)[0];';
            $outputString .= 's.parentNode.insertBefore(t,s)}(window,document,"script",';
            $outputString .= '"https://connect.facebook.net/en_US/fbevents.js");';
            $outputString .= 'fbq("init", "' . $Pix_ID . '");';
            $outputString .= 'fbq("track", "' . $Action . '");';
            $outputString .= '</script>';
            $outputString .= '<noscript>';
            $outputString .= '<img height="1" width="1" src="https://www.facebook.com/tr?id=1373550349326198&ev=PageView&noscript=1"/>';
            $outputString .= '</noscript>';
            $outputString .= '<!-- End Facebook Pixel Code -->';

        }

        echo $outputString;

    }

    function displayTrackingCode($Action = 'ViewContent', $ContentName = '', $ContentCategory = '', $contentIDs = '')
    {

        $outputString = '';

        $outputString .= 'fbq("track", "' . $Action . '", {';
        $outputString .= 'content_name: "' . $ContentName . '",';
        $outputString .= 'content_category: "' . $ContentCategory . '",';

        //$outputString .= 'content_ids: ["1234"],';
        //$outputString .= 'content_type: "product",';
        //$outputString .= 'value: 0.50,';
        //$outputString .= 'currency: "GBP",';

        $outputString .= 'referrer: document.referrer,';
        $outputString .= 'userAgent: navigator.userAgent,';
        $outputString .= 'language: navigator.language';

        $outputString .= '});';

        echo $outputString;

    }

    function displayTrackingSearch($ContentCategory = 'product', $SearchString = '')
    {

        $outputString = '';

        if (!empty($SearchString)) {

            $outputString .= 'fbq("track", "Search", {';

            $outputString .= 'content_category: "' . $ContentCategory . '",';
            $outputString .= 'search_string: "' . $SearchString . '",';

            $outputString .= 'referrer: document.referrer,';
            $outputString .= 'userAgent: navigator.userAgent,';
            $outputString .= 'language: navigator.language';

            $outputString .= '});';

        }

        echo $outputString;

    }


    function displayAddToCart($Prd_ID = NULL)
    {

        $ContentName = '';
        $ContentType = '';

        $outputString = '';

        $outputString .= 'fbq("track", "AddToCart", {';

        $outputString .= 'content_name: "' . $ContentName . '",';
        $outputString .= 'content_type: "product",';
        $outputString .= 'contents: "' . $ContentType . '",';
        $outputString .= 'content_ids: ["' . $ContentType . '"],';
        $outputString .= 'value: "' . $ContentType . '",';
        $outputString .= 'currency: "' . $ContentType . '",';


        $outputString .= 'referrer: document.referrer,';
        $outputString .= 'userAgent: navigator.userAgent,';
        $outputString .= 'language: navigator.language';

        $outputString .= '});';

        echo $outputString;

    }

    function displayInitCheckout()
    {

        $ContentName = '';
        $ContentCategory = '';
        $ContentType = '';

        $outputString = '';

        $outputString .= 'fbq("track", "InitiateCheckout", {';

        $outputString .= 'content_name: "' . $ContentName . '",';
        $outputString .= 'content_category: "' . $ContentCategory . '",';

        // JSON Contents
        $outputString .= 'contents: "' . $ContentType . '",';

        // Array
        $outputString .= 'content_ids: ["' . $ContentType . '"],';

        $outputString .= 'num_items: "' . $ContentType . '",';
        $outputString .= 'value: "' . $ContentType . '",';
        $outputString .= 'currency: "' . $ContentType . '",';


        $outputString .= 'referrer: document.referrer,';
        $outputString .= 'userAgent: navigator.userAgent,';
        $outputString .= 'language: navigator.language';

        $outputString .= '});';

        echo $outputString;

    }

    function displayPurchase()
    {

        $ContentName = '';
        $ContentType = '';

        $outputString = '';

        $outputString .= 'fbq("track", "InitiateCheckout", {';

        $outputString .= 'content_name: "' . $ContentName . '",';
        $outputString .= 'content_type: "product",';


        // JSON Contents
        $outputString .= 'contents: "' . $ContentType . '",';

        // Array
        $outputString .= 'content_ids: ["' . $ContentType . '"],';


        $outputString .= 'num_items: "' . $ContentType . '",';
        $outputString .= 'value: "' . $ContentType . '",';
        $outputString .= 'currency: "' . $ContentType . '",';

        $outputString .= 'referrer: document.referrer,';
        $outputString .= 'userAgent: navigator.userAgent,';
        $outputString .= 'language: navigator.language';

        $outputString .= '});';

        echo $outputString;

    }

}