var baseUrl = "http://localhost/bugmanagement/";
var bugApp = angular.module("bugApp",['ngRoute']);

//Angular Routing

bugApp.config(['$routeProvider',function($routeProvider) {
		$routeProvider.
						when("/",{
							templateUrl:"pages/login.html",
							controller:"loginCtrl"
						}).when("/list",{
							templateUrl:"pages/list.html",
							controller:"listCtrl"
						}).when("/edit/:bid",{
							templateUrl:"pages/edit.html",
							controller:"editCtrl"
						}).
						when("/addnewbug",{
							templateUrl:"pages/addnewbug.html",
							controller:"addNewBugCtrl"
						});
}]);

//Angular Controller
bugApp.controller("loginCtrl",['$scope','$log','$http',function($scope,$log,$http){
	$scope.logintext = "Simple login text";
	var burl = baseUrl+"api/login";
	$scope.login = function(){
		$http.post(burl,{
			'username':$scope.username,
			'password':$scope.password

		}).success(function(data,status,headers,config){
			if(data.result == "success"){
				location.href = baseUrl+"#/list";
			}else {
				alert("Supplied Credentials are invalid");
			}

		}).error(function(data,status,headers,config){

		});
	}
}]);

bugApp.controller("listCtrl",['$scope','$log','$http',function($scope,$log,$http){
	var burl = baseUrl+"api/bugs";
	$http.get(burl).
					success(function(data,status,headers,config){
						$scope.bug = data;
					}).
						error(function(data,status,headers,config){

						});
		$scope.delete_user = function(did){
			var burl = baseUrl+"api/deleteBugs/"+did;
			$http.delete(burl).
			success(function(data,status,headers,config){
				$scope.bug = data;
			}).error(function(data,status,headers,config){

			});
		}
	
}]);

bugApp.controller("editCtrl",['$scope','$log','$http','$routeParams',function($scope,$log,$http,$routeParams){
	var bid = $routeParams.bid;
	var burl = baseUrl+"api/editbug/"+bid;
//$scope.bugname = "Rajesh";
	$http.get(burl)
					.success(function(data,status,headers,config){
						$scope.bugname = data.bugname;
						//console.log(data);
						$scope.language = data.language;
						//console.log(data.bugname);
						$scope.person = data.person;
						$scope.founddate = data.founddate;
						$scope.exp_sol_date = data.exp_sol_date;

					}).
					error(function(data,status,headers,config){

					});

	$scope.updateBug = function(){
		var burl = baseUrl+"api/updateBug/"+bid;

		$http.post(burl,{
			'bugname':$scope.bugname,
			'language':$scope.language,
			'person':$scope.person,
			'founddate':$scope.founddate,
			'expsoldate':$scope.exp_sol_date
		}).success(function(data,status,headers,config){
			if(data.result == "success"){
				location.href = "http://localhost/bugmanagement/#/list";
			}
		}).error(function(data,status,headers,config){

		});
	}


}]);
bugApp.controller('addNewBugCtrl', ['$scope','$http', function($scope,$http){
	$scope.addnewbug = function(){
		var burl = baseUrl+"api/addnewbug";
		$http.post(burl,{
			'bugname':$scope.bugname,
			'language':$scope.language,
			'person':$scope.person,
			'founddate':$scope.founddate,
			'expsoldate':$scope.exp_sol_date
		}).success(function(data,status,headers,config){
			if(data.result == 'success'){
				location.href = "http://localhost/bugmanagement/#/list";
			}
		}).error(function(data,status,headers,config){

		});
	}
}])
