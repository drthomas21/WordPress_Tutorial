<section class="post clearfix">
    <div class="col-sm-3 post-logo float-left">
        <?= get_the_leading_asset(); ?>
    </div>
    <div class="col-sm-9 post-data float-right">
        <h2><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_excerpt(); ?></p>
        <span class="read-more"><a href="<?php the_permalink(); ?>" alt="<?= htmlentities(get_the_title(),ENT_QUOTES|ENT_HTML5); ?>">Read More &gt;</a></span>
    </div>
</section>
