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
    $baseUrl = GITHUB_GET_CONTENTS_PATH . IMAGES_FOLDER_PATH;
    $urls = [];

    foreach ($produtos as $produto) {
        $prodImgUrls = [];
        
        // get all product folders
        $productFolder = IMAGES_FOLDER_PATH . $produto['codigo'] . '/';
        for ($i=0;$i<5;$i++){
            $imagePath = false;

            $jpgImageName  = $produto['codigo'] . '-' . strval($i+1) . '.jpg';  // jpg format
            $jpegImageName = $produto['codigo'] . '-' . strval($i+1) . '.jpeg'; // jpeg format
            $pngImageName  = $produto['codigo'] . '-' . strval($i+1) . '.png';  // png format

            echo($productFolder . $jpgImageName) . PHP_EOL;
            echo($productFolder . $jpegImageName) . PHP_EOL;
            echo($productFolder . $pngImageName) . PHP_EOL;

            if (file_exists($productFolder . $jpegImageName)){
                $imagePath = $productFolder . $jpegImageName;
            }
            if (file_exists($productFolder . $jpgImageName)){
                $imagePath = $productFolder . $jpgImageName;
            }
            if (file_exists($productFolder . $pngImageName)){
                $imagePath = $productFolder . $pngImageName;
            }
            if ($imagePath) {
                // getting downloadable image url from github
                $imageUrl = GITHUB_GET_CONTENTS_PATH . $imagePath;
                echo $imageUrl . PHP_EOL;
                
                $downloadImageUrl = GithubAPI::getImageUrl($imageUrl);
                
                // array with all image urls for this product
                array_push($prodImgUrls, $downloadImageUrl);
                
                $urls = Utils::array_push_assoc($urls, $produto['codigo_produto'], $prodImgUrls); // GitHub repository link
            }
        }
    }

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