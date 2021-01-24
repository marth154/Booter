<?php

	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	session_start();
	include('../include/fonction_bdd.php');
	include('../include/fonction_abonnemnt.php');
	
	if(isset($_GET['p'])){
		
		$_SESSION['visite'] = $_GET['p'];
		header('Location: ../abonnement.php');
		
	}


?>