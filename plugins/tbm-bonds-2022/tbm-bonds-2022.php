<?php

/**
 * Plugin Name: TBM Bonds (2022)
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

namespace TBM\Bonds;

class Bonds2022
{
  protected $plugin_name;
  protected $plugin_slug;

  protected $base_price;
  protected $shipping_cost;
  protected $table;

  public static $free_underwear;
  protected $free_underwear_stop_at;

  public static $taking_orders;

  protected $config_order_email;

  public function __construct()
  {
    global $wpdb;

    $this->plugin_name = 'tbm_bonds_2022';
    $this->plugin_slug = 'tbm-bonds-2022';

    $this->table = $wpdb->prefix . 'bonds_2022';
    $this->base_price = 29.90;
    $this->shipping_cost = 8.00;

    $this->shipping_cost = [
      'Australia' => 8.00,
      'other' => 40.00
    ];

    $this->free_underwear_stop_at = 50;
    $total_active_orders = $wpdb->get_var("SELECT COUNT(1) FROM {$this->table} WHERE `status` = 'active' AND `underwear_size` IS NOT NULL");
    self::$free_underwear = $total_active_orders < $this->free_underwear_stop_at;

    $total_active_qty = $wpdb->get_var("SELECT SUM(quantity) FROM {$this->table} WHERE `status` = 'active'");
    self::$taking_orders = $total_active_qty < 240;

    $this->config_order_email['to'] = 'nick.ognenovski@ovato.com.au,frank.mrakovcic@ovato.com.au';
    $this->config_order_email['subject'] = 'New order: BONDS x Rolling Stone 2023 Charity Calendar';

    add_action('wp_footer', [$this, 'wp_footer']);
    add_action('wp_head', [$this, 'wp_head']);

    add_action('wp_ajax_bonds2022_process_payment', [$this, 'process_payment']);
    add_action('wp_ajax_nopriv_bonds2022_process_payment', [$this, 'process_payment']);

    add_action('wp_ajax_bonds2022_order_complete', [$this, 'order_complete']);
    add_action('wp_ajax_nopriv_bonds2022_order_complete', [$this, 'order_complete']);

    add_action('admin_menu', [$this, 'admin_menu']);

    add_action('admin_action_tbm_export_bonds2022', [$this, 'export2022']);
  }

  public function export2022()
  {
    global $wpdb;

    $csv_content = '';

    $query = "SELECT
        s.quantity QTY,
        s.full_name `Buyer name`, s.address_1 `Address 1`, s.address_2 `Address 2`, s.city `City`, s.state `State`, s.postcode `Postcode`, s.country `country`,
        s.shipping_full_name `Recipient name`, s.shipping_address_1 `Shipping address 1`, s.shipping_address_2 `Shipping address 2`, s.shipping_city `Shipping city`,
        s.shipping_state `Shipping state`, s.shipping_postcode `Shipping postcode`, s.shipping_country `Shipping country`,
        s.email `Email`, s.is_gift `Gift?`, s.status `Status`,
        s.created_at `When`
      FROM {$this->table} s";

    $records = $wpdb->get_results($query);

    if ($records) :
      $first = reset($records);
      if ($first) :
        $first = (array)$first;
        $csv_content = implode(",", array_keys($first)) . "\n";
      endif;

      foreach ($records as $record) :
        $record = (array)$record;
        $csv_content .= "\"" . implode("\",\"", $record) . "\"" . "\n";
      endforeach;
    endif;

    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="export-bonds-2022.csv";');
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $csv_content;
    exit();
  }

  public function admin_menu()
  {
    add_menu_page(
      'Bonds Calendar 2022',
      'Bonds Calendar 2022',
      'edit_posts',
      $this->plugin_slug,
      [$this, 'index'],
      'dashicons-calendar',
      10
    );
  }

  public function index()
  {
    global $wpdb;

    $status = isset($_GET['status']) ? $_GET['status'] : '0';

    $statuses = $wpdb->get_results("SELECT status, COUNT(*) total FROM {$this->table} GROUP BY status");

    $query = "SELECT
        s.*
      FROM {$this->table} s
      WHERE 1=1 ";

    if ('0' != $status) {
      $query .= " AND s.status = '{$status}' ";
    }
    $query .= " ORDER BY s.id DESC";

    $records = $wpdb->get_results($query);
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <div class="container-fluid">
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
        <h1 style="line-height: 1.25">BONDS x Rolling Stone 2023 Charity Calendar</h1>
        <div class="d-flex align-items-center justify-content-between">
          <div>
            Filter:
            <a href="<?php echo add_query_arg('status', '0', remove_query_arg('used_coupon')); ?>" style="margin: 0 .25rem;<?php echo '0' == $status ? ' font-weight: bold;' : ''; ?>">All</a>
            <?php foreach ($statuses as $s) : ?>
              |
              <a href="<?php echo add_query_arg('status', $s->status); ?>" style="margin: 0 .25rem;<?php echo $s->status == $status ? ' font-weight: bold;' : ''; ?>"><?php echo ucfirst($s->status); ?> (<?php echo $s->total; ?>)</a>
            <?php endforeach; ?>
          </div>
          <form method="post" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="tbm_export_bonds2022">
            <?php submit_button('Export'); ?>
          </form>
        </div>
      </div>


      <input type="text" name="search-record" id="search-record" placeholder="Search..." class="form-control">
      <table class="table small" cellspacing="0" id="list-records">
        <thead>
          <tr>
            <th>ID</th>
            <th>Recipient </th>
            <th>QTY</th>
            <th>Is Gift?</th>
            <th>Buyer</th>
            <th>Status</th>
            <th>Created Date/Time</th>
            <th>Stripe Record</th>
            <th>Underwear size</th>
          </tr>
        </thead>
        <?php
        if ($records) :
        ?>
          <tbody id="the-list">
            <?php foreach ($records as $record) : ?>
              <tr>
                <td><?php echo $record->id; ?>.</td>
                <td><strong><?php echo $record->shipping_full_name; ?></strong><br>
                  <?php
                  echo $record->shipping_address_1;
                  echo $record->shipping_address_2 ? '<br>' . $record->shipping_address_2 : '';
                  echo '<br>' . $record->shipping_city . ' ' . $record->shipping_state . ' ' . $record->shipping_postcode . ' ' . $record->shipping_country;
                  ?></td>
                <td><?php echo $record->quantity; ?></td>
                <td><?php echo $record->is_gift; ?></td>
                <td><strong><?php echo $record->full_name; ?></strong><br>
                  <a href="mailto:<?php echo $record->email; ?>"><?php echo $record->email; ?></a><br>
                  <?php
                  echo $record->address_1;
                  echo $record->address_2 ? '<br>' . $record->address_2 : '';
                  echo '<br>' . $record->city . ' ' . $record->state . ' ' . $record->postcode . ' ' . $record->country;
                  ?>
                </td>
                <td><?php echo $record->status; ?></td>

                <td><?php echo $record->created_at; ?></td>
                <td><a href="https://dashboard.stripe.com/customers/<?php echo $record->stripe_customer_id; ?>" target="_blank"><?php echo $record->stripe_customer_id; ?></a></td>
                <td><?php echo $record->underwear_size; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        <?php endif; ?>
      </table>
    </div>

    <script>
      jQuery(document).ready(function($) {
        $("#search-record").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#list-records tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
    </script>
  <?php
  }

  public function order_complete()
  {
    global $wpdb;
    $post = $_POST;

    $record_id = absint($post['record_id']);

    if (!$record_id) {
      wp_send_json_success();
      wp_die();
    }

    $wpdb->update(
      $this->table,
      ['status' => 'active'],
      ['id' => $record_id]
    );

    $record = $wpdb->get_row("SELECT 
    `id` order_id,
    `quantity`, `full_name`, `email`, `address_1`, `address_2`, `city`, `state`, `postcode`, `country`, `underwear_size`, `is_gift`, `shipping_full_name`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_state`, `shipping_postcode`, `shipping_country`, `created_at`
    FROM {$this->table} WHERE `id`= '{$record_id}' LIMIT 1");

    // Send order email
    $message = '<table>';
    foreach ($record as $k => $v) {
      $k = ucfirst(str_replace('_', ' ', $k));
      $message .= "<tr><td><strong>{$k}</strong></td><td>:</td><td>{$v}</td></tr>";
    }
    $message .= '</table>';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($this->config_order_email['to'], $this->config_order_email['subject'], $message, $headers);

    if ($record && $record->email) {
      $auth0_user = $this->get_or_create_user(['email' => $record->email], false);
      $user_id = isset($auth0_user['user_id']) ? $auth0_user['user_id'] : false;

      if ($user_id !== false) {
        require __DIR__ . '/classes/braze.class.php';
        $braze = new Braze();
        $braze->setMethod('POST');

        $attributes = [
          'external_id' => $user_id,
          'email' => $record->email,
          'purchased_bonds_calendar_2022' => true,
        ];

        $braze_purchase_properties = [
          'is_gift' => $record->is_gift == 'yes',
          'buyer_name' => isset($record) && !is_null($record) ? $record->full_name : 'Subscriber',
        ];

        $braze->setPayload(
          [
            'attributes' => [$attributes],
            'purchases' => [
              [
                'external_id' => $user_id,
                'name' => 'BONDS x Rolling Stone 2023 Charity Calendar',
                'time' => date('c'),
                'product_id' => 'bonds_rs_2023_calendar',
                'currency' => 'AUD',
                'price' => $record->amount_paid / 100,
                'quantity' => absint($record->quantity),
                'properties' => $braze_purchase_properties
              ]
            ]
          ]
        );
        $braze->request('/users/track');

        wp_send_json_success($user_id);
        wp_die();
      }
    }

    wp_send_json_success();
    wp_die();
  } // order_complete()

  public function process_payment()
  {
    global $wpdb;
    require_once __DIR__ . '/classes/payment.class.php';

    $post = $_POST;

    // Set Shipping address if it' not a gift
    if (!isset($post['purchase_as_gift'])) :

      $required_fields = [
        'buyer_full_name',
        'email',
        'address_1',
        'city',
        'state',
        'postcode',
        'country',
        'quantity',
      ];

      $post['shipping_address_1'] = $post['address_1'];
      $post['shipping_address_2'] = $post['address_2'];
      $post['shipping_city'] = $post['city'];
      $post['shipping_state'] = $post['state'];
      $post['shipping_postcode'] = $post['postcode'];
      $post['shipping_country'] = $post['country'];

      $post['shipping_full_name'] = $post['buyer_full_name'];

      $post['is_gift'] = 'no';
    else :

      $required_fields = [
        'buyer_full_name',
        'email',

        'address_1',
        'city',
        'state',
        'postcode',
        'country',

        'shipping_full_name',
        'shipping_address_1',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',

        'quantity',
      ];

      $post['is_gift'] = 'yes';
    endif;

    if (self::$free_underwear) {
      $required_fields[] = 'underwear_size';
    }


    foreach ($required_fields as $required_field) :
      if (!isset($post[$required_field]) || '' == trim($post[$required_field])) :
        wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have forgotten to fill out all the necessary fields. Make sure you go back and give us all the info we need!', 'elem' => $required_field]]);
        wp_die();
      endif;
    endforeach;

    if (!is_email($post['email'])) {
      wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have entered invalid email address!', 'elem' => 'email']]);
      wp_die();
    }

    if (!array_search($post['shipping_country'], Payment::getCountries()) || '0' == array_search($post['shipping_country'], Payment::getCountries())) :
      wp_send_json_error(['error' => ['message' => 'Looks like you forgot to select your country. Make sure you press the drop down list and pick the correct one!']]);
      wp_die();
    endif;

    $uniqid = uniqid();

    $underwear_size = null;
    if (isset($post['underwear_size'])) {
      $underwear_size = $post['underwear_size'];
    }

    $insert_values = [
      'quantity' => absint($post['quantity']),
      'full_name' => $post['buyer_full_name'],
      'email' => $post['email'],

      'address_1' => $post['address_1'],
      'address_2' => $post['address_2'],
      'city' => $post['city'],
      'state' => $post['state'],
      'postcode' => $post['postcode'],
      'country' => !is_null($post['country']) ? $post['country'] : 'Australia',

      'underwear_size' => $underwear_size,

      'is_gift' => $post['is_gift'],

      'shipping_full_name' => $post['shipping_full_name'],
      'shipping_address_1' => $post['shipping_address_1'],
      'shipping_address_2' => $post['shipping_address_2'],
      'shipping_city' => $post['shipping_city'],
      'shipping_state' => $post['shipping_state'],
      'shipping_postcode' => $post['shipping_postcode'],
      'shipping_country' => !is_null($post['shipping_country']) ? $post['shipping_country'] : 'Australia',

      'status' => 'init',
      'ip_address' => $_SERVER['REMOTE_ADDR'],

      'uniqid' => $uniqid,
    ];

    $wpdb->insert(
      $this->table,
      $insert_values
    );
    $record_id = $wpdb->insert_id;

    /*
    * Create Customer
    */
    $payment = new Payment();
    $buyer = [
      'full_name' => $post['buyer_full_name'],
      'address_1' => $post['address_1'],
      'address_2' => $post['address_2'],
      'city' => $post['city'],
      'country' => array_search($post['country'], self::getCountries()),
      'postcode' => $post['postcode'],
      'state' => $post['state'],
    ];
    $shipping = [
      'full_name' => $post['shipping_full_name'],
      'address_1' => $post['shipping_address_1'],
      'address_2' => $post['shipping_address_2'],
      'city' => $post['shipping_city'],
      'country' => array_search($post['shipping_country'], self::getCountries()),
      'postcode' => $post['shipping_postcode'],
      'state' => $post['shipping_state'],
    ];

    $shipping_cost = in_array($post['shipping_country'], array_keys($this->shipping_cost)) ? $this->shipping_cost[$post['shipping_country']] : $this->shipping_cost['other'];

    $invoice = $payment->createInvoice(
      (int) ($this->base_price * 100),
      (int) ($shipping_cost * 100),
      $post['quantity'],
      $post['payment_method'],
      $post['email'],
      $post['buyer_full_name'],
      $buyer,
      $shipping
    );

    if (isset($invoice['error'])) {
      $wpdb->update(
        $this->table,
        ['payment_error' => $invoice['error'],],
        ['id' => $record_id]
      );
      wp_send_json_error(['error' => ['message' => $invoice['error']]]);
      wp_die();
    }

    /*
    * Update record with Customer & Invoice id
    */
    if ($invoice) :

      $wpdb->update(
        $this->table,
        [
          'stripe_invoice_id' => $invoice['invoice']->id,
          'stripe_customer_id' => $invoice['customer']->id,
          'amount_paid' => $invoice['invoice']->amount_paid,
        ],
        ['id' => $record_id]
      );

      wp_send_json_success(array('record_id' => $record_id, 'invoice' => $invoice['invoice']));
      wp_die();
    endif; // If $customer
  }

  public function wp_head()
  {
    if (!is_page_template('page-templates/bonds-2022.php'))
      return;
  ?>
    <script src="https://js.stripe.com/v3/"></script>
  <?php
  } // wp_head()

  private function getShipping($country_code)
  {
    if ('Australia' == $country_code) {
      return 8.00;
    } else {
      return 40.00;
    }
  }

  public function wp_footer()
  {
    if (!is_page_template('page-templates/bonds-2022.php'))
      return;

    $this->inject_css();

    if (!self::$taking_orders)
      return;
  ?>

    <div class="success-msg-wrap">
      <div class="success-msg">
        Your order has been placed.
      </div>
    </div>

    <script>
      jQuery(document).ready(function($) {

        function updateAmount() {
          var quantity = jQuery('#quantity').val();
          var basePrice = <?php echo number_format($this->base_price, 2); ?> * quantity;
          jQuery('.base-price').text(`$${basePrice.toFixed(2)}`);

          var shipping_country = $("#purchase_as_gift").prop("checked") ? $('#shipping_country').val() : $('#country').val();
          if (shipping_country) {
            if ('Australia' == shipping_country) {
              var shipping = <?php echo number_format($this->shipping_cost['Australia'], 2); ?>;
            } else {
              var shipping = <?php echo number_format($this->shipping_cost['other'], 2); ?>;
            }

            var amountToPay = basePrice + shipping;
            jQuery('.shipping-price').text(`$${shipping.toFixed(2)}`);
            jQuery('.amount-to-pay').text(`$${amountToPay.toFixed(2)}`);
          } else {
            jQuery('.shipping-price').text(`Please select country`);
            jQuery('.amount-to-pay').text(`Please select country`);
          }
        }

        $('.success-msg-wrap').on('click', function() {
          $(this).hide();
          window.location.reload();
        })

        $("#purchase_as_gift").on("change", function() {
          if ($(this).prop("checked")) {
            $("#shipping_address_wrap").show();
            $("#shipping_address_wrap").find("input.req").prop("required", true);
            $("#shipping_address_wrap").find("select.req").prop("required", true);
          } else {
            $("#shipping_address_wrap").hide();
            $("#shipping_address_wrap").find("input").prop("required", false);
            $("#shipping_address_wrap").find("select").prop("required", false);
          }

          updateAmount();
        });

        $('#quantity, #country, #shipping_country').on('change', function() {
          updateAmount();
        })


        updateAmount();
      });

      const theForm = document.querySelector("#form-bonds-2022");
      var stripe;
      var isFormProcessing = false;
      var card;

      var stripeElements = function(publicKey) {
        stripe = Stripe(publicKey);
        var elements = stripe.elements();

        // Element styles
        var style = {
          base: {
            fontSize: "16px",
            color: "#32325d",
            fontFamily: "-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif",
            fontSmoothing: "antialiased",
            "::placeholder": {
              color: "rgba(0,0,0,0.4)",
            },
          },
        };

        card = elements.create("card", {
          style: style
        });

        card.mount("#card-element");

        // Element focus ring
        card.on("focus", function() {
          var el = document.getElementById("card-element");
          el.classList.add("focused");
        });

        card.on("blur", function() {
          var el = document.getElementById("card-element");
          el.classList.remove("focused");
        });

        document.querySelector("button#submit-bonds-2022").addEventListener("click", function(evt) {
          showCardError("");
          document.querySelector("#js-success").textContent = "Processing, do not close this window. Please wait...";
          evt.preventDefault();
          changeLoadingState(true);
          // Initiate payment
          if (!isFormProcessing) {
            createPaymentMethodAndCustomer(stripe, card);
            isFormProcessing = true;
          }
          isTimerActive = false;
        });
      };

      function showCardError(error) {
        changeLoadingState(false);
        var errorMsg = document.querySelector("#js-errors");
        errorMsg.textContent = "";

        var elem_errs = document.querySelector(".req_err");
        if (elem_errs != null) {
          elem_errs.classList.remove("req_err");
        }

        if ("" != error) {
          isFormProcessing = false;
          document.querySelector("#js-success").textContent = "";
          errorMsg.textContent = error.message;
          var elems = document.getElementsByName(error.elem);
          if (elems.length > 0) {
            elems[0].classList.add("req_err");
            elems[0].scrollIntoView({
              behavior: "smooth",
              block: "center"
            });
          }
        }
        // setTimeout(function() { errorMsg.textContent = ''; }, 15000);
      }

      var createPaymentMethodAndCustomer = function(stripe, card) {
        var cardholderEmail = document.querySelector("#email").value;
        stripe
          .createPaymentMethod("card", card, {
            billing_details: {
              email: cardholderEmail,
            },
          })
          .then(function(result) {
            if (result.error) {
              showCardError(result.error);
            } else {
              createCustomer(result.paymentMethod.id, cardholderEmail);
            }
          });
      };

      async function createCustomer(paymentMethod, cardholderEmail) {
        var formData = new FormData(theForm);
        formData.append("action", "bonds2022_process_payment");
        // formData.append("nonce", tbm_mag_sub.nonce);
        formData.append("payment_method", paymentMethod);
        return fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: "post",
            body: formData,
          })
          .then((response) => {
            return response.json();
          })
          .then((result) => {
            if (result.success) {
              handlePayment(result.data);
            } else {
              showCardError(result.data.error);
            }
          });
      }

      function handlePayment(data) {
        const {
          record_id,
          invoice
        } = data;

        const {
          payment_intent
        } = invoice;

        if (payment_intent) {
          const {
            client_secret,
            status
          } = payment_intent;

          if (status === "requires_action") {
            stripe.confirmCardPayment(client_secret).then(function(result) {
              if (result.error) {
                changeLoadingState(false);
                showCardError(result.error);
              } else {
                orderComplete(record_id);
              }
            });
          } else {
            // No additional information was needed
            // Show a success message to your customer
            orderComplete(record_id);
          }
        } else {
          orderComplete(record_id);
        }
      }

      function getPublicKey() {
        var formData = new FormData();
        formData.append("action", "get_stripe_public_key");
        return fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: "post",
            body: formData, // { action: 'get_stripe_public_key' },
          })
          .then(function(response) {
            return response.json();
          })
          .then(function(response) {
            stripeElements(response.data);
          });
      }

      getPublicKey();

      /* Shows a success / error message when the payment is complete */
      var orderComplete = function(record_id) {
        var formData = new FormData();
        formData.append("action", "bonds2022_order_complete");
        // formData.append("nonce", tbm_mag_sub.nonce);
        formData.append("record_id", record_id);
        return fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: "post",
            body: formData,
          })
          .then((response) => {
            return response.json();
          })
          .then((result) => {
            if (result.success) {
              // changeLoadingState(false);

              // var subscriptionJson = JSON.stringify(subscription, null, 2);

              document.querySelectorAll(".completed-view").forEach(function(view) {
                view.classList.remove("hidden");
              });

              document.querySelector("#js-success").textContent = "Congratulations!";

              jQuery('.success-msg-wrap').addClass('active');

              document.getElementById('form-bonds-2022').reset();
              updateAmount();
              card.clear();

            } else {
              showCardError(result.data.error);
            }
          });
      };

      // Show a spinner on form submission
      var changeLoadingState = function(isLoading) {
        if (isLoading) {
          document.querySelector("#spinner").classList.add("loading");
          document.querySelector("button").classList.add("hidden");
          document.querySelector("#button-text").classList.add("hidden");
        } else {
          document.querySelector("#spinner").classList.remove("loading");
          // document.querySelector("button").disabled = false;
          document.querySelector("button").classList.remove("hidden");
          document.querySelector("#button-text").classList.remove("hidden");
        }
      };

      // Show a spinner on coupon validation
      var changeLoadingStateCoupon = function(isLoading) {
        if (isLoading) {
          document.querySelector("#spinner-coupon").classList.add("loading");
        } else {
          document.querySelector("#spinner-coupon").classList.remove("loading");
        }
      };
    </script>
  <?php
  } // wp_footer()

  private function inject_css()
  {
  ?>
    <style>
      html {
        scroll-behavior: smooth;
      }

      .form-bonds {
        font-family: Graphik, sans-serif;
        /* background-color: #fff; */
      }

      .post-password-form {
        font-family: Graphik, sans-serif;
        font-weight: 300;
      }

      .post-password-form input {
        border: 1px solid #ccc;
        border-radius: .25rem;
        padding: .25rem .5rem;
      }

      .card-element {
        height: 40px;
        padding: 10px 12px;
        width: 100%;
        color: #32325d;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
      }

      .form-control {
        font-weight: 300;
      }

      select.form-control {
        height: 50px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' fill='%333333F2'><polygon points='0,0 100,0 50,50'/></svg>") no-repeat;
        background-size: 12px;
        background-position: calc(100% - 20px) center;
        background-repeat: no-repeat;
        background-color: #ffffff;
      }

      .form-control::placeholder,
      select.form-control:invalid {
        color: #999;
        font-size: 90%;
      }

      #submit-bonds-2022 {
        width: 100%;
        padding: .75rem 1rem;
        border: none;
        background-color: #d32531;
        color: white;
        font-size: 1rem;
        font-weight: bold;
        letter-spacing: 0.4px;
        border-radius: .5rem;
      }

      input.req_err,
      select.req_err {
        border: 1px solid #ff0000;
      }

      .error {
        color: red;
        margin-top: 1rem;
        margin-bottom: 1rem;
      }

      .success {
        color: #61ba00;
        margin-top: 1rem;
        margin-bottom: 1rem;
      }

      .success-msg-wrap {
        position: fixed;
        cursor: pointer;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        /* bottom: -100%; */
        background-color: rgba(0, 0, 0, .85);
        display: flex;
        justify-content: flex-end;
        align-items: flex-end;
        z-index: 3000;
        opacity: 0;
        visibility: hidden;
        transition: .25s all linear;
      }

      .success-msg-wrap.active {
        /* top: 0;
        bottom: 0; */
        visibility: visible;
        opacity: 1;
      }

      .success-msg {
        color: #fff;
        font-size: 200%;
        background-color: #61ba00;
        padding: 3rem;
        box-shadow: 0 0 100px #000;
        text-align: center;
        flex: 1;
      }

      .justify-content-start {
        justify-content: flex-start;
      }

      .justify-content-between {
        justify-content: space-between;
      }

      .align-items-start {
        align-items: flex-start !important;
      }

      .align-items-end {
        align-items: flex-end;
      }

      .align-items-stretch {
        align-items: stretch;
      }

      .flex-fill {
        flex: 1;
      }

      <?php foreach (range(1, 4) as $i) : ?>.mt-<?php echo $i; ?>,
      .my-<?php echo $i; ?> {
        margin-top: <?php echo $i / 2; ?>rem !important;
      }

      .mb-<?php echo $i; ?>,
      .my-<?php echo $i; ?> {
        margin-bottom: <?php echo $i / 2; ?>rem !important;
      }

      .ml-<?php echo $i; ?>,
      .mx-<?php echo $i; ?> {
        margin-left: <?php echo $i / 2; ?>rem !important;
      }

      .mr-<?php echo $i; ?>,
      .mx-<?php echo $i; ?> {
        margin-right: <?php echo $i / 2; ?>rem !important;
      }

      .p-<?php echo $i; ?> {
        padding: <?php echo $i / 2; ?>rem;
      }

      .pt-<?php echo $i; ?>,
      .py-<?php echo $i; ?> {
        padding-top: <?php echo $i / 2; ?>rem !important;
      }

      .pb-<?php echo $i; ?>,
      .py-<?php echo $i; ?> {
        padding-bottom: <?php echo $i / 2; ?>rem !important;
      }

      .pl-<?php echo $i; ?>,
      .px-<?php echo $i; ?> {
        padding-left: <?php echo $i / 2; ?>rem !important;
      }

      .pr-<?php echo $i; ?>,
      .px-<?php echo $i; ?> {
        padding-right: <?php echo $i / 2; ?>rem !important;
      }

      <?php endforeach; ?><?php for ($i = 1; $i <= 12; $i++) { ?>.col-<?php echo $i; ?> {
        flex: 0 0 <?php echo $i * 8.333333; ?>%;
      }

      <?php } ?>@media (min-width: 48rem) {


        .text-md-left {
          text-align: left !important;
        }

        .text-md-right {
          text-align: right !important;
        }

        .btn {
          padding: .5rem 1rem;
        }

        .d-md-none {
          display: none !important;
        }

        .d-md-block {
          display: block !important;
        }

        .d-md-flex {
          display: flex !important;
        }

        .flex-md-row {
          flex-direction: row !important;
        }

        .flex-md-column {
          flex-direction: column !important;
        }

        .col-md-6 {
          flex: 0 0 50%;
        }

        <?php foreach (range(0, 4) as $i) : ?>.m-md-<?php echo $i; ?> {
          margin: <?php echo $i / 2; ?>rem;
        }

        .ml-md-<?php echo $i; ?>,
        .mx-md-<?php echo $i; ?> {
          margin-left: <?php echo $i / 2; ?>rem !important;
        }

        .mr-md-<?php echo $i; ?>,
        .mx-md-<?php echo $i; ?> {
          margin-right: <?php echo $i / 2; ?>rem !important;
        }

        .mt-md-<?php echo $i; ?>,
        .my-md-<?php echo $i; ?> {
          margin-top: <?php echo $i / 2; ?>rem !important;
        }

        .mb-md-<?php echo $i; ?>,
        .my-md-<?php echo $i; ?> {
          margin-bottom: <?php echo $i / 2; ?>rem !important;
        }

        .p-md-<?php echo $i; ?> {
          padding: <?php echo $i / 2; ?>rem;
        }

        .pt-md-<?php echo $i; ?>,
        .py-md-<?php echo $i; ?> {
          padding-top: <?php echo $i / 2; ?>rem !important;
        }

        .pb-md-<?php echo $i; ?>,
        .py-md-<?php echo $i; ?> {
          padding-bottom: <?php echo $i / 2; ?>rem !important;
        }

        .pl-md-<?php echo $i; ?>,
        .px-md-<?php echo $i; ?> {
          padding-left: <?php echo $i / 2; ?>rem !important;
        }

        .pr-md-<?php echo $i; ?>,
        .px-md-<?php echo $i; ?> {
          padding-right: <?php echo $i / 2; ?>rem !important;
        }

        <?php endforeach; ?><?php for ($i = 1; $i <= 12; $i++) { ?>.col-md-<?php echo $i; ?> {
          flex: 0 0 <?php echo $i * 8.333333; ?>%;
        }

        <?php } ?>
      }

      @media (min-width: 48rem) {
        <?php for ($i = 1; $i <= 12; $i++) { ?>.col-lg-<?php echo $i; ?> {
          flex: 0 0 <?php echo $i * 8.333333; ?>%;
        }

        <?php } ?>
      }
    </style>
