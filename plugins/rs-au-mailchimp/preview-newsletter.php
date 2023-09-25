<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
$table = $wpdb->prefix . "edms";

if ( ! isset( $action ) ) :
  $action = 'preview';
endif;

$newsletter = $wpdb->get_row( "SELECT * FROM $table WHERE id = {$id}" );
if( is_null( $newsletter ) ):
    die( 'Newsletter not found.' );
endif;
$newsletter->details = json_decode( $newsletter->details );
$exclude_posts = array();
$posts = get_object_vars( $newsletter->details->posts );
$post_ids = array_keys ( $posts );

$post_links = get_object_vars( $newsletter->details->post_links );
$promoter_article_links = array();
$ad_links = array();
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<!-- NAME: 1:3:2 COLUMN -->
		<!--[if gte mso 15]>
        <xml>
            <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>*|MC:SUBJECT|*</title>

    <style type="text/css">
		/*body {margin: 0; padding: 0; min-width: 100%!important;}*/

		/* --------------------------------------------
			 FOR DESKTOP DEVICES.
		-------------------------------------------- */
		img {
			max-width: 100%;
		}

    .center {
      display: block !important;
      max-width: 700px !important;
      min-width: 640px !important;
      margin: 0 auto !important;
      clear: both !important;
      padding-top: 5px;
      box-sizing: border-box;
    }

		/* --------------------------------------------
			 FOR MOBILE DEVICES,
			 MAKE THE MAIN TEXT 25% LARGER
		-------------------------------------------- */
		@media only screen and (max-width: 728px) {

			.center {
				max-width: 100% !important;
				min-width: 375px !important;
				padding-left: 10px;
				padding-right: 10px;
        padding-top: 10px;
			}

			.small-12 {
				width: 100% !important;
				display: inline-block !important;
				padding: 0 !important;
			}
      .small-12.ad {
        padding-top: 20px !important;
        padding-bottom: 20px !important;
      }

      .feature-h1 {
        font-size: 28px !important;
        line-height: 36px !important;
      }
      .feature-h1-centered {
        font-size: 28px !important;
        line-height: 36px !important;
      }
      .headline {
        font-size: 28px !important;
        line-height: 36px !important;
      }
      .h5-3col {
        font-size: 28px !important;
        line-height: 36px !important;
      }
      .cta {
        font-size: 20px !important;
        line-height: 24px !important;
        padding-bottom: 10px !important;
      }
      .deck {
        font-size: 22px !important;
        line-height: 28px !important;
      }
      .tag {
        font-size: 15px !important;
        line-height: 20px !important;
      }
		}
	</style>
  <style>
    @media only screen {
      html {
        min-height: 100%;
        background: #f3f3f3
      }
    }

    @media only screen and (max-width:730px) {
      table.body img {
        width: auto;
        height: auto
      }
      table.body center {
        min-width: 0 !important
      }
      table.body .container {
        width: 95% !important
      }
      table.body .columns {
        height: auto !important;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      th.small-3 {
        display: inline-block !important;
        width: 25% !important
      }
      th.small-6 {
        display: inline-block !important;
        width: 50% !important
      }
      th.small-12 {
        display: inline-block !important;
        width: 100% !important
      }
    }

    @media only screen and (max-width:730px) {
      table.body .columns.first {
        padding-bottom: 5px !important
      }
      table.body .columns.first.ad {
        padding-bottom: 20px !important
      }
    }

    @media only screen and (max-width:730px) {
      table.header.header--stacked h1 {
        font-size: 30px !important
      }
    }

    @media only screen and (max-width:730px) {
      h1::not('.header-title') {
        font-size: 22px !important
      }
      h2 {
        font-size: 18px !important
      }
      table.body .large-12 h1,
      table.body .large-6 h2 {
        margin-top: 5px !important
      }
    }
  </style>

  <style>
  .feature-h1{
    color:#000 !important;
    font-family:Helvetica,Arial,sans-serif;
    font-size:25px;
    font-weight:700;
    line-height:28.4px;
  }
  .feature-h1-centered{
    color:#000 !important;
    font-family:Helvetica,Arial,sans-serif;
    font-size:25px;
    font-weight:700;
    line-height:28.4px;
  }
  .headline{
    color:#000 !important;
    font-family:Helvetica,Arial,sans-serif;
    font-size:23px;
    font-weight:700;
    line-height:28px;
  }
  .h5-3col {
    color:#000 !important;
    font-family:Helvetica,Arial,sans-serif;
    font-size:14px;
    font-weight:700;
    line-height:16.8px;
  }
  .tag {
    color:#000 !important;
    font-family:Helvetica,Arial,sans-serif;
    font-size:11px;
    letter-spacing:1px;
    line-height:15.4px;
  }
  .cta{
    color:#d32531 !important;
    font-family:Helvetica,Arial,sans-serif;
    font-size:9px;
    font-weight:400;
    line-height:18.2px;
  }
  </style>

    </head>
    <body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background-color:#f5f5f5;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;min-width:100%;padding:0;text-align:center;width:100%!important" >
      <!--*|IF:MC_PREVIEW_TEXT|*-->
  <!--[if !gte mso 9]><!----><span class="mcnPreview Text" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">*|MC_PREVIEW_TEXT|*</span><!--<![endif]-->
  <!--*|END:IF|*-->
  <table class="body" style="Margin:0;background:#f3f3f3;background-color:#f5f5f5;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;height:100%;line-height:1.4;margin:0;padding:0;text-align:center;vertical-align:top;width:100%" >
    <tr style="padding:0;text-align:center;vertical-align:top" >
      <td class="center" align="center" valign="top" style="-moz-hyphens:none;-webkit-hyphens:none;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;hyphens:none;line-height:1.4;margin:0;padding:0;text-align:center;vertical-align:top;word-wrap:break-word" >
        <center mcdisabled-data-parsed="" style="min-width:700px;width:100%" >
          <!-- Max Width for Outlook Fix -->
          <!--[if mso]>
                    <table height="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0px;margin:0px;">
                      <tr><td colspan="3" style="padding:0px;margin:0px;font-size:20px;height:20px;" height="20">&nbsp;</td></tr>
                      <tr>
                        <td style="padding:0px;margin:0px;">&nbsp;</td>
                        <td style="padding:0px;margin:0px;" width="700">
            <![endif]-->
            <table align="center" class="container float-center" style="Margin:0 auto;background:0 0;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:700px;max-width:700px" >
              <tbody>
                <tr style="padding:0;text-align:center;vertical-align:top" >
                  <td style="-moz-hyphens:none;-webkit-hyphens:none;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;hyphens:none;line-height:1.4;margin:0;padding:0;padding-top:0px;text-align:center;vertical-align:top;word-wrap:break-word" >
                    <!-- Header Stacked -->
                    <table class="row header-meta" style="background:0 0;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%; background-color: #ffffff;" >
                      <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top" >
                          <th class="small-6 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:10px;padding-left:0px;padding-right:15px;padding-top:10px;text-align:left;width:320px" >
                            <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%;" >
                              <tr style="padding:0;text-align:left;vertical-align:top" >
                                <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0 0 0 10px;text-align:left" >
                                  <p class="text-left" style="Margin:0;Margin-bottom:10px;color:#555;font-family:Georgia,serif;font-size:12px;font-weight:400;line-height:24px;margin:0;margin-bottom:10px;padding:0;text-align:left" ><a href="*|ARCHIVE|*" style="Margin:0;color:#3c43b2!important;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:16px;margin:0;padding:0;text-align:left;text-decoration:none" >View on web</a></p>
                                </th>
                              </tr>
                            </table>
                          </th>
                          <th class="small-6 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:10px;padding-left:15px;padding-right:0px;padding-top:10px;text-align:center;width:320px" >
                            <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                              <tr style="padding:0;text-align:center;vertical-align:top" >
                                <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0 10px 0 0;text-align:center" >
                                  <p class="text-right" style="Margin:0;Margin-bottom:10px;color:#555;font-family:Georgia,serif;font-size:12px;font-weight:400;line-height:24px;margin:0;margin-bottom:10px;padding:0;text-align:right" ><a href="<?php echo home_url( 'subscribe' ); ?>" target="_blank" style="Margin:0;color:#3c43b2!important;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:16px;margin:0;padding:0;text-align:center;text-decoration:none" >New reader? Subscribe</a></p>
                                </th>
                              </tr>
                            </table>
                          </th>
                        </tr>
                      </tbody>
                    </table>
                    <table class="row header header--stacked header-bg" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                      <tbody>
                        <tr style="padding:0;text-align:center;vertical-align:top" >
                          <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;text-align:center;" >
                            <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                              <tr style="padding:0;text-align:center;vertical-align:top" >
                                <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" ><a href="<?php echo home_url(); ?>" target="_blank" style="Margin:0;color:#2D2CA2;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none" ><img class="logo-img" src="<?php echo get_template_directory_uri(); ?>/images/edm/header.jpg" mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;max-width:100%;outline:0;text-decoration:none;width:auto" ></a>
                                  <h1 class="header-stacked-title" style="Margin:0;Margin-bottom:5px;color:#000!important;font-family:Helvetica,Arial,sans-serif;font-size:40px;font-weight:700;line-height:38px;margin:0;margin-bottom:5px;margin-top:5px;padding:0;text-align:center;word-wrap:normal" ></h1>
                                    <h4 class="date" style="Margin:0;Margin-bottom:5px;color:#505050!important;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19.6px;margin:0;margin-bottom:5px;margin-top:10px;padding:0;text-align:center;text-transform:uppercase;word-wrap:normal" >*|DATE:F d, Y|*</h4></th>
                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0!important;text-align:center;visibility:hidden;width:0" ></th>
                              </tr>
                            </table>
                          </th>
                        </tr>
                      </tbody>
                    </table>
                  <!-- Feature Story -->
                  <table class="row" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story feature-img-text pad-bottom small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:670px" >
                          <?php
                          if ( isset( $newsletter->details->cover_story_image ) && $newsletter->details->cover_story_image != '' ) :
                              $post_links[] = $newsletter->details->cover_story_link;
                          ?>
                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                            <tr style="padding:0;text-align:center;vertical-align:top" >
                              <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" >
                                <a href="<?php echo $newsletter->details->cover_story_link; ?>" style="Margin:0;color:#2D2CA2;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none">
                                  <img width="660" height="370" src="<?php echo $newsletter->details->cover_story_image; ?>" mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;height:auto;margin-bottom:15px;max-width:100%;outline:0;text-decoration:none;width:100%">
                                </a>

                                <h1 style="Margin:0;Margin-bottom:5px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:22px;font-weight:400;line-height:1.4;margin:0;margin-bottom:5px;padding:0;text-align:center;word-wrap:normal">
                                  <a class="feature-h1" href="<?php echo $newsletter->details->cover_story_link; ?>" title="<?php echo $newsletter->details->cover_story_title; ?>" style="Margin:0;color:#000!important;font-family:Helvetica,Arial,sans-serif;font-size:25px;font-weight:700;line-height:28.4px;margin:0;padding:0;text-align:center;text-decoration:none"><?php echo $newsletter->details->cover_story_title; ?></a>
                                </h1>

                                <p class="deck" style="Margin:0;Margin-bottom:10px;color:#686868!important;font-family:Georgia;font-size:16px;font-weight:400;line-height:24px;margin:0;margin-bottom:10px;padding:0;text-align:center"><?php echo $newsletter->details->cover_story_excerpt; ?></p>
                              </th>
                              <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0!important;text-align:center;visibility:hidden;width:0" ></th>
                            </tr>
                          </table>
                          <?php endif; // If Cover Story is set ?>
                        </th></tr>
                    </tbody>
                  </table>

                  <!-- Two Column side by side stories (with ad) -->
                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag pad-bottom small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:15px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[0] ); // First Article ?>
                        </th>
                        <th class="story story-tag centered small-12 large-6 columns last ad" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:15px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php if (
                            isset( $newsletter->details->ad_middle_1_image ) &&
                            $newsletter->details->ad_middle_1_image != '' &&
                            isset( $newsletter->details->ad_middle_1_link ) &&
                            $newsletter->details->ad_middle_1_link != ''
                            ):
                            array_push( $ad_links, $newsletter->details->ad_middle_1_link );
                            ?>
                          <table border="0" cellpadding="0" cellspacing="0" align="center" style="border:none!important;" >
                            <tr>
                              <td colspan="2" style="display:table-cell!important; width:100%;  text-align: center; " >
                                <a style="display: block;" href="<?php echo $newsletter->details->ad_middle_1_link; ?>" rel="nofollow" >
                                  <img src="<?php echo $newsletter->details->ad_middle_1_image; ?>" border="0" style="width:100%; max-width:300px;" align="center" >
                                </a>
                              </td>
                            </tr>
                          </table>
                          <?php endif; ?>
                        </th></tr>
                    </tbody>
                  </table>

                  <!-- Two Column side by side stories -->
                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[1] ); // Second Article ?>
                        </th>
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[2] ); // Third Article ?>
                        </th></tr>
                    </tbody>
                  </table>
                  <!-- Two Column side by side stories -->

                  <!-- Two Column side by side stories (with ad) -->
                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag centered small-12 large-6 columns first ad" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:15px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php if (
                            isset( $newsletter->details->ad_middle_2_image ) &&
                            $newsletter->details->ad_middle_2_image != '' &&
                            isset( $newsletter->details->ad_middle_2_link ) &&
                            $newsletter->details->ad_middle_2_link != ''
                            ):
                            array_push( $ad_links, $newsletter->details->ad_middle_2_link );
                            ?>
                          <table border="0" cellpadding="0" cellspacing="0" align="center" style="border:none!important;" >
                            <tr>
                              <td colspan="2" style="display:table-cell!important; width:100%;  text-align: center; " >
                                <a style="display: block;" href="<?php echo $newsletter->details->ad_middle_2_link; ?>" rel="nofollow" >
                                  <img src="<?php echo $newsletter->details->ad_middle_2_image; ?>" border="0" style="width:100%; max-width:300px;" align="center" >
                                </a>
                              </td>
                            </tr>
                          </table>
                          <?php endif; ?>
                        </th>
                        <th class="story story-tag pad-bottom small-12 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:15px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[3] ); // First Article ?>
                        </th>
                      </tr>
                    </tbody>
                  </table>

                  <!-- Two Column side by side stories -->
                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[4] ); // Second Article ?>
                        </th>
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[5] ); // Third Article ?>
                        </th></tr>
                    </tbody>
                  </table>
                  <!-- Two Column side by side stories -->

                  <!-- Two Column side by side stories (with ad) -->
                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag pad-bottom small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:15px;padding-top:20px;text-align:center;width:320px" >
                          <?php print_article( $newsletter, $post_ids[6] ); // First Article ?>
                        </th>
                        <th class="story story-tag centered small-12 large-6 columns last ad" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:15px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php if (
                            isset( $newsletter->details->ad_middle_3_image ) &&
                            $newsletter->details->ad_middle_3_image != '' &&
                            isset( $newsletter->details->ad_middle_3_link ) &&
                            $newsletter->details->ad_middle_3_link != ''
                            ):
                            array_push( $ad_links, $newsletter->details->ad_middle_3_link );
                            ?>
                          <table border="0" cellpadding="0" cellspacing="0" align="center" style="border:none!important;" >
                            <tr>
                              <td colspan="2" style="display:table-cell!important; width:100%;  text-align: center; " >
                                <a style="display: block;" href="<?php echo $newsletter->details->ad_middle_3_link; ?>" rel="nofollow" >
                                  <img src="<?php echo $newsletter->details->ad_middle_3_image; ?>" border="0" style="width:100%; max-width:300px;" align="center" >
                                </a>
                              </td>
                            </tr>
                          </table>
                          <?php endif; ?>
                        </th></tr>
                    </tbody>
                  </table>

                  <?php
                  if ( count ( $post_ids ) > 7 ) :
                    for( $i = 7; $i < count( $post_ids ); $i+=2 ):
                  ?>
                  <!-- Two Column side by side stories -->
                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php if ( isset( $post_ids[$i] ) ) print_article( $newsletter, $post_ids[$i] ); ?>
                        </th>
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                          <?php if ( isset( $post_ids[$i+1] ) ) print_article( $newsletter, $post_ids[$i+1] ); ?>
                        </th></tr>
                    </tbody>
                  </table>
                  <!-- Two Column side by side stories -->
                  <?php
                    endfor;
                  endif;
                  ?>

                  <?php
                  $trending_posts = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}tbm_trending ORDER BY created_at DESC LIMIT 5" );
                  $trending_args = array(
                      'post_status' => 'publish',
                      'posts_per_page' => 5,
                      'post__in' => wp_list_pluck( $trending_posts, 'post_id' ),
                  );
                  $trending_query = new WP_Query($trending_args);
                  if ( $trending_query->have_posts() ) :
                  ?>
                  <!-- Section Title -->
                  <table class="row section-title centered border" style="background:#fff;border-collapse:collapse;border-spacing:0;color:#0a0a0a!important;display:table;font-family:Helvetica,Arial,sans-serif;font-size:28px;font-weight:900;line-height:39.2px;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:0;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:670px" >
                          <table style="border-collapse:collapse;border-spacing:0;border-top:1px solid #000;padding:0;text-align:center;vertical-align:top;width:100%" >
                            <tr style="padding:0;text-align:center;vertical-align:top" >
                              <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;padding-bottom:0;padding-top:15px;text-align:center" >
                                <h1 class="section-title" style="Margin:0;Margin-bottom:5px;color:#0a0a0a!important;font-family:Helvetica,Arial,sans-serif;font-size:28px;font-weight:700;line-height:39.2px;margin:0;margin-bottom:0;padding:0;text-align:center;word-wrap:normal" >Trending Now</h1></th>
                              <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0!important;padding-bottom:0;padding-top:15px;text-align:center;visibility:hidden;width:0" ></th>
                            </tr>
                          </table>
                        </th>
                      </tr>
                    </tbody>
                  </table>

                  <!-- Three Column side by side stories -->
                  <table class="row" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:left;vertical-align:top" >
                        <?php
                        $count_trending = 0;
                        while ( $trending_query->have_posts() ) :

                          if ( $count_trending >= 3 ) :
                            break;
                          endif;

                          $trending_query->the_post();

                          if ( get_post_status() != 'publish' ) :
                            continue;
                          endif;

                          $count_trending++;


                          $trending_img = '';
                          if ( get_post_meta( get_the_ID(), 'thumbnail_ext_url', TRUE ) && '' != get_post_meta( get_the_ID(), 'thumbnail_ext_url', TRUE ) ) {
                          	$trending_img = get_post_meta( get_the_ID(), 'thumbnail_ext_url', TRUE );
                          } else {
                            if ( has_post_thumbnail() ) :
                              $trending_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                              $trending_img = $trending_img[0];
                            endif; // If there is featured image
                          }
                          if ( isset( $trending_img ) && '' != $trending_img ) :
                            $trending_img = $this->resize_image( $trending_img, 660, 370, '/edm/trending/' );
                          endif;

                        ?>
                        <th class="story story-tag pad-bottom small-12 large-4 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:left;width:203.33px" >
                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%" >
                            <tr style="padding:0;text-align:left;vertical-align:top" >
                              <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:left">
                                <?php if ( isset( $trending_img ) && '' != $trending_img ) : ?>
                                <a href="<?php the_permalink(); ?>" style="Margin:0;color:#2D2CA2;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:left;text-decoration:none"><img width="199" height="123" src="<?php echo $trending_img; ?>" alt="<?php echo get_the_title(); ?>" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;height:auto;margin-bottom:10px;max-width:100%;outline:0;text-decoration:none;width:100%"></a>
                                <?php endif; ?>
                                <h5 style="Margin:0;Margin-bottom:5px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;margin-bottom:5px;padding:0;text-align:left;word-wrap:normal">
                                  <a class="h5-3col" href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>" style="Margin:0;color:#000!important;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:700;line-height:16.8px;margin:0;padding:0;text-align:left;text-decoration:none"><?php echo get_the_title(); ?></a>
                                </h5>
                                <p style="Margin:0;Margin-bottom:10px;color:#555;font-family:Georgia,serif;font-size:16px;font-weight:400;line-height:24px;margin:0;margin-bottom:10px;padding:0;text-align:left" ><a class="cta" href="<?php the_permalink(); ?>" style="Margin:0;color:#d32531!important;font-family:Helvetica,Arial,sans-serif;font-size:9px;font-weight:400;line-height:18.2px;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase" >Read More</a></p>
                              </th>
                            </tr>
                          </table>
                        </th>
                        <?php endwhile; ?>
                      </tr>
                    </tbody>
                  </table>
                  <?php endif; // If there are trending articles ?>

                  <?php
                  /*
                   * Promoter
                   * Livenation
                   */
                  $promoter_articles = array();

                  /*
                  $tbm_promoter_articles = get_option( 'tbm_promoter_articles' ) ? json_decode( get_option( 'tbm_promoter_articles' ) ) : array();

                  foreach( $tbm_promoter_articles as $tbm_promoter_article ):

                    if ( '' == $tbm_promoter_article )
                      continue;

                    $promoter_article = array();

                    $promoter_article['link'] = $tbm_promoter_article;

                    $tbm_promoter_article_html = file_get_contents( $tbm_promoter_article );
                    $tbm_promoter_article_html_dom = new DOMDocument();
                    @$tbm_promoter_article_html_dom->loadHTML( $tbm_promoter_article_html );
                    foreach( $tbm_promoter_article_html_dom->getElementsByTagName('meta') as $meta ) {
                      if( $meta->getAttribute( 'property' ) == 'og:image' ){
                        $promoter_article['img'] = $meta->getAttribute('content');
                        // break;
                      }
                      if( $meta->getAttribute( 'property' ) == 'og:title' ){
                        $promoter_article['title'] = $meta->getAttribute('content');
                        // break;
                      }
                    }
                    array_push( $promoter_articles, $promoter_article );
                  endforeach; // For Each $tbm_promoter_articles

                  if ( empty( $promoter_articles ) ) :
                    // Articles from ToneDeaf.TheBrag.com
                    $ch_td_promoter = curl_init();
                    curl_setopt($ch_td_promoter, CURLOPT_URL, "https://tonedeaf.thebrag.com/wp-json/api/v1/articles/promoter?promoter=Livenation");
                    curl_setopt($ch_td_promoter, CURLOPT_RETURNTRANSFER, 1);
                    $output_td_promoter = curl_exec($ch_td_promoter);
                    curl_close($ch_td_promoter);
                    $articles_td_promoter = json_decode( $output_td_promoter );

                    if( count( $articles_td_promoter ) > 0 ):
                        foreach ( $articles_td_promoter as $article_td_promoter ) :
                            $promoter_article = array();
                            $promoter_article['title'] = $article_td_promoter->title;
                            $promoter_article['link'] = $article_td_promoter->link;
                            $promoter_article['img'] = $article_td_promoter->image; // $this->resize_image( $article_td_promoter->image, 660, 370, '/edm/promoter/' );
                            $promoter_article['pubdate'] = strtotime( $article_td_promoter->publish_date );

                            array_push( $promoter_articles, $promoter_article );
                        endforeach;
                    endif;

                    // Articles from TheBrag.com
                    $ch_brag_promoter = curl_init();
                    curl_setopt($ch_brag_promoter, CURLOPT_URL, "https://thebrag.com/wp-json/api/v1/articles/promoter?promoter=Livenation");
                    curl_setopt($ch_brag_promoter, CURLOPT_RETURNTRANSFER, 1);
                    $output_brag_promoter = curl_exec($ch_brag_promoter);
                    curl_close($ch_brag_promoter);
                    $articles_brag_promoter = json_decode( $output_brag_promoter );

                    if( count( $articles_brag_promoter ) > 0 ):
                        foreach ( $articles_brag_promoter as $key => $article_brag_promoter ) :
                            $promoter_article = array();
                            $promoter_article['title'] = $article_brag_promoter->title;
                            $promoter_article['link'] = $article_brag_promoter->link;
                            $promoter_article['img'] = $article_brag_promoter->image; // $this->resize_image( $article_brag_promoter->image, 660, 370, '/edm/promoter/' );
                            $promoter_article['pubdate'] = strtotime( $article_brag_promoter->publish_date );

                            array_push( $promoter_articles, $promoter_article );
                        endforeach;
                    endif;

                    // Articles from Don't Bore Us
                    $ch_dbu_promoter = curl_init();
                    curl_setopt($ch_dbu_promoter, CURLOPT_URL, "https://dontboreus.thebrag.com/wp-json/api/v1/articles/promoter?promoter=Livenation");
                    curl_setopt($ch_dbu_promoter, CURLOPT_RETURNTRANSFER, 1);
                    $output_dbu_promoter = curl_exec($ch_dbu_promoter);
                    curl_close($ch_dbu_promoter);
                    $articles_dbu_promoter = json_decode( $output_dbu_promoter );

                    if( count( $articles_dbu_promoter ) > 0 ):
                        foreach ( $articles_dbu_promoter as $key => $article_dbu_promoter ) :
                            $promoter_article = array();
                            $promoter_article['title'] = $article_dbu_promoter->title;
                            $promoter_article['link'] = $article_dbu_promoter->link;
                            $promoter_article['img'] = $article_dbu_promoter->image; // $this->resize_image( $article_dbu_promoter->image, 660, 370, '/edm/promoter/' );
                            $promoter_article['pubdate'] = strtotime( $article_dbu_promoter->publish_date );

                            array_push( $promoter_articles, $promoter_article );
                        endforeach;
                    endif;

                    usort( $promoter_articles, function($a, $b) {
                        return $b['pubdate'] <=> $a['pubdate'];
                    });
                  endif; // If $promoter_articles is empty
                  */

                  if ( count( $promoter_articles ) > 0 ) :
                  ?>
                  <!-- Section Title -->
                  <table class="row section-title centered border" style="background:#fff;border-collapse:collapse;border-spacing:0;color:#0a0a0a!important;display:table;font-family:Helvetica,Arial,sans-serif;font-size:28px;font-weight:900;line-height:39.2px;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:0;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:670px" >
                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                            <tr style="padding:0;text-align:center;vertical-align:top" >
                              <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;padding-bottom:0;padding-top:15px;text-align:center" >
                                <img class="section-title" src="https://tonedeaf.thebrag.com/wp-content/uploads/edm/LiveNationTours.jpg" style="margin:0; width: 100%; height: auto;">
                              </th>
                              <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0!important;padding-bottom:0;padding-top:15px;text-align:center;visibility:hidden;width:0" ></th>
                            </tr>
                          </table>
                        </th>
                      </tr>
                    </tbody>
                  </table>

                  <table class="row" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:left;vertical-align:top" >
                        <?php
                        foreach ( $promoter_articles as $key => $promoter_article ) :
                          if ( $key == 3 )
                            break;
                          $promoter_article['img'] =  $this->resize_image( $promoter_article['img'], 660, 370, '/edm/promoter/' );

                          array_push( $promoter_article_links, $promoter_article['link'] );
                        ?>
                        <th class="story story-tag pad-bottom small-12 large-4 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:left;width:203.33px" >
                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%" >
                            <tr style="padding:0;text-align:left;vertical-align:top" >
                              <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:left" >
                                <a href="<?php echo $promoter_article['link']; ?>" style="Margin:0;color:#2D2CA2;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:left;text-decoration:none" ><img width="199" height="123" src="<?php echo $promoter_article['img']; ?>" alt="<?php echo $promoter_article['title']; ?>" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;height:auto;margin-bottom:10px;max-width:100%;outline:0;text-decoration:none;width:100%" ></a>
                                <h5 style="Margin:0;Margin-bottom:5px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;margin-bottom:5px;padding:0;text-align:left;word-wrap:normal" ><a class="h5-3col" href="<?php echo $promoter_article['link']; ?>" title="<?php echo $promoter_article['title']; ?>" style="Margin:0;color:#000!important;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:700;line-height:16.8px;margin:0;padding:0;text-align:left;text-decoration:none" ><?php echo $promoter_article['title']; ?></a></h5>
                              </th>
                            </tr>
                          </table>
                        </th>
                        <?php endforeach; ?>
                      </tr>
                    </tbody>
                  </table>
                  <?php
                endif; // If there are $promoter_articles ?>

                  <!-- Featured Video / Audio -->

                  <table class="row col-2" style="background:#fff;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px">

                        <?php
                        $featured_video = get_option( 'tbm_featured_video' );
                        $featured_video_link = get_option( 'tbm_featured_video_link' );
                        // $featured_video_img_src = 'https://i.ytimg.com/vi/' . $featured_video . '/0.jpg';
                        if ( ! $featured_video_link || '' == $featured_video_link ) {
                          $featured_video_link = $featured_video;
                        }
                        {
                          $tbm_featured_video_link_html = file_get_contents( $featured_video_link );
                          $tbm_featured_video_link_html_dom = new DOMDocument();
                          @$tbm_featured_video_link_html_dom->loadHTML( $tbm_featured_video_link_html );
                          // $meta_og_img_tbm_featured_video_link = null;
                          foreach( $tbm_featured_video_link_html_dom->getElementsByTagName('meta') as $meta ) {
                            if( $meta->getAttribute( 'property' ) == 'og:image' ){
                              $featured_video_img_src = $meta->getAttribute('content');
                              break;
                            }
                          }
                        }
                        if ( !is_null( $featured_video ) && $featured_video != '' ) :
                          parse_str( parse_url( $featured_video, PHP_URL_QUERY ), $featured_video_vars );
                          $featured_yt_vid_id = $featured_video_vars['v'];
                          $featured_video_alt = '';
                          if( get_option( 'tbm_featured_video_artist' ) ) {
                            $featured_video_alt .= esc_html( stripslashes( get_option( 'tbm_featured_video_artist' ) ) );
                          }
                          if ( get_option( 'tbm_featured_video_song' ) ) {
                            $featured_video_alt .= ' - \''. esc_html( stripslashes( get_option( 'tbm_featured_video_song' ) ) ) . '\'';
                          }

                          // $featured_video_img =  $this->resize_image( 'https://i.ytimg.com/vi/' . $featured_yt_vid_id . '/sddefault.jpg', 660, 370, '/edm/featured/', 'featured-vid-' . date('Y\wW') . '.jpg' );

                          $featured_video_img =  $this->resize_image( $featured_video_img_src, 660, 370, '/edm/featured/', 'featured-vid-' . date('Y\wW') . '-n.jpg' );
                        ?>
                        <!--[if gte mso 9 ]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="700" style="max-width:700px">
                        <tr>
                        <td align="center" valign="top" width="700" style="max-width:700px">
                        <![endif]-->
                        <table class="row ad" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%;max-width:700px" >
                          <tbody>
                            <tr style="padding:0;text-align:center;vertical-align:top" >
                              <th class="pad-bottom small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:0px;padding-left:0px;padding-right:0px;padding-top:0px;text-align:center;" >
                                <table bgcolor="#000000" border="0" cellpadding="0" cellspacing="0" style="width:100%; border:none!important;background-color: #000000;" >
                                  <tr>
                                    <td class="mcnTextContent" valign="top" style="padding: 9px 9px 9px 9px;color: #ffffff;font-family: Helvetica;font-size: 14px;font-style: normal;font-weight: normal;line-height: 150%;text-align: center;" width="546">
                                      <h1 class="null" style="text-align: center; padding: 0; margin: 0;"><font color="#ffffff" size="4">VIDEO OF THE WEEK</font></h1>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" style="display:table-cell!important; line-height:0!important; height:auto!important;" >
                                      <a style="display: block;" href="<?php echo $featured_video_link; ?>" rel="nofollow" >
                                        <img src="<?php echo $featured_video_img; ?>" alt="<?php echo $featured_video_alt; ?>" title="<?php echo $featured_video_alt; ?>" border="0" style="width:100%" >
                                      </a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="mcnTextContent" valign="top" style="padding: 9px 9px 9px 9px;color: #ffffff;font-family: Helvetica;font-size: 12px;font-style: normal;font-weight: normal;line-height: 150%;text-align: center;" width="546">
                                      <h1 class="null" style="text-align: center; padding: 0; margin: 0;">
                                        <a href="<?php echo $featured_video_link; ?>" target="_blank" style="text-decoration: none;">
                                          <font color="#ffffff" size="4"><?php if( get_option( 'tbm_featured_video_artist' ) ) {
                                                echo '' . esc_html( stripslashes( get_option( 'tbm_featured_video_artist' ) ) );
                                            }
                                            if ( get_option( 'tbm_featured_video_song' ) ) {
                                                echo '<br><em>\''. esc_html( stripslashes( get_option( 'tbm_featured_video_song' ) ) ) . '\'</em>';
                                            } ?></font>
                                          </a>
                                        </h1>
                                      </td>
                                    </tr>
                                  </table>
                                  <br>
                                </th>
                              </tr>
                            </tbody>
                          </table>
                          <!--[if gte mso 9 ]>
                          </td>
                          </tr>
                          </table>
                          <![endif]-->
                        <?php
                        endif; // If Featured Video is available
                        ?>
                      </th>
                      <th class="story story-tag pad-bottom small-12 large-6 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:320px" >
                        <!--[if gte mso 9 ]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="700" style="max-width:700px">
                        <tr>
                        <td align="center" valign="top" width="700" style="max-width:700px">
                        <![endif]-->
                        <?php
                        $featured_record_alt = '';
                        $featured_record_alt .= esc_html( stripslashes( get_option( 'tbm_featured_album_artist' ) ) );
                        $featured_record_alt .= ' - ' . esc_html( stripslashes( get_option( 'tbm_featured_album_title' ) ) );
                        $featured_record_img =  $this->resize_image( get_option( 'tbm_featured_album_image_url' ), 660, 370, '/edm/featured/', 'featured-record-' . date('Y\wW') . '.jpg' );
                        ?>
                        <table class="row ad" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%;max-width:700px" >
                          <tbody>
                            <tr style="padding:0;text-align:center;vertical-align:top" >
                              <th class="pad-bottom small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:0px;padding-left:0px;padding-right:0px;padding-top:0px;text-align:center;" >
                                <table bgcolor="#000000" border="0" cellpadding="0" cellspacing="0" style="width:100%; border:none!important;background-color: #000000;" >
                                  <tr>
                                    <td class="mcnTextContent" valign="top" style="padding: 9px 9px 9px 9px;color: #ffffff;font-family: Helvetica;font-size: 14px;font-style: normal;font-weight: normal;line-height: 150%;text-align: center;" width="546">
                                      <h1 class="null" style="text-align: center; padding: 0; margin: 0;"><font color="#ffffff" size="4">RECORD OF THE WEEK</font></h1>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" style="display:table-cell!important; line-height:0!important; height:auto!important;" >
                                      <a style="display: block;" href="<?php echo get_option( 'tbm_featured_album_link' ); ?>" rel="nofollow" >
                                        <img src="<?php echo $featured_record_img; ?>" alt="<?php echo $featured_record_alt; ?>" title="<?php echo $featured_record_alt; ?>" border="0" style="width:100%" >
                                      </a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="mcnTextContent" valign="top" style="padding: 9px 9px 9px 9px;color: #ffffff;font-family: Helvetica;font-size: 12px;font-style: normal;font-weight: normal;line-height: 150%;text-align: center;" width="546">
                                      <h1 class="null" style="text-align: center; padding: 0; margin: 0;">
                                        <a href="<?php echo get_option( 'tbm_featured_album_link' ); ?>" target="_blank" style="text-decoration: none;">
                                          <font color="#ffffff" size="4"><?php if( get_option( 'tbm_featured_album_artist' ) ) {
                                                echo '' . esc_html( stripslashes( get_option( 'tbm_featured_album_artist' ) ) );
                                            }
                                            if ( get_option( 'tbm_featured_album_title' ) ) {
                                                echo '<br><em>\''. esc_html( stripslashes( get_option( 'tbm_featured_album_title' ) ) ) . '\'</em>';
                                            } ?></font>
                                          </a>
                                        </h1>
                                      </td>
                                    </tr>
                                  </table>
                                  <br>
                                </th>
                              </tr>
                            </tbody>
                          </table>
                          <!--[if gte mso 9 ]>
                          </td>
                          </tr>
                          </table>
                          <![endif]-->
                      </th></tr>
                  </tbody>
                </table>
                  <!-- END Featured Video / Audio -->

                  <!-- Footer -->
                  <table bgcolor="#000000" class="wrapper footer" align="center" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%;max-width:700px" >
                    <tr style="padding:0;text-align:center;vertical-align:top" >
                      <td class="wrapper-inner" style="-moz-hyphens:none;-webkit-hyphens:none;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;hyphens:none;line-height:1.4;margin:0;padding:0;text-align:center;vertical-align:top;word-wrap:break-word" >
                        <table class="row" style="background:0 0;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                          <tbody>
                            <tr style="padding:0;text-align:left;vertical-align:middle" >
                              <th class="logo small-6 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:15px;padding-top:20px;text-align:center;width:320px" >
                                <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                                  <tr style="padding:0;text-align:center;vertical-align:middle" >
                                    <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" ><a href="<?php echo home_url(); ?>" target="_blank" style="Margin:0;color:#2D2CA2;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none" ><img height="36" src="<?php echo get_template_directory_uri(); ?>/images/edm/RS-AU_LOGO-RED.png" mcdisabled-mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;outline:0;text-decoration:none;width:auto;max-width:200px;" ></a></th>
                                  </tr>
                                </table>
                              </th>
                              <th class="icon small-2 large-2 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;height:25px;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:20px;text-align:center;width:25px" >
                                <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                                  <tr style="padding:0;text-align:center;vertical-align:middle" >
                                    <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" ><a href="https://www.facebook.com/rollingstoneaustralia/" target="_blank" style="Margin:0;border:1px solid #2F2F2F;border-radius:50%;color:#2D2CA2;display:block;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none;width:35px" ><img height="34" width="34" src="<?php echo get_template_directory_uri(); ?>/images/edm/footer_social_facebook.png" mcdisabled-mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;max-width:100%;outline:0;text-decoration:none;width:auto" ></a></th>
                                  </tr>
                                </table>
                              </th>
                              <th class="icon small-2 large-2 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;height:25px;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:20px;text-align:center;width:25px" >
                                <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                                  <tr style="padding:0;text-align:center;vertical-align:middle" >
                                    <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" ><a href="https://twitter.com/rollingstoneaus" target="_blank" style="Margin:0;border:1px solid #2F2F2F;border-radius:50%;color:#2D2CA2;display:block;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none;width:35px" ><img height="34" width="34" src="<?php echo get_template_directory_uri(); ?>/images/edm/footer_social_twitter.png" mcdisabled-mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;max-width:100%;outline:0;text-decoration:none;width:auto" ></a></th>
                                  </tr>
                                </table>
                              </th>
                              <th class="icon small-2 large-2 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;height:25px;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:20px;text-align:center;width:25px" >
                                <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                                  <tr style="padding:0;text-align:center;vertical-align:middle" >
                                    <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" ><a href="https://instagram.com/rollingstoneaus" target="_blank" style="Margin:0;border:1px solid #2F2F2F;border-radius:50%;color:#2D2CA2;display:block;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none;width:35px" ><img height="34" width="34" src="<?php echo get_template_directory_uri(); ?>/images/edm/footer_social_instagram.png" mcdisabled-mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;max-width:100%;outline:0;text-decoration:none;width:auto" ></a></th>
                                  </tr>
                                </table>
                              </th>
                              <th class="icon small-2 large-2 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;height:25px;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:10px;padding-right:20px;padding-top:20px;text-align:center;width:25px" >
                                <table style="border-collapse:collapse;border-spacing:0;max-width:36px;padding:0;text-align:center;vertical-align:top;width:100%" >
                                  <tr style="padding:0;text-align:center;vertical-align:middle" >
                                    <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center" ><a href="https://www.youtube.com/channel/UC5ogXwEsy_q8_2DQHp1RU8A" target="_blank" style="Margin:0;border:1px solid #2F2F2F;border-radius:50%;color:#2D2CA2;display:block;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none;width:35px" ><img height="34" width="34" src="<?php echo get_template_directory_uri(); ?>/images/edm/footer_social_youtube.png" mcdisabled-mcdisabled-alt="" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;max-width:100%;outline:0;text-decoration:none;width:auto" ></a></th>
                                  </tr>
                                </table>
                              </th>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                  <!-- Footer Legal Copy -->

                  <table class="row footer-copy" style="background:0 0;border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%" >
                    <tbody>
                      <tr style="padding:0;text-align:center;vertical-align:top" >
                        <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0 auto;padding:0;padding-bottom:20px;padding-left:30px;padding-right:30px;padding-top:20px;text-align:center;width:670px" >
                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
                            <tr style="padding:0;text-align:center;vertical-align:top" >
                              <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;padding-top:0;text-align:center" >
                                <p style="Margin:0;Margin-bottom:10px;color:grey;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:400;line-height:16px;margin:0;margin-bottom:0;padding:0;text-align:center" >This email was sent to *|EMAIL|* by Rolling Stone Australia. Please add noreply@thebrag.media to your address book to ensure delivery to your inbox.</p>

                                <p style="Margin:0;Margin-bottom:10px;color:grey;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:400;line-height:16px;margin:0;margin-bottom:0;padding:0;text-align:center" >Copyright &#169; <?php echo date('Y'); ?> Rolling Stone Australia.</p>
                                <br>
                                <p style="Margin:0;Margin-bottom:10px;color:grey;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:400;line-height:16px;margin:0;margin-bottom:0;padding:0;text-align:center">
                                  <a href="*|UPDATE_PROFILE|*" style="Margin:0;color:#3c43b2!important;font-family:Helvetica;font-size:11px;font-weight:400;line-height:16px;margin:0;padding:0;text-align:center;text-decoration:none" >Update my details</a>
                                  |
                                  <a href="*|ARCHIVE|*" style="Margin:0;color:#3c43b2!important;font-family:Helvetica;font-size:11px;font-weight:400;line-height:16px;margin:0;padding:0;text-align:center;text-decoration:none" >View in Browser</a>
                                  |
                                  <a href="*|UNSUB|*" style="Margin:0;color:#3c43b2!important;font-family:Helvetica;font-size:11px;font-weight:400;line-height:16px;margin:0;padding:0;text-align:center;text-decoration:none" >Unsubscribe</a>
                                  |
                                  <a href="https://thebrag.media/" style="Margin:0;color:#3c43b2!important;font-family:Helvetica;font-size:11px;font-weight:400;line-height:16px;margin:0;padding:0;text-align:center;text-decoration:none" >Advertise with us</a></p>
                                </p>
                              </th>
                            </tr>
                          </table>
                        </th>
                      </tr>
                    </tbody>
                  </table>

                  <!--[if mso]>
                      </td>
                      <td style="padding:0px;margin:0px;">&nbsp;</td>
                  </tr>
                  <tr><td colspan="3" style="padding:0px;margin:0px;font-size:20px;height:20px;" height="20">&nbsp;</td></tr>
              </table>
              <![endif]--></td>
              </tr>
            </tbody>
          </table>
        </center>
      </td>
    </tr>
  </table>
  <!-- prevent Gmail on iOS font size manipulation -->
  <div style="display:none;white-space:nowrap;font:15px courier;line-height:0" >&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
