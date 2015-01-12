<?php use Respect\Validation\Validator as v;

while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php


  //carawebs_validate();

  include_once get_template_directory() . '/vendor/autoload.php';

  /*if (class_exists('Respect\Validation\Validator')) {
    echo 'CLASS!';
  }*/

  $number = 2666777;
  //$validnumber = Respect\Validation\Validator::numeric()->validate($number);
  $validnumber = v::numeric()->validate($number);

  echo 'The number is ' . $number . '</br>';
  $validnumber = 1 == $validnumber ? 'Yes!' : 'Nope.';
  echo 'Is it a valid number? ' . $validnumber;

  ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>
