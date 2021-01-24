<?php
    //On maintient la session ouverte
	session_start();

	//On détruit la session
	session_destroy(); 

	//On vide le tableau session
	$_SESSION = array();

	//On renvoie l'utilisateur sur la page d'accueil
	header('Location: index.php');

?>