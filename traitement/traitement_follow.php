<?php

include('../include/fonction_bdd.php');

session_start();

if (isset($_GET['follow']))
{
	$in = base() -> prepare('INSERT INTO abonnement (id_abonnement, id_utilisateur, cloche_abonnement) VALUES (:abo, :use, 0)');
	
	$in -> execute(array('abo' => $_SESSION['visite'], 'use' => $_SESSION['profil']['id']));
	
	$follow = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, follow) VALUES(:mentionner, :mentionneur, 1)');
							
	$follow -> execute(array(
		'mentionner' => $_SESSION['visite'],
		'mentionneur' => $_SESSION['profil']['id']
	));	
}

if(isset($_GET['unfollow']))
{
	$del = base() -> prepare('DELETE FROM abonnement WHERE id_utilisateur LIKE :use AND id_abonnement LIKE :abo');
	
	$del -> execute(array('abo' => $_SESSION['visite'], 'use' => $_SESSION['profil']['id']));
}

header('Location: ../profil.php');