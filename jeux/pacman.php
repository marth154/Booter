<?php

include('header.php');

$req = base()->prepare('SELECT * FROM utilisateur_jeux WHERE id_utilisateur = :id AND id_jeux = 5');
	$req->execute(array('id' => $_SESSION['profil']['id']));
	$res = $req->fetch();
	
$req->closeCursor();
	
$req = base() -> query('SELECT * FROM `utilisateur_jeux` 
							INNER JOIN utilisateur ON utilisateur_jeux.id_utilisateur = utilisateur.id_utilisateur
							WHERE id_jeux = 5
							ORDER BY score_jeux DESC 
							LIMIT 0,10');

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Pacman</title>
  <link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<!DOCTYPE html>
<html>

<head>
  <script src="//cdn.jsdelivr.net/npm/phaser@3.12/dist/phaser.js"></script>
</head>

<body>
	
<div style="z-index: 5; position: absolute;  float: left;" id="score2">
<?php 

foreach($req as $req)
{
	?>

		<div style="display:inline;">
			<div style=" border-top: 1px solid yellow; margin: 15px -1px 0px 10px;">
				<a style="text-decoration: none; color: yellow;  font-size: 20px;" href=<?="../traitement/traitement_profil.php?p=" . $req['id_utilisateur'];?>>
					<p>
						<?php echo "@" . $req['pseudo_utilisateur'] . ' <br>Meilleur score : ' . $req['score_jeux']; ?>
					</p>
				</a>
			</div>
		</div>
	<?php
}

?>
</div>

<div style="z-index: 6; float: right;  margin: 15px 15px 0px 0px;">
	<div style="display:inline; ">
		<div>
			<a style="text-decoration: none; color: yellow;  font-size: 25px;" href=<?="../traitement/traitement_profil.php?p=" . $res['id_utilisateur'];?>>
				<p id="score1">
					<?php echo "Votre meilleur score : " . $res['score_jeux']; ?>
				</p>
			</a>
		</div>
	</div>
</div>

  <div class="container game-container" style="margin-top: 5%; z-index: 3;">
	<canvas id="mycanvas"></canvas>
  </div>
  <script>
	
	  var auto_refresh = setInterval(
	function ()
	{
	$('#score1').load('../script/meilleur_score_pacman.php');
}, 3000);

  var auto_refresh = setInterval(
	function ()
	{
	$('#score2').load('../script/score_pacman.php');
}, 1000);
	
	
    window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);
      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };
      return t;
    }(document, "script", "twitter-wjs"));
  </script>
</body>

</html>
<!-- partial -->
  <script  src="./script.js"></script>

</body>
</html>


<script>
	let width = 800;
let height = 625;
let gridSize = 32;
let offset = parseInt(gridSize / 2);
let config = {
  type: Phaser.CANVAS,
  width: width,
  height: height,
  canvas: document.getElementById("mycanvas"),
  physics: {
    default: "arcade",
    arcade: {
      debug: false,
      gravity: {
        x: 0,
        y: 0
      }
    }
  },
  scene: {
    preload: preload,
    create: create,
    update: update
  }
};

let game = new Phaser.Game(config);
let cursors;
let player;
let ghosts = [];
let pills;
let pillsCount = 0;
let pillsAte = 0;
let map;
let layer1;
let layer2;
let graphics;
let scoreText;
let livesImage = [];
let tiles = "pacman-tiles";
let spritesheet = "pacman-spritesheet";
let spritesheetPath = "https://raw.githubusercontent.com/kudchikarsk/phaser-pacman/master/assets/images/pacmansprites.png";
let tilesPath = "https://raw.githubusercontent.com/kudchikarsk/phaser-pacman/master/assets/images/background.png";
let mapPath =
  "https://raw.githubusercontent.com/kudchikarsk/phaser-pacman/master/assets/levels/codepen-level.json";
let Animation = {
  Player: {
    Eat: "player-eat",
    Stay: "player-stay",
    Die: "player-die"
  },
  Ghost: {
    Blue: {
      Move: "ghost-blue-move"
    },

    Orange: {
      Move: "ghost-orange-move"
    },

    White: {
      Move: "ghost-white-move"
    },

    Pink: {
      Move: "ghost-pink-move"
    },

    Red: {
      Move: "ghost-red-move"
    }
  }
};

