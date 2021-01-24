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
	*::selection{
    background-color: lightgreen;
    color: orange;
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
		$req = base()->prepare('SELECT boot.id_boot AS "idB", media_boot AS "medB", contenu_boot AS "ctnB", date_boot AS "dtB" FROM `abonnement` 
								INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur
								INNER JOIN boot ON boot.id_utilisateur = utilisateur.id_utilisateur 
                                LEFT JOIN reboot ON utilisateur.id_utilisateur = reboot.id_utilisateur
								WHERE abonnement.id_utilisateur = :id OR boot.id_utilisateur = :id OR reboot.id_utilisateur = :id
                                GROUP BY boot.id_boot DESC');
		$req->execute(array('id' => $_SESSION['profil']['id']));$i=0;
		while($res = $req->fetch()) {
			$i = $i+1;
			$i = "$i";
			echo $i;
		$date2 = strtotime($res['dtB']);
	?>

 	<div class="ui centered card">
	    <div class="content">
	      <div class="header">
	      	<img class="ui mini circular image" src="../<?= imgUtilisateur($res['idB']) ?>" /> <!-- avatar du posteur -->
	      
	      	</a><a style="margin-left: 5px;" href="traitement/traitement_profil.php?p=<?= idUtilisateur($res['idB']); ?>">
	      		<?= pseudoBoot($res['idB']) ?>
	    
	      	</a>
	      	
	      		<style>body{
    margin: 0px;
    padding: 0px;
}
#popup<? echo $i?>{
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.8); 
}
#popup_content{
    background-color:rgb(113,112,112);
    margin: auto;
    padding: 10px;
    border: 6px solid black;;
    width: 50%;
    height: 20%;
}
#popup_content form *{
 
}
#close_popup<? echo $i?>{
    float: right;
    display: block;
}
#close_popup<? echo $i?> img{
    width: 20px;
    height: 25px;
}
#close_popup<? echo $i?> img::selection{
    display: none;
}
#close_popup<? echo $i?>:hover, #close_popup:focus{
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
#signa{
	width : 40%;
}</style>
	      	
	     	 <button id="show_popup<?echo $i;?>">Signaler</button>  
        <div id="popup<? echo $i;?>" style="display:none;">
            <div id="popup_content">
                <span id="close_popup<?echo $i;?>"><img src="https://cdn.pixabay.com/photo/2013/07/12/15/37/close-150192_960_720.png"/></span><br/>
                
                <form method="POST" action="traitement_signalemet.php?id=<?=  idUtilisateur($res['idB'])?>&amp;id_boot=<? echo pseudoBoot($res['idB']) ?>&amp;id_DUboot=<? echo  $res['idB'] ?> " >
              	
                	<select  name="signa" id="signa">

                		

                		<optgroup label=" Il est suspect ou publie du spam">

                		<option value=0.85,1>Le compte qui boot est un faux compte</option>
                		<option value=0.85,2>Il inclut un lien vers un site potentiellement dangereux, malveillant ou de hameçconnage </option>
                		<option value=0.80,3>Il utilise la fonction Répondre pour Spammer </option>
                		<option value=0.80,4>Il fait d’une autre maniére</option>

                		</optgroup>

                		<optgroup label="Il contient une photo ou video sensible">

                		<option value=0.90,5>Pour adultes</option>
                		<option value=0.90,6>Violent</option>
                		<option value=0.90,7>Haineux</option>
                		<option value=0.90,8>Une photo ou vidéo non autorisées </option>

                		</optgroup>

                		<optgroup label=" Les propos tenus sont inapproprié ou dangereux">

                		<option value=0.90,9>Les propos tenus sont irrespectueux ou choquant</option>
                		<option value=0.95,10>Des informations privées y sont dévoilées</option>
                		<option value=0.75,11>Les propos incitent a la haine envers une catégorie protégée</option>
                		<option value=0.95,12>Il s’agit de harcèlement ciblée</option>
                		<option value=0.95,13>L’utilisateur menace de faire usage de la violence ou de blesser quelqu’un</option>
                		<option value=0.95,14>Il incite a l’automutilation ou au suicide </option>

                		</optgroup>

                		<optgroup label="Il induits en erreurs au sujet d’election">

                		<option value=0.90,15>Le boot comprend de fausses informations sur des élections ou l’inscription sur ces listes électorales</option>
                		<option value=0.90,16>Ce boot comprend de fausses informations suceptible de troubler l’ordre public ou d’altérer la sincériter d’un des scrutins</option>

                		</optgroup>

                		<optgroup label="Il exprime des intentions suicidaires ou autodestructices">

                		<option value="0.95,0">intentions suicidaires ou autodestructices</option>

                		</optgroup>

                	</select>
                	
                	<input type="submit" href="traitement_signalemet.php?id=<?=  idUtilisateur($res['id_boot'])?>&amp;id_boot=<? echo pseudoBoot($res['id_boot']) ?>&amp;id_DUboot=<? echo $res['idB'] ?> " >
                    
                   
                </form>
                
            </div>
        </div>



   
	        <script>
     
    var i =  <?php echo json_encode($i); ?>;
    
