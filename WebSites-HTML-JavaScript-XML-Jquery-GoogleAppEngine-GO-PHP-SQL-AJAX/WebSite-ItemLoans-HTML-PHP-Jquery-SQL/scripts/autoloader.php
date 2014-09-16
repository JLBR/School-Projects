<?php
	spl_autoload_register(null, false);
	spl_autoload_extensions('.class.php,.php');
	
	const LOCAL_DIR = "/nfs/stak/students/b/brewerji/public_html/CS419/scripts/";

	function classLoader($class)
	{
		$filename = strtolower($class).'.class.php';
		$file = LOCAL_DIR.'classes/'.$filename;

		if(!file_exists($file))
			return false;
		
		include $file;
	}

	function configLoader($class)
	{
		$filename = strtolower($class).'.config.php';
		$file = LOCAL_DIR.'config/'.$filename;

		if(!file_exists($file))
			return false;
		
		include $file;
	}

	spl_autoload_register('classLoader');
	spl_autoload_register('configLoader');
?>