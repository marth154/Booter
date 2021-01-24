<?php
include('include/header.php');
	$requser = base()->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur');
	$requser->execute(array('id_utilisateur' => $_SESSION['profil']['id']));
	$user = $requser->fetch();

?>

<html>
	<head>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	</head>
	<body>
		<div class="container" style="padding-top: 60px;">
			<h1 class="page-header">Modification du profil</h1>
				<div class="col-md-8 col-sm-6 col-xs-12 personal-info">
    				<form class="form-horizontal" role="form" action="traitement/traitement_profil.php" method="POST">
        				<div class="form-group">
        					<label class="col-lg-3 control-label">Nouveau pseudo:</label>
        					<div class="col-lg-8">
            					<input class="form-control" value="<?php echo $user['pseudo_utilisateur'] ?>" type="text" name="newPseudo">
        					</div>
        				</div>
        				<hr width="602px" color="white">
        				<div class="form-group">
        					<label class="col-lg-3 control-label">Nouveau mail:</label>
        					<div class="col-lg-8">
            					<input class="form-control" value="<?php echo $user['mail_utilisateur'] ?>" type="email" name="newMail">
        					</div>
        				</div>
        				<hr width="602px" color="white">
        				<div class="form-group">
        					<label class="col-md-3 control-label">Mot de passe actuel:</label>
        					<div class="col-md-8">
            					<input class="form-control" type="password" name="mdp_utilisateur">
        					</div>
        				</div>
        				<hr width="602px" color="white">
        				<div class="form-group">
        					<label class="col-md-3 control-label">Nouveau mot de passe:</label>
        					<div class="col-md-8">
            					<input class="form-control" type="password" name="newMdp1_utilisateur">
        					</div>
        				</div>
        				<hr width="602px" color="white">
        				<div class="form-group">
        					<label class="col-md-3 control-label">Confirmation nouveau mot de passe:</label>
        					<div class="col-md-8">
            					<input class="form-control" type="password" name="newMdp2_utilisateur">
        					</div>
        				</div>
        				<div class="form-group">
        					<label class="col-md-3 control-label"></label>
        					<div class="col-md-8">
            					<input class="btn btn-primary" name="submit" value="Mettre à jour" type="submit" onclick="return confirm('Êtes-vous sûr de votre choix ?')">
            					<span></span>
            					<button class="btn btn-default" href="../profil.php">Annuler</button>
        					</div>
        				</div>
    				</form>
    			</div>
    			<div class="col-md-8 col-sm-6 col-xs-12 personal-info">
    				<form method="POST" action="traitement/traitement_profil.php?pdp_utilisateur" enctype="multipart/form-data">
    					<div class="form-group">
        					<label class="col-md-3 control-label">Nouvelle photo de profil:</label>
        					<div class="col-md-8">
            					<input class="parcours" type="file" name="pdp_utilisateur">
            					<button type="submit" class="btn btn-primary" style="float: right;" onclick="return confirm('Êtes-vous sûr de votre choix ?')">
									Modifier
								</button>
								<button type="submit" class="btn btn-danger" name="suppression_pdp" style="float: right;" onclick="return confirm('Êtes-vous sûr de votre choix ?')">
									Supprimer
								</button>
        					</div>
        				</div>
        			</form>
        		</div>
        		<div class="col-md-8 col-sm-6 col-xs-12 personal-info">
        			<form method="POST" action="traitement/traitement_profil.php?banner_utilisateur" enctype="multipart/form-data">
        				<div class="form-group">
        					<label class="col-md-3 control-label">Nouvelle bannière:</label>
        					<div class="col-md-8">
            					<input class="parcours" type="file" name="banner_utilisateur">
            					<button type="submit" class="btn btn-primary" style="float: right;" onclick="return confirm('Êtes-vous sûr de votre choix ?')">
									Modifier
								</button>
								<button type="submit" class="btn btn-danger" name="suppression_banner" style="float: right;" onclick="return confirm('Êtes-vous sûr de votre choix ?')">
									Supprimer
								</button>
        					</div>
        				</div>
        			</form>
				</div>
				<div class="col-md-8 col-sm-6 col-xs-12 personal-info">
					<form action="traitement/traitement_profil.php" method="post">
						<button type="submit" class="btn btn-danger" name="suppression_profil" onclick="return confirm('Êtes-vous sûr de votre choix ?')">Supprimer mon profil</button>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<style>
	body{ 
		background-color: #0C1524;
	}
	label{ 
		color: white; 
		
	} 
	.page-header{ color: white; 
		
	} 
	.parcours{ 
		color: white; 
		
	}
</style>