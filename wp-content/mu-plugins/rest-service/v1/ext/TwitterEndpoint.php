<?php
namespace MU_Plugins\Rest_Service\V1\Ext;

class TwitterEndpoint implements BaseRestfulEndpoint {
    public function registerEndpoints(string $namespace) {
        \register_rest_route($namespace,'/twitter/',[
            "methods" => \WP_REST_Server::READABLE,
            "callback" => function(\WP_REST_Request $Request): array {
                return $this->getTweets($Request);
            }
        ]);
    }

    protected function getTweets(\WP_REST_Request $Request): array {
        $list = [];
        if(class_exists("\\Twitter_Feed\\Controllers\\PageController")) {
            $Controller = new \Twitter_Feed\Controllers\PageController();
            $list = $Controller->searchTweets("tech");
        }
        
        return $list;
    }
}
