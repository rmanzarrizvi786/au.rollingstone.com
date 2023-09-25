jQuery(document).ready(function($) {
    var campaign_posts = [];
    var total_posts = 0;
    var max_post_id = 0;
    if ( $('.campaign-posts').length ) {
      total_posts = $('.campaign-posts').length;
      $('.campaign-posts').each(function() {
        var value = parseFloat($(this).data('id'));
        max_post_id = (value > max_post_id) ? value : max_post_id;
      });
    }
    $('#total-posts').find('.total').text(total_posts);

    // Prevent Submit form
    $('.create-campaign').on('submit', function(e) {
        e.preventDefault();
    });
    // Save Campaign
    $('.create-campaign #submit-campaign').on('click', function() {
        $(this).attr('disabled', true).parent().find('.status').html(' Saving...');
        var data = $('.create-campaign').serialize();
        $('#mc-errors').addClass('hide').html('');
        $.post(
          edm.ajaxurl,
          {
            action: 'save_newsletter',
            data: data
          },
          function(response){
            res = $.parseJSON(response);
            if (res.success) {
              window.location = '?page=rsau-mailchimp';
              $('.create-campaign #submit-campaign').attr('disabled', false).parent().find('.status').html(' Saved.');
            } else if (res.errors) {
              var errors = '';
              $.each(res.errors, function(index, error) {
                  errors += error + "\n";
              });
              $('.create-campaign #submit-campaign').parent().find('#mc-errors').removeClass('hide').html(errors);
              $('.create-campaign #submit-campaign').attr('disabled', false).parent().find('.status').html(' Error Saving, please check the errors and try again.');
            }
          }
        );
    });

    // AJAX Search for Campaign Section 1 posts
    /*
    $('.create-campaign #add-post').autoComplete({
        source: function(name, response) {
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: edm.ajaxurl,
            data: 'action=search_posts&type=any&term=' + name + '&after=-2 weeks',
            success: function(data) {
              response(data);
            }
          });
        },
        renderItem: function (item, search){
          search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
          var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
          return '<div class="autocomplete-suggestion" ' +
            'data-id="' + item[0] + '" ' +
            'data-title="' + item[1].replace(/&/g,'&amp;').replace(/>/g,'&gt;').replace(/</g,'&lt;').replace(/"/g,'&quot;') + '" ' +
            'data-link="' + item[2] + '" ' +
            'data-date="' + item[3] + '" ' +
            'data-excerpt="' + item[6].replace(/&/g,'&amp;').replace(/>/g,'&gt;').replace(/</g,'&lt;').replace(/"/g,'&quot;') + '" ' +
            'data-thumbnail="' + item[5] + '"' +
            'data-val="' + search + '">' +
            item[1].replace(re, "<b>$1</b>") +
            '</div>';
        },
        onSelect: function(e, term, item){
          var post_id = parseInt( item.data('id') );
          $('.create-campaign #add-post').next('div.error').text('').addClass('hide');
          campaign_posts.push( post_id );
          var post_title = item.data('title');
          var post_date = item.data('date');
          var post_excerpt = item.data('excerpt');
          var post_thumbnail = item.data('thumbnail');
          if ( post_thumbnail == 'null' || post_thumbnail == null )
              post_thumbnail = '';
          var post_link = item.data('link');
          total_posts++;
          max_post_id++;
          $('#campaign-posts').append(
            '<tr id="campaign-post-' + max_post_id + '">' +
            '<td>' +
            '<input type="number" maxlength="2" min="1" class="campaign-posts" name="posts[' + max_post_id + ']" value="' + total_posts + '" size="2"><br>' +
            '<a href="' + post_link + '" target="_blank"><img src="' + post_thumbnail + '" width="50"></a>' +
            '</td>' +
            '<td>' +
            '<label>Link:<input type="text" name="post_links[' + max_post_id + ']" value="' + post_link + '"></label>' +
            '<label>Title:<input type="text" name="post_titles[' + max_post_id + ']" value="' + post_title + '"></label>' +
            '<label>Blurb:<br><textarea name="post_excerpts[' + max_post_id + ']">' + post_excerpt + '</textarea></label>' +
            '<label>Image:<br><input type="text" name="post_images[' + max_post_id + ']" value="' + post_thumbnail + '"></label>' +
            '</td>' +
            '<td><label class="remove remove-campaign-post" data-id="' + max_post_id + '">x</label></td>' +
            '</tr>'
          );
          $('#total-posts').find('.total').text(total_posts);
          $('.create-campaign #add-post').val('');
        },
        minChars: 1
    });
    */
    $('.add-post-blank').on('click', function(e) {
        e.preventDefault();
        total_posts++;
        max_post_id++;
        $('#campaign-posts').append(
          '<tr id="campaign-post-' + max_post_id + '">' +
          '<td width="50">' +
          '<input type="number" maxlength="2" min="1" class="campaign-posts" name="posts[' + max_post_id + ']" value="' + total_posts + '" size="2" style="width: 50px;"><br>' +
          '<td>' +
          'Link:<input type="text" name="post_links[' + max_post_id + ']" id="post_' + max_post_id + '" value="" class="link_remote form-control">' +
          '<div class="hide remote_content">' +
          'Title:<input type="text" name="post_titles[' + max_post_id + ']" value="" class="title form-control">' +
          'Blurb:<br><textarea name="post_excerpts[' + max_post_id + ']" class="excerpt form-control"></textarea>' +
          'Image:<br><input type="text" name="post_images[' + max_post_id + ']" value="" class="image form-control">' +
          '</div>' +
          '</td>' +
          '<td><label class="remove remove-campaign-post" data-id="' + max_post_id + '">x</label></td>' +
          '</tr>'
        );
        $('#post_' + max_post_id).focus();
        $('#total-posts').find('.total').text(total_posts);
    });
    $(document).on('click', '.remove-campaign-post' , function() {
        var post_id = parseInt( $(this).data('id') );
        campaign_posts = jQuery.grep(campaign_posts, function(value) {
            return value !== post_id;
        });
        $('#campaign-post-' + post_id).detach();
        total_posts--;
        $('#total-posts').find('.total').text(total_posts);
    });

    // AJAX Search for Campaign Cover Story
    /*
    $('.create-campaign #add-cover-story').autoComplete({
      source: function(name, response) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: edm.ajaxurl,
          data: 'action=search_posts&type=any&term=' + name + '&after=-2 weeks',
          success: function(data) {
            response(data);
          }
        });
      },
      renderItem: function (item, search){
        search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
        return '<div class="autocomplete-suggestion" ' +
          'data-id="' + item[0] + '" ' +
          'data-title="' + item[1].replace(/&/g,'&amp;').replace(/>/g,'&gt;').replace(/</g,'&lt;').replace(/"/g,'&quot;') + '" ' +
          'data-link="' + item[2] + '" ' +
          'data-date="' + item[3] + '" ' +
          'data-excerpt="' + item[6].replace(/&/g,'&amp;').replace(/>/g,'&gt;').replace(/</g,'&lt;').replace(/"/g,'&quot;') + '" ' +
          'data-thumbnail="' + item[5] + '" ' +
          'data-val="' + search + '">' +
          item[1].replace(re, "<b>$1</b>") +
          '</div>';
      },
      onSelect: function(e, term, item){
        if ( $('.remove-cover-story').length) {
          alert ('You can add only one Cover Story, please remove the other one first.');
          return false;
        }
        var post_id = parseInt( item.data('id') );
        campaign_posts.push( post_id );
        var post_title = item.data('title');
        var post_date = item.data('date');
        var post_excerpt = item.data('excerpt');
        var post_thumbnail = item.data('thumbnail');
        var post_link = item.data('link');
        $('#campaign-cover-story').append(
          '<tr id="cover_story_wrap">' +
          '<td width="50"><a href="' + post_link + '" target="_blank"><img src="' + post_thumbnail + '" width="50"></a></td>' +
          '<td>' +
          '<label>Link:<input type="text" name="cover_story_link" value="' + post_link + '" class="link_remote"></label>' +
          '<div class="remote_content">' +
          '<label>Title:<input type="text" name="cover_story_title" value="' + post_title + '" class="title"></label><br>' +
          '<label>Blurb:<br><textarea name="cover_story_excerpt" class="excerpt">' + post_excerpt + '</textarea></label><br>' +
          '<label>Image:<br><input type="text" name="cover_story_image" value="' + post_thumbnail + '" class="image"></label>' +
          '</div>' +
          '</td>' +
          '<td width="25"><label class="remove remove-cover-story" data-id="cover_story_wrap">x</label></td>' +
          '</tr>'
          );
          $('.create-campaign #add-cover-story').val('');
        },
        minChars: 1
      });
      */
      $('.add-cover-story-blank').on('click', function(e) {
        e.preventDefault();
        if ( $('.remove-cover-story').length) {
            alert ('You can add only one Cover Story, please remove the other one first.');
            return false;
        }
        $('#campaign-cover-story').append(
            '<tr id="cover_story_wrap">' +
            '<td width="50">&nbsp;</td>' +
            '<td>' +
            'Link:<br><input type="text" name="cover_story_link" id="cover_story_link" value="" class="link_remote form-control">' +
            '<div class="hide remote_content">' +
            'Title:<br><input type="text" name="cover_story_title" value="" class="title form-control">' +
            'Blurb:<br><textarea name="cover_story_excerpt" class="excerpt form-control"></textarea>' +
            'Image:<br><input type="text" name="cover_story_image" value="" class="image form-control">' +
            '</div>' +
            '</td>' +
            '<td width="25"><label class="remove remove-cover-story" data-id="cover_story_wrap">x</label></td>' +
            '</tr>'
            );
        $('#cover_story_link').focus();
    });

    $(document).on('click', '.remove-cover-story, .remove-featured-story-1, .remove-featured-story-2, .remove-featured-video' , function() {
        $( '#' + $(this).data('id') ).detach();
    });

    $(document).on('click', '.remove-all-posts', function() {
        var section = $(this).data('id');
        $('#' + section).find('.campaign-post').detach();
    });

    if ( $('.datepicker').length ) {
        $('.datepicker').datepicker( { dateFormat: 'dd M yy' } );
    }

    $(document).on('paste', '.link_remote', function(e) {
      var element = this;
      $(element).parent().append('<div id="wait_msg" style="padding: 5px 10px; background: #333; color: #fff;">Please wait...</div>');
      $(element).parent().find('.remote_content').addClass('hide');
      $(element).parent().find('.remote_content').find('.title, .excerpt, .image').val('');
      setTimeout(function () {
        var data = 'url=' + $(element).val();
        $.post(
          edm.ajaxurl,
          {
            action: 'get_remote_data',
            data: data
          },
          function(response){
            res = $.parseJSON(response);
            if (res.success) {
              $(element).parent().find('.remote_content').find('.title').val( res.title );
              $(element).parent().find('.remote_content').find('.excerpt').val( res.description );
              $(element).parent().find('.remote_content').find('.image').val( res.image );
              $(element).parent().parent().find('td:first').find('img').remove();
              $(element).parent().parent().find('td:first').append('<img src="' + res.image + '" width="50">');
            }
            $(element).parent().find('.remote_content').removeClass('hide');
            $('#wait_msg').detach();
          }
        );
      }, 100);
    });

    $(document).on('click', '.delete', function(e) {
      e.preventDefault();
      return confirm( 'Are you sure?' );
    });

    // Save Campaign
    $('#save-edm-settings').on('click', function() {
        // $(this).attr('disabled', true).parent().find('.status').html(' Saving...');
        var data = $('.create-campaign').serialize();
        $('#td-mc-errors').addClass('hide').html('');
        $.post(
            ajaxurl,
            {
                action: 'save_edm_settings',
                data: data
            },
            function(response){
                res = $.parseJSON(response);
                console.log( res );
                if (res.success) {
//                    window.location = '?page=brag-mailchimp/brag-mailchimp.php';
                    $('#save-edm-settings').attr('disabled', false).parent().find('.status').html(' Saved.');
                } else if (res.errors) {
                    var errors = '';
                    $.each(res.errors, function(index, error) {
                        errors += error + "<br>";
                    });
                    $('#td-mc-errors').removeClass('hide').html(errors);
                    $('#save-edm-settings').attr('disabled', false).parent().find('.status').html(' Error Saving, please check the errors and try again.');
                }
            }
        );
    });
} );
