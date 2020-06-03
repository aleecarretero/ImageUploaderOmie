<?php

define("OMIE_ARGS", "?JSON=");                                          // Omie's URI parameter
define("OMIE_APP_KEY", "651842348157", false);                          // Insert Omie's app_key
define("OMIE_APP_SECRET", "30b6a83aac3290df4a882822b452a4a2", false);   // Insert Omie's app_secret

require 'Utils.php'; // Formating utils

class OmieAPI
{
    // Updates the images in the product
    // if image_url is the same as already in the product, it does not duplicate
    public static function alterarImagens(string $codigo_produto_integracao, array $urls): string
    {
        $endpoint = 'https://app.omie.com.br/api/v1/geral/produtos/';
        $call = 'AlterarProduto';

        // URL to Json
        foreach ($urls as &$url) {
            $url = [
                    "url_imagem" => $url
                ];
        }

        $params = array(
            "codigo_produto_integracao"=>$codigo_produto_integracao,
            "imagens"=>$urls
        );

        $json = array(
            "call"=>$call,
            "app_key"=>OMIE_APP_KEY,
            "app_secret"=>OMIE_APP_SECRET,
            "param"=>array($params)
        );

        // create complete request URL
        $request_url = $endpoint . OMIE_ARGS . json_encode($json);

        // Curling
        $options = array(
                CURLOPT_CUSTOMREQUEST  =>"POST",    // set request type post or get
                CURLOPT_POST           =>true,      // set to GET
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                // CURLOPT_SSL_VERIFYPEER => true,        // ignores peer SSL verification
                // CURLOPT_SSL_VERIFYHOST => true,        // ignores host SSL verification
                CURLOPT_CAINFO         => dirname(__FILE__) . '\cert\cacert.pem'
            );
        
        $http = curl_init($request_url);
                    
        curl_setopt_array($http, $options);
            
        $content = curl_exec($http);
        $headers = curl_getinfo($http);
        $err     = curl_errno($http);
        $errmsg  = curl_error($http);

        curl_close($http);

        if ($err != 0) {
            $error = "Erro {$err}: $errmsg";
            throw new Exception($error);
        }
        return Utils::json_minify($content);
    }
}