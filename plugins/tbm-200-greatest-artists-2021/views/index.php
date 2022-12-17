<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<?php
if (function_exists('wp_enqueue_media')) {
  wp_enqueue_media();
} else {
  wp_enqueue_style('thickbox');
  wp_enqueue_script('media-upload');
  wp_enqueue_script('thickbox');
}
global $wpdb;

$paged = isset($_GET['p']) ? absint($_GET['p']) : 1;

$from = $this->total_entries - (($paged - 1) * $per_page);
$to = $from - $per_page;

$query = "SELECT *
FROM {$wpdb->prefix}200_greatest_artists_2021
WHERE `position` >= {$to} AND `position` <= {$from}
ORDER BY position DESC
-- LIMIT {$from}, {$per_page}
";
// echo $query;
$db_artists = $wpdb->get_results($query);
$artists = [];
if ($db_artists) {
  foreach ($db_artists as $artist) {
    $artists[$artist->position] = $artist;
  }
}

$wpeditor_settings = array(
  // 'teeny' => true,
  'textarea_rows' => 10,
  'tabindex' => 1,
  'tinymce'       => array(
    'toolbar1' => 'formatselect,bold,italic,underline,separator,bullist,numlist,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo,removeformat',
    'toolbar2'      => '',
    'toolbar3'      => '',
  ),
  'media_buttons' => FALSE,
  'textarea_name' => 'description',
);
// echo '<pre>'; print_r( $artists ); exit;

$total_pages = ceil($this->total_entries / $per_page);
?>
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h1>200 Greatest Artists</h1>
  </div>
  <nav>
    <ul class="pagination sm">
      <?php
      for ($counter = 1; $counter <= $total_pages; $counter++) :
      ?>
        <li class="page-item">
          <a class="page-link <?php echo $paged === $counter ? 'bg-primary text-white' : ''; ?>" href="<?php echo add_query_arg(['p' => $counter]); ?>">
            <?php
            $pagination_from = $this->total_entries - (($counter - 1) * $per_page);
            $pagination_to = $pagination_from - $per_page + 1;
            echo $pagination_from;
            echo '-';
            echo $pagination_to >= 1 ? $pagination_to : 1;
            ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>

  <table class="table table-striped">
    <tbody>
      <?php
      for ($i = $from; $i > $to; $i--) :
        if ($i < 1)
          break;
      ?>

        <tr id="row-<?php echo $i; ?>">
          <td colspan="10">
            <form action="#" method="post" id="form-final-artist-<?php echo $i; ?>" class="form-final-artist">
              <div class="row">
                <div class="col-lg-2 mt-3">
                  <div><?php echo $i; ?></div>
                  <input type="hidden" name="position" value="<?php echo $i; ?>">

                  <input type="hidden" name="image_url" class="image_url form-control" value="<?php echo isset($artists[$i]->image_url) && !is_null($artists[$i]->image_url) && $artists[$i]->image_url != '' ? $artists[$i]->image_url  : ''; ?>">

                  <?php if (isset($artists[$i]->image_url) && !is_null($artists[$i]->image_url) && $artists[$i]->image_url != '') : ?>
                    <img src="<?php echo $artists[$i]->image_url; ?>" width="100" class="img-fluid d-block artist-image" style="width: 100%;">
                  <?php endif; ?>
                  <button type="button" class="btn btn-info btn-sm btn-featured-album-image">Upload / Select from Library</button>

                  Image credit (optional)
                  <input type="text" name="image_credit" value="<?php echo isset($artists[$i]) ? stripslashes($artists[$i]->image_credit) : ''; ?>" class="form-control">
                </div>
                <div class="col-lg-7 mt-3">
                  <div>
                    Title
                    <input type="text" name="title" value="<?php echo isset($artists[$i]) ? stripslashes($artists[$i]->title) : ''; ?>" class="form-control">
                  </div>
                  <div class="mt-2">
                    Subheading
                    <input type="text" name="subheading" value="<?php echo isset($artists[$i]) ? stripslashes($artists[$i]->subheading) : ''; ?>" class="form-control">
                  </div>
                  <div class="mt-2">
                    Description
                    <?php
                    wp_editor(isset($artists[$i]) ? stripslashes($artists[$i]->description) : '', 'description' . $i, $wpeditor_settings);
                    ?>
                  </div>
                </div>
                <div class="col-lg-3 mt-3">
                  <div>
                    Author name
                    <input type="text" name="author" value="<?php echo isset($artists[$i]) ? stripslashes($artists[$i]->author) : ''; ?>" class="form-control">
                  </div>
                  <div class="mt-2">
                    Author bio (optional)
                    <input type="text" name="author_bio" value="<?php echo isset($artists[$i]) ? stripslashes($artists[$i]->author_bio) : ''; ?>" class="form-control">
                  </div>
                  <div class="mt-2">
                    Author image
                    <input type="hidden" name="author_image_url" class="author_image_url form-control" value="<?php echo isset($artists[$i]->author_image_url) && !is_null($artists[$i]->author_image_url) && $artists[$i]->author_image_url != '' ? $artists[$i]->author_image_url  : ''; ?>">

                    <?php if (isset($artists[$i]->author_image_url) && !is_null($artists[$i]->author_image_url) && $artists[$i]->author_image_url != '') : ?>
                      <img src="<?php echo $artists[$i]->author_image_url; ?>" width="100" class="img-fluid d-block author-image">
                    <?php endif; ?>
                    <button type="button" class="btn btn-info btn-sm btn-author-image">Upload / Select from Library</button>
                  </div>

                  <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-save">Save</button>
                  </div>
                </div>
            </form>
          </td>
        </tr>

      <?php endfor; ?>
    </tbody>
  </table>
