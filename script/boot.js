
function like(idB){
	
	document.getElementById('iconUnlike'+idB).className = 'heart icon' ;
	
	const like = document.getElementById('likeForm'+idB);

	like.addEventListener('submit', function(e){
	
		e.preventDefault();
		
		const formData = new FormData(this);
		
		fetch('traitement/traitement_publication.php?like='+idB, {
			method : 'POST',
			body: formData
		}).then(function (reponse){ //Quand ça marche
			$('#post').load('script/post.php');
			return reponse.text();
		}).then(function (text){//Quand ça marche pas
			console.log(text);
		}).catch(function (error){
			console.error(error);//Quand y a une erreur
		})
		
	});
	
}

function unlike(idB){
	
	document.getElementById('iconLike'+idB).className = 'heart outline icon' ;
	
	const unlike = document.getElementById('unlikeForm'+idB);

	unlike.addEventListener('submit', function(e){
	
		e.preventDefault();
		
		const formData = new FormData(this);
		
		fetch('traitement/traitement_publication.php?unlike='+idB, {
			method : 'POST',
			body: formData
		}).then(function (reponse){ //Quand ça marche
			$('#post').load('script/post.php');
			return reponse.text();
		}).then(function (text){//Quand ça marche pas
			console.log(text);
		}).catch(function (error){
			console.error(error);//Quand y a une erreur
		})
		
	});
}

function save(idB){
	
	const save = document.getElementById('saveForm'+idB);

	save.addEventListener('submit', function(e){
	
		e.preventDefault();
		
		const formData = new FormData(this);
		
		fetch('traitement/traitement_publication.php?save='+idB, {
			method : 'POST',
			body: formData
		}).then(function (reponse){ //Quand ça marche
			$('#post').load('script/post.php');
			return reponse.text();
		}).then(function (text){//Quand ça marche pas
			console.log(text);
		}).catch(function (error){
			console.error(error);//Quand y a une erreur
		})
		
	});
}

function unsave(idB){
	
	const unsave = document.getElementById('unsaveForm'+idB);

	unsave.addEventListener('submit', function(e){
	
		e.preventDefault();
		
		const formData = new FormData(this);
		
		fetch('traitement/traitement_publication.php?unsave='+idB, {
			method : 'POST',
			body: formData
		}).then(function (reponse){ //Quand ça marche
			$('#post').load('script/post.php');
			return reponse.text();
		}).then(function (text){//Quand ça marche pas
			console.log(text);
		}).catch(function (error){
			console.error(error);//Quand y a une erreur
		})
		
	});
}

function supp(idB){
	
	
	const supp = document.getElementById('suppForm'+idB);

	supp.addEventListener('submit', function(e){
	
		e.preventDefault();
		
		const formData = new FormData(this);
		
		fetch('traitement/traitement_publication.php?suppBoot='+idB, {
			method : 'POST',
			body: formData
		}).then(function (reponse){ //Quand ça marche
			$('#post').load('script/post.php');
			return reponse.text();
		}).then(function (text){//Quand ça marche pas
			console.log(text);
		}).catch(function (error){
			console.error(error);//Quand y a une erreur
		})
		
	});
	
}