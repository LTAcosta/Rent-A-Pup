<?php echo file_get_contents("header.html"); ?>

<h2>Hello!</h2>

Welcome to Rent-A-Pup! We are a small start up company that feels everyone should have the opportunity to love and play with puppies any time they want to!<br><br>

The idea started when our CEO brought his little puppy Benjamin over to his friends' apartment. He saw how much fun his friends were having, and asked why they haven't gotten a puppy of their own. It turns out that, as much as they wanted one, it wasn't allowed by their landlord. It was at that moment that Rent-A-Pup was born!

<div class="container">
  <div id="slides">
      <img src="img/slides/slide1.jpg" alt="Puppy slide">
      <img src="img/slides/slide2.jpg" alt="Puppy slide">
      <img src="img/slides/slide3.jpg" alt="Puppy slide">
      <img src="img/slides/slide4.jpg" alt="Puppy slide">
      <img src="img/slides/slide5.jpg" alt="Puppy slide">
      <img src="img/slides/slide6.jpg" alt="Puppy slide">
      <img src="img/slides/slide7.jpg" alt="Puppy slide">
      <img src="img/slides/slide8.jpg" alt="Puppy slide">
      <img src="img/slides/slide9.jpg" alt="Puppy slide">
      <img src="img/slides/slide10.jpg" alt="Puppy slide">
      <img src="img/slides/slide11.jpg" alt="Puppy slide">
      <img src="img/slides/slide12.jpg" alt="Puppy slide">
      <img src="img/slides/slide13.jpg" alt="Puppy slide">
  </div>
</div>

<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/jquery.slides.min.js"></script>

<script>
  $(function() {
	$('#slides').slidesjs({
	  width: 600,
	  height: 300,
      play: {
          active: false,
          auto: true,
          interval: 4000,
          swap: true
      },
	  pagination: {
		  active: false
	  },
	  navigation: {
		  active: false
	  }
	});
  });
</script>
    
<?php echo file_get_contents("footer.html"); ?>