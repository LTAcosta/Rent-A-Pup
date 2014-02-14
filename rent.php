<?php echo file_get_contents("header.html"); ?>

<h2>Rent!</h2>

<div id="rentalContent">
Renting a puppy is easy! Simply fill out the information below, and we will contact you to confirm availability. All of our pups are billed at an hourly rate of $5/hr.<br />

<form id="contactForm" action="processRental.php" method="post">
	<div class="contactGroup">
    	<h3>Customer Information</h3>
        <table>
        	<tr>
				<td  class="label"><label for="name">Name: </label></td>
                <td><input id="name" name="name"/></td>
            </tr>
            <tr>
            	<td class="label"><label for="email">Email: </label></td>
                <td><input id="email" name="email"/></td>
            </tr>
            <tr>
            	<td class="label"><label for="phone">Phone: </label></td>
                <td><input id="phone" name="phone"/></td>
            </tr>
        </table>
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
            <table>
            	<tr>
    				<td class="label"><label for="pupSelect">Puppy: </label></td>
					<td>
                    <select onChange="pupChanged(this)" id="pupSelect" name="pupSelect">
						<option value="Charles">Charles</option>
    					<option value="Chunk">Chunk</option>
    					<option value="Goose">Goose</option>
    					<option value="Sheila">Sheila</option>
					</select>
                    </td>
                </tr>
                <tr>
    				<td class="label"><label for="datepicker">Date: </label></td>
                    <td><input id="datepicker" name="datepicker"></td>
                </tr>
                <tr>
                	<td class="label">Time: </td>
                    <td>
                    	<span id="startTime">10:00 AM</span> - <span id="stopTime">4:00 PM</span> (<span id="duration">6</span> hours)
                        <input type="hidden" id="startTimeValue" name="startTimeValue" value="10:00 AM">
                        <input type="hidden" id="stopTimeValue" name="stopTimeValue" value="4:00 PM">
                        <input type="hidden" id="durationValue" name="durationValue" value="6">
                    </td>
                </tr>
                <tr>
                	<td></td>
                    <td><div id="slider-range"></div></td>
                </tr>
            </table>
    	</div>
    </div>
    <br />
    <center>
    	Price: <b><span id="price">$30.00</span></b>
    	<input type="submit" id="submit" value="Submit">
    </center>
</form>
</div>

<div id="response"></div>

<script src="js/jquery.form.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>
<script src="js/contact.js"></script>

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
		var startTime = minutesToClock(ui.values[0]);
		var stopTime = minutesToClock(ui.values[1]);
		var duration = (ui.values[1] / 60) - (ui.values[0] / 60);
		var price = (duration * 5).toFixed(2);

		$('#startTime').html(startTime);
		document.getElementById("startTimeValue").value = startTime;
		
        $('#stopTime').html(stopTime);
		document.getElementById("stopTimeValue").value = stopTime;
		
		$('#duration').html(duration);
		document.getElementById("durationValue").value = duration;
		
		$('#price').html('$' + price);
    }
});

	function minutesToClock(time){
		if(time == 1440) time = 1439;
		
		var hours = Math.floor(time / 60);
        var minutes = time - (hours * 60);

        if (hours.length == 1) hours = '0' + hours;
        if (minutes.length == 1) minutes = '0' + minutes;
        if (minutes == 0) minutes = '00';
        if (hours >= 12) {
            if (hours > 12) hours = hours - 12;
			minutes = minutes + " PM";
        } else {
            minutes = minutes + " AM";
        }
        if (hours == 0) hours = 12;
		
		return hours + ':' + minutes;
	}

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