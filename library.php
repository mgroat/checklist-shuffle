<?php
function pgQuery($conn, $stmt, $vals = []) {
	if(!$conn) 
		$conn = pgConnect(); 
	$sql = pg_prepare($conn,null,$stmt); 
	$results = pg_execute($conn,null,$vals); 
	if ($results) 
		return pg_fetch_all($results); 
	else 
		return $results; 
} 

function pgConnect() { 
	if(file_exists('./dbConf.txt')) //This will exist on our test server, not on Heroku 
		$dbUrl = file_get_contents("./dbConf.txt"); 
	else 
		$dbUrl = getenv('DATABASE_URL'); 
	$dbopts = parse_url($dbUrl); 
	$conn = pg_connect("host={$dbopts['host']} port={$dbopts['port']} " 
		. "dbname=".ltrim($dbopts['path'],'/')." user={$dbopts['user']} password={$dbopts['pass']}"); 
	return $conn; 
}