<section class="fourohfour blog-header">
    <div class="page-container">
        <h1 class="text-center">404 :(</h1>
        <div class="youtube-vids row">

            <p class="col-lg-12 text-center">
                Sorry, you are trying to access something that does not exists.
                Try visiting the <a href="<?= site_url(); ?>">home page</a> or
                searching for it. Otherwise, check out my videos below...
            </p>
        </div>
        <div class="youtube-vids row">
            <div class="embed-responsive embed-responsive-16by9 col-sm-6" ng-repeat="Video in poularVideos | limitTo: 3">
                <iframe class="embed-responsive-item" ng-src="{{getYoutubeUrl(Video.id)}}" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>
