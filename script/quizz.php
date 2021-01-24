<?php
	session_start();
	include('../include/fonction_bdd.php');
	include('../include/fonction_quizz.php');

?>
	


<?php
	
	if(isset($_GET['submit'])){
		
		?>
			<div class="circular ui icon button" onclick="location.reload()">
				<i class="caret left icon"></i>
			</div>
			<div class="ui middle aligned center aligned grid">
			  <div class="column">
			    <form class="ui large form" id="formSubmit" method="post">
			      <div class="ui stacked segment">
			        <div class="field">
			          <div class="ui left icon input">
			            <i class="question circle icon"></i>
			            <input type="text" name="q" placeholder="Question" required id="questionnaire">
			          </div>
			        </div>
			        <div class="field">
			          <div class="ui left icon input">
			            <i class="reply icon"></i>
			            <input type="text" name="r1" placeholder="Bonne réponse" required id="questionnaire">
			          </div>
			        </div>
			        <div class="field error">
			          <div class="ui left icon input">
			            <i class="reply icon"></i>
			            <input type="text" name="r2" placeholder="Reponse 2" required id="questionnaire">
			          </div>
			        </div>
			        <div class="field error">
			          <div class="ui left icon input">
			            <i class="reply icon"></i>
			            <input type="text" name="r3" placeholder="Reponse 3" required id="questionnaire">
			          </div>
			        </div>
			        <div class="field error">
			          <div class="ui left icon input">
			            <i class="reply icon"></i>
			            <input type="text" name="r4" placeholder="Reponse 4" required id="questionnaire"> 
			          </div>
			        </div>
			        <div class="field">
			        	<select name="dif">
			        		<?php
			        			
			        			$req = base()->query('SELECT * FROM difficulte_quizz');
			        			while($res = $req->fetch()){
			        		
			        		?>
			        		
			        			<option value="<?= $res['id_difficulte']; ?>"><?= $res['nom_difficulte'] ?></option>
			        		
			        		<?php
			        		
			        			}
			        			
			        		?>
			        	</select>
			        	<br>
			        	<select name="theme">
			        		<?php
			        			
			        			$req = base()->query('SELECT * FROM theme_quizz');
			        			while($res = $req->fetch()){
			        		
			        		?>

				        		<option value="<?= $res['id_theme'] ?>"><?= $res['nom_theme'] ?></option>

			        		<?php
			        		
			        			}
			        			
			        		?>
			        	</select>
			        </div>
			        <button class="ui fluid large teal submit button" id="envoi" type="submit">Proposer</button>
			      </div>
			    </form>
			  </div>
			</div>
			<script>

			const myForm = document.getElementById('formSubmit');
			
			myForm.addEventListener('submit', function(e){
				
				e.preventDefault();
				
				const formData = new FormData(this);
				
				document.getElementById('envoi').className = "ui fluid disabled loading large teal submit button";
				
				fetch('../traitement/traitement_quizz.php?submit', {
					method : 'POST',
					body: formData
				}).then(function (reponse){
					$('#questionnaire').val('');
					document.getElementById('envoi').className = "ui fluid large teal submit button"
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
	
	if(isset($_GET['newGame'])){
		
		?>
			<div class="circular ui icon button" onclick="location.reload()">
				<i class="caret left icon"></i>
			</div>		
			<div class="ui middle aligned center aligned grid">
			  <div class="column">
				 <div class="ui segment">
				 	Avec qui voulez vous jouer ?
				 </div>
				 <?php
					
					$req = base()->prepare('SELECT * FROM `abonnement` 
								INNER JOIN utilisateur ON abonnement.id_abonnement = utilisateur.id_utilisateur
								WHERE abonnement.id_utilisateur = :id');
					$req->execute(array('id' => $_SESSION['profil']['id']));
					
					while($res = $req->fetch()){
						
						if(testGameEnCours($res['id_utilisateur'])){
						
				 ?>
				 
					<div class="ui segment">
						<div class="ui equal width grid">
							<div class="column">
								<img class="ui circular mini image" style="height: 35px; width: 35px;" src="../avatar/<?= $res['pdp_utilisateur'] ?>">
							</div>
							<div class="column">
								<?= $res['pseudo_utilisateur']; ?>
							</div>
							<div class="column">
								<button class="ui positive button" onclick="$('#game').load('../script/quizz.php?createGame=<?= $res['id_utilisateur'] ?>')">Jouer</button>
							</div>
						</div>
					</div>
				 
				 <?php
						}
					}
				 
				 ?>
			  </div>
			</div>		
		
		<?php
		
	}
	
	if(isset($_GET['gameList'])){
		
		?>
		
			<div class="ui segment" style="text-align: center;">
				Vos parties en cours
			</div>
			<?php
				
				$req = base()->prepare('SELECT * FROM game_quizz
										WHERE ( id_joueur1 = :id OR id_joueur2 = :id ) AND fin_game IS NULL');
				$req->execute(array('id' => $_SESSION['profil']['id']));
				while($res = $req->fetch()){
					
			?>
			
				<div class="ui segment">
					<div class="ui equal width grid">
						<div class="column">
							<img class="ui circular mini image" style="width: 35px; height: 35px;" src="../avatar/<?= getImgProfil($res['id_joueur1'], $res['id_joueur2']); ?>">
						</div>
						<div class="column">
							<?= getPseudo($res['id_joueur1'], $res['id_joueur2']); ?>
						</div>
						<div class="column">
							<button class="ui yellow button" onclick="$('#game').load('../script/quizz.php?game=<?= $res['id_game'] ?>')">Continuer</button>
						</div>
					</div>
				</div>
		
			<?php
			
				}
			
			?>
		
		<?php
		
	}
	
	if(isset($_GET['createGame'])){
		
		?>
		
		<div class="ui middle aligned center aligned grid">
			<div class="column">
				<div class="ui segment">
					<div class="ui equal width grid">
						<div class="column">
							<?php
								
								$req = base()->prepare('SELECT pdp_utilisateur,pseudo_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
								$req->execute(array('id' => $_SESSION['profil']['id']));
								$res = $req->fetch();
							
							?>
							
							<img class="ui medium circular image" style="width: 300px; heigth: 300px;" src="../avatar/<?= $res['pdp_utilisateur']; ?>">
							<?= $res['pseudo_utilisateur']; ?>
							
						</div>
						<div class="column" style="margin-top: auto; margin-bottom: auto">
							VS
						</div>
						<div class="column">
							<?php
								
								$req = base()->prepare('SELECT pdp_utilisateur,pseudo_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
								$req->execute(array('id' => $_GET['createGame']));
								$res = $req->fetch();
							
							?>
							
							<img class="ui medium circular image" style="width: 300px; heigth: 300px;" src="../avatar/<?= $res['pdp_utilisateur']; ?>">
							<?= $res['pseudo_utilisateur']; ?>
							
						</div>
					</div>
					<button class="ui large green button" onclick="newGame('<?= $_GET['createGame']; ?>')">Commencer</button>
					<button class="ui large red button" onclick="$('#game').load('../script/quizz.php?newGame')">Annuler</button>
				</div>	
			</div>
		</div>
		<script>
		
		function newGame(id){
			$.ajax({
		      url: '../traitement/traitement_jeux.php?quizz&create='+id,
		      type: 'post',
		      success: function(output) 
		      {
		          location.reload();
		      }, error: function()
		      {
		          alert('something went wrong, rating failed');
		      }
		   });
		}
		
		</script>
		<?php
		
	}
	
	if(isset($_GET['game'])){
		
		?>
		
			<div class="ui middle aligned center aligned grid">
				<div class="column">
					<div class="ui segment">
						<h3>
							<?= "Manche ".getProgress($_GET['game'])[0]." - Tour de ".getNomTour(getProgress($_GET['game']),$_GET['game'])[0] ?>
						</h3>
					</div>
					<div class="ui segment">
						<img class="ui circular centered medium image" style="height: 300px; width: 300px" src="../<?= getImgUtilDuel(getNomTour(getProgress($_GET['game']),$_GET['game'])[1]) ?>">
						<?php
							// debut de la partie
							if(getNomTour(getProgress($_GET['game']),$_GET['game'])[1] != $_SESSION['profil']['id']  && testDebutGame($_GET['game'])){
								
								echo "<h4>Choisissez un thème sur lequel votre adversaire devrai jouer !</h4>";
								
								affichageThemeAlea(getTheme());
								
							}else{ 
							
								tourDeJeu(getJoueurs($_GET['game'])[0], getJoueurs($_GET['game'])[1], $_GET['game']);
							
							}	
						?>
					</div>
				</div>
			</div>
			<script>
			function themeChoose(id){
		
				$.ajax({
			      url: '../traitement/traitement_quizz.php?quizz&newManche='+id+'&game=<?= $_GET['game'] ?>',
			      type: 'post',
			      success: function(output) 
			      {
					$('#game').load('../script/quizz.php?game=<?= $_GET['game']; ?>')
			      }, error: function()
			      {
			          alert('something went wrong, rating failed');
			      }
			   });
				
			}
			
			function getQuestion(id_theme, id_difficulte, id_manche){
				
				$.ajax({
			      url: '../traitement/traitement_quizz.php?getQ='+id_manche+'&th='+id_theme+'&dif='+id_difficulte,
			      type: 'post',
			      success: function(output) 
			      {
					$('#game').load('../script/quizz.php?game=<?= $_GET['game'] ?>&rep='+id_manche)
			      }, error: function()
			      {
			          alert('something went wrong, rating failed');
			      }
			   });			
				
			}
			


			</script>
			
		<?php
		
	}
	
	if(isset($_GET['resultGameList'])){
		
		?>
		
			<div class="ui segment" style="text-align: center;">
				Vos résultats récents
			</div>
			<?php
				
				$req = base()->prepare('SELECT * FROM game_quizz
										WHERE ( id_joueur1 = :id OR id_joueur2 = :id ) AND fin_game IS NOT NULL');
				$req->execute(array('id' => $_SESSION['profil']['id']));
				while($res = $req->fetch()){
					
			?>
			
				<div class="ui segment">
					<div class="ui equal width grid">
						<div class="column">
							<img class="ui circular mini image" style="width: 35px; height: 35px;" src="../avatar/<?= getImgProfil($res['id_joueur1'], $res['id_joueur2']); ?>">
						</div>
						<div class="column">
							<?= getPseudo($res['id_joueur1'], $res['id_joueur2']); ?>
						</div>
						<div class="column">
						<?php
							
							if($res['fin_game'] == $_SESSION['profil']['id']){
								
								?>
								
									<span style="color: green; font-weight: bold;">
										VICTOIRE
									</span>
									
								<?php
								
							}elseif($res['fin_game'] == 129){
								
								?>
								
									<span style="color: orange; font-weight: bold;">
										ÉGALITÉ
									</span>
									
								<?php
								
							}else{
								
								?>
								
									<span style="color: red; font-weight: bold;">
										DÉFAITE
									</span>
									
								<?php
								
							}
						
						?>
						</div>
					</div>
				</div>
		
			<?php
			
				}
			
			?>
		
		<?php
		
	}

?>