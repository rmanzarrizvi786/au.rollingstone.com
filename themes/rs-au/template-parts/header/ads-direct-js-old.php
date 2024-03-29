<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
<script>
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
</script>
<script>
  var adslot_mobile_leaderboard,
    adslot_hrec_1,
    adslot_side_1,
    adslot_vrec_1,
    adslot_vrec_2;
  window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {
    var mapping_desktop_wallpaper = googletag.sizeMapping().
      addSize([0, 0], []).
      addSize([1280, 500], [1600, 1200]). // Desktop
      build();
    var adslot_wallpaper = googletag.defineSlot('/9876188/rollingstoneau/rsau_wallpaper', [1600, 1200], 'td_skin').addService(googletag.pubads());
    // adslot_wallpaper.defineSizeMapping(mapping_desktop_wallpaper);

    var adslot_inskin = googletag.defineSlot('/9876188/rollingstoneau/rsau_inskin', [1, 1], 'td_inskin').addService(googletag.pubads());

    // HREC 1
    var mapping_hrec1 = googletag.sizeMapping().
      addSize([0, 0], []).
      addSize([970, 250], [970, 250]). // Desktop
      addSize([970, 250], [728, 90]). // Desktop
      build();
    adslot_hrec_1 = googletag.defineSlot('/9876188/rollingstoneau/rsau_hrec_1', [[970, 250], [728, 90]], 'td_hrec_1-desktop').addService(googletag.pubads());
    adslot_hrec_1.defineSizeMapping(mapping_hrec1);

    adslot_hrec_mobile = googletag.defineSlot('/9876188/rollingstoneau/rsau_hrec_1', [320, 50], 'td_hrec_1-mobile').addService(googletag.pubads());

    // VREC 1
    // var mapping_desktop_vrec_1 = googletag.sizeMapping().
    //   addSize([0, 0], []).
    //   addSize([970, 250], [300, 250]). // Desktop
    //   build();
    adslot_vrec_1 = googletag.defineSlot('/9876188/rollingstoneau/rsau_vrec_1', [300, 250], 'td_vrec_1').addService(googletag.pubads());
    // adslot_vrec_1.defineSizeMapping(mapping_desktop_vrec_1);

    // SIDE 1
    // var mapping_desktop_side_1 = googletag.sizeMapping().
    //   addSize([0, 0], []).
    //   addSize([970, 250], [300, 250]). // Desktop
    //   build();
    adslot_side_1 = googletag.defineSlot('/9876188/rollingstoneau/rsau_side_1', [300, 250], 'td_side_1').addService(googletag.pubads());
    // adslot_side_1.defineSizeMapping(mapping_desktop_side_1);

    // var mapping_desktop_vrec_2 = googletag.sizeMapping().
    //   addSize([0, 0], []).
    //   addSize([970, 250], [300, 600]). // Desktop
    //   build();
    adslot_vrec_2 = googletag.defineSlot('/9876188/rollingstoneau/rsau_vrec_2', [[300, 600], [300, 250]], 'td_vrec_2').addService(googletag.pubads());
    // adslot_vrec_2.defineSizeMapping(mapping_desktop_vrec_2);

    // Header Mobile
    // var mapping_mobile_header = googletag.sizeMapping().
    //   addSize([640, 480], [320, 50]).
    //   addSize([0, 0], [320, 50]).
    //   build();
    adslot_mobile_leaderboard = googletag.defineSlot('/9876188/tonedeaf/td_header_mobile', [320, 50], 'td_header_mobile').addService(googletag.pubads());
    // adslot_mobile_leaderboard.defineSizeMapping(mapping_mobile_header);

    <?php if ( is_single() ) : ?>
    var adslot_teads = googletag.defineSlot('/9876188/rollingstoneau/teads', [1, 1], 'td_teads').addService(googletag.pubads());
    <?php endif; ?>

    googletag.pubads().enableSingleRequest();
    // googletag.pubads().collapseEmptyDivs();
    googletag.pubads().enableLazyLoad({
      fetchMarginPercent: 10,
      renderMarginPercent: 5,
      mobileScaling: 2.0  // Double the above values on mobile.
    });

    googletag.pubads().setTargeting("pagepath", '<?php echo substr( str_replace( '/', '', $_SERVER['REQUEST_URI'] ), 0, 40 ); ?>');

    var fn_pageskin = "false";
    if (screen.width >= 1230) {
      fn_pageskin = "true";
    }
    googletag.pubads().setTargeting("inskin_yes",fn_pageskin);

    googletag.enableServices();

    googletag.pubads().addEventListener('slotRenderEnded', function(event) {

      if (event.slot == adslot_inskin && ! event.isEmpty) {
        // googletag.destroySlots([adslot_hrec_1, adslot_wallpaper]);
      }

      if ( event.slot == adslot_mobile_leaderboard && event.isEmpty ) {
        var newScript = document.createElement("script");
        var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_sticky_1'); });");
        newScript.appendChild(inlineScript);

        var slot_id_mobile_leaderboard = event.slot.getSlotElementId();
        jQuery('#' + slot_id_mobile_leaderboard ).show();
        document.getElementById(slot_id_mobile_leaderboard).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_sticky_1"></div></div>';
        document.getElementById(slot_id_mobile_leaderboard).appendChild( newScript);

        jQuery('#' + slot_id_mobile_leaderboard ).show();
      }

      if ( event.slot == adslot_hrec_1 && event.isEmpty ) {
        var newScript = document.createElement("script");
        var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_main_1'); });");
        newScript.appendChild(inlineScript);

        var slot_id_hrec_1 = event.slot.getSlotElementId();
        jQuery('#' + slot_id_hrec_1 ).show();
        document.getElementById(slot_id_hrec_1).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_main_1"></div></div>';
        document.getElementById(slot_id_hrec_1).appendChild( newScript);

        jQuery('#' + slot_id_hrec_1 ).show();
      }

      if ( event.slot == adslot_vrec_1 && event.isEmpty ) {
        var newScript = document.createElement("script");
        var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_side_1'); });");
        newScript.appendChild(inlineScript);

        var slot_id_vrec_1 = event.slot.getSlotElementId();
        jQuery('#' + slot_id_vrec_1 ).show();
        document.getElementById(slot_id_vrec_1).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_side_1"></div></div>';
        document.getElementById(slot_id_vrec_1).appendChild( newScript);

        jQuery('#' + slot_id_vrec_1 ).show();
      }

      if ( event.slot == adslot_side_1 && event.isEmpty ) {
        var newScript = document.createElement("script");
        var inlineScript = document.createTextNode("propertag.cmd.push(function() { proper_display('aurollingstonecom_side_1'); });");
        newScript.appendChild(inlineScript);

        var slot_id_side_1 = event.slot.getSlotElementId();
        jQuery('#' + slot_id_side_1 ).show();
        document.getElementById(slot_id_side_1).innerHTML = '<div class="proper-ad-unit"><div id="proper-ad-aurollingstonecom_side_1"></div></div>';
        document.getElementById(slot_id_side_1).appendChild( newScript);

        jQuery('#' + slot_id_side_1 ).show();
      }

      if ( event.slot == adslot_vrec_2 && event.isEmpty ) {
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
  });

  // setInterval(function(){googletag.pubads().refresh([adslot_mobile_leaderboard]);}, 45000);
</script>
