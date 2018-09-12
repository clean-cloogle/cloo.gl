<?php
//The database location
define("DBFILE", "db/db.sqlite");
//The domain name
define("WEBSITENAME", "cloo.gl");
//The folder containing the pastes
define("PASTES", "pastes");
//The symbols used by fiche for the slugs
define("SLUG_SYMBOLS", "[a-z0-9]");

function is_allowed_origin($origin){
	return in_array($origin, array(
		"https://cloogle.org",
		"http://localhost",
		"http://cloogle.org",
		"http://www2.cloogle.org",
		"https://www2.cloogle.org",
		));
}

function open_db(){
	try {
		return new SQLite3(DBFILE);
	} catch (Exception $e){
		echo "Failed to open database at " . DBFILE . "\n";
		exit;
	}
}
