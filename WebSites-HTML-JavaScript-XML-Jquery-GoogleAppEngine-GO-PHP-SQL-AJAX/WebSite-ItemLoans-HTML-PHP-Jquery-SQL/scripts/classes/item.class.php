<?php

    class Item
    {

	   private $id;
	   protected $itemName;
	   protected $ownerID;
	   protected $serial_num;
	   protected $physicalID;
	   protected $accessories;
	   protected $features;
	   protected $categoryID;
	   protected $pages;
	   protected $OS;
	   protected $notes;
	   private $dateAdded;

	   public function __construct( $newID, $newName, $newOwner, $newSN, 
					$newPhysicalID, $newAccessories, $newFeatures, 
					$newCategoryID, $newPages, $newOS, $newNotes, $newDate)
	   {
		  $this->id = $newID;
		  $this->itemName = $newName;
		  $this->ownerID = $newOwner;
		  $this->serial_num = $newSN;
		  $this->physicalID = $newPhysicalID;
		  $this->accessories = $newAccessories;
		  $this->features = $newFeatures;
		  $this->categoryID = $newCategoryID;
		  $this->pages = $newPages;
		  $this->OS = $newOS;
		  $this->notes = $newNotes;
		  $this->dateAdded = $newDate;
	   }

	   public function blankItem($itemID)
	   {
		return new item($itemID, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL);
	   }

	   public function getItem($itemID)
	   {
		$dbItem = item::blankItem($itemID);
		$dbItem->getFromDB($itemID);
		return $dbItem;
	   }

	   public function getID()
	   {
		  return $this->id;
	   }

	   public function getName()
	   {
		  return $this->itemName;
	   }

	   public function getOwner()
	   {
		  return $this->ownerID;
	   }

	   public function getSerial_num()
	   {
		  return $this->serial_num;
	   }

	   public function getPhysicalID()
	   {
		  return $this->physicalID;
	   }

	   public function getAccessories()
	   {
		  return $this->accessories;
	   }

	   public function getFeatures()
	   {
		  return $this->features;
	   }

	   public function getCategoryID()
	   {
		  return $this->categoryID;
	   }

	   public function getPages()
	   {
		  return $this->pages;
	   }

	   public function getOS()
	   {
		  return $this->OS;
	   }

	   public function getNotes()
	   {
		  return $this->notes;
	   }

	   public function getDate()
	   {
		  return $this->dateAdded;
	   }


	   //returns true on success, false on failure
	   public function setPhysicalID($updatePID)
	   {
		  if($updatePID != \NULL)
		  {
			 $this->physicalID = $updatePID;
		  }
		  else
		  {
			 return \FALSE;
		  }
		  return \TRUE;
	   }


	   //returns true on success, false on failure
	   public function setAccessory($updatedAccessory)
	   {

		  if($updatedAccessory != \NULL)
		  {
			 $this->accessories = $updatedAccessory;
		  }
		  else
		  {
			 return \FALSE;
		  }
		  return \TRUE;
	   }

	   //returns true on success, false on failure
	   public function setFeatures($updatedFeatures)
	   {

		  if($updatedFeatures != \NULL)
		  {
			 $this->features = $updatedFeatures;
		  }
		  else
		  {
			 return \FALSE;
		  }
		  return \TRUE;
	   }

	   //returns true on success, false on failure
	   public function setCategoryID($updatedCategoryID)
	   {

		  if($updatedCategoryID != \NULL)
		  {
			 $this->categoryID = $updatedCategoryID;
		  }
		  else
		  {
			 return \FALSE;
		  }
		  return \TRUE;
	   }

	   //returns true on success, false on failure
	   public function reloadItem()
	   {
		  return getFromDB($this->$id);
	   }

	   public function commitItem()
	   {

	   }

	   public function getArray()
	   {
		return 	array(
				'id' => $this->id,
				'name' => $this->itemName,
				'owner' => $this->ownerID,
				'serial_num' => $this->serial_num, 
				'physicalID' => $this->physicalID,
				'accessories' => $this->accessories, 
				'features' => $this->features,
				'categoryID' => $this->categoryID,
				'pages' => $this->pages,
				'OS' => $this->OS,
				'notes' => $this->notes,
				'dateAdded' => $this->dateAdded
			);
	   }

	   public function getJSON()
	   {
		  return json_encode( $this->getArray()	);
	   }


	   //returns true on success, false on failure
	   private function getFromDB($itemID)
	   {
		  $DBH = utility::connectToDB();
		  if($DBH == \NULL)
		  {
			 echo "Error connecting to database";
			 exit();
		  }

		  $sql = ("
				SELECT 
					   * 
				FROM 
					   items
				WHERE 
					   id = :id
				");

		  try
		  {
			 $STH = $DBH->prepare($sql);

			 $STH->bindParam(':id', $itemID);

			 if($STH->execute())
			 {
				$STH->bindColumn('id', $this->id);
				$STH->bindColumn('name', $this->itemName);
				$STH->bindColumn('owner', $this->ownerID);
				$STH->bindColumn('category', $this->categoryID);
				$STH->bindColumn('serialnum', $this->serial_num);
				$STH->bindColumn('features', $this->features);
				$STH->bindColumn('accessories', $this->accessories);
				$STH->bindColumn('pages', $this->pages);
				$STH->bindColumn('os', $this->OS);
				$STH->bindColumn('notes', $this->notes);
				$STH->bindColumn('date', $this->dateAdded);

				$STH->fetch(PDO::FETCH_BOUND);
			 }
			 else
			 {
				echo 'Error: retreaving data\n';
				print_r($DBH->errorInfo());
				print_r($STH->errorInfo());
			 }
		  }
		  catch(PDOException $e) 
		  {
   				echo 'Error: ' . $e->getMessage();
				$DBH = \NULL;
				return \FALSE;
		  }
		  $DBH = \NULL;
		  return \TRUE;
	   }

	   //returns sql results or NULL
	   public function getHistory()
	   {
		  $DBH = utility::connectToDB();
		  if($DBH == \NULL)
		  {
			 echo "Error connecting to database";
			 exit();
		  }

		  $sql = ("
				SELECT 
					   * 
				FROM 
				    eventLog
				WHERE 
					FK_ITEM_ID = :id
				");

		  try
		  {
			 $STH = $DBH->prepare($sql);

			 $STH->bindParam(':id', $this->id);

			 if($STH->execute())
			 {
				$results = $STH->fetchAll();
			 }
			 else
			 {
				echo 'Error: retreaving data\n';
				print_r($DBH->errorInfo());
				print_r($STH->errorInfo());
			 }
		  }
		  catch(PDOException $e) 
		  {
   			 echo 'Error: ' . $e->getMessage();
			 $DBH = \NULL;
			 return \NULL;
		  }
		  $DBH = \NULL;
		  return $results;
	   }

	   public function getHistoryJSON()
	   {
		return json_encode($this->getHistory());
	   }

    }

?>