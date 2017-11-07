<!Doctype HTML>
<html lang="en">
    <head>
        <title><?php wp_title(); ?></title>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php wp_head(); ?>
        <?php if(stripos($_SERVER['USER_AGENT'],"Google Page Speed Insights") === false): ?>
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <?php endif; ?>

    </head>
    <body ng-app="app">
          <?php get_template_part("partials/navbar"); ?>
          <div class="container-fluid">
