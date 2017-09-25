<!-- Post Data -->
<meta http-equiv="Content-Type" content="text/html; charset=<?= bloginfo( 'charset' ); ?>">
<meta name="description" content="<?= strip_tags(get_the_excerpt()); ?>">
<meta name="keywords" content="<?= get_the_keywords(); ?>" >
<meta property="og:title" content="<?= get_the_title(); ?>">
<meta property="og:type" content="article" >
<meta property="og:description" content="<?= strip_tags(get_the_excerpt()); ?>">
<meta property="og:url" content="<?= get_permalink(); ?>">
<link rel="canonical" href="<?= get_permalink(); ?>">

<!-- Image Data -->
<?php foreach(get_children([
    "post_parent" => $post->ID,
    "post_mime_type" => "image",
    "orderby" => "menu_order",
    "order" => "ASC"
]) as $Attachment): $data = wp_get_attachment_image_src($Attachment->ID,'original'); ?>
<meta property="og:image" content="<?= $data[0]; ?>">
<meta property="og:image:width" content="<?= $data[1]; ?>">
<meta property="og:image:height" content="<?= $data[2]; ?>">
<?php endforeach; ?>
