<?php echo file_get_contents("header.html"); ?>

<h2>Rent!</h2>

<div id="rentalContent">
Renting a puppy is easy! Simply fill out the information below, and we will contact you to confirm availability. All of our pups are billed at an hourly rate of $5/hr.<br />

<form id="contactForm" action="processRental.php" method="post">
	<div class="contactGroup">
    	<h3>Customer Information</h3>
        <table>
        	<tr>
				<td class="label"><label for="name">Name: </label></td>
                <td class="inputs"><input id="name" name="name"/></td>
            </tr>
            <tr>
            	<td class="label"><label for="email">Email: </label></td>
                <td class="inputs"><input id="email" name="email"/></td>
            </tr>
            <tr>
            	<td class="label"><label for="phone">Phone: </label></td>
                <td class="inputs"><input id="phone" name="phone"/></td>
            </tr>
        </table>
    </div>
    <div class="contactGroup">
    	<?php
			// Connect to MySQL.
			require ('../../rentapup_sql_connect.php');

			if(!$dbc)
			{
				die('Oh no! We can\'t find the puppies!');
			}

			$q = "SELECT * FROM puppies";
			$r = @mysqli_query ($dbc, $q); // Run the query.
			$puppies = array();
	
			if($r)
			{
				while ($row = mysqli_fetch_array($r))
				{
					$puppies[$row['puppy_id']] = $row['name'];
				}
			}
		?>
    
    	<div id="selectedPup">
        	<?php
				$first = True;
				
				foreach ($puppies as $id => $name)
				{
					if($first)
					{
						echo "<img src=\"img/pup".$id.".png\" id=\"pup".$id."\" alt=\"".$name."\" class=\"selected\">";
						$first = False;
					}
					else
					{
						echo "<img src=\"img/pup".$id.".png\" id=\"pup".$id."\" alt=\"".$name."\">";
					}
				}
			?>
    	</div>
    	<div id="resInfo">
    		<h3>Reservation Information</h3>
            <table>
            	<tr>
    				<td class="label"><label for="pupSelect">Puppy: </label></td>
					<td class="inputs">
                    <select onChange="pupChanged(this)" id="pupSelect" name="pupSelect">
                    	<?php
							foreach ($puppies as $id => $name)
							{
								echo "<option value=\"".$id."\">".$name."</option>";
							}
						?>
					</select>
                    </td>
                </tr>
                <tr>
    				<td class="label"><label for="datepicker">Date: </label></td>
                    <td class="inputs"><input id="datepicker" name="datepicker" onchange="refreshBlackouts()"></td>
                </tr>
                <tr>
                	<td class="label">Time: </td>
                    <td class="inputs">
                    	<span id="startTime">10:00 AM</span> - <span id="stopTime">4:00 PM</span> (<span id="duration">6</span> hours)
                        <input type="hidden" id="startTimeValue" name="startTimeValue" value="600">
                        <input type="hidden" id="stopTimeValue" name="stopTimeValue" value="960">
                    </td>
                </tr>
                <tr>
                	<td></td>
                    <td class="inputs"><div id="slider-range"></div></td>
                </tr>
                <tr>
                	<td></td>
                    <td class="inputs"><span id="rangeError"></span></td>
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
<script src="js/rentValidator.js"></script>
<script src="js/rent.js"></script>
    
<?php echo file_get_contents("footer.html"); ?>