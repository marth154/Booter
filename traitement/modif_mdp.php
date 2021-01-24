<?php

include('../include/header.php');


ini_set("display_errors", 1);
error_reporting(E_ALL);

if(isset($_POST['envoyer']))
{
	$recup_code = $_SESSION['recup']['code'];
	if(htmlspecialchars($_POST['nv_pass']) == htmlspecialchars($_POST['conf_pass']))
	{
		$up = base() -> prepare('UPDATE utilisateur SET mdp_utilisateur = :mdp WHERE mail_utilisateur LIKE :mail');
		
		$up -> execute(array(
			'mail' => $_SESSION['recup']['mail'],
			'mdp' => password_hash(htmlspecialchars($_POST['nv_pass']), PASSWORD_DEFAULT)
		));
		
		
		header('Location: ../index.php?modif');
		
		echo "<script language='javascript'>window.location.href = '../index.php?modif';</script>";
	}
	else
	{
		header("Location: mail_mdp.php?recup=$recup_code");
		
		echo "<script language='javascript'>window.location.href = '../mail.php?recup=$recup_code;</script>";
	}
}
else
{
	header('Location: ../index.php');
	
	echo "<script language='javascript'>window.location.href = '../index.php';</script>";
}