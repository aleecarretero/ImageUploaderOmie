<?php

require 'src/service/OmieAPI.php';

$produto = "00444";
$urls = [
    "https://www.urbansports.com.br/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/k/skate_pro_pgs_lacasa_7.8.jpg",
    "https://assets.xtechcommerce.com/uploads/images/medium/0ef206245233b01bb5277f32c7e5b3e9.png"
];

$request = OmieAPI::alterarImagens($produto, $urls);

echo $request;