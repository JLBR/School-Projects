<?php

    class EventLog
    {
	const EVENT_ADD_ITEM = 1;
	const EVENT_REMOVE_ITEM = 2;
	const EVENT_UPDATE_ITEM = 3;
	const EVENT_CHECK_OUT_ITEM = 4;
	const EVENT_CHECK_IN_ITEM = 5;
	const EVENT_MAKE_AVAILABLE = 6;
	const EVENT_MAKE_UNAVAILABLE = 7;
	const EVENT_ITEM_LOST = 8;
	const EVENT_ITEM_DAMAGED = 9;

	   private function logEvent($eventCatagory, $loanID, $itemID)
	   {

		  $timeNow = date('Y-m-d H:i:s');
		  $DBH = utility::connectToDB();
		  if($DBH == \NULL)
		  {
			 echo "Error connecting to database";
			 exit();
		  }

		  $sql = ("
				INSERT INTO eventLog 
					   ( 
						  FK_ITEM_ID,
						  FK_LOAN_ID,
						  event,
						  eventDate
					   )
				    VALUES
					   (
						  :id,
						  :loanID,
						  :event,
						  :eventDate
					   )
				");

		  try
		  {
			 $STH = $DBH->prepare($sql);

			 $STH->bindParam(':id', $itemID);
			 $STH->bindParam(':loanID', $loanID);
			 $STH->bindParam(':event', $eventCatagory);
			 $STH->bindParam(':eventDate', $timeNow);

			 $DBH->beginTransaction();

			 $sqlError = $STH->execute();
			 if(!($sqlError))
			 {
				echo 'Error: updating event log data';
				print_r($DBH->errorInfo());
				echo "<p>";
				print_r($STH->errorInfo());
				exit();
			 }

			$DBH->commit();
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

	   public function logAddItem($itemID)
	   {
		  EventLog::logEvent( EventLog::EVENT_ADD_ITEM, NULL, $itemID);
	   }

	   public function logRemoveItem($itemID)
	   {
		  EventLog::logEvent( EventLog::EVENT_REMOVE_ITEM, NULL, $itemID);
	   }

	   public function logItemCheckedOut($itemID, $loanID)
	   {
		  EventLog::logEvent(EventLog::EVENT_CHECK_OUT_ITEM, $loanID, $itemID);
	   }

	   public function logItemCheckedIn($itemID, $loanID)
	   {
		  EventLog::logEvent(EventLog::EVENT_CHECK_IN_ITEM, $loanID, $itemID);
	   }

	   public function logUpdatedItem($itemID)
	   {
		  EventLog::logEvent(EventLog::EVENT_UPDATE_ITEM, NULL, $itemID);
	   }

	   public function logItemUnavailable($itemID)
	   {
		  EventLog::logEvent(EventLog::EVENT_MAKE_UNAVAILABLE, NULL, $itemID);
	   }

	   public function logItemAvailable($itemID)
	   {
		  EventLog::logEvent(EventLog::EVENT_MAKE_AVAILABLE, NULL, $itemID);
	   }
    }
?>