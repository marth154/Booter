<?php 

include('../include/fonction_bdd.php');

session_start();


$req = base() -> query('SELECT * FROM `utilisateur_jeux` 
							INNER JOIN utilisateur ON utilisateur_jeux.id_utilisateur = utilisateur.id_utilisateur
							WHERE id_jeux = 5
							ORDER BY score_jeux DESC 
							LIMIT 0,10');

foreach($req as $req)
{
	?>

		<div style="display:inline;">
			<div style=" border-top: 1px solid yellow; margin: 15px -1px 0px 10px;">
				<a style="text-decoration: none; color: yellow;  font-size: 20px;" href=<?="../traitement/traitement_profil.php?p=" . $req['id_utilisateur'];?>>
					<p>
						<?php echo "@" . $req['pseudo_utilisateur'] . ' <br>Meilleur score : ' . $req['score_jeux']; ?>
					</p>
				</a>
			</div>
		</div>
	<?php
}