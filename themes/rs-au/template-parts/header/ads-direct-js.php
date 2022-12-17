<?php

if (!get_field('disable_ads')) :

  $network_code = '9876188';
  $adunit_parent_code = 'rollingstoneau';
  $adunit_page = 'homepage_';

  $categories = [
    'music' => ['music', 'music-news', 'music-album-reviews', 'music-features', 'music-lists', 'music-live-reviews', 'music-pictures', 'music-videos',],
    'politics' => ['politics', 'politics-features', 'politics-lists', 'politics-news', 'politics-pictures', 'politics-videos',],
    'sports' => ['sports', 'sports-features', 'sports-news',],
    'tv' => ['tv', 'tv-features', 'tv-lists', 'tv-news', 'tv-pictures', 'tv-recaps', 'tv-reviews', 'tv-videos',],
    'movies' => ['movies', 'movie-features', 'movie-lists', 'movie-news', 'movie-pictures', 'movie-reviews', 'movie-videos',],
    'culture' => ['culture', 'culture-features', 'culture-lists', 'culture-news', 'culture-videos, ']
  ];

  if (is_home() || is_front_page()) {
    $adunit_page = 'homepage_';
  } else if (is_category()) {
    if (is_array($categories)) {
      foreach ($categories as $section => $category) {
        if (is_category($category)) {
          $adunit_page = $section . '_front_';
          break;
        }
      }
    }
  } else if (is_single()) {
    if (is_array($categories)) {
      foreach ($categories as $section => $category) {
        if (in_category($category)) {
          $adunit_page = $section . '_article_';
          break;
        }
      }
    }
  }
