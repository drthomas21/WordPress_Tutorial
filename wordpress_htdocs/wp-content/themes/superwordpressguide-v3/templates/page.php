<section class="page blog-header">
    <div class="page-container row">
        <div class="col-sm-12 col-md-8 posts">
            <section class="post">
                <h1 ng-bind="Post.post_title"></h1>
                <!--div class="sharethis-inline-share-buttons"></div-->
                <div class="post_content" ng-bind-html="Post.post_content"></div>
            </section>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="youtube-vids">
                <h2 class="text-center section-label">Going Viral?</h2>
                <div class="embed-responsive embed-responsive-16by9" ng-repeat="Video in popularVideos | limitTo: 3">
                    <iframe class="embed-responsive-item" ng-src="{{getYoutubeUrl(Video.id)}}" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</selection>
