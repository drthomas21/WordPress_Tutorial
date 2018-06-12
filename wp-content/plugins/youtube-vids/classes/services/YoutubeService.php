<?php
namespace Youtube_Vids\Services;
class YoutubeService {
    private $GoogleDriver = null;
    private $Service = null;
    private $token = "";

    public function __construct() {
        $this->GoogleDriver = new \Youtube_Vids\Drivers\GoogleApiDriver();
        $this->GoogleDriver->prepareScopes();
        if(!$this->GoogleDriver->checkAccessToken() && $this->GoogleDriver->refreshAccessToken()) {
            error_log("Could not refresh token");
        }

        $this->Service = new \Google_Service_YouTube($this->GoogleDriver->getClient());
    }

    private function search(array $args,int $offset = 0, int $limit = 10): \Google_Service_YouTube_SearchListResponse  {
        $args['maxResults'] = $offset + $limit;
        $args['type'] = 'video';
        $resp = [];

        if($args['maxResults'] <= 50) {
            try {
                $resp = $this->Service->search->listSearch('id,snippet', $args);
            } catch(\Exception $e) {
                error_log($e->getMessage());
            }

            if(!is_a($resp,"Google_Service_YouTube_SearchListResponse")) {
                throw new \Exception("Invalid object");
            }
        } else {
            $args['maxResults'] = 50;
            $iter = ceil(($offset + $limit) / $args['maxResults']) ;
            try {
                $i = 0;
                $resp = null;
                while($i < $iter) {
                    if($resp != null) {
                        $args['nextPageToken'] = $resp->nextPageToken;
                    }

                    $resp = $this->Service->search->listSearch('id,snippet', $args);
                    $i++;
                }
            } catch(\Exception $e) {
                error_log($e->getMessage());
            }

            if(!is_a($resp,"Google_Service_YouTube_SearchListResponse")) {
                throw new \Exception("Invalid object");
            }
        }


        return $resp;
    }

    public function getPopularVideos(int $offset = 0, int $limit = 10): array {
        $list = array();

        try {
            $Resp = $this->search([
                'forMine' => true,
                'order' => 'viewCount',
            ],$offset,$limit);

            if(!empty($Resp->items)) {
                foreach($Resp->items as $Item) {
                    $list[] = new \Youtube_Vids\Records\VideoRecord((object)(array)$Item->id,(object)(array)$Item->snippet);
                }
            }
        } catch(\Exception $e) {
            //Do nothing;
        }

        return array_slice($list,$offset,$limit);
    }

    public function getNewestVideos(int $offset = 0, int $limit = 10): array {
        $list = array();

        try {
            $Resp = $this->search([
                'forMine' => true,
                'order' => 'date',
            ],$offset,$limit);

            if(!empty($Resp->items)) {
                foreach($Resp->items as $Item) {
                    $list[] = new \Youtube_Vids\Records\VideoRecord((object)(array)$Item->id,(object)(array)$Item->snippet);
                }
            }
        } catch(\Exception $e) {
            //Do nothing;
        }

        return array_slice($list,$offset,$limit);
    }
}
