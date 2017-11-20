<?php
namespace Youtube_Vids\Controllers;
class PageController {
    private $Service = null;
    public function __construct() {
        $this->Service = new \Youtube_Vids\Services\YoutubeService();
    }

    public function getPopularVideos(int $offset = 0, int $limit = 10): array {
        $vidoes = $this->Service->getPopularVideos($offset,$limit);
        return $vidoes;
    }

    public function getRecentVideos(int $offset = 0, int $limit = 10): array {
        $vidoes = $this->Service->getNewestVideos($offset,$limit);
        return $vidoes;
    }
}
