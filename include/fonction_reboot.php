<?php

	function imgUtilisateurReboot(){
		
		$req = base()->prepare('SELECT pdp_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $_SESSION['profil']['id']));
		$res = $req->fetch();
		
		return "avatar/".$res['pdp_utilisateur'];
		
	}
	
		function idUtilisateurReboot(){
		
		$req = base()->prepare('SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $_SESSION['profil']['id']));
		$res = $req->fetch();
		
		return $res['id_utilisateur'];
		
	}
	
		function pseudoReboot(){
		
		$req = base()->prepare('SELECT pseudo_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $_SESSION['profil']['id']));
		$res = $req->fetch();
		
		return $res['pseudo_utilisateur'];
		
	}
?>