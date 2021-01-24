<script>
$(document).ready(function() {
    $('.group').click(function() {
        $('.group').removeClass('active');
        $(this).closest('.group').addClass('active')
    });
});
</script>

<!--/************************************************************************************************************************************************/-->

<?php
function get_conversation($id){
	
	
	$req1= base()->prepare('SELECT * FROM conversation WHERE id_u = :id OR id_r = :id');
	$req1->execute(array('id'=>$id));
	while($res1 = $req1->fetch()){
    	if($res1['id_r']==$_SESSION['profil']['id']){
    		?>
			<div class="ui menu">
				<a class="item group" style="width:100%;" onclick="showConv(<?= $res1['id_conv']?>)">
					<div class="ui grid "style="width:100%">
						<div class="six wide column"><img src="<?= getImgUtilisateur( $res1['id_u'])?>" class="ui tiny circular image" style="width:35px; height:35px;"></div>
						<div class="eight wide column"><?= getNomUtil($res1['id_u'])?></div>
						<div class="two wide column">1</div>
					</div>
				</a>
			</div>
			<?php
    	}else{
    		?>
			<div class="ui menu">
				<a class="item group" style="width:100%;">
					<div class="ui grid" style="width:100%;" onclick="showConv(<?= $res1['id_conv']?>)">
						<div class="five wide column"><img src="<?= getImgUtilisateur( $res1['id_r'])?>" class="ui tiny circular image" style="width:35px; height:35px;"></div>
						<div class="seven wide column"><?= getNomUtil($res1['id_r'])?></div>
						<div class="two wide column">1</div>
					</div>
				</a>
			</div>
			<?php
    	}
	}
	?>

<?php
	//SELECT DISTINCT id_receveur, id_utilisateur FROM message WHERE message.id_utilisateur = 4 OR id_receveur = 4
	
}
/************************************************************************************************************************************************/
function getImgUtilisateur($id_utilisateur){
	
	$req = base()->prepare('SELECT pdp_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
	$req->execute(array('id' => $id_utilisateur));
	$res = $req->fetch();
	
	return "avatar/".$res['pdp_utilisateur'];
	
}
/************************************************************************************************************************************************/
/*function sendMessage(){
	
}*/
/************************************************************************************************************************************************/
function getConvMessage($id_conv){

/*--------------------------------------------------recuperer message ou id = idconv------------------------------------------------------------------*/
	$req= base()->prepare('SELECT * FROM message WHERE id_conv =:id');
	$req->execute(array('id'=>$id_conv));
	$res = $req->fetch();
    $idr = $res['id_receveur'];
    $idu = $res['id_utilisateur'];
    /*--------------------------------------------------message de utilisateur------------------------------------------------------------------*/
	$req_util=base()->prepare('SELECT cotenu_message FROM message WHERE id_utilisateur = :id_u AND id_receveur = :id_r ORDER BY date_message ASC');
	$req_util->execute(array('id_u' =>$idu, 'id_r' =>$idr));
	
	/*--------------------------------------------------message de receveur------------------------------------------------------------------*/
	$req_re=base()->prepare('SELECT cotenu_message FROM message WHERE id_utilisateur = :id_r AND id_receveur = :id_u ORDER BY date_message ASC');
	$req_re->execute(array('id_u' =>$idu, 'id_r' =>$idr));
	while($res_util = $req_util->fetch()){
		if($idu == $_SESSION['profil']['id']){
			
			echo '<p style="text-align: right;">',getNomUtil($res['id_utilisateur']),'</p>';
			echo '<p style="text-align: right;">',$res_util['cotenu_message'],'</p>';
		}else{	
		echo '<p style="text-align: left;">',getNomUtil($res['id_utilisateur']),'</p>';
		echo '<p style="text-align: left;">',$res_util['cotenu_message'],'</p>';
			
		}
	
	}
	while($res_re = $req_re->fetch()){
			if($idr == $_SESSION['profil']['id']){
		echo '<p style="text-align: right;">',getNomUtil($res['id_receveur']),'</p>';
		echo '<p style="text-align: right;">',$res_re['cotenu_message'],'</p>';
			}else{
				echo '<p style="text-align: left;">',getNomUtil($res['id_receveur']),'</p>';
				echo '<p style="text-align: left;">',$res_re['cotenu_message'],'</p>';
			}
	}
}

/************************************************************************************************************************************************/

function modalpseudo($id_utilisateur1){
	
	$req = base()->prepare('SELECT * FROM utilisateur INNER JOIN abonnement ON utilisateur.id_utilisateur = abonnement.id_abonnement WHERE abonnement.id_utilisateur = :id');
	$req->execute(array('id'=>$id_utilisateur1));
	while($resmod = $req->fetch()){
		echo $resmod['pseudo_utilisateur'];
	}
}

/************************************************************************************************************************************************/

function modalimg($id_utilisateur1){
	
	$req = base()->prepare('SELECT * FROM utilisateur INNER JOIN abonnement ON utilisateur.id_utilisateur = abonnement.id_abonnement WHERE abonnement.id_utilisateur = :id');
	$req->execute(array('id'=>$id_utilisateur1));
	echo $req;
}
?>





