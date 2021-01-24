<?php

	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	session_start();
	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
	if(isset($_GET['p'])){
		
		$_SESSION['visite'] = $_GET['p'];
		header('Location: ../profil.php');
	}

		if(isset($_GET['like'])){
				
				$envoie = base()->prepare('INSERT INTO aimer(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
				$envoie->execute([
					'id_boot' => htmlspecialchars($_GET['like']),
					'id_utilisateur' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../profil.php');
		}
		
			if(isset($_GET['unlike'])){
				
				$envoie = base()->prepare('DELETE FROM aimer WHERE id_utilisateur = :idU AND id_boot = :idB');
				$envoie->execute([
					'idB' => htmlspecialchars($_GET['unlike']),
					'idU' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../profil.php');
		}
		
			if(isset($_GET['save'])){
				
				$envoie = base()->prepare('INSERT INTO sauvegarder(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
				$envoie->execute([
					'id_boot' => htmlspecialchars($_GET['save']),
					'id_utilisateur' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../profil.php');
		}
		
			if(isset($_GET['unsave'])){
				
				$envoie = base()->prepare('DELETE FROM sauvegarder WHERE id_utilisateur = :idU AND id_boot = :idB');
				$envoie->execute([
					'idB' => htmlspecialchars($_GET['unsave']),
					'idU' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../profil.php');
		}
		
		
		if(isset($_GET['suppBoot'])){
			
			suppBoot($_GET['suppBoot']);
			
			header('Location: ../profil.php');
			
		}


if(isset($_GET['bio'])){
	
	echo 'test';
	
	$req = base()->prepare('UPDATE utilisateur SET bio_utilisateur = :bio WHERE id_utilisateur = :id');
	$req->execute(array('bio' => htmlspecialchars($_POST['bio']), 'id' => $_SESSION['profil']['id']));
	
}


if (isset($_SESSION['profil']['id']))
{
	/*$requser = base()->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur');
	$requser->execute(array('id_utilisateur' => $_SESSION['profil']['id']));
	$user = $requser->fetch();
	
	if(isset($_POST['newPseudo']) AND !empty($_POST['newPseudo']) AND $_POST['newPseudo'] != $user['pseudo_utilisateur'])
	{	
		$newPseudo = htmlspecialchars($_POST['newPseudo']);
		$updatepseudo = base()->prepare('UPDATE utilisateur SET pseudo_utilisateur = :pseudo_utilisateur WHERE id_utilisateur = :id_utilisateur');
		$updatepseudo->execute(array('pseudo_utilisateur' => $newPseudo, 'id_utilisateur' => $_SESSION['profil']['id']));
		?>
			<script>window.location="../profil.php";</script>
			<?php
	}
	
	if(isset($_POST['newMail']) AND !empty($_POST['newMail']) AND $_POST['newMail'] != $user['mail_utilisateur'])
	{	
		$newMail = htmlspecialchars($_POST['newMail']);
		$updatemail = base()->prepare('UPDATE utilisateur SET mail_utilisateur = :mail_utilisateur WHERE id_utilisateur = :id_utilisateur');
		$updatemail->execute(array('mail_utilisateur' => $newMail, 'id_utilisateur' => $_SESSION['profil']['id']));
		?>
			<script>window.location="../profil.php";</script>
			<?php
	}
	
	if(isset($_POST['newMdp1_utilisateur']) AND !empty($_POST['newMdp1_utilisateur']) AND isset($_POST['newMdp2_utilisateur']) AND !empty($_POST['newMdp2_utilisateur'])){
		$mdp1_utilisateur = password_hash($_POST['newMdp1_utilisateur'], PASSWORD_DEFAULT);
		$mdp2_utilisateur = password_hash($_POST['newMdp2_utilisateur'], PASSWORD_DEFAULT);
		if($mdp1_utilisateur == $mdp2_utilisateur){
			$insert_mdp = base()->prepare('UPDATE utilisateur SET mdp_utilisateur = :mdp_utilisateur WHERE id_utilisateur = :id_utilisateur');
			$insert_mdp->execute(array($mdp1_utilisateur, $_SESSION['profil']['id']));
			?>
			<script>window.location="../profil.php";</script>
			<?php
			echo $mdp1_utilisateur . '/' . $mdp2_utilisateur;
		}
		else{
			$message = "Vos deux mots de passe ne correspondent pas.";
		}
	}*/
	
	if(isset($_POST['submit']))
	{
		$requser = base()->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur');
		$requser->execute(array('id_utilisateur' => $_SESSION['profil']['id']));
		$user = $requser->fetch();
		
		$newPseudo = htmlspecialchars($_POST['newPseudo']);
		$updatepseudo = base()->prepare('UPDATE utilisateur SET pseudo_utilisateur = :pseudo_utilisateur WHERE id_utilisateur = :id_utilisateur');
		$updatepseudo->execute(array('pseudo_utilisateur' => $newPseudo, 'id_utilisateur' => $_SESSION['profil']['id']));
		
		$_SESSION['profil']['pseudo'] = $newPseudo; 
		
		$newMail = htmlspecialchars($_POST['newMail']);
		$updatemail = base()->prepare('UPDATE utilisateur SET mail_utilisateur = :mail_utilisateur WHERE id_utilisateur = :id_utilisateur');
		$updatemail->execute(array('mail_utilisateur' => $newMail, 'id_utilisateur' => $_SESSION['profil']['id']));
		
		
		if(password_verify($_POST['mdp_utilisateur'], $user['mdp_utilisateur']))
		{
			$mdp1_utilisateur = password_hash($_POST['newMdp1_utilisateur'], PASSWORD_DEFAULT);
	
			if(htmlspecialchars($_POST['newMdp2_utilisateur']) == htmlspecialchars($_POST['newMdp1_utilisateur'])){
				$insert_mdp = base()->prepare('UPDATE utilisateur SET mdp_utilisateur = :mdp_utilisateur WHERE id_utilisateur = :id_utilisateur');
				$insert_mdp->execute(array('mdp_utilisateur' => $mdp1_utilisateur, 'id_utilisateur' => $_SESSION['profil']['id']));
				
				$_SESSION['profil']['mdp'] = htmlspecialchars($_POST['newMdp1_utilisateur']);
				
				?>
				<script>
				window.location="../profil.php";
				</script>
				
				<?php
			}
			else{
				$message = "Vos deux mots de passe ne correspondent pas.";
			}
		}
		
	}
}
	
	if(isset($_GET['pdp_utilisateur'])){
		echo'0';
		if(isset($_FILES['pdp_utilisateur']) AND !empty($_FILES['pdp_utilisateur']['name']))
		{
			$taille_max = 2097152;
			$extensions_valides = array('jpg', 'jpeg', 'png');
			if($_FILES['pdp_utilisateur']['size'] <= $taille_max)
			{
				$extensions_upload = strtolower(substr(strrchr($_FILES['pdp_utilisateur']['name'], '.'), 1));
				if(in_array($extensions_upload, $extensions_valides))
				{
					$chemin = "../avatar/" . $_SESSION['profil']['id'] . '.' . $extensions_upload;
					$ch = "../avatar/" . $_SESSION['profil']['id']."." ;
					for($i = 0; $i < sizeof($extensions_valides); $i++){
						
						unlink($ch.$extensions_valides[$i]);
						echo $ch.$extensions_valides[$i];
						
					}
					$resultat = move_uploaded_file($_FILES['pdp_utilisateur']['tmp_name'], $chemin);
					if($resultat)
					{
						$insert_avatar = base()->prepare('UPDATE utilisateur SET pdp_utilisateur = :pdp_utilisateur WHERE id_utilisateur = :id_utilisateur');
						$insert_avatar->execute(['pdp_utilisateur' => $_SESSION['profil']['id'] . '.' . $extensions_upload, 'id_utilisateur' => $_SESSION['profil']['id']]);
						
						?>
						<script>window.location="../profil.php";</script>
						<?php
					}
					else
					{
						$message = "Erreur pendant l\'importation de la photo";
					}
				}
				else
				{
					$message = "Votre photo de profil n\'est pas au bon format";	
				}
			}
			else
			{
				$message = "Votre photo de profil ne doit pas excédé 2 Mo";
			}
		}

	}
	
	if(isset($_GET['banner_utilisateur'])){
	
		if(isset($_FILES['banner_utilisateur']) AND !empty($_FILES['banner_utilisateur']['name']))
		{
			$taille_max = 2097152;
			$extensions_validees = array('jpg', 'jpeg', 'png');
			
			if($_FILES['banner_utilisateur']['size'] <= $taille_max)
			{
				$extensions_upload = strtolower(substr(strrchr($_FILES['banner_utilisateur']['name'], '.'), 1));
				
				if(in_array($extensions_upload, $extensions_validees))
				{
					$chemin = "../banner/" . $_SESSION['profil']['id'] . '.' . $extensions_upload;
					unlink($chemin);
					$resultat = move_uploaded_file($_FILES['banner_utilisateur']['tmp_name'], $chemin);
					
					if($resultat)
					{
						$insert_avatar = base()->prepare('UPDATE utilisateur SET banner_utilisateur = :banner_utilisateur WHERE id_utilisateur = :id_utilisateur');
						$insert_avatar->execute(['banner_utilisateur' => $_SESSION['profil']['id'] . '.' . $extensions_upload, 'id_utilisateur' => $_SESSION['profil']['id']]);
						
						?>
						<script>window.location="../profil.php";</script>
						<?php
					}
					else
					{
						$message = "Erreur pendant l\'importation de la bannière";
					}
				}
				else
				{
					$message = "Votre bannière n\'est pas au bon format";	
				}
			}
			else
			{
				$message = "Votre bannière ne doit pas excédé 2 Mo";
			}
		}
		if(isset($message))
		{
			echo $message;
		}
	}
	
	if(isset($_POST['suppression_profil'])){
		$req = base()->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id'=>$_SESSION['profil']['id']));
		?>
		<script>window.location="../deconnexion.php";</script>
		<?php
	}
	
	if(isset($_POST['suppression_pdp'])){
		$req = base()->prepare('UPDATE utilisateur SET pdp_utilisateur = "default.png" WHERE id_utilisateur = :id');
		$req->execute(array('id'=>$_SESSION['profil']['id']));
		?>
		<script>window.location="../profil.php";</script>
		<?php
	}
	
	if(isset($_POST['suppression_banner'])){
		$req = base()->prepare('UPDATE utilisateur SET banner_utilisateur = "default.svg" WHERE id_utilisateur = :id');
		$req->execute(array('id'=>$_SESSION['profil']['id']));
		?>
		<script>window.location="../profil.php";</script>
		<?php
	}


?>
