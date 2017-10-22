<?php
define("THEME_VERSION","0.1");

function get_asset_url(string $path): string {
    return get_template_directory_uri().'/assets/'.trim($path," \t\n\r\0\x0B/");
}

function get_the_leading_asset(string $postContent = ""): string {
    if(empty($postContent)) {
        $postContent = apply_filters("the_content",get_the_content());
    }
    $content = "";

    if(has_post_thumbnail()) {
        $content = preg_replace("/class\s*=\s*[\'\"][A-Za-z0-9\-\_\s]+[\'\"]/","class='\$1 img-fluid'",get_the_post_thumbnail(null,"large"));
    } elseif(get_media_embedded_in_content($postContent,["video","object","embed","iframe"])) {
        $list = get_media_embedded_in_content($postContent,["video","object","embed","iframe"]);
        if(!empty($list)) {
            $content = '<div class="embed-responsive embed-responsive-16by9">'.$list[0].'</div>';
        }
    }

    return $content;
}

function get_the_featured_content(): string {
    $postContent = apply_filters("the_content",get_the_content());
    return get_the_leading_asset($postContent) . "<p class='text-left'>".get_the_excerpt()."</p>";;
}

function the_featured_content() {
    echo get_the_featured_content();
}

function get_the_category_link(): string {
    $list = [];
    $Categories = get_the_category();
    foreach($Categories as $Category) {
        $list[] = "<a href='".get_category_link($Category)."'>".ucwords($Category->name)."</a>";
    }

    return implode(", ",$list);
}

function the_category_link() {
    echo get_the_category_link();
}

function get_the_tag_link(): string {
    $list = [];
    $Categories = get_the_tags();
    foreach($Categories as $Category) {
        $list[] = "<a href='".get_tag_link($Category)."'>#".strtolower($Category->name)."</a>";
    }

    return implode(", ",$list);
}

function the_tag_link() {
    echo get_the_tag_link();
}


add_action("after_setup_theme",function() {
    add_theme_support('post-thumbnail');
    add_theme_support('html5');
    add_theme_support('automatic-feed-links');
});

add_action("wp_enqueue_scripts",function() {
    wp_deregister_script("jquery");

    //WP Enqueue Script
    wp_enqueue_script("jquery",get_asset_url("js/jquery.min.js"),array(),"3.2.1",true);
    //wp_enqueue_script("angularjs",get_asset_url("js/angular.min.js"),array("jquery"),"1.6.6",true);
    //wp_enqueue_script("angularjs-sanitize",get_asset_url("js/angular-sanitize.min.js"),array("angularjs"),"1.6.6",true);
    wp_enqueue_script("popper",get_asset_url("js/popper.min.js"),array("jquery"),"1.12.3",true);
    wp_enqueue_script("bootstrap",get_asset_url("js/bootstrap.min.js"),array("jquery","popper"),"4.0.0",true);
    //wp_enqueue_script("main",get_asset_url("js/main.js"),array("jquery","bootstrap"),THEME_VERSION,true);

    //WP Enqueue Styles
    wp_enqueue_style("boostrap",get_asset_url("css/bootstrap.min.css"),array(),"4.0.0","all");
    wp_enqueue_style("main",get_stylesheet_uri(),array("boostrap"),THEME_VERSION,"all");
});

add_filter("embed_oembed_html",function(string $html): string {
    $html = preg_replace("/(width|height)\s*=\s*[\'\"][0-9]+[\'\"]/","",$html);
    $html = preg_replace("/<([a-z]+)/","<\$1 class='embed-responsive-item'",$html);
    return '<div class="embed-responsive embed-responsive-16by9">'.$html.'</div>';
},10);

add_filter("the_content",function(string $content){
    $content = preg_replace("/<(img[^>]+)class\s*=\s*[\'\"]([^\'\"]+)[\'\"]/","<\$1class='\$2 center-image'",$content);

    return $content;
});
