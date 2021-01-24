<?php

	function testProfil($id){
		
		if($id == $_SESSION['profil']['id']){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
	}
	
	function getAbonne($id){
		
		$req = base()->prepare('SELECT COUNT("id_utilisateur") AS "follower" FROM abonnement WHERE id_abonnement = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['follower'];
		
	}
	
	function getAbonnement($id){
		
		$req = base()->prepare('SELECT COUNT("id_abonnement") AS "follow" FROM abonnement WHERE id_utilisateur = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['follow'];
		
	}
	
	function getNbBoot($id){
		
		$req = base()->prepare('SELECT COUNT("id_boot") AS "nb" FROM boot WHERE id_utilisateur = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['nb']; 
		
	}
	
	function getNomUtil($id){
		
		$req = base()->prepare('SELECT pseudo_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['pseudo_utilisateur'];
		
	}
	
	function getImgUtil($id){
		
		$req = base()->prepare('SELECT pdp_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return "avatar/".$res['pdp_utilisateur'];
		
	}
	
	function follow($id){
		
		$sel_user = base() -> prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id');
		$sel_user -> execute(array('id'=>$id));
		
		foreach($sel_user as $sel_user ){
			
			$sel_abo = base() -> prepare('SELECT * FROM abonnement WHERE id_utilisateur LIKE :use AND id_abonnement LIKE :abo');
					
			$sel_abo -> execute(array(
				'use' => $_SESSION['profil']['id'],
				'abo' => $id
				));
						
			$sel_abo = $sel_abo -> fetch();
					
			if(empty($sel_abo)){
				?>
					<button style="width: 100%; height: 100%;" class="ui violet button" onclick="window.location.href='traitement/traitement_follow.php?follow'">Follow</button>
				<?php
			}else{
				?>
					<button style="width: 100%; height: 100%;" class="ui black button" onclick="window.location.href='traitement/traitement_follow.php?unfollow'">unFollow</button>
				<?php
			}
		}
	}
	
	function popupBadge($id_badge){
		
		$req = base()->prepare('SELECT * FROM badge WHERE id_badge = :id');
		$req->execute(array('id' => $id_badge));
		$res = $req->fetch();
	?>	
	
		<div class="ui modal" id=modalBadge>
			<i class="close icon"></i>
			<div class="header">
				Nouvelle acquisition !
			</div>
			<div class="image content">
				<div class="ui medium image">
					<img src="badge/<?= $res['img_badge'] ?>">
				</div>
				<div class="description">
					<div class="ui header">Badge <?= $res['nom_badge'] ?> débloqué !</div>
					<p>Félicitations vous avez débloqué le badge <?= strtolower($res['nom_badge']); ?> !<br><?= $res['description_badge'] ?></p>
				</div>
			</div>
			<div class="actions">
				<div class="ui positive right labeled icon button">
				OK
				<i class="checkmark icon"></i>
				</div>
			</div>
		</div>
		
	<?php
	
	}
	
	function getProgressLike($id){
		
		$req = base()->prepare('SELECT * FROM badge_utilisateur INNER JOIN badge ON badge.id_badge = badge_utilisateur.id_badge WHERE id_utilisateur = :id AND badge_utilisateur.id_badge BETWEEN 4 AND 7');
		$req->execute(array('id' => $id));
		$res = $req->fetch();
		
		$tab = array();
		$tab[0] = $res['img_badge'];
		$tab[1] = $res['id_badge'];
		
		if($tab[1] == 7){
			
			$tab[2] = $tab[0];
			$tab[3] = $tab[1];
			$tab[4] = 100;
			
		}else{
			
			$req = base()->prepare('SELECT * FROM badge WHERE id_badge = :id');
			$req->execute(array('id' => $tab[1]+1));
			$res = $req->fetch();
			
			$tab[2] = $res['img_badge'];
			$tab[3] = $res['id_badge'];
			$tab[4] = $res['condition_badge'];
			
			$req = base()->prepare('SELECT COUNT(id_boot), COUNT(id_commentaire) as nb FROM aimer WHERE id_utilisateur = :id');
			$req->execute(array('id' => $id));
			$res = $req->fetch();
			
			$total = $res['COUNT(id_boot)']+$res['nb'];
			
			$tab[4] = $total / $tab[4] * 100;
			
		}
		
		return $tab;
		
	}
	
	function getBio($id){
		
		$req = base()->prepare('SELECT bio_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $id));
		$res = $req->fetch();
		
		return $res['bio_utilisateur'];
	}
	
?>