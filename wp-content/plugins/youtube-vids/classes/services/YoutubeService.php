<?php
namespace Youtube_Vids\Services;
class YoutubeService {
    private $GoogleDriver = null;
    private $Service = null;

    public function __construct() {
        $this->GoogleDriver = new \Youtube_Vids\Drivers\GoogleApiDriver();
        $this->Service = new Google_Service_YouTube($this->GoogleDriver);
    }

    private function search(array $args,int $offset = 0, int $limit = 10): array {
        $args['maxResults'] = ($offset+1) * $limit;
        $resp = array();
        try {
            $resp = $this->Service->search->listSearch('id,snippet', $args);
        } catch(\Exception $e) {
            var_dump($e);
            exit;
        }
        return $resp;
    }

    public function getPopularVideos(int $offset = 0, int $limit = 10): array {
        $resp = $this->searc([
            'forMine' => true,
            'type' => 'video',
            'order' => 'viewCount',
        ],$offset,$limit);
        return $resp;
    }

    public function getNewestVideos(int $offset = 0, int $limit = 10): array {
        $resp = $this->searc([
            'forMine' => true,
            'type' => 'video',
            'order' => 'date',
        ],$offset,$limit);
        return $resp;
    }
}
