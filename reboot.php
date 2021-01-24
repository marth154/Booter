<?php

include('include/header.php');
if(isset($_SESSION['profil']['id']))
date_default_timezone_set("UTC");

	ini_set("display_errors", 1);
	error_reporting(E_ALL);
{
	
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
	
</style>

<?php
}

	if(isset($_GET['reboot'])){
				
	$envoie = base()->prepare('INSERT INTO reboot(id_boot,id_utilisateur,date_reboot, contenu_reboot) VALUES(:id_boot,:id_utilisateur,:date, :contenu_boot)');
	$envoie->execute([
		'id_boot' => htmlspecialchars($_GET['reboot']),
		'date' => date('Y-m-d H:i:s'),
		'id_utilisateur' => $_SESSION['profil']['id'],
		'contenu_boot' => htmlspecialchars($_GET['post_reboot']) 
	]);
				
	header('Location: ../index.php');
}
	
	
	$req = base()->prepare('SELECT * FROM boot WHERE id_boot = :id');
	$req->execute(array('id' => $_SESSION['reboot']));
	while($res = $req->fetch()) {
		
		$boot = $res['id_boot'];
		$now   = time();
		$date2 = strtotime($res['date_boot']);
	
?>
<div class="ui centered card">
	<div class="content">
		<div class="header">
			<img class="ui mini circular image" src="<?= imgUtilisateurReboot() ?>" /> <!-- avatar du posteur -->
	      	<a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateur($res['id_boot']); ?>">
	      		<?= pseudoReboot($res['id_boot']) ?>
	      	</a>
		</div>
		<div class="ui divider"></div>
		<form class="ui form" id="formpubli" method="POST" enctype="multipart/form-data">
				    <div class="field">
						<textarea style="resize : none;" name="txt" id="search1" required value=""></textarea>
					</div>
					<div class="two fields">
						<input type="file" name="post_reboot" id="fileInput"> </input>
						<button type="submit" class="ui primary button" style="float: right;" id="envoi">
							Publier
						</button>
					</div>
		    	</form>
	</div>
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
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
	      <div class="description" style="font-size: 18px; padding-top: 5px; padding-bottom: 5px;">
	    	<?= contenuBoot($res['id_boot']); ?>
	      </div>
	      <?php if(testMedia($res['id_boot'])){ ?>
	    		<?php if(typePJ($res['id_boot'])){ ?>
	    			<a href="http://booter.lcdfbtssio.fr/<?= $res['media_boot'] ?>" data-fancybox>
			    		<img src="<?= $res['media_boot'] ?>" class="ui centered small image" /><!-- A n'afficher que s'il y a une piece jointe -->
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
						<a href="commentaire.php" style="text-decoration: none; color: black; display: block">
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
					
						<form method="POST" action="traitement/traitement_commentaire.php?like=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart outline like icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbLike($res['id_boot']); ?>
							</div>
						</form>
					
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_commentaire.php?unlike=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart icon"></i> <!-- Quand le boot est aimé -->
								</button>
								<?= nbLike($res['id_boot']); ?>
							</div>
						</form>
					
					<?php } ?>
				</div>
			</div>
			
		</div>
		<div class="extra content">
			<form class="ui form" style="margin-top: 5px;" method="POST" id="comPubli"> <!-- formulaire de d'envoi de commentaire -->
		    	<img class="ui mini circular image" src="avatar/<?= $_SESSION['profil']['pdp'] ?>" /> <!-- avatar $_SESSION -->
			    <div class="ui large transparent input">
			    	<input type="text" placeholder="Commentaire" required name="txt" size="75" id="comPTxt" autocomplete="off">
			    </div>
		    	<button class="ui secondary button" style="float: right" id="comPPost">OK</button>
	    	</form>
	  </div>
	</div>
	
</div>
<?php
}

?>