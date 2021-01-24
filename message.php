<?php 
include("include/header.php");
?>

<style>
    <?php require_once("css/style.css");?>
</style>

<?php

	




?>

	
</div>
<div id="container">
	
    <div id="menu">
    	<?= $_SESSION['profil']['pseudo']?>
    </div>
<!-----------------------------------------------CONVERSATION GAUCHE------------------------------------------->    
    <div id="left-col">
    	<div id="left-col-container">
			<?php get_conversation($_SESSION['profil']['id'])?>
    	</div>
    
    	
<!----------------------------------------------------MODAL---------------------------------------------------->
		<div class="modalmess">
			<div class="ui mdnew modal">	
			
				<div class="ui grid">
					
					<div class="five wide column" style="max-height:45%;">
						<?php
						$req = base()->prepare('SELECT utilisateur.id_utilisateur AS "id", pseudo_utilisateur, pdp_utilisateur FROM utilisateur INNER JOIN abonnement ON utilisateur.id_utilisateur = abonnement.id_abonnement WHERE abonnement.id_utilisateur = :id');
						$req->execute(array('id'=>$_SESSION['profil']['id']));
						while($resmod = $req->fetch()){
						?>
						<div class="ui menu"style="margin:5%;">	
					
							<a class="item group" style="width:100%;">
								<div class="ui grid "style="width:100%" onclick="startConv(<?= $resmod['id']?>)">
											<div class="six wide column" style="margin-top:auto; margin-bottom:auto; text-align:center;"><img src="<?php echo "avatar/".$resmod['pdp_utilisateur']?>" class="ui tiny circular image" style="width:35px; height:35px;"></div>
											<div class="ten wide column"style="margin-top:auto; margin-bottom:auto; text-align:center;"><?php echo $resmod['pseudo_utilisateur']?></div>
								</div>
							</a>
						
						</div>	<?php	
							}
							?>
					</div>
					<div class="eleven wide column">
						<div class="ui menu" style="margin-top:5%; height:75%; margin-right:5%;" id="start_conv">
							
						</div>
					</div>
						
				</div>
	
			</div>
		</div>
</div>	<div class="right-col" id="conv">
    	</div><!---------------------------------------------BOUTTON NOUVEAU MESSAGE----------------------------------------->
    	<div class="modalb ui grid "style="width:100%;">
				<div class="new five wide column" style="padding:0;" id="mdnew">
			    	<div class="ui menu">
						<a class="item" style="width:100%; ">
							<div class="modalb ui grid "style="width:100%;">
								<div class="ten wide column" style="margin-top:auto; margin-bottom:auto; font-weight:bold; font-size:120%;" >Nouveau Message</div>
								<div class="six wide column" ><i class="plus circle big icon" style ="float:right; margin:5%;"></i></div>
							</div>
						</a>
					</div>
				</div>
				    <div class="nine wide column" style="padding:0;" >
				    	<textarea style="width:100%; height:100%; ">Écrire votre message</textarea>
				    </div>
		    
				    <div class="two wide column" style="padding:0;" >
				    	<i class="large reply icon" style="margin-top:50%; "></i>
				    </div>
				 </div>
		</div>

					
				
    
   
   
   
   
    
</div>

<script>
	$('.mdnew.modal').modal('attach events', '#mdnew', 'show');
	function showConv(id){
		$("#conv").load("script/conversation.php?conv="+id)
		
	}
	
	function startConv(id){
		$("#start_conv").load("script/conversation.php?start="+id);
	}

</script>

