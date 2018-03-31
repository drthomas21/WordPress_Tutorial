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
        $category = $Request->get_param('category');
        $tag = $Request->get_param('tag');
        $limit = intval($Request->get_param('limit'));
        $offset = intval($Request->get_param('offset'));
        $search = $Request->get_param('search');

        if($limit <= 0) {
            $limit = 10;
        }
        if($offset < 0) {
            $offset = 0;
        }

        $Items = [];

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

        $args = [
            "offset" => $offset,
            "posts_per_page" => $limit,
            "has_password" => current_user_can('edit_others_posts') ? null : false
        ];

        if($search) {
            $args['s'] = $search;
        } elseif($category) {
            if(is_numeric($category)) {
                $args['category'] = intval($category);
            } else {
                $args['category_name'] = $category;
            }

        } elseif($tag) {
            if(is_numeric($tag)) {
                $args['tag_id  '] = intval($tag);
            } else {
                $args['tag '] = $tag;
            }
        }

        $Posts = \get_posts($args);

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
        global $post;
        $postName = $Request->get_param("post_name");
        $password = $Request->get_param("password");
        if(!$password) {
            $password = md5("");
        }

        $postType = $Request->get_param("post_type");
        if(!in_array($postType,['post','page'])) {
            $postType = "post";
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
            $post = null;
            if(is_numeric($postName)) {
                $post = \get_post($postName,OBJECT);
            } else {
                $post = \get_page_by_path($postName,OBJECT,$postType);
            }

            if($post && $post->post_status == "publish" && (strtolower(md5($post->post_password)) == strtolower($password) || current_user_can('edit_others_posts'))) {
                setup_postdata($post);
                $Item = new \stdClass();
                $Item->permalink = [
                    "alt" => htmlentities($post->post_title,ENT_QUOTES|ENT_HTML5),
                    "href" => \get_permalink($post),
                    "label"=> $post->post_title
                ];
                $Item->timestamp = strtotime($post->post_date);
                $Item->human_time = human_time_diff(strtotime($post->post_date),time());
                $Item->post_content = apply_filters("the_content",$post->post_content);
                $Item->tag_html = get_the_tag_link();
                $Item->category_html = get_the_category_link();
                $Item->ID = $post->ID;
                $Item->post_title = $post->post_title;
                $Item->post_name = $post->post_name;
                $Item->post_excerpt = $post->post_excerpt;
            }
        }
        return $Item;
    }
}
