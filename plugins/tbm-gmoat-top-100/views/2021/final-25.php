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
$db_movies = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmoat_final_2021 ORDER BY position DESC");
$movies = [];
if ($db_movies) {
    foreach ($db_movies as $movie) {
        $movies[$movie->position] = $movie;
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
// echo '<pre>'; print_r( $movies ); exit;
?>
<div>
    <div class="d-flex justify-content-between align-items-center">
        <h1>GMOAT Final 25 (2021)</h1>

        <nav style="position: fixed; top: 40px; right: 0; z-index: 30001;">
            <ul class="pagination">
                <?php for ($counter = 25; $counter >= 5; $counter -= 5) : ?>
                    <li class="page-item">
                        <a class="page-link" href="#row-<?php echo $counter; ?>"><?php echo $counter; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <?php for ($i = 25; $i >= 1; $i--) : ?>
        <div id="row-<?php echo $i; ?>">
            <form action="#" method="post" id="form-final-movie-<?php echo $i; ?>" class="form-final-movie-2021">
                <div class="row pb-3" style="background-color: #<?php echo $i % 2 ? 'ddd' : 'fff'; ?>;">
                    <div class="col-lg-6 mt-3">
                        <div class="d-inline p-2 bg-dark text-white rounded"><?php echo $i; ?></div>
                        <input type="hidden" name="position" value="<?php echo $i; ?>">

                        <div class="mt-2">
                            <input type="hidden" name="image_url" class="image_url form-control" value="<?php echo isset($movies[$i]) && !is_null($movies[$i]->image_url) && $movies[$i]->image_url != '' ? $movies[$i]->image_url  : ''; ?>">

                            <?php if (isset($movies[$i]) && !is_null($movies[$i]->image_url) && $movies[$i]->image_url != '') : ?>
                                <img src="<?php echo $movies[$i]->image_url; ?>" width="100" class="img-fluid d-block movie-image">
                            <?php endif; ?>
                            <button type="button" class="btn btn-info btn-sm btn-featured-album-image mt-1">Upload / Select from Library</button>
                        </div>

                        <div class="mt-3">
                            Title
                            <input type="text" name="title" value="<?php echo isset($movies[$i]) ? stripslashes($movies[$i]->title) : ''; ?>" class="form-control">
                        </div>
                        <div class="mt-3">
                            Purchase Link
                            <input type="text" name="link_purchase" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_purchase : ''; ?>" class="form-control" placeholder="https://">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-save">Save</button>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <?php
                        wp_editor(isset($movies[$i]) ? stripslashes($movies[$i]->description) : '', 'description' . $i, $wpeditor_settings);
                        ?>
                    </div>
            </form>
        </div>

    <?php endfor; ?>
</div>

<script>
    jQuery(document).ready(function($) {

        $('.btn-featured-album-image').click(function(e) {
            e.preventDefault();

            var theButton = $(this);

            var custom_uploader = wp.media({
                    title: 'Select Image for Movie',
                    button: {
                        text: 'Select'
                    },
                    multiple: false // Set this to true to allow multiple files to be selected
                })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    if (theButton.parent().find('.movie-image').length)
                        theButton.parent().find('.movie-image').detach();
                    theButton.parent().find('.image_url').val(attachment.url);
                    theButton.before('<img src="' + attachment.sizes.thumbnail.url + '" width="100" class="img-fluid d-block movie-image">');
                })
                .open();
        });

        $(document).on('submit', '.form-final-movie-2021', function(e) {
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
                    action: 'save_final_movie_2021',
                    formData: formData
                },
                success: function(res, textStatus, jqXHR) {
                    // console.log( res );
                    if (!res.success) {
                        theForm.append('<div class="alert alert-danger mt-3">' + res.data + '</div>');
                    } else {
                        theForm.append('<div class="alert alert-success mt-3">Saved!</div>');
                        setTimeout(function() {
                            theForm.find('.alert-success').fadeOut().detach;
                        }, 2000);
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