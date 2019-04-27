<!Doctype HTML>
<html lang="en" ng-app="app" ng-cloak ng-controller="PageCtrl">
<head>
    <title ng-bind="pageTitle"></title>
    <base href="<?= site_url("/"); ?>" >
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>.async-hide { opacity: 0 !important} </style>
    <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
    h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
    (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
    })(window,document.documentElement,'async-hide','dataLayer',4000,
    {'GTM-K295QQK':true});</script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-29594513-13', 'auto');
        ga('require', 'GTM-K295QQK');
        ga('set', 'anonymizeIp', true);
        //ga('send', 'pageview');
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
