<?php
class SeoCore {
    const OPTION_DEFAULT_KEYWORDS = "default_keywords";

    private static $Instance = null;

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    protected function __construct() {
        add_action("wp_head",function() {
            echo "<!-- Start SEO Plugin -->" . PHP_EOL;
            include SEO_BASE.'/templates/analytics.php';
            if(is_single()) {
                global $post;
                include SEO_BASE.'/templates/metadata-post.php';
            } else {
                include SEO_BASE.'/templates/metadata-default.php';
            }
            echo "<!-- End SEO Plugin -->" . PHP_EOL;
        });

        add_action("save_post",function(int $id) {
            $post = get_post($id);
            $keywords = get_keywords($post,20);
            $tags = [];
            foreach($keywords as $tag) {
                if(!tag_exists($tag)) {
                    wp_create_tag($tag);
                }

                $Tag = get_term_by('slug',$tag,'post_tag');
                if($Tag) {
                    $tags[] = $Tag->slug;
                }
            }

            if(!empty($tags)) {
                wp_set_post_tags($id,$tags,true);
            }
        });
    }
}
