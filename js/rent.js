$(function() {
		$(document).tooltip({
      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        }
      }
    });
	});
	
	var blackouts = [];

	function removeBlackouts(){
		console.log("Clearing Blackouts");
		$(".blackout").remove();
		blackouts.length = 0;
		$('#rangeError').html('');
	}
	
	function addBlackout(startTime, endTime){
		if(startTime > endTime)
			return;
		
		blackouts.push([startTime, endTime]);
			
		var startPercent = startTime / 1440 * 100;
		var widthPercent = (endTime - startTime) / 1440 * 100;
		
		$("#slider-range").children(".ui-slider-range")
		                  .last()
						  .after("<div class=\"ui-slider-range ui-widget-header ui-corner-all blackout\" style=\"left: "
						          + startPercent 
								  + "%; width: "
								  + widthPercent
								  + "%;\" title=\"Unavailable: "
								  + minutesToClock(startTime)
								  + " - "
								  + minutesToClock(endTime)
								  + "\"></div>");
	}
	
	function refreshBlackouts(){
		removeBlackouts();
		
		date = $("#datepicker").val();
		if(!date)
			return;
			
		puppy = $("#pupSelect").val();
		
		if(!puppy)
			return;
		
		$.post('queryReservations.php', "date="+date+"&puppy="+puppy, function(response) {
			// log the response to the console
			console.log("Response: "+response);
			if(response.indexOf("NO") == -1){
				var times = response.split(",");
				for(var i = 0; i < times.length; i++){
					if(times[i] == "" || times[i].indexOf("-") == -1)
						continue;
					
					var time = times[i].split("-");
					if(time.length < 2 || time[0] == "" || time[1] == "")
						continue;
						
					addBlackout(parseInt(time[0]), parseInt(time[1]));
				}
			}
			validateTime();
		});
	}
	
	function validateTime(values){
		if(!values)
			values = $("#slider-range").slider("values");
		
		$('#rangeError').html('');
		for (var i = 0; i < blackouts.length; i++){
			if (blackouts[i] && blackouts[i].length >= 2 && 
			    ((blackouts[i][0] >= values[0] && blackouts[i][0] <= values[1]) || 
				 (blackouts[i][1] >= values[0] && blackouts[i][1] <= values[1]))){
				$('#rangeError').html('Please select an available time slot.');
				return false;
			}
		}
		
		return true;
	}

  $(function() {
    $( "#datepicker" ).datepicker({
		minDate: 0,
		dateFormat: "yy-mm-dd",
		onSelect: function (dateText, inst) {refreshBlackouts();}
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
		document.getElementById("startTimeValue").value = ui.values[0];
		
        $('#stopTime').html(stopTime);
		document.getElementById("stopTimeValue").value = ui.values[1];
		
		$('#duration').html(duration);
		
		$('#price').html('$' + price);
		
		validateTime(ui.values);
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
		var $next = $('#pup' + pupSelect.value);
		
		if ( $next.length != 0 ) {
			var $active = $('#selectedPup IMG.selected');
			if ( $active.length != 0 )
				$active.removeClass('selected');
				
			$next.addClass('selected');			
		}
		
		refreshBlackouts();
	}

