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

    public static function checkWinSafe(string $value) {
        $forbidden = [
            "\\",
            "/",
            ":",
            "*",
            "?",
            "\"",
            "<",
            ">",
            "|",
        ];

        foreach ($forbidden as $char) {
            $pos = strpos($value, $char);    
            if ($pos !== false) {
                return $char;
            }
        }
        return true;
    }

    // return array of images to be pushed to Omie
    public static function getImagesUrl(array $produtos): array {
        $urls = [];
    
        foreach ($produtos as $produto) {
            $winSafe = Utils::checkWinSafe($produto['codigo']);
            if ($winSafe !== true) {
                Utils::echoLog(
                    'Erro!' . PHP_EOL .
                    "Caracter inválido encontrado em {$produto['descricao']}" . PHP_EOL .
                    "(cod.: {$produto['codigo']})" . PHP_EOL .
                    "(caracter: {$winSafe})" . LINE_SEPARATOR
                );
                continue;
            }
            
            $prodImgUrls = [];
            
            // get all product folders

            $generalProduct = [];
            preg_match("/.{3}\..{3}\..{4}/",$produto['codigo'], $generalProduct);

            if (sizeof($generalProduct) > 0) {
                $productFolder = IMAGES_FOLDER_DIR . $generalProduct['0'] . '\\';
                $productURL = IMAGES_FOLDER_PATH . $generalProduct['0'] . '/';
            } else {
                $productFolder = IMAGES_FOLDER_DIR . $produto['codigo'] . '\\';
                $productURL = IMAGES_FOLDER_PATH . $produto['codigo'] . '/';
            }
            

            if (is_dir($productFolder)) {
                $files = array_diff(scandir($productFolder), array('.', '..'));
            } else {
                Utils::echoLog(
                    'Erro!' . PHP_EOL .
                    "Nenhuma imagem encontrada para {$produto['descricao']}" . PHP_EOL .
                    "(cod.: {$produto['codigo']})" . LINE_SEPARATOR
                );
                continue;
            }

            foreach ($files as $file => $path) {
                // clean non-image files
                $ext = pathinfo($path)['extension'];
                if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png'){
                    unset($files[$file]);
                }
            }
            
            if (sizeof($files) > 0){
                foreach ($files as $file) {
                    $imagePath = $productFolder.$file;
                    // get filename

                    if (file_exists($imagePath)) {

                        $imagePath = $productURL.$file;

                        // getting downloadable image url from github
                        $imageUrl = GITHUB_GET_CONTENTS_PATH . $imagePath;

                        $git = new GithubAPI;
                        $downloadImageUrl = $git->getImageUrl($imageUrl);
                
                        // array with all image urls for this product
                        array_push($prodImgUrls, $downloadImageUrl);
                
                        $urls[$produto['codigo_produto']] = $prodImgUrls; // GitHub repository link
                    }

                }
            } else {
                Utils::echoLog(
                    'Erro!' . PHP_EOL .
                    "Nenhuma imagem encontrada para {$produto['descricao']}" . PHP_EOL .
                    "(cod.: {$produto['codigo']})" . LINE_SEPARATOR
                );
            }
            
        }   
        if ($urls) {
            return $urls;
        } else {
            Utils::echoLog(
                "ERRO FATAL:" . PHP_EOL .
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

                    $codigo = OmieAPI::getCodigo($content['codigo_produto']);

                    Utils::echoLog (
                        'Sucesso!' . PHP_EOL .
                        "Produto {$codigo} " .
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