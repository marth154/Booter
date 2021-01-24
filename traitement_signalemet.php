<?php 
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
function base()
{
		try
	    {
	        $bdd = new PDO('mysql:host=localhost:3306;dbname=lcdfbsgr_booter;charset=utf8', 'lcdfbsgr_booter', 'Booter3630', [
	        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	        	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	        	]);
	    }
	    catch(Exception $e)
	    {
	        die('Erreur : '. $e->getMessage());
	    }
	    return $bdd;
}
	session_start(); 


$valeur=$_POST["signa"];
$e = ",";
$val = preg_split( "/[\s,]+/", $valeur);

$id_boot =(int) $_GET['id_DUboot'];

$id = $_GET['id'];

$nom_profil = $_GET['id_boot'];






$re = base()->prepare('SELECT id_utilisateur FROM utilisateur WHERE pseudo_utilisateur like  :id');
$re ->execute(array('id'=>$nom_profil));
$id_pro=$re->fetch();

$req = base()->prepare('SELECT * FROM boot WHERE id_boot = :id');
$req->execute(array('id' => $id));
$res = $req->fetch();

$signa = base()->prepare('INSERT INTO signalement (id_boot,id_signaleur,type_signalement) VALUES (:boot,:signaleur,:type)');
$signa->execute(array('boot'=>$id_boot, 'signaleur'=>$_SESSION['profil']['id'] ,'type'=>$val[1]));


function getSignalement($id,$signa){
		
		$req = base()->prepare('SELECT COUNT("type-signalement") AS "signalement" FROM signalement WHERE id_boot = :id and type_signalement = :signa');
		$req->execute(array('id' =>$id ,'signa'=> $signa));
		
		$res = $req->fetch();
		
		return $res['signalement'];
		
	}
function getAbonnement($id){
		
		$req = base()->prepare('SELECT COUNT("id_utilisateur") AS "follow" FROM abonnement WHERE id_abonnement = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['follow'];
		
	}



if(getabonnement($id)/getSignalement($id_boot,$val[1])>$val[0]){
	
	header('Location:testT.php');}
else{

	$prep=base()->prepare('UPDATE `utilisateur` SET `suspension_utilisateur`=1 WHERE id_utilisateur = :utilisateur');
	$prep->execute(array('utilisateur'=> $id ));
	
	
	header('Location:testT.php');
};




?>