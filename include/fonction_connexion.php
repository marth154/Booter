<?php

	function affichage_connexion(){
		
	$reCaptcha = new ReCaptcha('6LcVgsEUAAAAANVjfpYUzcGkEXfrU1J-FRhykV2G'
	);
	if(isset($_POST["g-recaptcha-response"])) 
	{
	    $resp = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $_POST["g-recaptcha-response"]
	        );
		if ($resp != null && $resp->success) 
		{
			echo "OK";
		}
	    else
	    {
	    	echo "CAPTCHA incorrect";	
	    }
	}
	?>												
			
	<script type="text/javascript">
	function afficher_cacher(){
	    if(document.getElementById('texte').style.visibility=="hidden")
	    {
	        document.getElementById('texte').style.visibility="hidden";
	    }
	    else
	    {
	        document.getElementById('texte').style.visibility="visible";
	    }
	}	
	
</script>

<div class="ui three column centered grid">
	<div class="column">
		<center><img class="ui huge image" src="css/img/booter2.png"></center>
	</div>
</div>

<div class="ui placeholder segment">
	<div class="ui two column very relaxed stackable grid">
    	<div class="middle aligned column">
    		<div class="ui form" style="margin-left: 25%; width: 50%;">
      	
		        <form method="post" action="traitement/traitement_connexion.php" >
		        	
			        <div class="field">
			          <label>E-mail</label>
			          <div class="ui left icon input">
			            <input type="text" placeholder="E-mail" required name='email' pattern="[a-z0-9._%+-]{0,30}+@[a-z0-9.-]+\.[a-z]{2,}$">
			            <i class="envelope icon"></i>
			          </div>
			        </div>
			        
			        <div class="field">
			          <label>Mot de passe</label>
			          <div class="ui left icon input">
			            <input type="password" placeholder="Mot de passe" required name="pass">
			            <i class="lock icon"></i>
			          </div>
			        </div>
			        
			        <input type='submit' class="ui blue one button" name="envoyer" value="Se connecter"><br>
			        <div id="twitter"></div><br>
			        <button type="button" class="ui facebook button"><i class="facebook icon"></i>Se connecter avec Facebook</button>
			        <script>
		        		$('#twitter').load('../API/twitter_login_php');
		        	</script>
		        </form>
		        
		        <center><a style="text-decoration: underline; cursor:pointer;" id="test">Mot de passe oublié ?</a></center>
    		</div>
    	</div>
    	
	    <div class="middle aligned column">
	      <div class='ui form' style="margin-left: 25%; width: 50%;">
			<form method="post" action="traitement/traitement_inscription.php" >		
				
				<div class="field">
		          <label>Nom</label>
		          <div class="ui left icon input">
		            <input type="text" placeholder="Nom" required name="nom">
		            <i class="user icon"></i>
		          </div>
		        </div>
				
				<div class="field">
		          <label>Prénom</label>
		          <div class="ui left icon input">
		            <input type="text" placeholder="Prénom" required name="prenom">
		            <i class="user icon"></i>
		          </div>
		        </div>
			        
				<div class="field">
		          <label>Pseudo</label>
		          <div class="ui left icon input">
		            <input type="text" placeholder="Pseudo" required name="pseudo">
		            <i class="user circle icon"></i>
		          </div>
		        </div>
		        
		        <div class="field">
		          <label><span data-tooltip="Le boot name ne doit pas contenir d'espace">Boot Name * </span></label>
		          <div class="ui left icon input">
		            <input type="text" placeholder="Boot Name" required id="input" name="boot_name" pattern="[a-zA-Z0-9]*">
		            <i class="user circle icon"></i>
		          </div>
		        </div>
			        
				<div class="field">
					<label>E-mail</label>
			          <div class="ui left icon input">
			            <input type="text" placeholder="E-mail" required name='email' pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
			            <i class="envelope icon"></i>
			          </div>
		        </div>
													
				<div class="field">
		          <label>Mot de passe</label>
		          <div class="ui left icon input">
		            <input type="password" placeholder="Mot de passe" required name="pass">
		            <i class="lock icon"></i>
		          </div>
		        </div>
		        
		        <center><div class="g-recaptcha" data-sitekey="6LcVgsEUAAAAABo0KA6PST3yMdZu4He76jMPsWx3" style="margin-bottom: 20px; margin-top: 5px;"></div></center>
		        
				<input type='submit' name="envoyer" class="ui blue one button" value="S'inscrire">
			</form>
			<p style="font-size: 15px;">* Le boot name ne doit pas contenir d'espace</p>
			</div>
		</div>
    </div>
    
  <div class="ui vertical divider">
    OU
  </div>
  </div>
	<?php
	
?>

<div class="ui modal test">
  <i class="close icon"></i>
  <div class="header" style="font-size:30px;">
    Booter
  </div>
  <div class="image content">
    <div class="description">
      <div class="ui header">Mot de passe oublié ?</div>
      <p style="font-size:15px;">Entrer votre adresse mail pour réinitialiser votre mot de passe</p>
      
      <form action method="post">
      	<div class="ui basic large input">
      	<input type="text" id="mail" placeholder="Entrer votre adresse mail" style="width: 300px;">
		</div>
    </div>
  </div>
  <div class="actions">
    <div class="ui black deny button">
      Fermer
    </div>
    <a id="changer" onclick="javascript:mail();" style="color:white;">
    	<div class="ui positive right labeled icon button">
	     Changer
    		 <i class="checkmark icon"></i>
    	</div>
    </a>
    
  </div>
</div>

<script>
$(function(){
	$("#test").click(function(){
		$(".test").modal('show');
	});
	$(".test").modal({
		closable: true
	});
});

function mail()
{
	document.getElementById('changer').href = 'traitement/mail_mdp.php?mail=' + document.getElementById("mail").value; 
}

$('.activating.element').popup();
</script>
<?php
}

/*function mdp_oublie()
{
	$recup_code = "";
    
    for($i=0; $i < 8; $i++) 
	{ 
         $recup_code .= mt_rand(0,9);
    }
	$_SESSION['mdp_oublie'] = $recup_code;
}*/
?>

<style>
	.etoile:hover {
  content: 'Text affiche lors du hover';
}
</style>
