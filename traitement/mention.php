<?php 

include('../include/fonction_bdd.php');


if(isset($_GET['user']))
{
	$user = explode(' ', trim($_GET['user']));
	
	if(isset($user[sizeof($user) - 1][0]))
	{
		if($user[sizeof($user) - 1][0] == '$')
		{
			$pseudo = str_replace("$", "", $user[sizeof($user) - 1]);
			
			$req = base() -> prepare('SELECT * FROM utilisateur WHERE UCASE(boot_name) LIKE UCASE(:pseudo) LIMIT 5');

			$req -> execute(array('pseudo' => "$pseudo%"));
			
			if($req -> rowCount() != 0)
			{
				$i = 0;
				foreach($req as $req)
				{
					$i += 1;
					?>
						<div style="display:inline; width: 100%;" onclick="focusMethod()">
							<div style="border-bottom: 1px solid black; margin: 0px -1px 0px -1px; cursor: pointer;"  onClick="pseudo(<?= $i ?>);">
									<p>	
										<img  for="search1" src="<?= '../avatar/' . $req['pdp_utilisateur'];?>" class="ui image circular" style="margin-left: 10px; width: 20%; margin-top: 5px;">			
									
										<em id="pseudo<?= $i ?>" style="font-size: 20px;">@<?=$req['boot_name'];?></em>
									</p>
							</div>
						</div>
					<?php
				}
			}
		}
	}
	
?>

<script>
	function pseudo(i)
	{
		var pseudo = document.getElementById('pseudo' + i).innerHTML.replace("@", '');
		
		document.getElementById('search1').value += document.getElementById('pseudo' + i).innerHTML.replace("@", '');
		
		var string = document.getElementById('search1').value.split(' ');
		
		document.getElementById('search1').value = "";
		
		for (var i = 0; i < string.length; i++)
		{
			if(i + 1 != string.length)
			{
				document.getElementById('search1').value += string[i] + " ";
			}
		}
		
		document.getElementById('search1').value += "$" + pseudo + " ";
	}
	
	function focusMethod()
	{
		document.getElementById("search1").focus();
	}
</script>

<?php
	
}
?>