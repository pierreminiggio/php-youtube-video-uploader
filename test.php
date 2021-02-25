<?php

use PierreMiniggio\YoutubeVideoUploader\FR\CategoryEnum;
use PierreMiniggio\YoutubeVideoUploader\PrivacyStatusEnum;
use PierreMiniggio\YoutubeVideoUploader\YoutubeUploader;
use PierreMiniggio\YoutubeVideoUploader\YoutubeVideo;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$useLikeThis = 'Use like this:'
    . PHP_EOL
    . 'composer test [clientId] [clientSecret] [refreshToken]'
;

if (! isset($argv[1])) {
    die($useLikeThis);
}

$clientId = $argv[1];

if (! isset($argv[2])) {
    die($useLikeThis);
}

$clientSecret = $argv[2];

if (! isset($argv[3])) {
    die($useLikeThis);
}

$refreshToken = $argv[3];

$uploader = new YoutubeUploader();

$uploader->upload(
    $clientId,
    $clientSecret,
    $refreshToken,
    new YoutubeVideo(
        'test titre',
        'test description',
        CategoryEnum::EDUCATION,
        PrivacyStatusEnum::UNLISTED_VIDEO,
        'https://old.miniggiodev.fr/videos/SWAL2.mp4'
    )
);