var popup = document.getElementById("popup"+i);
var show_popup = document.getElementById("show_popup"+i);
var close_popup = document.getElementById("close_popup"+i);

show_popup.onclick = function(){
    popup.style.display = "block";
}
close_popup.onclick = function(){
    popup.style.display = "none";
}
window.onclick = function(event){
    if(event.target == popup){
        popup.style.display = "none";
    }
}
        </script>
		
	      
	      
	      <div class="meta"><?= dateDiff($now, $date2); if(testProprioBoot($res['idB'])){?><a href="traitement/traitement_publication.php?suppBoot=<?= $res['idB'] ?>"><i class="close icon"></i></a><?php } ?>
	      
	      </div>
	      </div>
	      <div class="ui divider"></div>
	      <div class="description" style="font-size: 18px; padding-top: 5px; padding-bottom: 5px;">
	    	<?= contenuBoot($res['idB']);  ?>
	      </div>
		     <?php if(testMedia($res['idB'])){ ?>
		    		<?php if(typePJ($res['idB'])){ ?>
			    		<a href="http://booter.lcdfbtssio.fr/<?= $res['media_boot'] ?>" data-fancybox>
			    			<img src="../<?= $res['media_boot'] ?>" class="ui centered small image" /><!-- A n'afficher que s'il y a une piece jointe -->
			    		</a>
		    		<?php }else{ ?>
		    			<video src="../<?= $res['media_boot'] ?>" class="ui centered video" />
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
					
						<form method="POST" action="traitement/traitement_publication.php?like=<?= $res['idB'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="heart outline like icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbLike($res['idB']); ?>
							</div>
						</form>
					
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_publication.php?unlike=<?= $res['idB'] ?>" style="display: inline-block">
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
					
						<form method="POST" action="traitement/traitement_publication.php?save=<?= $res['idB'] ?>" style="display: inline-block">
							<div class="column">
								<button type="submit" style="border: none; background: none;">
									<i class="bookmark outline icon"></i> <!-- Quand le boot n'est pas aimé -->
								</button>
								<?= nbSave($res['idB']); ?>
							</div>
						</form>
						
					<?php }else{ ?>
					
						<form method="POST" action="traitement/traitement_publication.php?unsave=<?= $res['idB'] ?>" style="display: inline-block">
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
	?>
	
	</body>
	<?php
}
?>

<script type="text/javascript">

//var auto_refresh = setInterval(
//	function ()
//	{
//	$('#testActu').load('../script/actuBtn.php');
//}, 5000);

function actu(){
	
	$('#post').load('script/post.php');
	$('#actuReq').load('script/actuReq.php');
	document.getElementById("actuBtn").style.visibility = "hidden";
	document.getElementById("actuBtn").style.display='none';
	
}

</script>

   