function preload() {
  this.load.spritesheet(spritesheet, spritesheetPath, {
    frameWidth: gridSize,
    frameHeight: gridSize
  });
  this.load.tilemapTiledJSON("map", mapPath);
  this.load.image(tiles, tilesPath);
  this.load.image("pill", "https://raw.githubusercontent.com/kudchikarsk/phaser-pacman/master/assets/images/pac%20man%20pill/spr_pill_0.png");
  this.load.image("lifecounter", "https://raw.githubusercontent.com/kudchikarsk/phaser-pacman/master/assets/images/pac%20man%20life%20counter/spr_lifecounter_0.png");
}

function create() {
  this.anims.create({
    key: Animation.Player.Eat,
    frames: this.anims.generateFrameNumbers(spritesheet, { start: 9, end: 13 }),
    frameRate: 10,
    repeat: -1
  });

  this.anims.create({
    key: Animation.Player.Stay,
    frames: [{ key: spritesheet, frame: 9 }],
    frameRate: 20
  });

  this.anims.create({
    key: Animation.Player.Die,
    frames: this.anims.generateFrameNumbers(spritesheet, { start: 6, end: 8 }),
    frameRate: 1
  });

  this.anims.create({
    key: Animation.Ghost.Blue.Move,
    frames: this.anims.generateFrameNumbers(spritesheet, { start: 0, end: 1 }),
    frameRate: 10,
    repeat: -1
  });

  this.anims.create({
    key: Animation.Ghost.Orange.Move,
    frames: this.anims.generateFrameNumbers(spritesheet, { start: 4, end: 5 }),
    frameRate: 10,
    repeat: -1
  });

  this.anims.create({
    key: Animation.Ghost.White.Move,
    frames: this.anims.generateFrameNumbers(spritesheet, { start: 4, end: 5 }),
    frameRate: 10,
    repeat: -1
  });

  this.anims.create({
    key: Animation.Ghost.Pink.Move,
    frames: this.anims.generateFrameNumbers(spritesheet, {
      start: 14,
      end: 15
    }),
    frameRate: 10,
    repeat: -1
  });

  this.anims.create({
    key: Animation.Ghost.Red.Move,
    frames: this.anims.generateFrameNumbers(spritesheet, {
      start: 16,
      end: 17
    }),
    frameRate: 10,
    repeat: -1
  });

  map = this.make.tilemap({
    key: "map",
    tileWidth: gridSize,
    tileHeight: gridSize
  });
  const tileset = map.addTilesetImage(tiles);

  layer1 = map.createStaticLayer("Layer 1", tileset, 0, 0);
  layer1.setCollisionByProperty({ collides: true });

  layer2 = map.createStaticLayer("Layer 2", tileset, 0, 0);
  layer2.setCollisionByProperty({ collides: true });

  let spawnPoint = map.findObject("Objects", obj => obj.name === "Player");
  let position = new Phaser.Geom.Point(
    spawnPoint.x + offset,
    spawnPoint.y - offset
  );
  player = new Player(this, position, Animation.Player, function() {
    if (player.life <= 0) {
      newGame();
    } else {
      respawn();
    }
  });

  let scene = this;

  pills = this.physics.add.group();
  map.filterObjects("Objects", function(value, index, array) {
    if (value.name == "Pill") {
      let pill = scene.physics.add.sprite(
        value.x + offset,
        value.y - offset,
        "pill"
      );
      pills.add(pill);
      pillsCount++;
    }
  });

  let ghostsGroup = this.physics.add.group();
  let i = 0;
  let skins = [
    Animation.Ghost.Blue,
    Animation.Ghost.Red,
    Animation.Ghost.Orange,
    Animation.Ghost.Pink
  ];
  map.filterObjects("Objects", function(value, index, array) {
    if (value.name == "Ghost") {
      let position = new Phaser.Geom.Point(value.x + offset, value.y - offset);
      let ghost = new Ghost(scene, position, skins[i]);
      ghosts.push(ghost);
      ghostsGroup.add(ghost.sprite);
      i++;
    }
  });

  this.physics.add.collider(player.sprite, layer1);
  this.physics.add.collider(player.sprite, layer2);
  this.physics.add.collider(ghostsGroup, layer1);
  this.physics.add.overlap(
    player.sprite,
    pills,
    function(sprite, pill) {
      pill.disableBody(true, true);
      pillsAte++;
      player.score += 10;
      if (pillsCount == pillsAte) {
        reset();
      }
    },
    null,
    this
  );

  this.physics.add.overlap(
    player.sprite,
    ghostsGroup,
    function(sprite, ghostSprite) {
      if (player.active) {
        player.die();
        for (let ghost of ghosts) {
          ghost.freeze();
        }
      }
    },
    null,
    this
  );

  cursors = this.input.keyboard.createCursorKeys();

  graphics = this.add.graphics();

  scoreText = this.add
    .text(25, 595, "Score: " + player.score)
    .setFontFamily("Arial")
    .setFontSize(18)
    .setColor("#ffffff");
  this.add
    .text(630, 595, "Lives:")
    .setFontFamily("Arial")
    .setFontSize(18)
    .setColor("#ffffff");
  for (let i = 0; i < player.life; i++) {
    livesImage.push(this.add.image(700 + i * 25, 605, "lifecounter"));
  }

}

