<?php
class Coupon
{

  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->table = $wpdb->prefix . 'mag_coupons';
  }

  public function get($id)
  {
    global $wpdb;
    $coupon = $wpdb->get_row("SELECT * FROM {$this->table} WHERE id = '" . $id . "' LIMIT 1");
    if ($coupon) {
      foreach ($coupon as $key => $value) {
        $this->$key = $value;
      }
      return $this;
    }
  }

  public function update($updates = [])
  {
    if ($updates) {
      global $wpdb;
      $wpdb->update(
        $this->table,
        $updates,
        array(
          'id' => $this->id
        )
      );
      return $this;
    }
  }

  public function validateCoupon($coupon_code = null, $email = null)
  {

    if (is_null($coupon_code) || '' == trim($coupon_code)) :
      return;
    endif; // If $coupon_code is NULL

    global $wpdb;

    $query = "SELECT *
      FROM {$this->table}
      WHERE 1 = 1
        AND coupon_code = '{$coupon_code}'
        AND status = 'active'

      LIMIT 1";
    // AND expiry_date <= '" . date('Y-m-d') . "'
    $coupon = $wpdb->get_row($query);

    if (!$coupon) :
      return [
        'error' => 'Looks like that coupon code is invalid. If you are sure you are entering it correctly, send us an email at subscribe@thebrag.media to sort out the issue.'
      ];
    endif; // If $coupon is not found

    if ('limited_emails' == $coupon->type) {

      if (is_null($email) || '' == trim($email)) :
        return [
          'error' => 'Looks like that coupon code is invalid. Please check your email address or if you are sure you are entering it correctly, send us an email at subscribe@thebrag.media to sort out the issue.'
        ];
      endif; // If $email is NULL

      $query = "SELECT *
      FROM {$wpdb->prefix}mag_coupon_emails
      WHERE coupon_id = '{$coupon->id}'
        AND email = '{$email}'

      LIMIT 1";
      $coupon_email = $wpdb->get_row($query);
      if (!$coupon_email) :
        return [
          'error' => 'Looks like that coupon code is invalid. Please check your email address or if you are sure you are entering it correctly, send us an email at subscribe@thebrag.media to sort out the issue.'
        ];
      endif; // If $coupon is not found
    }

    $discount = $coupon->amount_off / 100;
    return [
      'id' => $coupon->id,
      'success' => true,
      'amount_off' => $discount,
    ];
  }

  public function getCouponForSubscription($subscription)
  {
    global $wpdb;
    return $wpdb->get_row("SELECT * FROM {$this->table} WHERE subscription_id = '{$subscription->id}' LIMIT 1");
  }

  public function createRandomCoupon($subscription, $amount_off, $type)
  {
    global $wpdb;
    $wpdb->insert(
      $this->table,
      array(
        'subscription_id' => $subscription->id,
        'coupon_code' => $this->generateRandomCoupon(),
        'amount_off' => $amount_off,
        'type' => $type,
        'expiry_date' => NULL,
        'status' => 'active',
        'created_at' => current_time('mysql')
      )
    );
  }

  private function generateRandomCoupon($coupon_code = null)
  {
    global $wpdb;

    $coupon = $wpdb->get_row("SELECT * FROM {$this->table} WHERE coupon_code = '{$coupon_code}' LIMIT 1");

    if ($coupon) {
      die($coupon_code);
      return $this->generate_random_coupon($coupon_code);
    }

    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $coupon_code = "";
    for ($i = 0; $i < 7; $i++) {
      $coupon_code .= $chars[mt_rand(0, strlen($chars) - 1)];
    }

    return $coupon_code;
  }
}
