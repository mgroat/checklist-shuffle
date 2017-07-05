<?php
include 'library.php';
$conn = pgConnect();
$stmt = 'SELECT * FROM checkItem WHERE parent = $1';
$itemResults = pgQuery($conn,$stmt,[$_GET['parent']]);

$stmt = 'SELECT * FROM checkMaster WHERE pk = $1';
$masterResults = pgQuery($conn,$stmt,[$_GET['parent']])[0];

$results = json_encode(['master' => $masterResults, 'item' => $itemResults]);
echo $results;