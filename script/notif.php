<?php

include('../include/fonction_bdd.php');

session_start();

$notif = 0;
		
$sel_notif = base() -> prepare('SELECT * FROM notifications WHERE id_notifier LIKE :id AND lu = 0');

$sel_notif -> execute(array('id' => $_SESSION['profil']['id']));

foreach($sel_notif as $sel_notif)
{
	$notif += 1;
}
if($notif != 0){echo "!";}?>