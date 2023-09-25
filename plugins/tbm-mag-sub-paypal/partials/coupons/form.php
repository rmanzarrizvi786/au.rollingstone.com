discount<h2>Generate one</h2>
<div style="color: red; font-weight: bold;">
  <?php echo isset ( $_SESSION['tbm']['coupons']['errors'] ) ? implode( '<br>', $_SESSION['tbm']['coupons']['errors'] ) : ''; ?>
</div>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
  <?php if ( isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) : ?>
  <input type="hidden" name="action" value="tbm_update_coupon">
  <input type="hidden" name="id" value="<?php echo $form_coupon['id']; ?>">
  <?php else : ?>
  <input type="hidden" name="action" value="tbm_create_coupon">
  <?php endif; ?>
  <input type="hidden" name="tbm_coupon_nonce" value="<?php echo wp_create_nonce( 'tbm_coupon_form_nonce' ); ?>" />
  <table class="form-table">
    <tr>
      <th><label for="coupon_code">Code</label></th>
      <td><input type="text" name="coupon_code" id="coupon_code" value="<?php echo isset( $form_coupon['coupon_code'] ) ? $form_coupon['coupon_code'] : wp_generate_password( 8, false, false ); ?>"></td>
    </tr>
    <tr>
      <th><label for="discount">Discount</label></th>
      <td>
        <select name="discount" id="discount">
        <?php foreach( range( 10, 100, 10 ) as $d ) : ?>
          <option value="<?php echo $d; ?>"<?php echo isset( $form_coupon['discount'] ) && $d == $form_coupon['discount'] ? ' selected' : ''; ?>><?php echo $d; ?>%</option>
        <?php endforeach; ?>
        </select>
      </td>
    </tr>
    <tr>
      <th><label for="expiry_date">Expiry Date<br><small>Format: YYYY-MM-DD<br>e.g. 2019-12-31</small></label></th>
      <td><input type="text" name="expiry_date" id="expiry_date" value="<?php echo isset( $form_coupon['expiry_date'] ) ? $form_coupon['expiry_date'] : date( 'Y-m-d', strtotime( '+90 days' ) ); ?>"></td>
    </tr>
    <tr>
      <th>Type
        <small>
          <ul>
            <li>Limited one can be used once only before expiry date</li>
            <li>UnLimited one can be used multiple times before expiry date</li>
          </ul>
        </small>
      </th>
      <td>
        <label><input type="radio" name="type" id="type_limited" value="limited" <?php echo isset( $form_coupon['type'] ) && 'limited' == $form_coupon['type'] ? 'checked="checked"' : ''; ?>> Limited</label>
        <label><input type="radio" name="type" id="type_unlimited" value="unlimited" <?php echo isset( $form_coupon['type'] ) && 'unlimited' == $form_coupon['type'] ? 'checked="checked"' : ''; ?>> UnLimited</label>
      </td>
    </tr>
    <tr>
      <th>Status</th>
      <td>
        <label><input type="radio" name="status" id="status_active" value="active" <?php echo isset( $form_coupon['status'] ) && 'active' == $form_coupon['status'] ? 'checked="checked"' : ''; ?>> Active</label>
        <label><input type="radio" name="status" id="status_inactive" value="inactive" <?php echo isset( $form_coupon['status'] ) && 'inactive' == $form_coupon['status'] ? 'checked="checked"' : ''; ?>> InActive</label>
      </td>
    </tr>
  </table>
  <?php if ( 0 && isset( $id ) && isset( $form_coupon ) ) : ?>
  <table class="form-table">
    <tr>
      <th><label for="remaining_use">Remaining times it can be used for</label></th>
      <td><input type="text" name="remaining_use" id="max_use" value="<?php echo isset( $form_coupon['remaining_use'] ) ? $form_coupon['remaining_use'] : 1; ?>"></td>
    </tr>
  </table>
  <?php endif; ?>
  <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p>
</form>
