<?php

	include("include/header.php");
?>
	

<?php
if(isset($_SESSION['profil']['id']) || isset($_SESSION['twitter']))
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
		    	<form class="ui form" id="formpubli" method="POST" enctype="multipart/form-data">
				    <div class="field">
						<textarea style="resize : none;" name="txt" id="search1" required value=""></textarea>
					</div>
					<div class="two fields">
						<input type="file" name="post" id="fileInput"> </input>
						<button type="submit" class="ui primary button" style="float: right;" id="envoi">
							Publier
						</button>
					</div>
		    	</form>
	      </div>
	      
	      <div id="result1" class="result" style="border-radius: 10px; position:absolute; z-index: 3; margin-right: 10px; background:white; width: 20%;"></div>
	 </div>
</div>


<div id="post">
<script src="script/boot.js" type="text/javascript"></script>
<?php
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

 	<div class="ui centered card boot">
	    <div class="content">
	      <div class="header">
	      	<img class="ui mini circular image" src="<?= imgUtilisateur($res['idB']) ?>" /> <!-- avatar du posteur -->
	      	<a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateur($res['idB']); ?>">
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
									<i class="heart outline icon"  id='iconLike<?= $res['idB'] ?>'></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbLike($res['idB']); ?>
							</div>
						</form>
					
					<?php }else{ ?>
					
						<form method="POST" style="display: inline-block" onclick="unlike(<?= $res['idB'] ?>)" id="unlikeForm<?= $res['idB'] ?>">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart icon" id='iconUnlike<?= $res['idB'] ?>'></i> <!-- Quand le boot est aimé -->
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
	if(isset($_GET['mdp_oublier']))
	{
		?><script>alert('Un mail pour changer votre mot de passe vous a été envoyé');</script><?php
	}
	if(isset($_GET['modif']))
	{
		?><script>alert('Votre mot de passe a été modifié');</script><?php
	}
	if(isset($_GET['maile']))
	{
		?><script>alert('Votre e-mail n\'existe pas');</script><?php
	}
	if(isset($_GET['bootname']))
	{
		?><script>alert('Ce boot name existe déjà');</script><?php
	}
	if(isset($_GET['captcha']))
	{
		?><script>alert('La captcha n\'est pas activé');</script><?php
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

// -------------------- P U B L I C A T I O N --------------------------
const myForm = document.getElementById('formpubli');

myForm.addEventListener('submit', function(e){
	
	e.preventDefault();
	
	const formData = new FormData(this);
	
	document.getElementById('envoi').className = "ui primary disabled loading button";
	
	fetch('traitement/traitement_publication.php?post', {
		method : 'POST',
		body: formData
	}).then(function (reponse){ //Quand ça marche
		$('#post').load('script/post.php');	
		$('#actuReq').load('script/actuReq.php');
		$('textarea').val('');
		$('fileInput').val('');
		$('.boot').transition('fade left').transition('fade left');
		document.getElementById('envoi').className = "ui primary button";
		return reponse.text();
	}).then(function (text){//Quand ça marche pas
		console.log(text);
		document.getElementById('envoi').className = "ui primary button";
	}).catch(function (error){
		console.error(error);//Quand y a une erreur
		document.getElementById('envoi').className = "ui primary button";
	})
	
});


// // --------------------  MENTION // -------------------- 

$(document).ready(function()
	{
		$('#search1').keyup(function()
		{
			$('#result1').html('');
			
			var user = $(this).val();
			
			if(user != "")
			{
				$.ajax({
					type: 'GET',
					url: '../traitement/mention.php',
					data: 'user=' + encodeURIComponent(user),
					success: function(data)
					
					{
						if(data != "")
						{
							$('#result1').append(data);
						}
					}
				});
			}
			
		});
	});


</script>
