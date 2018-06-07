<?php
namespace MU_Plugins\Rest_Service\V1\Ext;

class YoutubeEndpoint implements BaseRestfulEndpoint {
    public function registerEndpoints(string $namespace) {
        \register_rest_route($namespace,'/videos',[
            "methods" => \WP_REST_Server::READABLE,
            "callback" => function(\WP_REST_Request $Request):array {
                return $this->getVideos($Request);
            }
        ]);
    }

    protected function getVideos(\WP_REST_Request $Request):array {
        $data = $_GET;
        $orderby = $Request->get_param("orderby");
        if($orderby) {
            $orderby = strtolower($orderby);
        }

        $limit = intval($Request->get_param('limit'));
        $offset = intval($Request->get_param('offset'));

        if($limit <= 0) {
            $limit = 10;
        }
        if($offset < 0) {
            $offset = 0;
        }

        $Items = [];

        if($orderby == "popular") {
            if(function_exists("get_popular_videos")) {
                $Videos = \get_popular_videos($offset,$limit);
                if(!empty($Videos)) {
                    foreach($Videos as $Vid) {
                        $Item = new \stdClass();
                        $Item->id = $Vid->id;
                        $Items[] = $Item;
                    }
                }
            }
        } else {
            if(function_exists("get_recent_videos")) {
                $Videos = \get_recent_videos($offset,$limit);
                if(!empty($Videos)) {
                    foreach($Videos as $Vid) {
                        $Item = new \stdClass();
                        $Item->id = $Vid->id;
                        $Items[] = $Item;
                    }
                }
            }
        }

        return $Items;
    }
}
