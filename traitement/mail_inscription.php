<?php

include('../include/fonction_bdd.php');


	if(isset($_GET['mail']))
	{
		$from = "mail@booter.lcdfbtssio.fr";
	 
	    $subject = "Vérification de votre e-mail de BOOTER";
	 
	    $headers = "From:" . $from."\n";
	    $headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
	    $headers .='Content-Transfer-Encoding: 8bit';
	    
	    $to = $_GET['mail'];
	 
	    $message = "
	     <html>
	      <head>
			<link rel='stylesheet' type='text/css' href='Semantic-UI-CSS-master/semantic.min.css'>
	      </head>
	    	<body>
	    		<h1>Confirmation de votre adresse mail</h1></br></br>
	    		
	    		<p>Afin de confirmer votre adresse mail et ne pas avoir de piratage cliquez sur ce bouton afin de nous confirmer que c'est bien vous.</p></br></br>
	    		
	    		<button class='ui button'><a href='http://booter.lcdfbtssio.fr/traitement/mail_inscription.php?inscription&amp;email=$to'>Valider votre adresse mail</a></button>
	    	</body>
	     </html>
	      ";
	 
	    mail($to,$subject,$message, $headers);
	   
	    
	    header('Location: ../index.php?validation');
	}
	if(isset($_GET['inscription']))
	{
		$up = base() -> prepare('UPDATE utilisateur SET inscription = 1 WHERE mail_utilisateur LIKE :mail');
		
		$up -> execute(array('mail' => $_GET['email']));
		
		header('Location: ../index.php?inscrit');
	}
