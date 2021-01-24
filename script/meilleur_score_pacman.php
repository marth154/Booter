<?php

include('../include/fonction_bdd.php');

session_start();

$res = base()->prepare('SELECT * FROM utilisateur_jeux WHERE id_utilisateur = :id AND id_jeux = 5');
$res->execute(array('id' => $_SESSION['profil']['id']));
$res = $res->fetch();
						
echo "Votre meilleur score : " . $res['score_jeux']; 
