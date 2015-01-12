<?php
/**
* Create custom user roles.
*
*
* Custom roles for 'student', 'supervisor' & 'coordinator'.
* - 'student' can view Workbooks (a CPT), submit posts for review, can't publish
* - 'supervisor' can view the workbook & submissions of student's they've been associated with
* - 'coordinator' - the user who purchases workbooks and sets up students & supervisors
* Creation of the roles is hooked into 'after_switch_theme' - so roles are created on theme activation.
*
*/

function carawebs_custom_roles_on_theme_activation() {

  // Student Capabilities
  // TODO Check that student role can submit posts as draft. This may need to be tweaked??
  // -------------------------
  $author = get_role( 'author' );
  $student_caps = $author->capabilities; // start with author capabilities

  $student_caps['edit_student_submission'] = TRUE;
	$student_caps['read_student_submission'] = TRUE;
  $student_caps['delete_student_submission'] = TRUE;
	$student_caps['edit_others_student_submissions'] = TRUE;
	$student_caps['publish_student_submissions'] = TRUE;
	$student_caps['edit_student_submissions'] = TRUE;
	$student_caps['read_private_student_submissions'] = TRUE;
	$student_caps['delete_student_submissions'] = TRUE;
	$student_caps['delete_private_student_submissions'] = TRUE;
	$student_caps['delete_published_student_submissions'] = TRUE;
	$student_caps['delete_others_student_submissions'] = TRUE;
	$student_caps['edit_private_student_submissions'] = TRUE;


  // Supervisor Capabilities
  // -------------------------
  $supervisor_caps = $author->capabilities; // start with author capabilities
  $supervisor_caps['publish_posts'] = TRUE; // Student posts are saved as 'draft' - can be 'published' by Supervisors
  $supervisor_caps['edit_posts'] = TRUE;
  $supervisor_caps['delete_published_posts'] = FALSE;
  $supervisor_caps['edit_others_pending_posts'] = FALSE;

  // TODO
  // Give supervisors appropriate capabilities for 'student-submission' CPT defined in /lib/cpt.php
  // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  // Coordinator Capabilities
  // NOTE: we're not touching user management capability -
  // TODO MAKE SURE THIS ROLE CAN CREATE USERS PROGRAMMATICALLY
  // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  $coordinator_caps = $author->capabilities; // start with author capabilities
  $coordinator_caps['publish_posts'] = TRUE; // Student posts are saved as 'draft' - can be 'published' by Supervisors
  $coordinator_caps['edit_posts'] = TRUE;
  $coordinator_caps['delete_published_posts'] = FALSE;
  $coordinator_caps['edit_others_pending_posts'] = FALSE; // custom cap

  add_role( 'student', 'Student', $student_caps );
  add_role( 'supervisor', 'Supervisor', $supervisor_caps );
  add_role( 'coordinator', 'Coordinator', $coordinator_caps );

  // This is a bugfix - Custom user roles can't be given capabilities with regard to CPTs
  // https://core.trac.wordpress.org/ticket/16841
  // see https://www.stormconsultancy.co.uk/blog/development/bug-of-the-day/custom-roles-in-wordpress-not-appearing-in-author-lists/ for a description & hacky fix
  $student = get_role('student');
  $student->add_cap('level_1');
  $student->add_cap('upload_files'); // This is needed to allow media uploads on 'student-submission' CPTs

}

add_action('after_switch_theme', 'carawebs_custom_roles_on_theme_activation');

/**
* Return user role.
*
* This is important in templates, because content is displayed differentially
* for students, supervisors & coordinators. We could get this using the $current_user
* WordPress global - not sure which method is better.
*
* @param string $user_ID
*
* @return string $user_role Current user's role.
*/

function carawebs_get_user_role( $user_ID ) {

  //$current_user is a WordPress global
  //global $current_user;

  $user_object = get_user_by( 'id', $user_ID );
	//$user_roles  = $current_user->roles;
  $user_roles = $user_object->roles;

  $user_role = $user_roles[0];

  return $user_role;

}

/**
* Pass variables through URL.
*
* Allows student user_id to be added to a link. This is used to allow the supervisor
* to view content relating to a particular student, when following a properly constructed
* link from the supervisor's page. Once set up, a link can be constructed in this way:
*
*  $link = '<a href="' . get_bloginfo('url') .
*  '/workbook/budding-brunels/?studentID=' . $studentID .
*  '" title="Go to ' . $student_firstname . '\'s Workbook">' . $student_firstname .
*  '\'s Workbook</a></p>';
*
* This allows the $studentID varaible to be available in the destination URL.
*
* @return array $vars Current user's role.
*/

function carawebs_add_query_vars_filter( $vars ){

    $vars[] = "studentID";
    return $vars;
  }

add_filter( 'query_vars', 'carawebs_add_query_vars_filter' );

/**
* Create new student user.
*
* Create a new user with custom role 'student' based on form input data.
*
* @param int $coordinator_ID
* @param int $firstname
* @param int $lastname
* @param int $email
* @param int $supervisor_ID Required if 'student' == $user_role
*
*/

/*
function carawebs_create_user( $coordinator_ID, $firstname, $lastname, $email, $user_role, $supervisor_ID ) {

  // Create the new user
  // ---------------------------------
  $password = wp_generate_password();

  $userdata = array(
    'user_login' => $email,
    'user_pass'  => $password,
    'user_email' => $email,
    'first_name' => $firstname,
    'last_name'  => $lastname,
  );

  $user_id = wp_insert_user( $userdata ) ;

  // Set the Student's Coordinator
  // -----------------------------
  add_user_meta( $user_id, 'coordinator_id', $coordinator_ID); // $coordinator_id comes from the originating page

  // Set the Student's Supervisor (workbook specific)
  // ------------------------------------------------
  carawebs_setup_supervisor ($user_id, $supervisor_ID, $workbook_ID ); //

  if ( is_wp_error($user_id) ) { // in case of error during student creation

    $error_message = $user_id->get_error_message();

    return $error_message;


  } else {

    // Set the role
    $user = new WP_User( $user_id );
    $user->set_role( 'student' );

    // Assign Workbook & Keep count
    // -----------------------------
    carawebs_assign_workbook_to_student_complete( $coordinator_ID, $supervisor_ID, $user_id, $workbook_ID ); // lib/user-setup-helpers.php

    carawebs_decrement_workbook_count ( $workbook_ID, $coordinator_ID );

    // Send email to student with login details
    // ------------------------------------------

    // Allow html in email
    add_filter( 'wp_mail_content_type', function($content_type){

      return 'text/html';

    });

    // TODO allow Admin to select the attachment in back end of site
    $attachments = array( WP_CONTENT_DIR . '/uploads/2014/11/Student-Studio-Milestones.pdf' );
    $headers = 'From: info@studentstudio.co.uk' . "\r\n";
    $welcome = 'Welcome, ' . $firstname . '!';
    $post_id = 62; // Refers to the Student Intro email custom post
    $post_object = get_post( $post_id );
    $mailmessage = $post_object->post_content;
    $mailmessage .= '<hr><p>Your password is: ' . $password . '</p>';

    wp_mail( $email, $welcome, $mailmessage, $headers, $attachments );

    // Success: return a user object to the originating function.
    // ----------------------------------------------------------
    return $user;

  }

}
*/
