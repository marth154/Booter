<?php include('include/header.php'); ?>

<style>
		
	.ui.card,.ui.cards>.card{
		
		width: 750px;
		background-color: #e3e2e0;
		
	}
	
	.meta{
		
		float: right;
		
	}
</style>
<?php

	$now   = time();
	$req = base()->prepare('SELECT * FROM sauvegarder INNER JOIN boot ON sauvegarder.id_boot = boot.id_boot WHERE sauvegarder.id_utilisateur = :idU');
	$req->execute(array('idU' => $_SESSION['profil']['id']));
	while($res = $req->fetch()){
	$date2 = strtotime($res['date_boot']);
?>
<div class="ui centered card">
	    <div class="content">
	      <div class="header">
	      	<img class="ui mini circular image" src="<?= imgUtilisateur($res['id_boot']) ?>" /> <!-- avatar du posteur -->
	      	<a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateur($res['id_boot']); ?>">
	      		<?= pseudoBoot($res['id_boot']) ?>
	      	</a>
	      <div class="meta"><?= dateDiff($now, $date2);?></div>
	      </div>
	      <div class="ui divider"></div>
	      <div class="description">
	    	<?= contenuBoot($res['id_boot']); ?>
	      </div>
	      <?php if(testMedia($res['id_boot'])){ ?>
	    	<a href="http://booter.lcdfbtssio.fr/<?= $res['media_boot'] ?>" data-fancybox>
				<img src="<?= $res['media_boot'] ?>" class="ui centered small image" /><!-- A n'afficher que s'il y a une piece jointe -->
			</a>
	      <?php } ?>
	    </div>
		<div class="content">
			<div class="ui three column centered grid">
				<div class="seven column centered row">
					<div class="column">
						<a href="traitement/traitement_publication.php?com=<?= $res['id_boot'] ?>" style="text-decoration: none; color: black; display: block">
							<i class="comment icon"></i>
							<?= nbCom($res['id_boot']); ?>
						</a>
					</div>
					<div class="column"></div>
					<div class="column">
						<i class="redo icon"></i>
						<?= nbRB($res['id_boot']); ?>
					</div>
					<div class="column"></div>
					<?php if(!testLike($res['id_boot'])){ ?>
					
						<form method="POST" action="traitement/traitement_save.php?like=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart outline like icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbLike($res['id_boot']); ?>
							</div>
						</form>
					
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_save.php?unlike=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart icon"></i> <!-- Quand le boot est aimé -->
								</button>
								<?= nbLike($res['id_boot']); ?>
							</div>
						</form>
					
					<?php } ?>
					<div class="column"></div>
					<?php if(!testSave($res['id_boot'])){ ?>
					
						<form method="POST" action="traitement/traitement_save.php?save=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="bookmark outline icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbSave($res['id_boot']); ?>
							</div>
						</form>
						
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_save.php?unsave=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="bookmark icon"></i> <!-- Quand le boot est sauvegardé -->
								</button>
								<?= nbSave($res['id_boot']); ?>
							</div>
						</form>
					
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

<?php

}

?>