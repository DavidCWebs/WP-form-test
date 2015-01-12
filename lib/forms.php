<?php
/**
*
*
*
*/
//function carawebs_userform_process_facade ( $coordinator_ID, $user_role = 'student' ) {
function carawebs_userform_process_facade () {

  //$coordinator_ID = '1';
  //$user_role = 'student';

  // Verify nonce
  //if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'cw_new_user' ) )
  //die( 'Sorry - there was a problem. Please try again later.' );

  if ( !empty($_POST) ) {

    if (isset ($_POST['cw_new_user_nonce'])) { $nonce = $_POST['cw_new_user_nonce']; }

    // check to see if the submitted nonce matches the generated nonce
    if ( ! wp_verify_nonce( $nonce, 'cw_new_user' ) )
        die ( 'Nonce does not verify!');


    // Define variables and set to empty values
    // -------------------------------------------------------------------------
    $firstname = $email = $lastname = $user_role = $coordinator_ID = "";

      if (isset ($_POST['cw_firstname'])) { $firstname = $_POST['cw_firstname']; }
      if (isset ($_POST['cw_lastname'])){ $lastname = $_POST['cw_lastname']; }
      if (isset($_POST['cw_email'])){ $email = $_POST['cw_email']; }
      if (isset($_POST['cw_coord_id'])){ $coordinator_ID = $_POST['cw_coord_id']; }
      if (isset($_POST['cw_user_role'])){ $user_role = $_POST['cw_user_role']; }
      $cw_ajax = isset($_POST['cw_ajax']) ? $_POST['cw_ajax'] : 'false'; // Allows a differential response for ajax submissions

        $form = new CW_Reg_Validator( $firstname, $lastname, $email );

        if ( 'true' == $form->is_valid() ) // returns 'true' if no errors, otherwise returns $errors array
        {
          $new_user = new CW_Create_User( $user_role, $coordinator_ID, $form->get_values() );
          $new_user->set_values( $form->get_values() ); // get values from $form object and put into $user->values()
          $new_user->register( $user_role ); // register a user, using values registered by set_values() - checked for errors & sanitised

          echo 'false' == $cw_ajax ? $new_user->success_message : '';

          //header('Location: ' . $_SERVER['HTTP_REFERER']);

          //exit;
        }
        else
        {
          $errors = array();
          $errors = $form->get_errors();
        }

      if ('true' == $cw_ajax ) {

        $response = array(); // this will be a JSON array

        $response['status']       = 'success';
        $response['message']      = $firstname . ' ' . $lastname . ' ' . $email;

        wp_send_json( $response ); // sends $response as a JSON object
      }

  }

    //$view = new CW_View_Form( $user_role, $coordinator_ID );

    //$registration_form = $view->render(); // This can now be echoed in the correct place in the template?

    //echo $registration_form;

    if (!empty($errors)) {

      echo 'Sorry - we can\'t process your form because:</br><ul>';

      foreach ( $errors as $error ) {

        echo '<li>' . $error . '</li>';

      }

      echo '</ul>';


    }

}

function carawebs_test_ajax_processor_new(){

  $response = array(); // this will be a JSON array

    $response['status']       = 'success';
    $response['message']      = 'It\'s connected.';

    wp_send_json( $response ); // sends $response as a JSON object

}

//add_action('wp_ajax_register_new_user', 'carawebs_test_ajax_processor_new');
add_action('wp_ajax_register_new_user', 'carawebs_userform_process_facade');