function respawn() {
  player.respawn();
  for (let ghost of ghosts) {
    ghost.respawn();
  }
}

function reset() {
  respawn();
  for (let child of pills.getChildren()) {
    child.enableBody(false, child.x, child.y, true, true);
  }
  pillsAte = 0;

 $.ajax({
		type: 'GET',
		url: '../traitement/traitement_jeux.php?pacman',
		data: 'score=' + encodeURIComponent(player.score),
		success: function(data)
		
		{
			if(data != "")
			{
				$('#score').append(data);
			}
		}
});
}

function newGame() {
		
  reset();
  player.life = 3;
  player.score = 0;
  for (let i = 0; i < player.life; i++) {
    let image = livesImage[i];
    if (image) {
      image.alpha = 1;
    }
  }
}

function update() {
  player.setDirections(getDirection(map, layer1, player.sprite));

  if (!player.playing) {
    for (let ghost of ghosts) {
      ghost.freeze();
    }
  }

  for (let ghost of ghosts) {
    ghost.setDirections(getDirection(map, layer1, ghost.sprite));
  }

  player.setTurningPoint(getTurningPoint(map, player.sprite));

  for (let ghost of ghosts) {
    ghost.setTurningPoint(getTurningPoint(map, ghost.sprite));
  }

  if (cursors.left.isDown) {
    player.setTurn(Phaser.LEFT);
  } else if (cursors.right.isDown) {
    player.setTurn(Phaser.RIGHT);
  } else if (cursors.up.isDown) {
    player.setTurn(Phaser.UP);
  } else if (cursors.down.isDown) {
    player.setTurn(Phaser.DOWN);
  } else {
    player.setTurn(Phaser.NONE);
  }

  player.update();

  for (let ghost of ghosts) {
    ghost.update();
  }

  scoreText.setText("Score: " + player.score);

  for (let i = player.life; i < 3; i++) {
    let image = livesImage[i];
    if (image) {
      image.alpha = 0;
    }
  }

  if (player.active) {
    if (player.sprite.x < 0 - offset) {
      player.sprite.setPosition(width + offset, player.sprite.y);
    } else if (player.sprite.x > width + offset) {
      player.sprite.setPosition(0 - offset, player.sprite.y);
    }
  }

  //drawDebug();
}

function drawDebug() {
  graphics.clear();
  player.drawDebug(graphics);
  for (let ghost of ghosts) {
    ghost.drawDebug(graphics);
  }
}

function getDirection(map, layer, sprite) {
  let directions = [];
  let sx = Phaser.Math.FloorTo(sprite.x);
  let sy = Phaser.Math.FloorTo(sprite.y);
  let currentTile = map.getTileAtWorldXY(sx, sy, true);
  if (currentTile) {
    var x = currentTile.x;
    var y = currentTile.y;

    directions[Phaser.LEFT] = map.getTileAt(x - 1, y, true, layer);
    directions[Phaser.RIGHT] = map.getTileAt(x + 1, y, true, layer);
    directions[Phaser.UP] = map.getTileAt(x, y - 1, true, layer);
    directions[Phaser.DOWN] = map.getTileAt(x, y + 1, true, layer);
  }

  return directions;
}

