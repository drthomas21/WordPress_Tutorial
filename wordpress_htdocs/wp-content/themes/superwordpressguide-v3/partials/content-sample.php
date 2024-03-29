<section class="post clearfix">
    <div class="post-header">
        <h2 class="col-sm-12">
            <span class="col-sm-12 col-md-12 col-lg-8"><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>"><?php the_title(); ?></a></span>
            <span class="col-sm-12 col-md-12 col-lg-4 float-right"><small class="post-date">Posted on <?php the_date(); ?></small></span>
        </h2>
    </div>
    <p>Categories: <?= get_the_category_link(); ?></p>
    <div class="post-excerpt text-center">
        <?php the_featured_content(); ?>
    </div>
    <span class="read-more"><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>">Read More &gt;</a></span>
    <?php if(!empty(get_the_tag_link())): ?><p>Tags: <?= the_tag_link(); ?></p><?php endif; ?>
</section>
