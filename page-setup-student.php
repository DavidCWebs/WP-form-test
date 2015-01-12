<?php while (have_posts()) : the_post(); ?>
<?php get_template_part('templates/page', 'header'); ?>
<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Logout">Logout</a>
<div class="row">
<div class="col-md-8">
  FORMS BRANCH
<?php
  if ( is_user_logged_in() ){

    // Get data relating to the current user
    // -------------------------------------
    $current_user = wp_get_current_user();            // gets the current user object
    $user_id = $current_user->ID;                     // current user ID
    $user_firstname = $current_user->user_firstname;  // current user first name
    $user_role = $current_user->roles[0];             // current user role
    $all_user_meta = get_user_meta($user_id);         // all usermeta data for current user

/*==============================================================================
  Restrict Page by role: Only coordinators and Administrators allowed
  ============================================================================*/
    if ('coordinator' == $user_role || 'administrator' == $user_role) {

      echo '<h3 class=' . $user_id . '>Welcome, ' . ucfirst($user_role) . ' ' . $user_firstname . '</h3>
      <h4>Setup New Students on this Page</h4>';

      //carawebs_reg_student_basic_form( $user_id );

      // Student Registration Form
      // -----------------------------------------------------------------------
      $view = new CW_View_Form( 'student', $user_id );

      $registration_form = $view->render();

      carawebs_userform_process_facade ( $user_id, 'student' );

      // Check for 'workbooks' in usermeta
      // ---------------------------------
      $workbooks_user_meta = get_user_meta($user_id, 'workbooks');


      if ( $workbooks_user_meta ){

        // Output available workbooks as ul
        // ---------------------------------
        echo'<hr><h3>Available Workbooks</h3>
        <p>You have purchased the following Workbooks:</p>
        <ul>';

        foreach($workbooks_user_meta[0] as $key => $value) { // [0] is required to deregister - TODO double check this, can/should we return a string?

          echo '<li>Workbook ID: ' . $key . ', Title: ' . get_the_title($key) .
          ', Workbook Quantity: <span id="workbook-' . $key . '">' . $value . '</span></li>';

        }
        echo '</ul><hr>';
      }
      ?>

      <?php get_template_part('templates/content', 'page'); ?>
      <?php

    } else {

      echo "<p>Sorry - you don't have permission to view this content.</p>";
      echo "<p>This page is for Student Studio Coordinators only.</p>";
      echo '<p><a href="' . wp_login_url() . '" title="Login">Login to the Site</a></p>';

    }




  } else {

    echo "<p>Sorry - you don't have permission to view this content.</p>";
    echo "<p>This page is for Student Studio Coordinators only.</p>";
    echo '<p><a href="' . wp_login_url() . '" title="Login">Login to the Site</a></p>';

  }
?>
</div>
</div>
<?php endwhile;?>
