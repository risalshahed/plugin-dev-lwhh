
<!-- eikhane amra 1ta parameter PASS krte hook er pashapashi, tai THEME er "functions.php" file a ei ACTION ADD krar smy amra 1ta parameter PASS krte parbo -->
<?php do_action('astra_category_page', single_cat_title('', false)); ?>

<!-- data retrieve krte chaile true, just pass krte chaile false -->

<?php get_header() ?>


    <!-- s-content
    ================================================== -->
  <section class="s-content">

    <div class="row narrow">
      <div class="col-full s-content__header" data-aos="fade-up">
          <?php echo apply_filters("astra_text","hello","wonderful", "world"); ?>
          
          <?php do_action("astra_before_category_title"); ?>

          <h1>
            <?php single_cat_title(); ?>
          </h1>

          <?php do_action("astra_after_category_title"); ?>


          <?php do_action("astra_before_category_description"); ?>

          <p class="lead">
            <?php echo category_description(); ?>
          </p>

          <?php do_action("astra_after_category_description"); ?>

