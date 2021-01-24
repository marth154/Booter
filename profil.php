<?php

	include('include/header.php');
	ini_set("display_errors", 1);
	date_default_timezone_set("UTC");
	error_reporting(E_ALL);

if(isset($_SESSION['visite'])){
	
	$id = $_SESSION['visite'];
	
}else{
	
	$id = $_SESSION['profil']['id'];
	
}


	/*if(isset($_GET['modifier']))
	{
		?><script>alert('Vos informations personnelles ont été modifiées.');</script><?php
	}*/


$req = base()->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id');
$req->execute(array('id' => $id));
$res = $req->fetch();

	?>
	<img class="ui big fluid image" src="banner/<?= $res['banner_utilisateur']; ?>" style="width: 100%; height: 250px; position: absolute; z-index: 1; background: white;"/>
	<div class="ui grid" style="position: absolute; width: 100%; z-index: 0; top: 315px; background: #e3e2e0; margin: 0; padding: 0; height: 100px;">
		<div class="eigth column row" style="padding: 0; margin: 0;">
			<div class="left floated column">
				<div class="ui statistics">
					<div class="statistic">
				    	<div class="value">
				    		<?= getNbBoot($id); ?>
				    	</div>
						<div class="label">Boots </div>
					</div>
				</div>
			</div>
			<div class="left floated column">
				<div class="ui statistics" id="mdFollower" style="cursor: pointer">
					<div class="statistic">
				    	<div class="value">
				    		<?= getAbonne($id); ?>
				    	</div>
						<div class="label">Abonnés </div>
					</div>
				</div>
			</div>
			<div class="right floated column">
				 <div class="ui statistics" style=" float: right; cursor: pointer" id="mdFollow">
					<div class="statistic">
				    	<div class="value">
				    		<?= getAbonnement($id); ?>
				    	</div>
						<div class="label">Abonnements </div>
					</div>
				</div>
			</div>
			<div class="right floated column" style=" float: right">
				<?php if(!testProfil($id)){ follow($id);} ?> 
			</div>
		</div>
	</div>
	<div class="ui centered container">	  
    	<img class="ui centered medium circular image" src="avatar/<?= $res['pdp_utilisateur'] ?>" style="margin-bottom: 10px; height: 300px; width: 300px; margin-top: 100px; z-index: 2;"/>
	</div>
