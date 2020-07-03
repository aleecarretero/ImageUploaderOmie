<?php

require_once 'src/Model/Variables.php';
require_once 'src/service/Utils.php';
require_once 'src/service/OmieAPI.php';

// logging settings
    Utils::setLogPath(__DIR__);
    Utils::echoSys('Início de log',6,0);

// main
    // get the products
    $produtos = OmieAPI::getProducts(false,"10");
    
    // logging
        Utils::echoSys('Lista de produtos', 6,0);
        Utils::appendLog(print_r($produtos, true). LINE_SEPARATOR);

    // send the images
        Utils::sendBatchImg($produtos);

    // logging
        file_put_contents(LOG_FILE,Utils::echoSys('Fim de log', 6) . LINE_SEPARATOR,FILE_APPEND);