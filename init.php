<?php
include_once 'config.php';

$db = open_db();

function has_column($table, $column){
	global $db;
	$q = $db->query("PRAGMA table_info($table)");
	while($r = $q->fetchArray())
		if($r['name'] === $column)
			return TRUE;
	return FALSE;
}
	
# v0.1
$db->query("CREATE TABLE IF NOT EXISTS cloogle(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS regular(url TEXT)");
$db->query("CREATE TABLE IF NOT EXISTS access(token TEXT)");

# v0.2
$db->query("CREATE TABLE IF NOT EXISTS log(id INTEGER, date TEXT, iscloogle INTEGER)");

# v0.3
if(!has_column("regular", "date"))
	$db->query("ALTER TABLE regular ADD COLUMN date TEXT");
if(!has_column("cloogle", "date"))
	$db->query("ALTER TABLE cloogle ADD COLUMN date TEXT");

$db->close();
