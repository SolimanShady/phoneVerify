<?php
/**
 * phoneVerify php class
 * Easy php class to validate phone number.
 *
 * @author Soliman Adel
 * @email soliman.adelzx@gmail.com
 * @version 1.0
 */

class phoneVerify
{
    public
        $apiKey,
        $number,
        $valid,
        $carrier,
        $line_type,
        $location,
        $local_format,
        $country_code,
        $country_name,
        $country_prefix,
        $international_format;

    protected $phoneNumber;

    /**
     * constructor function
     *
     * @param mixed $apiKey optional
     * @return void
     */
    function __construct($apiKey = false)
    {
        if ( !empty($apiKey) ) {
            $this->apiKey = $apiKey;
        }
    }

    /**
     * setPhoneNumber
     * set the phone number to search.
     *
     * @param int $phoneNumber
     * @return void
     */
    function setPhoneNumber($phoneNumber = false)
    {
        if( empty($phoneNumber) || !is_numeric($phoneNumber) ) {
            throw new phoneVerifyException("Invalid phone number", 1);
            return;
        }
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * verifyNumber
     * check if the phone number valid or not.
     * retrun additional information.
     *
     * @param int $number
     * @return object
     */
    function verifyNumber($number = false)
    {
        $number = $number ?: $this->phoneNumber;

        if ( empty($number) && empty($this->phoneNumber) ) {
            throw new phoneVerifyException("Invalid phone number", 1);
            return;
        }

        if ( !is_numeric($number) ) {
            throw new phoneVerifyException("Invalid phone number", 1);
            return;
        }

        $response = json_decode(
            self::request(
                "https://api.apilayer.com/number_verification/validate?number={$number}"
            )
        );

        if ( !$response ) {
            throw new phoneVerifyException("Error Processing Request", 1);
            return;
        }

        foreach ($response as $key => $value) {
            $this->{$key} = $value;
        }

        return $response;
    }

    /**
     * getCountryList
     * return the supported country list with additional information.
     *
     * @param null
     * @return object
     */
    function getCountryList()
    {
        $response = json_decode(
            self::request("https://api.apilayer.com/number_verification/countries")
        );
        if ( !$response ) {
            throw new phoneVerifyException("Error Processing Request", 1);
            return;
        }
        return $response;
    }

    /**
     * isValid
     * check if the phone number valid or not.
     *
     * @param null
     * @return boolean
     */
    function isValid()
    {
        return $this->valid;
    }

    /**
     * request
     * make a request to the target endpoint
     *
     * @param mixed $url
     * @return mixed
     */
    private function request($url)
    {
        if( empty($url) ) return;

        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array(
              "Content-Type: text/plain",
              "apikey: {$this->apiKey}"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }
}

class phoneVerifyException extends \Exception{}
?>
