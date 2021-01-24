<?php

	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
	session_start();

if(isset($_GET['post'])){
	
	?>

		<div class="ui centered card" id="comment">
			<div class="ui threaded comments" style="width: 750px; padding-left: 10px; padding-top: 10px;">
		<?php
		
		$req = base()->prepare('SELECT * FROM commentaire INNER JOIN utilisateur ON utilisateur.id_utilisateur = commentaire.id_utilisateur WHERE id_boot = :id AND id_reponse IS NULL');
		$req->execute(array('id' => $_SESSION['commentaire']));
		
		while($res = $req->fetch()){
		
		$now   = time();
		$date2 = strtotime($res['date_commentaire']);
		 
		$comment = "'comment".$res['id_commentaire']."'";
		
		?>
			<div class="comment">
				<a class="avatar">
					<img src="<?= "avatar/".$res['pdp_utilisateur']; ?>" style="height: 35px; width: 35px">
				</a>
				<div class="content">
					<a class="author" href="traitement/traitement_profil.php?p=<?= $res['id_utilisateur'] ?>">
						<?= $res['pseudo_utilisateur']; ?>
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
						<p>
							<?= $res['contenu_commentaire'] ?>
						</p>
					</div>
					<div class="actions">
						<a class="reply" onclick="bascule(<?= $comment ?>)" id="reply">
							Répondre
						</a>
					</div>
					<form class="ui form" style="margin-top: 5px; display: none; visibility: hidden;" action="traitement/traitement_commentaire.php?repCom=<?= $res['id_commentaire']; ?>" method="POST" id="comment<?= $res['id_commentaire'] ?>">
						<textarea style="width: 100%; height: 50px; resize : none;" required name="txt" ></textarea>
						<button class="ui blue labeled submit icon button" type="submit"><i class="icon edit"></i> Ajouter une réponse </button>
					</form>
				</div>
				
				<?php sousCom($res['id_commentaire']) ?>
				
			</div>
		
		<?php
		
		}
		
		?>
			</div>
		</div>
	
	
	<?php
	
}

?>