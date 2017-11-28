var app = angular.module("paypal",['ngSanitize','ngCookies']);
app.controller("ShoppingChartCtrl",['$scope','$http','$cookies','$timeout',function($scope,$http,$cookies,$timeout) {
	$scope.price = angularConfig && angularConfig.price ? angularConfig.price : 1;
	$scope.issues = [];
	$scope.items = [];
	$scope.user = {
			firstName: {
				label: "First Name",
				id: "firstName",
				value: "",
				type: "text",
				className: "field-name",
				sanitize: function() {
					$scope.user.firstName.value = $scope.user.firstName.value.replace(/[^A-Za-z]/g,"");
				},
				validation: function() {
					if($scope.user.firstName.value.length == 0 || $scope.user.firstName.value.replace(/[A-Za-z]/g,"").length > 0) {
						angular.element("#"+$scope.user.firstName.id).parent().addClass("has-error");
						return false;
					} 
					angular.element("#"+$scope.user.firstName.id).parent().removeClass("has-error");
					return true;
				}
			},
			lastName: {
				label: "Last Name",
				id: "lastName",
				value: "",
				type: "text",
				className: "field-name",
				sanitize: function() {
					$scope.user.lastName.value = $scope.user.lastName.value.replace(/[^A-Za-z\- ]/g,"");
				},
				validation: function() {
					if($scope.user.lastName.value.length == 0 || $scope.user.lastName.value.replace(/[A-Za-z\- ]/g,"").length > 0) {
						angular.element("#"+$scope.user.lastName.id).parent().addClass("has-error");
						return false;
					}
					angular.element("#"+$scope.user.lastName.id).parent().removeClass("has-error");
					return true;
				}
			},
			emailAddress: {
				label: "Email Address",
				id: "emailAddress",
				value: "",
				type: "email",
				className: "field-email",
				sanitize: function() {
					$scope.user.emailAddress.value = $scope.user.emailAddress.value.replace(/[ :,\"\(\)\\\[\]\:\;\<\>]/g,"");
				},
				validation: function() {
					var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				    if($scope.user.emailAddress.value.length == 0 || !re.test($scope.user.emailAddress.value)) {
				    	angular.element("#"+$scope.user.emailAddress.id).parent().addClass("has-error");
				    	return false;
					}
				    angular.element("#"+$scope.user.emailAddress.id).parent().removeClass("has-error");
				    return true;
				}
			},
			phoneNumber: {
				label: "Phone Number",
				id: "phoneNumber",
				value: "",
				type: "text",
				className: "field-phone",
				sanitize: function() {
					$scope.user.phoneNumber.value = $scope.user.phoneNumber.value.replace(/[^0-9\(\)\- ]/g,"");
				},
				validation: function() {
				    if($scope.user.phoneNumber.value.length == 0 || $scope.user.phoneNumber.value.replace(/[^0-9]/g,"").length != 10 || $scope.user.phoneNumber.value.replace(/[0-9\(\)\- ]/g,"").length > 0) {
				    	angular.element("#"+$scope.user.phoneNumber.id).parent().addClass("has-error");
				    	return false;
					} 
				    var phone = $scope.user.phoneNumber.value.replace(/[^0-9]/g,"");
				    $scope.user.phoneNumber.value = "("+phone.substr(0,3)+")"+" "+phone.substr(3,3)+"-"+phone.substr(6);
				    angular.element("#"+$scope.user.phoneNumber.id).parent().removeClass("has-error");
				    return true;
				}
			},
			state: {
				label: "State",
				id: "state",
				value: "",
				type: "text",
				className: "field-state",
				sanitize: function() {
					$scope.user.state.value = $scope.user.state.value.replace(/[^A-Za-z]/g,"").substr(0,2).toUpperCase();
				},
				validation: function() {
				    if($scope.user.state.value.length == 0 || $scope.user.state.value.length != 2 || $scope.user.state.value.replace(/[A-Za-z]/g,"").length > 0) {
				    	angular.element("#"+$scope.user.state.id).parent().addClass("has-error");
				    	return false;
					} 
				    angular.element("#"+$scope.user.state.id).parent().removeClass("has-error");
				    return true;
				}
			},
			city: {
				label: "City",
				id: "city",
				value: "",
				type: "text",
				className: "field-city",
				sanitize: function() {
					$scope.user.city.value = $scope.user.city.value.replace(/[^A-Za-z0-9\.\- ]/g,"");
				},
				validation: function() {
				    if($scope.user.city.value.length == 0 || $scope.user.city.value.replace(/[A-Za-z ]/g,"").length > 0) {
				    	angular.element("#"+$scope.user.city.id).parent().addClass("has-error");
				    	return false;
					} 
				    angular.element("#"+$scope.user.city.id).parent().removeClass("has-error");
				    return true;
				}
			},
			address: {
				label: "Address",
				id: "address",
				value: "",
				type: "text",
				className: "field-address",
				sanitize: function() {
					$scope.user.address.value = $scope.user.address.value.replace(/[^A-Za-z0-9 \#\.\,\;\:\']/g,"");
				},
				validation: function() {
				    if($scope.user.address.value.length == 0 || $scope.user.address.value.replace(/[A-Za-z0-9 \#\.\,\;\:\']/g,"").length > 0) {
				    	angular.element("#"+$scope.user.address.id).parent().addClass("has-error");
				    	return false;
					} 
				    angular.element("#"+$scope.user.address.id).parent().removeClass("has-error");
				    return true;
				}
			},
			zipCode: {
				label: "Zip",
				id: "zip",
				value: "",
				type: "text",
				className: "field-zip",
				sanitize: function() {
					$scope.user.zipCode.value = $scope.user.zipCode.value.replace(/[^0-9]/g,"").substr(0,5);
				},
				validation: function() {
				    if($scope.user.zipCode.value.length == 0 || $scope.user.zipCode.value.length != 5 || $scope.user.zipCode.value.replace(/[0-9]/g,"").length > 0) {
				    	angular.element("#"+$scope.user.zipCode.id).parent().addClass("has-error");
				    	return false;
					} 
				    angular.element("#"+$scope.user.zipCode.id).parent().removeClass("has-error");
				    return true;
				}
			}
	}
	
	$scope.showShoppingChart = function() {
		angular.element("#shoppingChartModal").modal({backdrop:false});
	};
	
	$scope.removeItem = function($index) {
		$scope.items.splice($index,1);
		saveData();
	};
	
	$scope.checkout = function() {
		for(prop in $scope.user) {
			if(!$scope.user[prop].validation()) {
				return false;
			}
		}
		
		if($scope.items.length > 0)
			angular.element("div.paypal-shopping-chart form").submit();
	};
	
	$scope.getUserProps = function() {
		var props = [];
		for(attr in $scope.user) {
			props.push($scope.user[attr]);
		}
		
		return props;
	}
	
	var init = function() {
		if(window.location.search.length > 0 && window.location.search.indexOf("paymentId") >= 0) {
			$timeout(function() {
				window.open("/downloads"+window.location.search,'_blank');
			}, 1000);
		}
		if(!navigator.cookieEnabled) {
			$scope.issues.push("Your browser does not support cookies, you need cookies enabled if you want to buy something");
		}
		
		var items = $cookies.getObject("paypal_items");
		if(items) {
			$scope.items = items;
		}
		
		angular.element(document).ready(function() {
			angular.element("a.shopping-item").click(function() {
				var itemDetails = angular.element(this).data();
				var add=true;
				for(var i = 0; i < $scope.items.length; i++) {
					if($scope.items[i].itemId == itemDetails.itemId) {
						add = false;
						break;
					}
				}
				
				if(add) {
					$scope.items.push(itemDetails);
					if(!$scope.$$phase) {
						$scope.$apply();
					}
					saveData();
				} else {
					showWarning("Item is already in shopping chart");
				}
			});
		});
	};
	
	var getMessageElement = function(message) {
		return '<div class="alert alert-success text-center" role="alert" style="display:none">'+message+'</div>';
	};
	
	var getWarningElement = function(message) {
		return '<div class="alert alert-warning text-center" role="alert" style="display:none">'+message+'</div>';
	}
	
	var saveData = function() {
		var date = new Date();
		date.setDate(date.getDate() +1);
		$cookies.putObject("paypal_items",$scope.items,{path:"/",expires:date});
		showMessage("Item has been added to chart");
	};
	
	var remoteData = function() {
		$cookies.remove("paypal_items",{path:"/"});
	};
	
	var showMessage = function(message) {
		var element = angular.element(getMessageElement(message));
		angular.element("div.paypal-shopping-chart div.success.messages").append(element);
		element.slideDown();
		$timeout(function(){
			element.slideUp();
		},3000);		
	};
	
	var showWarning = function(message) {
		var element = angular.element(getWarningElement(message));
		angular.element("div.paypal-shopping-chart div.warning.messages").append(element);
		element.slideDown();
		$timeout(function(){
			element.slideUp();
		},3000);
	};
	
	
	init();
}]);