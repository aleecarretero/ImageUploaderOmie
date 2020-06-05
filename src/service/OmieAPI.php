<?php

define("OMIE_ARGS", "?JSON=");                                          // Omie's URI parameter

require 'Utils.php'; // Formating utils
require 'OmieAuthentication.php';
class OmieAPI {
    // Updates the images in the product
    // if image_url is the same as already in the product, it does not duplicate
    public static function alterarImagens(string $codigo_produto, array $urls, string $key, string $secret): array {
        $endpoint = 'https://app.omie.com.br/api/v1/geral/produtos/';
        $call = 'AlterarProduto';

        $auths = new OmieAuthentication($key, $secret);

        $app_key = $auths->getKeys()['app_key'];
        $app_secret = $auths->getKeys()['app_secret'];

        // URL to Json
        foreach ($urls as &$url) {
            $url = [
                    "url_imagem" => $url
                ];
        }

        $params = array(
            "codigo_produto"=>$codigo_produto,
            "imagens"=>$urls
        );

        // send Omie request
        $response = self::sendRequest($endpoint, $call, $params, $app_key, $app_secret);

        if ($response['err'] != 0) {
            $error = "Erro {$response['err']}: {$response['errmsg']}";
            throw new Exception($error);
        }
        return $response;
    }

    public static function sendRequest($endpoint, $call, $params, $app_key, $app_secret): array{
        // create json
        $json = array(
            "call"=>$call,
            "app_key"=>$app_key,
            "app_secret"=>$app_secret,
            "param"=>array($params)
        );
        
        // create complete request URL
        $request_url = $endpoint . OMIE_ARGS . json_encode($json);

        // Curling
        $options = array(
                CURLOPT_CUSTOMREQUEST  => "POST",   // set request type post or get
                CURLOPT_POST           => true,     // set to POST
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                CURLOPT_CAINFO         => dirname(__FILE__) . '\cert\cacert.pem' // cert location
            );
        
        $http = curl_init($request_url);
                    
        curl_setopt_array($http, $options);
            
        $content = curl_exec($http);
        $headers = curl_getinfo($http);
        $err     = curl_errno($http);
        $errmsg  = curl_error($http);
        curl_close($http);

        $response = [
            'content' => $content,
            'headers' => $headers,
            'err'     => $err,
            'errmsg'  => $errmsg
        ];

        return $response;
    }
}