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
