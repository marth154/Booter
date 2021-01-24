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
		$req = base()->prepare('SELECT * FROM commentaire INNER JOIN utilisateur ON commentaire.id_utilisateur = utilisateur.id_utilisateur WHERE commentaire.id_utilisateur = :id ORDER BY date_commentaire DESC');
		$req->execute(array('id' => $id));
		while($res = $req->fetch()) {
					
		$date2 = strtotime($res['date_commentaire']);
	?>

	 	
	<div class="ui centered card">
		<div class="content">
			<div class="header">
				<img class="ui mini circular image" src="<?= "avatar/".$res['pdp_utilisateur'] ?>" style="margin-right: 5px;"/>
				<a style='text-decoration: underline;'href='traitement/traitement_profil.php?p=<?= $res['id_utilisateur'] ?>'><?= $res['pseudo_utilisateur']  ?></a> a répondu :
			</div>
			<span style="margin-left: 40px;">
				<?= $res['contenu_commentaire'] ?>
			</span>
			<div class="meta">
				<span><?= dateDiff($now, $date2) ?></span>
			</div>
			<p></p>
		</div>
	</div>
	<?php
	
		}
		
	?>