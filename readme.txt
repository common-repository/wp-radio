=== WP Radio - Worldwide Online Radio Stations Directory for WordPress ===
Contributors: wpmilitary, princeahmed
Tags: radio, html5 radio player, sticky shoutcast player,  wordpress radio, directory, shoutcast,  icecast, icecast players, internet radio, streaming radio widget,html5 radio player, radio forge, kast, html5 audio player, music player, radio station
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 3.1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Radio is a worldwide online radio stations directory plugin for WordPress. You can easily create a full-featured online radio directory website with the WP Radio plugin. WP Radio has pre-included 52000+ online radio stations from around 190+ countries all over the world.


== Description ==
WP Radio is a worldwide online radio stations directory plugin for WordPress. You can easily create a full-featured online radio directory website with the WP Radio plugin. WP Radio has pre-included 52000+ online radio stations from around 190+ countries all over the world.

You can also add unlimited radio stations (Icecast, Shoutcast, Radionomy, Radiojar, RadioCo, .m3u8, etc) of your own.


== Video Overview ==
https://youtu.be/AnJtcDCEWDk

 👁️ [View Demo](https://webradiodirectory.com/) | 🚀 [Get PRO](https://wpmilitary.com/wp-radio/)


== WP RADIO FEATURES ==
* **Radio Stations Listing** - All the radio stations can be listed on any page/post using the [wp_radio_listing] shortcode.

* **Radio Stations Importer** - WP Radio included 52000+ online radio stations from around 190+ countries all over the world. You can import these radio stations easily from the Import Stations page. In the free version, you can only import the stations from the first 30 countries of the available country list.

* **Add Unlimited Radio Stations** - You can add unlimited radio stations with radio station logo, genres, description, contacts, and other additional information.

* **Sticky Player** - WP Radio has a full-width sticky player that will be displayed on the bottom of every page of your website.

* **Stations Search** - WP Radio has advanced stations search & filtering options. Users can easily search & filter the radio stations that they looking for.

* **State/ City Region List** - On the station country archive page, all the available states and cities of the country will be listed. Users can easily browse the stations by filtering the state and city regions.

* **Current Song Title** - The radio station player can display the currently playing song title.

* **Mobile Media Notification** - While playing a radio station, users can see currently playing station information and can play/ pause the radio player from the mobile notification bar.

* **m3u8 Extension Supported** - WP Radio can play the .m3u8 stream links.

* **Gutenberg Blocks** - WP Radio provides 3 Gutenberg blocks: Radio Player, Radio Station, and Country List.

* **Elementor Widgets** - WP Radio also provides 3 Elementor widgets: Radio Player, Radio Station, and Country List.


== WP RADIO PRO FEATURES ==
* **Import All Stations** - In the PRO version you can import all the  included radio stations (52000+) from around 190+ countries all over the world.

* **Country Based Listing** - User's IP based country stations list can be set from the settings. Visitors will see their country’s stations at first in the listing page. Then users can chang the country to view other stations.

* **Shortcode Player** - The radio station player can be placed anywhere using the `[wp_radio_player] `shortcode. You can also display the player on any post/ page using the gutenberg block & elementor widget.

* **Popup Player** - WP Radio also has the popup player feature. Users can play the radio stations in a new popup window. This will increase the radio listening experience without page reload interrupts.

* **Multiple Listing Layouts** - You can display the stations in list view and grid view.

* **Recently Played Tracks Playlist** - On the single radio station page recently played tracks playlist will be displayed.

* **Stations Play Statistics** - On the statistics page you will get an overview of the stations play counts per day and the number total listeners who played the stations and also the most played stations list.

* **Admin Dashboard Chart Widget** - There is also an admin dashboard widget available for the stations play statistics, to get a quick overview of the stations play statistics.

* **Statistics Email Reporting** - You can receive a daily/weekly/monthly email report with the stations play statistics and the list of the top played stations.

* **Country List** - You can display all the radio stations countries using the [wp_radio_country_list] shortcode. You also can display the country list in any post/ page using the gutenberg block & elementor widget.

* **Color Customizations** - You can customize the radio stations listing and player background and text colors from the color settings of the plugin. You also can use gradient color for the station listing and player.

* **Trending Stations** - You can display the trending stations listing in any page/ post use the code [wp_radio_trending] shortcode.

* **Featured Stations** - You can display the featured stations listing in any page/ post use the code [wp_radio_featured] shortcode.

* **AJAX Loading** - You can enable the AJAX loading from the general settings of the plugin. If the ajax loading is on users can browse the stations without any page reload.



== SHORT CODES: ==

This plugin provides multiple shortcodes for displaying various components & sections of the plugin on any post/ page in a very flexible way.
WP Radio provides the following shortcodes:

 1.  `[wp_radio_listing]` - Use this shortcode in a page for listing the radio stations. This shortcode supports **country** && **genre** attributes where you can pass comma separated country code and genre.
     **Example:** `[wp_radio_listing country="us, ru, bd" genre="rock,news"]`

 2.  `[wp_radio_player]` - Use this shortcode anywhere for displaying the radio player. This shortcode supports the **id** attribute where you can the id of a radio station as the default station of the player.
     **Example:** `[wp_radio_player id="11"]`

 3.  `[wp_radio_search_form]` - Use this shortcode anywhere for displaying the station search form. This shortcode supports two attributes **(country_filter and genre_filter)**. You have to pass true/ false value to the attributes for showing/ hiding the country and genre filter in the search form.
     **Example:** `[wp_radio_search_form country_filter="false" genre_filter="false"]`

 4.  `[wp_radio_featured]` - Use this shortcode for displaying the featured stations of a country. This shortcode support 2 attributes **(count and country)**.
     **Example:** `[wp_radio_featured count="10" country="us" ]`

 5.  `[wp_radio_trending]` - Use this shortcode for displaying the trending stations of a country. This shortcode also supports 2 attributes, the same as the featured shortcode.
     **Example:** `[wp_radio_trending count="10" country="us"]`

 6.  `[wp_radio_country_list]` - To display all the imported station countries, use the `[wp_radio_country_list]` shortcode. This shortcode supports two optional attributes, those are style and columns.
     **Example:** `[wp_radio_country_list style="horizontal" columns="3"]`

 7.  `[wp_radio_station]` - To display a single radio station with a play button use the `[wp_radio_station]` shortcode. This shortcode required an id attribute. This shortcode required an id attribute, the ID of the station.
      **Example:** `[wp_radio_station id="55"]`



== INSTALLATION AND USAGE: ==

1. **Add Stations** - After you have successfully activated the plugin, the next step is to add radio stations. You can add the radio stations in two ways. 1. add radio stations manually and 2. Import the pre-included radio stations with the plugin.

2. **Display Radio Stations** - After adding the radio stations, you have to display the radio stations on your site. While you activated the plugin a new page titled "Live Radio" is automatically created. On this page, all the radio stations are displayed. You also can display the radio stations on any post/ page by using the shortcode `[wp_radio_listing]`. From the radio stations listing users can play the radio stations by clicking on the play button.


== WP RADIO PROXY PLAYER ADDON: ==

As of Google Chrome browser updates with some restrictions, mixed content is no longer played in google chrome. If the stream link is without SSL and your website is with SSL, the stream link will no longer be played. Other browsers follow the example of Google.
Read more about this: https://blog.chromium.org/2019/10/no-more-mixed-messages-about-https.html. In case you have a HTTPS website you’ll need to use a HTTPS radio link.

This issue can be fixed by using the **WP Radio Proxy Player** addon. Using the Proxy Player addon most of the HTTP radio stream links can be played even your site is loaded with HTTPS.

**WP Radio Proxy Player Addon Features:**

*  ️ Fix Browsers mixed-content/ CORS issues.
*  ️ Fix station metadata not showing issue.
*  ️ Play HTTP radio streams on HTTPS website.
*  ️ Play most of the available radio stations than before.

 🚀 [Get WP Radio Proxy Player Addon](https://checkout.freemius.com/mode/dialog/plugin/7628/plan/12495/)



== WP RADIO USER FRONTEND ADDON: ==

**WP Radio User Frontend** let engage your visitors to your website by adding powerful features for the users such as adding a login/ registration form, review submission, favorite stations, station submission, report submission etc.

**WP Radio User Frontend Addon Features:**

* ️ **User Account Page** - Let the users register, login, and customize their profile.
*  **Station Sharing** - Let the users to share the station on Facebook, Twitter, WhatsApp and also embed the radio player.
* ️ **Rating and Reviews** - Let the users add rating and review for the stations
* ️ **Favorite Stations** - Let the users create their own favorite list of the station and able to add/ remove a station to the favorite list
* ️ **Station Submission** - Let the users request to add their own radio station to your website.
* ️ **Report Submission** - Let the users report if any station doesn't play.
* ️ **Enable Comment** - Enable Comment on the station page.
* ️ **User Favorites Shortcode** - `[wp_radio_user_favorites]` Use this shortcode to display the favorite stations of the user.

🚀 [Get WP Radio User Frontend Addon](https://checkout.freemius.com/mode/dialog/plugin/4907/plan/8106/)


== WP RADIO ADS PLAYER ADDON: ==

Monetize your radio directory website by playing custom mic-drops, stringers and audio advertisements. You can set custom audio ads for your radio stations by filtering specific radio stations and countries.
The ads will be played before the radio stream is play, when users click to any station play button and also can be played in the middle of the radio station playback after a specific time interval.
You can also set the audio ad duration of how many seconds the ad will play and also control how often the add will play.
You can also set the expiration date of the audio ads and also set how many times the ads can be played.

**WP Radio Ads Player Addon Features:**

* **Pre-roll & Mid-roll Ads** - Ads can be played before and middle of the radio station playback.
* **Custom Interval** - You can control how often the pre-roll and mid-roll ads will be played.
* **Custom Audio Ads** - You can play any audio file or URL as ad at the start of any station play..
* **Custom Ad Duration** - You can set the ad duration, how long the add will be played..
* **Countries Filtering** - You can play any ad for a specific countries..
* **Stations Filtering** - You can play any ad for a specific stations.
* **Ad Stats Email Report** - Get weekly ad stats email report to the ad publisher email.
* **Custom Play Rules** - Custom expire date for the ad and maximum play count of how many times the ad can be played can be set.

🚀 [Get WP Radio Ads Player Addon](https://checkout.freemius.com/mode/dialog/plugin/8169/plan/13793/)


== WP RADIO IMAGE IMPORT ADDON: ==
By default, all the imported station's images are loaded from a 3rd-party server website https://webradiodirectory.com.
Sometimes the images may take a while to load from the 3rd-party server website and can slow down your website a bit.

Using the WP Radio Image Importer addon, you can import all the imported station's images to your own server from the different server.
This will increase your website's performance and reduce the time it takes to load the images.

**WP Radio Image Importer Addon Features:**

* Import the radio station logo images from 3rd-party server to your own server.
* It will increase your website performances and faster.
* It will increase your seo rank.
* No dependency to other servers.

🚀 [Get Image Import Addon](https://checkout.freemius.com/mode/dialog/plugin/6807/plan/11092/)

== Ajax Press Plugin: ==
If you need to load your site without any page reloads and faster, then Ajax Press is the right tool for you. Your site will appear faster and more impressively using the Ajax Press plugin.

It allows you to completely transform the user experience of standard websites to make users feel like they are browsing an single page application.

Ajax Press plugin let the users to play the radio stations or any sticky player musics without any interruptions. It means if user navigate to another page, the player will continue playing the same music without any stop. This is a great feature for any radio station or any music player.

**Ajax Press Plugin Features:**

* **Ajax Page Load** -  Loads your site using AJAX without any page refresh.
* **Ajax Search** -  Users can search your site without any page reload.
* **Ajax Comments** -  Users can post comments on your site without any page reloads.
* **Ajaxify Forms** -  You can make any form ajaxify on your site that has an internal action link.
* **Page Transition Animation** -  Multiple page transition animation is available to add a more attractive page switching mode.
* **Preloader** -  Multiple preloader available to show before the ajax page is loaded.
* **Custom JavaScripts** -  You can execute custom JavaScripts, after every ajax page is loaded.

🚀 [Get Ajax Press Plugin](https://wordpress.org/plugins/ajax-press/)


== Compatibility ==

 WP Radio has no dependency on any other plugin or theme. You can use the WP Radio plugin with any theme.

 You should at least have PHP version – 5.6 for the smooth operation of this state-of-the-art plugin. We tested this plugin thoroughly to make sure it operates seamlessly under every situation. We did not detect any problem or conflict during our test. Still, we are open to issue as we understand that WordPress is a vast ecosystem and anything can happen.

 == NOTES: ==
Notes for IOS/Android (restrictions imposed by Apple/Google):

1. The autoplay will not work because IOS/Android disables autoplay feature, and it can’t be controlled from JS
2. Volume controllers will not work on IOS/Android. You’ll have to adjust the volume with physical buttons of the mobile
   device.
3. ShoutCast version below 2.0 doesn’t function on iOS 11. Please update ShoutCast server to at least v2.0
4. Icecast 2.4 kh4/kh5 do not function on iOS 11. Older versions (2.3.3 KH11) and standard Icecast 2.4.1 do function
   correctly. Please update IceCast server to the latest version.

Note for Safari, Chrome & Firefox (restrictions imposed by Apple & Google):

1. Starting with Safari 11, Chrome 66 and Firefox 66 the autoplay will not work because Apple & Google disabled autoplay
   feature, and it can’t be controlled from JS.
2. Chrome no longer accepts mixed requests. Please check this
   link: [https://blog.chromium.org/2019/10/no-more-mixed-messages-about-https.html](https://blog.chromium.org/2019/10/no-more-mixed-messages-about-https.html). In case you have a HTTPS website
   you’ll need to use an HTTPS radio link.



== 🔥 WHAT’S NEXT ==
If you like this plugin, then consider checking out our other projects:

* [Ajax Press  - Easily Enable Fast Ajax Navigation](https://wordpress.org/plugins/ajax-press/) - If you need to load your site faster and without any page reloads, then Ajax Press is the right tool for you. Your site will appear faster and more impressively using the Ajax Press plugin.

* [Radio Player – Live Shoutcast, Icecast and Audio Stream Player for WordPress](https://wordpress.org/plugins/radio-player) - A simple, easy-to-use, user-friendly and fully customizable web radio player for WordPress. You can play any live mp3, iceCast and Shoutcast stream in your WordPress website using shortcode, gutenberg block, elementor widget, sidebar widget, full-width sticky and popup player.

* [Integrate Google Drive - Complete Google Drive Cloud Solution for WordPress](https://wordpress.org/plugins/integrate-google-drive) - Share your Google Drive cloud files into your site very fast and easily. You can browse, manage, embed, display, upload, download, search, play, share your Google Drive files directly into your website without any hassle and coding.

* [Podcast Box - Worldwide Podcast Directory Player](https://wordpress.org/plugins/podcast-box) - Podcast Box is all in one solution that provides you an easy way to show and play your podcast episodes. You can also make a worldwide podcasts directory website of 5000+ podcasts included from 70+ countries.


 == Frequently Asked Questions ==

= When I clicked play, there is no sound? =

Most of the radio station stream links are HTTP (Unsecured) that can't be played on HTTPS (Secured) website because of browser mixed-content restrictions.
Modern browsers no longer accepts mixed requests.
Please check this link:
[https://web.dev/what-is-mixed-content](https://web.dev/what-is-mixed-content)


= How can I play the HTTP radio stream link on my HTTPS website? =
You have to use the WP Radio Proxy Player addon to remove the browser mixed-content restrictions.
That means you can play HTTP radio stream link on HTTPS website by using a cors-proxy system by using the **WP Radio Proxy Player** Addon.

= Can I add my own or any new radio station to play? =
Yes, You can add unlimited new radio station very easily.
For adding a new radio station you need to click the Add New Station submenu under the Radio Stations main menu.

= Station metadata (song title) not showing? =
Most of the shared hosting server blocks some URL port.
that's why if your site loaded on the shared hosting server then the stream metadata couldn't be loaded.

But, the issue can be fixed by the **WP Radio Proxy Player** Addon.

= Is this plugin is optimized to get the Google Adsense approval? =
Yes, this plugin is strongly optimized for getting google adsense approval.
Many WP Radio plugin users are displaying adsense ads on their website.
Even the demo site [https://webradiodirectory.com](https://webradiodirectory.com) got adsense approval and displaying adsense ads.

= Why some stations are not playing? =
* First check your site URL. Is your site loaded over http or https. If loaded  over http then the radio stream link with http can not be played because of the browser mix-content issue.

* There is some station, which can't be played for any reason. Likes: If the station has been closed.

* It is important to note that, all the channels might not work for you all the time. Because there are some radio channels that stop streaming after a certain time of the day. So, please if you find a channel not working; try again later. After a couple of hours, you should find that station working. The streaming URL has been changed etc. You can simply delete those stations.

= How Can I group stations by country and Genre? =
Use `[wp_radio_listing]` shortcode. where you can pass comma separated countries and genres.
Example: `[wp_radio_listing country="us, ru, bd" genre="rock,news"]`

= How do I translate some words into my language? =
https://youtu.be/LCdET5h7iqY

= Can I monetize my site by playing custom mic-drops, stinger and advertisements? =
Yes, you can monetize your site using the WP Radio Ads Player addon.
WP Radio ads player lets you monetize your radio website by playing audio ads and you can also display any ads in the popup player.

It let you play custom mic drops, stringer, and audio advertisements at the start of radio station play.

You can create and play unlimited ads and set them to be played by filtering specific counties or specific stations. You can also control how often the users will listen to the ads.

= How can I display ads in the radio player? =
You need the WP Radio Ads Player addon to display the banner ads below the popup player.
You have to set the player type to popup from the player settings and needs to enter the the ad codes in the ads settings.

= How can I set the radio player fixed to the footer? =
To set the radio player fixed to the footer you need to set the player type to Play on-page from the
Radio Station > Settings > Player settings > Player Type > Play on page

= How I enable comment on the station page? =
You have to activate the **WP Radio User Frontend** addon to show the comment form on the station page.
After activating the WP Radio User Frontend addon you will get the option **Enable Comment** in the general settings. You need to enable the Enable Comment option.

= Do the plugin has any refund policy for the PRO version? =
No, There is no refund policy available for the PRO version of this plugin.

= Do the plugin has any trial period for the PRO version? =
No, There is no trial period available for the PRO version of this plugin.


== Screenshots ==

1. Radio Stations Listing
2. Radio Stations Importer
3. Add Unlimited Radio Stations
4. Sticky Player
5. Stations Search
6. State/ City Region List
7. Mobile Media Notification
8. Shortcode Player
9. Popup Player
10. Recently Played Tracks Playlist
11. Stations Play Statistics
12. Admin Dashboard Chart Widget
13. Country List
14. Color Customizations
15. Shortcodes
16. Gutenberg Blocks
17. Elementor Widgets


== Changelog ==

= 3.1.9 =
* Fix: Fixed station playlist.
* Fix: Fixed play statistics listener count.
* Fix: Fixed per_page option not working.
* Fix: Fixed embed player scroll issue.
* Fix: Fixed radio player gutenberg block and elementor widget alignment issue.

= 3.1.8 =
* New: Added [wp_radio_search_form] shortcode.
* New: Added station search gutenberg block.
* New: Added station search elementor widget.
* New: Added sticky player toggle button.
* Fix: Fixed shortcode play button not playing issue.
* Fix: Fixed PHP fatal error.
* Fix: Fixed media notification pause/ stop issue.
* Fix: Fixed volume slider.
* Update: Improved the country list css design.
* Update: Updated single page layout.

= 3.1.7 =
* New: Added popup button hide/ show settings.
* Improvement: Improved radio player design.
* Fix: Fixed trending listing.
* Remove: Removed the visualizer animation on the player.


= 3.1.6 =
* New: Added Ajax Press plugin compatibility.
* Fix: Fixed country based listing.
* Fix: Fixed quick edit.
* Fix: Fixed listing sort order.
* Remove: Removed the ajax delay loading.

= 3.1.5 =
* Fix: Fixed statistics page.
* Fix: Fixed ``[wp_radio_trending]`` shortcode.
* Fix: Fixed country based listing.

= 3.1.4 =
* New: Added new WP Radio category in block editor.
* New: Added new WP Radio category in elementor.
* New: Add Country List elementor widget.
* New: Add alignment control to the gutenberg radio player block.
* New: Added show/hide control for station description and genre for the radio station gutenberg editor block.
* New: Added show/hide control for station description and genre for the radio station elementor widget.
* New: Added mobile media notification.
* New: Added gradient color supports for the radio station listing and player.
* New: Added radio player statistics email reporting.
* Improvement: Updated the import stations page.
* Improvement: Updated the settings page.
* Fix: Fixed Gutenberg Radio Blocks.
* Remove: Removed the custom permalinks structures for the radio stations.

= 3.1.3 =
* New: Added Enable/ disable statistics option
* Fix: Fixed multiple listing issue on same page
* Removed: Remove enable-popup from station edit page
* Enhancement: Improved radio station edit meta-box, settings page and get started page

= 3.1.2 =
* New: Added country list gutenberg block
* Fix: Fixed station listing scrolling issue.
* Fix: Fixed station metadata not showing issue.

= 3.1.1 =
* New: Added proxy settings in the settings page (WP Radio Proxy Player).
* Fix: Fixed .m3u8 extension stream play

= 3.1.0 =
* New: Added listing loading skeleton.
* Fix: Fixed listing json parse issue.

= 3.0.9 =
* New: Added playlist section to the single radio station page
* Improvement: Loads radio stations pages faster than before
* Improvement: Updated the radio player with eye catching modern design
* Improvement: Improved the radio station listing and single page design
* Improvement: Browse and play multiple radio stations without reloading the page
* Remove: Removed search style 2

= 3.0.8 =
* Fix: Added WordPress 8 compatibility.
* Fix: Fixed stations pagination error

= 3.0.7 =
* New: Added recommended plugins page
* New: Added slogan to the radio player
* Fix: PHP warning message
* Fix: Fixed player CSS
* Fix: Fixed IP based listing

= 3.0.6 =
* New: Added OGG/Vorbis format stream song title supports
* Fix: Fixed Podcast Box plugin compatibility

= 3.0.5 =
* New: Added stations search info (total stations, country) and change country option to listing top.
* New: Added search form to the country list for searching the countries.
* New: Added station slogan in the mobile listing.
* New: Added station individual popup option to the meta box.
* New: Added settings to enable/ disable visitor's country based station listing.
* New: Added listing sort/order settings.
* Improvement: Make station search form ajaxify.

= 3.0.4 =
* New: Added aria-label attribute to all the buttons
* New: Added wp_radio_visitors table to save the visitor ip & country_code records
* New: Added listing show per page  & listing order selection
* New: Added recent played track playlist
* Improvement: Added custom page input option to the pagination

= 3.0.3 =
* New: Added Next/ Previous button hide option in the player.
* New: Added show/ hide option for displaying the station genres in the listing.
* New: Added post ID column in the stations post table.
* New: Added ads player addon documentation.
* Improvement: Added genre filter in the search form.
* Improvement: Tweak station listing, player and search design and colors.

= 3.0.2 =
* New: Added station slogan field
* New: Added Dashboard Play Chart Widget
* New: Added google rich snippest for the radio stations
* Improvement: Updated the listing pagination to Ajax pagination

= 3.0.1 =
* Fix: Fixed country selection not working
* Fixed: Fixed phone number meta saving

= 3.0.0 =
* New: Added updated radio stations database
* New: Added Updated for previous imported stations
* New: Added Station slogan field
* New: Added Station State, City
* New: Added Station Breadcrumbs
* Fix: Fixed Player Loader Blinking
* Fix: Fixed Related Station Query
* Remove: Removed CSV Importer

= 2.2.8 =
* Fix: Fixed Popup Player Issue
* Fix: Fixed Grid Style

= 2.2.7 =
* New: Added New Search Form Style
* New: Added Proxy Player Addon Compatibility to play the HTTP (Unsecured) stream link.
* New: Added Station Live/ Offline Indicator
* New: Added Get Started Documentation Page
* New: Added Recommended Plugins Page
* New: Added Radio Player Gutenberg Block
* New: Added Radio Player Elementor Widget
* New: Added Affilitation Integration
* New: Added Alpha Color Control
* Update: Improved Search Form Functionality
* Update: Improved Player Volume Bar
* Update: Improved Station Listing Style
* Update: Player Color Style
* Remove: Auto Detect Player Type

= 2.2.6 (15 December. 2020) =
* Fix: Fix popup player

= 2.2.5 (9 December. 2020) =
* New: Added WordPress 5.6 compatibility.

= 2.2.4 (9 December. 2020) =
* New: Added default volume controller
* New: Added autoplay option on the station page
* New: Added Grid Listing column number settings
* New: Added Listing and Player thumbnail size settings
* New: Added popup player size settings
* Fix: Fixed Continuous Playing
* Remove: Removed col attribute from [wp_radio_listing], [wp_radio_trending] and [wp_radio_featured] shortcode.

= 2.2.3 (7 December, 2020) =
* Fix: Fixed country flag issue
* New: Added Country list widget
* New: Added comment option to single page - (WP Radio User Frontend)

= 2.2.2 (26 November, 2020) =
* Fix: Fixed featured Stations Shortcode

= 2.2.1 (24 November, 2020) =
* Fix: Station Reference Links

= 2.2.0 (02 November, 2020) =
* New: Added a new WP Radio Image Import addon to import the images
* Remove: Removed template_layout settings
* Remove: Removed "Hide Country List" settings
* Remove: Removed language field from metabox
* Remove: Removed station wikipedia url metabox field
* Remove: Removed additional contact info metabox field
* Remove: Removed the sidebar from the archive page
* Remove: Removed Help page under settings page
* Improvement: Improved the settings page
* Improvement: Improved the radio station importer UI

= 2.1.9 (26 August, 2020) =
* Fix Gutenberg radio station block
* Add radio station elementor widget
* Fix trending station shortcode
* Fix add favorite from popup player

= 2.1.8 (12 August, 2020) =
* New: Add [wp_radio_station] shortcode
* New: Add Radio Station gutenberg block
* Fixed: Fix Wpml integration fatal error
* Fixed: Make compatible with 5.5

= 2.1.7 (20 June, 2020) =
* Fixed: Fix station social link settings saving

= 2.1.6 (14 June, 2020) =
* Fixed: Fix Database Options Settings

= 2.1.5 (13 June, 2020) =
* Fixed: Fix Settings Page
* Fixed: Fix Uninstalling Process Function

= 2.1.4 (26 May, 2020) =
* Update: Optimized the popup player to handle the without stream links on google chrome
* Update: New option added to the play button behaviour setting section on the settings page
* Fix: Remove settings menu item from admin bar menu

= 2.1.3 (14 May, 2020) =
* Fix: Undefined Variable error
* Fix: Report Email Error
* Update: Player Play Icon
* Fix: Country term error

= 2.1.2 (3 May, 2020) =
* Update: Make Search bar expanded
* Update: The Popup player updated to be more mobile friendly
* Update: Improved footer player on mobile
* Update: Improved trending/featured listing layout
* New: Add New Help Page
* New: Add Current Track Title Support on Mobile

= 2.1.1 (28 April, 2020) =
* Remove: Air on/ off indicator removed
* Fix: Now playing stream title
* Fix: Station single page double player
* Fix: WPML integration error
* Fix: Trending stations shortcode

= 2.1.0 (25 April, 2020) =
* New: Stream Link live/ offline indicator message on station not playing
* New: Add Player Icon/ Text Color Setting added to the style settings option.
* Remove: Show popup play button setting field removed from player settings option.
* New: Add New Statistics Page (premium version)
* New: Add play btn behaviour setting to the player settings option.
* Update: Popup player updated to handle without SSL stream links. ( premium version )
* Update: Update Player (HLS & m3u8 support)
* Update: Country flags linked to the country archive page

= 2.0.9 (18 March, 2020) =
* Improve: Station Edit Metabox & Options page ui
* Fix: Browser mix-content blocking (HTTP & HTTPS)
* Fix: popup link open on parent window

= 2.0.8 (18 January, 2020) =
* New: Add search bar show/hide option (Listing page search bar show/ hide option added on the settings page)
* New: Add upgrades
* Fix: Station search action fixed
* Update: Responsive Design Improved
* Removed: Removed the report form to the wp-radio-user-frontend addon

= 2.0.7 (17 January, 2020) =
* New: Custom Station Permalink
* New: Backend stations filter by country field
* Fix: Responsive Design

= 2.0.6 (19 November, 2019) =
* New: Radio Player Widget
* New: Integrate WP Radio User Frontend Add-on
* Fix: Grid View Layout

= 2.0.5 (11 November, 2019) =
* New: Add .m3u8 File Support
* New: Add Show/ Hide Option For You May Also Like Section
* New: "WP Radio User Frontend" extension integrated
* Improve: Improved The UI Design

= 2.0.4.4 (23 October, 2019) =
* New: You May Like Section in The Station Single Page
* New: Remove Button in The Imported Country List in Import Page
* Update: Improve Layout Design
* Remove: Update Button Removed From Imported Country List

= 2.0.4.3 (21 October, 2019) =
* New: Add CSV Importer
* Fix: Search
* Update: Add genres to the listing

= 2.0.4.2 (19 October, 2019) =
* New: Add Report Form
* Fix: Grid List Style
* Fix: Station Prev Next

= 2.0.4.1 (16 October, 2019) =
* New: Add Grid Listing View
* Remove: Single Station Page Breadcrumb

= 2.0.4 (15 October, 2019) =
* New: Popup Play Button
* New: Ascending Radio Stations in Admin View
* Update: Popup Player Setting
* Update: Remove HTML from Country & Genre Title

= 2.0.3 (3 October, 2019) =
* Fix: Genre Archive Broken Link

= 2.0.2.2 (7 September, 2019) =
* New: Color Customizing Settings
* Fix: PHP Notice on no station

= 2.0.2.1 (31 August, 2019) =
*   New: Station Listing Short Description.
*   New: Add genre attribute to [wp_radio_listing] Short Code.
*   Fix: United Kingdom Country Code.
*   Enhance: Theme Compatible.
*   Enhance: Layout Design.
*   Enhance: Responsive Design.
*   Remove: TwentyNineteen & TwentySeventeen theme supports.

= 2.0.2 (22 August, 2019) =
*   New: Add Popup Player.
*   New: Add Short Code Player.
*   New: Add Search/ Filter bar.
*   New: Add Delete plugin data on deactivation.
*   New: Add Player hide option.
*   Fix: Import Station Error.
*   Fix: Featured On/ Off button in meta box.
*   Fix: Page Not Found.

= 2.0.1 (02 August, 2019) =
*   New: Integrate Freemius.
*   New: Add [wp_radio_featured] shortcode.
*   New: Add [wp_radio_trending] shortcode.
*   New: Add [wp_radio_country_list] shortcode.
*   New: Play now song information.
*   Enhance: Add stations updater on the import page.
*   Enhance: Add TwentySeventeen and TwentyNineteen theme support.
*   Remove: Search bar from the country list sidebar.

= 2.0.0 (05 July,2019) =
*   New: [wp_radio_listing] shortcode.
*   New: Country based archive page.
*   Add: demo.
*   Modify: readme.txt.
*   Fix: Plugin file missing error.
*   Fix: Plugin action links.
*   Fix: Plugin Settings Page.
*   Remove: Deprecated functions.

= 1.0.0 (26 June,2019) =
*   Fix: readme.txt tested up to
*   Enhance: Import features added to the readme.txt

= 1.0.0 (26 June,2019) =
*   Initial release