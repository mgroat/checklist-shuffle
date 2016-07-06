<?php
$dbopts = parse_url(getenv('DATABASE_URL'));
$conn = pg_connect("host={$dbopts['host']} port={$dbopts['port']} dbname=".ltrim($dbopts['path'],'/')." user={$dbopts['user']} password={$dbopts['pass']}");
$sql = pg_prepare($conn,"SHOW","SELECT * FROM pg_catalog.pg_tables");
$results = pg_execute($conn,"SHOW",[]);
$results = pg_fetch_all($results);
print_r($results);