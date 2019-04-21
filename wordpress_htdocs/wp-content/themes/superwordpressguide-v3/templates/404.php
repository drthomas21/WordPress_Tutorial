<section class="fourohfour blog-header">
    <div class="page-container">
        <h1 class="text-center">404 :(</h1>
        <div class="youtube-vids row">

            <p class="col-lg-12 text-center">
                Sorry, you have tried accessing something that does not exists! <br />
                Please try visiting <?= site_url("?search.php"); ?> and searching for what you want.
            </p>
        </div>
        <div class="youtube-vids row">
            <div class="embed-responsive embed-responsive-16by9 col-sm-6" ng-repeat="Video in poularVideos | limitTo: 3">
                <iframe class="embed-responsive-item" ng-src="{{getYoutubeUrl(Video.id)}}" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>
