<?php 
	include('include/header.php'); 
	$req = base()->query('SELECT * FROM jeux');
	
	if($_SESSION['profil']['id'] ==56) { 
		echo ("Vous n'êtes pas autorisé à allez sur cette page");
	}
	
	else {
	
?>
<div class="ui grid" style="margin-top: 1%; margin-left: 1%;">
	<?php while($res = $req->fetch()){ ?>
	<div class="three wide column">
		<a href="jeux/<?= $res['lien_jeu'] ?>" style="color: black;">
			<div class="ui segment">
				<div class="ui segment">
					<img src="css/img/jeux/<?= $res['img_jeu']; ?>" class="ui medium image">
				</div>
				<h2 style="text-align: center;"><?= $res['nom_jeu'] ?></h2>
				<div class="ui divider"></div>
				<?= nl2br($res['description_jeu']) ?>
			</div>
		</a>
	</div>
	<?php } ?>
</div>
<?php } ?>