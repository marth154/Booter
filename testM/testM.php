<?php 

include('../include/header.php');

require('config.php');

?>

<p>
	<a href="https://accounts.google.com/o/oauth2/v2/auth?scope=email&access_type=online&redirect_uri=<?= urlencode('http://booter.lcdfbtssio.fr/testM/connect.php') ?>&response_type=code&client_id=<?= GOOGLE_ID ?>">Se connecter</a>
</p>