function getTurningPoint(map, sprite) {
  let turningPoint = new Phaser.Geom.Point();
  let sx = Phaser.Math.FloorTo(sprite.x);
  let sy = Phaser.Math.FloorTo(sprite.y);
  let currentTile = map.getTileAtWorldXY(sx, sy, true);
  if (currentTile) {
    turningPoint.x = currentTile.pixelX + offset;
    turningPoint.y = currentTile.pixelY + offset;
  }

  return turningPoint;
}

class Ghost {
  constructor(scene, position, anim) {
    this.sprite = scene.physics.add
      .sprite(position.x, position.y, "ghost")
      .setScale(0.85)
      .setOrigin(0.5);
    this.spawnPoint = position;
    this.anim = anim;
    this.speed = 100;
    this.moveTo = new Phaser.Geom.Point();
    this.safetile = [-1, 19];
    this.directions = [];
    this.opposites = [
      null,
      null,
      null,
      null,
      null,
      Phaser.DOWN,
      Phaser.UP,
      Phaser.RIGHT,
      Phaser.LEFT
    ];
    this.turning = Phaser.NONE;
    this.current = Phaser.NONE;
    this.turningPoint = new Phaser.Geom.Point();
    this.threshold = 5;
    this.rnd = new Phaser.Math.RandomDataGenerator();
    this.sprite.anims.play(anim.Move, true);
    this.turnCount = 0;
    this.turnAtTime = [4, 8, 16, 32, 64];
    this.turnAt = this.rnd.pick(this.turnAtTime);
  }

  freeze() {
    this.moveTo = new Phaser.Geom.Point();
    this.current = Phaser.NONE;
  }

  move() {
    this.move(this.rnd.pick([Phaser.UP, Phaser.DOWN]));
  }

  respawn() {
    this.sprite.setPosition(this.spawnPoint.x, this.spawnPoint.y);
    this.move(this.rnd.pick([Phaser.UP, Phaser.DOWN]));
    this.sprite.flipX = false;
  }

  moveLeft() {
    this.moveTo.x = -1;
    this.moveTo.y = 0;
    this.sprite.flipX = true;
    this.sprite.angle = 0;
  }

  moveRight() {
    this.moveTo.x = 1;
    this.moveTo.y = 0;
    this.sprite.flipX = false;
    this.sprite.angle = 0;
  }

  moveUp() {
    this.moveTo.x = 0;
    this.moveTo.y = -1;
    this.sprite.angle = 0;
  }

  moveDown() {
    this.moveTo.x = 0;
    this.moveTo.y = 1;
    this.sprite.angle = 0;
  }

  update() {
    this.sprite.setVelocity(
      this.moveTo.x * this.speed,
      this.moveTo.y * this.speed
    );
    this.turn();
    if (
      this.directions[this.current] &&
      !this.isSafe(this.directions[this.current].index)
    ) {
      this.sprite.anims.play("faceRight", true);
      this.takeRandomTurn();
    }
  }

  setDirections(directions) {
    this.directions = directions;
  }

  setTurningPoint(turningPoint) {
    this.turningPoint = turningPoint;
  }

  setTurn(turnTo) {
    if (
      !this.directions[turnTo] ||
      this.turning === turnTo ||
      this.current === turnTo ||
      !this.isSafe(this.directions[turnTo].index)
    ) {
      return false;
    }

    //console.log("turning:"+this.turning+" current:"+this.current+" turnTo:"+turnTo);

    if (this.opposites[turnTo] && this.opposites[turnTo] === this.current) {
      this.move(turnTo);
      this.turning = Phaser.NONE;
      this.turningPoint = new Phaser.Geom.Point();
    } else {
      this.turning = turnTo;
    }
  }

