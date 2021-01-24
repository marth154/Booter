<?php

	function getImgProfil($J1, $J2){
		
		if($J1 != $_SESSION['profil']['id']){
			
			$req = base()->prepare('SELECT pdp_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
			$req->execute(array('id' => $J1));
			
			$res = $req->fetch();
			
			return $res['pdp_utilisateur'];
			
		}else{
			
			$req = base()->prepare('SELECT pdp_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
			$req->execute(array('id' => $J2));
			
			$res = $req->fetch();
			
			return $res['pdp_utilisateur'];
			
		}
		
	}
	
	function getImgUtilDuel($id){
		
		$req = base()->prepare('SELECT pdp_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return "avatar/".$res['pdp_utilisateur'];
		
	}
	
	function getPseudo($J1, $J2){
		
		if($J1 != $_SESSION['profil']['id']){
			
			$req = base()->prepare('SELECT pseudo_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
			$req->execute(array('id' => $J1));
			
			$res = $req->fetch();
			
			return $res['pseudo_utilisateur'];
			
		}else{
			
			$req = base()->prepare('SELECT pseudo_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
			$req->execute(array('id' => $J2));
			
			$res = $req->fetch();
			
			return $res['pseudo_utilisateur'];
			
		}
		
	}
	
	function getProgress($game){
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_game = :id');
		$req->execute(array('id' => $game));
		
		$res = $req->fetch();
		
		$tab = array(0,0);// Manche/tour de jeu
		
		if($res['manche1J1'] == null){
			
			$tab = [1,2];
			
		}elseif($res['manche1J2'] == null){
			
			$tab = [1,1];
			
		}elseif($res['manche2J1'] == null){
			
			$tab = [2,2];
			
		}elseif($res['manche2J2'] == null){
			
			$tab = [2,1];
			
		}elseif($res['manche3J1'] == null){
			
			$tab = [3,2];
			
		}elseif($res['fin_game'] == 0){
			
			$tab = [3,1];
			
		}
		
		return $tab;
		
	}
	
	function getNomTour($tab, $game){
		
		if($tab[1] == 1){
			
			$req = base()->prepare('SELECT pseudo_utilisateur,id_utilisateur FROM game_quizz INNER JOIN utilisateur ON utilisateur.id_utilisateur = game_quizz.id_joueur1 WHERE id_game = :id');
			$req->execute(array('id' => $game));
			
			$res = $req->fetch();
			
			return array($res['pseudo_utilisateur'], $res['id_utilisateur']);
			
		}else{
			
			$req = base()->prepare('SELECT pseudo_utilisateur,id_utilisateur FROM game_quizz INNER JOIN utilisateur ON utilisateur.id_utilisateur = game_quizz.id_joueur2 WHERE id_game = :id');
			$req->execute(array('id' => $game));
			
			$res = $req->fetch();
			
			return array($res['pseudo_utilisateur'], $res['id_utilisateur']);
			
		}
		
	}
	
	function getTheme(){
		
		$req = base()->query('SELECT COUNT(id_theme) FROM theme_quizz');
		$res = $req->fetch();
		
		do{
			
			$tab = array(rand(1, $res['COUNT(id_theme)']), rand(1, $res['COUNT(id_theme)']), rand(1, $res['COUNT(id_theme)']));
			
		}while($tab[0] == $tab[1] && $tab[0] == $tab[2] && $tab[1] == $tab[2]);	
		
		return $tab;
		
	}
	
	function getQuestion($idTheme, $idDifficulte){
		
		$req = base()->prepare('SELECT COUNT(id_question) FROM questions_quizz WHERE id_theme = :idT AND id_difficulte = :idD');
		$req->execute(array('idT' => $idTheme, 'idD' => $idDifficulte));
		
		$res = $req->fetch();
		
		$numQ = rand(1, $res['COUNT(id_question)']);
		
		$req = base()->prepare('SELECT id_question FROM questions_quizz WHERE id_theme = :idT AND id_difficulte = :idD');
		$req->execute(array('idT' => $idTheme, 'idD' => $idDifficulte));
		
		$i = 1;
		
		while($res = $req->fetch()){
			
			if($i == $numQ){
				
				return $res['id_question'];
				
			}
			
			$i++;
			
		}
	}
	
	function affichageThemeAlea($tab){
		
		$req = base()->prepare('SELECT * FROM theme_quizz WHERE id_theme = :idT');
		
		?>
			
			<div class="ui equal width grid">
				<div class="column">
					<?php
					
						$req->execute(array('idT' => $tab[0]));
						$res = $req->fetch();
						
					?>
					<div class="ui segment" onclick="themeChoose(<?= $tab[0] ?>)" style="cursor: pointer;">
						<?= $res['nom_theme']; ?>
					</div>
				</div>
				<div class="column">
					<?php
					
						$req->execute(array('idT' => $tab[1]));
						$res = $req->fetch();
						
					?>
					<div class="ui segment" onclick="themeChoose(<?= $tab[1] ?>)" style="cursor: pointer;">
						<?= $res['nom_theme']; ?>
					</div>
				</div>
				<div class="column">
					<?php
					
						$req->execute(array('idT' => $tab[2]));
						$res = $req->fetch();
						
					?>
					<div class="ui segment" onclick="themeChoose(<?= $tab[2] ?>)" style="cursor: pointer;">
						<?= $res['nom_theme']; ?>
					</div>
				</div>
			</div>
		
		<?php
		
	}
	
	function testFinManche($idManche){
		
		$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
		$req->execute(array('id' => $idManche));
		$res = $res->fetch();
		
		if($res['id_reponse3'] == null){
			
			return false;
			
		}
		
		return true;
		
	}
	
	function testGameEnCours($id){
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_joueur1 = :J1 AND id_joueur2 = :J2 OR id_joueur1 = :J2 AND id_joueur2 =:J1');
		$req->execute(array('J1' => $_SESSION['profil']['id'], 'J2' => $id));
		$res = $req->fetch();
		
		if($res['id_game'] == null || $res['fin_game'] != null){
			
			return true;
			
		}
		
		return false;
		
	}	
	
	function testDebutGame($id){
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_game = :id');
		$req->execute(array('id' => $id));
		$res = $req->fetch();
		
		if($res['manche1J2'] == null){
			
			return true;
			
		}
		
		return false;
		
	}
	
	function getThemeManche($tab){
		
		return $tab[0]." - ".$tab[1];
		
		switch ($tab){
			
			case [1,1]:
				$req = base()->query('SELECT id_theme FROM game_quizz INNER JOIN manche_quizz ON game_quizz.manche1J1 = manche_quizz.id_manche');
				break;
			case [1,2]:
				$req = base()->query('SELECT id_theme FROM game_quizz INNER JOIN manche_quizz ON game_quizz.manche1J2 = manche_quizz.id_manche');
				return 1;
				break;
			case [2,1]:
				$req = base()->query('SELECT id_theme FROM game_quizz INNER JOIN manche_quizz ON game_quizz.manche2J1 = manche_quizz.id_manche');
				break;
			case [2,2]:
				$req = base()->query('SELECT id_theme FROM game_quizz INNER JOIN manche_quizz ON game_quizz.manche2J2 = manche_quizz.id_manche');
				break;
			case [3,1]:
				$req = base()->query('SELECT id_theme FROM game_quizz INNER JOIN manche_quizz ON game_quizz.manche3J1 = manche_quizz.id_manche');
				break;
			case [3,2]:
				$req = base()->query('SELECT id_theme FROM game_quizz INNER JOIN manche_quizz ON game_quizz.manche3J2 = manche_quizz.id_manche');
				break;

		}
		
					
		$res = $req->fetch();
		
		$id = $res['id_theme'];
		
		$req = base()->prepare('SELECT * FROM theme_quizz WHERE id_theme = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['nom_theme'];
		
	}
	
	function getJoueurs($id_game){
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_game = :id');
		$req->execute(array('id' => $id_game));
		$res = $req->fetch();
		
		return array($res['id_joueur1'], $res['id_joueur2']);
		
	}
	
	function tourDeJeu($J1, $J2, $id_game){
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_game = :id');
		$req->execute(array('id' => $id_game));
		
		$res = $req->fetch();
		
		if($J1 == $_SESSION['profil']['id']){
			
			if($res['manche3J1'] != null){// Manche 3 tour de J1
			
				$reponse = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
				$reponse->execute(array('id' => $res['manche3J1']));
				$reponse = $reponse->fetch();
				
				if($reponse['id_reponse1'] == null){
					
					if(!isset($_GET['rep']) && !testDifficulte($res['manche3J1'])){
						
						getDifficult($res['manche3J1']);
					
					}else{
						?>
							
							<form class="ui form" id="formQ" method="POST">
								
						<?php
						
								repQ($res['manche3J1']);
								?><button class="ui green button" type="submit" id="validQ" style="width: 100%">Valider</button>  <?php
						
						?>
						
							</form>
							<script>
								const formQuestionnaire = document.getElementById('formQ');
							
								formQuestionnaire.addEventListener('submit', function(e){
									
									e.preventDefault();
									
									const formDataQ = new FormData(this);
									
									document.getElementById('validQ').className = "ui disabled loading green button";
									
									fetch('../traitement/traitement_quizz.php?validQ=<?= getMancheEnCours($_GET['game']) ?>&calcV', {
										method : 'POST',
										body: formDataQ
									}).then(function (reponse){
										$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
										document.getElementById('validQ').className = "ui green button"
										return reponse.text();
									}).then(function (text){//Quand ça marche pas
										console.log(text);
									}).catch(function (error){
										console.error(error);
									})
									
								});
							</script>
						<?php
					}
				
				}
				
			}elseif($res['manche2J1'] != null){// Manche 2 tour de J1
			
				$reponse = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
				$reponse->execute(array('id' => $res['manche2J1']));
				$reponse = $reponse->fetch();
					
				if($res['manche3J2'] == null && testReponse($res['manche2J1'])){
					
					echo "<h4>Choisissez un thème sur lequel votre adversaire devrai jouer !</h4>";
								
					affichageThemeAlea(getTheme());
					
				}elseif($reponse['id_reponse1'] == null){
					
					if(!isset($_GET['rep']) && !testDifficulte($res['manche2J1'])){
						
						getDifficult($res['manche2J1']);
					
					}else{
						?>
							
							<form class="ui form" id="formQ" method="POST">
								
						<?php
						
								repQ($res['manche2J2']);
								?><button class="ui green button" type="submit" id="validQ" style="width: 100%">Valider</button>  <?php
						
						?>
						
							</form>
							<script>
								const formQuestionnaire = document.getElementById('formQ');
							
								formQuestionnaire.addEventListener('submit', function(e){
									
									e.preventDefault();
									
									const formDataQ = new FormData(this);
									
									document.getElementById('validQ').className = "ui disabled loading green button";
									
									fetch('../traitement/traitement_quizz.php?validQ=<?= getMancheEnCours($_GET['game']) ?>', {
										method : 'POST',
										body: formDataQ
									}).then(function (reponse){
										$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
										document.getElementById('validQ').className = "ui green button"
										return reponse.text();
									}).then(function (text){//Quand ça marche pas
										console.log(text);
									}).catch(function (error){
										console.error(error);
									})
									
								});
							</script>
						<?php
					}
				
				}
				
			}elseif($res['manche1J1'] != null){// Manche 1 tour de J1
			
				$reponse = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
				$reponse->execute(array('id' => $res['manche1J1']));
				$reponse = $reponse->fetch();
					
				if($res['manche2J2'] == null && testReponse($res['manche1J1'])){
					
					echo "<h4>Choisissez un thème sur lequel votre adversaire devrai jouer !</h4>";
								
					affichageThemeAlea(getTheme());
					
				}elseif($reponse['id_reponse1'] == null){
					
					
					if(!isset($_GET['rep']) && !testDifficulte($res['manche1J1'])){
						
						getDifficult($res['manche1J1']);
					
					}else{
						?>
							
							<form class="ui form" id="formQ" method="POST">
								
						<?php
						
								repQ($res['manche1J1']);
								?><button class="ui green button" type="submit" id="validQ" style="width: 100%">Valider</button>  <?php
						
						?>
						
							</form>
							<script>
								const formQuestionnaire = document.getElementById('formQ');
							
								formQuestionnaire.addEventListener('submit', function(e){
									
									e.preventDefault();
									
									const formDataQ = new FormData(this);
									
									document.getElementById('validQ').className = "ui disabled loading green button";
									
									fetch('../traitement/traitement_quizz.php?validQ=<?= getMancheEnCours($_GET['game']) ?>', {
										method : 'POST',
										body: formDataQ
									}).then(function (reponse){
										$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
										document.getElementById('validQ').className = "ui green button"
										return reponse.text();
									}).then(function (text){//Quand ça marche pas
										console.log(text);
									}).catch(function (error){
										console.error(error);
									})
									
								});
							</script>
						<?php
					}
				
				}
					
			}
			
		}
		
		if($J2 == $_SESSION['profil']['id']){
			
			if($res['manche3J2'] != null){// Manche 3 tour de J1
			
				$reponse = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
				$reponse->execute(array('id' => $res['manche3J2']));
				$reponse = $reponse->fetch();
					
				if($res['manche3J1'] == null && testReponse($res['manche3J2'])){
					
					echo "<h4>Choisissez un thème sur lequel votre adversaire devrai jouer !</h4>";
								
					affichageThemeAlea(getTheme());
					
				}elseif($reponse['id_reponse1'] == null){
					
					if(!isset($_GET['rep']) && !testDifficulte($res['manche3J2'])){
						
						getDifficult($res['manche3J2']);
					
					}else{
						?>
							
							<form class="ui form" id="formQ" method="POST">
								
						<?php
						
								repQ($res['manche2J2']);
								?><button class="ui green button" type="submit" id="validQ" style="width: 100%">Valider</button>  <?php
						
						?>
						
							</form>
							<script>
								const formQuestionnaire = document.getElementById('formQ');
							
								formQuestionnaire.addEventListener('submit', function(e){
									
									e.preventDefault();
									
									const formDataQ = new FormData(this);
									
									document.getElementById('validQ').className = "ui disabled loading green button";
									
									fetch('../traitement/traitement_quizz.php?validQ=<?= getMancheEnCours($_GET['game']) ?>', {
										method : 'POST',
										body: formDataQ
									}).then(function (reponse){
										$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
										document.getElementById('validQ').className = "ui green button"
										return reponse.text();
									}).then(function (text){//Quand ça marche pas
										console.log(text);
									}).catch(function (error){
										console.error(error);
									})
									
								});
							</script>
						<?php
					}
				
				}
				
			}elseif($res['manche2J2'] != null){// Manche 2 tour de J1
			
				$reponse = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
				$reponse->execute(array('id' => $res['manche2J2']));
				$reponse = $reponse->fetch();
					
				if($res['manche2J1'] == null && testReponse($res['manche2J2'])){
					
					echo "<h4>Choisissez un thème sur lequel votre adversaire devrai jouer !</h4>";
								
					affichageThemeAlea(getTheme());
					
				}elseif($reponse['id_reponse1'] == null){
					
					if(!isset($_GET['rep']) && !testDifficulte($res['manche2J2'])){
						
						getDifficult($res['manche2J2']);
					
					}else{
						?>
							
							<form class="ui form" id="formQ" method="POST">
								
						<?php
						
								repQ($res['manche2J2']);
								?><button class="ui green button" type="submit" id="validQ" style="width: 100%">Valider</button>  <?php
						
						?>
						
							</form>
							<script>
								const formQuestionnaire = document.getElementById('formQ');
							
								formQuestionnaire.addEventListener('submit', function(e){
									
									e.preventDefault();
									
									const formDataQ = new FormData(this);
									
									document.getElementById('validQ').className = "ui disabled loading green button";
									
									fetch('../traitement/traitement_quizz.php?validQ=<?= getMancheEnCours($_GET['game']) ?>', {
										method : 'POST',
										body: formDataQ
									}).then(function (reponse){
										$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
										document.getElementById('validQ').className = "ui green button"
										return reponse.text();
									}).then(function (text){//Quand ça marche pas
										console.log(text);
									}).catch(function (error){
										console.error(error);
									})
									
								});
							</script>
						<?php
					}
				
				}
				
			}elseif($res['manche1J2'] != null){// Manche 1 tour de J1
			
				$reponse = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
				$reponse->execute(array('id' => $res['manche1J2']));
				$reponse = $reponse->fetch();
					
				if($res['manche1J1'] == null && testReponse($res['manche1J2'])){
					
					echo "<h4>Choisissez un thème sur lequel votre adversaire devrai jouer !</h4>";
								
					affichageThemeAlea(getTheme());
					
				}elseif($reponse['id_reponse1'] == null){
					
					if(!isset($_GET['rep']) && !testDifficulte($res['manche1J2'])){

						getDifficult($res['manche1J2']);
					
					}else{
						?>
							
							<form class="ui form" id="formQ" method="POST">
								
						<?php
						
								repQ($res['manche1J2']);
								?><button class="ui green button" type="submit" id="validQ" style="width: 100%">Valider</button>  <?php
						
						?>
						
							</form>
							<script>
								const formQuestionnaire = document.getElementById('formQ');
							
								formQuestionnaire.addEventListener('submit', function(e){
									
									e.preventDefault();
									
									const formDataQ = new FormData(this);
									
									document.getElementById('validQ').className = "ui disabled loading green button";
									
									fetch('../traitement/traitement_quizz.php?validQ=<?= getMancheEnCours($_GET['game']) ?>', {
										method : 'POST',
										body: formDataQ
									}).then(function (reponse){
										$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
										document.getElementById('validQ').className = "ui green button"
										return reponse.text();
									}).then(function (text){//Quand ça marche pas
										console.log(text);
									}).catch(function (error){
										console.error(error);
									})
									
								});
							</script>
						<?php
					}
				
				}
				
			}
			
		}
		
		?>
		
		
		<?php
		
	}
	
	function testReponse($id_manche){
		
		$req = base()->prepare('SELECT id_reponse1 FROM manche_quizz WHERE id_manche = :id');
		$req->execute(array('id' => $id_manche));
		
		$res = $req->fetch();
		
		if($res['id_reponse1'] != null){
			
			return true;
			
		}
		
		return false;		
		
		
	}
	
	function getDifficult($id_manche){
		
		$req = base()->prepare('SELECT * FROM manche_quizz INNER JOIN theme_quizz ON theme_quizz.id_theme = manche_quizz.id_theme WHERE id_manche = :id');
		$req->execute(array('id' => $id_manche));
		
		$res = $req->fetch();
		?>
			
			<div class="ui segment">
				<h4>Votre adversaire vous a choisi le thème : <u><?= $res['nom_theme'] ?></u></h4>
				<br>
				Choisissez votre difficulté :
			</div>
			<div class="ui equal width grid">
		<?php
		
		$theme = $res['id_theme'];
		
		$req = base()->query('SELECT * FROM difficulte_quizz');
		while($res = $req->fetch()){
			
			?>
				<div class="column">
					<div class="ui segment" style="cursor: pointer;" onClick="getQuestion('<?= $theme ?>', '<?= $res['id_difficulte'] ?>', '<?= $id_manche ?>')">
						<?= $res['nom_difficulte'] ?>
					</div>
				</div>
				
			<?php
			
		}
		
		?>
		
			</div>
			
		<?php
	}
	
	function testDifficulte($id_manche){
		
		$req = base()->prepare('SELECT id_question1 FROM manche_quizz WHERE id_manche = :id');
		$req->execute(array('id' => $id_manche));
		
		$res = $req->fetch();
		
		if($res['id_question1'] != null){
			
			return true;
			
		}
		
		return false;
		
	}
	
	function repQ($id_manche){
		
		$req = base()->prepare('SELECT * FROM manche_quizz INNER JOIN questions_quizz ON manche_quizz.id_question1 = questions_quizz.id_question
														   WHERE id_manche = :id');
		$req->execute(array('id' => $id_manche));
		$res = $req->fetch();
		
		affichageQuestion($res, 1);
		
		$req = base()->prepare('SELECT * FROM manche_quizz INNER JOIN questions_quizz ON manche_quizz.id_question2 = questions_quizz.id_question
														   WHERE id_manche = :id');
		$req->execute(array('id' => $id_manche));
		$res = $req->fetch();
		
		affichageQuestion($res, 2);
		
		$req = base()->prepare('SELECT * FROM manche_quizz INNER JOIN questions_quizz ON manche_quizz.id_question3 = questions_quizz.id_question
														   WHERE id_manche = :id');
		$req->execute(array('id' => $id_manche));
		$res = $req->fetch();
		
		affichageQuestion($res, 3);
		
	}
	
	function affichageQuestion($req, $num){
		
		?>
		
			<div class="ui segment">
					
				<?= $req['contenu_question'] ?>
				<?php affichageProposition($req['id_question'], $num); ?>
				
			</div>
		
		<?php
		
	}
	
	function affichageProposition($q, $num){
		
		$req1 = base()->prepare('SELECT * FROM propositions_quizz WHERE id_question = :id');
		$req1->execute(array('id' => $q));
		
		$tab = array();
		
		$i = 0;
		
		while($res = $req1->fetch()){
			
			$tab[$i] = $res['id_proposition'];
			
			$i++;
			
		}
		
		$alea = rand(1,5);
		
		switch($alea){
			
			case 1:
				?>
					
					<select name="<?= "q".$num ?>">
						<option value="<?= $tab[3] ?>"><?= getContenuProposition($tab[3]) ?></option>
						<option value="<?= $tab[1] ?>"><?= getContenuProposition($tab[1]) ?></option>
						<option value="<?= $tab[0] ?>"><?= getContenuProposition($tab[0]) ?></option>
						<option value="<?= $tab[2] ?>"><?= getContenuProposition($tab[2]) ?></option>
					</select>
				
				<?php
				break;
				
			case 2:
				?>
					
					<select name="<?= "q".$num ?>">
						<option value="<?= $tab[2] ?>"><?= getContenuProposition($tab[2]) ?></option>
						<option value="<?= $tab[0] ?>"><?= getContenuProposition($tab[0]) ?></option>
						<option value="<?= $tab[3] ?>"><?= getContenuProposition($tab[3]) ?></option>
						<option value="<?= $tab[1] ?>"><?= getContenuProposition($tab[1]) ?></option>
					</select>
				
				<?php
				break;
				
			case 3:
				?>
					
					<select name="<?= "q".$num ?>">
						<option value="<?= $tab[2] ?>"><?= getContenuProposition($tab[2]) ?></option>
						<option value="<?= $tab[3] ?>"><?= getContenuProposition($tab[3]) ?></option>
						<option value="<?= $tab[1] ?>"><?= getContenuProposition($tab[1]) ?></option>
						<option value="<?= $tab[0] ?>"><?= getContenuProposition($tab[0]) ?></option>
					</select>
				
				<?php
				break;
				
			case 4:
				?>
					
					<select name="<?= "q".$num ?>">
						<option value="<?= $tab[0] ?>"><?= getContenuProposition($tab[0]) ?></option>
						<option value="<?= $tab[2] ?>"><?= getContenuProposition($tab[2]) ?></option>
						<option value="<?= $tab[1] ?>"><?= getContenuProposition($tab[1]) ?></option>
						<option value="<?= $tab[3] ?>"><?= getContenuProposition($tab[3]) ?></option>
					</select>
				
				<?php
				break;
				
			case 5:
				?>
					
					<select name="<?= "q".$num ?>">
						<option value="<?= $tab[1] ?>"><?= getContenuProposition($tab[1]) ?></option>
						<option value="<?= $tab[3] ?>"><?= getContenuProposition($tab[3]) ?></option>
						<option value="<?= $tab[0] ?>"><?= getContenuProposition($tab[0]) ?></option>
						<option value="<?= $tab[2] ?>"><?= getContenuProposition($tab[2]) ?></option>
					</select>
				
				<?php
				break;
			
		}
		
	}
	
	function getContenuProposition($id){
		
		$req = base()->prepare('SELECT contenu_proposition FROM propositions_quizz WHERE id_proposition = :id');
		$req->execute(array('id' => $id));
		
		$res = $req->fetch();
		
		return $res['contenu_proposition'];
		
	}
	
	function getMancheEnCours($id_game){
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_game = :id');
		$req->execute(array('id' => $id_game));
		
		$res = $req->fetch();
		
		if($res['manche1J2'] != null && $res['manche1J1'] == null){
			
			return $res['manche1J2'];
			
		}elseif($res['manche1J1'] != null && $res['manche2J2'] == null){
			
			return $res['manche1J1'];
			
		}elseif($res['manche2J2'] != null && $res['manche2J1'] == null){
			
			return $res['manche2J2'];
			
		}elseif($res['manche2J1'] != null && $res['manche3J2'] == null){
			
			return $res['manche2J1'];
			
		}elseif($res['manche3J2'] != null && $res['manche3J1'] == null){
			
			return $res['manche3J2'];
			
		}else{
			
			return $res['manche3J1'];
			
		}
		
	}
	

?>