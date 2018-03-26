<?php
namespace MU_Plugins\Rest_Service\V1\Ext;

class PostEndpoint implements BaseRestfulEndpoint {
    public function registerEndpoints(string $namespace) {
        \register_rest_route($namespace,'/posts',[
            "methods" => \WP_REST_Server::READABLE,
            "callback" => function(\WP_REST_Request $Request): array {
                return $this->getPosts($Request);
            }
        ]);

        \register_rest_route($namespace,'/post/(?P<post_name>[A-Za-z0-9\-\_\"\']+)',[
            "methods" => \WP_REST_Server::READABLE,
            "callback" => function(\WP_REST_Request $Request): ?\stdClass {
                return $this->getPost($Request);
            }
        ]);
    }

    protected function getPosts(\WP_REST_Request $Request):array {
        global $post;
        $category = intval($Request->get_param('category'));
        $limit = intval($Request->get_param('limit'));
        $offset = intval($Request->get_param('offset'));

        if($category < 0) {
            $category = 0;
        }
        if($limit <= 0) {
            $limit = 10;
        }
        if($offset < 0) {
            $offset = 0;
        }

        $Items = [];
        $Posts = \get_posts([
            "posts_per_page" => $limit,
            "category" => [$category]
        ]);

        foreach($Posts as $post) {
            setup_postdata($post);
            $Item = new \stdClass();
            foreach(["ID","post_name","post_title","post_content","post_date"] as $prop) {
                $Item->$prop = $post->$prop;
            }
            $Item->permalink = [
                "alt" => htmlentities($post->post_title,ENT_QUOTES|ENT_HTML5),
                "href" => \get_permalink($post),
                "label"=> $post->post_title
            ];
            $Item->timestamp = strtotime($Item->post_date);
            $Item->human_time = human_time_diff(strtotime($post->post_date),time());
            $Item->post_content = get_the_featured_content();
            $Item->tag_html = get_the_tag_link();
            $Item->category_html = get_the_category_link();
            $Items[] = $Item;
        }

        return $Items;
    }

    protected function getPost(\WP_REST_Request $Request): ?\stdClass {
        $postName = $Request->get_param("post_name");
        $password = $Request->get_param("password");
        if(!$password) {
            $password = md5("");
        }
        $Item = null;

        $user_id = get_current_user_id();
        if(!$user_id) {
            $user_id = wp_validate_auth_cookie();
        }
        if(!$user_id) {
            $user_id = wp_validate_auth_cookie('',"logged_in");
        }
        if(!$user_id) {
            $user_id = wp_validate_auth_cookie('',"secure_auth");
        }
        if(!$user_id) {
            $user_id = wp_validate_auth_cookie('',"auth");
        }

        wp_set_current_user($user_id);

        if($postName) {
            $Post = \get_page_by_path($postName,OBJECT,'post');
            if($Post && (strtolower(md5($Post->post_password)) == strtolower($password) || current_user_can('edit_others_posts'))) {
                $Item = new \stdClass();
                $Item->permalink = [
                    "alt" => htmlentities($Post->post_title,ENT_QUOTES|ENT_HTML5),
                    "href" => \get_permalink($Post),
                    "label"=> $Post->post_title
                ];
                $Item->timestamp = strtotime($Post->post_date);
                $Item->human_time = human_time_diff(strtotime($Post->post_date),time());
                $Item->post_content = apply_filters("the_content",$Post->post_content);
                $Item->tag_html = get_the_tag_link();
                $Item->category_html = get_the_category_link();
                $Item->ID = $Post->ID;
                $Item->post_title = $Post->post_title;
                $Item->post_name = $Post->post_name;
                $Item->post_excerpt = $Post->post_excerpt;
            }
        }
        return $Item;
    }
}
