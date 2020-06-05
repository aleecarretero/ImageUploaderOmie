<?php
// https://api.github.com/repos/aleecarretero/ImageUploaderOmie/contents/src/images/
define('GITHUB_API_URL', 'https://api.github.com/');
define('GITHUB_REPO_NAME','ImageUploaderOmie');
define('GITHUB_REPO_OWNER','aleecarretero');
define('IMAGES_FOLDER_PATH','src/images/');

 //GET /repos/:owner/:repo/contents/:path
define('GITHUB_GET_CONTENTS_PATH',
    GITHUB_API_URL .
    'repos/' .
    GITHUB_REPO_OWNER . '/' . 
    GITHUB_REPO_NAME . '/' .
    'contents/'
    // :path
);