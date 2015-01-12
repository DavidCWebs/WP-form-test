<?php
/**
*
*
*
*/

class CW_View_Form {

  public $proto_session_id;
  //$_SESSION['cw_session_id'] = $this->proto_session_id;

  function __construct ( $user_role, $coordinator_ID, $sessionnonce = '' ) {

    //$this->errors = $errors;
    $this->user_role      = $user_role;
    $this->coordinator_ID = $coordinator_ID;
    $this->sessionnonce = $sessionnonce;
    $this->proto_session_id = md5( 'whoohoo'. microtime() );

  }

  public function render() {

    $user_role = ucfirst($this->user_role);

    echo 'Output a form to register a ' . $user_role . ' user.</br>';
    $_SESSION['cw_session_id'] = $this->proto_session_id;
    echo '
      <div class="cw-student-registration-form">
        <form id="student-reg-form" class="registration-form" role="form" action="' . get_template_directory_uri() . '/' . $this->user_role . '-form.php" method="post">
          <input id="refcode" type="hidden" name="cw_coord_id" value="' . $this->coordinator_ID . '" />
          <input id="cw_user_role" type="hidden" name="cw_user_role" value="' . $this->user_role . '" />
          <input type="hidden" size="45" name="sessionnonce" value="' . $this->proto_session_id . '" />
          <div class="form-group">
            <label for="cw_firstname" class="sr-only">' . $user_role . '\'s First Name</label>
            <input autocomplete="off" type="text" name="cw_firstname" id="cw_firstname" value="" placeholder="' . $user_role . '\'s First Name" class="form-control" />
          </div>
          <div class="form-group">
            <label for="cw_lastname" class="sr-only">' . $user_role . '\'s Last Name</label>
            <input autocomplete="off" type="text" name="cw_lastname" id="cw_lastname" value="" placeholder="' . $user_role . '\'s Last Name" class="form-control" />
          </div>
          <div class="form-group">
            <label for="cw_email" class="sr-only">' . $user_role . '\'s Email</label>
            <input autocomplete="off" type="email" name="cw_email" id="cw_email" value="" placeholder="' . $user_role . '\'s Email" class="form-control" />
          </div>';
          wp_nonce_field('cw_new_user','cw_new_user_nonce', true, true );
          echo '
          <input type="submit" name="cw_register" class="btn btn-primary" id="btn-new-user" value="Register" />
        </form>
        <div class="indicator">Please wait...</div>
        <div class="alert result-message"></div>
      </div>
    ';

  }

}
