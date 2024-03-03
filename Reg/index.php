<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

?>

<!DOCTYPE html>
<html>
<head>
	<title>My website</title>
</head>
<body>

<div class="navbar">
      <div class="site">ALUMINITY</div>
      <br>
      <div class="site-menu">
        <div class="menu-item">ACCESS</div>
        <div class="menu-item">GROW</div>
        <div class="menu-item">ABOUT US</div>
        <div class="menu-item">Hello, <?php echo $user_data['user_name']; ?></div>
      </div>
    </div>
    <p class="title">Aluminity</p>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
    <script>
      var textWrapper = document.querySelector(".title");
      textWrapper.innerHTML = textWrapper.textContent.replace(
        /\S/g,
        "<span class='letter'>$&</span>"
      );

      anime.timeline().add({
        targets: ".title .letter",
        translateY: [-200, 0],
        easing: "easeOutExpo",
        duration: 1400,
        delay: (el, i) => 1000 + 60 * i,
      });

      TweenMax.staggerFrom(
        ".container > .block",
        2,
        {
          y: "110%",
          ease: Expo.easeInOut,
          delay: 1.5,
        },
        0.4
      );
      TweenMax.to(".overlay", 0.5, {
        y: "100%",
        ease: Expo.easeInOut,
        delay: 1.2,
      });

      TweenMax.to(".container", 2, {
        scale: "2",
        y: "90%",
        ease: Expo.easeInOut,
        delay: 5.2,
      });

      TweenMax.staggerFrom(
        ".navbar > div",
        1.6,
        {
          opacity: 0,
          y: -100,
          ease: Expo.easeInOut,
          delay: 7,
        },
        0.08
      );
      TweenMax.staggerFrom(
        ".site-menu > div",
        2,
        {
          opacity: 0,
          y: -100,
          ease: Power2.easeOut,
          delay: 6.5,
        },
        0.1
      );
    </script>
    <style>
      * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: #e3dcc7;
}

.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  height: 100px;
  padding: 0 40px;
  display: flex;
  justify-content: space-between;
  font-family: "Kobe", Helvetica, sans-serif;
  font-weight: 400;
  text-transform: uppercase;
  line-height: 100px;
  color: #6d6458;
}

.site-menu {
  display: flex;
}

.menu-item {
  margin-left: 60px;
}

.container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  /* border: 1px solid red; */
  width: 400px;
  height: 300px;
}

.block {
  position: absolute;
  width: 100%;
  height: 100%;
}
.overlay {
  position: absolute;
  width: 100%;
  height: 50vh;
  bottom: -10rem;
  background: #e3dcc7;
}

.title {
  margin-top: 20rem;
  text-align: center;
  font-family: "Canopee";
  font-size: 10em;
  color: #393833;
  overflow: hidden;
}

.title .letter {
  display: inline-block;
  line-height: 1em;
}
.title{
    font-size: 50px;
    letter-spacing: 7px;
    transition: 1s ;
    cursor: pointer;
    font-size: 150px;
}
.title:hover{
    transition: 1s ease;
    color: rgb(255, 255, 255);
}
    </style>
	
</body>
</html>