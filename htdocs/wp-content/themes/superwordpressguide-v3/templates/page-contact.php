<section class="page blog-header">
    <div class="page-container row">
        <div class="col-sm-12 col-md-8 posts">
            <section class="post">
                <h1 ng-bind="Post.post_title"></h1>
                <!--div class="sharethis-inline-share-buttons"></div-->
                <div class="post_content">
                    <form action="javascript:void(0)" class="form">
                        <div class="alert alert-success" role="alert" ng-repeat="message in messages">{{message}}</div>
                        <div class="alert alert-danger" role="alert" ng-repeat="error in errors">{{error}}</div>

                        <div class="form-group">
                            <label for="form-email">Email address </label>
                            <input type="from" class="form-control" id="form-email" ng-model="form.from" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="form-subject">Subject</label>
                            <input type="text" class="form-control" id="from-subject" ng-model="form.subject" placeholder="Enter Subject">
                        </div>
                        <div class="form-group">
                            <label for="form-subject">Message</label>
                            <textarea name="body" class="form-control" ng-model="form.body"></textarea>
                        </div>

                        <button class="btn btn-primary" ng-click="submitForm()">Submit</button>
                    </form>
                </div>
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
