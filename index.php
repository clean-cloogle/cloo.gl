<?php
define("DBFILE", "db/db.sqlite");

if($_SERVER['REQUEST_METHOD'] === 'GET'){
	header('Location: https://cloogle.org');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
	echo "Unsupported request method...\n";
	die();
}

if(!isset($_POST['type'])){
	echo "No type set\n";
	die();
}

switch($_POST['type']){
case 'regular':
	if(!isset($_POST['token'])){
		echo "Authentication token required\n";
		die();
	}
case 'cloogle':
	if(!isset($_POST['url'])){
		echo "url argument missing\n";
		die();
	}
	break;
default:
	echo "Incorrect type\n";
	die();
}

# Open handle
if(!$db = new SQLite3(DBFILE)){
	die();
}

# Init the database
$db->query("CREATE TABLE IF NOT EXISTS cloogle(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS regular(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS access(token TEXT)");

if($_POST['type'] === 'cloogle'){
	echo "Not implemented yet...\n";
}
if($_POST['type'] === 'regular'){
	$stmt = $db->prepare("INSERT INTO regular (url) VALUES (:url)");
	$stmt->bindParam(':url', $_POST['url'], SQLITE3_TEXT);
	$stmt->execute();
	echo "http://cloo.gl/" . base64_encode($db->lastInsertRowID()) . "\n";
}
