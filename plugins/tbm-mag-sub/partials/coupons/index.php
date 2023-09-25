<?php
$action = isset( $_GET['action'] ) ? $_GET['action'] : NULL;
?>
<div class="wrap">
  <h1 class="wp-heading-inline">Coupons</h1>

  <?php if ( ! in_array( $action, array( 'create', 'edit' ) ) ) : ?>
    <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=create' ); ?>" class="page-title-action">Create Coupon</a>
  <?php endif; ?>

  <?php
  global $wpdb;
  switch ( $action ) :
    case 'create' :
      $form_coupon = isset( $_SESSION['tbm']['coupons']['post_vars'] ) ? $_SESSION['tbm']['coupons']['post_vars'] : array();
      include 'form.php';
      break;
    case 'edit' :
      if ( isset( $_GET['id'] ) ) :
        $id = (int) $_GET['id'];
        $form_coupon = isset( $_SESSION['tbm']['coupons']['post_vars'] ) ? $_SESSION['tbm']['coupons']['post_vars'] : $wpdb->get_row( $wpdb->prepare(
          "SELECT * FROM {$wpdb->prefix}mag_coupons WHERE id = %d ",
          $id
        ), ARRAY_A );
      endif;
      include 'form.php';
      break;
    case 'delete' :
      if ( isset( $_GET['id'] ) ) :
        $id = (int) $_GET['id'];
        $wpdb->delete( $wpdb->prefix . 'mag_coupons',
        array ( 'id' => $id ),
        array ( '%d' )
      );
    endif;
    include 'list.php';
    break;
  default :
    include 'list.php';
    break;
  endswitch;
?>
</div>

<?php
if ( isset ( $_SESSION['tbm']['coupons'] ) ) :
  unset( $_SESSION['tbm']['coupons'] );
endif;
