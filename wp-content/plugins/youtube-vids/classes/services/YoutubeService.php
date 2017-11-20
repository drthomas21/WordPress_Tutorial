<?php
namespace Youtube_Vids\Services;
class YoutubeService {
    private $GoogleDriver = null;
    private $Service = null;
    private $token = "";

    public function __construct() {
        $this->GoogleDriver = new \Youtube_Vids\Drivers\GoogleApiDriver();
        $this->GoogleDriver->prepareScopes();
        $this->GoogleDriver->checkAccessToken();

        $this->Service = new \Google_Service_YouTube($this->GoogleDriver->getClient());
    }

    private function search(array $args,int $offset = 0, int $limit = 10): \Google_Service_YouTube_SearchListResponse  {
        $args['maxResults'] = $offset + $limit;
        $args['type'] = 'video';
        $resp = array();
        try {
            $resp = $this->Service->search->listSearch('id,snippet', $args);
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        return $resp;
    }

    public function getPopularVideos(int $offset = 0, int $limit = 10): array {
        $list = array();
        $Resp = $this->search([
            'forMine' => true,
            'order' => 'viewCount',
        ],$offset,$limit);

        if(!empty($Resp->items)) {
            foreach($Resp->items as $Item) {
                $list[] = new \Youtube_Vids\Records\VideoRecord((object)(array)$Item->id,(object)(array)$Item->snippet);
            }
        }

        return $list;
    }

    public function getNewestVideos(int $offset = 0, int $limit = 10): array {
        $list = array();
        $Resp = $this->search([
            'forMine' => true,
            'order' => 'date',
        ],$offset,$limit);

        if(!empty($Resp->items)) {
            foreach($Resp->items as $Item) {
                $list[] = new \Youtube_Vids\Records\VideoRecord((object)(array)$Item->id,(object)(array)$Item->snippet);
            }
        }

        return $list;
    }
}
