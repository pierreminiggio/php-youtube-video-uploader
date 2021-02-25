<?php

namespace PierreMiniggio\YoutubeVideoUploader;

use PierreMiniggio\YoutubeVideoUploader\FR\CategoryEnum;

class YoutubeVideo
{

    /**
     * @see CategoryEnum for $categoryId
     * @see PrivacyStatusEnum for $privacy
     */
    public function __construct(
        public string $title,
        public string $description,
        public int $categoryId,
        public string $privacy,
        public string $filePathOrURL,
    )
    { 
    }
}
