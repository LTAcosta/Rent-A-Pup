<?php echo file_get_contents("header.html"); ?>

<h2>Hello!</h2>

Welcome to Rent-A-Pup! We are a small start up company that feels everyone should have the opportunity to love and play with puppies any time they want to!<br><br>

The idea started when our CEO brought his little puppy Benjamin over to his friends' apartment. He saw how much fun his friends were having, and asked why they haven't gotten a puppy of their own. It turns out that, as much as they wanted one, it wasn't allowed by their landlord. It was at that moment that Rent-A-Pup was born!

<div class="container">
	<div id="slideshow">
		<?php
			$files = glob("img/slides/*.jpg");
			foreach ($files as $file){
			    echo '<img src="', $file, '" alt="Puppy slide">';
			}
		?>
	</div>
</div>

<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/jquery.slides.min.js"></script>

<script>

function slideSwitch() {
    // Grabs the currently active image
    var $active = $('#slideshow IMG.active');

    // If no image is active, start with the last image so that the first image is the first to appear.
    if ( $active.length == 0 ) $active = $('#slideshow IMG:last');

    // Grab the next image. If we're at the end, get the first.
    var $next =  $active.next().length ? $active.next()
        : $('#slideshow IMG:first');

    $active.addClass('last-active');
        
	// Animate the fading between images
    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 1000, function() {
            $active.removeClass('active last-active');
        });
}

$(function() {
	// Set a timer to periodically trigger a new slide
    slideSwitch();
    setInterval( "slideSwitch()", 5000 );
});

function updateHeight() {
	// Since the slides are fading using the z-index, they act as if
	// they are floating. Until I find a better solution, I am addressing
	// this by making sure the div is always the same height as the images.
	
	// Retrieve the active image
    var $active = $('#slideshow IMG.active');
    if ( $active.length == 0 ) $active = $('#slideshow IMG:last');
	
	// Resize the slideshow div to match the image height
	$('#slideshow').css("height", $active.css("height"))
}

$(document).ready(function () {
    updateHeight();
    $(window).resize(function() {
        updateHeight();
    });
});

</script>
    
<?php echo file_get_contents("footer.html"); ?>