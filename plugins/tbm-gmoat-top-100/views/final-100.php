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
$db_movies = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmoat_final ORDER BY position DESC");
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
        <h1>GMOAT Final 100</h1>

        <nav style="position: fixed; top: 40px; right: 0; z-index: 30001;">
            <ul class="pagination">
                <?php for ($counter = 100; $counter >= 10; $counter -= 10) : ?>
                    <li class="page-item">
                        <a class="page-link" href="#row-<?php echo $counter; ?>"><?php echo $counter; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Order</th>
                <th>Image</th>
                <th>Movie Title / Description</th>
                <th>Buy now links</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 100; $i >= 1; $i--) : ?>

                <tr id="row-<?php echo $i; ?>">
                    <td colspan="10">
                        <form action="#" method="post" id="form-final-movie-<?php echo $i; ?>" class="form-final-movie">
                            <div class="row">
                                <div class="col-lg-1 mt-3">
                                    <div><?php echo $i; ?></div>
                                    <input type="hidden" name="position" value="<?php echo $i; ?>">

                                    <input type="hidden" name="image_url" class="image_url form-control" value="<?php echo !is_null($movies[$i]->image_url) && $movies[$i]->image_url != '' ? $movies[$i]->image_url  : ''; ?>">

                                    <?php if (!is_null($movies[$i]->image_url) && $movies[$i]->image_url != '') : ?>
                                        <img src="<?php echo $movies[$i]->image_url; ?>" width="100" class="img-fluid d-block movie-image">
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-primary btn-sm btn-featured-album-image">Upload / Select from Library</button>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div>
                                        Title
                                        <input type="text" name="title" value="<?php echo isset($movies[$i]) ? stripslashes($movies[$i]->title) : ''; ?>" class="form-control">
                                    </div>
                                    <div>
                                        Description
                                        <?php
                                        wp_editor(isset($movies[$i]) ? stripslashes($movies[$i]->description) : '', 'description' . $i, $wpeditor_settings);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-3">
                                    <div>
                                        <strong>JB Hi-Fi</strong><br>
                                        <table class="table table-sm">
                                            <tr>
                                                <td>Blu ray</td>
                                                <td><input type="text" name="link_jbhifi_bluray" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_jbhifi_bluray : ''; ?>" class="form-control" placeholder="Blu ray"></td>
                                            </tr>
                                            <tr>
                                                <td>DVD</td>
                                                <td><input type="text" name="link_jbhifi_dvd" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_jbhifi_dvd : ''; ?>" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>4K</td>
                                                <td><input type="text" name="link_jbhifi_4k" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_jbhifi_4k : ''; ?>" class="form-control"></td>
                                            </tr>
                                        </table>
                                        <div class="mt-2">
                                            <strong>Amazon</strong><br>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Blu ray</td>
                                                    <td> <input type="text" name="link_amazon_bluray" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_amazon_bluray : ''; ?>" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>DVD</td>
                                                    <td><input type="text" name="link_amazon_dvd" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_amazon_dvd : ''; ?>" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>4K</td>
                                                    <td><input type="text" name="link_amazon_4k" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_amazon_4k : ''; ?>" class="form-control"></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            <strong>Sanity</strong><br>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Blu ray</td>
                                                    <td> <input type="text" name="link_sanity_bluray" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_sanity_bluray : ''; ?>" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>DVD</td>
                                                    <td><input type="text" name="link_sanity_dvd" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_sanity_dvd : ''; ?>" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>4K</td>
                                                    <td><input type="text" name="link_sanity_4k" value="<?php echo isset($movies[$i]) ? $movies[$i]->link_sanity_4k : ''; ?>" class="form-control"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 mt-3">
                                        <button type="submit" class="btn btn-primary btn-save">Save</button>
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

        $(document).on('submit', '.form-final-movie', function(e) {
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
                    action: 'save_final_movie',
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