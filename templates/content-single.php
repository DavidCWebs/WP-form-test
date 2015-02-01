<?php while (have_posts()) : the_post();
//$form = new Function_Form();

//$form->init();

?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <form class="form-horizontal" name="my_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
      <fieldset>

      <!-- Form Name -->
      <legend>PDF Test</legend>

      <!-- Button -->
      <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton">Single Button</label>
        <div class="col-md-4">
          <button id="singlebutton" name="pdf-button" value="create-pdf" class="btn btn-primary">Button</button>
        </div>
      </div>

      <div class="well">
      <?php
      if (isset($_POST['pdf-button'])) {

        echo 'pressed';
      }

      ?>


      </div>

      </fieldset>
      </form>




      <?php the_content(); ?>
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

      <?php

      $associated_product = get_field('associated_product');

      if ( $associated_product ){

        echo '<pre>';
        var_dump( $associated_product );
        echo '</pre>';

        $product_title = $associated_product->post_title;
        $product_id = $associated_product->ID;

        echo "The associated product is: $product_title, ID: $product_id";
      }

      ?>



    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
