<?php
$dbUrl = getenv('DATABASE_URL');
if(file_exists('./conf.txt')) //This will exist on our test server, not on Heroku
    $dbUrl = file_get_contents("./conf.txt");
$dbopts = parse_url($dbUrl);
$conn = pg_connect("host={$dbopts['host']} port={$dbopts['port']} dbname=".ltrim($dbopts['path'],'/')." user={$dbopts['user']} password={$dbopts['pass']}");
$stmt = 'INSERT INTO "checkMaster" (description, owner) VALUES ($1,$2) RETURNING pk ';
$vals = [$_POST['description'],0];
$sql = pg_prepare($conn,"INSERT-MASTER",$stmt);
$results = pg_execute($conn,"INSERT-MASTER",$vals);
$parent = pg_fetch_all($results)[0]['pk'];
echo $parent;

$stmt = 'INSERT INTO "checkItem" (data, parent, depend, item) VALUES ($1, $2, $3, $4)';
$sql = pg_prepare($conn,"INSERT-DETAIL",$stmt);
foreach($_POST['data'] as $key => $data) {
	if(!empty($data['detail'])) {
		if(empty($data['depend']))
			$data['depend'] = null;
		$vals = [$data['detail'],$parent,$data['depend'],$key];
		$results = pg_execute($conn,"INSERT-DETAIL",$vals);
	}	
}
$url = $_SERVER['HTTP_ORIGIN']."/check.html?$parent";
?>

<center><h1>Your super-secret URL is <a href="<?=$url?>"><?=$url?></a></h1></center>