?>
  <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
  <script>
    /*
var propertag = propertag || {};
propertag.cmd = propertag.cmd || [];
(function() {
var pm = document.createElement('script');
pm.async = true; pm.type = 'text/javascript';
var is_ssl = 'https:' == document.location.protocol;
pm.src = (is_ssl ? 'https:' : 'http:') + '//global.proper.io/aurollingstonecom.min.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(pm, node);
})();
*/
  </script>
  <script>
    var adslot_skin,
      adslot_leaderboard,
      adslot_rail_1,
      adslot_rail_2;
    window.googletag = window.googletag || {
      cmd: []
    };
    googletag.cmd.push(function() {
      var mapping_skin = googletag.sizeMapping().
      addSize([0, 0], []).
      addSize([1200, 500], [1600, 1200]). // Desktop
      build();
      var adslot_skin = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>skin', [1600, 1200], 'adm_skin').addService(googletag.pubads());
      adslot_skin.defineSizeMapping(mapping_skin);

      // var adslot_inskin = googletag.defineSlot('/9876188/rollingstoneau/rsau_inskin', [1, 1], 'td_inskin').addService(googletag.pubads());

      // HREC 1
      var mapping_leaderboard = googletag.sizeMapping().
      addSize([0, 0], []).
      addSize([970, 250], [
        [970, 250],
        [970, 66],
        [970, 90],
        [728, 90]
      ]). // Desktop
      build();
      adslot_leaderboard = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>leaderboard', [
        [970, 250],
        [970, 66],
        [970, 90],
        [728, 90]
      ], 'adm_leaderboard-desktop').addService(googletag.pubads());
      adslot_leaderboard.defineSizeMapping(mapping_leaderboard);

      var mapping_leaderboard_mobile = googletag.sizeMapping().
      addSize([0, 0], [
        [320, 50],
        [320, 100]
      ]).
      addSize([970, 250], []). // Desktop
      build();
      adslot_hrec_mobile = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>leaderboard', [
        [320, 50],
        [320, 100]
      ], 'adm_leaderboard-mobile').addService(googletag.pubads());
      adslot_hrec_mobile.defineSizeMapping(mapping_leaderboard_mobile);

      <?php if (is_single()) : ?>
        // Rail 1
        adslot_rail_1 = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>rail1', [
            [300, 250],
            [300, 251]
          ], 'adm_rail1')
          .addService(googletag.pubads())
          .setTargeting('count', '1');

        <?php if (!has_term('featured-article', 'global-options')) : ?>
          // Teads
          // var adslot_teads = googletag.defineSlot('/9876188/rollingstoneau/teads', [1, 1], 'teads-outstream').addService(googletag.pubads());
        <?php endif; ?>
        // Inbody 1
        var adslot_inbody1 = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>inbody1', [
            [300, 250],
            [300, 251]
          ], 'adm_inbody1')
          .addService(googletag.pubads())
          .setTargeting('count', '1')
          .setTargeting('pos', 'inbody1');
      <?php else : ?>
        // Rail 1
        adslot_rail_1 = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>rail1', [
            [300, 250],
            [300, 251]
          ], 'adm_rail1')
          .addService(googletag.pubads())
          .setTargeting('count', '1');
      <?php endif; ?>

      // Rail 2
      adslot_rail_2 = googletag.defineSlot('/<?php echo $network_code; ?>/<?php echo $adunit_parent_code; ?>/<?php echo $adunit_page; ?>rail2', [
          [300, 600],
          [300, 250],
          [300, 251]
        ], 'adm_rail2')
        .addService(googletag.pubads())
        .setTargeting('count', '1');
      // adslot_rail_2.defineSizeMapping(mapping_desktop_vrec_2);



      googletag.pubads().enableSingleRequest();
      googletag.pubads().collapseEmptyDivs();
      googletag.pubads().enableLazyLoad({
        fetchMarginPercent: 10,
        renderMarginPercent: 5,
        mobileScaling: 2.0 // Double the above values on mobile.
      });

      <?php
      if (isset($_GET['screenshot'])) {
        $pagepath = 'screenshot';
      } else if (isset($_GET['dfp_key'])) {
        $pagepath = $_GET['dfp_key'];
      } else if (is_home() || is_front_page()) {
        $pagepath = 'homepage';
      } else {
        $pagepath_uri = substr(str_replace('/', '', $_SERVER['REQUEST_URI']), 0, 40);
        $pagepath_e = explode('?', $pagepath_uri);
        $pagepath = $pagepath_e[0];
      }
      ?>
      googletag.pubads().setTargeting("pagepath", '<?php echo $pagepath; ?>');

      var fn_pageskin = "false";
      if (screen.width >= 1230) {
        fn_pageskin = "true";
      }
      googletag.pubads().setTargeting("inskin_yes", fn_pageskin);

      <?php if (is_single()) {
        $category_slug = '';
        $categories = get_the_category();

        // Only parent categories.
        foreach ($categories as $category) {
          if (0 === $category->parent) {
            $category_slug = $category->slug;
            break;
          }
        }

        if ('' != $category_slug) {
      ?>
          googletag.pubads().setTargeting("category", '<?php echo $category_slug; ?>');
        <?php
        }
      } else if (is_category()) {
        $category = get_queried_object();
        $category_slug = $category->slug;
        ?>
        googletag.pubads().setTargeting("category", '<?php echo $category_slug; ?>');
      <?php
      } // If Single 
      ?>

      googletag.enableServices();

      /*
      googletag.pubads().addEventListener('slotRenderEnded', function(event) {

        if (event.slot == adslot_inskin && ! event.isEmpty) {
          // googletag.destroySlots([adslot_leaderboard, adslot_wallpaper]);
        }

        if ( event.slot == adslot_leaderboard && event.isEmpty ) {
          var newScript = document.createElement("script");
          var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_main_1'); });");
          newScript.appendChild(inlineScript);

          var slot_id_hrec_1 = event.slot.getSlotElementId();
          jQuery('#' + slot_id_hrec_1 ).show();
          document.getElementById(slot_id_hrec_1).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_main_1"></div></div>';
          document.getElementById(slot_id_hrec_1).appendChild( newScript);

          jQuery('#' + slot_id_hrec_1 ).show();
        }

        if ( event.slot == adslot_rail_1 && event.isEmpty ) {
          var newScript = document.createElement("script");
          var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_side_1'); });");
          newScript.appendChild(inlineScript);

          var slot_id_vrec_1 = event.slot.getSlotElementId();
          jQuery('#' + slot_id_vrec_1 ).show();
          document.getElementById(slot_id_vrec_1).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_side_1"></div></div>';
          document.getElementById(slot_id_vrec_1).appendChild( newScript);

          jQuery('#' + slot_id_vrec_1 ).show();
        }

        if ( event.slot == adslot_rail_2 && event.isEmpty ) {
          var newScript = document.createElement("script");
          var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_content_6'); });");
          newScript.appendChild(inlineScript);

          var slot_id_vrec_2 = event.slot.getSlotElementId();
          jQuery('#' + slot_id_vrec_2 ).show();
          document.getElementById(slot_id_vrec_2).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_content_6"></div></div>';
          document.getElementById(slot_id_vrec_2).appendChild( newScript);

          jQuery('#' + slot_id_vrec_2 ).show();
        }
      });
      */
    });

    // setInterval(function(){googletag.pubads().refresh([adslot_mobile_leaderboard]);}, 45000);
  </script>
<?php endif; // If ! get_field('disable_ads' )