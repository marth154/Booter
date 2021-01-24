<?php

	session_start(); 
	
	if(isset($_SESSION['profil']['id']))
	{
		include("include/header.php");
	}
	else
	{
		include('include/header_connexion.php');
	}
?>

	

<?php
if(isset($_SESSION['profil']['id']))
{

	?>
	<style>
		
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
<span id="actuReq">
<?php


	
			$req = base()->prepare('SELECT DISTINCT MAX(id_boot) AS "max" FROM `abonnement` 
									INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur
									INNER JOIN boot ON boot.id_utilisateur = utilisateur.id_utilisateur 
									WHERE abonnement.id_utilisateur = :id OR boot.id_utilisateur = :id 
									ORDER BY id_boot DESC');
			$req->execute(array('id' => $_SESSION['profil']['id']));
			$res = $req->fetch();
			$_SESSION['maxIndex'] = $res['max'];
			

?>
</span>
<span id="testActu">
	<?php
	
	if(testPubli($_SESSION['profil']['id'], $_SESSION['maxIndex'])){
			
		
			btnActu();
		
		
	}
	
	?>
</span>
<div class="ui centered card" style="width: 750px;">
	<div class="content">
	      <div class="header">
	      		<center>Publier</center>
	      </div>
	      <div class="ui divider"></div>
	      <div class="description">
		    	<form class="ui form" method="POST" action="traitement/traitement_publication.php?post" enctype="multipart/form-data">
				    <div class="field">
						<textarea style="resize : none;" name="txt" required></textarea>
					</div>
					<div class="two fields">
						<input type="file" name="post"> </input>
						<button type="submit" class="ui primary button" style="float: right;">
							Publier
						</button>
					</div>
		    	</form>
	      </div>
	 </div>
</div>


<div id="post">
<?php
		$now   = time();
		$req = base()->prepare('SELECT boot.id_boot, id_reboot,  date_boot, media_boot FROM `abonnement` 
								INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur
								INNER JOIN boot ON boot.id_utilisateur = utilisateur.id_utilisateur 
								INNER JOIN reboot ON reboot.id_utilisateur = utilisateur.id_utilisateur 
								WHERE abonnement.id_utilisateur = :id OR boot.id_utilisateur = :id OR reboot.id_utilisateur = :id
                                GROUP BY boot.id_boot DESC');
		$req->execute(array('id' => $_SESSION['profil']['id']));
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
	      <div class="meta"><?= dateDiff($now, $date2); if(testProprioBoot($res['id_boot'])){?><a href="traitement/traitement_publication.php?suppBoot=<?= $res['id_boot'] ?>"><i class="close icon"></i></a><?php } ?></div>
	      </div>
	      <div class="ui divider"></div>
	      <div class="description" style="font-size: 18px; padding-top: 5px; padding-bottom: 5px;">
	    	<?= contenuBoot($res['id_boot']);  ?>
	      </div>
		     <?php if(testMedia($res['id_boot'])){ ?>
		    		<?php if(typePJ($res['id_boot'])){ ?>
			    		<a href="http://booter.lcdfbtssio.fr/<?= $res['media_boot'] ?>" data-fancybox>
			    			<img src="<?= $res['media_boot'] ?>" class="ui centered small image" /><!-- A n'afficher que s'il y a une piece jointe -->
			    		</a>
		    		<?php }else{ ?>
		    			<video src="<?= $res['media_boot'] ?>" class="ui centered video" />
		    		<?php } ?>
		      <?php } ?>
	    </div>
		<div class="content">
			<div class="ui four column centered grid">
				<div class="seven column centered row">
					<div class="column">
						<a href="traitement/traitement_publication.php?com=<?= $res['id_boot'] ?>" style="text-decoration: none; color: black; display: inline-block">
							<i class="comment icon"></i>
							<?= nbCom($res['id_boot']); ?>
						</a>
					</div>
					
					<div class="column"></div>
<?php 
					if(!testReboot($res['id_boot'])) {
?>
						<form method="POST" action="traitement/traitement_publication.php?reboot=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="retweet icon"></i>
								</button>
								<?= nbRB($res['id_boot']); ?>
							</div>
						</form>
<?php
					}
					
					else {
?>						
						<form method="POST" action="traitement/traitement_publication.php?unreboot=<?= $res['id_boot'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit"  style="border: none; background: none;" >
									<i class="retweet icon"></i>
								</button>
								<?= nbRB($res['id_boot']); ?>
							</div>
						</form>
<?php
					}
?>
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
		
<?php
		if(isset($_GET['reboot'])){
?>
		<div class="extra content">
			<form class="ui form" style="margin-top: 5px;" action="traitement/traitement_publication.php?postCom=<?= $res['id_boot']; ?>" method="POST"> <!-- formulaire de d'envoi de commentaire -->
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
</div>
<?php
	
}
else
{
	?>
	<style>
	body{
		
		background-color: #0C1524;
		
	}
	</style>
	<body>
	<?php

	affichage_connexion();
	
	if(isset($_GET['mail']))
	{
		?><script>alert('Cette adresse mail est déjà prise veuillez en choisir une autre');</script><?php
	}
	if(isset($_GET['non_valide']))
	{
		?><script>alert('Votre compte n\'est pas validé, regardez dans votre boite mail');</script><?php
	}
	if(isset($_GET['inscrit']))
	{
		?><script>alert('Votre compte est validé, vous pouvez vous connecter');</script><?php
	}
	if(isset($_GET['validation']))
	{
		?><script>alert('Un mail de validation vous a été envoyé');</script><?php
	}
	?>
	
	</body>
	<?php
}
?>

<script type="text/javascript">

var auto_refresh = setInterval(
	function ()
	{
	$('#testActu').load('script/actuBtn.php');
}, 5000);

function actu(){
	
	$('#post').load('script/post.php');
	$('#actuReq').load('script/actuReq.php');
	document.getElementById("actuBtn").style.visibility = "hidden";
	document.getElementById("actuBtn").style.display='none';
	
}

</script>