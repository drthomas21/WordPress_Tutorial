<section class="post">
    <div class="post-header">
        <h2 class="col-sm-12">
            <span class="col-sm-12 col-md-12 col-lg-8"><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>"><?php the_title(); ?></a></span>
            <span class="col-sm-12 col-md-12 col-lg-4 float-right"><small class="post-date">Posted on <?php the_date(); ?></small></span>
        </h2>
    </div>
    <div class="post-excerpt text-center">
        <?php the_featured_content(); ?>
    </div>
    <span class="read-more"><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>">Read More &gt;</a></span>
</section>
