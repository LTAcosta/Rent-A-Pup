<?php echo file_get_contents("header.html"); ?>

<h2>Contact Us!</h2>

<div id="contactContent">
Have any comments or questions? Fill out the form below, and we will get back to you as soon as possible!

<form id="contactForm" action="processContact.php" method="post">
	<div class="contactGroup">
    	<h3>Customer</h3>
        <table>
        	<tr>
				<td class="label"><label for="name">Name: </label></td>
                <td class="inputs"><input id="name" name="name"/></td>
            </tr>
            <tr>
            	<td class="label"><label for="email">Email: </label></td>
                <td class="inputs"><input id="email" name="email"/></td>
            </tr>
        </table>
    </div>
    <div class="contactGroup">
    	<h3>Email</h3>
            <table>
        	<tr>
				<td class="label"><label for="subject">Subject: </label></td>
                <td class="inputs"><input id="subject" name="subject"/></td>
            </tr>
            <tr>
            	<td class="label"><label for="message">Message: </label></td>
                <td class="inputs"><textarea id="message" name="message" rows="5" cols="20"></textarea></td>
            </tr>
        </table>
    </div>
    <center>
    	<input type="submit" id="submit" value="Submit">
    </center>
</form>
</div>

<div id="response"></div>

<script src="js/jquery.form.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>
<script src="js/contact.js"></script>
    
<?php echo file_get_contents("footer.html"); ?>