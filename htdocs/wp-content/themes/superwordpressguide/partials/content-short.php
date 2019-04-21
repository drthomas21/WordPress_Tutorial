<section class="post clearfix">
    <div class="post-header">
        <h2 class="col-sm-12">
            <a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>"><?php the_title(); ?></a>
        </h2>
    </div>

    <p class="col-xs-6 float-left">Categories: <?= get_the_category_link(); ?></p>
    <p class="col-xs-6 float-right"><small class="post-date">Posted <?= human_time_diff(strtotime($post->post_date),time()); ?> ago</small></p>
    <div class="clearfix"></div>
    <div class="post-excerpt text-center col-xs-12">
        <?php the_featured_content(); ?>
    </div>
    <span class="read-more"><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>">Read More &gt;</a></span>
    <?php if(!empty(get_the_tag_link())): ?><p>Tags: <?= the_tag_link(); ?></p><?php endif; ?>
</section>
