<?php

    class Items
    {
	  private $itemCollection;
	  private $originalCollection;
	  private $localIndex;
	  private $count;

	  private function __construct($newItems, $newCount)
	  {
		  $this->itemCollection = $newItems;
		  $this->originalCollection = $newItems;
		  $this->localIndex = 0;
		  $this->count = $newCount;
	  }

	  private function newCollection($results, $count)
	  {
		  for ($index = 0; $index<$count-1;$index++)
		  {
			  $newItemCollection[$index] = new Item(	$results[$index]['id'],
								  $results[$index]['name'],
								  $results[$index]['owner'],
								  $results[$index]['serialnum'],
								  $results[$index]['phyID'],
								  $results[$index]['accessories'],
								  $results[$index]['features'],
								  $results[$index]['category'],
								  $results[$index]['pages'],
								  $results[$index]['os'],
								  $results[$index]['notes'],
								  $results[$index]['date']
								  );
		  }
		  return new Items($newItemCollection, $count);
	  }



	  //gets item by owner
	  //returns \NULL on error
	  public function getByOwner($ownerID)
	  {
		    $DBH = utility::connectToDB();
		    if($DBH == \NULL)
			   return \NULL;

		    $sql = ("
				  SELECT 
					     * 
				  FROM 
				      items
				  WHERE 
					  owner = :id
				  ");

		    try
		    {
			   $STH = $DBH->prepare($sql);

			   $STH->bindParam(':id', $ownerID);
                  if($STH->execute())
			   {
				  $results = $STH->fetchAll();
			   }
			   else
			   {
				  echo 'Error: retreaving data\n';
				  print_r($DBH->errorInfo());
				  print_r($STH->errorInfo());
				  return \NULL;
			   }
			   $count = $STH->rowCount();
		    }
		    catch(PDOException $e) 
		    {
   			   echo 'Error: ' . $e->getMessage();
			   $DBH = \NULL;
			   return \NULL;
		    }
		    $DBH = \NULL;
		    return Items::newCollection($results, $count);
	  }

	  public function moveToFirst()
	  {
		  $this->localIndex = 0;
	  }

	  //returns false if at the end of the items
	  public function next()
	  {
		  if($this->localIndex < ($this->count-1))
		  {
			  ++$this->localIndex;
			  return \TRUE;
		  }
		
		  return \FALSE;
	  }

	  public function getCurrent()
	  {
		  return $this->itemCollection[index];
	  }

	  public function getTotalItems()
	  {
		  return $this->count;
	  }


	  public function getAll()
	  {
		  return $this->itemCollection;
	  }

	  public function getAllJSON()
	  {
		  $index = 0;
		  foreach ($this->itemCollection as $item)
		  {
			  $indexedResults[$index] = $item->getArray();
			  ++$index;
		  }

		  return json_encode($indexedResults);
	  }
    }

?>