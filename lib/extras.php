<?php
//use Respect\Validation\Validator;
//use Respect\Validation\Validator as v;
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

function carawebs_validate() {

/*

  include_once get_template_directory() . '/vendor/autoload.php';
  //use Respect\Validation\Validator as v;

  //use Respect\Validation\Validator as v;
  if (class_exists('Respect\Validation\Validator')) {
    echo 'CLASS!';
  }

  //use Respect\Validation\Validator as v;

  $number = 2666777;
  //$validnumber = Respect\Validation\Validator::numeric()->validate($number);
  $validnumber = v::numeric()->validate($number);

  echo 'The number is ' . $number . '</br>';
  $validnumber = 1 == $validnumber ? 'Yes!' : 'Nope.';
  echo 'Is it a valid number? ' . $validnumber;
  */

}

function carawebs_register_user_scripts() {

  // New Ajax methods for registration forms
  // ---------------------------------------------------------------------------
  if (is_page('setup-student') || is_page( 'setup-supervisor' )) {

    wp_register_script('carawebs_user_reg_script', get_template_directory_uri() . '/assets/js/ajax/ajax-reg.js', array('jquery'), null, false);

    wp_enqueue_script('carawebs_user_reg_script');

    wp_localize_script( 'carawebs_user_reg_script', 'carawebs_reg_vars', array(
      'carawebs_ajax_url' => admin_url( 'admin-ajax.php' ),
      )
    );

  }

}

//add_action('wp_enqueue_scripts', 'carawebs_register_user_scripts', 100);
