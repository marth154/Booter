<?php
	
	include('../include/fonction_bdd.php');
	include('../include/fonction_quizz.php');
	
	if(isset($_GET['submit'])){
		
		$req = base()->prepare('INSERT INTO questions_quizz(id_theme, id_difficulte, contenu_question) VALUES(:idT, :idD, :q)');
		$req->execute(array('idT' => $_POST['theme'], 'idD' => $_POST['dif'], 'q' => htmlspecialchars($_POST['q'])));
		$req = base()->prepare('SELECT MAX(id_question) FROM questions_quizz WHERE contenu_question = :ctn');
		$req->execute(array('ctn' => htmlspecialchars($_POST['q'])));
		$res = $req->fetch();
		$q = $res['MAX(id_question)'];
		
		$req = base()->prepare('INSERT INTO propositions_quizz(contenu_proposition, reponse_question, id_question) VALUES(:ctn, :rep, :idQ)');
		$req->execute(array('ctn' => htmlspecialchars($_POST['r1']), 'rep' => 1, 'idQ' => $q));
		
		$req = base()->prepare('INSERT INTO propositions_quizz(contenu_proposition, reponse_question, id_question) VALUES(:ctn, :rep, :idQ)');
		$req->execute(array('ctn' => htmlspecialchars($_POST['r2']), 'rep' => 0, 'idQ' => $q));
		
		$req = base()->prepare('INSERT INTO propositions_quizz(contenu_proposition, reponse_question, id_question) VALUES(:ctn, :rep, :idQ)');
		$req->execute(array('ctn' => htmlspecialchars($_POST['r3']), 'rep' => 0, 'idQ' => $q));
		
		$req = base()->prepare('INSERT INTO propositions_quizz(contenu_proposition, reponse_question, id_question) VALUES(:ctn, :rep, :idQ)');
		$req->execute(array('ctn' => htmlspecialchars($_POST['r4']), 'rep' => 0, 'idQ' => $q));
		
	}
	
	if(isset($_GET['newManche']) && isset($_GET['game'])){
		
		$req = base()->prepare('INSERT INTO manche_quizz(id_manche, id_theme) VALUES(:idM, :idT)');
		
		$req2 = base()->query('SELECT MAX(id_manche) FROM manche_quizz');
		$res2 = $req2->fetch();
		
		$id = $res2['MAX(id_manche)']+1;
		
		$req->execute(array('idM' => $id, 'idT' => $_GET['newManche']));
		
		$req = base()->prepare('SELECT * FROM game_quizz WHERE id_game = :idG');
		$req->execute(array('idG' => $_GET['game']));
		$res = $req->fetch();
		
		
		if($res['manche1J2'] == null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche1J2 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));			
			
		}elseif($res['manche1J1'] == null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche1J1 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));
			
			
		}elseif($res['manche2J2'] == null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche2J2 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));
			
		}elseif($res['manche2J1'] == null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche2J1 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));
			
		}elseif($res['manche3J2'] == null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche3J2 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));
			
		}elseif($res['manche3J1'] == null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche3J1 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));
			
		}elseif($res['manche3J2'] != null){
			
			$req = base()->prepare('UPDATE game_quizz SET manche3J2 = :idM WHERE id_game = :idG');
			$req->execute(array('idM' => $id, 'idG' => $_GET['game']));
			
		}
		
	}
	
	if(isset($_GET['getQ'])){
		
		$req = base()->prepare('UPDATE manche_quizz SET id_question1 = :q1,
														id_question2 = :q2,
														id_question3 = :q3
													WHERE id_manche = :manche');
		$req->execute(array('q1' => getQuestion($_GET['th'], $_GET['dif']),
							'q2' => getQuestion($_GET['th'], $_GET['dif']),
							'q3' => getQuestion($_GET['th'], $_GET['dif']),
							'manche' => $_GET['getQ']));
		
	}
	
	if(isset($_GET['validQ'])){
		
		?>
		
			<script>
				alert('test');
			</script>
			
		<?php
		
		$req = base()->prepare('UPDATE manche_quizz SET id_reponse1 = :r1,
														id_reponse2 = :r2,
														id_reponse3 = :r3
													WHERE id_manche = :id');
													
		$req->execute(array('r1' => $_POST['q1'],
							'r2' => $_POST['q2'],
							'r3' => $_POST['q3'],
							'id' => $_GET['validQ']));										
		
		if(isset($_GET['calcV'])){
			
			$pointJ1 = 0;
			$pointJ2 = 0;
			
			$req = base()->prepare('SELECT * FROM game_quizz WHERE manche3J1 = :id');
			$req->execute(array('id' => $_GET['validQ']));
			$game = $req->fetch();
			
			//calcul de points manche 1
			
			$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
			$req->execute(array('id' => $game['manche1J1']));
			$manche1J1 = $req->fetch();
			
			$req = base()->prepare('SELECT id_proposition, points_difficulte FROM questions_quizz INNER JOIN propositions_quizz ON questions_quizz.id_question = propositions_quizz.id_question INNER JOIN difficulte_quizz ON questions_quizz.id_difficulte = difficulte_quizz.id_difficulte WHERE questions_quizz.id_question = :id AND reponse_question = 1');
			$req->execute(array('id' => $manche1J1['id_question1']));
			$q1 = $req->fetch();
			
			if($manche1J1['id_reponse1'] == $q1['id_proposition']){
				
				$pointJ1 += $q1['points_difficulte'];
				
			}
			
			$req->execute(array('id' => $manche1J1['id_question2']));
			$q2 = $req->fetch();
			
			if($manche1J1['id_reponse2'] == $q2['id_proposition']){
				
				$pointJ1 += $q2['points_difficulte'];
				
			}

			$req->execute(array('id' => $manche1J1['id_question3']));
			$q3 = $req->fetch();
			
			if($manche1J1['id_reponse3'] == $q3['id_proposition']){
				
				$pointJ1 += $q3['points_difficulte'];
				
			}
			
			
			$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
			$req->execute(array('id' => $game['manche1J2']));
			$manche1J2 = $req->fetch();
			
			$req = base()->prepare('SELECT id_proposition, points_difficulte FROM questions_quizz INNER JOIN propositions_quizz ON questions_quizz.id_question = propositions_quizz.id_question INNER JOIN difficulte_quizz ON questions_quizz.id_difficulte = difficulte_quizz.id_difficulte WHERE questions_quizz.id_question = :id AND reponse_question = 1');
			$req->execute(array('id' => $manche1J2['id_question1']));
			$q1 = $req->fetch();
			
			if($manche1J2['id_reponse1'] == $q1['id_proposition']){
				
				$pointJ2 += $q1['points_difficulte'];
				
			}
			
			$req->execute(array('id' => $manche1J2['id_question2']));
			$q2 = $req->fetch();
			
			if($manche1J2['id_reponse2'] == $q2['id_proposition']){
				
				$pointJ2 += $q2['points_difficulte'];
				
			}

			$req->execute(array('id' => $manche1J2['id_question3']));
			$q3 = $req->fetch();
			
			if($manche1J2['id_reponse3'] == $q3['id_proposition']){
				
				$pointJ2 += $q3['points_difficulte'];
				
			}
			
			// calcul points manche 2
			
			$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
			$req->execute(array('id' => $game['manche2J1']));
			$manche2J1 = $req->fetch();
			
			$req = base()->prepare('SELECT id_proposition, points_difficulte FROM questions_quizz INNER JOIN propositions_quizz ON questions_quizz.id_question = propositions_quizz.id_question INNER JOIN difficulte_quizz ON questions_quizz.id_difficulte = difficulte_quizz.id_difficulte WHERE questions_quizz.id_question = :id AND reponse_question = 1');
			$req->execute(array('id' => $manche2J1['id_question1']));
			$q1 = $req->fetch();
			
			if($manche2J1['id_reponse1'] == $q1['id_proposition']){
				
				$pointJ1 += $q1['points_difficulte'];
				
			}
			
			$req->execute(array('id' => $manche2J1['id_question2']));
			$q2 = $req->fetch();
			
			if($manche2J1['id_reponse2'] == $q2['id_proposition']){
				
				$pointJ1 += $q2['points_difficulte'];
				
			}

			$req->execute(array('id' => $manche2J1['id_question3']));
			$q3 = $req->fetch();
			
			if($manche2J1['id_reponse3'] == $q3['id_proposition']){
				
				$pointJ1 += $q3['points_difficulte'];
				
			}
			
			
			$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
			$req->execute(array('id' => $game['manche2J2']));
			$manche2J2 = $req->fetch();
			
			$req = base()->prepare('SELECT id_proposition, points_difficulte FROM questions_quizz INNER JOIN propositions_quizz ON questions_quizz.id_question = propositions_quizz.id_question INNER JOIN difficulte_quizz ON questions_quizz.id_difficulte = difficulte_quizz.id_difficulte WHERE questions_quizz.id_question = :id AND reponse_question = 1');
			$req->execute(array('id' => $manche2J2['id_question1']));
			$q1 = $req->fetch();
			
			if($manche2J2['id_reponse1'] == $q1['id_proposition']){
				
				$pointJ2 += $q1['points_difficulte'];
				
			}
			
			$req->execute(array('id' => $manche2J2['id_question2']));
			$q2 = $req->fetch();
			
			if($manche2J2['id_reponse2'] == $q2['id_proposition']){
				
				$pointJ2 += $q2['points_difficulte'];
				
			}

			$req->execute(array('id' => $manche2J2['id_question3']));
			$q3 = $req->fetch();
			
			if($manche2J2['id_reponse3'] == $q3['id_proposition']){
				
				$pointJ2 += $q3['points_difficulte'];
				
			}
			
			// calcul points manche 3
			
			$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
			$req->execute(array('id' => $game['manche3J1']));
			$manche3J1 = $req->fetch();
			
			$req = base()->prepare('SELECT id_proposition, points_difficulte FROM questions_quizz INNER JOIN propositions_quizz ON questions_quizz.id_question = propositions_quizz.id_question INNER JOIN difficulte_quizz ON questions_quizz.id_difficulte = difficulte_quizz.id_difficulte WHERE questions_quizz.id_question = :id AND reponse_question = 1');
			$req->execute(array('id' => $manche3J1['id_question1']));
			$q1 = $req->fetch();
			
			if($manche3J1['id_reponse1'] == $q1['id_proposition']){
				
				$pointJ1 += $q1['points_difficulte'];
				
			}
			
			$req->execute(array('id' => $manche3J1['id_question2']));
			$q2 = $req->fetch();
			
			if($manche3J1['id_reponse2'] == $q2['id_proposition']){
				
				$pointJ1 += $q2['points_difficulte'];
				
			}

			$req->execute(array('id' => $manche3J1['id_question3']));
			$q3 = $req->fetch();
			
			if($manche3J1['id_reponse3'] == $q3['id_proposition']){
				
				$pointJ1 += $q3['points_difficulte'];
				
			}
			
			
			$req = base()->prepare('SELECT * FROM manche_quizz WHERE id_manche = :id');
			$req->execute(array('id' => $game['manche3J2']));
			$manche3J2 = $req->fetch();
			
			$req = base()->prepare('SELECT id_proposition, points_difficulte FROM questions_quizz INNER JOIN propositions_quizz ON questions_quizz.id_question = propositions_quizz.id_question INNER JOIN difficulte_quizz ON questions_quizz.id_difficulte = difficulte_quizz.id_difficulte WHERE questions_quizz.id_question = :id AND reponse_question = 1');
			$req->execute(array('id' => $manche3J2['id_question1']));
			$q1 = $req->fetch();
			
			if($manche3J2['id_reponse1'] == $q1['id_proposition']){
				
				$pointJ2 += $q1['points_difficulte'];
				
			}
			
			$req->execute(array('id' => $manche3J2['id_question2']));
			$q2 = $req->fetch();
			
			if($manche3J2['id_reponse2'] == $q2['id_proposition']){
				
				$pointJ2 += $q2['points_difficulte'];
				
			}

			$req->execute(array('id' => $manche3J2['id_question3']));
			$q3 = $req->fetch();
			
			if($manche3J2['id_reponse3'] == $q3['id_proposition']){
				
				$pointJ2 += $q3['points_difficulte'];
				
			}
			
			$req = base()->prepare('UPDATE game_quizz SET scoreJ1_quizz = :J1, scoreJ2_quizz = :J2, fin_game = :id WHERE id_game = :idG');
			
			if($pointJ1 > $pointJ2){
				
				$win = $game['id_joueur1'];
				
			}elseif($pointJ1 < $pointJ2){
				
				$win  = $game['id_joueur2'];
				
			}elseif($pointJ1 == $pointJ2){
				
				$win = 129;
				
			}
			
			$req->execute(array('idG' => $game['id_game'],
								'J1' => $pointJ1,
								'J2' => $pointJ2,
								'id' => $win));
			
			
		}
		
	}
	

?>