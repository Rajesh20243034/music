var musicapp = angular.module("musicapp",['ngRoute','ngAnimate']);

//Angular Routing

musicapp.config(['$routeProvider',function($routeProvider) {
	$routeProvider.when("/",{
		templateUrl:"templates/home.html",
		controller:"homeCtrl"
	}).
	when("/signup",{
		templateUrl:"templates/signup.html",
		controller:"signupCtrl"
	}).
	when("/signin",{
		templateUrl:"templates/signin.html",
		controller:"signinCtrl"
	});
}]);

//Angular Services

musicapp.factory('dataFactory', ['$http', function($http){
	var baseURL = "/music/api";
	var datafactory = {};

	datafactory.getSongs = function(){
		return $http.get(baseURL+"/getsongs");
	};

	datafactory.getfSongs = function(){
		return $http.get(baseURL+"/getfSongs");
	};
	datafactory.getSession = function(){
		return $http.get(baseURL+"/getsession");
	};
	datafactory.logout = function(){
		return $http.put(baseURL+"/logout");
	}
	return datafactory;
}]);


musicapp.service('dataService', ['$http', function($http){
		var baseURL = "/music/api";
		this.getRecentSongs = function(){
			return $http.get(baseURL+"/recentSongs");
		};
		this.signupf = function(username,useremail,userpassword,userdob,usermo){
			return $http.post(baseURL+"/signup",{
				username:username,
				useremail:useremail,
				userpassword:userpassword,
				userdob:userdob,
				usermo:usermo
			});
		};
		this.signinf = function(useremail,userpassword){
			return $http.post(baseURL+"/signin",{
				useremail:useremail,
				userpassword:userpassword
			});
		}

		this.getcategories = function(){
			return $http.get(baseUrl+"/category");
		}
}]);


//Angular Js directive

musicapp.directive("myinput",function(){
	return {
		restrict:"E",
		templateUrl:"directives/input.html"
	}
});

musicapp.directive("centerdiv",function(){
	return {
		restrict:"E",
		templateUrl:"directives/center.html"
	}
});


musicapp.directive("leftdiv",function(){
	return {
		restrict:"E",
		templateUrl:"directives/left.html"
	}
});

musicapp.directive("rightdiv",function(){
	return {
		restrict:"E",
		templateUrl:"directives/right.html"
	}
});
//Angular Js Controllers
musicapp.controller('headerCtrl', ['$scope','$log','dataFactory', function($scope,$log,dataFactory){
		$scope.session = false;
		dataFactory.getSession().success(function(data){
			$scope.signin = false;
			if(data.useremail){
				$scope.signin = true;
				$scope.name = data.username;
			}
			
			$log.info(data);
		}).error(function(data){
			$log.warn(data);
		});

		$scope.logout = function(){

		}
}]);

musicapp.controller('homeCtrl',['$scope','$log',function($scope,$log){
	$scope.slides = [
	{
		image:'images/slider_images/0.jpg',
		description:'Image 00'
	},
	{
		image:'images/slider_images/1.jpg',
		description:'Image 01'
	},
	{
		image:'images/slider_images/12.jpg',
		description:'Image 02'

	},
	{
		image:'images/slider_images/13051.jpg',
		description:'Image 03'
	},
	{
		image:'images/slider_images/15.jpg',
		description:'Image 04'
	},
	{
		image:'images/slider_images/17.jpg',
		description:'Image 05'
	}
	];

	$scope.currentIndex = 0;

        $scope.setCurrentSlideIndex = function (index) {
            $scope.currentIndex = index;
        };

        $scope.isCurrentSlideIndex = function (index) {
            return $scope.currentIndex === index;
        };

        $scope.prevSlide = function () {
            $scope.currentIndex = ($scope.currentIndex < $scope.slides.length - 1) ? ++$scope.currentIndex : 0;
        };

        $scope.nextSlide = function () {
            $scope.currentIndex = ($scope.currentIndex > 0) ? --$scope.currentIndex : $scope.slides.length - 1;
        };

}]).animation('.slide-animation', function () {
        return {
            beforeAddClass: function (element, className, done) {
                var scope = element.scope();

                if (className == 'ng-hide') {
                    var finishPoint = element.parent().width();
                    if(scope.direction !== 'right') {
                        finishPoint = -finishPoint;
                    }
                    TweenMax.to(element, 0.5, {left: finishPoint, onComplete: done });
                }
                else {
                    done();
                }
            },
            removeClass: function (element, className, done) {
                var scope = element.scope();

                if (className == 'ng-hide') {
                    element.removeClass('ng-hide');

                    var startPoint = element.parent().width();
                    if(scope.direction === 'right') {
                        startPoint = -startPoint;
                    }

                    TweenMax.fromTo(element, 0.5, { left: startPoint }, {left: 0, onComplete: done });
                }
                else {
                    done();
                }
            }
        };
    });

musicapp.controller('productCtrl', ['$scope','dataFactory','$log', function($scope,dataFactory,$log){
	dataFactory.getSongs().success(function(data){
		$scope.songs = data;
	});

	dataFactory.getfSongs().success(function(data){
		$scope.fsong = data;
	});
	$scope.addtocart = function(){
		alert("add to cart");
	};

}]);

musicapp.controller('recentController',['$scope','dataService','$log',function($scope,dataService,$log){
	dataService.getRecentSongs().success(function(data){
		$scope.recentsongs = data;
	});
}]);

musicapp.controller("signupCtrl",['$scope','dataService','$log',function($scope,dataService,$log){
	$scope.signup = function(){
		dataService.signupf($scope.name,$scope.email,$scope.password,$scope.date,$scope.mobile).
			success(function(data){
				console.log(data);
			}).error(function(data){
				console.log(data);
			});

	};
	
}]);

musicapp.controller("signinCtrl",['$scope','dataService',function($scope,dataService){
	//dataService.signinf($scope.)
	
	$scope.signin = function(){
		dataService.signinf($scope.useremail,$scope.userpassword).success(function(data){
			if(data.result == 1){
				location.href = "#/";
			}
		}).error(function(data){
			console.log(data);
		})
	};
}]);

musicapp.controller('categoryCtrl', ['$scope','dataService', function($scope,dataService){
		dataService.getcategories().success(function(data){
			console.log(data);
		});
}]);