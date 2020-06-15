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

            // send Omie a request
            $response = self::sendRequest($endpoint, $call, $params, $app_key, $app_secret);

            if ($response['err'] != 0) {
                $error = "Erro {$response['err']}: {$response['errmsg']}";
                throw new Exception($error);
            }
            return $response;
        }

    // get a list of all the products
        // TO DO
        public static function getProducts(bool $longList=false): array {
            // define if longlisted request or shorlisted
            if($longList){
                $call = 'ListarProdutos';
            } else {
                $call = 'ListarProdutosResumido';
            }

            $endpoint = 'https://app.omie.com.br/api/v1/geral/produtos/';
            $app_key = APP_KEY;
            $app_secret = APP_SECRET;
            
            // params for 1 item per page request
            $firstParams = array(
                "pagina"                    => 1,
                "registros_por_pagina"      => 1,
                "apenas_importado_api"      => "N",
                "filtrar_apenas_omiepdv"    => "N"
            );
            
            // get amount of pages
            $totalRegistrosContent = self::sendRequest($endpoint, $call, $firstParams, $app_key, $app_secret);
            $totalRegistrosContent = $totalRegistrosContent['content'];
            $totalRegistrosContent = json_decode($totalRegistrosContent,true);
            
            $totalRegistros = $totalRegistrosContent['total_de_registros'];
            $paginas = intdiv($totalRegistros,500);
            
            // set $params for request
            $params = array(
                "pagina"                    => 1,
                "registros_por_pagina"      => 500,
                "apenas_importado_api"      => "N",
                "filtrar_apenas_omiepdv"    => "N"
            );

            // initialize $produtos
            $produtos = [];
            
            // iterate to get all pages
            for ($i = 0; $i<=$paginas;$i++){
                $params["pagina"] = $i+1;
                $return = OmieAPI::sendRequest($endpoint, $call, $params, $app_key, $app_secret)['content'];
                $return = json_decode($return,true)['produto_servico_resumido'];
                print_r($return);
                foreach($return as $produto) {
                    array_push($produtos, $produto);
                }
            }
            return $produtos;
        }

        public static function countProducts(): int{
            $call = 'ListarProdutosResumido';
            $endpoint = 'https://app.omie.com.br/api/v1/geral/produtos/';
            $params = array(
                "pagina"                    => 1,
                "registros_por_pagina"      => 1,
                "apenas_importado_api"      => "N",
                "filtrar_apenas_omiepdv"    => "N"
            );
            $app_key = APP_KEY;
            $app_secret = APP_SECRET;

            $request = new OmieAPI;
            $response = json_decode($request->sendRequest($endpoint, $call, $params, $app_key, $app_secret)['content'],true);
            
            $count = $response['total_de_registros'];
            
            return $count;
        }

    // A generic request sender to Omie
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
                    CURLOPT_CONNECTTIMEOUT => 1200,      // timeout on connect
                    CURLOPT_TIMEOUT        => 1200,      // timeout on response
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