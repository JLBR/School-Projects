<?php
  ini_set('display_errors',1); 
  error_reporting(E_ALL);
  include '../autoloader.php';	



  echo "<html><body>";

  echo "<p>autoloader included";

  $testItem = Item::blankItem("1");

  echo "<p>blank item created<p>";
  var_dump($testItem);
  unset($testItem);

  $testItem = new item(1, "TestItem", 1, "555-555", "Test-555", "Test accessory 1", "Test features 1", 1, 10, "TestOS 6.22", "Notes 1", date('Y-m-d'));
  echo "<p>item created <p>";
  var_dump($testItem);

  echo "<p> getID(): ".$testItem->getID();
  echo "<p> getName(): ".$testItem->getName();
  echo "<p> getOwner(): ".$testItem->getOwner();
  echo "<p> getSerial_num(): ".$testItem->getSerial_num();
  echo "<p> getPhysicalID(): ".$testItem->getPhysicalID();
  echo "<p> getAccessories(): ".$testItem->getAccessories();
  echo "<p> getFeatures(): ".$testItem->getFeatures();
  echo "<p> getCategoryID(): ".$testItem->getCategoryID();
  echo "<p> getPages(): ".$testItem->getPages();
  echo "<p> getOS(): ".$testItem->getOS();
  echo "<p> getNotes(): ".$testItem->getNotes();
  echo "<p> getDate(): ".$testItem->getDate();
      
  echo "<p> setPhysicalID to Test-123: ";
  $testItem->setPhysicalID("Test-123");
  echo "<p> getPhysicalID(): ".$testItem->getPhysicalID();
      
  echo "<p> setFeatures to Test features 2: ";
  $testItem->setFeatures("Test features 2");
  echo "<p> getFeatures(): ".$testItem->getFeatures();
      
  echo "<p> setCategoryID to 2: ";
  $testItem->setCategoryID(2);
  echo "<p> getCategoryID(): ".$testItem->getCategoryID();
      
  echo "<p> setAccessory to Test accessory 2: ";
  $testItem->setAccessory("Test accessory 2");
  echo "<p> getAccessories(): ".$testItem->getAccessories();
      
  echo"<p> test getJSON()";
  echo $testItem->getJSON();
      
  unset($testItem);
      
  echo"<p> connecting to db to get item :<p>";
      
  $testItem = Item::getItem(1);
      
  var_dump($testItem);
      
  echo "<p> getting history<p>";
      
  $results = $testItem->getHistory();
            
  var_dump($results);

  echo "<p> getting JSON history";

  echo $testItem->getHistoryJSON();
?>