<?php

	include('include/header.php');
	ini_set("display_errors", 1);
	date_default_timezone_set("UTC");
	error_reporting(E_ALL);
	
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
</style>	
<?php


		$amis = base()->prepare('SELECT * FROM abonnement WHERE utilisateur.id_utilisateur = :id_utilisateur ');
		$amis->execute([
			'id_utilisateur' => $_SESSION['visite']
			]);
			
		while($res = $amis->fetch()) {

?>

<div class="ui six column divided grid">
  <div class="row">
    <div class="column">
	   <div class="ui link cards">
		  <div class="card">
		    <div class="image">
		      <img src="/images/avatar2/large/matthew.png">
		    </div>
		    <div class="content">
		      <div class="header"> <?= $res['id_abonnement']?> </div>
		      <div class="meta">
		        <a>Friends</a>
		      </div>
		      <div class="description">
		        Matthew is an interior designer living in New York.
		      </div>
		    </div>
		    <div class="extra content">
		      <span class="right floated">
		        Joined in 2013
		      </span>
		      <span>
		        <i class="user icon"></i>
		        75 Abonnements
		      </span>
		    </div>
		  </div>
	  </div>
    </div>
  </div>
</div>

<?php
}
?>