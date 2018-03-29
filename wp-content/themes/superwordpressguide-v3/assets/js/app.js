app.config(["$routeProvider","$locationProvider",function($routeProvider,$locationProvider){
    $routeProvider
    .when("/preview/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=single",
        controller: "ArticleCtrl"
    })
    .when("/search/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=search",
        controller: "SearchCtrl"
    })
    .when("/category/:slug",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=category",
        controller: "CategoryCtrl"
    })
    .when("/tag/:slug",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=tag",
        controller: "TagCtrl"
    })
    .when("/article/:post_name",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=single",
        controller: "ArticleCtrl"
    })
    .when("/contact/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=page-contact",
        controller: "FormCtrl"
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
    $scope.pageTitle = "AngularJS App";

    $scope.getPosts = function(offset, limit, callback) {
        $http.get("/wp-json/data/v1/posts?offset="+offset+"&limit="+limit)
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.searchPosts = function(search, offset, limit, callback) {
        $http.get("/wp-json/data/v1/posts?offset="+offset+"&limit="+limit+"&search="+search)
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

    $scope.getFullArticle = function(post_name,callback) {
        $http.get("/wp-json/data/v1/post/"+post_name+ "?post_type=post")
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getFullPage = function(post_name,callback) {
        $http.get("/wp-json/data/v1/post/"+post_name + "?post_type=page")
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

    $scope.setPageTitle = function(title) {
        $scope.pageTitle = title;
    }

    $scope.$on("$routeChangeStart",function(e,next,current) {
        var search = $route.search();
        if(search.preview == "true" && !$route.absUrl().match(/\/preview\/\?/)) {
            var params = [];

            for(param in search) {
                var _param = param;
                if(_param == "p") {
                    _param = "preview_id";
                }
                params.push(_param+"="+search[param]);
            }
            $route.url("/preview/?"+params.join("&"));
            return false;
        }
        else if(search.s && !$route.absUrl().match(/\/search\/\?/)) {
            console.log("here");
            var params = [];

            for(param in search) {
                params.push(param+"="+search[param]);
            }
            $route.url("/search/?"+params.join("&"));
            return false;
        }
    });
}])
.controller("HomepageCtrl",["$scope","$rootScope","$sce",function($scope,$rootScope,$sce){
    $scope.latestVideos = [];
    $scope.popularVideos = [];
    $scope.latestPosts = [];
    $scope.opEdPosts = [];

    function init() {
        $scope.$parent.setPageTitle("Super WordPress Guide Homepage");
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

        $scope.$parent.getPosts(0,11,function(posts) {
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
        $scope.$parent.getPostsForCategory(412,0,4,function(posts) {
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
.controller("ArticleCtrl",["$scope","$rootScope","$sce","$routeParams","$location",function($scope,$rootScope,$sce,$routeParams,$location){
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

        var search = $location.search();
        if(search.p || search.preview_id) {
            var preview_id = search.p || search.preview_id;
            $scope.$parent.getFullArticle(preview_id,function(Post) {
                if(Post) {
                    if(!$scope.$$phase) {
                        $scope.$apply(function(){
                            $scope.$parent.setPageTitle("Preview: " + Post.post_title);
                            $scope.Post = Post;
                        });
                    } else {
                        $scope.Post = Post;
                        $scope.$parent.setPageTitle(Post.post_title);
                    }
                } else {
                    $location.url("/");
                }
            });
        } else {
            $scope.$parent.getFullArticle($routeParams.post_name,function(Post) {
                if(Post) {
                    if(!$scope.$$phase) {
                        $scope.$apply(function(){
                            $scope.$parent.setPageTitle(Post.post_title);
                            $scope.Post = Post;
                        });
                    } else {
                        $scope.Post = Post;
                        $scope.$parent.setPageTitle(Post.post_title);
                    }
                } else {
                    $location.url("/?s="+$routeParams.post_name);
                }
            });
        }
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("PostCtrl",["$scope","$rootScope","$sce","$routeParams","$location",function($scope,$rootScope,$sce,$routeParams,$location){
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

        $scope.$parent.getFullPage($routeParams.post_name,function(Post) {
            if(Post) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.$parent.setPageTitle(Post.post_title);
                        $scope.Post = Post;
                    });
                } else {
                    $scope.Post = Post;
                    $scope.$parent.setPageTitle(Post.post_title);
                }
            } else {
                $location.url("/?s="+$routeParams.post_name);
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("FormCtrl",["$http","$timeout","$scope","$rootScope","$sce",function($http,timeout,$scope,$rootScope,$sce) {
    $scope.errors = [];
    $scope.messages = [];
    $scope.form = {
        "from":"",
        "subject":"",
        "body":"",
        "recaptcha":""
    };

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

        $scope.$parent.getFullPage("contact",function(Post) {
            if(Post) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.$parent.setPageTitle(Post.post_title);
                        $scope.Post = Post;
                    });
                } else {
                    $scope.Post = Post;
                    $scope.$parent.setPageTitle(Post.post_title);
                }
            } else {
                $location.url("/?s="+$routeParams.post_name);
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    $scope.submitForm = function() {
        $scope.form.recaptcha = angular.element("#g-recaptcha-response").val();
        $http.post("/wp-admin/admin-ajax.php?action=contact_send_mail",$scope.form)
        .then(function(resp) {
            $scope.errors = [];
            $scope.messages = [];
            if(!resp.data.success) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.errors = resp.data.data;
                    });
                } else {
                    $scope.errors = resp.data.data;
                }
            } else {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.messages = resp.data.messages;
                    });
                } else {
                    $scope.messages = resp.data.messages;
                }
            }
        });
    }

    init();
}])
.controller("SearchCtrl",["$scope","$rootScope","$sce","$routeParams","$location",function($scope,$rootScope,$sce,$routeParams,$location){
    $scope.popularVideos = [];
    $scope.latestPosts = [];
    $scope.Post = {};

    function init() {
        var search = $location.search();

        $scope.$parent.setPageTitle("Search for: " + search.s.replace(/-/g," ").replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}));
        $scope.$parent.getPopularVideos(0,4,function(videos) {
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

        $scope.$parent.searchPosts(search.s,0,10,function(Posts) {
            if(Posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.Posts = Posts;
                    });
                } else {
                    $scope.Posts = Posts;
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("CategoryCtrl",["$scope","$rootScope","$sce","$routeParams","$location",function($scope,$rootScope,$sce,$routeParams,$location){
    $scope.popularVideos = [];
    $scope.latestPosts = [];
    $scope.Post = {};

    function init() {
        var search = $location.search();
        console.log($routeParams);

        $scope.$parent.setPageTitle("Category: " + $routeParams.slug);
        $scope.$parent.getPopularVideos(0,4,function(videos) {
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

/*
        $scope.$parent.searchPosts(search.s,0,10,function(Posts) {
            if(Posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.Posts = Posts;
                    });
                } else {
                    $scope.Posts = Posts;
                }
            }
        });
*/
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("TagCtrl",["$scope","$rootScope","$sce","$routeParams","$location",function($scope,$rootScope,$sce,$routeParams,$location){
    $scope.popularVideos = [];
    $scope.latestPosts = [];
    $scope.Post = {};

    function init() {
        var search = $location.search();
        console.log($routeParams);

        $scope.$parent.setPageTitle("Tag: " + $routeParams.slug);
        $scope.$parent.getPopularVideos(0,4,function(videos) {
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

/*
        $scope.$parent.searchPosts(search.s,0,10,function(Posts) {
            if(Posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.Posts = Posts;
                    });
                } else {
                    $scope.Posts = Posts;
                }
            }
        });
*/
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
