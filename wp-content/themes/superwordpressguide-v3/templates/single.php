<section class="single blog-header">
    <div class="page-container row">
        <div class="col-sm-12 col-md-8 posts">
            <section class="post">
                <h1 ng-bind="Post.post_title"></h1>
                <small>Posted on <span ng-bind="Post.human_time"></span></small>
                <p>Categories: <span ng-bind-html="Post.category_html"></span></p>
                <!--div class="sharethis-inline-share-buttons"></div-->
                <div class="post_content" ng-bind-html="Post.post_content"></div>
                <p>Tags: <span ng-bind-html="Post.tag_html"></span></p>
            </section>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="youtube-vids">
                <h2 class="text-center section-label">Going Viral?</h2>
                <div class="embed-responsive embed-responsive-16by9" ng-repeat="Video in popularVideos">
                    <iframe class="embed-responsive-item" ng-src="{{getYoutubeUrl(Video.id)}}" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</selection>
