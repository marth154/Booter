<?php
session_start();
include('../include/fonction_bdd.php');
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

date_default_timezone_set("UTC");

	if(isset($_GET['post'])){

		$req = base()->prepare('INSERT INTO commentaire(id_boot, id_utilisateur, contenu_commentaire, date_commentaire) VALUES(:idB, :idU, :txt, :date)');
		$req->execute(array('idB' => htmlspecialchars($_GET['post']),
							'idU' => $_SESSION['profil']['id'],
							'txt' => htmlspecialchars($_POST['txt']),
							'date' => date('Y-m-d H:i:s')
							));
							
		header('Location: ../index.php');
	
	}


	if(isset($_GET['postCom'])){
		
		$req = base()->prepare('INSERT INTO commentaire(id_boot, id_utilisateur, contenu_commentaire, date_commentaire) VALUES(:idB, :idU, :txt, :date)');
		$req->execute(array('idB' => htmlspecialchars($_GET['postCom']),
							'idU' => $_SESSION['profil']['id'],
							'txt' => htmlspecialchars($_POST['txt']),
							'date' => date('Y-m-d H:i:s')
							));
		
		$sel_booter = base() -> prepare('SELECT * FROM boot WHERE id_boot LIKE :id');
		
		$sel_booter ->  execute(array('id' => htmlspecialchars($_GET['postCom'])));
		
		$sel_booter = $sel_booter -> fetch();
		
		$com = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, id_boot, commentaire) VALUES(:mentionner, :mentionneur, :boot, 1)');
							
		$com -> execute(array(
			'mentionner' => $sel_booter['id_utilisateur'],
			'boot' => htmlspecialchars($_GET['postCom']),
			'mentionneur' => $_SESSION['profil']['id']
		));				
		
		header('Location: ../commentaire.php');
	}

	if(isset($_GET['like'])){
			
		$envoie = base()->prepare('INSERT INTO aimer(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
		$envoie->execute([
			'id_boot' => htmlspecialchars($_GET['like']),
			'id_utilisateur' => $_SESSION['profil']['id']
		]);
		
		$sel_booter = base() -> prepare('SELECT * FROM boot WHERE id_boot LIKE :id');
		
		$sel_booter ->  execute(array('id' => htmlspecialchars($_GET['like'])));
		
		$sel_booter = $sel_booter -> fetch();
		
		$like = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, id_boot, like_boot) VALUES(:mentionner, :mentionneur, :boot, 1)');
							
		$like -> execute(array(
			'mentionner' => $sel_booter['id_utilisateur'],
			'boot' => htmlspecialchars($_GET['like']),
			'mentionneur' => $_SESSION['profil']['id']
		));	
		
		header('Location: ../commentaire.php');
	}
	
	if(isset($_GET['unlike'])){
				
		$envoie = base()->prepare('DELETE FROM aimer WHERE id_utilisateur = :idU AND id_boot = :idB');
		$envoie->execute([
			'idB' => htmlspecialchars($_GET['unlike']),
			'idU' => $_SESSION['profil']['id']
			]);
				
		header('Location: ../commentaire.php');
	}
	
	if(isset($_GET['repCom'])){
		
		$req = base()->prepare('INSERT INTO commentaire(id_reponse, id_utilisateur, contenu_commentaire, date_commentaire) VALUES(:idR, :idU, :txt, :date)');
		$req->execute(array('idR' => $_GET['repCom'],
							'idU' => $_SESSION['profil']['id'],
							'txt' => htmlspecialchars($_POST['txt']),
							'date' => date('Y-m-d H:i:s')));
							
							
		$sel_booter = base() -> prepare('SELECT * FROM commentaire WHERE id_commentaire LIKE :id');
		
		$sel_booter ->  execute(array('id' => htmlspecialchars($_GET['repCom'])));
		
		$sel_booter = $sel_booter -> fetch();
		
		$like_com = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, id_boot, rep_com) VALUES(:mentionner, :mentionneur, :boot, 1)');
							
		$like_com -> execute(array(
			'mentionner' => $sel_booter['id_utilisateur'],
			'boot' => $sel_booter['id_boot'],
			'mentionneur' => $_SESSION['profil']['id']
		));	
							
		header('Location: ../commentaire.php');
		
	}
	
	if(isset($_GET['likeCom'])){
			
		$envoie = base()->prepare('INSERT INTO aimer(id_commentaire,id_utilisateur) VALUES(:id_commentaire,:id_utilisateur)');
		$envoie->execute([
			'id_commentaire' => htmlspecialchars($_GET['likeCom']),
			'id_utilisateur' => $_SESSION['profil']['id']
		]);
		
		$sel_booter = base() -> prepare('SELECT * FROM commentaire WHERE id_commentaire LIKE :id');
		
		$sel_booter ->  execute(array('id' => htmlspecialchars($_GET['likeCom'])));
		
		$sel_booter = $sel_booter -> fetch();
		
		$like_com = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, id_boot, like_com) VALUES(:mentionner, :mentionneur, :boot, 1)');
							
		$like_com -> execute(array(
			'mentionner' => $sel_booter['id_utilisateur'],
			'boot' => $sel_booter['id_boot'],
			'mentionneur' => $_SESSION['profil']['id']
		));	
			
		header('Location: ../commentaire.php');
	}
	
	if(isset($_GET['unlikeCom'])){
				
		$envoie = base()->prepare('DELETE FROM aimer WHERE id_utilisateur = :idU AND id_commentaire = :idC');
		$envoie->execute([
			'idC' => htmlspecialchars($_GET['unlikeCom']),
			'idU' => $_SESSION['profil']['id']
			]);
				
		header('Location: ../commentaire.php');
	}
	
?>