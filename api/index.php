<?php
session_start();
include 'dbConfig.php';
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$slim_app = new \Slim\Slim();
/*
$slim_app->post('/login','login');
$slim_app->get('/bugs','bugs');
$slim_app->get('/editbug/:bid','editbug');
$slim_app->post('/updateBug/:bid','updateBug');
$slim_app->delete('/deleteBugs/:bid','deleteBugs');
$slim_app->post('/addnewbug','addnewbug');
*/
$slim_app->get('/getsongs','getSongs');
$slim_app->get('/getfSongs','getfSongs');
$slim_app->get('/recentSongs','recentSongs');
$slim_app->post('/signup','signup');
$slim_app->post('/signin','signin');
$slim_app->get('/getsession','getsession');
$slim_app->get('/category','category');

$slim_app->run();

function getSongs(){
	$sql = "SELECT * FROM songs LIMIT 5";
	try{
		$db = getDB();
		$stmt = $db->query($sql);
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$count = $stmt->rowCount();
		if($count > 0){
			//echo '{"result":"success"}';
			echo json_encode($data);
		}else {
			echo '{"result":"fail"}';
		}
	}catch(PDOException $e){

	}
}

function getfSongs(){
	$sql = "SELECT * FROM songs LIMIT 6,9";
	try{
		$db = getDB();
		$stmt = $db->query($sql);
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$count = $stmt->rowCount();
		if($count > 0){
			//echo '{"result":"success"}';
			echo json_encode($data);
		}else {
			echo '{"result":"fail"}';
		}
	}catch(PDOException $e){
		
	}
}
function recentSongs(){
	$sql = "SELECT * FROM recent_songs LIMIT 6";
	try{
		$db = getDB();
		$stmt = $db->query($sql);
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$count = $stmt->rowCount();
		if($count > 0){
			//echo '{"result":"success"}';
			echo json_encode($data);
		}else {
			echo '{"result":"fail"}';
		}
	}catch(PDOException $e){
		
	}
}

