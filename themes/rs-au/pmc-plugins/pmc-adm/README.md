# PMC ADM Plugin #
This plugin is written to run Advertisements on all the PMC sites:

- Provide a UI in wp-admin ( wp-admin/?page=ad-manager ) to create, configure, update and delete Ads by various providers
- Run Out Of Page ads
- Run Interstitial( A full cover Ad that loads when home page is hit )  ads on the site. 
- Run Prestitial ( A full cover Ad that runs when an article page is hit ) ads on the site.
- We have ads from various Providers such as google-publisher, site-served (PMC in-house), Double Click, Adsense.
- We have feature to export the Ads in WXR format and import them into another wordpress site by uploading that WXR file from the admin.
- Ads can be placed in the widgets by using the PMC_Ads_Location_Widget.

### Conditions for the Ads ###

- Run Ads by priority. The priority is decided by using size + slot type + ad unit  => unique ads per slot, render first ads from highest priority (lower number). The lower priority number is considered of higher priority. If the size is the same, only the highest priority ad will render if both ads conditions are met. If the size is different, then both ads will render. @see pmc-plugins/pmc-adm/class-pmc-ads.php.line-606 - get_ads_to_render() 
- Allow custom conditions using class PMC_Ad_Conditions such as is_home(), is_single(), is_logged_in() etc for the ads to be rendered for those pages and those conditions only. @see class PMC_Ad_Conditions doc block "pmc-plugins/pmc-adm/class-pmc-ad-conditions.php" for more information.
- Run campaigns by having a start and end time to an ad to be set in the admin.
- Run ads based on dynamic zones by creating dynamic ad unit. @see class doc block to PMC_Ad_Dynamic_Zone pmc-plugins/pmc-adm/class-pmc-ad-dynamic-zone.php, that class has information on how the dynamic ad unit is created base on the drop down format. This drop down ad unit is configurable and extended via this class. It also allow different LOB to configure different format and register custom function to generate these dynamic ad unit.


### Theme Settings ###

In your theme you need the below code to setup the PMC Ad Manager

- Define the provider. Check if the provider is not already instantiated and then create one if not already done.

```
		
		// note: by default, pmc-adm will setup hostname target
		// To disable hostname targetting, use filter pmc_adm_hostname to override.
		pmc_adm_add_provider( new Google_Publisher_Provider( '3782' ) );
```
- Define the locations. We have to define the ad locations in the code which we can configure from the Admin. The locations have to be all predefined and need to have a provider. If no provider is specified then that Ad defaults to 'google-publisher' provider.

```
		// IMPORTANT: do not change location slug name.
		pmc_adm_add_locations( $locations = [
		'leaderboard' => [
			'title' => __('Top Leaderboard Ad', 'pmc-plugins'),
			'provider' => 'site-served', 
		],
		'mid-article' => __('Mid Article Ad', 'pmc-plugins'),
		'footer' => [
			'title' => __('Footer Ad', 'pmc-plugins'),
		]
	] );
```
- Define any custom conditions that we want to create for ad rendering.

```
		PMC_Ad_Conditions::get_instance()->register(
			'is_editorial',
			array( $this, 'is_editorial' ),
			array( 'term' )
		);
```

- We need to call pmc_adm_render_ads() and pass it the location for which we want the ad served in the theme HTML where we want to display ad. There are 4 parameters to that function, namely Ad Location ( unique location slug for the ad ) ,Ad Title ( Text title that needs to be rendered in HTML, Echo ( if we want to echo the ad out in the html or return the ad output and Provider ( The specific provider for which we want to fetch the ad ).

```
pmc_adm_render_ads( 'leaderboard' ); 

pmc_adm_render_ads( 'leaderboard', 'HomePage Leaderboard' );

$ad_html = pmc_adm_render_ads( 'leaderboard', 'HomePage Leaderboard', false );

pmc_adm_render_ads( 'leaderboard', 'HomePage Leaderboard', true, 'site-served' );

```
