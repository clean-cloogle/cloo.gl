<?php
define("DBFILE", "db/db.sqlite");

function open_db(){
	try {
		return new SQLite3(DBFILE);
	} catch (Exception $e){
		echo "Failed to open database at " . DBFILE . "\n";
		exit;
	}
}
