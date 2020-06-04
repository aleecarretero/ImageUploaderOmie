<?php

require 'src/service/OmieAPI.php';
require 'src/service/cert/Keys.php';

$produto = [
    "codigo_produto_integracao" => "00444",
    "codigo_produto"            => 1776599691,
    "codigo"                    => "00005784"
];

// not working: redirect is sending blank image
$urls = [
    "https://github.com/aleecarretero/ImageUploaderOmie/raw/master/src/images/skate_casa_papel.jpg" // GitHub repository link
];

$request = OmieAPI::alterarImagens($produto['codigo_produto_integracao'], $urls, APP_KEY, APP_SECRET);

echo $request;
