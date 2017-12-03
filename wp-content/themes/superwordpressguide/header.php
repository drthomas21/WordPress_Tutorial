<!Doctype HTML>
<html lang="en">
    <head>
        <title><?php wp_title(); ?></title>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- WP_HEAD -->
        <?php wp_head(); ?>
        <!-- END WP_HEAD -->

        <?php if(is_single() && !is_attachment()): ?>
            <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=5a19bd0f1d108f0012ed9d85&product=inline-share-buttons"></script>
        <?php endif; ?>

        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-6823528022937171",
            enable_page_level_ads: true
          });
        </script>

        <?php if(is_user_logged_in()): ?>
        <style>
            nav.navbar {
                top: 32px;
            }
            @media all and (max-width: 600px) {
                nav.navbar {
                    top: 0px;
                }
            }
        </style>
        <?php endif; ?>
    </head>
    <body>
          <?php get_template_part("partials/navbar"); ?>
          <div class="container-fluid">
