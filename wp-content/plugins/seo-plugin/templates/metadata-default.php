<!-- Page Data -->
<meta http-equiv="Content-Type" content="text/html; charset=<?= get_bloginfo( 'charset' ); ?>">
<?php if(is_category()):?><meta property="og:title" content="Category |<?= single_cat_title(); ?>">
<?php elseif(is_tag()):?><meta property="og:title" content="Tag | <?= single_tag_title(); ?>">
<?php elseif(is_search()):?><meta property="og:title" content="Search | <?= get_search_query(); ?>">
<?php else: ?><meta property="og:title" content="<?= get_bloginfo('name'); ?>"><?php endif; ?>
