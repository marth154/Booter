<?php
		date_default_timezone_set("UTC");

	
	function liker(){

		if(isset($_POST['bouton'])) {
			
			$envoie = base()->prepare('INSERT INTO aimer(id_boot,id_utilisateur) VALUES(:id_boot, :id_utilisateur)');
			$envoie->execute([
				'id_boot' => $bouton = htmlspecialchars($_POST['bouton']),
				'id_utilisateur' => $_SESSION['profil']['id']
			]);
		}
		
		
	}
	
	function pseudoBoot($id_boot){
		
		$req = base()->prepare('SELECT * FROM boot INNER JOIN utilisateur ON boot.id_utilisateur = utilisateur.id_utilisateur WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['pseudo_utilisateur'];
		
	}
	
	function contenuBoot($id_boot){
		
		$req = base()->prepare('SELECT * FROM boot WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['contenu_boot'];
		
	}
	
	function contenuReboot($id_reboot){
		
		$req = base()->prepare('SELECT * FROM reboot WHERE id_reboot = :id');
		$req->execute(array('id' => $id_reboot));
		$res = $req->fetch();
		
		return $res['contenu_reboot'];
	}
	
	function testMedia($id_boot){
		
		$req = base()->prepare('SELECT * FROM boot WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		if(isset($res['media_boot'])){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
	}
	
	function imgUtilisateur($id_boot){
		
		$req = base()->prepare('SELECT * FROM boot INNER JOIN utilisateur ON boot.id_utilisateur = utilisateur.id_utilisateur WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return "avatar/".$res['pdp_utilisateur'];
		
	}
	
	function idUtilisateur($id_boot){
		
		$req = base()->prepare('SELECT * FROM boot INNER JOIN utilisateur ON boot.id_utilisateur = utilisateur.id_utilisateur WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['id_utilisateur'];
		
	}
	
	function nbCom($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) AS "com" FROM commentaire WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['com'];
		
	}
	
	function nbRB($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) AS "rb" FROM reboot WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['rb'];
		
	}
	
	function nbLike($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) AS "like" FROM aimer WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['like'];
		
	}
	
	function nbLikeCom($id_com){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) AS "like" FROM aimer WHERE id_commentaire = :id');
		$req->execute(array('id' => $id_com));
		$res = $req->fetch();
		
		return $res['like'];
		
	}
	
	function nbSave($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) AS "save" FROM sauvegarder WHERE id_boot = :id');
		$req->execute(array('id' => $id_boot));
		$res = $req->fetch();
		
		return $res['save'];
		
	}
	
	function testLike($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) as "test" FROM aimer WHERE id_boot = :idB and id_utilisateur = :idU');
		$req->execute(array('idB' => $id_boot,
							'idU' => $_SESSION['profil']['id']));
		
		$res = $req->fetch();
		
		if($res['test'] > 0){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
	}
	
	function testReboot($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) as "test" FROM reboot WHERE id_boot = :id_boot and id_utilisateur = :id_utilisateur');
		$req->execute(array('id_boot' => $id_boot,
							'id_utilisateur' => $_SESSION['profil']['id']));
		
		$res = $req->fetch();
		
		if($res['test'] > 0){
			
			return true;
			
		}else{
			
			return false;
			
		}
	}
	
	function testLikeCom($id_com){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) as "test" FROM aimer WHERE id_commentaire = :idC and id_utilisateur = :idU');
		$req->execute(array('idC' => $id_com,
							'idU' => $_SESSION['profil']['id']));
		
		$res = $req->fetch();
		
		if($res['test'] > 0){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
	}
	
	function testSave($id_boot){
		
		$req = base()->prepare('SELECT COUNT(id_utilisateur) as "test" FROM sauvegarder WHERE id_boot = :idB and id_utilisateur = :idU');
		$req->execute(array('idB' => $id_boot,
							'idU' => $_SESSION['profil']['id']));
		
		$res = $req->fetch();
		
		if($res['test'] > 0){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
	}
	
	function dateDiff($date1, $date2){
	    $diff = abs($date1 - $date2); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
	    $retour = array();
	 
	    $tmp = $diff;
	    $retour['second'] = $tmp % 60;
	 
	    $tmp = floor( ($tmp - $retour['second']) /60 );
	    $retour['minute'] = $tmp % 60;
	 
	    $tmp = floor( ($tmp - $retour['minute'])/60 );
	    $retour['hour'] = $tmp % 24;
	 
	    $tmp = floor( ($tmp - $retour['hour'])  /24 );
	    $retour['day'] = $tmp;
	 
	    return modifDateDiff($retour);
	}
	
	function modifDateDiff($tab){
		
		if($tab['minute'] > 0){
			
			if($tab['hour'] > 0){
				
				if($tab['day'] > 0){
					
					return $tab['day']."j";
					
				}
				
				return $tab['hour']."h ".$tab['minute']."mn";
				
			}
			
			return $tab['minute']."mn ".$tab['second']."s";
			
		}
		
		return $tab['second']."s";
		
	}
	
	function typePJ($boot){
		
		$req = base()->prepare('SELECT media_boot FROM boot WHERE id_boot = :id');
		$req->execute(array('id' => $boot));
		$res = $req->fetch();
		
		$img = $res['media_boot'][3].$res['media_boot'][4].$res['media_boot'][5];
		if($img == 'img'){
			
			return true; //image
			
		}else{
			
			return false; //vidéo
			
		}
		
	}
	
	function sousCom($id){
		
		$req = base()->prepare('SELECT * FROM commentaire INNER JOIN utilisateur ON utilisateur.id_utilisateur = commentaire.id_utilisateur WHERE id_reponse = :id');
		$req->execute(array('id' => $id));
		
		while($res = $req->fetch()){
					
		$now   = time();
		$date2 = strtotime($res['date_commentaire']); 
		$comment = "'comment".$res['id_commentaire']."'";
		
		?>
			
			<div class="comments">
				<div class="comment">
					<a class="avatar">
						<img src="<?= "avatar/".$res['pdp_utilisateur']; ?>" style="height: 35px; width: 35px">
					</a>
					<div class="content">
						<a class="author">
							<?= $res['pseudo_utilisateur'] ?>
						</a>
						<div class="metadata">
							<span class="date">
								<?= dateDiff($now, $date2);?>
							</span>
							<div class="rating">
								<?php if(testLikeCom($res['id_commentaire'])){ ?>
								<form method="POST" action="traitement/traitement_commentaire.php?unlikeCom=<?= $res['id_commentaire'] ?>" style="display: inline-block">
									<button type="submit" style="border: none; background: none;">
										<i class="heart like icon"></i>
									</button>
								</form>
									<?= nbLikeCom($res['id_commentaire']) ?> Likes
								<?php }else{ ?>
									<form method="POST" action="traitement/traitement_commentaire.php?likeCom=<?= $res['id_commentaire'] ?>" style="display: inline-block">
									<button type="submit" style="border: none; background: none;">
										<i class="heart outline like icon"></i>
									</button>
								</form> 
									<?= nbLikeCom($res['id_commentaire']) ?> Likes
								<?php } ?>
							</div>
						</div>
						<div class="text">
							<?= $res['contenu_commentaire'] ?>
						</div>
						<div class="actions">
							<a class="reply" onclick="bascule(<?= $comment ?>)">
								Répondre
							</a>
							<form class="ui form" style="margin-top: 5px; display: none; visibility: hidden;" action="traitement/traitement_commentaire.php?repCom=<?= $res['id_commentaire']; ?>" method="POST" id="comment<?= $res['id_commentaire'] ?>">
								<textarea style="width: 100%; height: 50px; resize : none;" required name="txt" ></textarea>
								<button class="ui blue labeled submit icon button" type="submit"><i class="icon edit"></i> Ajouter une réponse </button>
							</form>
						</div>
					</div>
				</div>
				<?php sousCom($res['id_commentaire']) ?>
			</div>
			
		<?php
		
		}
		
		?>
		
		
		<script type="text/javascript"> 
			function bascule(id) { 
				
				
				if (document.getElementById(id).style.display != "none"){
					
		    			document.getElementById(id).style.visibility = "hidden";
		    			document.getElementById(id).style.display='none';
						
				}else{
					
		    			document.getElementById(id).style.visibility = "visible";
		    			document.getElementById(id).style.display='block';
					
				}
				
			} 
		</script>
		
		<?php
	}
	
	function testProprioBoot($id){
		
		$req = base()->prepare('SELECT COUNT(id_boot) AS "test" FROM boot WHERE id_boot = :id AND id_utilisateur = :idU');
		$req->execute(array('id' => $id,
							'idU' => $_SESSION['profil']['id']));
							
		$res = $req->fetch();
		
		if($res['test'] < 1){
			
			return false;
			
		}else{
			
			return true;
			
		}
		
	}
	
	function testProprioCom($id){
		
		$req = base()->prepare('SELECT COUNT(id_commentaire) AS "test" FROM boot WHERE id_commentaire = :id AND id_utilisateur = :idU');
		$req->execute(array('id' => $id,
							'idU' => $_SESSION['profil']['id']));
							
		$res = $req->fetch();
		
		if($res['test'] < 1){
			
			return false;
			
		}else{
			
			return true;
			
		}
		
	}
	
	function suppBoot($id){
		
		$req = base()->prepare('DELETE FROM boot WHERE id_boot = :id');
		$req->execute(array('id' => $id));
		
		$supp_ment = base() -> prepare('DELETE FROM notifications WHERE id_boot = :id');
		$supp_ment -> execute(array('id' => $id));
		
	}
	
	function suppCom($id){
		
		$req = base()->prepare('DELETE FROM commentaire WHERE id_commentaire = :id');
		$req->execute(array('id' => $id));
		
	}
	
	function testPubli($id, $nb){
		
		$req = base()->prepare('SELECT DISTINCT MAX(id_boot) AS "max" FROM `abonnement` 
								INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur
								INNER JOIN boot ON boot.id_utilisateur = utilisateur.id_utilisateur 
								WHERE abonnement.id_utilisateur = :id OR boot.id_utilisateur = :id 
								ORDER BY id_boot DESC');
		$req->execute(array('id' => $id));
		$res = $req->fetch();
		if($res['max'] > $nb){
			
			return true;
			
		}
		
		return false;
		
	}
	
	function btnActu(){
		
		?>
				
			<button class="ui primary button" style="position: fixed; z-index : 5; display: block; visibility: visible;" onclick="actu()" id="actuBtn">
				Nouveaux boots disponnibles
			</button>
		
		<?php
		
	}
?>