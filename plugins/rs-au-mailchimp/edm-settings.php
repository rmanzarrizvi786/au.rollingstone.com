<style>
    #campaign-posts { border-collapse: collapse; }
    #campaign-posts tr td { border-bottom: 3px solid #e5e5e5; margin: 0; padding: 5px; }
</style>
<?php
wp_enqueue_script( 'td-newsletter', plugin_dir_url( __FILE__ ) . '/js/edm.js', array( 'jquery' ), time(), true );

$tbm_promoter_articles = get_option( 'tbm_promoter_articles' ) ? json_decode( get_option( 'tbm_promoter_articles' ) ) : array();
?>
<form method="post" action="#" id="edm-settings" class="create-campaign">
  <h3>Promoter (Livenation) Articles <small>override</small></h3>
  <?php for( $i = 0; $i <= 2; $i++ ) : ?>
  <input type="text" name="tbm_promoter_articles[]" value="<?php echo isset( $tbm_promoter_articles[$i] ) ? $tbm_promoter_articles[$i] : ''; ?>" class="form-control my-2" placeholder="https://" style="width: 100%; margin: .25rem 0; padding: .25rem;">
  <?php endfor; ?>

  <div class="submit">
    <input type="button" name="submit" id="save-edm-settings" class="button button-primary" value="Save">
    <span id="td-mc-errors" class="hide error" style="color: #ff0000; display: block; padding: 10px 0;"></span>
    <span class="status"></span>
  </div>
</form>
