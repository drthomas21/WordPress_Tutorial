<?php
define("THEME_DIRECTORY",__DIR__);
define("THEME_VERSION","3.0");
\Themes\Superwordpressguide_V3\Util\JS\AngularBuilder::getInstance();
\Themes\Superwordpressguide_V3\Util\Ajax\NgTemplateService::getInstance();

function get_asset_url(string $path): string {
    return get_template_directory_uri().'/assets/'.trim($path," \t\n\r\0\x0B/");
}

function get_the_leading_asset(string $postContent = ""): string {
    if(empty($postContent)) {
        $postContent = apply_filters("the_content",get_the_content());
    }
    $content = "";

    if(has_post_thumbnail()) {
        $content = preg_replace("/class\s*=\s*[\'\"][A-Za-z0-9\-\_\s]+[\'\"]/","class='\$1 rounded img-fluid'",get_the_post_thumbnail(null,"large"));
    } elseif(get_media_embedded_in_content($postContent,["video","object","embed","iframe"])) {
        $list = get_media_embedded_in_content($postContent,["video","object","embed","iframe"]);
        if(!empty($list)) {
            $content = '<div class="embed-responsive embed-responsive-21by9">'.$list[0].'</div>';
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
    if(is_array($Categories)) {
        foreach($Categories as $Category) {
            $list[] = "<a href='".get_tag_link($Category)."'>#".strtolower($Category->name)."</a>";
        }
    }

    return implode(", ",$list);
}

function the_tag_link() {
    echo get_the_tag_link();
}


add_action("after_setup_theme",function() {
    add_theme_support('post-thumbnails');
    add_theme_support('html5');
    add_theme_support('automatic-feed-links');
});

add_action("wp",function() {
    wp_deregister_script("jquery");
    wp_register_script("jquery",get_asset_url("js/jquery.min.js"),[],"3.2.1",false);
    //wp_register_script("popper","https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js",array("jquery"),"1.12.3",true);
    wp_register_script("popper",get_asset_url("js/popper.min.js"),['jquery'],"1.12.3",true);
    //wp_register_script("bootstrap","https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js",array("jquery","popper"),"4.0.0",true);
    wp_register_script("bootstrap",get_asset_url("js/bootstrap.min.js"),['jquery','popper'],"4.0.0",true);

    //wp_register_script("app",get_asset_url('js/app.js'),['angularjs'],THEME_VERSION,true);
    wp_register_script("sharethis","//platform-api.sharethis.com/js/sharethis.js#property=5a19bd0f1d108f0012ed9d85&product=inline-share-buttons");

    //WP Enqueue Styles
    //wp_register_style("boostrap","https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css",array(),"4.0.0","all");
    wp_register_style("boostrap",get_asset_url("css/bootstrap.min.css"),[],"4.0.0","all");
    wp_register_style("theme-main",get_stylesheet_uri(),array("boostrap"),THEME_VERSION,"all");
});

add_action("wp_enqueue_scripts",function() {
    //WP Enqueue Script
    wp_enqueue_script("jquery");
    wp_enqueue_script("popper");
    wp_enqueue_script("bootstrap");
    //wp_enqueue_script("app");
    //wp_enqueue_script("sharethis");

    //WP Enqueue Styles
    wp_enqueue_style("boostrap");
    wp_enqueue_style("theme-main");
});

add_filter("embed_oembed_html",function(string $html): string {
    $html = preg_replace("/(width|height)\s*=\s*[\'\"][0-9]+[\'\"]/","",$html);
    $html = preg_replace("/<([a-z]+)/","<\$1 class='embed-responsive-item'",$html);
    return '<div class="embed-responsive embed-responsive-21by9">'.$html.'</div>';
},10);

add_filter("the_content",function(string $content){
    $content = preg_replace("/<(img[^>]+)class\s*=\s*[\'\"]([^\'\"]+)[\'\"]/","<\$1class='\$2 center-image'",$content);

    return $content;
});
