<?php

define("OMIE_APP_KEY", "651842348157", false);
define("OMIE_APP_SECRET", "30b6a83aac3290df4a882822b452a4a2 ", false);

class OmieAPI {
    // Omie data

    public static function alterarImagens(string $codigo_produto_integracao, array $urls) {
        $endpoint = 'https://app.omie.com.br/api/v1/geral/produtos/';
        $call = 'AlterarProduto';
        $arg = "?JSON=";

        // URL to Json
            foreach ($urls as &$url){
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
            $request_url = $endpoint . $arg . json_encode($json);

        // Curling
            $options = array(
                CURLOPT_CUSTOMREQUEST  =>"POST",    //set request type post or get
                CURLOPT_POST           =>true,      //set to GET
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            );

            $http = curl_init($request_url);
            curl_setopt_array($http, $options);
            
            $content = curl_exec($http);
            $headers = curl_getinfo($http);
            $err     = curl_errno($http); 
            $errmsg  = curl_error($http);

            curl_close($http);

            echo "Content: "  . PHP_EOL;
            var_dump($content) . PHP_EOL;

            echo "ErrNo: " . PHP_EOL;
            var_dump($err) . PHP_EOL;

            echo "ErrMsg: " . PHP_EOL;
            var_dump($errmsg) . PHP_EOL;

            echo "Headers: " . PHP_EOL;
            var_dump($headers) . PHP_EOL;
    }
}

$produto = "00444";
$urls = [
    "https://www.urbansports.com.br/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/k/skate_pro_pgs_lacasa_7.8.jpg",
    "https://assets.xtechcommerce.com/uploads/images/medium/0ef206245233b01bb5277f32c7e5b3e9.png"
];

OmieAPI::alterarImagens($produto, $urls);