<?php

require 'src/service/OmieAPI.php';
require 'src/service/cert/Keys.php';
require 'src/Model/Variables.php';

// logging settings
$utils = new Utils;
$utils->setLogPath(__DIR__);
file_put_contents(LOG_FILE,$utils->echoSys('Início de log',6,0),FILE_APPEND);

// main
// get the products
$produtos = OmieAPI::getProducts();

$utils->echoSys('Lista de produtos', 6,0);
$utils->Appendlog(print_r($produtos, true));

// for testing
    // $produtos = [
    //     [
    //         "codigo_produto_integracao" => "00444",
    //         "codigo_produto"            => 1776599691,
    //         "codigo"                    => "00005784",
    //         "descricao"                 => "Descrição 1"
    //     ],
    //     [
    //         "codigo_produto_integracao" => "PRD00002",
    //         "codigo_produto"            => 1763673825,
    //         "codigo"                    => "PRD00002",
    //         "descricao"                 => "Descrição 2"
    //     ],
    //     [
    //         "codigo_produto_integracao" => "PRD00005",
    //         "codigo_produto"            => 1777979909,
    //         "codigo"                    => "PRD00005",
    //         "descricao"                 => "Descrição 3"
    //     ],
    //     [
    //         "codigo_produto_integracao" => "SFKUADSA",
    //         "codigo_produto"            => 4123412314,
    //         "codigo"                    => "SDGADFGSD",
    //         "descricao"                 => "Descrição 4"
    //     ]
    // ];


// send the images
$utils->sendBatchImg($produtos);
file_put_contents(LOG_FILE,$utils->echoSys('Fim de log', 6) . LINE_SEPARATOR,FILE_APPEND);