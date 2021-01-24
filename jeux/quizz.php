<?php
	
	include('header.php');

?>

<div class="ui equal width grid" style="margin-top: 1%;">
	<div class="column" id="sub">
		<div class="ui segment" style="text-align: center;">
			Vos parties en cours
		</div>
		<div class="ui loading segment">
			
		</div>
	</div>
	<div class="column" id="game">
		<div class="ui middle aligned center aligned grid">
		  <div class="column">
		    <h2 class="ui teal image header">
		      <div class="content">
		        LE GRAND QUIZZ
		      </div>
		    </h2>
		    <div class="ui centered cards" >
				<div class="card">
				  <div class="content" onclick="$('#game').load('../script/quizz.php?newGame')" style="cursor: pointer;">
				    <div class="header">Jouer</div>
				    <p></p>
				  </div>
				</div>
				<div class="card">
				  <div class="content">
				    <div class="header">Scores</div>
				    <p></p>
				  </div>
				</div>
			</div>
			<div class="ui segment" onclick="$('#game').load('../script/quizz.php?submit')" style="cursor: pointer;">
				<div class="content">
					<h3 class="header">Proposer une question</h3>
				</div>
			</div>
		  </div>
		</div>
	</div>
	<div class="column" id="resultGame">
		<div class="ui segment" style="text-align: center;">
			Vos résultats récents
		</div>
		<div class="ui loading segment">
			
		</div>
	</div>
</div>
<script>
	var auto_refresh = setInterval(
	function ()
	{
	$('#sub').load('../script/quizz.php?gameList');
	$('#resultGame').load('../script/quizz.php?resultGameList');
}, 1500);
	

</script>
