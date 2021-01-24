<?php session_start() ?>


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
	
	</style>
	
	<?php
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
		$now   = time();
		$req = base()->prepare('SELECT B.id_boot AS "idB", B.date_boot AS "dtB", B.media_boot AS "medB", B.contenu_boot AS "ctnB", B.id_utilisateur AS "id_utilisateur_boot", UB.pseudo_utilisateur AS "pseudo_boot", R.id_reboot, R.date_reboot, R.contenu_reboot, UR.id_utilisateur AS "id_utilisateur_reboot", UR.pseudo_utilisateur AS "pseudo_reboot"
									FROM
									    -- On part des abonnements
									    abonnement A
									     
									        -- On prend tous les boots créés par les utilisateurs abonnés
									        INNER JOIN boot B
									            ON A.id_abonnement = B.id_utilisateur
									             
									        -- On retrouve les données des utilisateurs booters
									        INNER JOIN utilisateur UB
									            ON B.id_utilisateur = UB.id_utilisateur
									             
									        -- On récupère les reboots si il y en a
									        LEFT JOIN reboot R
									            ON B.id_boot = R.id_boot
									             
									        -- On récupère les données des utilisateurs rebooters
									        LEFT JOIN utilisateur UR
									            ON R.id_utilisateur = UR.id_utilisateur
									 
									WHERE
									    -- On ne prend que les abonnements de utilisateur connecté
									    A.id_utilisateur = :id
									    
									GROUP BY B.id_boot DESC');
		$req->execute(array('id' => $_SESSION['profil']['id']));
		while($res = $req->fetch()) {
					
		$date2 = strtotime($res['dtB']);
	?>

 	<div class="ui centered card">
	    <div class="content">
	      <div class="header">
	      	<img class="ui mini circular image" src="<?= imgUtilisateur($res['idB']) ?>" /> <!-- avatar du posteur -->
	      	<a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateur($res['id_boot']); ?>">
	      		<?= pseudoBoot($res['idB']) ?>
	      	</a>
	      	<div class="meta">
		      	<?= dateDiff($now, $date2); if(testProprioBoot($res['idB'])){?>
		      	<form id="suppForm<?= $res['idB'] ?>" style="display: inline-block" onclick="supp(<?= $res['idB'] ?>)">
		      		<button type="submit" style="border: none; background: none;"><i class="close icon"></i></button>
		      	</form>
		      	<?php } ?>
      		</div>
	      </div>
	      <div class="ui divider"></div>
	      <div class="description" style="font-size: 18px; padding-top: 5px; padding-bottom: 5px;">
	    	<?= contenuBoot($res['idB']);  ?>
	      </div>
		     <?php if(testMedia($res['idB'])){ ?>
		    		<?php if(typePJ($res['idB'])){ ?>
			    		<a href="http://booter.lcdfbtssio.fr/<?= $res['medB'] ?>" data-fancybox>
			    			<img src="<?= $res['medB'] ?>" class="ui centered small image" /><!-- A n'afficher que s'il y a une piece jointe -->
			    		</a>
		    		<?php }else{ ?>
		    			<video src="<?= $res['medB'] ?>" class="ui centered video" />
		    		<?php } ?>
		      <?php } ?>
	    </div>
		<div class="content">
			<div class="ui four column centered grid">
				<div class="seven column centered row">
					<div class="column">
						<a href="traitement/traitement_publication.php?com=<?= $res['idB'] ?>" style="text-decoration: none; color: black; display: inline-block">
							<i class="comment icon"></i>
							<?= nbCom($res['idB']); ?>
						</a>
					</div>
					
					<div class="column"></div>
<?php 
					if(!testReboot($res['idB'])) {
?>
						<form method="POST" action="traitement/traitement_publication.php?reboot=<?= $res['idB'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="retweet icon"></i>
								</button>
								<?= nbRB($res['idB']); ?>
							</div>
						</form>
<?php
					}
					
					else {
?>						
						<form method="POST" action="traitement/traitement_publication.php?unreboot=<?= $res['idB'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit"  style="border: none; background: none;" >
									<i class="retweet icon"></i>
								</button>
								<?= nbRB($res['idB']); ?>
							</div>
						</form>
<?php
					}
?>
					<div class="column"></div>
					<?php if(!testLike($res['idB'])){ ?>
					
						<form method="POST" style="display: inline-block" onclick="like(<?= $res['idB'] ?>)" id="likeForm<?= $res['idB'] ?>">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart outline icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbLike($res['idB']); ?>
							</div>
						</form>
					
					<?php }else{ ?>
					
						<form method="POST" style="display: inline-block" onclick="unlike(<?= $res['idB'] ?>)" id="unlikeForm<?= $res['idB'] ?>">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart icon"></i> <!-- Quand le boot est aimé -->
								</button>
								<?= nbLike($res['idB']); ?>
							</div>
						</form>
					
					<?php } ?>
					<div class="column"></div>
					<?php if(!testSave($res['idB'])){ ?>
					
						<form method="POST" style="display: inline-block" onclick="save(<?= $res['idB'] ?>)" id="saveForm<?= $res['idB'] ?>">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="bookmark outline icon"></i> <!-- Quand le boot n'est pas sauvegardé -->
								</button>
								<?= nbSave($res['idB']); ?>
							</div>
						</form>
						
					<?php }else{ ?>
					
						<form method="POST" style="display: inline-block" onclick="unsave(<?= $res['idB'] ?>)" id="unsaveForm<?= $res['idB'] ?>">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="bookmark icon"></i> <!-- Quand le boot est sauvegardé -->
								</button>
								<?= nbSave($res['idB']); ?>
							</div>
						</form>
					
					<?php } ?>
				</div>
			</div>
			
		</div>
		
<?php
		if(isset($_GET['reboot'])){
?>
		<div class="extra content">
			<form class="ui form" style="margin-top: 5px;" action="traitement/traitement_publication.php?postCom=<?= $res['idB']; ?>" method="POST"> <!-- formulaire de d'envoi de commentaire -->
		    	<img class="ui mini circular image" src="avatar/<?= $_SESSION['profil']['pdp'] ?>" /> <!-- avatar $_SESSION -->
			    <div class="ui large transparent input">
			    	<input type="text" placeholder="Commentaire" required name="txt" size="75">
			    </div>
		    	<button class="ui secondary button" style="float: right">ReBoot</button>
	    	</form>
	  </div>
<?php
		}
?>
	</div>
	
	<?php
	
		}
		
	?>