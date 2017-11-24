<?php get_header(); ?>
<section class="homepage blog-header">
    <div class="col-md-12 col-lg-8 float-left main-content">
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
                <div class='clearfix'></div>
                <hr />
            <?php endif; ?>
            <div class="posts">
                <h2 class='text-center section-label'>OP;ED</h2>
                <?php
                    foreach(get_posts(['posts_per_page' => 5,'category' => [412]]) as $post) {
                        setup_postdata($post);
                        get_template_part("partials/content","short");
                    }
                ?>
            </div>
        </div>
        <div class="posts col-xl-6 main-content-2">
            <h2 class='text-center section-label'>Recent Posts</h2>
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
                <?php get_template_part("partials/content","short"); ?>
            <?php endwhile; else: ?>
                <?php get_template_part("partials/content","empty"); ?>
            <?php endif; ?>
        </div>


    </div>

    <div class="col-md-12 col-lg-4 float-right">
        <?php if(!empty(list_popular_videos(0,2))): ?>
            <div class="youtube-vids">
                <h2 class='text-center section-label'>Going Viral?</h2>
                <?php foreach(list_popular_videos(0,5) as $Video): ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $Video->id; ?>?rel=0" allowfullscreen></iframe>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="goole-ad">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- homepage -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:300px;height:600px"
                 data-ad-client="ca-pub-6823528022937171"
                 data-ad-slot="7968861962"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
</section>

<?php get_footer(); ?>
