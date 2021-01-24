<?php

include('include/header.php');

$sel_notifnl = base() -> prepare('SELECT * FROM notifications WHERE id_notifier LIKE :id AND lu = 0 ORDER BY id_notif DESC');

$sel_notifnl -> execute(array('id' => $_SESSION['profil']['id']));

foreach($sel_notifnl as $sel_notifnl)
{
	$sel_boot = base() -> prepare('SELECT * FROM boot WHERE id_boot LIKE :boot');
	
	$sel_boot -> execute(array('boot' => $sel_notifnl['id_boot']));
	
	$sel_boot = $sel_boot -> fetch();
	
	$sel_mention = base() -> prepare('SELECT * FROM utilisateur WHERE id_utilisateur LIKE :user');
	
	$sel_mention -> execute(array('user' => $sel_notifnl['id_notifieur']));
	
	$sel_mention = $sel_mention -> fetch();
	
	if($sel_notifnl['id_notifier'] != $sel_notifnl['id_notifieur'])
	{
		if(!empty($sel_notifnl['id_boot']))
		{
			if($sel_notifnl['reboot'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    				<i class="retweet icon big" style="color: black;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a reboot votre boot</span></a>
				    				
				    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
				    		</div>
		    	<?php
			}
			
			if($sel_notifnl['follow'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    				<i class="user icon big" style="color: blue;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> vous a follow</span></a>
				    		</div>
		    	<?php
			}
			
			if($sel_notifnl['like_com'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    				<i class="heart icon big"  style="color: red;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a aimé votre commentaire</span></a>
				    				
				    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
				    		</div>
		    	<?php
			}
			
			if($sel_notifnl['rep_com'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    				<i class="comment icon big"  style="color: black;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a commenté votre boot</span></a>
				    				
				    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
				    		</div>
		    	<?php
			}
			
			if($sel_notifnl['commentaire'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    				<i class="comment icon big"  style="color: black;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a commenté votre boot</span></a>
				    				
				    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
				    		</div>
		    	<?php
			}
			
			if($sel_notifnl['like_boot'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    				<i class="heart icon big" style="color: red;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a aimé votre boot</span></a>
				    				
				    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
				    		</div>
		    	<?php
			}
			
			if($sel_notifnl['mention'] == '1')
			{
				?>
					<div class="ui card center" id="aff_notif" style="width: 50%; margin-left: 25%;">
						<div class="content">
				    		<div class="meta">
				    			<a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> vous a mentionné</span></a>
				    				
				    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
				    		</div>
		    	<?php
			}
		
			?>
		    		<div class="ui divider"></div>
		    		
		    		<a href="traitement/traitement_publication.php?com=<?= $sel_boot['id_boot']?>" style="text-decoration:none; color:black;">
		    			<div class="description">
		    				<p style="word-wrap: break-word;"><?= $sel_boot['contenu_boot']?></p>
		    				
		    				<?php
		    					if(isset($sel_boot['media_boot']))
		    					{
		    						?>
		    							<img src="<?= $sel_boot['media_boot'] ?>" class="ui centered small image" />
		    						<?php
		    					}
	    					?>
		    			</div>
		    		</a>
				</div>
			</div>
		<?php
		}
	}
	
}


$sel_notifl = base() -> prepare('SELECT * FROM notifications WHERE id_notifier LIKE :id AND lu = 1 ORDER BY id_notif DESC');

$sel_notifl -> execute(array('id' => $_SESSION['profil']['id']));

foreach($sel_notifl as $sel_notifl)
{
	$sel_boot = base() -> prepare('SELECT * FROM boot WHERE id_boot LIKE :boot');
	
	$sel_boot -> execute(array('boot' => $sel_notifl['id_boot']));
	
	$sel_boot = $sel_boot -> fetch();
	
	$sel_mention = base() -> prepare('SELECT * FROM utilisateur WHERE id_utilisateur LIKE :user');
	
	$sel_mention -> execute(array('user' => $sel_notifl['id_notifieur']));
	
	$sel_mention = $sel_mention -> fetch();
	
	if($sel_notifl['id_notifier'] != $sel_notifl['id_notifieur'])
	{
		if($sel_notifl['reboot'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    				<i class="retweet icon big" style="color: green;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a reboot votre boot</span></a>
			    				
			    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
			    		</div>
	    	<?php
		}
		
		if($sel_notifl['follow'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    				<i class="user icon big" style="color: blue;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> vous a follow</span></a>
			    		</div>
	    	<?php
		}
		
		if($sel_notifl['commentaire'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    				<i class="comment icon big"  style="color: black;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a commenté votre boot</span></a>
			    				
			    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
			    		</div>
	    	<?php
		}
		
		if($sel_notifl['rep_com'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    				<i class="comment icon big"  style="color: black;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a commenté votre boot</span></a>
			    				
			    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
			    		</div>
	    	<?php
		}
		
		if($sel_notifl['like_com'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    				<i class="heart icon big"  style="color: red;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a aimé votre commentaire</span></a>
			    				
			    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
			    		</div>
	    	<?php
		}
		
		if($sel_notifl['like_boot'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    				<i class="heart icon big" style="color: red;"></i><a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> a aimé votre boot</span></a>
			    				
			    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
			    		</div>
	    	<?php
		}
		
		if($sel_notifl['mention'] == '1')
		{
			?>
				<div class="ui card center" id="aff_notif" style="width: 50%; opacity: 0.8; margin-left: 25%;">
					<div class="content">
			    		<div class="meta">
			    			<a href= "traitement/traitement_profil.php?p=<?= $sel_mention['id_utilisateur']?>"><img class="ui avatar image" src=<?= 'avatar/' . $sel_mention['pdp_utilisateur']?>> @<?= $sel_mention['pseudo_utilisateur'] ?><span style="color: black; font-weight: bold; font-size: 15px;"> vous a mentionné</span></a>
			    				
			    			<span class="right floated time"><?= dateDiff(time(), strtotime($sel_boot['date_boot']));?></span>
			    		</div>
	    	<?php
		}
	
	?>
	    		
	    		<div class="ui divider"></div>
	    		
	    		<a href="traitement/traitement_publication.php?com=<?= $sel_boot['id_boot']?>" style="text-decoration:none; color:black;">
	    			<div class="description">
	    				<p style="word-wrap: break-word;"><?= $sel_boot['contenu_boot']?></p>
	    				
	    				<?php
	    					if(isset($sel_boot['media_boot']))
	    					{
	    						?>
	    							<img src="<?= $sel_boot['media_boot'] ?>" class="ui centered small image" />
	    						<?php
	    					}
	    				?>
	    			</div>
	    		</a>
			</div>
		</div>
	<?php
	}
}


	
	$up_notif = base() -> prepare('UPDATE notifications SET lu = 1 WHERE id_notifier LIKE :mentionner');
	
	$up_notif -> execute(array('mentionner' => $_SESSION['profil']['id']));
?>