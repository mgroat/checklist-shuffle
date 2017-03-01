<?php
include 'library.php';
$conn = pgConnect();
$stmt = 'SELECT * FROM checkItem WHERE parent = $1';
$results = pgQuery($conn,$stmt,[$_GET['parent']]);
$results = json_encode($results);
echo $results;