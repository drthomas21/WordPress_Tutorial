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
            $list = $Controller->searchTweets("#tech OR #apple OR #google OR #facebook");

            array_walk($list, function($Item) {
                $Now = new \DateTime();
                $Date = new \DateTime();
                $Date->setTimestamp($Item->created_at);
                $Diff = $Date->diff($Now);

                $humanRead = "";
                $years = $Diff->y;
                $months = $Diff->m;
                $days = $Diff->d;
                $hours = $Diff->h;
                $mins = $Diff->i;
                if($years >= 1) {
                    $humanRead = $years . ($years > 1 ? " years" : " year") . " ago";
                } else if($months >= 1) {
                    $humanRead = $months . ($months > 1 ? " months" : " month") . " ago";
                } else if($days >= 1) {
                    $humanRead = $days . ($days > 1 ? " days" : " day") . " ago";
                } else if($hours >= 1) {
                    $humanRead = $hours . ($hours > 1 ? " hours" : " hour") . " ago";
                } else if($mins >= 1) {
                    $humanRead = $mins . ($mins > 1 ? " minutes" : " minute") . " ago";
                } else {
                    $humanRead = "now";
                }
                $Item->human_time = $humanRead;
            });
        }

        return $list;
    }
}
