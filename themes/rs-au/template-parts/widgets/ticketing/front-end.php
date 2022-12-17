<?php
echo wp_kses_post( $args['before_widget'] );
?>

	<div class="c-ticketing" id="rs-ticketing" data-ticketing>
		<div class="c-ticketing__heading t-align--center c-ticketing__loader">
			<h4 class="c-ticketing__heading--title t-bold"><?php esc_html_e( 'Trending Concerts', 'pmc-rollingstone' ); ?></h4>
		</div>
		<h5 class="c-ticketing__location t-align--center c-ticketing__loader">
			<div class="c-ticketing__loader--loading">
			</div>
			<span></span>
			<a href="https://www.vividseats.com/Search.action" target="_blank" rel="noopener"></a>
		</h5>
		<ul class="c-ticketing__list">
			<li class="c-ticketing__list--item c-ticketing__loader">
				<div class="c-ticketing__loader--loading">
				</div>
				<div class="c-ticketing__date t-align--center">
					<a href="#" target="_blank" rel="noopener">
						<div class="c-ticketing__date--day">
							&nbsp;
						</div>
						<div class="c-ticketing__date--date t-bold">
							&nbsp;
						</div>
						<div class="c-ticketing__date--time">
							&nbsp;
						</div>
					</a>
				</div>
				<div class="c-ticketing__details">
					<div class="c-ticketing__details--event">
						<a href="#" target="_blank" rel="noopener" class="t-bold">&nbsp;</a>
					</div>
					<div class="c-ticketing__details--location">
						&nbsp;
					</div>
					<div class="c-ticketing__details--purchase">
						<a href="#" target="_blank" rel="noopener"><?php esc_html_e( 'Get Tickets', 'pmc-rollingstone' ); ?></a>
					</div>
				</div>
			</li>
			<li class="c-ticketing__list--item c-ticketing__loader">
				<div class="c-ticketing__loader--loading">
				</div>
				<div class="c-ticketing__date t-align--center">
					<a href="#" target="_blank" rel="noopener">
						<div class="c-ticketing__date--day">
							&nbsp;
						</div>
						<div class="c-ticketing__date--date t-bold">
							&nbsp;
						</div>
						<div class="c-ticketing__date--time">
							&nbsp;
						</div>
					</a>
				</div>
				<div class="c-ticketing__details">
					<div class="c-ticketing__details--event">
						<a href="#" target="_blank" rel="noopener" class="t-bold">&nbsp;</a>
					</div>
					<div class="c-ticketing__details--location">
						&nbsp;
					</div>
					<div class="c-ticketing__details--purchase">
						<a href="#" target="_blank" rel="noopener"><?php esc_html_e( 'Get Tickets', 'pmc-rollingstone' ); ?></a>
					</div>
				</div>
			</li>
			<li class="c-ticketing__list--item c-ticketing__loader">
				<div class="c-ticketing__loader--loading">
				</div>
				<div class="c-ticketing__date t-align--center">
					<a href="#" target="_blank" rel="noopener">
						<div class="c-ticketing__date--day">
							&nbsp;
						</div>
						<div class="c-ticketing__date--date t-bold">
							&nbsp;
						</div>
						<div class="c-ticketing__date--time">
							&nbsp;
						</div>
					</a>
				</div>
				<div class="c-ticketing__details">
					<div class="c-ticketing__details--event">
						<a href="#" target="_blank" rel="noopener" class="t-bold">&nbsp;</a>
					</div>
					<div class="c-ticketing__details--location">
						&nbsp;
					</div>
					<div class="c-ticketing__details--purchase">
						<a href="#" target="_blank" rel="noopener"><?php esc_html_e( 'Get Tickets', 'pmc-rollingstone' ); ?></a>
					</div>
				</div>
			</li>
		</ul>
		<div class="c-ticketing__footer t-align--center">
			<div class="c-ticketing__footer--more-events">
				<a href="https://www.vividseats.com/concerts/" target="_blank" rel="noopener" class="t-bold"><?php esc_html_e( 'Browse More Events', 'pmc-rollingstone' ); ?></a>
			</div>
			<div class="c-ticketing__footer--powered-by">
				<span><?php esc_html_e( 'Powered by', 'pmc-rollingstone' ); ?></span>
				<a href="https://vividseats.com/" target="_blank" rel="noopener">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/build/images/_dev/vivid-seats.png' ); ?>" width="86" />
				</a>
			</div>
		</div>
	</div>

<?php
echo wp_kses_post( $args['after_widget'] );
