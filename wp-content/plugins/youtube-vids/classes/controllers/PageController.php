<?php
namespace Youtube_Vids\Controllers;
class PageController {
    private $Service = null;
    public function __construct() {
        $this->Service = new \Youtube_Vids\Services\YoutubeService();
    }

    public function listPopularVideos(int $offset = 0, int $limit = 10): string {
        $vidoes = $this->Service->getPopularVideos($offset,$limit);
        $html = "";
        if(!empty($videos)) {
            foreach ($videos as $Video) {
                
            }
        }
    }
}
