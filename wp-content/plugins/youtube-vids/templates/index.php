<div class='wrapper' ng-app="app" ng-cloak>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.10/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.10/angular-sanitize.min.js"></script>
    <script>
        (function(jQuery,angular){
            angular.module("app",["ngSanitize"])
            .controller("videoFilterCtrl",["$scope","$http",function($scope,$http) {
                $scope.recentVideos = [];
                $scope.popularVideos = [];
                var filteredIds = {
                    recent: [],
                    popular: []
                };
                var limit = 10;
                var offset = 0;

                function init() {
                    getRecentVideos(offset,limit);
                    getPopularVideos(offset,limit);
                    getFilteredIds();
                }

                function getRecentVideos(offset,limit) {
                    $http.get("<?= admin_url("admin-ajax.php"); ?>?action=<?php echo self::WP_AJAX; ?>&type=recent&offset="+offset+"&limit="+limit)
                    .then(function(response){
                        if(response && response.data && response.data.videos) {
                            for(var idx in response.data.videos) {
                                $scope.recentVideos.push(response.data.videos[idx]);
                            }

                            var ids = [];
                            var i = 0;
                            while(i < $scope.recentVideos.length) {
                                var id = $scope.recentVideos[i].id;
                                if(ids.indexOf(id) >= 0) {
                                    $scope.recentVideos.splice(i,1);
                                    continue;
                                } else {
                                    ids.push(id);
                                }

                                i++;
                            }
                        }

                        var width = 350 * ($scope.recentVideos.length + 1);
                        angular.element(".video-list.recent-videos").css({
                            "width":width
                        });
                    });
                }

                function getPopularVideos(offset,limit) {
                    $http.get("<?= admin_url("admin-ajax.php"); ?>?action=<?php echo self::WP_AJAX; ?>&type=popular&offset="+offset+"&limit="+limit)
                    .then(function(response){
                        if(response && response.data && response.data.videos) {
                            for(var idx in response.data.videos) {
                                $scope.popularVideos.push(response.data.videos[idx]);
                            }

                            var ids = [];
                            var i = 0;
                            while(i < $scope.popularVideos.length) {
                                var id = $scope.popularVideos[i].id;
                                if(ids.indexOf(id) >= 0) {
                                    $scope.popularVideos.splice(i,1);
                                    continue;
                                } else {
                                    ids.push(id);
                                }

                                i++;
                            }
                        }

                        var width = 350 * ($scope.popularVideos.length + 1);
                        angular.element(".video-list.popular-videos").css({
                            "width":width
                        });
                    });
                }

                function getFilteredIds() {
                    $http.get("<?= admin_url("admin-ajax.php"); ?>?action=<?php echo self::WP_AJAX_FILTER; ?>")
                    .then(function(response){
                        if(response && response.data && response.data.filters) {
                            filteredIds = response.data.filters;
                        }
                    });
                }

                $scope.loadMoreRecent = function() {
                    var offset = $scope.recentVideos.length;
                    getRecentVideos(offset,limit);
                }

                $scope.loadMorePopular = function() {
                    var offset = $scope.popularVideos.length;
                    getPopularVideos(offset,limit);
                }

                $scope.isFiltered = function(id,key) {
                    return filteredIds[key].indexOf(id) >= 0;
                }

                $scope.toggleRecentFilter = function(id) {
                    $http.post("<?= admin_url("admin-ajax.php"); ?>?action=<?php echo self::WP_AJAX_FILTER; ?>",{
                        id:id,
                        type:"recent"
                    })
                    .then(function(response){
                        if(response && response.data && response.data.filters) {
                            filteredIds = response.data.filters;
                        }
                    });
                }

                $scope.togglePopularFilter = function(id) {
                    $http.post("<?= admin_url("admin-ajax.php"); ?>?action=<?php echo self::WP_AJAX_FILTER; ?>",{
                        id:id,
                        type:"popular"
                    })
                    .then(function(response){
                        if(response && response.data && response.data.filters) {
                            filteredIds = response.data.filters;
                        }
                    });
                }


                init();
            }]);
        })(jQuery,angular);
    </script>
    <style type="text/css">
        .video-container {
            width: 100%;
            height: 300px;
            overflow-x: scroll;
            overflow-y: hidden;
        }

        .video-container .video-list .item {
            display: inline-block;
            max-width: 320px;
            margin-right: 30px;
            vertical-align: top;
        }

        .video-container .video-list .item.filtered {
            opacity:  0.2;
        }

        .video-container .video-list .item.view-more {
            width: 300px;
            height: 180px;
            background-color: #333;
            cursor: pointer;
        }

        .video-container .video-list .item.view-more h4 {
            margin-top: calc(20% - 10px);
            color: #fff;
            padding-left: 30px;
            font-size: 18px;
        }
    </style>
    <h2><?php echo self::PAGE_TITLE; ?></h2>
    <p>Link your YouTube account with this App to display your vidoes onto your website.</p>

    <section class="wrap">
        <p>Is Access Token Valid?
            <?php if($isValid): ?>
                <span style='color: rgb(0,250,0); font-weight: bold; font-size: 20px;'>Yes</span>
            <?php else: ?>
                <span style='color: rgb(250,0,0); font-weight: bold; font-size: 20px;'>No</span>
                <br /> <a href="<?php echo $authUrl; ?>"><input type="submit" name="submit" id="submit" class="button button-primary" value="Authenticate App"></a>
            <?php endif; ?>
        </p>
    </section>

    <section class="wrap" ng-controller="videoFilterCtrl">
        <p>Select which videos that you would like to exclude from their persepctive list</p>
        <h3>Recent Videos Filter</h3>
        <div class="video-container">
            <div class="video-list recent-videos">
                <div ng-repeat="video in recentVideos" class="item" ng-click="toggleRecentFilter(video.id)" ng-class="{'filtered':isFiltered(video.id,'recent')}">
                    <div class="header">
                        <img src="{{video.thumbnails.medium.url}}" />
                    </div>
                    <h4 ng-bind="video.title"></h4>
                </div>
                <div class="item view-more" ng-click="loadMoreRecent()">
                    <h4>View More &gt;</h4>
                </div>
            </div>
        </div>

        <h3>Popular Videos Filter</h3>
        <div class="video-container">
            <div class="video-list popular-videos">
                <div ng-repeat="video in popularVideos" class="item" ng-click="togglePopularFilter(video.id)" ng-class="{'filtered':isFiltered(video.id,'popular')}">
                    <div class="header">
                        <img src="{{video.thumbnails.medium.url}}" />
                    </div>
                    <h4 ng-bind="video.title"></h4>
                </div>
                <div class="item view-more" ng-click="loadMorePopular()">
                    <h4>View More &gt;</h4>
                </div>
            </div>
        </div>
    </section>
</div>