<div class="ui equal width grid" id="stick">
	<div class="column">
			<div class="ui vertical menu">
			<!--  style="position: absolute; font-size: 20px; width: 250px; margin: 0;" -->
				<div class="item"><?= getNomUtil($id);?>
				<div class="ui divider"></div>
					<div class="menu">
						<a class="active item" onclick="boots()" id="menuBoot">Boots</a>
						<a class="item" onclick="com()" id="menuCom">Commentaires</a>
						<a class="item" onclick="like()" id="menuLike">Likes</a>
						<a class="item" onclick="media()" id="menuMedia">Médias</a>
					</div>
				</div>
				<?php if(testProfil($id)){ ?><a class="item" href="message.php">Messages </a><?php } ?>
				<?php if(testProfil($id)){ ?><a class="item" href="modification_profil.php">Éditer mon profil</a><?php } ?>
				<?php if(testProfil($id)){ ?><a class="item" id="mdAsso">Associer mon profil</a>
					<div class="ui mdAsso modal">
						<i class="close icon"></i>
						<div class="header">
					    	Associez votre profil Booter
						</div>
						<div class="scrolling content">
							<div class="ui equal width grid">
									<div class="column">
										Twitter
									</div>
									<div class="column" id="twiAsso">
										<?php
											
											$req = base()->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id');
											$req->execute(array('id' => $_SESSION['profil']['id']));
											$res = $req->fetch();
											
											if($res['id_twitter'] == null){ 
											
											?>
											
												<button class="ui twitter button" onclick="window.location='API/twitter_login_php/index.php?asso'"><i class="twitter icon"></i>Associer</button>
												
											<?php
										
											}else{
												
												?>
												
													<button class="ui twitter button" onclick="decoTwi(<?= $_SESSION['profil']['id']; ?>)"><i class="twitter icon"></i>Déconnexion</button>
													<script>
													
														function decoTwi(idU){
															$.ajax({
														      url: 'traitement/traitement_connexion.php?decoTwi='+idU,
														      type: 'post',
														      success: function(output) 
														      {
														          location.reload();
														      }, error: function()
														      {
														          alert('something went wrong, rating failed');
														      }
														   });
														} 
													
													</script>
												<?php
												
											}
											
										?>
									</div>
									<div class="column">
										Pas encore associé
									</div>
								<div class="row">
									<div class="column">
										Facebook
									</div>
									<div class="column">
										<button class="ui primary button">Associer</button>
									</div>
									<div class="column">
										Pas encore associé
									</div>
								</div>
								<div class="row">
									<div class="column">
										Google
									</div>
									<div class="column">
										<button class="ui primary button">Associer</button>
									</div>
									<div class="column">
										Pas encore Associé
									</div>
								</div>
							</div>
						</div>
						<div class="actions">
						    <div class="ui positive right labeled icon button">
						      OK
						      <i class="checkmark icon"></i>
						    </div>
						</div>			  
					</div>
				<?php } ?>
				<div class="ui divier"></div>
				<a class="item">Badges<br>
					<div class="ui grid">
						<div class="three column row">
							<?php 
							
								$req = base()->prepare('SELECT * FROM badge_utilisateur INNER JOIN badge ON badge_utilisateur.id_badge = badge.id_badge WHERE id_utilisateur = :id');
								$req->execute(array('id' => $id));
								
								while($res = $req->fetch()){
							
							?>
								<div class="column"  data-tooltip="<?= $res['description_badge'] ?>" data-position="right center">
									<img src="badge/<?= $res['img_badge'] ?>" class="ui small image" data-fancybox href="http://booter.lcdfbtssio.fr/badge/<?= $res['img_badge'] ?>">
								</div>
							<?php
							
								}
								
							?>
						</div>
					</div>
				</a>
				<?php if(testProfil($id)){ ?>
				
					<a class="item" id="mdProgres">Progrès</a>
					<div class="ui mdProgres modal">
						<i class="close icon"></i>
						<div class="header">
					    	Votre progrès sur Booter
						</div>
						<div class="image content">
							<div class="ui equal width grid"  style="width: 100%">
								<div class="row">
									<div class="column">
										<div class="ui tiny image">
									      <img src="badge/<?= getProgressLike($id)[0] ?>">
									    </div>
									</div>
									<div class="eight wide column">
										<div class="description">
											<div class="ui header">Aimez des boots afin d'améliorer votre badge !</div>
										    <div class="ui indicating progress" data-percent="0" id="example1" style="width: 100%">
												<div class="bar" style="transition-duration: 300ms;"></div>
												<div class="label"><?= getProgressLike($id)[4]."%" ?></div>
											</div>
										</div>
									</div>
									<div class="right floated column">
										<div class="ui tiny image" style="float: right">
									      <img src="badge/<?= getProgressLike($id)[2] ?>">
									    </div>
									</div>
								</div>
								<div class="ui divider"></div>
							</div>
						</div>
						<div class="actions">
						    <div class="ui positive right labeled icon button">
						      OK
						      <i class="checkmark icon"></i>
						    </div>
						</div>			  
					</div>
				
				
				<?php } ?>
			</div>
	</div>
	<div class="eight wide column" id="post">
			<center>
				<div class="ui segment">
				  <div class="ui active loader"></div>
				  <br>
				  <br>
				  <br>
				  <br>
				</div>
			</center>
	</div>
	<div class="column" id="bioP">
		<div class="ui sticky">
			<div class="ui right floated vertical menu" style="width: 100%;">
				<div class="item">
					<div class="header">
						Biographie 
						<?php
							
							if(testProfil($id)){
								?>
									<span style="float: right" onClick="bascule('bioForm')" id="modifBio">Modifer</span>
								<?php
							}
						
						?>
					</div>
					<p>
						<form id="bioForm" style="visibility: hidden; display: none;" method="POST">
							<textarea style="width: 100%;" name="bio">
							<?= getBio($id); ?>
							</textarea>
							<br>
							<button class="ui primary button" type="submit" id="modif">Modifier</button>
						</form>
						<span id="bio">
							<?= nl2br(getBio($id)); ?>
						</span>
					</p>
				</div>
			</div>
		</div>
	</div>

	
</div>
<div class="ui tiny mdFollow modal">
  <div class="header" style="text-align: center;">Abonnements</div>
  <div class="scrolling content">
  <?php 
	
	$req = base()->prepare('SELECT * FROM abonnement INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur WHERE abonnement.id_utilisateur = :id');
	$req->execute(array('id' => $id));
	while($res = $req->fetch()){
  ?>
  <a href="traitement/traitement_profil.php?p=<?= $res['id_abonnement'] ?>" style="text-decoration: none;">
   <div class="ui segment">
   	<div class="ui equal width grid">
   		<div class="column">
   			<img class="ui circular mini image" src="<?= getImgUtil($res['id_abonnement']) ?>" style="width: 35px; height: 35px;">
   		</div>
   		<div class="column">
   			<?= getNomUtil($res['id_abonnement']); ?>
   		</div>
   	</div>
   </div>
   </a>
   <?php
	}
   ?>
  </div>
  <div class="actions">
    <div class="ui positive right labeled icon button">
      Ok
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>