  takeRandomTurn() {
    let turns = [];
    for (let i = 0; i < this.directions.length; i++) {
      let direction = this.directions[i];
      if (direction) {
        if (this.isSafe(direction.index)) {
          turns.push(i);
        }
      }
    }

    if (turns.length >= 2) {
      let index = turns.indexOf(this.opposites[this.current]);
      if (index > -1) {
        turns.splice(index, 1);
      }
    }

    let turn = this.rnd.pick(turns);
    this.setTurn(turn);

    this.turnCount = 0;
    this.turnAt = this.rnd.pick(this.turnAtTime);
  }

  turn() {
    if (this.turnCount === this.turnAt) {
      this.takeRandomTurn();
    }
    this.turnCount++;

    if (this.turning === Phaser.NONE) {
      return false;
    }

    //  This needs a threshold, because at high speeds you can't turn because the coordinates skip past
    if (
      !Phaser.Math.Within(this.sprite.x, this.turningPoint.x, this.threshold) ||
      !Phaser.Math.Within(this.sprite.y, this.turningPoint.y, this.threshold)
    ) {
      return false;
    }

    this.sprite.setPosition(this.turningPoint.x, this.turningPoint.y);
    this.move(this.turning);
    this.turning = Phaser.NONE;
    this.turningPoint = new Phaser.Geom.Point();
    return true;
  }

  move(direction) {
    this.current = direction;

    switch (direction) {
      case Phaser.LEFT:
        this.moveLeft();
        break;

      case Phaser.RIGHT:
        this.moveRight();
        break;

      case Phaser.UP:
        this.moveUp();
        break;

      case Phaser.DOWN:
        this.moveDown();
        break;
    }
  }

  isSafe(index) {
    for (let i of this.safetile) {
      if (i === index) return true;
    }

    return false;
  }

  drawDebug(graphics) {
    let thickness = 4;
    let alpha = 1;
    let color = 0x00ff00;
    for (var t = 0; t < 9; t++) {
      if (this.directions[t] === null || this.directions[t] === undefined) {
        continue;
      }

      if (!this.isSafe(this.directions[t].index)) {
        color = 0xff0000;
      } else {
        color = 0x00ff00;
      }

      graphics.lineStyle(thickness, color, alpha);
      graphics.strokeRect(
        this.directions[t].pixelX,
        this.directions[t].pixelY,
        32,
        32
      );
    }

    color = 0x00ff00;
    graphics.lineStyle(thickness, color, alpha);
    graphics.strokeRect(this.turningPoint.x, this.turningPoint.y, 1, 1);
  }
}

class Player {
  constructor(scene, position, anim, dieCallback) {
    this.sprite = scene.physics.add
      .sprite(position.x, position.y, "pacman")
      .setScale(0.9)
      .setOrigin(0.5);
    this.spawnPoint = position;
    this.anim = anim;
    this.dieCallback = dieCallback;
    this.speed = 95;
    this.moveTo = new Phaser.Geom.Point();
    this.sprite.angle = 180;
    this.safetile = [-1, 18];
    this.directions = [];
    this.opposites = [
      null,
      null,
      null,
      null,
      null,
      Phaser.DOWN,
      Phaser.UP,
      Phaser.RIGHT,
      Phaser.LEFT
    ];
    this.turning = Phaser.NONE;
    this.current = Phaser.NONE;
    this.turningPoint = new Phaser.Geom.Point();
    this.threshold = 5;
    this.life = 3;
    this.score = 0;
    this.active = true;
    this.sprite.anims.play(this.anim.Stay, true);
    let ref = this;
    this.sprite.on(
      "animationcomplete",
      function(animation, frame) {
        ref.animComplete(animation, frame);
      },
      scene
    );
    this.playing = false;
  }

  die() {
    this.active = false;
    this.playing = false;
    this.life--;
    this.moveTo = new Phaser.Geom.Point();
    this.sprite.anims.play(this.anim.Die, true);
  }

  animComplete(animation, frame) {
    if (animation.key == this.anim.Die) {
      this.dieCallback();
    }
  }

  respawn() {
    this.active = true;
    this.playing = false;
    this.sprite.setPosition(this.spawnPoint.x, this.spawnPoint.y);
    this.moveTo = new Phaser.Geom.Point();
    this.sprite.anims.play(this.anim.Stay, true);
    this.sprite.angle = 180;
    this.turning = Phaser.NONE;
    this.current = Phaser.NONE;
  }

