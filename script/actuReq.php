<?php

	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	session_start();
	
	$req = base()->prepare('SELECT DISTINCT MAX(id_boot) AS "max" FROM `abonnement` 
							INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur
							INNER JOIN boot ON boot.id_utilisateur = utilisateur.id_utilisateur 
							WHERE abonnement.id_utilisateur = :id OR boot.id_utilisateur = :id 
							ORDER BY id_boot DESC');
	$req->execute(array('id' => $_SESSION['profil']['id']));
	$res = $req->fetch();
	
	$_SESSION['max'] = $res['max'];
	
?>