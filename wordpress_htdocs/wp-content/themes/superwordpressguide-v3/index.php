<!Doctype HTML>
<html lang="en" ng-app="app" ng-cloak ng-controller="PageCtrl">
<head>
    <title ng-bind="pageTitle"></title>
    <base href="<?= site_url("/"); ?>" >
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://www.googleoptimize.com/optimize.js?id=GTM-K295QQK"></script>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SWNVKMV3X3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-SWNVKMV3X3', {
            'send_page_view':false
        });
        gtag('config', 'UA-29594513-13', {
            'send_page_view':false
        });
    </script>

    <!-- Facebook App ID -->
    <meta property="fb:app_id" content="1996617927234073">

    <!-- WP_HEAD -->
    <?php wp_head(); ?>
    <!-- END WP_HEAD -->

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
    <div class="container-fluid" ng-view></div>
    <?php get_template_part("partials/footer"); ?>
    <?php wp_footer(); ?>
</body>
</html>
