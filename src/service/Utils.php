<?php

require_once __DIR__.'/../service/GithubAPI.php';
require_once __DIR__.'/../service/OmieAPI.php';

class Utils {
    // log file
    public static function setLogPath($path): void {
        $now = new DateTime();
        $logFilePath = (
            $path . DIRECTORY_SEPARATOR . 
            "Logs" . DIRECTORY_SEPARATOR .
            $now->format('Ymd-His') . DIRECTORY_SEPARATOR
        );

        define('LOG_FILE', $logFilePath . $now->getTimestamp() . '.txt');

        mkdir($logFilePath, 0777, true);
    }

    // log messages
    public static function echoSys(string $msg, int $hashtagNumber = 4, int $eolUp = 1, int $eolDown = 1): string {
        $now = new DateTime();
        $output = (
            str_repeat(PHP_EOL, $eolUp) .
            str_repeat('#', $hashtagNumber) .
            " {$msg} - " . $now->format('Y-m-d H:i:s') . ' ' .
            str_repeat('#', $hashtagNumber) .
            str_repeat(PHP_EOL, $eolDown)
        );
        echo $output;
        Utils::Appendlog($output);
        return $output;
    }

    public static function echoLog($msg): void{
        echo $msg;
        file_put_contents(LOG_FILE,$msg,FILE_APPEND);
    }

    public static function Appendlog($msg): void{
        file_put_contents(LOG_FILE,$msg,FILE_APPEND);
    }

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
            $productFolder = IMAGES_FOLDER_DIR . $produto['codigo'] . '\\';
            $productURL = IMAGES_FOLDER_PATH . $produto['codigo'] . '/';
            for ($i=0;$i<5;$i++) {
                $imagePath = false;
    
                $jpgImageName  = $produto['codigo'] . '-' . strval($i+1) . '.jpg';  // jpg format
                $jpegImageName = $produto['codigo'] . '-' . strval($i+1) . '.jpeg'; // jpeg format
                $pngImageName  = $produto['codigo'] . '-' . strval($i+1) . '.png';  // png format
    
                if (file_exists($productFolder . $jpegImageName)) {
                    $imagePath = $productURL . $jpegImageName;
                }
                if (file_exists($productFolder . $jpgImageName)) {
                    $imagePath = $productURL . $jpgImageName;
                }
                if (file_exists($productFolder . $pngImageName)) {
                    $imagePath = $productURL . $pngImageName;
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
                    Utils::echoLog (
                        'Erro!' . PHP_EOL .
                        "Nenhuma imagem encontrada para {$produto['descricao']}" . PHP_EOL .
                        "(cod.: {$produto['codigo']})" . LINE_SEPARATOR
                    );
                }
            }
        }
        if ($urls) {
            return $urls;
        } else {
            Utils::echoLog(
                "Erro 404:" . PHP_EOL .
                "Nenhum produto encontrado" . LINE_SEPARATOR
            );
            exit();
        }
    }

    // push a batch of images to Omie
    public static function sendBatchImg(array $produtos) {
        // Generate image url
        Utils::echoSys('Coletando os endereços das imagens',4,1,2);
        $urls = self::getImagesUrl($produtos);

        // push images to Omie for each product
        Utils::echoSys('Enviando as imagens para o Omie',4,1,2);
        foreach ($urls as $key=>$codProduto) {

                $omieRequest = new OmieAPI;
                $response = $omieRequest->alterarImagens(strval($key), $codProduto, APP_KEY, APP_SECRET);
                $content = json_decode($response['content'],true);

                if ($content['codigo_status'] == 0) {
                    Utils::echoLog (
                        'Sucesso!' . PHP_EOL .
                        "Produto {$content['codigo_produto_integracao']} " .
                        'alterado com sucesso' . LINE_SEPARATOR
                    );
                } else {
                    Utils::echoLog (
                        "Erro {$content['codigo_status']}: " .
                        $content['descricao_status']
                    );
                }
        }
    }
}