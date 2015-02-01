<article <?php post_class(); ?>>
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
    <?php
    $associated_workbook = get_field('associated_workbook');

    if ( $associated_workbook ){

      //echo '<pre>';
      //var_dump( $associated_workbook );
      //echo '</pre>';

      $workbook_title = $associated_workbook->post_title;
      $workbook_id = $associated_workbook->ID;

      echo "The associated workbook is: $workbook_title, ID: $workbook_id";
    }

    ?>


  </div>
</article>
