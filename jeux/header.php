<?php 

	session_start(); 
	
	include("../include/fonction_bdd.php");
	include("../include/fonction_publication.php");
	include("../include/fonction_connexion.php");
	include("../include/fonction_save.php");
	include("../include/fonction_chat.php");
	include("../include/fonction_profil.php");
	include("../include/fonction_abonnement.php");
	include("../include/fonction_quizz.php");
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	
	if($_SESSION['profil']['id'] == 56){
		
		?>
		
		<script>
			window.location='https://booter.lcdfbtssio.fr';
		</script>
		
		<?php
		
	}
	
?>

<!DOCTYPE>

<html lang="fr">
	<head>
		
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/publication.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.min.css"/>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/page.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
		<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
		<title>Booter</title>
		<link rel="icon" href="../css/img/favicon.ico" />
		
		
	</head>
	
	<?php 
	
	if(isset($_SESSION['profil']['id']))
	{
		$notif = 0;
		
		$sel_notif = base() -> prepare('SELECT * FROM notifications WHERE id_notifier LIKE :id AND lu = 0');
		
		$sel_notif -> execute(array('id' => $_SESSION['profil']['id']));
		
		foreach($sel_notif as $sel_notif)
		{
			$notif += 1;
		}
		?>
			 <header style="z-index: 15;">
				 <div class="ui menu">
					  <div class="left menu">
						  <a href="../index.php">
						  	<img class="logo" src="../css/img/booter.svg" >
						  </a>
						  <a class="item" id="nbr_notif" href="../notification.php">Notification <?php if($notif != 0){echo "<span id='notif' class='ui label' style='font-size: 15px;'>$notif</span>";}else{}?></a>
						  <a class="item" href="../save.php">Boots sauvegardés</a>
						  <a class="item" href="../message.php">Chat</a>
						  <a class="item" href="../games.php">Jeux</a>
					  </div>
					  
					  <div class="right menu" id="recherche">
			    			<form method="GET">
				    			<div class="ui small icon input" style="margin-top:15px; margin-right: 10px;">
									<input type="text" id="search" placeholder="Recherche..." autocomplete="off" />
									<i aria-hidden="true" class="search icon"></i>
								 </div>
								 
								 <div id="result" class="result" style="border-radius: 10px; position:absolute; z-index: 3; text-overflow: ellipsis; overflow: hidden; margin-right: 10px; background:white;"></div>
							 </form>
							 
					    <script>
						 	document.getElementById('result').style.width = document.getElementById('recherche').offsetWidth;
						</script>
					    
					    
					    <div class="ui compact menu">
					    	<div class="ui simple dropdown item">
					    		Profil
					    		<i class="dropdown icon"></i>
					    		<div class="menu">
					    			<a class="item" href="../traitement/traitement_profil.php?p=<?= $_SESSION['profil']['id']; ?>">Profil</a>
					    			<a class="item" href="../deconnexion.php">Déconnexion</a>
					    		</div>
					    	</div>
					    </div>
					  </div>
				</div>
			</header>
		<?php
	}
	else
	{
		?>
			<script>window.location='../index.php';</script>
		<?php
	}
	?>
	
	
	<style>
	body{
		
		background-color: #0C1524;
		
	}
	#notif{
	    animation: Test 2s infinite;
	}
	@keyframes Test{
	    0%{opacity: 1;}
	    50%{opacity: 0;}
	    100%{opacity: 1;}
	}
	</style>
</html>


<?php

function session_visite()
{
	$_SESSION['visite'] = $_SESSION['profil']['id'];
}

if(isset($_SESSION['succesBadge'])){
	
	popupBadge($_SESSION['succesBadge']);
	unset($_SESSION['succesBadge']);
	
?>

<script>
	$('#modalBadge').modal('show');
</script>
	
<?php
	
}

?>

<script>
//document.getElementById("result").remove();

setTimeout(function() {
  //your code to be executed after 1 second

$(document).ready(function()
	{
		$('#search').keyup(function()
		{
			$('#result').html('');
			
			var user = $(this).val();
			
			if(user != "")
			{
				$.ajax({
					type: 'GET',
					url: '../traitement/recherche.php',
					data: 'user=' + encodeURIComponent(user),
					success: function(data)
					
					{
						if(data != "")
						{
							$('#result').append(data);
						}
					}
				});
			}
			
		});
	});
	

}, 1000);



function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

var auto_refresh = setInterval(
	function ()
	{
	$('#notif').load('../script/notif.php');
}, 1500);

</script>
