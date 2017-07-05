<?php
include "library.php";
$conn = pgConnect();

//Remove old tables if they exist
//Uncomment these lines to trash the existing DB
//@pgQuery($conn,"DROP TABLE checkItem");
//@pgQuery($conn,"DROP TABLE checkMaster");


//Uncomment to add column to existing table if needed
//pgQuery($conn,"ALTER TABLE checkMaster ADD COLUMN editPass text");

//Create tables
pgQuery($conn,"CREATE TABLE checkMaster(  pk text primary key,  editPass text, description text,  owner text)");
pgQuery($conn,"CREATE TABLE checkItem(  pk serial,  data text,  item integer,  depend integer,  parent text REFERENCES checkMaster(pk))");

//Create some sample data to play with
if(pgQuery($conn,"INSERT INTO checkMaster (description, owner, pk, editPass) VALUES ($1,$2,$3,$4) RETURNING pk",['This is a sample checklist','0','DfsHQzYUvamIWAWr8Xaa','aiejflERFdifjerEDFEW245'])) {
	pgQuery($conn,"INSERT INTO checkItem (data, parent, depend, item) VALUES ($1, $2, $3, $4)",['This is the first item.','DfsHQzYUvamIWAWr8Xaa',null,1]);
	pgQuery($conn,"INSERT INTO checkItem (data, parent, depend, item) VALUES ($1, $2, $3, $4)",['If you skipped the first item, you won\'t see this until you complete it.','DfsHQzYUvamIWAWr8Xaa',1,2]);
	pgQuery($conn,"INSERT INTO checkItem (data, parent, depend, item) VALUES ($1, $2, $3, $4)",['This is the third item.','DfsHQzYUvamIWAWr8Xaa',null,3]);
}
?>

<h1>Database setup complete</h1>