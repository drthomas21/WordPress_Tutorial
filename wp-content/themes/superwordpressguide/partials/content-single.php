<section class="post">
    <h1><?php the_title(); ?></h1>
    <small>Posted on <?php the_date(); ?></small>
    <p>Categories: <?= get_the_category_link(); ?></p>
    <?php the_content(); ?>
    <?php if(!empty(get_the_tag_link())): ?><p>Tags: <?= the_tag_link(); ?></p><?php endif; ?>
</section>
