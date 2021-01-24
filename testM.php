<?php
include ('include/header.php');

?>

<html lang="en">
  <head>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="76101037801-agu5cu1nfb11e00o3ndl6ecgurdg717k.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
   <div class="g-signin2" data-onsuccess="onSignIn"></div>
    <script>
      function onSignIn(googleUser) {
		  var profile = googleUser.getBasicProfile();
		  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
		  console.log('Name: ' + profile.getName());
		  console.log('Image URL: ' + profile.getImageUrl());
		  console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
		}
    </script>
  </body>
</html>