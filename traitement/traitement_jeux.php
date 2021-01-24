<?php
	session_start();
	include('../include/fonction_bdd.php');

if(isset($_GET['snake'])){
	
	$req = base()->prepare('UPDATE utilisateur_jeux SET score_jeux = :score WHERE id_utilisateur = :id AND id_jeux = 1');
	$req->execute(array('score' => $_GET['best'],
						'id' => $_SESSION['profil']['id']));
	
}

if(isset($_GET['doodle'])){
	
	$req = base()->prepare('UPDATE utilisateur_jeux SET score_jeux = :score WHERE id_utilisateur = :id AND id_jeux = 2');
	$req->execute(array('score' => $_GET['best'],
						'id' => $_SESSION['profil']['id']));
	
}

if(isset($_GET['quizz'])){
	
	if(isset($_GET['create'])){
		
		$req = base()->prepare('INSERT INTO game_quizz(id_joueur1, id_joueur2) VALUES(:J1, :J2)');
		$req->execute(array('J1' => $_SESSION['profil']['id'], 'J2' => $_GET['create']));
		
	}
	
}

if(isset($_GET['solitaire']))
{
	
	$sel_score = base() -> prepare('SELECT * FROM utilisateur_jeux WHERE id_utilisateur LIKE :id AND id_jeux LIKE 4');
	
	$sel_score -> execute(array('id' => $_SESSION['profil']['id']));
	
	$sel_score = $sel_score -> fetch();
	
	$score = explode(' ', trim($_GET['score']));
	if($score[1] < 1500){
		if($score[1] > $sel_score['score_jeux'])
		{
			if(!empty($sel_score))
			{
				$req = base() -> prepare('UPDATE utilisateur_jeux SET score_jeux = :score WHERE id_jeux LIKE 4 AND id_utilisateur LIKE :id');
				$req -> execute(array(
					'id' => $_SESSION['profil']['id'],
					'score' => $score[1]
				));
			}
			else
			{
				$req = base() -> prepare('INSERT INTO utilisateur_jeux (id_utilisateur, id_jeux, score_jeux) VALUES (:id, 4, :score)');
				$req -> execute(array(
					'id' => $_SESSION['profil']['id'],
					'score' => $score[1]
				));
			}
		}
	}
}

if(isset($_GET['pacman']))
{
	$sel_score = base() -> prepare('SELECT * FROM utilisateur_jeux WHERE id_utilisateur LIKE :id AND id_jeux LIKE 5');
	
	$sel_score -> execute(array('id' => $_SESSION['profil']['id']));
	
	$sel_score = $sel_score -> fetch();
	
	if($_GET['score'] > $sel_score['score_jeux'])
	{
		if(!empty($sel_score))
		{
			$req = base() -> prepare('UPDATE utilisateur_jeux SET score_jeux = :score WHERE id_jeux LIKE 5 AND id_utilisateur LIKE :id');
			$req -> execute(array(
				'id' => $_SESSION['profil']['id'],
				'score' => $_GET['score']
			));
		}
		else
		{
			$req = base() -> prepare('INSERT INTO utilisateur_jeux (id_utilisateur, id_jeux, score_jeux) VALUES (:id, 5, :score)');
			$req -> execute(array(
				'id' => $_SESSION['profil']['id'],
				'score' => $_GET['score']
			));
		}
	}
}

if(isset($_GET['2048']))
{
	$sel_score = base() -> prepare('SELECT * FROM utilisateur_jeux WHERE id_utilisateur LIKE :id AND id_jeux LIKE 6');
	
	$sel_score -> execute(array('id' => $_SESSION['profil']['id']));
	
	$sel_score = $sel_score -> fetch();
	
	if($_GET['score'] > $sel_score['score_jeux'])
	{
		if(!empty($sel_score))
		{
			$req = base() -> prepare('UPDATE utilisateur_jeux SET score_jeux = :score WHERE id_jeux LIKE 6 AND id_utilisateur LIKE :id');
			$req -> execute(array(
				'id' => $_SESSION['profil']['id'],
				'score' => $_GET['score']
			));
		}
		else
		{
			$req = base() -> prepare('INSERT INTO utilisateur_jeux (id_utilisateur, id_jeux, score_jeux) VALUES (:id, 6, :score)');
			$req -> execute(array(
				'id' => $_SESSION['profil']['id'],
				'score' => $_GET['score']
			));
		}
	}
}
?>