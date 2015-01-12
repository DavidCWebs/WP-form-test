<?php

class CW_Reg_Validator {

  public $form_errors = array();
  public $user_values = array();

  function __construct ( $firstname, $lastname, $email ) {

    $this->firstname  = $firstname;
    $this->lastname   = $lastname;
    $this->email      = $email;

  }

  public function is_valid() {

    $preg_str_check="#[^][(\\\\') A-Za-z-]#"; // allow letters (both cases), hyphen, space and '.

    if ( empty( $this->email ) ) {
      array_push( $this->form_errors, 'Please enter an email address.');
    }

    if ( empty( $this->firstname ) ) {
      array_push( $this->form_errors, 'Please enter a first name.');
    }

    if( preg_match( $preg_str_check, $this->firstname ) ) {
      array_push( $this->form_errors, 'The first name you entered doesn\'t look right - please try again with alphabet characters only.');
    }

    if ( empty( $this->lastname ) ) {
      array_push( $this->form_errors, 'Please enter a last name.');
    }

    if( preg_match( $preg_str_check, $this->lastname ) ) {
      array_push( $this->form_errors, 'The last name you entered doesn\'t look right - please try again with alphabet characters only.');
    }

    if ( ( $this->email) && !is_email( $this->email ) ) {
      array_push( $this->form_errors, 'The email address you submitted doesn\'t look right - please try again, with the format name@domain.com');
    }

    if ( email_exists( $this->email ) ) {
      array_push( $this->form_errors, 'Sorry - this email is already in use.');
    }

    if ( empty($this->form_errors)) { // There are no errors, so return the string 'true'

      return 'true';

    }

  }

  public function get_errors() {

    return $this->form_errors;

  }

  public function sanitise_inputs( $data ) {

    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;

  }

  public function get_values() {

    $this->firstname  = $this->sanitise_inputs($this->firstname);
    $this->lastname   = $this->sanitise_inputs($this->lastname);
    //$this->email      = sanitize_email($this->email);

    $this->user_values = array(
      'first_name'  => $this->firstname,
      'last_name'   => $this->lastname,
      'email'       => $this->email
    );

    return $this->user_values;

  }

}
