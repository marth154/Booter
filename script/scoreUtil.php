<?php

	include('../include/fonction_bdd.php');
	session_start();
	$req = base()->prepare('SELECT * FROM utilisateur_jeux WHERE id_utilisateur = :id AND id_jeux = 1');
	$req->execute(array('id' => $_SESSION['profil']['id']));
	$res = $req->fetch();
	
	if($res['score_jeux'] == null){
		
		$req->closeCursor();
		$req = base()->prepare('INSERT INTO utilisateur_jeux(id_jeux, id_utilisateur, score_jeux) VALUES(1, :id, 0)');
		$req->execute(array('id' => $_SESSION['profil']['id']));
		
		$best = 0;
		
	}else{
		
		$best = $res['score_jeux'];
		
	}

?>
	
		Score : <span class="score" id="score">0</span><br>
		Meilleur score : <span class="best"><?= $best ?></span>