<?php
  }

  /*
  * Get Countries
  */
  public static function getCountries()
  {
    require_once __DIR__ . '/classes/helper.class.php';
    return Helper::getCountries();
  }

  public function get_or_create_user($data = null, $ajax = true)
  {
    $data = !is_null($data) ? stripslashes_deep($data) : [];

    if (isset($data['email']) && is_email($data['email'])) {
      require __DIR__ . '/vendor/autoload.php';

      try {
        $auth0 = new \Auth0\SDK\Auth0([
          'domain' => 'AUTH0_DOMAIN',
          'clientId' => 'AUTH0_CLIENT_ID',
          'clientSecret' => 'AUTH0_CLIENT_SECRET',
        ]);

        $management = $auth0->management();

        $response = $management->UsersByEmail()->get($data['email']);

        if ($response->getStatusCode() === 200) {
          // Decode the JSON response into a PHP array:
          $response = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);

          if (!empty($response)) {
            // User found
            if ($ajax) {
              wp_send_json_success(
                $response[0]['user_id']
              );
              die();
            } else {
              return [
                'new_user' => false,
                'user_id' => $response[0]['user_id']
              ];
            }
          } else {
            // Create user
            try {
              $user_pass = wp_generate_password(12, true, true);
              $response = $management->users()->create(
                'brag-observer',
                [
                  'email' => trim($data['email']),
                  'password' => $user_pass,
                  'email_verified' => true,
                ]
              );
              if ($response->getStatusCode() === 201) {
                $auth0_user = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
                if ($ajax) {
                  wp_send_json_success(
                    $auth0_user['user_id']
                  );
                  die();
                } else {
                  return [
                    'new_user' => true,
                    'user_id' => $auth0_user['user_id'],
                    'email' => trim($data['email']),
                    'password' => $user_pass,
                  ];
                }
              } else {
                if ($ajax) {
                  wp_send_json_error($response->getStatusCode());
                  die();
                } else {
                  return false;
                }
              }
            } catch (\Exception $e) {
              error_log('Line: ' . __LINE__ . ' ' .  $e->getMessage());
            }
          }
        } else {
          if ($ajax) {
            wp_send_json_error($response->getStatusCode());
            die();
          } else {
            return false;
          }
        }
      } catch (\Exception $e) {
        error_log('Line: ' . __LINE__ . ' ' .  $e->getMessage());
      }
    }

    wp_send_json_error('Error');
    die();
  }
}

new Bonds2022();
