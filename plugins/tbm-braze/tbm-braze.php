<?php

/**
 * Plugin Name: TBM Braze
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

namespace TBM;

class Braze
{

  protected $plugin_title;
  protected $plugin_name;
  protected $plugin_slug;
  protected $is_sandbox;
  protected $api_key;
  protected $sdk_api_key;
  protected $safariWebsitePushId;
  protected $api_url;
  protected $categoryToCanvasId;
  protected $enablePush;

  public function __construct()
  {
    $this->plugin_title = 'TBM Braze';
    $this->plugin_name = 'tbm_braze';
    $this->plugin_slug = 'tbm-braze';
    $this->is_sandbox = (isset($_ENV) && isset($_ENV['ENVIRONMENT']) && 'sandbox' == $_ENV['ENVIRONMENT']) || str_contains($_SERVER['SERVER_NAME'], 'staging.') || str_contains($_SERVER['SERVER_NAME'], 'rs.thebrag.com');

    $this->api_key = $this->is_sandbox ? '4bdcad2b-f354-48b5-a305-7a9d77eb356e' : '3570732f-b2bd-4687-9b19-e2cb32f226ae';

    $this->sdk_api_key = $this->is_sandbox ? '08d1f29b-c48e-4bb3-aef3-e133789a0c89' : '8e7d8809-1deb-4183-8ec1-91e49e70a5e4'; // Rolling Stone Australia

    $this->api_url = 'https://rest.iad-05.braze.com';

    $this->safariWebsitePushId = 'web.com.rollingstone.au';

    if ($this->is_sandbox) {
      $this->categoryToCanvasId = [
        'music' => 'd44db806-2438-4661-9349-f30e7e36eedb'
      ];
    } else {
      $this->categoryToCanvasId = [
        'music' => '51681b3c-28fd-4af5-8449-0c691ed211fe',
        'culture' => 'bc72cdaf-fd29-4fc7-8c90-e48438b09445',
        'movies' => '39cc6959-46a8-42ec-8e68-1ba185a65efb',
        'tv' => '6519475c-34df-4783-89bd-48e2716db2a1',
        'sports' => '2b62034e-fb2a-4360-a5de-2ba92d49cf64',
        'politics' => '3f4e6104-9a9f-4a65-87de-6fe40704f566',
      ];
    }

    $this->enablePush = true;

    add_action('wp_footer', [$this, 'wp_footer']);

    add_action('wp_ajax_get_user_external_id', [$this, 'get_user_external_id']);
    add_action('wp_ajax_nopriv_get_user_external_id', [$this, 'get_user_external_id']);

    add_action('publish_post', [$this, 'publish_post'], 10, 3);
  }

  function publish_post($post_id, $post, $old_status)
  {
    if (!$this->enablePush)
      return;

    // Stop if post's old status is published
    if ('publish' == $old_status)
      return;

    // Get categories for the post
    $categories = get_the_category($post_id);

    // Stop if categories is not set
    if (!$categories)
      return;
    $categories = wp_list_pluck($categories, 'slug');

    // Get categories for which canvases are mapped
    $availableCategories = array_keys($this->categoryToCanvasId);

    foreach ($categories as $category) {
      // Stop if category is not in canvas mapping array
      if (in_array($category, $availableCategories)) {
        $the_canvas_id = $this->categoryToCanvasId[$category];
        break;
      }
    }

    if (isset($the_canvas_id)) {
      // Get ready with body to send with post request
      $body = [
        'canvas_id' => $the_canvas_id,
        'broadcast' => true,
        'canvas_entry_properties' => [
          'title' => get_the_title($post_id),
          'message' => get_the_excerpt($post_id),
          'url' => get_the_permalink($post_id),
          'image_url' => get_the_post_thumbnail_url($post_id)
        ]
      ];

      $body = wp_json_encode($body);

      // Send post request to braze to trigger canvas send
      wp_remote_post(
        $this->api_url . '/canvas/trigger/send',
        [
          'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->api_key,
          ],
          'body' => $body,
        ]
      );
    }
  } // publish_post hook

  public function get_user_external_id()
  {
    global $wpdb;
    if (is_user_logged_in()) {
      $user_id = get_current_user_id();
      if (!$user_id)
        return;
      $auth0_user_id = get_user_meta($user_id, $wpdb->prefix . 'auth0_id', true);
      wp_send_json_success($auth0_user_id);
      wp_die();
    } // If user is logged in
    wp_send_json_error('Not logged in');
    wp_die();
  } // get_user_external_id()

  public function wp_footer()
  {
?>
    <script>
      window.getUserExternalId = async () => {
        return new Promise(function(resolve, reject) {
          jQuery.get('<?php echo admin_url('admin-ajax.php'); ?>', {
            action: 'get_user_external_id',
          }, function(res) {
            if (res.success && res.data) {
              resolve(res.data);
            }
            reject(null);
          });
        });
      }

      window.callBraze = () => {
        + function(a, p, P, b, y) {
          a.braze = {};
          a.brazeQueue = [];
          for (var s = "BrazeSdkMetadata DeviceProperties Card Card.prototype.dismissCard Card.prototype.removeAllSubscriptions Card.prototype.removeSubscription Card.prototype.subscribeToClickedEvent Card.prototype.subscribeToDismissedEvent Card.fromContentCardsJson Banner CaptionedImage ClassicCard ControlCard ContentCards ContentCards.prototype.getUnviewedCardCount Feed Feed.prototype.getUnreadCardCount ControlMessage InAppMessage InAppMessage.SlideFrom InAppMessage.ClickAction InAppMessage.DismissType InAppMessage.OpenTarget InAppMessage.ImageStyle InAppMessage.Orientation InAppMessage.TextAlignment InAppMessage.CropType InAppMessage.prototype.closeMessage InAppMessage.prototype.removeAllSubscriptions InAppMessage.prototype.removeSubscription InAppMessage.prototype.subscribeToClickedEvent InAppMessage.prototype.subscribeToDismissedEvent InAppMessage.fromJson FullScreenMessage ModalMessage HtmlMessage SlideUpMessage User User.Genders User.NotificationSubscriptionTypes User.prototype.addAlias User.prototype.addToCustomAttributeArray User.prototype.addToSubscriptionGroup User.prototype.getUserId User.prototype.incrementCustomUserAttribute User.prototype.removeFromCustomAttributeArray User.prototype.removeFromSubscriptionGroup User.prototype.setCountry User.prototype.setCustomLocationAttribute User.prototype.setCustomUserAttribute User.prototype.setDateOfBirth User.prototype.setEmail User.prototype.setEmailNotificationSubscriptionType User.prototype.setFirstName User.prototype.setGender User.prototype.setHomeCity User.prototype.setLanguage User.prototype.setLastKnownLocation User.prototype.setLastName User.prototype.setPhoneNumber User.prototype.setPushNotificationSubscriptionType InAppMessageButton InAppMessageButton.prototype.removeAllSubscriptions InAppMessageButton.prototype.removeSubscription InAppMessageButton.prototype.subscribeToClickedEvent automaticallyShowInAppMessages destroyFeed hideContentCards showContentCards showFeed showInAppMessage toggleContentCards toggleFeed changeUser destroy getDeviceId initialize isPushBlocked isPushPermissionGranted isPushSupported logCardClick logCardDismissal logCardImpressions logContentCardsDisplayed logCustomEvent logFeedDisplayed logInAppMessageButtonClick logInAppMessageClick logInAppMessageHtmlClick logInAppMessageImpression logPurchase openSession requestPushPermission removeAllSubscriptions removeSubscription requestContentCardsRefresh requestFeedRefresh requestImmediateDataFlush enableSDK isDisabled setLogger setSdkAuthenticationSignature addSdkMetadata disableSDK subscribeToContentCardsUpdates subscribeToFeedUpdates subscribeToInAppMessage subscribeToSdkAuthenticationFailures toggleLogging unregisterPush wipeData handleBrazeAction".split(" "), i = 0; i < s.length; i++) {
            for (var m = s[i], k = a.braze, l = m.split("."), j = 0; j < l.length - 1; j++) k = k[l[j]];
            k[l[j]] = (new Function("return function " + m.replace(/\./g, "_") + "(){window.brazeQueue.push(arguments); return true}"))()
          }
          window.braze.getCachedContentCards = function() {
            return new window.braze.ContentCards
          };
          window.braze.getCachedFeed = function() {
            return new window.braze.Feed
          };
          window.braze.getUser = function() {
            return new window.braze.User
          };
          (y = p.createElement(P)).type = 'text/javascript';
          y.src = 'https://js.appboycdn.com/web-sdk/4.0/braze.min.js';
          y.async = 1;
          (b = p.getElementsByTagName(P)[0]).parentNode.insertBefore(y, b)
        }(window, document, 'script');

        // initialize the SDK
        braze.initialize('<?php echo $this->sdk_api_key; ?>', {
          baseUrl: "sdk.iad-05.braze.com",
          inAppMessageZIndex: 12000,
          allowUserSuppliedJavascript: true,
          <?php echo $this->safariWebsitePushId ? "safariWebsitePushId: '{$this->safariWebsitePushId}'," : ''; ?>
          <?php echo $this->is_sandbox || current_user_can('administrator') ? 'minimumIntervalBetweenTriggerActionsInSeconds: 2,' : ''; ?>
          <?php echo $this->is_sandbox || current_user_can('administrator') ? 'enableLogging: true,' : ''; ?>
        });

        if (window.getUserExternalId) {
          getUserExternalId().then(function(user_external_id) {
              braze.changeUser(user_external_id);
            },
            function(e) {}
          )
        }

        <?php if ($this->enablePush) : ?>
          braze.logCustomEvent("prime-for-push-rs");

          window.braze.subscribeToInAppMessage(function(inAppMessage) {
            var shouldDisplay = true;

            if (inAppMessage instanceof window.braze.InAppMessage) {
              // Read the key-value pair for msg-id
              var msgId = inAppMessage.extras["msg-id"];

              // If this is our push primer message
              if (msgId == "push-primer-rs") {
                // We don't want to display the soft push prompt to users on browsers that don't support push, or if the user has already granted/blocked permission
                if (
                  !window.braze.isPushSupported() ||
                  window.braze.isPushPermissionGranted() ||
                  window.braze.isPushBlocked()
                ) {
                  shouldDisplay = false;
                }
              }
            }

            // Display the message
            if (shouldDisplay) {
              window.braze.showInAppMessage(inAppMessage);
            }
          });
        <?php endif; // If push is enabled 
        ?>
      }
    </script>
<?php
  } // wp_footer()
}

new Braze();
