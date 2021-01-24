<?php

include('../include/fonction_bdd.php');

session_start();

	ini_set("display_errors", 1);
	error_reporting(E_ALL);

if(isset($_GET['decoTwi'])){
	
	$req = base()->prepare('UPDATE utilisateur SET id_twitter = null WHERE id_utilisateur = :id');
	$req->execute(array('id' => $_SESSION['profil']['id']));
	
}


if(isset($_POST['envoyer'])) {

	$email = htmlspecialchars($_POST['email']);

	$reponse = base()->prepare("SELECT * FROM utilisateur WHERE mail_utilisateur = :email");

	$reponse->execute(array(
		'email' => $email
		));
	
	if($reponse->rowCount() != 0)
	{
		foreach($reponse as $donnees)
		{
			if($donnees['inscription'] == 0)
			{
				header('Location: ../index.php?non_valide');
			}
			
			if(password_verify( $_POST['pass'], $donnees['mdp_utilisateur']))
			{
					$_SESSION['profil']['id'] = $donnees['id_utilisateur'];
					$_SESSION['profil']['prenom'] = $donnees['prenom_utilisateur'];
					$_SESSION['profil']['nom'] = $donnees['nom_utilisateur'];
					$_SESSION['profil']['mdp'] = htmlspecialchars($_POST['pass']);
					$_SESSION['profil']['pseudo'] = $donnees['pseudo_utilisateur'];
					$_SESSION['profil']['naissance'] = $donnees['naissance_utilisateur'];
					$_SESSION['profil']['pdp'] = $donnees['pdp_utilisateur'];
					$_SESSION['profil']['banner'] = $donnees['banner_utilisateur'];
					$_SESSION['profil']['bio'] = $donnees['bio_utilisateur'];
					$_SESSION['profil']['datecrea'] = $donnees['datecrea_utilisateur'];
					$_SESSION['profil']['suspension'] = $donnees['suspension_utilisateur'];
					
					$_SESSION['etat'] = 'connecte';
					header('Location:../index.php');
			}
			else
			{
				header('Location:../index.php');
				$_SESSION['etat'] = 'mdp';
			}
		}
	}
	else
	{
		header('Location: ../index.php');
	}
	
}elseif(isset($_SESSION['twitter']) && !isset($_GET['twitterAsso'])){
	
	$req = base()->prepare('SELECT * FROM utilisateur WHERE id_twitter = :id');
	$req->execute(array('id' => $_SESSION['twitter']['id']));
	$donnees = $req->fetch();
	
	if($donnees['id_utilisateur'] == null){
		
		$req = base()->prepare('UPDATE utilisateur SET id_twitter = :id WHERE mail_utilisateur = :mail');
		$req->execute(array('mail' => $_SESSION['twitter']['mail'], 'id' => $_SESSION['twitter']['id']));
		
		$req = base()->prepare('SELECT * FROM utilisateur WHERE id_twitter = :id');
		$req->execute(array('id' => $_SESSION['twitter']['id']));
		$donnees = $req->fetch();
		
	}
	
	$_SESSION['profil']['id'] = $donnees['id_utilisateur'];
	$_SESSION['profil']['prenom'] = $donnees['prenom_utilisateur'];
	$_SESSION['profil']['nom'] = $donnees['nom_utilisateur'];
	$_SESSION['profil']['pseudo'] = $donnees['pseudo_utilisateur'];
	$_SESSION['profil']['naissance'] = $donnees['naissance_utilisateur'];
	$_SESSION['profil']['pdp'] = $donnees['pdp_utilisateur'];
	$_SESSION['profil']['banner'] = $donnees['banner_utilisateur'];
	$_SESSION['profil']['bio'] = $donnees['bio_utilisateur'];
	$_SESSION['profil']['datecrea'] = $donnees['datecrea_utilisateur'];
	$_SESSION['profil']['suspension'] = $donnees['suspension_utilisateur'];
	
	$_SESSION['etat'] = 'connecte';
	unset($_SESSION['twitter']);
	header('Location:../index.php');
	
}elseif(isset($_GET['twitterAsso'])){
	
	$req = base()->prepare('UPDATE utilisateur SET id_twitter = :id WHERE id_utilisateur = :idU');
	$req->execute(array('id' => $_SESSION['twitter']['id'],
						'idU' => $_SESSION['profil']['id']));
	
	header('Location: ../profil.php');
}else{

	$_SESSION['etat'] = 'post';
	header('Location:../index.php');
}
?>