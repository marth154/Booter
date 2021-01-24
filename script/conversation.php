<?php

session_start();

include('../include/fonction_bdd.php');
include('../include/fonction_profil.php');
include('../include/fonction_chat.php');
	ini_set("display_errors", 1);
	error_reporting(E_ALL);

if(isset($_GET['conv'])){
	getConvMessage($_GET['conv']);
}	
if(isset($_GET['start'])){
	?>
	<div class="ui equal width grid" style="width:100%;">
		<div class="column">
		</div>
		<div class="column">
			<div style="margin-top:auto; margin-bottom:auto;">
				<img src="<?= '../'.getImgUtilisateur($_GET['start']);?>" class="ui small centered circular image" style="width:150px; height:150px;"><br>
				<center><?= getNomUtil($_GET['start']) ;?></center><br>
				<center><button class="ui primary centered button" >Commencer</button></center>
			</div>
		</div>
		<div class="column">
		</div>
	</div>
	<?php
}



?>