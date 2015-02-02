<?php
//use Respect\Validation\Validator;
//use Respect\Validation\Validator as v;
//
//
//


function carawebs_create_PDF( $current_username, $post_ID, $student_ID, $submission_html ){

  // Include the main TCPDF library (search for installation path).
  require_once(get_template_directory() . '/tcpdf/tcpdf.php');

  // create new PDF document
  $pdf = new CW_PDF();

  // set document information
  $pdf->SetCreator('Student Studio');
  $pdf->SetAuthor('David Egan');
  $pdf->SetTitle('Student Studio Workbook Report');
  $pdf->SetSubject('Student Studio');
  $pdf->SetKeywords('Work Experience, Digitally Enhanced Learning');

  $pdf->setPrintHeader(true);

  // set default header data
  //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
  $pdf->setFooterData(array(0,64,0), array(0,64,128));

  // set header and footer fonts
  //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  // set default monospaced font
  //$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

  // set margins
  //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetMargins(10, 40, 10, true);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetFooterMargin($fm = 50);

  // set auto page breaks
  //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetAutoPageBreak(TRUE, 40);

  // set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

  // set default font subsetting mode
  $pdf->setFontSubsetting(true);

  // Set font
  // dejavusans is a UTF-8 Unicode font, if you only need to
  // print standard ASCII chars, you can use core fonts like
  // helvetica or times to reduce file size.
  $pdf->SetFont('helvetica', '', 14, '', true);

  // Add a page
  // This method has several options, check the source code documentation for more information.
  $pdf->AddPage();

  // Set some content to print
  $html = "<h1>$current_username - You've Created a Mother-Fucking PDF!</h1>";
  $html .= "<p>The current post ID is $post_ID, so we can get the workbook info.</p>
  <p>Look, I'm just not ready to ask Lorraine out to the dance, and not you, nor anybody else on this planet is gonna make me change my mind.</p>
  <p>C'mon, he's not that bad. At least he's letting you borrow the car tomorrow night.</p>
  <p>Hey Biff, check out this guy's life preserver, dork thinks he's gonna drown. Shit.</p>";

  //$html .= the_content(1);
  $post_id = 1;
  $post_object = get_post( $post_id );
  $html .= $post_object->post_content;

  $html .= $submission_html;


  // Print text using writeHTMLCell()
  $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

  // ---------------------------------------------------------

  // Close and output PDF document
  // This method has several options, check the source code documentation for more information.
  // http://www.tcpdf.org/doc/code/classTCPDF.html#a3d6dcb62298ec9d42e9125ee2f5b23a1
  $pdf->Output('my-student-studio-workbook.pdf', 'I');

}

function carawebs_process_pdf() {

	if ( isset( $_POST['pdf-button'] ) && 'create-pdf' == $_POST['pdf-button'] ) {

    $current_user           = wp_get_current_user();  // Current user object
    // if we want to get student user from a supervisor view, use $_GET studentID variable in the URL????
    $current_user_id        = $current_user->ID;      // Current user ID
    $current_username       = $current_user->user_firstname . ' ' . $current_user->user_lastname;

    // get_the_ID() isn't yet available, so use url_to_postid();
    $post_ID = url_to_postid( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ) ;

		//carawebs_test_head_function();
		$html = carawebs_return_html_from_posts( $current_user_id );
		carawebs_create_PDF( $current_username, $post_ID, $current_user_id, $html );

	} // end if

} // end my_theme_send_email
add_action( 'init', 'carawebs_process_pdf' );


function carawebs_return_html_from_posts( $student_ID, $workbook_ID = '' ){

  $html = '';


    // Gather 'student-submission' for this author - Loop through to build a dropdown for each stage
    // -------------------------------------------------------------------------
    $args = array (
    	'post_type'  => 'post',
    	'author'     => $student_ID,
      'posts_per_page'         => '-1',
      /*'tax_query' => array(
    		array(
    			'taxonomy' => 'stage',
    			'field'    => 'slug',
    			'terms'    => $c,
    		),
    	),
      'meta_query'             => array(
    		array(
    			'key'       => 'workbook',
    			'value'     => $current_workbook_id,
    			'compare'   => '=',
    			'type'      => 'NUMERIC',
    		),
    	),*/
    );

    // The Query
    $student_submission = new WP_Query( $args );

    // The Loop
    if ( $student_submission->have_posts() ) {

      while ( $student_submission->have_posts() ) {
        $student_submission->the_post();

        $title = get_the_title();
        $content = get_the_content();

        //$student_submission_ID = get_the_id();
        $html .= '<br/><hr><br/>';
        $html .= "<h1>$title</h1>";
        $html .= wpautop($content);
        $html .= '<br/><hr><br/>';


      }

    } else {

      // No submission for this stage yet - do nothing

    }

    // Restore original Post Data
    wp_reset_postdata();


    return $html;

}