</body>
</html>
<?php

if ( 'create-on-mc' == $action ) :
  $wpdb->update(
    $table,
    array(
      'articles' => json_encode( $post_links ),
      'promoter_articles' => json_encode( $promoter_article_links ),
      'ad_links' => json_encode( $ad_links ),
    ),
    array(
      'id' => $id
    )
  );
endif;

function print_article( $newsletter, $article_number ) {
    $post_links = get_object_vars( $newsletter->details->post_links );
    $post_images = get_object_vars( $newsletter->details->post_images );
    $post_titles = get_object_vars( $newsletter->details->post_titles );
    $post_excerpts = get_object_vars( $newsletter->details->post_excerpts );

    if ( !is_null( $article_number ) ) : ?>
    <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:center;vertical-align:top;width:100%" >
      <tr style="padding:0;text-align:center;vertical-align:top" >
        <th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center">
          <?php if ( isset( $post_images[ $article_number ] ) && $post_images[ $article_number ] != '' ) : ?>
          <a href="<?php echo $post_links[ $article_number ]; ?>" style="Margin:0;color:#2D2CA2;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.4;margin:0;padding:0;text-align:center;text-decoration:none"><img src="<?php echo  $post_images[ $article_number ]; ?>" alt="<?php echo $post_titles[ $article_number ]; ?>" title="<?php echo $post_titles[ $article_number ]; ?>" width="304" height="170" style="-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;height:auto;margin-bottom:10px;max-width:100%;outline:0;text-decoration:none;width:100%" >
          </a>
          <?php endif; ?>
          <h2 style="Margin:0;Margin-bottom:5px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:400;line-height:1.4;margin:0;margin-bottom:5px;margin-top:5px;padding:0;text-align:center;word-wrap:normal" ><a class="headline" href="<?php echo $post_links[ $article_number ]; ?>" title="<?php echo $post_titles[ $article_number ]; ?>" style="Margin:0;color:#000!important;font-family:Helvetica,Arial,sans-serif;font-size:23px;font-weight:700;line-height:28px;margin:0;padding:0;text-align:center;text-decoration:none" ><?php echo $post_titles[ $article_number ]; ?></a></h2>
        </th>
      </tr>
    </table>

<?php endif;
} // function print_articles
