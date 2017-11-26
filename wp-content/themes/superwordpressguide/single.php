<?php get_header(); ?>
<section class="single blog-header">
    <div class="page-container">
        <div class="col-sm-8 col-xl-8 posts float-left">
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
                <?php get_template_part("partials/content","single"); ?>
            <?php endwhile; else: ?>
                <?php get_template_part("partials/content","empty"); ?>
            <?php endif; ?>
        </div>
        <div class="col-sm-4 col-xl-4 float-right text-center">
            <div class="goole-ad">
                <!-- article_page -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:600px"
                     data-ad-client="ca-pub-6823528022937171"
                     data-ad-slot="5361989274"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <?php if(!empty(list_popular_videos(0,2))): ?>
                <div class="youtube-vids">
                    <h2 class='text-center section-label'>Going Viral?</h2>
                    <?php foreach(list_popular_videos(0,3) as $Video): ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $Video->id; ?>?rel=0" allowfullscreen></iframe>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
