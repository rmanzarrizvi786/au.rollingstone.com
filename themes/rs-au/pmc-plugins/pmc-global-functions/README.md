pmc-global-functions
########################################

This plugin is used for common functions that will be used in different brands, please be careful when you are editing or creating a new feature in this plugin, we need to be sure that this not going to affect any brand that is using this plugin.


### Classes and Use

#### pmc-class-cookie.php

To avoid malicious cookie value that can be introduced by the end-user, we decided to create our own cookie class where you can set/get a cookie that is trustworthy.

We signed cookie value something like `original_cookie_value.hash_of_original_cookie_value` then whenever cookie data needs to be accessed, the original value is hashed and compared to the given hash, and if they match; the cookie has not been tampered with and it's contents are trustworthy. If they do not match, treat the cookie as maliciously altered and discard it.

In the `init` hook of WordPress we create a cache group using the `vary_cache_on_function` with all the valid cookies that belong to the `pmc cookies`.

### Security and Privacy Models

Each feature/work for this project requires an accompanying threat assessment prior to beginning the work—so that the assessment positively impacts the work. [Click here to learn more](https://confluence.pmcdev.io/x/WIlJAg).

[Threats and Attack Models](docs/threats-and-attack-models)