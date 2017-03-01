<?php
//http://stackoverflow.com/a/31107425
//Generate a secure random string
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_') {
	$str = '';
	$max = mb_strlen($keyspace, '8bit') - 1;
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
$key = random_str(20);
echo $key;
$stmt = 'INSERT INTO checkMaster (description, owner, pk) VALUES ($1,$2,$3) RETURNING pk';
$vals = [$_POST['description'],0,$key];
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
?>

<center><h1>Your super-secret URL is <a href="<?=$relUrl?>"><?=$absUrl?></a></h1></center>
<center><h2>Bookmark the above URL to come back to your checklist at any time</h2></center>