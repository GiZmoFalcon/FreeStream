<!DOCTYPE html>
<html>
<title>freestream</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Karma", sans-serif}
.w3-bar-block .w3-bar-item {padding:20px}
</style>
<body>

<!-- Sidebar (hidden by default) -->
<nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:40%;min-width:300px" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()"
  class="w3-bar-item w3-button">Close Menu</a>
  <a href="#tv" onclick="w3_close()" class="w3-bar-item w3-button">TV Series</a>
  <a href="#anime" onclick="w3_close()" class="w3-bar-item w3-button">Anime</a>
</nav>

<!-- Top menu -->
<div class="w3-top">
  <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
    <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
    <div class="w3-small w3-right w3-padding-16">Version: 1.0</div>
    <div class="w3-center w3-padding-16">freestream</div>
  </div>
</div>
  
<!-- !PAGE CONTENT! -->
<div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">


  <!-- First Photo Grid-->
  <div class="w3-row-padding w3-padding-16 w3-center" id="food">
    <div class="w3-quarter">
      <img src="https://image.tmdb.org/t/p/w370_and_h556_bestv2/nGsNruW3W27V6r4gkyc3iiEGsKR.jpg" alt="Sandwich" style="width:100%">
      <h3>The Big Bang Theory</h3>
      <p>Just some random text, lorem ipsum text praesent tincidunt ipsum lipsum.</p>
    </div>
    <div class="w3-quarter">
      <img src="https://image.tmdb.org/t/p/w370_and_h556_bestv2/yp94aOXzuqcQHva90B3jxLfnOO9.jpg" alt="Steak" style="width:100%">
      <h3>Westworld</h3>
      <p>Once again, some random text to lorem lorem lorem lorem ipsum text praesent tincidunt ipsum lipsum.</p>
    </div>
    <div class="w3-quarter">
      <img src="https://image.tmdb.org/t/p/w370_and_h556_bestv2/a906PH7CDmSOdS7kmnAgdWk5mhv.jpg" alt="Cherries" style="width:100%">
      <h3>Mindhunter</h3>
      <p>Lorem ipsum text praesent tincidunt ipsum lipsum.</p>
      <p>What else?</p>
    </div>
    <div class="w3-quarter">
      <img src="https://image.tmdb.org/t/p/w370_and_h556_bestv2/2qou2R47XZ1N6SlqGZcoCHDyEhN.jpg" alt="Pasta and Wine" style="width:100%">
      <h3>Supergirl</h3>
      <p>Lorem ipsum text praesent tincidunt ipsum lipsum.</p>
    </div>
  </div>
  
  <hr id="about">
<div class="w3-container w3-padding-32 w3-center">  
    <h3>The Big Bang Theory</h3><br>
    <img src="https://image.tmdb.org/t/p/w1000_and_h563_face/ooBGRQBdbGzBxAVfExiO8r7kloA.jpg" alt="popular" class="w3-image" style="display:block;margin:auto" width="800" height="533">
    <div class="w3-padding-32">
      <h4><b>This is a currently popular</b></h4>
      <h6><i>Some info</i></h6>
      <p>The Big Bang Theory is centered on five characters living in Pasadena, California: roommates Leonard Hofstadter and Sheldon Cooper; Penny, a waitress and aspiring actress who lives across the hall; and Leonard and Sheldon's equally geeky and socially awkward friends and co-workers, mechanical engineer Howard Wolowitz and astrophysicist Raj Koothrappali. The geekiness and intellect of the four guys is contrasted for comic effect with Penny's social skills and common sense.</p>
    </div>
  </div>
  <hr>
  


  
  

  <!-- About Section -->
  
  
  <!-- Footer -->
  <footer class="w3-row-padding w3-padding-32">
    
  </footer>

<!-- End page content -->
</div>

<script>
// Script to open and close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
}
 
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
}
</script>

</body>
</html>
