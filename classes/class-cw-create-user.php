<?php
/**
 * CW_Create_User - Create a new user from form input.
 *
 * All input values MUST have been sanitized previously. Form render, validation & sanitization
 * is handled by separate classes, to separate the logic.
 *
 * This class is typically called by a facade type function. For Student Studio, the following classes are related:
 *
 * - CW_Reg_Validator, which validates & sanitizes input, /classes/class-cw-reg-validator.php
 * - CW_View_Form, which builds the form, /classes/class-cw-view-form.php
 *
 * The facade function used for supervisor & student registration is carawebs_userform_process_facade(),
 * which is in /lib/forms.php
 *
 * WordPress Version 4.1
 * @package CW_Create_User
 * @author David Egan <david@carawebs.com>
 *
 */

class CW_Create_User {

  public $firstname;
  public $lastname;
  public $email;
  public $user_role;
  public $coordinator_ID;
  public $username;
  public $success_message;

  function __construct ( $user_role, $coordinator_ID ) {

    $this->user_role  = $user_role;
    $this->coordinator_ID = $coordinator_ID;

  }

  public function set_values( $user_values ) {

    $this->firstname = $user_values['first_name'];
    $this->lastname = $user_values['last_name'];
    $this->email = $user_values['email'];
    $this->username = $user_values['email'];

  }

  public function register( $user_role ) {

    $password = wp_generate_password();
    $username = $this->username;

    $userdata = array(
      'user_login'      => $this->email,
      'user_pass'       => $password,
      'user_email'      => $this->email,
      'first_name'      => $this->firstname,
      'last_name'       => $this->lastname,
      'user_nicename'   => $this->firstname,
    );

    $user_id = wp_insert_user( $userdata ) ;

      if ( is_wp_error( $user_id ) ) {

        $insert_user_error = $user_id->get_error_message();

        $insert_user_error .= 'TEST';

        return $insert_user_error;

      } else {

        // Set the role
        $user = new WP_User( $user_id );
        $user->set_role( $this->user_role );
        // Set the Student's Coordinator
        add_user_meta( $user_id, 'coordinator_id', $this->coordinator_ID); // $coordinator_id comes from the originating page

        $coordinator_info = get_userdata( $this->coordinator_ID );
        $coordinator_name = $coordinator_info->user_firstname . ' ' . $coordinator_info->user_lastname;

        $new_user_info = array(

          'first_name' => $user->user_firstname,
          'last_name'  => $user->user_lastname

        );

        $this->success_message =
        '<p>You\'ve successfully registered a new ' . $user_role . '</p>' .
        'Name: ' . $user->user_firstname . ' ' . $user->user_lastname . '</br>' .
        'Email Address: ' . $user->user_email . '</br>' .
        'Display Name: ' . $user->user_nicename . '</br>' .
        'Username (for login purposes): ' . $user->user_login . '</br>' ;

        //return $new_user_info;
        //$this->success_message = $success_message;
        $this->email_new_user( $password );

      }

  }

  public function email_new_user( $password ){

    // Email the user
    // --------------

    // Allow html in email
    add_filter( 'wp_mail_content_type', function($content_type){

      return 'text/html';

    });

    /**
    * TODO allow Admin to select the attachment in back end of site
    */
    //$attachments = array( WP_CONTENT_DIR . '/uploads/2015/01/RDBMStoMongoDBMigration.pdf' );
    $headers = 'From: info@studentstudio.co.uk' . "\r\n";
    $welcome = 'Welcome, ' . $this->firstname . '!';

    /**
    * TODO set this up so that the right 'email' CPT is referenced.
    * It might be better to set up to reference by post_title, and instruct client to
    * give appropriate name to the relevant email CPT.
    */
    //$post_id = 62; // Refers to the Student Intro email custom post - rough & ready solution.
    //$post_object = get_post( $post_id );
    //$mailmessage = $post_object->post_content; // Build a mail message from the content of a post.
    $mailmessage = '<hr><p>Your password is: ' . $password . '</p>';

  wp_mail( $this->email, $welcome, $mailmessage, $headers/*, $attachments*/ );

  }



}
