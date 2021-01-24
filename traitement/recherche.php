<?php 

include('../include/fonction_bdd.php');


if(isset($_GET['user']))
{
	$user = (String) trim($_GET['user']);
	
	$req = base() -> prepare('SELECT * FROM utilisateur WHERE UCASE(pseudo_utilisateur) LIKE UCASE(:pseudo) OR UCASE(boot_name) LIKE UCASE(:pseudo) LIMIT 5');
	
	$req -> execute(array('pseudo' => "$user%"));
	
	?>
		<div style="font-size: 25px; margin-top: 15px; margin-bottom: 10px;"><a><strong>Utilisateur</strong></a><i class="user circle icon"></i></div>
		
	<?php
	if($req -> rowCount() != 0)
	{
		foreach($req as $req)
		{
			?>
				<div style="display:inline;">
					<div style=" border-top: 1px solid black; margin: 5px -1px 0px -1px;">
						<a style="text-decoration: none; color: black;  font-size: 20px;" href=<?= "traitement/traitement_profil.php?p=" . $req['id_utilisateur'];?>>
							<p>
								<img src="<?= 'avatar/' . $req['pdp_utilisateur'];?>" class="ui image circular" style="margin-left: 10px; width: 30%; margin-top: 5px;">			
							
								<em>@<?=$req['pseudo_utilisateur'];?></em>
							</p>
						</a>
					</div>
				</div>
			<?php
		}
	}
	else
	{
		?>
			<div style="display:inline;">
				<p style="font-size: 20px;">
					Aucun utilisateur
				</p>
			</div>
		<?php
	}
	
	
	$publi = base() -> prepare('SELECT * FROM boot INNER JOIN utilisateur WHERE boot.id_utilisateur = utilisateur.id_utilisateur AND contenu_boot LIKE :boot LIMIT 5');
	
	$publi -> execute(array('boot' => "%$user%"));
	
	?>
	
		<div style="font-size: 25px; margin-top: 20px; margin-bottom: 10px;"><strong><a>Publication</a></strong><i class="edit icon"></i></div>
		
	<?php
	if ($publi -> rowCount() != 0)
	{
		foreach($publi as $publi)
		{
			?>
				<div style="display:inline;">
					<div style=" border-top: 1px solid black; margin: 0px -1px 0px -1px;">
						<a style="text-decoration: none; color: black;" href=<?= "traitement/traitement_publication.php?com=" . $publi['id_boot'];?>>
								
							<p style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 15px; margin-left: 10px; '>
								<?=$publi['contenu_boot'];?>
							</p>
							
							<p  style="white-space: nowrap; margin-bottom: 5px; margin-left: 5px; font-size: 18px; ">
								<em>@<?=$publi['pseudo_utilisateur'];?></em>
							</p>
						</a>
					</div>
				</div>
				
			<?php
		}
	}
	else
	{
		?>
			<div style="display:inline;">
				<p style=" font-size: 20px;">
					Aucune publication 
				</p>
			</div>
		<?php
	}
}
