jQuery(document).ready(function($) {

  $('#btn-new-user').click( function(event) {

    // Prevent default action
    // -----------------------
    if (event.preventDefault) {
        event.preventDefault();
    } else {
        event.returnValue = false;
    }

    // Show 'Please wait' loader to user
    // ---------------------------------
    $('.indicator').fadeIn(400);

    $('.result-message').hide();

    // Collect data from inputs, and slot into variables
    // -------------------------------------------------
    var reg_nonce = $('#cw_new_user_nonce').val();
    var reg_coord_id = $('#refcode').val();
    var reg_user_role = $('#cw_user_role').val();
    var reg_email  = $('#cw_email').val();
    var reg_firstname  = $('#cw_firstname').val();
    var reg_lastname  = $('#cw_lastname').val();
    var reg_cw_ajax = 'true';

    // AJAX URL: where to send data (set in the localize_script function)
    // --------------------------------------------------
    var ajax_url = carawebs_reg_vars.carawebs_ajax_url;

    // Data to send
    // -----------------
    data = {
      action: 'register_new_user',
      cw_coord_id: reg_coord_id,
      cw_user_role: reg_user_role,
      cw_new_user_nonce: reg_nonce,
      cw_email: reg_email,
      cw_firstname: reg_firstname,
      cw_lastname: reg_lastname,
      cw_ajax: reg_cw_ajax
    };

    // Do AJAX request
    $.post( ajax_url, data, function(response) {

      // If we have response
      if( response ) {

        alert('Response from server is: ' + response.status);

        // Hide 'Please wait' indicator
        $('.indicator').hide();

          var status = response.status;
          //alert(status);

            if( response.status === 'error' ) {

                $('.result-message').html(response.message);
                $('.result-message').show(); // Show results div


            } else {

                // Build a success message
                var studentReturn =
                  "<p>" + response.message + "</p>";/* +
                  "<h3>Student Details</h3>" +
                    "<ul>" +
                      "<li>Name: " + response.firstname + " " + response.lastname + "</li>" +
                      "<li>Username: " + response.username + "</li>" +
                      "<li>Email: " + response.email + "</li>" +
                      "<li>------------------------------</li>" +
                  "<p>The Student's login details (including an auto-generated password) will be emailed to them.</p>";*/

                $('.result-message').html(studentReturn);
                //liChange = $('#workbook-' + response.workbookID);
                //$(liChange).text(response.quantity);
                $('.result-message').show(); // Show results div
            }

      }

    });

  });

  // Handle another form on the same page

  $('#btn-different-id').click( function(event) {

    // Process the form data

  });


});
