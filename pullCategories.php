<?php

use PierreMiniggio\GoogleTokenRefresher\AccessTokenProvider;

$useLikeThis = 'Use like this:'
    . PHP_EOL
    . 'composer run-script pull-categories [locale] [clientId] [clientSecret] [refreshToken]'
;

if (! isset($argv[1])) {
    die($useLikeThis);
}

$locale = $argv[1];

if (! isset($argv[2])) {
    die($useLikeThis);
}

$clientId = $argv[2];

if (! isset($argv[3])) {
    die($useLikeThis);
}

$clientSecret = $argv[3];

if (! isset($argv[4])) {
    die($useLikeThis);
}

$refreshToken = $argv[4];

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$provider = new AccessTokenProvider();

$accessToken = $provider->get($clientId, $clientSecret, $refreshToken);
