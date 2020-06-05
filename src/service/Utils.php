<?php

class Utils {
    public static function json_minify(string $json): string {
        return json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
    }
    public static function array_push_assoc(array $array, $key, $value){
        $array[$key] = $value;
        return $array;
    }

    public static function getImagesUrl(array $produtos): array {
        $urls = [];
    
        foreach ($produtos as $produto) {
            $prodImgUrls = [];
            
            // get all product folders
            $productFolder = IMAGES_FOLDER_PATH . $produto['codigo'] . '/';
            for ($i=0;$i<5;$i++) {
                $imagePath = false;
    
                $jpgImageName  = $produto['codigo'] . '-' . strval($i+1) . '.jpg';  // jpg format
                $jpegImageName = $produto['codigo'] . '-' . strval($i+1) . '.jpeg'; // jpeg format
                $pngImageName  = $produto['codigo'] . '-' . strval($i+1) . '.png';  // png format
    
                echo($productFolder . $jpgImageName) . PHP_EOL;
                echo($productFolder . $jpegImageName) . PHP_EOL;
                echo($productFolder . $pngImageName) . PHP_EOL;
    
                if (file_exists($productFolder . $jpegImageName)) {
                    $imagePath = $productFolder . $jpegImageName;
                }
                if (file_exists($productFolder . $jpgImageName)) {
                    $imagePath = $productFolder . $jpgImageName;
                }
                if (file_exists($productFolder . $pngImageName)) {
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
        return $urls;
    }
}