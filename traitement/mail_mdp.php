<?php

include('../include/fonction_bdd.php');

session_start();

if(isset($_GET['mail']))
{
	$_SESSION['recup']['mail'] = $_GET['mail'];
	
	$sel_info = base()->prepare('SELECT nom_utilisateur, prenom_utilisateur FROM utilisateur WHERE mail_utilisateur LIKE :mail');
	$sel_info -> execute(array('mail' => $_GET['mail']));
	
	$sel_info = $sel_info -> fetch();
	
	if($sel_info == false)
	{
		?>
			<script>window.location = '../index.php?maile';</script>
		<?php
		//header('Location: ../index.php');
	}
	else
	{
		$prenom = $sel_info['prenom_utilisateur'];
		$nom = $sel_info['nom_utilisateur'];
	}

	
	$recup_code = "";
    for($i=0; $i < 20; $i++)
    { 
        $recup_code .= mt_rand(0,9);
    }
    
    $sel_code = base() -> query('SELECT code_recup FROM code_recuperation');
    
    foreach($sel_code as $sel_code)
    {
    	if($sel_code['code_recup'] == $recup_code)
    	{
    		$recup_code = "";
		    for($i=0; $i < 20; $i++)
		    { 
		        $recup_code .= mt_rand(0,9);
		    }
    	}
    }
    
    $_SESSION['recup']['code'] = $recup_code;
	
	$from = "mail@booter.lcdfbtssio.fr";
 
    $subject = "Changement de mot de passe";
 
    $headers = "From:" . $from."\n";
    $headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
    $headers .='Content-Transfer-Encoding: 8bit';
    
    $to = $_GET['mail'];
 
    $message = "
     <html>
         <head>
           <title>Récupération de mot de passe - Booter</title>
           <meta charset='utf-8' />
         </head>
         <body>
           <font color='#303030';>
             <div align='center'>
               <table width='600px'>
                 <tr>
                   <td>
                     
                     <div align='center'>Bonjour <b> $prenom  $nom</b>,</div>
                     Pour renouveler votre mot de passe cliquer <a href='http://booter.lcdfbtssio.fr/traitement/mail_mdp.php?recup=$recup_code'>ici</a>.
                     A bientôt sur <a href='http://booter.lcdfbtssio.fr/'>Booter</a> !
                     
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <font size='2'>
                       Ceci est un email automatique, merci de ne pas y répondre
                     </font>
                   </td>
                 </tr>
               </table>
             </div>
           </font>
         </body>
         </html>
      ";
 
    mail($to,$subject,$message, $headers);
   
    
   //header('Location: ../index.php?mdp_oublier');
    
    echo "<script language='javascript'>window.location.href = '../index.php?mdp_oublier';</script>";
}

if(isset($_GET['recup']))
{
	if($_SESSION['recup']['code'] == $_GET['recup'])
	{
		?>
		<div class="ui four column centered grid">
			<div class="column">
				<center><a href="../index.php"><img class="ui huge image" src="../css/img/booter2.png"></a></center>
			</div>
		</div>
		
		<br><br>
		
<div class="ui placeholder segment">
	<div class="ui two column very relaxed stackable grid">
    	<div class="middle aligned column">
    		<div class="ui form">
    			<p style="font-family: 'DejaVuSans'; font-size:30px;">Modification de mot de passe</p>
				<form action="modif_mdp.php" method="POST">
					<div class="ui large icon input" style="margin-left: 10%;">
						<input type="password" name="nv_pass" placeholder="Nouveau mot de passe"  style="width:300px;">
					</div>
					
					<br><br>
					
					<div class="ui large icon input" style="margin-left: 10%;">
						<input type="password" name="conf_pass" placeholder="Confirmer votre mot de passe" style="width:300px;">
					</div>
					
					<br><br>
					
					<input class="ui large button" type="submit" name="envoyer" style="margin-left: 15%;">
				</form>
			</div>
		</div>
	</div>
</div>
		<?php
	}
}