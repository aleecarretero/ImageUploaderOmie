<?php

class Utils {

    // json formatting
    public static function json_minify(string $json): string {
        return json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
    }

    // pushing associative arrays
    public static function array_push_assoc(array $array, $key, $value){
        $array[$key] = $value;
        return $array;
    }

    // return array of images to be pushed to Omie
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

                    $git = new GithubAPI;
                    $downloadImageUrl = $git->getImageUrl($imageUrl);
                    
                    // array with all image urls for this product
                    array_push($prodImgUrls, $downloadImageUrl);
                    
                    $urls = Utils::array_push_assoc($urls, $produto['codigo_produto'], $prodImgUrls); // GitHub repository link
                } elseif ($i==0) {
                    echo ("Nenhuma imagem encontrada para o produto {$produto['codigo']}" . PHP_EOL);
                }
            }
        }
        if ($urls) {
            return $urls;
        } else {
            throw new Exception("Nenhum produto encontrado", 404);
            
        }
    }

    // push a batch of images to Omie
    public static function sendBatchImg(array $produtos) {
        
        // Generate image url
        $urls = self::getImagesUrl($produtos);

        // push images to Omie for each product
        foreach ($urls as $key=>$produto) {

                $omieRequest = new OmieAPI;
                $content = $omieRequest->alterarImagens(strval($key), $produto, APP_KEY, APP_SECRET);
                $content = json_decode($content, true);

                if ($content['codigo_status'] == 0) {
                    echo(
                        'Sucesso! ' .
                        "Produto {$content['codigo_produto_integracao']} " .
                        'alterado com sucesso' . PHP_EOL
                    );
                } else {
                    echo (
                        "Erro {$content['codigo_status']}: " .
                        $content['descricao_status']
                    );
                }
        }
    }
}