function signup(){
	$request = \Slim\Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "INSERT INTO user(name,email,password,dob,mobile) VALUES(?,?,?,?,?)";
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$val = array($user->username,$user->useremail,$user->userpassword,$user->userdob,$user->usermo);
		$stmt->execute($val);
		$rows = $stmt->rowCount();
		if($rows == 1){
			echo '{"sucess":"Your Registration is successful"}';
		}else {
			echo '{"fail":"Your registration is not successful"}';
		}
	}catch(PDOException $e){
		echo '{"error":"Database operation failed"}';
	}
	
	//var_dump($user);
}
function signin(){
	$request = \Slim\Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "SELECT * FROM user WHERE email=? AND password=?";
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$val = array($user->useremail,$user->userpassword);
		$stmt->execute($val);
		$stmt->fetch();
		foreach ($stmt as $key => $value) {
			$username = "Rajesh";
			$id = '1';
		}
		$row = $stmt->rowCount();
		if($row > 0 ){
			echo '{"result":"1"}';
			
			$_SESSION['user'] = $user->useremail;
			$_SESSION['username'] = $username;
		}else {
			echo '{"result":"Your credential are invalid"}';
		}
	}catch(PDOException $e){
		echo '{"error":"Can not connect to database"}';
	}
}
function getsession(){
	if(isset($_SESSION['user'])){
		$sessionvalue = array('useremail'=>$_SESSION['user'],'username' => 'Rajesh');
		echo json_encode($sessionvalue);
	}else {
		echo '{"session":"not active"}';
	}
}
function category(){
		$sql = "SELECT * FROM category";
	try{
		$db = getDB();

	}catch(PDOException $e){

	}
}
/*
function login(){
	$request = \Slim\Slim::getInstance()->request();
	$user = json_decode($request->getBody());

	$sql = "SELECT * FROM login WHERE username=? AND password = ?";
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute(array($user->username,$user->password));
		$count = $stmt->rowCount();
		if($count > 0){
			echo '{"result":"success"}';
		}else {
			echo '{"result":"fail"}';
		}
	}catch(PDOException $e){

	}

}

function bugs(){
	$sql = "SELECT * FROM bugs";
	try{
		$db = getDB();
		$stmt = $db->query($sql);
		$bg = $stmt->fetchAll();
		echo json_encode($bg);
	}catch(PDOException $e){

	}
}

function editbug($bid){
	$sql = "SELECT * FROM bugs WHERE id = '$bid' LIMIT 1";
	try{
		$db = getDB();
		$stmt = $db->query($sql);
		$bg = $stmt->fetch(PDO::FETCH_OBJ);
		echo json_encode($bg);
	}catch(PDOException $e){

	}
}

function updateBug($bid){
	$request = \Slim\Slim::getInstance()->request();
	$bug = json_decode($request->getBody());

	$sql = "UPDATE bugs SET bugname=?,language=?,person=?,founddate=?,exp_sol_date=? WHERE id=?";
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute(array($bug->bugname,$bug->language,$bug->person,$bug->founddate,$bug->expsoldate,$bid));
		$count = $stmt->rowCount();
		if($count > 0){
			echo '{"result":"success"}';
		}else {
			echo '{"result":"fail"}';
		}
	}catch(PDOException $e){

	}
}

function deleteBugs($bid){
	$sql = "DELETE FROM bugs WHERE id=:delete_id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("delete_id", $bid);
		$stmt->execute();
		$db = null;
		bugs();
		//echo true;
		
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}	
}

function addnewbug(){
	$request = \Slim\Slim::getInstance()->request();
	$bug = json_decode($request->getBody());

	$sql = "INSERT INTO bugs(bugname,language,person,founddate,exp_sol_date) VALUES(?,?,?,?,?)";
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute(
						array(
							$bug->bugname,
							$bug->language,
							$bug->person,
							date('Y-m-d H:i:s',strtotime($bug->founddate)),
							date('Y-m-d H:i:s',strtotime($bug->expsoldate))
						)
					);
		$count = $stmt->rowCount();
		if($count > 0){
			echo '{"result":"success"}';
		}else {
			echo '{"result":"fail"}';
		}
	}catch(PDOException $e){

	}
}*/
/*
$slim_app->post('/doLogin','doLogin');
$slim_app->get('/isLogin','isLogin');
$slim_app->get('/logout','logout');
$slim_app->get('/loadUsers','loadUsers');
$slim_app->post('/newUser','newUser');
$slim_app->post('/editUser','editUser');
$slim_app->get('/loadEditUser/:eid','loadEditUser');
$slim_app->delete('/deleteUser/:did','deleteUser');


$slim_app->get('/getTask','getTask');
$slim_app->post('/addTask','addTask');
$slim_app->post('/editTask','editTask');
$slim_app->post('/deleteTask','deleteTask');
$slim_app->run();


function getTask(){
	$sql = "SELECT * FROM todos ORDER BY id asc";
	try {
		$db = getDB();
		$stmt = $db->query($sql);  
		$tasks = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($tasks);
		
	} catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function addTask(){
	$request = \Slim\Slim::getInstance()->request();
	$insert = json_decode($request->getBody());
	
	$sql = "INSERT INTO todos (name, startdate, enddate) VALUES (:name, :startdate, :enddate)";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $insert->taskname);
		$stmt->bindParam("startdate", $insert->startdate);
		$stmt->bindParam("enddate", $insert->enddate);		
		$status = $stmt->execute();	
		$db = null;
		echo '{"status":"success"}';
	} catch(PDOException $e) {		
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function editTask(){
	$request = \Slim\Slim::getInstance()->request();
	$update = json_decode($request->getBody());
	
	$sql = "UPDATE todos SET name=:name,startdate=:startdate,enddate=:enddate WHERE id=:id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $update->taskname);
		$stmt->bindParam("startdate", $update->startdate);
		$stmt->bindParam("enddate", $update->enddate);
		$stmt->bindParam("id", $update->id);			
		$status = $stmt->execute();	
		$db = null;
		echo '{"status":"success"}';
	} catch(PDOException $e) {		
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteTask(){
	$request = \Slim\Slim::getInstance()->request();
	$delete = json_decode($request->getBody());

	$sql = "DELETE FROM todos WHERE id = ?";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(1,$delete->id);
		if($stmt->execute()){
			$db = null;
			echo '{"status":"success"}';
		}
	} catch (Exception $e) {
		
	}
}
/*
function isLogin() {
	session_start();
	if(isset($_SESSION['username']) && !empty($_SESSION['username']))
		echo '{"isLogin": true}';
	else
		echo '{"isLogin": false}';
}
function logout() {
	session_start();
	session_destroy();
}

function doLogin() {
	$request = \Slim\Slim::getInstance()->request();
	$update = json_decode($request->getBody());	
	
	try {
		$db = getDB();		
		$stmt = $db->prepare("SELECT * FROM admin WHERE username=:username1 AND password=:password1");
		$stmt->bindValue(':username1', $update->username, PDO::PARAM_INT);
		$stmt->bindValue(':password1', $update->password, PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		$db = null;
		
		if(count($rows)) {			
			session_start();
			$_SESSION['username'] =  $update->username;
			echo '{"status": "success"}';
		}
		else
			echo '{"status": "failed"}';
	} catch(PDOException $e) {	    
		echo '{"error":{"msg":'. $e->getMessage() .'}}'; 
	}
	
}

function loadUsers() {
	$sql = "SELECT id,name,email,mobile FROM users ORDER BY id asc";
	try {
		$db = getDB();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($users);
		
	} catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteUser($did) {   
	$sql = "DELETE FROM users WHERE id=:delete_id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("delete_id", $did);
		$stmt->execute();
		$db = null;
		//echo true;
		loadUsers();
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}	
}

function newUser() {
	$request = \Slim\Slim::getInstance()->request();
	$insert = json_decode($request->getBody());
	
	$sql = "INSERT INTO users (name, email, mobile) VALUES (:name, :email, :mobile)";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $insert->name);
		$stmt->bindParam("email", $insert->email);
		$stmt->bindParam("mobile", $insert->mobile);		
		$status = $stmt->execute();	
		$db = null;
		echo '{"status":'.$status.'}';
	} catch(PDOException $e) {		
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function loadEditUser($eid) {
	$sql = "SELECT id,name,email,mobile FROM users WHERE id=:id";
	try {		
		$db = getDB();		
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $eid);		
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		$db = null;
		echo json_encode($rows);
	} catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


function editUser() {
	$request = \Slim\Slim::getInstance()->request();
	$update = json_decode($request->getBody());
	
	$sql = "UPDATE users SET name = :name, email = :email, mobile = :mobile WHERE id = :id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $update->name);
		$stmt->bindParam("email", $update->email);
		$stmt->bindParam("mobile", $update->mobile);
		$stmt->bindParam("id", $update->id);		
		$status = $stmt->execute();	
		$db = null;
		echo '{"status":'.$status.'}';
	} catch(PDOException $e) {		
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
*/
?>