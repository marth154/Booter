<?php

	include('../include/fonction_profil.php');
	include('../include/fonction_bdd.php');
	session_start();
if(isset($_SESSION['visite'])){
	
	$id = $_SESSION['visite'];
	
}else{
	
	$id = $_SESSION['profil']['id'];
	
}
?>
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
					<p id="bioP">
						<form id="bioForm" style="visibility: hidden; display: none;">
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