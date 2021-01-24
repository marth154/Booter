<?php
	session_start();
	include('../include/fonction_bdd.php');
		if(isset($_GET['like'])){
				
				$envoie = base()->prepare('INSERT INTO aimer(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
				$envoie->execute([
					'id_boot' => htmlspecialchars($_GET['like']),
					'id_utilisateur' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../save.php');
		}
		
			if(isset($_GET['unlike'])){
				
				$envoie = base()->prepare('DELETE FROM aimer WHERE id_utilisateur = :idU AND id_boot = :idB');
				$envoie->execute([
					'idB' => htmlspecialchars($_GET['unlike']),
					'idU' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../save.php');
		}
		
			if(isset($_GET['save'])){
				
				$envoie = base()->prepare('INSERT INTO sauvegarder(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
				$envoie->execute([
					'id_boot' => htmlspecialchars($_GET['save']),
					'id_utilisateur' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../save.php');
		}
		
			if(isset($_GET['unsave'])){
				
				$envoie = base()->prepare('DELETE FROM sauvegarder WHERE id_utilisateur = :idU AND id_boot = :idB');
				$envoie->execute([
					'idB' => htmlspecialchars($_GET['unsave']),
					'idU' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../save.php');
		}


?>