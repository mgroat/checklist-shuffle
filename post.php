<?php
//http://stackoverflow.com/a/31107425
//Generate a secure random string
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_') {
	$str = '';
	//$max = mb_strlen($keyspace, '8bit') - 1;
	for ($i = 0; $i < $length; ++$i) {
		//$str .= $keyspace[random_int(0, $max)];//This is better crypto, but requires php7
		$str .= $keyspace[rand(0, strlen($keyspace) - 1)];
	}
	return $str;
}


include 'library.php';
if(empty($_POST))
	die("You should only come to this page when creating a new checklist");

$conn = pgConnect();
if(isset($_POST['editPass'])){
	$stmt = "SELECT pk FROM checkMaster WHERE PK=$1 AND editPass=$2";
	$vals = [$_POST['parent'], $_POST['editPass']];
	$results = pgQuery($conn,$stmt,$vals);
	
	if(!empty($results)) {
		$stmt = "DELETE FROM checkItem WHERE parent=$1";
		$vals = [$_POST['parent']];
		$results = pgQuery($conn,$stmt,$vals);
		$stmt = "DELETE FROM checkMaster WHERE pk=$1";
		$vals = [$_POST['parent']];
		$results = pgQuery($conn,$stmt,$vals);
		$key = $_POST['parent'];
		$editPass=$_POST['editPass'];
	}
	else {
		die("Incorrect key");
	}
}
else {
	$key = random_str(20);
	$editPass = random_str(20);
}

$stmt = 'INSERT INTO checkMaster (description, owner, pk, editPass) VALUES ($1,$2,$3, $4) RETURNING pk';
$vals = [$_POST['description'],0,$key,$editPass];
$results = pgQuery($conn,$stmt,$vals);
$parent = $results[0]['pk'];

$stmt = 'INSERT INTO checkItem (data, parent, depend, item) VALUES ($1, $2, $3, $4)';
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
$editUrl = "make.php?parent=$parent&editPass=$editPass";
?>

<center><h1>Your super-secret URL is <a href="<?=$relUrl?>"><?=$absUrl?></a></h1></center>
<center><h2>Bookmark the above URL to come back to your checklist at any time</h2></center>
<center><h2>You can edit the checklist <a href="<?=$editUrl?>">Here</a> This link will not be shown again</h2></center>