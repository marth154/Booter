<?php session_start(); 
if(isset($_SESSION['visite'])){
	
	$id = $_SESSION['visite'];
	
}else{
	
	$id = $_SESSION['profil']['id'];
	
}
?>
	<style>
		
	body{
		
		background-color: #0C1524;
		
	}
	
	.ui.card,.ui.cards>.card{
		
		width: 750px;
		background-color: #e3e2e0;
		
	}
	
	.meta{
		
		float: right;
		
	}
	
	.card{
		
		background-color: blue;
		
	}
		
	</style>
	
	<?php
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
		$now   = time();
		$req = base()->prepare('SELECT * FROM boot WHERE id_utilisateur = :id ORDER BY id_boot DESC');
		$req->execute(array('id' => $id));
		while($res = $req->fetch()) {
					
		$date2 = strtotime($res['date_boot']);
	?>

 	<div class="ui centered card">
	    <div class="content">
	      <div class="header">
	      	<img class="ui mini circular image" src="<?= imgUtilisateur($res['id_boot']) ?>" /> <!-- avatar du posteur -->
	      	<a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateur($res['id_boot']); ?>">
	      		<?= pseudoBoot($res['id_boot']) ?>
	      	</a>
	      <div class="meta"><?= dateDiff($now, $date2); if(testProprioBoot($res['id_boot'])){?><a href="traitement/traitement_profil.php?suppBoot=<?= $res['id_boot'] ?>"><i class="close icon"></i></a><?php } ?></div>
	      </div>
	      <div class="ui divider"></div>
	      <div class="description">
	    	<?= contenuBoot($res['id_boot']); ?>
	      </div>
		     <?php if(testMedia($res['id_boot'])){ ?>
		    		<?php if(typePJ($res['id_boot'])){ ?>
		    		<a href="http://booter.lcdfbtssio.fr/<?= $res['media_boot'] ?>" data-fancybox>
		    			<img src="<?= $res['media_boot'] ?>" class="ui small image" /><!-- A n'afficher que s'il y a une piece jointe -->
		    		</a>
		    		<?php }else{ ?>
		    			<video src="<?= $res['media_boot'] ?>" class="ui video" />
		    		<?php } ?>
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
					
						<form method="POST" action="traitement/traitement_publication.php?like=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart outline like icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbLike($res['id_boot']); ?>
							</div>
						</form>
					
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_publication.php?unlike=<?= $res['id_boot'] ?>" style="display: inline-block">
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
					
						<form method="POST" action="traitement/traitement_publication.php?save=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="bookmark outline icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbSave($res['id_boot']); ?>
							</div>
						</form>
						
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_publication.php?unsave=<?= $res['id_boot'] ?>" style="display: inline-block">
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