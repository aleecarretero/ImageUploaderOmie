<?php

require 'src/service/OmieAPI.php';
require 'src/service/GithubAPI.php';
require 'src/service/cert/Keys.php';

$produto = [
    "codigo_produto_integracao" => "00444",
    "codigo_produto"            => 1776599691,
    "codigo"                    => "00005784"
];

// generating image url
// code here

// getting downloadable image url from github
$imageUrl = GithubAPI::getImageUrl('https://api.github.com/repos/aleecarretero/ImageUploaderOmie/contents/src/images/skate_casa_papel.jpg');

// array with all image urls for this product
$urls = [
    $imageUrl // GitHub repository link
];

$request = OmieAPI::alterarImagens($produto['codigo_produto_integracao'], $urls, APP_KEY, APP_SECRET);

echo $request;
