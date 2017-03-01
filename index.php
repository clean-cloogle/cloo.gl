<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
define("DBFILE", "db/db.sqlite");

# Open handle
if(!$db = new SQLite3(DBFILE)){
	die();
}
	
# Init the database
$db->query("CREATE TABLE IF NOT EXISTS cloogle(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS regular(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS access(token TEXT)");

function quit($msg){
	global $db;
	echo $msg . "\n";
	if(!is_null($db)){
		$db->close();
	}
	exit;
}

# Lookup or just a redirect to cloogle.org
if($_SERVER['REQUEST_METHOD'] === 'GET'){
	$url = 'https://cloogle.org';
	if(isset($_GET['key']) && isset($_GET['type'])){
		if($_GET['type'] === 'regular'){
			$stmt = $db->prepare("SELECT url FROM regular WHERE rowid=:id");
			$stmt->bindValue(':id', intval(base64_decode($_GET['key'])), SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($arr = $res->fetchArray()){
				$url = $arr[0];
				$stmt->close();
			} else {
				quit("No url with that key");
			}
		} else if($_GET['type'] === 'cloogle'){
			quit("Not yet supported");
		}
	}
	header("Location: $url");
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
	
	$stmt = $db->prepare("INSERT INTO regular (url) VALUES (:url)");
	
	if($_POST['type'] === 'cloogle'){
		echo "Not implemented yet...\n";
		$mod="e/";
	}
	
	if($_POST['type'] === 'regular'){
		$stmt->bindParam(':url', $_POST['url'], SQLITE3_TEXT);
		$mod="";
	}
	
	$stmt->execute();
	echo "https://cloo.gl/" . $mod . base64_encode($db->lastInsertRowID()) . "\n";
	$stmt->close();
} else {
	quit("Unsupported request method");
}
$db->close();
