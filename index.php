<?php

require 'src/service/OmieAPI.php';

$app_key = '651842348157';
$app_secret = '30b6a83aac3290df4a882822b452a4a2';

$produto = "00444";
$urls = [
    "https://www.urbansports.com.br/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/k/skate_pro_pgs_lacasa_7.8.jpg",
    "https://assets.xtechcommerce.com/uploads/images/medium/0ef206245233b01bb5277f32c7e5b3e9.png"
];

$request = OmieAPI::alterarImagens($produto, $urls, $app_key, $app_secret);

echo $request;
