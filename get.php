<?php
$dbUrl = getenv('DATABASE_URL');
if(file_exists('./conf.txt')) //This will exist on our test server, not on Heroku
    $dbUrl = file_get_contents("./conf.txt");
$dbopts = parse_url($dbUrl);
$conn = pg_connect("host={$dbopts['host']} port={$dbopts['port']} dbname=".ltrim($dbopts['path'],'/')." user={$dbopts['user']} password={$dbopts['pass']}");
$sql = pg_prepare($conn,"SHOW",'SELECT * FROM "checkItem" WHERE parent = $1');
//$sql = pg_prepare($conn,"SHOW","SELECT * FROM pg_catalog.pg_tables");
$results = pg_execute($conn,"SHOW",[$_GET['parent']]);
$results = json_encode(pg_fetch_all($results));
echo $results;