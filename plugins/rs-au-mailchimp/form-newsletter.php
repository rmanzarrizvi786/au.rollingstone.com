<?php
wp_enqueue_script( 'edm', plugin_dir_url( __FILE__ ) . '/js/edm.js', array( 'jquery' ), time(), true );

$admin_ajax_url = admin_url( 'admin-ajax.php' );
$args = array(
  'ajaxurl'   => $admin_ajax_url,
);
wp_localize_script( 'edm', 'edm', $args );

wp_enqueue_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . '/js/jquery-ui.js', array ( 'jquery' ), 1.0, true);
wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '/css/jquery-ui.css' );

wp_enqueue_style( 'edm', plugin_dir_url( __FILE__ ) . '/css/edm.css', array(), time() );

wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' );

wp_enqueue_script( 'td-jquery-autocomplete', plugin_dir_url( __FILE__ ) . '/js/jquery.auto-complete.js', array( 'jquery' ), '1.0', true );
?>
<form method="post" action="options.php" class="create-campaign">
  <?php if ( isset($newsletter) ): ?>
    <input type="hidden" name="id" id="newsletter-id" value="<?php echo $newsletter->id; ?>">
    <h1>Edit "<?php echo $newsletter->details->subject; ?>"</h1>
  <?php else: ?>
    <h1>Create New Newsletter</h1>
  <?php endif; ?>

  <div class="row">
      <div class="col-md-5" style="border-right: 1px solid #ddd;">
        <table class="table table-condensed table-borderless">
          <!-- MailChimp Campaign Details -->
          <tr>
            <th>Campaign Details <small>(for MailChimp)</small></th>
          </tr>
          <tr>
            <td style="width: 150px;">
              Date
              <input type="text" name="date_for" class="datepicker form-control form-control-sm" readonly value="<?php echo isset($newsletter) && isset($newsletter->details->date_for) ? date('j F Y', strtotime($newsletter->details->date_for)) : date('j F Y'); ?>"></td>
          </tr>
          <tr>
            <td>
              Title
              <input type="text" name="title" value="<?php echo isset($newsletter) && isset($newsletter->details->title) ? htmlentities( $newsletter->details->title ) : $this->defaults['title']; ?>" class="form-control form-control-sm"></td>
          </tr>
          <tr>
            <td>
              Subject <small>Max 150 characters</small>
              <input type="text" name="subject" value="<?php echo isset($newsletter) && isset($newsletter->details->subject) ? htmlentities( $newsletter->details->subject ) : $this->defaults['subject']; ?>" maxlength="150" class="form-control form-control-sm"></td>
          </tr>
          <tr>
            <td>
              Preview Text <small>Max 150 characters</small>
              <input type="text" name="preview_text" value="<?php echo isset($newsletter) && isset($newsletter->details->preview_text) ? htmlentities( $newsletter->details->preview_text ) : ''; ?>" maxlength="150" class="form-control form-control-sm"></td>
          </tr>
          <tr>
            <td>
              Reply to Email Address
              <input type="text" name="reply_to" value="<?php echo isset($newsletter) && isset($newsletter->details->reply_to) ? $newsletter->details->reply_to : $this->defaults['replyto']; ?>" class="form-control form-control-sm"></td>
          </tr>
          <tr>
            <td>
              From Name
              <input type="text" name="from_name" value="<?php echo isset($newsletter) && isset($newsletter->details->from_name) ? $newsletter->details->from_name : $this->defaults['from_name']; ?>" class="form-control form-control-sm"></td>
          </tr>
          <!-- MailChimp Campaign Details -->

          <!-- <tr><th><hr></th></tr> -->
        </table>

        <hr>

        <table class="table table-condensed table-borderless">
          <tr>
            <th>Search the post and select to add to the list on the right.</th>
          </tr>

          <!-- Add Post (Cover Story) (AJAX) -->
          <tr>
            <td>
              <strong>Cover Story</strong> <a href="#" class="add-cover-story-blank float-right">Add blank</a>
              <input type="text" id="add-cover-story" size="30" placeholder="Search..." class="form-control form-control-sm">

            </td>
          </tr>
          <!-- Add Post (Cover Story) (AJAX) -->

          <!-- Add Post (AJAX) -->
          <tr>
            <td>
              <strong>Add Article</strong>
              <!-- <small id="total-posts"> # of articles: <span class="total">0</span></small> -->
              <a href="#" class="add-post-blank float-right">Add blank</a>
              <input type="text" id="add-post" size="30" placeholder="Search..." class="form-control form-control-sm">

            </td>
          </tr>
          <!-- Add Post (AJAX) -->

          <!-- <tr><th><hr></th></tr> -->
        </table>

        <hr>

        <table class="table table-condensed table-borderless">
          <!-- Middle Ads -->
          <?php for( $i = 1; $i <= 3; $i++ ) :
            // echo 'ad_middle_$i_link'; exit;
            ?>
          <tr>
            <td colspan="2">
              <div class="mb-2"><strong>Ad <?php echo $i; ?></strong></div>
              Target Link
              <input type="text" name="ad_middle_<?php echo $i; ?>_link" value="<?php echo isset($newsletter) && isset($newsletter->details->{'ad_middle_' . $i . '_link'}) ? $newsletter->details->{'ad_middle_' . $i . '_link'} : ''; ?>" class="form-control form-control-sm">
              Image URL
              <input type="text" name="ad_middle_<?php echo $i; ?>_image" value="<?php echo isset($newsletter) && isset($newsletter->details->{'ad_middle_' . $i . '_image'}) ? $newsletter->details->{'ad_middle_' . $i . '_image'} : ''; ?>" class="form-control form-control-sm"></td>
          </tr>
          <?php endfor; ?>
          <!-- Middle Ads -->
        </table>
      </div>

      <div class="col-md-7">
        <div id="campaign-posts-wrap">
          <h3 style="float:left; margin: 0;">Cover Story</h3>
          <a href="#" class="add-cover-story-blank" style="float: right">Add blank</a>
          <br class="clear">
          <table id="campaign-cover-story" class="table table-borderless">
            <?php if ( isset( $newsletter->details->cover_story_title ) ) : ?>
            <tr id="cover_story_wrap">
              <td width="50">
                <a href="<?php echo $newsletter->details->cover_story_link; ?>" target="_blank">
                  <img src="<?php echo $newsletter->details->cover_story_image; ?>" width="50">
                </a>
              </td>
              <td>
                Link: <input type="text" name="cover_story_link" value="<?php echo $newsletter->details->cover_story_link; ?>" class="link_remote form-control">
                <div class="remote_content">
                  Title: <input type="text" name="cover_story_title" value="<?php echo htmlentities( $newsletter->details->cover_story_title ); ?>" class="title form-control">
                  Blurb: <textarea name="cover_story_excerpt" class="excerpt form-control"><?php echo htmlentities( $newsletter->details->cover_story_excerpt ); ?></textarea>
                  Image: <input type="text" name="cover_story_image" value="<?php echo $newsletter->details->cover_story_image; ?>" class="image form-control">
                </div>
              </td>
              <td width="25"><label class="remove remove-cover-story" data-id="cover_story_wrap">x</label></td>
            </tr>
            <?php endif; ?>
          </table>

          <hr>

          <h3 style="float: left; margin: 0;">Articles</h3>
          <a href="#" class="add-post-blank" style="float: right;">Add blank</a>
          <br class="clear">
          <table id="campaign-posts" class="table table-condensed table-borderless">
            <tr>
              <th width="50">Order</th>
              <th colspan="2">Post</th>
            </tr>
            <?php
            if ( isset( $newsletter ) && isset( $newsletter->details->posts ) ):
              $i = 1;
              foreach ( $newsletter->details->posts as $post_id => $order):
                ?>
            <tr class="campaign-post" id="campaign-post-<?php echo $post_id; ?>">
              <td>
                <input type="number" maxlength="2" min="1" class="campaign-posts form-control" data-id="<?php echo $post_id; ?>" name="posts[<?php echo $post_id; ?>]" value="<?php echo $i; ?>" size="2">
                <?php if ( $newsletter->details->post_images->{$post_id} != '' ) : ?>
                  <a href="<?php echo $newsletter->details->post_links->{$post_id}; ?>" target="_blank">
                    <img src="<?php echo $newsletter->details->post_images->{$post_id}; ?>" width="50">
                  </a>
                <?php endif; ?>
              </td>
              <td>
                Link: <input type="text" name="post_links[<?php echo $post_id; ?>]" value="<?php echo $newsletter->details->post_links->{$post_id}; ?>" class="link_remote form-control">
                <div class="remote_content">
                  Title: <input type="text" name="post_titles[<?php echo $post_id; ?>]" value="<?php echo htmlentities( $newsletter->details->post_titles->{$post_id} ); ?>" class="title form-control">
                  Blurb: <textarea name="post_excerpts[<?php echo $post_id; ?>]" class="excerpt form-control"><?php echo htmlentities( $newsletter->details->post_excerpts->{$post_id} ); ?></textarea>
                  Image: <input type="text" name="post_images[<?php echo $post_id; ?>]" value="<?php echo $newsletter->details->post_images->{$post_id}; ?>" class="image form-control">
                </div>
              </td>
              <td width="25"><label class="remove remove-campaign-post" data-id="<?php echo $post_id; ?>">x</label></td>
            </tr>
            <?php
            $i++;
              endforeach;
            endif;
            ?>
          </table>

          <a href="#" class="add-post-blank" style="float: right;">Add blank</a>

          <hr>

        </div>
      </div>
  </div>

  <?php // submit_button(); ?>

  <div>
    <div class="submit">
      <span id="mc-errors" class="hide error" style="color: #ff0000; display: block; padding: 10px 0;"></span>
      <input type="button" name="submit" id="submit-campaign" class="button button-primary" value="Save">
      <span class="status"></span>
    </div>
  </div>
</form>