  moveLeft() {
    this.moveTo.x = -1;
    this.moveTo.y = 0;
    this.sprite.anims.play(this.anim.Eat, true);
    this.sprite.angle = 180;
  }

  moveRight() {
    this.moveTo.x = 1;
    this.moveTo.y = 0;
    this.sprite.anims.play(this.anim.Eat, true);
    this.sprite.angle = 0;
  }

  moveUp() {
    this.moveTo.x = 0;
    this.moveTo.y = -1;
    this.sprite.anims.play(this.anim.Eat, true);
    this.sprite.angle = 270;
  }

  moveDown() {
    this.moveTo.x = 0;
    this.moveTo.y = 1;
    this.sprite.anims.play(this.anim.Eat, true);
    this.sprite.angle = 90;
  }

  update() {
    this.sprite.setVelocity(
      this.moveTo.x * this.speed,
      this.moveTo.y * this.speed
    );
    this.turn();
    if (
      this.directions[this.current] &&
      !this.isSafe(this.directions[this.current].index)
    ) {
      this.sprite.anims.play("faceRight", true);
    }
  }

  setDirections(directions) {
    this.directions = directions;
  }

  setTurningPoint(turningPoint) {
    this.turningPoint = turningPoint;
  }

  setTurn(turnTo) {
    if (
      !this.active ||
      !this.directions[turnTo] ||
      this.turning === turnTo ||
      this.current === turnTo ||
      !this.isSafe(this.directions[turnTo].index)
    ) {
      return false;
    }

    if (this.opposites[turnTo] && this.opposites[turnTo] === this.current) {
      this.move(turnTo);
      this.turning = Phaser.NONE;
      this.turningPoint = new Phaser.Geom.Point();
    } else {
      this.turning = turnTo;
    }
  }

  turn() {
    if (this.turning === Phaser.NONE) {
      return false;
    }

    //  This needs a threshold, because at high speeds you can't turn because the coordinates skip past
    if (
      !Phaser.Math.Within(this.sprite.x, this.turningPoint.x, this.threshold) ||
      !Phaser.Math.Within(this.sprite.y, this.turningPoint.y, this.threshold)
    ) {
      return false;
    }

    this.sprite.setPosition(this.turningPoint.x, this.turningPoint.y);
    this.move(this.turning);
    this.turning = Phaser.NONE;
    this.turningPoint = new Phaser.Geom.Point();
    return true;
  }

  move(direction) {
    this.playing = true;
    this.current = direction;

    switch (direction) {
      case Phaser.LEFT:
        this.moveLeft();
        break;

      case Phaser.RIGHT:
        this.moveRight();
        break;

      case Phaser.UP:
        this.moveUp();
        break;

      case Phaser.DOWN:
        this.moveDown();
        break;
    }
  }

  isSafe(index) {
    for (let i of this.safetile) {
      if (i === index) return true;
    }

    return false;
  }

  drawDebug(graphics) {
    let thickness = 4;
    let alpha = 1;
    let color = 0x00ff00;

    for (var t = 0; t < 9; t++) {
      if (this.directions[t] === null || this.directions[t] === undefined) {
        continue;
      }

      if (this.directions[t].index !== -1) {
        color = 0xff0000;
      } else {
        color = 0x00ff00;
      }

      graphics.lineStyle(thickness, color, alpha);
      graphics.strokeRect(
        this.directions[t].pixelX,
        this.directions[t].pixelY,
        32,
        32
      );
    }

    color = 0x00ff00;
    graphics.lineStyle(thickness, color, alpha);
    graphics.strokeRect(this.turningPoint.x, this.turningPoint.y, 1, 1);
  }
}
</script>


<style>
	html,
body {
  margin: 0px;
  padding: 0px;
  width: 100%;
  overflow: hidden;
  background-color: black;
}

.container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.social-links {
  padding: 5px;
  width: 560px;
}

#pacman-banner {
  font-family: Arial;
  font-weight: bold;
  font-size: 21pt;
  margin-right: 100px;
  color: yellow;
  float: left;
}

.social-buttons {
  float: right;
  position: relative;
  top: 5px;
}

.game-container {
  transform: scale(0.7) translateY(-20%);
}
</style>