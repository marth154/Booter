<?php 
	include('../include/fonction_bdd.php');

	$req = base()->prepare('SELECT pseudo_utilisateur, score_jeux, pdp_utilisateur FROM `utilisateur_jeux` 
							INNER JOIN utilisateur ON utilisateur_jeux.id_utilisateur = utilisateur.id_utilisateur
							WHERE id_jeux = :id
							ORDER BY score_jeux DESC 
							LIMIT 0,5');
	$req->execute(array('id' => $_GET['jeu']));
?>


<div class="ui vertical menu" id="scoreboard">
		<h3 class="ui centered header">
			Meilleurs joueurs
		</h3>
			<?php
				
				while($res = $req->fetch()){
			
			?>
			 <div class="ui segment">
			   	<div class="ui equal width grid">
			   		<div class="column">
			   			<img class="ui circular mini image" src="../avatar/<?= $res['pdp_utilisateur'] ?>" style="width: 35px; height: 35px;">
			   		</div>
			   		<div class="column">
			   			<?= $res['pseudo_utilisateur'] ?>
			   		</div>
			   		<div class="column">
			   			<?= $res['score_jeux'] ?>
			   		</div>
			   	</div>
			 </div>
			<?php
			
				}
			
			?>
	</div>