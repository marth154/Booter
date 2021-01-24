<?php

include('../include/fonction_bdd.php');

date_default_timezone_set('UTC');

ini_set("display_errors", 1);
error_reporting(E_ALL);

session_start();

if(isset($_POST['envoyer'])) 
{
	$secretKey = "6LcVgsEUAAAAANVjfpYUzcGkEXfrU1J-FRhykV2G";
	
	$responseKey = $_POST['g-recaptcha-response'];
	
	$userIP = $_SERVER['REMOTE_ADDR'];
	
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remote=$userIP";
	
	$response = file_get_contents($url);
	
	$reponse = explode(' ', $response);
	
	$success = preg_replace('/,/', '', $reponse[3]);
	
	$success = trim($success);
	
	if ($success == 'true')
	{
		
		$sel = base() -> query('SELECT * FROM utilisateur');
		
		foreach($sel as $sel)
		{
			
			if(htmlspecialchars($_POST['email']) == $sel['mail_utilisateur'])
			{
				header('Location: ../index.php?mail');
				
				exit;
			}
			if(htmlspecialchars($_POST['boot_name']) == $sel['boot_name'])
			{
				header('Location: ../index.php?bootname');
				
				exit;
			}
		}
	
		$in = base() -> prepare('INSERT INTO `utilisateur`(`nom_utilisateur`, `prenom_utilisateur`, `pseudo_utilisateur`, `boot_name`, `mail_utilisateur`, `mdp_utilisateur`, `datecrea_utilisateur`) 
				VALUES (:nom, :prenom, :pseudo, :boot_name, :mail, :mdp, :date)');
		$in -> execute(array(
				'nom' => strtoupper(htmlspecialchars($_POST['nom'])),
				'prenom' => ucfirst(htmlspecialchars($_POST['prenom'])),
				'pseudo' => htmlspecialchars($_POST['pseudo']),
				'boot_name' => htmlspecialchars($_POST['boot_name']),
				'mail' => htmlspecialchars($_POST['email']),
				'mdp' => password_hash(htmlspecialchars($_POST['pass']), PASSWORD_DEFAULT),
				'date' => date('Y-m-d')
			));
		
		
		header('Location: mail_inscription.php?mail=' . htmlspecialchars($_POST['email']));
		
		$_SESSION['etat'] = 'inscription';
	}
	else
	{
		header('Location: ../index.php?captcha');
	}
}
else
{
	header('Location: ../index.php');
}
?>