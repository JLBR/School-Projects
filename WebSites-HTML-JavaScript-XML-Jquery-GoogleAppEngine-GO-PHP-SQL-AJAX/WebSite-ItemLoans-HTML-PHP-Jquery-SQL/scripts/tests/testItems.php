<?php
	ini_set('display_errors',1); 
	error_reporting(E_ALL);
	
	include '../autoloader.php';

	echo "<p>getting itemsp<p>";

	$ownersItemsCollection = items::getByOwner(1);
	
	var_dump($ownersItemsCollection);

	echo "<p>get All<p>";

	$results = $ownersItemsCollection->getAll();

	var_dump($results);

	echo "<p>displaying JSON<p>";

	$ownerItemJSON = $ownersItemsCollection->getAllJSON();

	var_dump($ownerItemJSON);
?>