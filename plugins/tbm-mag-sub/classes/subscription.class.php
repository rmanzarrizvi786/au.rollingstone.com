<?php
class Subscription
{

  protected $table;

  public $id;
  public $status;

  public function __construct()
  {
    global $wpdb;
    $this->table = $wpdb->prefix . 'mag_subscriptions';
  }

  public function get($id)
  {
    global $wpdb;
    $subscription = $wpdb->get_row("SELECT * FROM {$this->table} WHERE id = '" . $id . "' LIMIT 1");
    // $this->id = $subscription->id;
    if ($subscription) {
      foreach ($subscription as $key => $value) {
        $this->$key = $value;
      }
      return $this;
    }
    // return $subscription;
  }

  public function get_by($filters = [])
  {
    if (count($filters) == 0)
      return;

    global $wpdb;

    $where_clause = " 1 = 1 AND salesforce_id IS NOT NULL ";

    foreach ($filters as $filter) {
      $op = isset($filter['op']) ? $filter['op'] : '=';
      $where_clause .= " AND {$filter['field']} {$op} '{$filter['value']}' ";
    }

    $query = "
    SELECT
      uniqid,
      is_gift,
      full_name, address_1, address_2, city, state, postcode, country,
      sub_full_name, shipping_address_1, shipping_address_2, shipping_city, shipping_state, shipping_postcode, shipping_country, status,
      stripe_customer_id,
      salesforce_id
    FROM
      {$this->table}
    WHERE
      {$where_clause}
    ";

    $subscriptions = $wpdb->get_results($query);

    return $subscriptions;
  }

  public function create($post)
  {

    global $wpdb;

    $uniqid = uniqid();

    $insert_values = [
      'full_name' => $post['buyer_full_name'],
      'email' => $post['sub_email'],

      'address_1' => $post['sub_address_1'],
      'address_2' => $post['sub_address_2'],
      'city' => $post['sub_city'],
      'state' => $post['sub_state'],
      'postcode' => $post['sub_postcode'],
      'country' => !is_null($post['sub_country']) ? $post['sub_country'] : 'Australia',

      'coupon_id' => $post['coupon_id'],
      'is_gift' => $post['is_gift'],

      'sub_full_name' => $post['sub_full_name'],
      'shipping_address_1' => $post['shipping_address_1'],
      'shipping_address_2' => $post['shipping_address_2'],
      'shipping_city' => $post['shipping_city'],
      'shipping_state' => $post['shipping_state'],
      'shipping_postcode' => $post['shipping_postcode'],
      'shipping_country' => !is_null($post['shipping_country']) ? $post['shipping_country'] : 'Australia',

      'status' => 'init',
      'ip_address' => $_SERVER['REMOTE_ADDR'],

      'uniqid' => $uniqid,

      'promotion_response' => isset($post['promotion_response']) && '' != trim($post['promotion_response']) && !is_null($post['promo_response_prefix']) ? $post['promo_response_prefix'] . ": \n" . trim($post['promotion_response']) : NULL,
    ];

    if (isset($post['tshirt_size'])) {
      $insert_values['tshirt_size'] = $post['tshirt_size'];
    }

    $mag_sub = $wpdb->insert(
      $this->table,
      $insert_values
    );
    // $_SESSION['sub_record'] = $wpdb->insert_id;
    $this->id = $wpdb->insert_id;

    return $this;
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
}
