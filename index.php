<?php

require_once 'src/Model/Variables.php';
require_once 'src/service/Utils.php';

// logging settings
    Utils::setLogPath(__DIR__);
    Utils::echoSys('Início de log',6,0);

// main
    // get the products
    $produtos = OmieAPI::getProducts();

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
    
    // logging
        Utils::echoSys('Lista de produtos', 6,0);
        Utils::appendLog(print_r($produtos, true). LINE_SEPARATOR);

    // send the images
        Utils::sendBatchImg($produtos);

    // logging
        file_put_contents(LOG_FILE,Utils::echoSys('Fim de log', 6) . LINE_SEPARATOR,FILE_APPEND);