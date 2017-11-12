<!Doctype HTML>
<html lang="en">
    <head>
        <title><?php wp_title(); ?></title>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- WP_HEAD -->
        <?php wp_head(); ?>
        <!-- END WP_HEAD -->
    </head>
    <body ng-app="app">
          <?php get_template_part("partials/navbar"); ?>
          <div class="container-fluid">
