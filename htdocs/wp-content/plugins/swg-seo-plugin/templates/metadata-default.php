<!-- Page Data -->
<meta http-equiv="Content-Type" content="text/html; charset=<?= get_bloginfo( 'charset' ); ?>">
<?php if(is_category()):?>
    <meta property="og:title" content="Category |<?= htmlentities(single_cat_title(),ENT_QUOTES); ?>">
    <meta property="og:description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
    <meta property="description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
<?php elseif(is_tag()):?>
    <meta property="og:title" content="Tag | <?= htmlentities(single_tag_title(),ENT_QUOTES); ?>">
    <meta property="og:description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
    <meta property="description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
<?php elseif(is_search()):?>
    <meta property="og:title" content="Search | <?= htmlentities(get_search_query(),ENT_QUOTES); ?>">
    <meta property="og:description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
    <meta property="description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
<?php else: ?>
    <meta property="og:title" content="<?= htmlentities(get_bloginfo('name'),ENT_QUOTES); ?>">
    <meta property="og:description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
    <meta property="description" content="<?= htmlentities(get_bloginfo('description'),ENT_QUOTES); ?>">
<?php endif; ?>
<meta property="og:url" content="<?= site_url($_SERVER['REQUEST_URI']); ?>">
<link rel="canonical" href="<?= site_url($_SERVER['REQUEST_URI']); ?>">
