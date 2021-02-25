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
        'CoffeeZilla is the best',
        'And I love him',
        ['CoffeeZilla', 'love', 'Coffee'],
        CategoryEnum::EDUCATION,
        PrivacyStatusEnum::PUBLIC_VIDEO,
        'https://youtube-video-random-clip-api.miniggiodev.fr/public/cache/kBr7LEUV6iA_cut.mp4'
    )
);
