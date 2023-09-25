<?php

/**
 * Newsletter Signup - Single.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<!-- <div class="rs-subscribe-footer">
	<a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" rel="noopener">Want more in-depth culture content? <span>Subscribe</span> to <strong>Rolling Stone magazine</strong> for deep reporting, unforgettable interviews, and criticism you can trust.</a>
</div> -->

<a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" rel="noopener" class="d-flex flex-column flex-md-row align-items-start rs-subscribe-footer">
	<div class="d-flex" style="flex-wrap: nowrap;">
		<?php
		$mag_cover = get_option('tbm_next_issue_cover');
		if (isset($mag_cover) && '' != $mag_cover) { ?>
			<div class="flex-fill img-wrap"><img src="<?php echo  $mag_cover; ?>" width="100"></div>
		<?php }
		?>
		<div style="margin: auto .5rem;">Get unlimited access to the coverage that shapes our culture.
			<div class="d-none d-md-block mt-1"><span class="subscribe">Subscribe</span> to <strong>Rolling Stone magazine</strong></div>
		</div>
	</div>
	<div class="d-block d-md-none mt-3 w-100"><span class="subscribe">Subscribe</span> to <strong>Rolling Stone magazine</strong></div>
</a>