/*function cw_init_test(){

  echo '<h1>init test</h1>';

}

//add_action( 'init', 'cw_init_test', 0 );



class Function_Form {

  public $validate;
  public $input;

  function __construct() {

    //add_action( 'init', array($this, 'form_processor'), 0 );
    //add_action( 'init', $this->form_processor()) ;
    //add_action('init', array(&$this, 'init'),1);

    $this->init();

    //echo $this->render_form();

  }

  public function init(){

    echo '<h1>$this->init</h1>';

    add_action('init', array($this, 'form_processor'));

  }

  function render_form() {

    //
     // If the submission was successful, the page will have reloaded and $_SESSION
     // will have been set.

    if ( isset ($_SESSION['validate']) ) {

      $success_message = $_SESSION['validate'];

    }

    $output = '';

    if( $this->validate['success'] == 'error' ){

      $output .= '<div class="error">'.$this->validate['message'].'</div>';

    } elseif( ( isset ($_SESSION['validate']) ) && $success_message['success'] == 'success' ){

      $output .= '<div class="success">'.$success_message['message'].'</div>';

      unset($_SESSION['validate']); // Clear the session, ready for the next time.

    }

    // Put this into a partial
    $output .= '<form class="registration-form" role="form" name="my_form" method="post">';
    $output .= '<div class="form-group">
                  <input autocomplete="off" type="text" name="form_data" id="cw_lastname" placeholder="Data" class="form-control" />
                </div>';
    $output .= '<button type="submit">'.__('Submit').'</button>';
    $output .= '</form>';

    return $output;

    echo '<pre>';
    var_dump($success_message);
    echo '</pre>';

    $this->input = $_POST['my_form'];

  }

  public function form_processor() {

  if( isset($_POST['form_data']) ) {

    // This won't validate - doesn't know $this!
    $this->validate_form( $_POST['form_data'] );

      if ( $this->validate['success'] == 'success' ) {

        $success = $this->save_form_data( $_POST['form_data'] );

        if ( $success ) {

          if( !session_id() ){

            session_start();

          }

          $_SESSION['validate'] = $this->validate;

          // Redirect to same page. No headers sent yet, so we're OK!
          header('Location: ' . $_SERVER['HTTP_REFERER']);

          exit();

        }

      }

    }

  }

  function save_form_data( $form_data ) {

      // Run save process here, return true if successful
      return true;

  }

  function validate_form( $data ) {

    $validation = array();

    if( $data == 'validate_me' ) {

        $validation['success'] = "success";
        $validation['message'] = "You entered" . $_POST['form_data'];

    } else {

        $validation['success'] = "error";
        $validation['message'] = "Nope - the error message";

    }

    $this->validate = $validation;

  }

}*/

//new Function_Form();


class MyForm {

    private $validate;

    function __construct() {
         add_shortcode( 'myform', array($this, 'my_form') ); // add the shortcode from a new object from this class
         add_action( 'init', array($this, 'wpse_process_my_form') );
    }

