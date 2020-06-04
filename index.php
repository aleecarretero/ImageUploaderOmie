<?php

require 'src/service/OmieAPI.php';
require 'src/service/cert/Keys.php';

$produto = [
    "codigo_produto_integracao" => "00444",
    "codigo_produto"            => 1776599691,
    "codigo"                    => "00005784"
];

$urls = [
    "https://www.urbansports.com.br/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/k/skate_pro_pgs_lacasa_7.8.jpg",
    "https://assets.xtechcommerce.com/uploads/images/medium/0ef206245233b01bb5277f32c7e5b3e9.png",
    "https://assets.website-files.com/56c5b86020f9e0ef31f4cb85/5d444bdfc27b0255e12906c0_treinamentos-para-pequenas-e-medias-empresas.png",
    "https://pbs.twimg.com/profile_images/486956728/IMG0145A_400x400.jpg",
    "https://pbs.twimg.com/profile_images/1060537634613800961/yE8-teu1_400x400.jpg"
];

$request = OmieAPI::alterarImagens($produto['codigo_produto_integracao'], $urls, $app_key, $app_secret);

echo $request;
