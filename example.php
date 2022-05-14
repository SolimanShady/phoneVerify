<?php
// Load the core class
require("phoneVerify.class.php");

try {

    // Class initalization
    $phoneVerify = new phoneVerify;

    // Set the api key
    $phoneVerify->apiKey = API_KEY_HERE;

    // Set the phone number
    $phoneVerify->setPhoneNumber(14158586273);

    // Validate the phone number and get the response.
    $response = $phoneVerify->verifyNumber();
    
    print_r($response);

    // return the supported country list
    $phoneVerify->getCountryList();

    // Return true if it's a valid phone number
    $phoneVerify->isValid();

} catch (phoneVerifyException $e) { // Catch errors
    echo $e->getMessage()." in ".$e->getLine();
}

?>
