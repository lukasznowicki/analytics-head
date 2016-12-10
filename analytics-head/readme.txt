=== Analytics Head ===

Tags: Google, Analytics, Script, Webmaster, Tools, Tracking, Code, Head, Header, Footer, Page, Section
Requires at least: 4.0
Tested up to: 4.7
Contributors: lukasznowicki
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZUE7KNWHW3CJ4
Stable tag: 1.6.0

This plugin adds tracking code for Google Analytics to your WordPress &lt;head&gt; section, so you can authorize your site in Google Webmaster Tools.

== Description ==

This plugin adds tracking code for Google Analytics to your WordPress site. Unlike other plugins, code is added to the &lt;head&gt; section, so you can authorize your site in Google Webmaster Tools.

There are many Google Analytics plugins for WordPress. I used a few of those myself and it worked well. The trouble began when I willed to use Google Webmaster's Tools.

It turned out that I can authenticate the ownership of the website using my Google Analytics account. Where's the catch? Google Webmaster's Tools expects that the code will be located at the &lt;head&gt; section and all the plugs have placed it at the very end of the page (apart from this case - very rightly).

Therefore, I created a plug-in called "Analytics Head", which places tracking code in the &lt;head&gt; section of the webpage. Of course you can put it in the footer at any moment, if you like.

== Installation ==

1. Upload `analytics-home` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Provide your Google ID in the Settings - Analytics Head section
1. That's all folks, have fun :)

== Requirements ==

This plugin requires WordPress <b>4.0</b> (never forget to update your WP installation!) and <b>PHP 5.3</b> installed on your server. It is pretty uncommon to offer previous versions of the PHP, however I must note that.

== Frequently Asked Questions ==

= How do I get Google Tracking code? =
Register at http://www.google.com/analytics/, add your site and then Google will provide you valid Google Analytics code (something like UA-xxxxxxxx-y)

= Why do I need this code in the head section? Google told me that the code should be put just before </html> tag =
It is for Google Webmaster Tools users. You can prove that your site is owned by you using Google code. However, in that case, Google will require that the code was placed in the "head" section of the site.

= Do I need to know how to use html, php or similar techniques? =
No.

= What if I know php? =

You may use following actions:

* pp_google_analytics_head_before
* pp_google_analytics_head_after

And one filter:

* pp_google_analytics_head_output

Please note that those features are disabled by default. If you want to use them, you must turn it on using plugin options page.

= Is it free? =
Yes, it is under GPLv2 (and later) licence. However, you can donate me a few dollars if it makes you feel good. I certainly have nothing against it.

== Examples ==

Here are a few examples for developers

= Actions =

`add_action( 'pp_google_analytics_head_before', function() {
  	echo '' . PHP_EOL;
	$of_course = 'I do not have to print here, I may do something else!';
} );`

`add_action( 'pp_google_analytics_head_after', function() {
	$i_just = 'printed google code...';
} );`

= Filter =

`add_filter( 'pp_google_analytics_head_output', function( $s ) {
	return str_replace( 'Google Analytics', 'Doodle Analytics', $s );
} );`

== Changelog ==

= 1.6.0 =
* Release date: 2016-11-19
* Status: Stable
* Compatibility: 4.7 and previous
* Compatibility tested up with WordPress 4.7

= 1.5.4.0 =
* Release date: 2016-11-08
* Status: Stable
* Compatibility: 4.x and previous
* Compatibility tested up with WordPress 4.6.1
* Better options handling and versioning for builds

= 1.5.3 =
* Release date: 2016-11-08
* Status: Stable
* Compatibility: 4.x and previous
* Compatibility tested up with WordPress 4.6.1
* Better options handling

= 1.5.2 =
* Release date: 2016-11-08
* Status: Stable
* Compatibility: 4.x and previous
* Compatibility tested up with WordPress 4.6.1
* Getting rid of svn tags mess

= 1.5.1 =
* Release date: 2016-11-08
* Status: Stable
* Compatibility: 4.x and previous
* Compatibility tested up with WordPress 4.6.1
* We added missing method

= 1.5.0 =
* Release date: 2016-11-08
* Status: Stable
* Compatibility: 4.x and previous
* Compatibility tested up with WordPress 4.6.1
* This version is tested up with latest WordPress 4.6.1,
* we changed donate link,
* we get rid of previous riddiculs versioning,
* fixed some typos and updated translation.

= 1.4.4.1 =
* Release date: 2016-01-25
* Status: Stable
* Compatibility: 4.x and previous
* Compatibility tested up with WordPress 4.4.1
* Change way of versioning, instead of 0.MAIN.SUB pattern I will use MAIN.WPVERSION pattern - so now you know that subversion 4.4.1 is not a coincidence
* Added two actions (pp_google_analytics_head_before, pp_google_analytics_head_after) and one filter (pp_google_analytics_head_output) - disabled by default, you may enable it in the admin panel.
* Dropped 0.4.1 and previous settings check - those versions wasn't available in WordPress repository
* Version 0.6.0 loses 0.5.x settings. Now, if there are no 0.6 settings (blank field) we will use 0.5 settings if available

= 0.6.0 =
* Release date: 2015-10-01
* Status: Stable
* Compatibility: 4.x and previous
* Now you may hide your tracking code for all logged-in users
* You may move your tracking code into footer instead of &lt;head&gt; section
* This version requires at least PHP5.3

= 0.5.6 =
* Release date: 2015-05-17
* Status: Stable
* Compatibility: 4.x and previous
* New Google Analytics code
* Changed after_setup_theme hook with wp_loaded hook

= 0.5.5 =
* Release date: 2015-05-17
* Status: Stable
* Compatibility: 4.x and previous
* Fixed problem with load_text_domain

= 0.5.4 =
* Release date: 2013-07-22
* Status: Stable
* Compatibility: 3.x and previous
* Removed UTF leading info which can sometimes trigger 'Headers already sent' error.

= 0.5.3 =
* Release date: 2013-07-19
* Status: Stable
* Compatibility: 3.x and previous
* On some installations, even after providing Google ID, you can see message to provide it.
* Some minor bug-fixes and typo fixes.

= 0.5.2 =
* Release date: 2011-06-18
* Status: Stable
* Compatibility: 3.x and previous
* On some machines, plugin can fire "wordpress Fatal error" - like many other plugins as I read on the net. It is fixed now.

= 0.5.1 =
* Release date: 2011-06-14
* Status: Stable
* Compatibility: 3.x and previous
* Rewritten completely using OOP
* Some minor bug-fixes
* Removed trashy machine translations except polish (it isn't machine)

= 0.4.1	=
* Release date: 2011-06-11)
* Status: Release Candidate 1
* Added ability to change the language
* Added polish/german/french translations
* Changed the way of saving the settings

= 0.3 =
* Release date: 2011-05-20)
* Status: Beta
* Rewritten completely just for fun
* Some interface changes, no new functionality added

= 0.2 =
* Release date: 2011-04-09)
* Status: Alpha
* Added disabling for logged on admins

= 0.1 =
* Release date: 2011-04-08)
* Status: Prealpha