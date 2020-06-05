<?php

require 'src/service/OmieAPI.php';
require 'src/service/GithubAPI.php';
require 'src/service/cert/Keys.php';
require 'src/Model/Variables.php';

// get the products
    // get ListarProdutosResumido for 1 product per page

    // for ($i=0;$<($total_de_registros mod 500);$i++{
    //     get list fo page $i
    // }

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
    ],
    [
        "codigo_produto_integracao" => "SFKUADSA",
        "codigo_produto"            => 4123412314,
        "codigo"                    => "SDGADFGSD"
    ]
];
// send the images
$utils = new Utils;
$utils->sendBatchImg($produtos);