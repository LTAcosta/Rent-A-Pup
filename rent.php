<?php echo file_get_contents("header.html"); ?>

<h2>Rent!</h2>

Renting a puppy is easy! Simply fill out the information below, and we will contact you to confirm availability. All of our pups are billed at an hourly rate of $5/hr.<br />

<form>
	<div class="contactGroup">
    	<h3>Customer Information</h3>
		Name: <input type="text" name="Name" />
    	<br />
    	Email: <input type="text" name="Email" />
    	<br />
    	Phone: <input type="text" name="Phone" />
    </div>
    <div class="contactGroup">
    	<div id="selectedPup">
    		<img src="img/pup1.png" id="Charles" alt="Charles" class="selected">
    		<img src="img/pup2.png" id="Chunk" alt="Chunk">
    		<img src="img/pup3.png" id="Goose" alt="Goose">
    		<img src="img/pup4.png" id="Sheila" alt="Sheila">
    	</div>
    	<div id="resInfo">
    		<h3>Reservation Information</h3>
    		Puppy: 
			<select onChange="pupChanged(this)" id="pupSelect">
				<option value="Charles">Charles</option>
    			<option value="Chunk">Chunk</option>
    			<option value="Goose">Goose</option>
    			<option value="Sheila">Sheila</option>
			</select>
    		<br />
    		Date: <input type="text" id="datepicker">
    		<br />
    		<div id="time-range">
    			Time: <span id="startTime">10:00 AM</span> - <span id="stopTime">4:00 PM</span> (<span id="duration">6</span> hours)
    			<div id="slider-range"></div>
			</div>
    	</div>
    </div>
    <br />
    <center>
    	Price: <b><span id="price">$30.00</span></b>
    	<input type="submit" value="Submit">
    </center>
</form>

<script>
  $(function() {
    $( "#datepicker" ).datepicker({
		minDate: 0
		});
  });
  
  $("#slider-range").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 15,
    values: [600, 960],
    slide: function (e, ui) {
        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);

        if (hours1.length == 1) hours1 = '0' + hours1;
        if (minutes1.length == 1) minutes1 = '0' + minutes1;
        if (minutes1 == 0) minutes1 = '00';
        if (hours1 >= 12) {
            if (hours1 == 12) {
                hours1 = hours1;
                minutes1 = minutes1 + " PM";
            } else {
                hours1 = hours1 - 12;
                minutes1 = minutes1 + " PM";
            }
        } else {
            hours1 = hours1;
            minutes1 = minutes1 + " AM";
        }
        if (hours1 == 0) {
            hours1 = 12;
            minutes1 = minutes1;
        }

        $('#startTime').html(hours1 + ':' + minutes1);

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';
        if (hours2 >= 12) {
            if (hours2 == 12) {
                hours2 = hours2;
                minutes2 = minutes2 + " PM";
            } else if (hours2 == 24) {
                hours2 = 11;
                minutes2 = "59 PM";
            } else {
                hours2 = hours2 - 12;
                minutes2 = minutes2 + " PM";
            }
        } else {
            hours2 = hours2;
            minutes2 = minutes2 + " AM";
        }

        $('#stopTime').html(hours2 + ':' + minutes2);
		
		var duration = (ui.values[1] / 60) - (ui.values[0] / 60);
		$('#duration').html(duration);
		
		var price = duration * 5;
		$('#price').html('$' + price.toFixed(2));
    }
});

	function pupChanged(pupSelect){
		var $next = $('#' + pupSelect.value);
		
		if ( $next.length != 0 ) {
			var $active = $('#selectedPup IMG.selected');
			if ( $active.length != 0 )
				$active.removeClass('selected');
				
			$next.addClass('selected');			
		}
	}
</script>
    
<?php echo file_get_contents("footer.html"); ?>