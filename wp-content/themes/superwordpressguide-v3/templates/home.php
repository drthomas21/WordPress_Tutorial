<section class="homepage blog-header">
    <div class="main-content row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 main-content-1" ng-show="hasConsented">
            <div class="youtube-vids">
                <h2 class="text-center section-label col-sm-12">Newest Videos</h2>
                <div class="embed-responsive embed-responsive-16by9" ng-repeat="Video in latestVideos | limitTo: 2">
                    <iframe class="embed-responsive-item" ng-src="{{getYoutubeUrl(Video.id)}}" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 posts main-content-2" ng-show="hasConsented">
            <h2 class="text-center section-label col-sm-12">Recent Posts</h2>
            <div class="post" ng-repeat="Post in latestPosts | limitTo:1">
                <div class="post-header row">
                    <h2 class="col-sm-12">
                        <a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}" ng-bind="Post.permalink.label"></a>
                    </h2>
                </div>
                <div class="post-info row">
                    <div class="col-sm-6 text-left">Categories: <span ng-bind-html="Post.category_html"></span></div>
                    <div class="col-sm-6 text-right"><small>Posted <span ng-bind-html="Post.human_time"></span> ago</small></div>
                </div>
                <div class="row">
                    <div class="post-excerpt text-center col-sm-12" ng-bind-html="Post.post_content"></div>
                </div>
                <div class="row">
                    <div class="read-more col-sm-12"><a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}">Read More &gt;</a></div>
                </div>
                <div class="row">
                    <div class="post-tags col-sm-12">Tags: <span ng-bind-html="Post.tag_html"></span></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 posts main-content-2" ng-show="!hasConsented">
            <h2 class="text-center section-label col-sm-12">Recent Posts</h2>
            <div class="post col-sm-12 col-md-4" ng-repeat="Post in latestPosts | limitTo:3">
                <div class="post-header row">
                    <h2 class="col-sm-12">
                        <a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}" ng-bind="Post.permalink.label"></a>
                    </h2>
                </div>
                <div class="post-info row">
                    <div class="col-sm-6 text-left">Categories: <span ng-bind-html="Post.category_html"></span></div>
                    <div class="col-sm-6 text-right"><small>Posted <span ng-bind-html="Post.human_time"></span> ago</small></div>
                </div>
                <div class="row">
                    <div class="post-excerpt text-center col-sm-12" ng-bind-html="Post.post_content"></div>
                </div>
                <div class="row">
                    <div class="read-more col-sm-12"><a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}">Read More &gt;</a></div>
                </div>
                <div class="row">
                    <div class="post-tags col-sm-12">Tags: <span ng-bind-html="Post.tag_html"></span></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 youtube-vids" ng-show="hasConsented">
            <h2 class="text-center section-label col-sm-12">Going Viral?</h2>
            <div class="embed-responsive embed-responsive-16by9" ng-repeat="Video in popularVideos | limitTo: 2">
                <iframe class="embed-responsive-item" ng-src="{{getYoutubeUrl(Video.id)}}" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="posts op-ed col-sm-12 row">
            <h2 class="text-center section-label col-sm-12">OP;ED</h2>
            <div class="col-lg-4 col-md-6" ng-repeat="Post in opEdPosts">
                <div class="col-sm-12">
                    <div class="post-header row">
                        <h2 class="col-sm-12">
                            <a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}" ng-bind="Post.permalink.label"></a>
                        </h2>
                    </div>
                    <div class="post-info row">
                        <div class="col-sm-6 text-left">Categories: <span ng-bind-html="Post.category_html"></span></div>
                        <div class="col-sm-6 text-right"><small>Posted <span ng-bind-html="Post.human_time"></span> ago</small></small></div>
                    </div>
                    <div class="row">
                        <div class="post-excerpt text-center col-sm-12" ng-bind-html="Post.post_content"></div>
                    </div>
                    <div class="row">
                        <div class="read-more col-sm-12"><a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}">Read More &gt;</a></div>
                    </div>
                    <div class="row">
                        <div class="post-tags col-sm-12">Tags: <span ng-bind-html="Post.tag_html"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="posts more-posts col-sm-12 row">
            <h2 class="text-center section-label col-sm-12">More Posts</h2>
            <div class="col-lg-4 col-md-6" ng-repeat="Post in latestPosts" ng-hide="$first">
                <div class="col-sm-12">
                    <div class="post-header row">
                        <h2 class="col-sm-12">
                            <a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}" ng-bind="Post.permalink.label"></a>
                        </h2>
                    </div>
                    <div class="post-info row">
                        <div class="col-sm-6 text-left">Categories: <span ng-bind-html="Post.category_html"></span></div>
                        <div class="col-sm-6 text-right"><small>Posted <span ng-bind-html="Post.human_time"></span> ago</small></div>
                    </div>
                    <div class="row">
                        <div class="post-excerpt text-center col-sm-12" ng-bind-html="Post.post_content"></div>
                    </div>
                    <div class="row">
                        <div class="read-more col-sm-12"><a ng-href="{{Post.permalink.href}}" ng-attr-alt="{{Post.permalink.alt}}">Read More &gt;</a></div>
                    </div>
                    <div class="row">
                        <div class="post-tags col-sm-12">Tags: <span ng-bind-html="Post.tag_html"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
