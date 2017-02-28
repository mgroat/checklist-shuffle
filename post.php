<?php
include 'library.php';
if(empty($_POST))
	die("You should only come to this page when creating a new checklist");
$conn = pgConnect();
$stmt = 'INSERT INTO "checkMaster" (description, owner) VALUES ($1,$2) RETURNING pk ';
$vals = [$_POST['description'],0];
$results = pgQuery($conn,$stmt,$vals);
$parent = $results[0]['pk'];

$stmt = 'INSERT INTO "checkItem" (data, parent, depend, item) VALUES ($1, $2, $3, $4)';
foreach($_POST['data'] as $key => $data) {
	if(!empty($data['detail'])) {
		if(empty($data['depend']))
			$data['depend'] = null;
		$vals = [$data['detail'],$parent,$data['depend'],$key];
		$results = pgQuery($conn,$stmt,$vals);
	}	
}
$absUrl = $_SERVER['HTTP_ORIGIN']."/check.html?$parent";
$relUrl = "check.html?$parent";
?>

<center><h1>Your super-secret URL is <a href="<?=$relUrl?>"><?=$absUrl?></a></h1></center>
<center><h2>Bookmark the above URL to come back to your checklist at any time</h2></center>