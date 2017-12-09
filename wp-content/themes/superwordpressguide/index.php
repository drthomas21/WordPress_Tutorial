<?php get_header(); ?>
<section class="homepage blog-header">
    <div class="main-content">
        <div class="col-md-12 col-lg-8 float-left">
            <div class="col-xl-6 main-content-1">
                <?php if(!empty(list_recent_videos(0,2))): ?>
                    <div class="youtube-vids">
                        <h2 class='text-center section-label'>Newest Video</h2>
                        <?php foreach(list_recent_videos(0,2) as $Video): ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $Video->id; ?>?rel=0" allowfullscreen></iframe>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="posts col-xl-6 main-content-2">
                <h2 class='text-center section-label'>Recent Posts</h2>
                <?php if(have_posts()): $c = 0; while(have_posts() && $c < 1): $c++; the_post(); ?>
                    <?php get_template_part("partials/content","short"); ?>
                <?php endwhile; else: ?>
                    <?php get_template_part("partials/content","empty"); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-12 col-lg-4 float-right">
            <?php if(!empty(list_popular_videos(0,2))): ?>
                <div class="col-lg-12 youtube-vids">
                    <h2 class='text-center section-label'>Going Viral?</h2>
                    <?php foreach(list_popular_videos(0,2) as $Video): ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $Video->id; ?>?rel=0" allowfullscreen></iframe>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-12 center-block text-center ">
            <div class="col-md-8 inline-block">
                <hr />
            </div>
        </div>
        <div class="posts col-sm-12">
            <h2 class='text-center section-label'>OP;ED</h2>
            <?php foreach(get_posts(['posts_per_page' => 3,'category' => [412]]) as $post):  setup_postdata($post); ?>
            <div class="col-lg-4 col-md-12 float-left">
                <div class="col-sm-12"><?php get_template_part("partials/content","short"); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-12 center-block text-center ">
            <div class="col-md-8 inline-block">
                <hr />
            </div>
        </div>
        <div class="posts more-posts col-sm-12">
            <h2 class='text-center section-label'>More Posts</h2>
            <?php $c = 0; while(have_posts()): $c++; the_post(); ?>
            <div class="col-lg-4 col-md-12 float-left">
                <div class="col-sm-12"><?php get_template_part("partials/content","short"); ?></div>
            </div>
            <?php if($c % 3 == 0): ?><div class="col-md-12 clearfix"></div><?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
