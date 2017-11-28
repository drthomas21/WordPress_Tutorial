var app = angular.module("paypal",['ngSanitize']);
app.controller("SearchCtrl",['$scope','$http',function($scope,$http) {
	$scope.search = "";
	$scope.rows = [];
	$scope.doSearch= function() {
		$http({
			url:"/wp-admin/admin-ajax.php?action=paypal&search="+$scope.search
		})
		.success(function(data){
			if(data.success) {
				$scope.rows = data.data;
			}
		});
	};
}]);