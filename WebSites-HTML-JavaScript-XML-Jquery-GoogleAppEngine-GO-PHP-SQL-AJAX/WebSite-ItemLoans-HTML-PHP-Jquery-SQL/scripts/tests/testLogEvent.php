<?php
  	ini_set('display_errors',1); 
  	error_reporting(E_ALL);
  	include '../autoloader.php';

  	EventLog::logAddItem(1);

  	echo "<p> ading to log item 1 add  at time: ".date('Y-m-d H:i:s',time());

	EventLog::logRemoveItem(1);

  	echo "<p> ading to log item 1 remove at time: ".date('Y-m-d H:i:s',time());

	EventLog::logItemCheckedOut(1, NULL);

  	echo "<p> ading to log item 1 checked out no loan at time: ".date('Y-m-d H:i:s',time());
	
	EventLog::logItemCheckedIn(1, NULL);

  	echo "<p> ading to log item 1 checked in no loan at time: ".date('Y-m-d H:i:s',time());

	EventLog::logItemCheckedOut(1, 1);

  	echo "<p> ading to log item 1 checked out loan 1 at time: ".date('Y-m-d H:i:s',time());
	
	EventLog::logItemCheckedIn(1, 1);

  	echo "<p> ading to log item 1 checked in loan 1 at time: ".date('Y-m-d H:i:s',time());

	EventLog::logUpdatedItem(1);

  	echo "<p> ading to log item 1 update at time: ".date('Y-m-d H:i:s',time());

	EventLog::logItemUnavailable(1);

  	echo "<p> ading to log item 1 unavailable at time: ".date('Y-m-d H:i:s',time());

	EventLog::logItemAvailable(1);

  	echo "<p> ading to log item 1 unavailable at time: ".date('Y-m-d H:i:s',time());

	$testItem = Item::getItem(1);

 	$results = $testItem->getHistory();

	var_dump($results);
?>