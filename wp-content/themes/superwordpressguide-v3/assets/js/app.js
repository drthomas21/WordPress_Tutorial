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
    .when("/category/:slug/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=category",
        controller: "CategoryCtrl"
    })
    .when("/tag/:slug/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=tag",
        controller: "TagCtrl"
    })
    .when("/article/:post_name/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=single",
        controller: "ArticleCtrl"
    })
    .when("/contact/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=page-contact",
        controller: "FormCtrl"
    })

    .when("/:post_name/",{
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=page",
        controller: "PostCtrl"
    })
    .otherwise({
        templateUrl: "/wp-admin/admin-ajax.php?action=ngTemplate&name=home",
        controller: "HomepageCtrl"
    });

    $locationProvider.html5Mode(true);
}])
.controller("PageCtrl",["$scope","$rootScope","$http","$timeout","$interval","$location","$route","$sce",function($scope,$rootScope,$http,$timeout,$interval,$route,$sce) {
    $scope.cached = {
        Posts: [],
        Terms: []
    };
    $scope.latestVideos = [];
    $scope.popularVideos = [];
    $scope.latestPosts = [];
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

    $scope.getPostsForTag = function(id, offset, limit, callback) {
        $http.get("/wp-json/data/v1/posts?offset="+offset+"&limit="+limit+"&tag="+id)
        .then(function(response){
            callback(response.data);
        });
    }

    $scope.getFullArticle = function(post_name,callback) {
        for(i in $scope.cached.Posts) {
            if($scope.cached.Posts[i].ID == post_name || $scope.cached.Posts[i].post_name == post_name) {
                callback($scope.cached.Posts[i]);
                return;
            }
        }

        $http.get("/wp-json/data/v1/post/"+post_name+ "?post_type=post")
        .then(function(response){
            if(response.data) {
                $scope.cached.Posts.push(response.data);
            }
            callback(response.data);
        });
    }

    $scope.getFullPage = function(post_name,callback) {
        for(i in $scope.cached.Posts) {
            if($scope.cached.Posts[i].ID == post_name || $scope.cached.Posts[i].post_name == post_name) {
                callback($scope.cached.Posts[i]);
                return;
            }
        }

        $http.get("/wp-json/data/v1/post/"+post_name + "?post_type=page")
        .then(function(response){
            if(response.data) {
                $scope.cached.Posts.push(response.data);
            }

            callback(response.data);
        });
    }

    $scope.getCategory = function(term,callback) {
        for(i in $scope.cached.Terms) {
            if($scope.cached.Terms[i].term_id == term || $scope.cached.Terms[i].slug == term) {
                callback($scope.cached.Terms[i]);
                return;
            }
        }

        $http.get("/wp-json/data/v1/term/"+term+ "?taxonomy=category")
        .then(function(response){
            if(response.data) {
                $scope.cached.Terms.push(response.data);
            }
            callback(response.data);
        });
    }

    $scope.getTag = function(term,callback) {
        for(i in $scope.cached.Terms) {
            if($scope.cached.Terms[i].term_id == term || $scope.cached.Terms[i].slug == term) {
                callback($scope.cached.Terms[i]);
                return;
            }
        }

        $http.get("/wp-json/data/v1/term/"+term+ "?taxonomy=post_tag")
        .then(function(response){
            if(response.data) {
                $scope.cached.Terms.push(response.data);
            }
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
            var params = [];

            for(param in search) {
                params.push(param+"="+search[param]);
            }
            $route.url("/search/?"+params.join("&"));
            return false;
        }
    });

    $scope.$on("$routeChangeSuccess",function(e,current,previous){
        if(typeof ga != "undefined") {
            ga("send","pageview",$route.url());
        }

        angular.element("html,body").animate({scrollTop: '0px'}, "slow");
    })

    var init = function() {
        $scope.getVideos(0,10,function(videos) {
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

        $scope.getPopularVideos(0,10,function(videos) {
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
    }

    init();
}])
.controller("HomepageCtrl",["$scope","$rootScope","$sce",function($scope,$rootScope,$sce){
    $scope.opEdPosts = [];

    function init() {
        $scope.$parent.setPageTitle("Super WordPress Guide Homepage");

        $scope.$parent.getPostsForCategory(412,0,4,function(posts) {
            if(posts) {
                if(!$scope.$$phase) {
                    $scope.$apply(function(){
                        $scope.opEdPosts = posts;
                        for(var i in $scope.opEdPosts) {
                            $scope.opEdPosts[i].post_content = $sce.trustAsHtml($scope.opEdPosts[i].post_content);
                        }
                    });
                } else {
                    $scope.opEdPosts = posts;
                    for(var i in $scope.opEdPosts) {
                        $scope.opEdPosts[i].post_content = $sce.trustAsHtml($scope.opEdPosts[i].post_content);
                    }
                }
            }
        });

        if($scope.latestPosts.length == 0) {
            $scope.getPosts(0,11,function(posts) {
                if(posts) {
                    if(!$scope.$$phase) {
                        $scope.$apply(function(){
                            $scope.latestPosts = posts;
                            for(var i in $scope.latestPosts) {
                                $scope.latestPosts[i].post_content = $sce.trustAsHtml($scope.latestPosts[i].post_content);
                            }
                        });
                    } else {
                        $scope.latestPosts = posts;
                        for(var i in $scope.latestPosts) {
                            $scope.latestPosts[i].post_content = $sce.trustAsHtml($scope.latestPosts[i].post_content);
                        }
                    }
                }
            });
        }
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("ArticleCtrl",["$scope","$rootScope","$routeParams","$location","$sce",function($scope,$rootScope,$routeParams,$location,$sce){
    $scope.Post = {};

    function init() {
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
                            $scope.Post.post_content = $sce.trustAsHtml($scope.Post.post_content);
                        });
                    } else {
                        $scope.Post = Post;
                        $scope.Post.post_content = $sce.trustAsHtml($scope.Post.post_content);
                        $scope.$parent.setPageTitle(Post.post_title);
                    }
                } else {
                    $location.url("/?s="+$routeParams.post_name);
                }
            });
        }
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("PostCtrl",["$scope","$rootScope","$routeParams","$location","$sce",function($scope,$rootScope,$routeParams,$location,$sce){
    $scope.Post = {};

    function init() {
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

    $scope.Post = {};

    function init() {
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
.controller("SearchCtrl",["$scope","$rootScope","$routeParams","$location","$sce",function($scope,$rootScope,$routeParams,$location,$sce){
    $scope.Post = {};

    function init() {
        var search = $location.search();

        $scope.$parent.setPageTitle("Search for: " + search.s.replace(/-/g," ").replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}));

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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("CategoryCtrl",["$scope","$rootScope","$routeParams","$location","$sce",function($scope,$rootScope,$routeParams,$location,$sce){
    $scope.Posts = {};
    $scope.Term = {};

    function init() {
        var search = $location.search();

        $scope.$parent.setPageTitle("Category: " + $routeParams.slug);

        $scope.$parent.getPostsForCategory($routeParams.slug,0,10,function(Posts) {
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

        $scope.$parent.getCategory($routeParams.slug,function(Term){
            if(Term) {
                $scope.Term = Term;
                $scope.$parent.setPageTitle("Category: " + Term.name.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}));
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
.controller("TagCtrl",["$scope","$rootScope","$routeParams","$location","$sce",function($scope,$rootScope,$routeParams,$location,$sce){
    $scope.Posts = {};
    $scope.Term = {};

    function init() {
        var search = $location.search();

        $scope.$parent.setPageTitle("Tag: " + $routeParams.slug);

        $scope.$parent.getPostsForTag($routeParams.slug,0,10,function(Posts) {
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

        $scope.$parent.getTag($routeParams.slug,function(Term){
            if(Term) {
                $scope.Term = Term;
                $scope.$parent.setPageTitle("Category: " + Term.name.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}));
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
    }

    $scope.getYoutubeUrl = function(id) {
        return $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+id);
    }

    init();
}])
