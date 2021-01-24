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

	$req = base()->prepare('SELECT * FROM boot WHERE id_boot = :id');
	$req->execute(array('id' => $_SESSION['commentaire']));
	while($res = $req->fetch()) {
		
	$boot = $res['id_boot'];
	$now   = time();
	$date2 = strtotime($res['date_boot']);
	
?>

<div class="ui centered card">
	    <div class="content">
	      <div class="header">
	      	<img class="ui mini circular image" src="<?= imgUtilisateur($res['id_boot']) ?>" /> <!-- avatar du posteur -->
	      	<a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateurReboot(); ?>">
	      		<?= pseudoReboot() ?>
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

<div class="ui centered card" id="comment">
	<div class="ui threaded comments" style="width: 750px; padding-left: 10px; padding-top: 10px;">
<?php

}
$req->closeCursor();

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
		
		
const myForm = document.getElementById('comPubli');

myForm.addEventListener('submit', function(e){
	
	e.preventDefault();
	
	const formData = new FormData(this);
	
	document.getElementById('comPPost').className = "ui secondary disabled loading button";
	
	fetch('traitement/traitement_commentaire.php?postCom=<?= $boot; ?>', {
			method : 'POST',
			body: formData
		}).then(function (reponse){
			document.getElementById('comPPost').className = "ui secondary button";
			$('#comPTxt').val('');
			$('#comment').load('script/commentaire.php?post');
			return reponse.text();
		}).then(function (text){
			console.log(text);
			document.getElementById('comPPost').className = "ui secondary button";
			document.getElementById('comPtxt').val('');
		}).catch(function (error){
			console.error(error);
			document.getElementById('comPPost').className = "ui secondary button";
			document.getElementById('comPtxt').val('');
		})
		
	})
	</script>