    //function my_form( $atts ) {
    function my_form() {
        // Somehow get the errors ($validate) generated in the function attached to init
        //
         if ( isset ($_SESSION['validate']) ) { $success_message = $_SESSION['validate'];

         echo '<pre>';
         var_dump ($_SESSION['validate']);
         echo '</pre>';
         };

        $output = '';

        // output form (with errors if found)
        if( $this->validate['success'] == 'error' ){

             $output .= '<div class="error">'.$this->validate['message'].'</div>';

        } elseif( ( isset ($_SESSION['validate']) ) && $success_message['success'] == 'success' ){

          $output .= '<div class="success">'.$success_message['message'].'</div>';

          unset($_SESSION['validate']);


        }
        $output .= '<form class="registration-form" role="form" name="my_form" method="post">';
        $output .= '<div class="form-group">
                    <input autocomplete="off" type="text" name="form_data" id="cw_lastname" placeholder="Data" class="form-control" />
                  </div>';
        //$output .= '<input type="text" name="form_data">';
        $output .= '<button type="submit">'.__('Submit').'</button>';
        $output .= '</form>';

        return $output;

    }

    function wpse_process_my_form() {
        if( isset($_POST['form_data']) ) {

            $this->wpse_validate_my_form( $_POST['form_data'] );

            if ( $this->validate['success'] == 'success' ) {

                $success = $this->wpse_save_my_form( $_POST['form_data'] );

                if ( $success ) {

                  if( !session_id() ){

                    session_start();

                  }

                  $_SESSION['validate'] = $this->validate;

                  header('Location: ' . $_SERVER['HTTP_REFERER']);

                  //wp_redirect( '/success', 302 );
                    exit();

                }
            }
        }
    }

    function wpse_save_my_form( $form_data ) {
        // Run save process here, return true if successful
        return true;
    }

    function wpse_validate_my_form( $data ) {

        $validation = array();

        if( $data == 'validate_me' ) {
            $validation['success'] = "success";
            $validation['message'] = "the success message";
        } else {
            $validation['success'] = "error";
            $validation['message'] = "the error message";
        }

        $this->validate = $validation;

    }

}

new MyForm();



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
//
//

define('EDD_SLUG', 'products');
function set_download_labels($labels) {
	$labels = array(
		'name' => _x('Products', 'post type general name', 'your-domain'),
		'singular_name' => _x('Product', 'post type singular name', 'your-domain'),
		'add_new' => __('Add New', 'your-domain'),
		'add_new_item' => __('Add New Product', 'your-domain'),
		'edit_item' => __('Edit Product', 'your-domain'),
		'new_item' => __('New Product', 'your-domain'),
		'all_items' => __('All Products', 'your-domain'),
		'view_item' => __('View Product', 'your-domain'),
		'search_items' => __('Search Products', 'your-domain'),
		'not_found' =>  __('No Products found', 'your-domain'),
		'not_found_in_trash' => __('No Products found in Trash', 'your-domain'),
		'parent_item_colon' => '',
		'menu_name' => __('Products', 'your-domain')
	);
	return $labels;
}
add_filter('edd_download_labels', 'set_download_labels');

function pw_remove_edd_metaboxes() {
	remove_meta_box('edd_file_download_log', 'download', 'normal');
}
//add_action('add_meta_boxes', 'pw_remove_edd_metaboxes', 999);
//
function pw_remove_product_options() {
	remove_action('edd_meta_box_fields', 'edd_render_files_field', 20);
	remove_action('edd_meta_box_fields', 'edd_render_purchase_text_field', 30);
	remove_action('edd_meta_box_fields', 'edd_render_link_styles', 40);
	remove_action('edd_meta_box_fields', 'edd_render_button_color', 50);
	remove_action('edd_meta_box_fields', 'edd_render_disable_button',60);
	remove_action('edd_meta_box_fields', 'edd_render_meta_notes',70);
}
add_action('admin_init', 'pw_remove_product_options');

add_filter('wp_nav_menu_items','add_search_box', 10, 2);

function add_search_box( $items, $args ) {

  if (!is_admin() && $args->theme_location == 'primary_navigation') {

        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();

        $items .= '<li class="search">Search ' . $searchform . '</li>';
      }

    return $items;
}






// remove the CSS for EDD's custom menu icon
remove_action( 'admin_head', 'edd_admin_downloads_icon' );

// filter EDD's post type args to use an icon from dashicons (http://melchoyce.github.io/dashicons/)
function sumobi_edd_modify_menu_icon( $download_args ) {
	$download_args['menu_icon'] = 'dashicons-cart';
	return $download_args;
}
add_filter( 'edd_download_post_type_args', 'sumobi_edd_modify_menu_icon' );
