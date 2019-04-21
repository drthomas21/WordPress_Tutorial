(function($,angular){
    var app = angular.module("contactApp",["ngSanitize"])
    .controller("FormController",["$http","$timeout","$scope",function($http,timeout,$scope) {
        $scope.errors = [];
        $scope.messages = [];
        $scope.form = {
            "from":"",
            "subject":"",
            "body":"",
            "recaptcha":""
        };

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
    }]);
})(jQuery,angular);
