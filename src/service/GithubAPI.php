<?php

require_once 'cert/Keys.php';

class GithubAPI {
    static public function getImageUrl($url): string {
        $requestUrl = $url;

        $options = [
            CURLOPT_CUSTOMREQUEST  => "GET",    // set request type post or get
            CURLOPT_POST           => false,     // set to GET
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_CAINFO         => __DIR__ . '\cert\cacert.pem', // cert location
            CURLOPT_USERAGENT      => GITHUB_USERNAME,
            CURLOPT_USERPWD        => GITHUB_KEY
        ];

        $http = curl_init($requestUrl);
        curl_setopt_array($http, $options);
        $content = curl_exec($http);
        $headers = curl_getinfo($http);
        $err     = curl_errno($http);
        $errmsg  = curl_error($http);
        curl_close($http);

        return (json_decode($content,true)['download_url']);

    }
}