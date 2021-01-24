<?php
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
function base()
{
		try
	    {
	        $bdd = new PDO('mysql:host=localhost:3306;dbname=lcdfbsgr_booter;charset=utf8', 'lcdfbsgr_booter', 'Booter3630', [
	        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	        	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	        	PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
	        	]);
	    }
	    catch(Exception $e)
	    {
	        die('Erreur : '. $e->getMessage());
	    }
	    return $bdd;
}



?>