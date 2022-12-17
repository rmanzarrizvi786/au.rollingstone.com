<?php
global $wpdb;
$coupons = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mag_coupons ORDER BY expiry_date ASC" );
?>
<table class="widefat fixed" cellspacing="0">
  <thead>
    <tr>
      <th>Coupon Code</th>
      <th>Discount</th>
      <th>Type</th>
      <th>Expiry Date</th>
      <th>Status</th>
      <th>Created Date/Time</th>
      <th colspan="2"></th>
    </tr>
  </thead>

  <?php if ( count( $coupons ) > 0 ) : ?>
    <tbody id="the-list">
      <?php foreach ( $coupons as $coupon ): ?>
      <tr style="<?php echo isset( $_GET['id'] ) && $coupon->id == (int) $_GET['id'] ? 'background: lightyellow;' : ''; ?>">
        <td><?php echo $coupon->coupon_code; ?></td>
        <td><?php echo $coupon->discount; ?>%</td>
        <td><?php echo ucfirst( $coupon->type ); ?></td>
        <td><?php echo date('d M, Y', strtotime( $coupon->expiry_date ) ); ?></td>
        <td><?php echo $coupon->status; ?></td>
        <td><?php echo date('d M, Y h:ia', strtotime( $coupon->created_at ) ); ?></td>

        <td><a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=edit&id=' . $coupon->id ); ?>">Edit</a></td>
        <td><a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=delete&id=' . $coupon->id ); ?>" onclick="return confirm( 'Are you sure?' );">Delete</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  <?php endif; ?>
</table>
