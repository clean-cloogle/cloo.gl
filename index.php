<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

define("DBFILE", "db/db.sqlite");

# This is needed to run the scripts as an AJAX request from javascript
if(isset($_SERVER['HTTP_ORIGIN'])){
	$http_origin = $_SERVER['HTTP_ORIGIN'];
	if($http_origin === "https://cloogle.org" ||
			$http_origin === "http://localhost" ||
			$http_origin === "http://cloogle.org" ||
			$http_origin === "http://www2.cloogle.org" ||
			$http_origin === "https://www2.cloogle.org"){
		header("Access-Control-Allow-Origin: $http_origin");
	}
}

# Open handle
try {
	$db = new SQLite3(DBFILE);
} catch (Exception $e){
	echo "Failed to open database at " . DBFILE . "\n";
	exit;
}
	
# Init the database
$db->query("CREATE TABLE IF NOT EXISTS cloogle(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS regular(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS access(token TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS log(id INTEGER, date TEXT, iscloogle INTEGER)");

function quit($msg){
	global $db;
	echo $msg . "\n";
	if(!is_null($db)){
		$db->close();
	}
	http_response_code(400);
	exit;
}

# Lookup or just a redirect to cloogle.org
if($_SERVER['REQUEST_METHOD'] === 'GET'){
	$url = 'https://cloogle.org';
	$prefix = "";
	if(isset($_GET['key']) && isset($_GET['type'])){
		if($_GET['type'] === 'regular'){
			$dbname = "regular";
			$iscloogle = 0;
		} else if($_GET['type'] === 'cloogle'){
			$dbname = "cloogle";
			$iscloogle = 1;
			$prefix = "https://cloogle.org/";
		} else {
			quit("Incorrect type");
		}
		$stmt = $db->prepare("SELECT rowid, url FROM $dbname WHERE rowid=:id");
		$stmt->bindValue(':id', intval(base64_decode($_GET['key'])), SQLITE3_INTEGER);
		if(($res = $stmt->execute()) === FALSE){
			quit("Select query went wrong");
		}
		if($arr = $res->fetchArray()){
			$urlid = intval($arr[0]);
			$url = $arr[1];
			$stmt->close();

			//Insert into log table
			$stmt = $db->prepare("
				INSERT INTO log (id, date, iscloogle)
				VALUES (:id, DATETIME('now', 'localtime'), :iscloogle)");
			$stmt->bindValue(':id', $urlid, SQLITE3_INTEGER);
			$stmt->bindValue(':iscloogle', $iscloogle, SQLITE3_INTEGER);
			if(($res = $stmt->execute()) === FALSE){
				quit("Select query went wrong");
			}
			$stmt->close();
		} else {
			quit("No url with key={$_GET['key']}");
		}
	}
	header("Location: $prefix$url");
# Api call to generate a new link
} else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	if(!isset($_POST['type'])){
		quit("No type set");
	}
	
	switch($_POST['type']){
	case 'regular':
		if(!isset($_POST['token'])){
			quit("Authentication token required");
		}
	case 'cloogle':
		if(!isset($_POST['url'])){
			quit("url argument missing");
		}
		break;
	default:
		quit("Incorrect type");
	}
	
	if($_POST['type'] === 'cloogle'){
		$dbname = "cloogle";
		$mod="e/";
	} else if($_POST['type'] === 'regular'){
		$dbname = "regular";
		if(preg_match('#^https?://#i', $_POST['url']) === 0) {
			$_POST['url'] = "http://{$_POST['url']}";
		}
		$mod="";
	}
	$stmt = $db->prepare("INSERT INTO $dbname (url) VALUES (:url)");
	$stmt->bindParam(':url', $_POST['url'], SQLITE3_TEXT);
	if($stmt->execute() === FALSE){
		quit("Insert query went wrong");
	}
	echo "https://cloo.gl/" . $mod . base64_encode($db->lastInsertRowID()) . "\n";
	$stmt->close();
} else {
	quit("Unsupported request method");
}
$db->close();
