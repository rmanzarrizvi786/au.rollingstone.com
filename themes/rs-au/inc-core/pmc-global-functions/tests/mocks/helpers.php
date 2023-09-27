<?php
/**
 * Mocks of helper methods only for unit testing
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-09-27
 */

if ( ! function_exists( 'jetpack_is_mobile' ) ) {

	function jetpack_is_mobile( $kind = 'any', $return_matched_agent = false ) {

		$pre = apply_filters( 'pre_jetpack_is_mobile', false, $kind, $return_matched_agent );

		return $pre;

	}

}

if ( ! class_exists( '\Jetpack_User_Agent_Info' ) ) {

	class Jetpack_User_Agent_Info {

		public static function is_tablet() : bool {
			return ( static::is_android_tablet() || static::is_ipad() );
		}

		public static function is_android_tablet() : bool {

			if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				return false;
			}

			$agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

			$pos_android      = strpos( $agent, 'android' );
			$pos_mobile       = strpos( $agent, 'mobile' );
			$post_android_app = strpos( $agent, 'wp-android' );

			if ( false !== $pos_android && false === $pos_mobile && false === $post_android_app ) {
				return true;
			}

			return false;

		}

		public static function is_ipad( $type = 'ipad-any' ) : bool {

			if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				return false;
			}

			$ua = strtolower( $_SERVER['HTTP_USER_AGENT'] );

			$is_ipad   = ( false !== strpos( $ua, 'ipad' ) );
			$is_safari = ( false !== strpos( $ua, 'safari' ) );

			if ( 'ipad-safari' === $type ) {
				return ( $is_ipad && $is_safari );
			} elseif ( 'ipad-not-safari' === $type ) {
				return ( $is_ipad && ! $is_safari );
			}

			return $is_ipad;

		}

		public static function is_bot() : bool {

			$ua = $_SERVER['HTTP_USER_AGENT'];

			if ( empty( $ua ) ) {
				return false;
			}

			$bot_agents = [
				'alexa', 'altavista', 'ask jeeves', 'attentio', 'baiduspider', 'bingbot', 'chtml generic', 'crawler', 'fastmobilecrawl',
				'feedfetcher-google', 'firefly', 'froogle', 'gigabot', 'googlebot', 'googlebot-mobile', 'heritrix', 'httrack', 'ia_archiver', 'irlbot',
				'iescholar', 'infoseek', 'jumpbot', 'linkcheck', 'lycos', 'mediapartners', 'mediobot', 'motionbot', 'msnbot', 'mshots', 'openbot',
				'pss-webkit-request', 'pythumbnail', 'scooter', 'slurp', 'snapbot', 'spider', 'taptubot', 'technoratisnoop',
				'teoma', 'twiceler', 'yahooseeker', 'yahooysmcm', 'yammybot', 'ahrefsbot', 'pingdom.com_bot', 'kraken', 'yandexbot',
				'twitterbot', 'tweetmemebot', 'openhosebot', 'queryseekerspider', 'linkdexbot', 'grokkit-crawler',
				'livelapbot', 'germcrawler', 'domaintunocrawler', 'grapeshotcrawler', 'cloudflare-alwaysonline',
			];

			foreach ( $bot_agents as $bot_agent ) {
				if ( false !== stripos( $ua, $bot_agent ) ) {
					return true;
				}
			}

			return false;

		}

	}

}

//EOF
