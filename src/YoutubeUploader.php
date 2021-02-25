<?php

namespace PierreMiniggio\YoutubeVideoUploader;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_Video;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoStatus;
use PierreMiniggio\GoogleTokenRefresher\AccessTokenProvider;

class YoutubeUploader
{

    protected AccessTokenProvider $tokenProvider;

    public function __construct()
    {
        $this->tokenProvider = new AccessTokenProvider();
    }

    public function upload(
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        YoutubeVideo $video
    ): void
    {
        $accessToken = $this->tokenProvider->get($clientId, $clientSecret, $refreshToken);

        $client = new Google_Client();
        $client->setAccessToken($accessToken);

        $videoToUpload = new Google_Service_YouTube_Video();

        $videoSnippet = new Google_Service_YouTube_VideoSnippet();
        $videoSnippet->setCategoryId((string) $video->categoryId);
        $videoSnippet->setTitle($video->title);
        $videoSnippet->setDescription($video->description);
        $videoSnippet->setTags($video->tags);
        //$videoSnippet->setPublishedAt((new \DateTime('2021-02-28 10:00:00'))->format(\DateTimeInterface::ISO8601));
        $videoToUpload->setSnippet($videoSnippet);

        $videoStatus = new Google_Service_YouTube_VideoStatus();
        $videoStatus->setPrivacyStatus($video->privacy);
        $videoToUpload->setStatus($videoStatus);

        $service = new Google_Service_YouTube($client);
        $response = $service->videos->insert(
            'snippet,status',
            $videoToUpload,
            [
                'data' => file_get_contents($video->filePathOrURL),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );

        var_dump($response);
        var_dump($response->id);
    }
}
