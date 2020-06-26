<?php
// Date and Time
date_default_timezone_set('America/Sao_Paulo');

// https://api.github.com/repos/aleecarretero/ImageUploaderOmie/contents/src/images/
define('GITHUB_API_URL', 'https://api.github.com/');
define('GITHUB_REPO_NAME','ImageUploaderOmie');
define('GITHUB_REPO_OWNER','aleecarretero');
define('ROOT_DIR',str_ireplace("src".DIRECTORY_SEPARATOR."model","",__DIR__));
define('IMAGES_FOLDER_PATH','src/images/');
define('IMAGES_FOLDER_DIR',ROOT_DIR."src".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR);

//GET /repos/:owner/:repo/contents/:path
define('GITHUB_GET_CONTENTS_PATH',
    GITHUB_API_URL .
    'repos/' .
    GITHUB_REPO_OWNER . '/' . 
    GITHUB_REPO_NAME . '/' .
    'contents/'
    // :path
);

// FORMATTING
define('LINE_SEPARATOR', PHP_EOL . '==================================================' . PHP_EOL); // 50 "="