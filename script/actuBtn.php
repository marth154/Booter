<?php

	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	session_start();
	
	
			if(testPubli($_SESSION['profil']['id'], $_SESSION['maxIndex'])){
				
					btnActu();
				
			}

?>