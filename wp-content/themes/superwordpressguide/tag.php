<?php get_header(); ?>
<section class="tag blog-header">
    <div class="page-container">
        <div class="col-lg-12 col-xl-8">
            <h1 class="text-center">Tag: <?= ucwords(single_cat_title()); ?></h1>
            <div class="col-sm-12 col-md-6 col-lg-8 posts float-left">
                <?php if(have_posts()): while(have_posts()): the_post(); ?>
                    <?php get_template_part("partials/content","snippet"); ?>
                <?php endwhile; else: ?>
                    <?php get_template_part("partials/content","empty"); ?>
                <?php endif; ?>
            </div>
            <div class="col-md-6 col-lg-4 hide-sm float-right">
                <?php get_sidebar("homepage"); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
