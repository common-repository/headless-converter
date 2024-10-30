=== Headless Converter ===
Contributors: attlii
Tags: headless, converter, json
Requires at least: 5.6
Tested up to: 5.9
Stable tag: 1.0.6
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Converts frontend to JSON response when request is done with certain conditions.

== Inspiration ==
After creating a bunch of headless Wordpress + Web app stacks, I wanted to find a standard and a all-round solution for fetching per page information for web applications inside Wordpress. Built-in rest api works fine in basic cases, but it doesn't support querying by path. Usually this meant that for each project developers would create a custom rest endpoint which would return expected content using content type and slug parameters.

Wordpress has few built-in functions to retrieve content by path, [url_to_postid](https://developer.wordpress.org/reference/functions/url_to_postid/) and [get_page_by_path](https://developer.wordpress.org/reference/functions/get_page_by_path/), but they don't seem to work with multilanguage plugins, taxonomy or archive pages, which means that WP doesn't have a reliable way to fetch content this way through rest api.

This plugin converts frontend to JSON which seems after above solution the best way to do things, with added layer of security through application passwords (Wordpress v5.6 feature) and a filter, which let's developers alter outgoing data.

== How to start using the plugin ==
- Install and activate this plugin in your environment
- Create application password for a user with administrator role

After above steps have been made, make a request to a page with added [Authorization header](https://en.wikipedia.org/wiki/Basic_access_authentication#Client_side). See data fetching examples at the end of this documentation for more help

== Frequently Asked Questions ==

= Application Password doesn't show up, even though I use Wordpress version 5.6 or newer. What do?  =

This seems to be a feature that is opt-in on some environments. Following hook enables it.

`add_filter( 'wp_is_application_passwords_available' , '__return_true' );`

=== Modifying the output ===
Plugin outputs current page's Post object or null. This can be modified using `headless-converter-modify-data`-filter. You can either modify passed in post object or do your own logic like in the example below.

`
/**
 * Modifies Headless Converter plugin's output.
 * 
 * @param WPPost|null $post - Current template's post object 
 */
function modify_headless_converter_output($post) {
  if(is_404()) {
    return "this block renders 404 page content";
  } else if(is_page()) {
    return "this block renders page post types content";
  } else if (is_singular('post')) {
    return "this block renders single post content";
  } else if(is_home()) {
    return "this block renders post archive";
  } else {
    return $post;
  }
}

add_filter('headless-converter-modify-data', 'modify_headless_converter_output');
`

== Data fetching examples ==

= Fetch = 
`
const username = "admin"
const password = "1111 1111 1111 1111 1111"
const url = "http://localhost:3000/?page_id=2"
const opts = {
  headers: {
     'Authorization': 'Basic ' + btoa(username + ":" + password) 
  }, 
}
fetch(url, opts).then(r => r.json()).then(console.log)
`

= Axios =
`
const axios = require("axios")

const username = "admin"
const password = "1111 1111 1111 1111 1111"
const url = "http://localhost:3000/?page_id=2"
const opts = {
  auth: {
    username,
    password
  }
}

axios(url, opts).then(r => r.data).then(console.log)
`

== Changelog ==

= 1.0.0 (2020-10-08): =
- Initial release

= 1.0.1 (2020-10-08): =
- Remove unnecessary stuff from the plugin folder

= 1.0.2 (2020-10-20): =
- Add FAQ about enabling application passwords

= 1.0.3 (2020-11-15): =
- Update and prune repository dependencies

= 1.0.4 (2020-11-15): =
- Run repository through a linter

= 1.0.5 (2020-11-16): =
- Fix documentation

= 1.0.6 (2021-01-28): =
- update tested up to version to 5.9

= 1.0.7 (2021-03-01): =
- fix issue when the plugin is accidentally included in the same environment twice

= 1.0.8 (2021-07-26): =
- Test and confirm that plugin works with PHP 8.1 and WP 6.3

