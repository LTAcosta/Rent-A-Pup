$(function() {
	// Add custom time validation
	jQuery.validator.addMethod("blackouts", function(value, element) {
		return this.optional(element) || validateTime();
	}, "");
	
  // Validate the contact form
  $('#contactForm').validate({
    // Specify what the errors should look like
    // when they are dynamically added to the form
    errorElement: "label",
    wrapper: "td",
    errorPlacement: function(error, element) {
      error.insertBefore( element.parent().parent() );
      error.wrap("<tr class='error'></tr>");
      $("</td><td>").insertBefore(error);
    },
 
    // Add requirements to each of the fields
    rules: {
      name: {
        required: true,
        minlength: 2,
		blackouts: true
      },
      email: {
        required: true,
        email: true
      },
      phone: {
        required: true,
        phoneUS: true
      },
	  datepicker: {
		  required: true,
		  date: true
	  }
    },
 
    // Specify what error messages to display
    // when the user does something horrid
    messages: {
      name: {
        required: "Please enter your name.",
        minlength: jQuery.format("At least {0} characters required.")
      },
      email: {
        required: "Please enter your email.",
        email: "Please enter a valid email."
      },
      phone: {
        required: "Please enter your phone number.",
		phoneUS: "Please enter a valid phone number."
      },
	  datepicker: {
		  required: "Please enter your reservation date.",
		  date: "Please enter a valid date."
	  }
    },
 
    // Use Ajax to send everything to processForm.php
    submitHandler: function(form) {
      $("#submit").attr("value", "Sending...");
      $(form).ajaxSubmit({
        target: "#response",
        success: function(responseText, statusText, xhr, $form) {
          $("#rentalContent").slideUp("fast");
          $("#response").html(responseText).hide().slideDown("fast");
        }
      });
      return false;
    }
  });
});