<div class="ui tiny mdFollower modal">
  <div class="header" style="text-align: center;">Abonnés</div>
  <div class="scrolling content">
  <?php 
	
	$req = base()->prepare('SELECT * FROM abonnement INNER JOIN utilisateur ON abonnement.id_utilisateur = utilisateur.id_utilisateur WHERE abonnement.id_abonnement = :id');
	$req->execute(array('id' => $id));
	while($res = $req->fetch()){
  ?>
  <a href="traitement/traitement_profil.php?p=<?= $res['id_utilisateur'] ?>" style="text-decoration: none;">
   <div class="ui segment">
   	<div class="ui equal width grid">
   		<div class=" column">
			<img class="ui circular mini image" src="<?= getImgUtil($res['id_utilisateur']) ?>" style="width: 35px; height: 35px;">
   		</div>
   		<div class="column">
   			<?= getNomUtil($res['id_utilisateur']); ?>
   		</div>
   	</div>
   </div>
   </a>
   <?php
	}
   ?>
  </div>
  <div class="actions">
    <div class="ui positive right labeled icon button">
      Ok
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>
<script type="text/javascript">

if(document.getElementById("menuBoot").className == "active item"){
	
	boots();
	
}

function boots(){
	
	page = "profil.php"
	var NAME = document.getElementById("menuBoot");
	NAME.className="active item";
	var NAME = document.getElementById("menuCom");
	NAME.className="item";
	var NAME = document.getElementById("menuLike");
	NAME.className="item";
	var NAME = document.getElementById("menuMedia");
	NAME.className="item";
	
}

function com(){
	
	page ="profil_com.php"
	var NAME = document.getElementById("menuBoot");
	NAME.className="item";
	var NAME = document.getElementById("menuCom");
	NAME.className="active item";
	var NAME = document.getElementById("menuLike");
	NAME.className="item";
	var NAME = document.getElementById("menuMedia");
	NAME.className="item";
	
}

function like(){
	
	page = "profil_like.php"
	var NAME = document.getElementById("menuBoot");
	NAME.className="item";
	var NAME = document.getElementById("menuCom");
	NAME.className="item";
	var NAME = document.getElementById("menuLike");
	NAME.className="active item";
	var NAME = document.getElementById("menuMedia");
	NAME.className="item";
	
}

function media(){
	
	page = "profil_media.php"
	var NAME = document.getElementById("menuBoot");
	NAME.className="item";
	var NAME = document.getElementById("menuCom");
	NAME.className="item";
	var NAME = document.getElementById("menuLike");
	NAME.className="item";
	var NAME = document.getElementById("menuMedia");
	NAME.className="active item";
	
}

var auto_refresh = setInterval(
	function ()
	{
	$('#post').load('script/'+page);
}, 1500);

<?php if(testProfil($id)){ ?>
$('#example1').progress({
	
	percent: <?= getProgressLike($id)[4] ?>
	
});
<?php } ?>

$('.mdFollower.modal').modal('attach events', '#mdFollower', 'show');
$('.mdFollow.modal').modal('attach events', '#mdFollow', 'show');
$('.mdProgres.modal').modal('attach events', '#mdProgres', 'show');
$('.mdAsso.modal').modal('attach events', '#mdAsso', 'show');
$('.activating.element').popup();

$('.ui.sticky')
  .sticky({
    context: '#stick'
  })
;
function bascule(id) { 
	
	if (document.getElementById(id).style.display == "none"){
		
			document.getElementById(id).style.visibility = "visible";
			document.getElementById(id).style.display='block';
			document.getElementById("bio").style.visibility = "hidden";
			document.getElementById("bio").style.display='none';
			document.getElementById("modifBio").style.visibility = "hidden";
			document.getElementById("modifBio").style.display='none';
			
	}else{
		
			document.getElementById(id).style.visibility = "hidden";
			document.getElementById(id).style.display='none'; 
			document.getElementById("bio").style.visibility = "visible";
			document.getElementById("bio").style.display='block';
		
	}
	
}

const bioForm = document.getElementById('bioForm');

bioForm.addEventListener('submit', function(e){
	
	e.preventDefault();
	
	const bioFormData = new FormData(this);
	
	document.getElementById('modif').className = "ui primary disabled loading button";
	
	fetch('traitement/traitement_profil.php?bio', {
		method : 'POST',
		body: bioFormData
	}).then(function (reponse){ //Quand ça marche
		$('#bioP').load('script/profil_bio.php');
		document.getElementById('modif').className = "ui primary button";
		document.getElementById('bioForm').style.visibility = "hidden";
		document.getElementById('bioForm').style.display='none'; 
		document.getElementById("bio").style.visibility = "visible";
		document.getElementById("bio").style.display='block';
		return reponse.text();
	}).then(function (text){//Quand ça marche pas
		console.log(text);
		document.getElementById('modif').className = "ui primary button";
	}).catch(function (error){
		console.error(error);//Quand y a une erreur
		document.getElementById('modif').className = "ui primary button";
	})
	
});
</script>
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
