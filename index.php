<?php

require 'src/service/OmieAPI.php';
require 'src/service/GithubAPI.php';
require 'src/service/cert/Keys.php';
require 'src/Model/Variables.php';

$produtos = [
    [
        "codigo_produto_integracao" => "00444",
        "codigo_produto"            => 1776599691,
        "codigo"                    => "00005784"
    ],
    [
        "codigo_produto_integracao" => "PRD00002",
        "codigo_produto"            => 1763673825,
        "codigo"                    => "PRD00002"
    ],
    [
        "codigo_produto_integracao" => "PRD00005",
        "codigo_produto"            => 1777979909,
        "codigo"                    => "PRD00005"
    ]
];

// Generate image url
$urls = Utils::getImagesUrl($produtos);

// TESTING
    print_r($urls);
    // exit();

// post images to Omie

foreach ($urls as $key=>$produto) {

        $request = OmieAPI::alterarImagens(strval($key), $produto, APP_KEY, APP_SECRET);

        echo (
            '================================' . PHP_EOL .
            $request . PHP_EOL .
            '================================'
        );
}