</div>

<script>
  jQuery(document).ready(function($) {

    $('.btn-featured-album-image').click(function(e) {
      e.preventDefault();

      var theButton = $(this);

      var custom_uploader = wp.media({
          title: 'Select Image for Artist',
          button: {
            text: 'Select'
          },
          multiple: false // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
          var attachment = custom_uploader.state().get('selection').first().toJSON();
          if (theButton.parent().find('.artist-image').length)
            theButton.parent().find('.artist-image').detach();
          theButton.parent().find('.image_url').val(attachment.url);
          theButton.before('<img src="' + attachment.sizes.thumbnail.url + '" width="100" class="img-fluid d-block artist-image">');
        })
        .open();
    });

    $('.btn-author-image').click(function(e) {
      e.preventDefault();

      var theButton = $(this);

      var custom_uploader = wp.media({
          title: 'Select Image for Author',
          button: {
            text: 'Select'
          },
          multiple: false // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
          var attachment = custom_uploader.state().get('selection').first().toJSON();
          if (theButton.parent().find('.author-image').length)
            theButton.parent().find('.author-image').detach();
          theButton.parent().find('.author_image_url').val(attachment.url);
          theButton.before('<img src="' + attachment.sizes.thumbnail.url + '" width="100" class="img-fluid d-block author-image">');
        })
        .open();
    });

    $(document).on('submit', '.form-final-artist', function(e) {
      e.preventDefault();
      var theForm = $(this);
      var theButton = $(this).find('button.btn-save');
      theForm.find('.alert').detach();

      theButton.prop('disabled', true);

      formData = theForm.serialize();

      $.ajax({
        type: 'POST',
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        data: {
          action: 'save_artist',
          formData: formData
        },
        success: function(res, textStatus, jqXHR) {
          // console.log( res );
          if (!res.success) {
            theForm.append('<div class="alert alert-danger">' + res.data + '</div>');
          } else {
            theForm.append('<div class="alert alert-success">Saved!</div>');
            setTimeout(function() {
              theForm.find('.alert-success').fadeOut().detach;
            }, 1000);
          }
        },
        error: function(res, textStatus, jqXHR) {
          console.error(res);
        },
        complete: function(jqXHR, textStatus) {
          theButton.prop('disabled', false);
        }
      })
    });
  });
</script>