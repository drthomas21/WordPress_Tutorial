<!-- Page Data -->
<meta http-equiv="Content-Type" content="text/html; charset=<?= get_bloginfo( 'charset' ); ?>">
<?php if(is_category()):?>
    <meta property="og:title" content="Category |<?= single_cat_title(); ?>">
    <meta property="og:description" content="<?= get_bloginfo('description'); ?>">
    <meta property="description" content="<?= get_bloginfo('description'); ?>">
<?php elseif(is_tag()):?>
    <meta property="og:title" content="Tag | <?= single_tag_title(); ?>">
    <meta property="og:description" content="<?= get_bloginfo('description'); ?>">
    <meta property="description" content="<?= get_bloginfo('description'); ?>">
<?php elseif(is_search()):?>
    <meta property="og:title" content="Search | <?= get_search_query(); ?>">
    <meta property="og:description" content="<?= get_bloginfo('description'); ?>">
    <meta property="description" content="<?= get_bloginfo('description'); ?>">
<?php else: ?>
    <meta property="og:title" content="<?= get_bloginfo('name'); ?>">
    <meta property="og:description" content="<?= get_bloginfo('description'); ?>">
    <meta property="description" content="<?= get_bloginfo('description'); ?>">
<?php endif; ?>
<meta property="og:url" content="<?= site_url($_SERVER['REQUEST_URI']); ?>">
<link rel="canonical" href="<?= site_url($_SERVER['REQUEST_URI']); ?>">
