<?php get_header(); ?>
<section class="attachment blog-header">
    <div class="page-container">
        <div class="col-sm-12 posts">
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
                <?php get_template_part("partials/content","single"); ?>
            <?php endwhile; else: ?>
                <?php get_template_part("partials/content","empty"); ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
