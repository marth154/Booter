<?php
	session_start();
	
	include('../include/fonction_bdd.php');
	include('../include/fonction_publication.php');
	ini_set("display_errors", 1);
	date_default_timezone_set("UTC");
	error_reporting(E_ALL);
		
		if(isset($_GET['like'])){
				
				$envoie = base()->prepare('INSERT INTO aimer(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
				$envoie->execute([
					'id_boot' => htmlspecialchars($_GET['like']),
					'id_utilisateur' => $_SESSION['profil']['id']
				]);
				
				$sel_booter = base() -> prepare('SELECT * FROM boot WHERE id_boot LIKE :id');
		
				$sel_booter ->  execute(array('id' => htmlspecialchars($_GET['like'])));
				
				$sel_booter = $sel_booter -> fetch();
				
				$like = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, id_boot, like_boot) VALUES(:mentionner, :mentionneur, :boot, 1)');
									
				$like -> execute(array(
					'mentionner' => $sel_booter['id_utilisateur'],
					'boot' => htmlspecialchars($_GET['like']),
					'mentionneur' => $_SESSION['profil']['id']
				));	
				
				 $req = base()->prepare('SELECT MAX(id_badge) as "test" FROM `badge_utilisateur` WHERE id_utilisateur = :id AND id_badge BETWEEN 4 AND 7');
				 $req->execute(array('id' => $_SESSION['profil']['id']));
				 $res = $req->fetch();
				 
				 if($res['test'] == null){
				 	
				 	$req2 = base()->prepare('INSERT INTO badge_utilisateur(id_utilisateur, id_badge, date_acquisition) VALUES(:idU, :idB, :date)');
				 	$req2->execute(array('idU' => $_SESSION['profil']['id'],
				 						 'idB' => 4,
				 						 'date' => date('Y-m-d')));
				 	
				 	$_SESSION['succesBadge'] = 4;
				 	
				 	
				 }else{
				 	
				 	$req2 = base()->prepare('SELECT condition_badge, id_badge FROM badge WHERE id_badge > :nb AND id_badge+1 <= 8');
				 	$req2->execute(array('nb' => $res['test']));
				 	$res2 = $req2->fetch();
				 	
					$req3 = base()->prepare('UPDATE badge_utilisateur SET id_badge = :nId WHERE id_badge = :idB AND id_utilisateur = :idU');
					
					$req4 = base()->prepare('SELECT id_utilisateur, COUNT(id_boot) AS "cpt" FROM aimer WHERE id_utilisateur = :id HAVING COUNT(id_boot) + COUNT(id_commentaire) > :nb');
				 	switch($res2['id_badge']){
				 		
				 		case 5:
				 			
				 			$req4->execute(array('id' => $_SESSION['profil']['id'], 'nb' => 10));
				 			$res4 = $req4->fetch();
				 			
				 			if($res4['cpt'] != null){
				 				
				 				$req3->execute(array('nId' => 5, 'idB' => 4, 'idU' => $_SESSION['profil']['id']));
				 				$_SESSION['succesBadge'] = 5;
				 				
				 			}
				 			
				 			break;
				 		
				 		case 6:
				 			
				 			$req4->execute(array('id' => $_SESSION['profil']['id'], 'nb' => 100));
				 			$res4 = $req4->fetch();
				 			
				 			if($res4['cpt'] != null){
				 			
					 			$req3->execute(array('nId' => 6, 'idB' => 5, 'idU' => $_SESSION['profil']['id']));
					 			$_SESSION['succesBadge'] = 6;
					 			
				 			}
				 			
				 			break;
				 			
				 		case 7:
				 			
				 			$req4->execute(array('id' => $_SESSION['profil']['id'], 'nb' => 500));
				 			$res4 = $req4->fetch();
				 			
				 			if($res4['cpt'] != null){
				 			
					 			$req3->execute(array('nId' => 7, 'idB' => 6, 'idU' => $_SESSION['profil']['id']));
					 			$_SESSION['succesBadge'] = 7;
					 			
				 			}
				 			
				 			break;
				 		
				 	}
				 	
				 }
				
				header('Location: ../index.php');
		}
		
		if(isset($_GET['unreboot'])){
				
			$envoie = base()->prepare('DELETE FROM reboot WHERE id_utilisateur = :id_utilisateur AND id_reboot = :id_reboot');
			$envoie->execute([
				'id_reboot' => htmlspecialchars($_GET['unreboot']),
				'id_utilisateur' => $_SESSION['profil']['id']
			]);
					
			header('Location: ../index.php');
		}
		
			if(isset($_GET['unlike'])){
				
				$envoie = base()->prepare('DELETE FROM aimer WHERE id_utilisateur = :idU AND id_boot = :idB');
				$envoie->execute([
					'idB' => htmlspecialchars($_GET['unlike']),
					'idU' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../index.php');
		}
		
			if(isset($_GET['save'])){
				
				$envoie = base()->prepare('INSERT INTO sauvegarder(id_boot,id_utilisateur) VALUES(:id_boot,:id_utilisateur)');
				$envoie->execute([
					'id_boot' => htmlspecialchars($_GET['save']),
					'id_utilisateur' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../index.php');
		}
		
			if(isset($_GET['unsave'])){
				
				$envoie = base()->prepare('DELETE FROM sauvegarder WHERE id_utilisateur = :idU AND id_boot = :idB');
				$envoie->execute([
					'idB' => htmlspecialchars($_GET['unsave']),
					'idU' => $_SESSION['profil']['id']
				]);
				
				header('Location: ../index.php');
		}
		
		
		if(isset($_GET['post'])){
			
			// print_r($_POST);
			
			$req = base()->prepare('INSERT INTO boot(id_utilisateur, date_boot, contenu_boot) VALUES(:id, :date, :txt)');
			$req->execute(array('id' => $_SESSION['profil']['id'],
								'date' => date('Y-m-d H:i:s'),
								'txt' => nl2br($_POST['txt']),
								));
			
			$req->closeCursor();
			
			$req = base()->query('SELECT MAX(id_boot) as "post" FROM boot');
			$res = $req->fetch();
			
			$chars = preg_split('/ /', nl2br($_POST['txt']), -1, PREG_SPLIT_OFFSET_CAPTURE);
			
			for($i = 0; $i < sizeof($chars); $i ++)
			{
				if($chars[$i][0][0] == '$')
				{
					$pseudo = str_replace("$", "", $chars[$i][0]);
					
					$sel_id = base() -> prepare('SELECT id_utilisateur FROM utilisateur WHERE boot_name LIKE :pseudo');
					
					$sel_id -> execute(array('pseudo' => $pseudo));
					
					$sel_id = $sel_id -> fetch();
					
					if(!empty($sel_id))
					{
						$mention = base() -> prepare('INSERT INTO notifications (id_notifier, id_notifieur, id_boot, mention) VALUES(:mentionner, :mentionneur, :boot, 1)');
							
						$mention -> execute(array(
							'mentionner' => $sel_id['id_utilisateur'],
							'boot' => $res['post'],
							'mentionneur' => $_SESSION['profil']['id']
						));
					}
				}
			}
			
								
			if (isset($_FILES['post']) AND !empty($_FILES['post']['name'])) 
			  {
			  	
			  	$extensions_valides_photo = array('.jpg','.jpeg','.png', '.ico', '.gif');
					
				if(isset($_FILES['post']['name']) == $extensions_valides_photo[0] or isset($_FILES['post']['name']) == $extensions_valides_photo[1] or isset($_FILES['post']['name']) == $extensions_valides_photo[2] or isset($_FILES['post']['name']) == $extensions_valides_photo[3] or isset($_FILES['post']['name']) == $extensions_valides_photo[4])
				{
						
					if (isset($extensions_valides_photo)) {
				  		
				  		$extensions_upload_photo = strrchr($_FILES['post']['name'],'.');
				  		
				  		if (in_array($extensions_upload_photo, $extensions_valides_photo)) 
				  		{
				  			$chemin_photo = "../pj/img/".htmlspecialchars($res['post']).$extensions_upload_photo;
				  			
				  			if (move_uploaded_file($_FILES['post']['tmp_name'],$chemin_photo))
				  			{
				  				$inserer_photo = base()->prepare('UPDATE boot SET media_boot = :post WHERE id_boot = :id_boot');
				  				$inserer_photo->execute([
				  					'post' => "pj/img/".htmlspecialchars($res['post']).$extensions_upload_photo,
				  					'id_boot' => htmlspecialchars($res['post'])
				  				]);
				  			}	
				  			
				  			else 
				  			{
				  				echo "Erreur pendant l'importation de la photo post";
				  			}
				  		}
				  		
				  		else
				  		{
				  			echo "Votre photo de publication n'est pas au bon format";
				  		}
					}
				}
				  	
			  	
			  	
			  	 if (isset($_FILES['post']) AND !empty($_FILES['post']['name'])) {
			  		
			  		$extensions_valides_video = array('.mpg','.avi','.mp4','.mov');
			  		
			  		if (isset($_FILES['post']['name']) == $extensions_valides_video[0] or isset($_FILES['post']['name']) == $extensions_valides_video[1] or isset($_FILES['post']['name']) == $extensions_valides_video[2] or isset($_FILES['post']['name']) == $extensions_valides_video[3]) 
			  		{
			  			
			  			if(isset($extensions_valides_video)) {
				  			
					  		$extensions_upload_video = strrchr($_FILES['post']['name'],'.');
					  		
					  		if (in_array($extensions_upload_video, $extensions_valides_video)) {
					  			
					  			$chemin_video = "../pj/video/".htmlspecialchars($res['post']).$extensions_upload_video;	
					  			
					  			if (move_uploaded_file($_FILES['post']['tmp_name'],$chemin_video))
					  			{
					  				$inserer_video = base()->prepare('UPDATE boot SET media_boot = :post WHERE id_boot = :id_boot');
					  				$inserer_video->execute([
					  					'post' => "pj/video/".htmlspecialchars($res['post']).$extensions_upload_video,
					  					'id_boot' => htmlspecialchars($res['post'])
					  				]);
					  			}	
					  		}
					  	}
			  		}
			  	}
			  }
			
			// echo "post";
			header('Location: ../index.php');
		}
		
		
		if(isset($_GET['com'])){
			
			$_SESSION['commentaire'] = $_GET['com'];
			header('Location: ../commentaire.php');
			
		}
		
		if(isset($_GET['reboot'])) {
			
			$_SESSION['reboot'] = $_GET['reboot'];
			header('Location: ../reboot.php');
		}
		
		if(isset($_GET['suppBoot'])){
			
			suppBoot($_GET['suppBoot']);
			
			header('Location: ../index.php');
			
		}
?>