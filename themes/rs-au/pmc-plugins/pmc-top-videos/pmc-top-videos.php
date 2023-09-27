<?php
// wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );
require_once( __DIR__ . '/class-pmc-top-videos.php' );
require_once( __DIR__ . '/class-pmc-top-videos-widget.php' );

// PMC_Top_Videos::get_instance();
$PMC_Top_Videos = new PMC_Top_Videos();
$PMC_Top_Videos->_init();

//EOF
