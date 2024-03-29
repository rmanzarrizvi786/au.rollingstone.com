<?php
/**
 * prestitial template
 */
$site_logo = apply_filters( 'pmc-adm_ads-interruptus_interstitial-site-logo', esc_html( get_bloginfo( 'name' ) ), $css_class );
$site_name = apply_filters( 'pmc-adm_ads-interruptus_interstitial-site-name', esc_html( get_bloginfo( 'name' ) ) );


?>
<script type="text/javascript" language="javascript">
	pmc_admanager.settings.interrupt_counter = <?php echo intval( $duration ) ; ?>;

</script>
<style>
body.interrupt-ads > * {
	display: none;
}

body.interrupt-ads div#site-wrap {
	display: <?php echo ( 'Style2' === $css_class ) ? 'block' : 'none'; ?>;
}

body #pmc-adm-interrupts-container {
	display: none;
}

body.interrupt-ads #pmc-adm-interrupts-container  {
	display: block;
}

body.interrupt-ads {
	font-size: 1em; font-family:Verdana, Geneva, sans-serif;
}

body.interrupt-ads a {
	color: #000; text-decoration: none;
}

#ad-interruptus-header {
	width:75%; margin: 1% auto; padding:0;
	font-weight: bold;
}

#ad-interruptus-header #site-logo, #ad-interruptus-header #continue-link {
	width: 40%; display: inline-block;
}

#ad-interruptus-header #site-logo {
	left 0; font-size: 2em;
}

#ad-interruptus-header #continue-link {
	float: right; text-align:right; font-size: 1em;
}

#timer {
	width:85%; margin: 2% auto; padding:0;
	text-align:center;
}

#pmc-adm-ad-interruptus {
	width:90%; margin: 20px auto; padding:0;
	text-align:center; display: block;
}
</style>
<div  id="pmc-adm-interrupts-container" class="prestitial <?php echo esc_attr( $css_class ); ?>" >

	<div id="ad-interruptus-header">
		<a href="javascript:pmc_admanager.hide_interrupt_ads();"><div id="site-logo"><?php echo wp_kses_post( $site_logo ); ?></div></a>
		<div id="continue-link">
			<a href="javascript:pmc_admanager.hide_interrupt_ads();" class="skip-ad-close"></a>
			<a href="javascript:pmc_admanager.hide_interrupt_ads();">Continue to <?php echo wp_kses_post( $site_name ); ?></a>
			<a href="javascript:pmc_admanager.hide_interrupt_ads();" class="skip-ad-text">SKIP AD</a>
		</div>
	</div>


	<div id="timer">
		You will be redirected back to the site in <span id="pmc_ads_interrupts_timer"></span> seconds
		<script type="text/javascript" language="javascript">
			pmc_admanager.interrupt_timer();
		</script>
	</div>

	<div id="pmc-adm-ad-interruptus"><?php pmc_adm_render_ads( 'Prestitial' ); ?></div>
</div>
