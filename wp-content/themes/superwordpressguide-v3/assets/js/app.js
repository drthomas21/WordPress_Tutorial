app.config(["$routeProvider","$locationProvider",function($routeProvider,$locationProvider){
    $routeProvider
    .when("/?p=:id",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=single",
        controller: "PostCtrl"
    })
    .when("/category/:slug",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=category",
        controller: "TermCtrl"
    })
    .when("/tag/:slug",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=tag",
        controller: "TermCtrl"
    })
    .when("/article/:post_name/:image_name",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=image",
        controller: "ImageCtrl"
    })
    .when("/article/:post_name",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=single",
        controller: "PostCtrl"
    })
    .when("/:post_name",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=page",
        controller: "PostCtrl"
    })
    .otherwise({
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=home",
        controller: "HomepageCtrl"
    });

    $locationProvider.html5Mode(true);
}])
.controller("PageCtrl",["$scope","$rootScope","$http","$timeout","$interval","$location","$route",function($scope,$rootScope,$http,$timeout,$interval,$route) {
    $scope.getPosts = function(offset, limit, callback) {
        $http.get("/wp-json/data/v1/posts?offset="+offset+"&limit="+limit)
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getVideos = function(offset, limit, callback) {
        $http.get("/wp-json/data/v1/videos?offset="+offset+"&limit="+limit)
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getPopularVideos = function(offset, limit, callback) {
        $http.get("/wp-json/data/v1/videos?offset="+offset+"&limit="+limit+"&orderby=popular")
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getPostsForCategory = function(id, offset, limit, callback) {
        $http.get("/wp-json/data/v1/posts?offset="+offset+"&limit="+limit+"&category="+id)
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getFullArticle = function(id,callback) {
        $http.get("/wp-json/data/v1/post/"+id)
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getSummaryArticle = function(id,callback) {
        $http.get("/wp-json/data/v1/post/"+id+"?type=excerpt")
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.$on("$routeChangeStart",function(e,next,current) {
        console.log('toggled',next,current);
        e.preventDefault();
        //$location.path("/preview/");
    });
}])
.controller("HomepageCtrl",["$scope","$rootScope","$sce",function($scope,$rootScope,$sce){
    $scope.latestVideos = [];
    $scope.popularVideos = [];
    $scope.latestPosts = [];
    $scope.opEdPosts = [];

    function init() {
        $scope.$parent.getVideos(0,2,function(videos) {
            if(videos) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.latestVideos = videos;
                    });
                } else {
                    $scope.latestVideos = videos;
                }
            }
        });
        $scope.$parent.getPopularVideos(0,2,function(videos) {
            if(videos) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.popularVideos = videos;
                    });
                } else {
                    $scope.popularVideos = videos;
                }
            }
        });

        $scope.$parent.getPosts(0,10,function(posts) {
            if(posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.latestPosts = posts;
                    });
                } else {
                    $scope.latestPosts = posts;
                }
            }
        });
        $scope.$parent.getPostsForCategory(412,0,3,function(posts) {
            if(posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.opEdPosts = posts;
                    });
                } else {
                    $scope.opEdPosts = posts;
                }
            }
        });

    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("PostCtrl",["$scope","$rootScope","$sce","$routeParams",function($scope,$rootScope,$sce,$routeParams){
    $scope.popularVideos = [];
    $scope.latestPosts = [];
    $scope.Post = {};

    function init() {
        $scope.$parent.getPopularVideos(0,3,function(videos) {
            if(videos) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.popularVideos = videos;
                    });
                } else {
                    $scope.popularVideos = videos;
                }
            }
        });

        $scope.$parent.getPosts(0,3,function(posts) {
            if(posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.latestPosts = posts;
                    });
                } else {
                    $scope.latestPosts = posts;
                }
            }
        });
        $scope.$parent.getFullArticle($routeParams.post_name,function(Post) {
            if(Post) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.Post = Post;
                    });
                } else {
                    $scope.Post = Post;
                }
            }
        });

    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}]);
