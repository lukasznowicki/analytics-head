# Analytics Head

This plugin adds tracking code for Google Analytics to your WordPress &lt;head> section, so you can authorize your site in Google Webmaster Tools.

Current version: `1.6.7`

## More informations

This plugin adds tracking code for Google Analytics to your WordPress site. Unlike other plugins, code is added to the &lt;head&gt; section, so you can authorize your site in Google Webmaster Tools.

There are many Google Analytics plugins for WordPress. I used a few of those myself and it worked well. The trouble began when I willed to use Google Webmaster's Tools.

It turned out that I can authenticate the ownership of the website using my Google Analytics account. Where's the catch? Google Webmaster's Tools expects that the code will be located at the &lt;head&gt; section and all the plugs have placed it at the very end of the page (apart from this case - very rightly).

Therefore, I created a plug-in called "Analytics Head", which places tracking code in the &lt;head&gt; section of the webpage. Of course you can put it in the footer at any moment, if you like.

## For developers

You may use following actions:

*pp_google_analytics_head_before*
*pp_google_analytics_head_after*

And one filter:

*pp_google_analytics_head_output*

Please note that those features are disabled by default. If you want to use them, you must turn it on using plugin options page.

## Authors
* Łukasz Nowicki <https://lukasznowicki.info/>
* [Kurs programowania WordPress](https://wpkurs.pl/)
* [Strony internetowe, aplikacje](https://phylax.pl/)

## License
Copyright 2016-2020 phylax.pl Łukasz Nowicki
<https://phylax.pl/>

Licensed under the GPLv2 or later: <http://www.gnu.org/licenses/gpl-2.0.html>
