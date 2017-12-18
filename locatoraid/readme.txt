=== Locatoraid - Store Locator Plugin ===
Contributors: HitCode
Tags: business locations, dealer locator, dealer locater, store locator, post, store locater, mapping, mapper, google, google maps, ajax, shop locator, shop finder, shortcode, location finder, places, widget, stores, plugin, maps, coordinates, latitude, longitude, posts, geo, geocoding, jquery, shops, page, zipcode, zip code, zip code search, store finder, address map, address location map, map maker, map creator, mapping software, map tools, mapping tools, locator maps, map of addresses, map multiple locations, wordpress locator, store locator map
License: GPLv2 or later
Stable tag: trunk
Requires at least: 3.3
Tested up to: 4.9
Requires PHP: 5.3

Add a store locator map to your site and get your visitors to start finding you faster!

== Description ==

Locatoraid is a lightweight, easy to use WordPress store locator, dealer locator, zip code locator plugin for any business that needs to let their customers find their stores, dealers, hotels, restaurants, ATMs, products, or any other types of locations. 

__Start In Minutes__
Just start adding your locations, then the automatic geocoding function finds the coordinates, and your locations are on the map waiting for your customers! 

__Works Worldwide__
More than 200 countries covered. USA, Australia, Canada, Hong Kong, Italy, Japan, Mexico, Singapore, South Africa, Spain, UK and more. As long as it is on the Google Maps, it will be found. 

__Mobile Friendly__
Responsive design that works perfectly well for iPhone, Android, Blackberry, Windows as well as for desktops, laptops and tablets.

__Shortcode Parameters__
A number of shortcode parameters that can be used to configure the front end view, like default search text, search radius, view layout, limit to a state or a country, and more. 

__Custom Map Styles__
Easily configurable map styles to match your website.

###Pro Version Features###

* __CSV Import / Export__ to bulk add and update your locations
* __Custom Fields__    to show more detailed information
* __Custom Map Icons__ for various types of locations
* __Products / Categories__ to categorize your locations

[Get the Pro version of Locatoraid store locator plugin!](http://www.locatoraid.com/order/)

== Support ==
Please contact us at [http://www.locatoraid.com/contact/](http://www.locatoraid.com/contact/)
[Follow on Facebook](https://www.facebook.com/Locatoraid-165233107413846/)

Author: HitCode
Author URI: http://www.locatoraid.com

== Installation ==

1. After unzipping, upload everything in the `locatoraid` folder to your `/wp-content/plugins/` directory (preserving directory structure).

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. If needed, enter your Google Maps API Key in Configuration > Google Maps API Key

== Upgrade Notice ==
The upgrade is simply - upload everything up again to your `/wp-content/plugins/` directory, then go to the Locatoraid menu item in the admin panel. It will automatically start the upgrade process if any needed.

== Changelog ==

= 3.3.1 =
* BUG: When searching for more than one product, it returned less results then it should (Pro version).
* BUG: The export file didn't properly link locations to products if any (Pro version).

= 3.3.0 =
* Added the id parameter for the shortcode, so it displays the map for this location only, with no search form.
* BUG: There might be fatal errors when both free and pro versions are enabled.

= 3.2.9 =
* BUG: An ajax error when using the limit="1" parameter for the shortcode.

= 3.2.8 =
* BUG: Another fix for the Ajax Error (JSON.parse: bad escaped character) message with certain configurations.

= 3.2.7 =
* BUG: The search form widget didn't work properly if only digits search string was posted, like zip (postal) code.
* BUG: Attempted to fix the Ajax Error (JSON.parse: bad escaped character) message with certain configurations.

= 3.2.6 =
* BUG: When uploading a CSV file, and it contained new products, the location to product relations were not built properly.
* Minor code updates.

= 3.2.5 =
* BUG: When it was configured not to start with default results, the map outline was still displayed.
* Minor code updates.

= 3.2.4 =
* BUG: Error saying "required field" that did not allow to update a location.

= 3.2.3 =
* BUG: Fatal error when upgrading from several older versions.

= 3.2.2 =
* BUG: The bulk geocoding process did not save the coordinates properly.

= 3.2.1 =
* Minor bug fixes.

= 3.2.0 =
* Front text (submit button, search field, more results link) can be edited.
* Bulk delete option for products (Pro version).
* Code refactoring improved speed and reduced size.

= 3.1.7 =
* BUG: The initial Google Maps API Key entry check didn't work correctly.

= 3.1.6 =
* BUG: The import upload did not associate locations with products (Pro version).
* Internal cleanup and optimization that now requires PHP version 5.3 or later.

= 3.1.5 =
* If you do not want to auto start with default results, you can use the start="no" shortcode parameter.

= 3.1.4 =
* Added a shortcode parameter to filter by product ("where-product") (Pro version).
* Added an option to supply custom map styles.
* Added a setting to enable/disable map scroll zoom.
* For the widget we have added an option to choose a target page to submit the search to, if you have multiple front end locator pages.
* BUG: Could not import from CSV files created on a Mac computer (Pro version).
* BUG: When updating an existing location and trying to add 0 to the start of the zip code, it got dropped.

= 3.1.3 =
* BUG: searching in the admin view produced a fatal error.
* BUG: products were shown as [object] text in the search results (Pro version).

= 3.1.2 =
* BUG: if a starting search setting consisted of digits only (a zip code for example), it did not recognize it.

= 3.1.1 =
* The search form widget is available again.

= 3.1.0 =
* A few code updates.

= 3.0.8 =
* Modified the front view output for a better fit with various themes.
* Moved the submenu links closer to the page header for a more prominent position.

= 3.0.7 =
* Added the map-style and list-style shortcode parameters to specify custom style HTML attribute for the map and the results list components. 

= 3.0.6 =
* BUG: If only "map" or "list" options were given in the shortcode "layout" parameter, the front end did not work.

= 3.0.5 =
* Now it allows to enter "none" as the Google Maps API key if you don't need it for any reason.
* Minor code updates.

= 3.0.4 =
* Now it allows to use duplicated locations names.
* Minor code updates.

= 3.0.3 =
* BUG: the language translation files were not loaded correctly.

= 3.0.2 =
* Make the map and the results list start hidden if no default search is given.
* Minor code updates.

= 3.0.1 =
* BUG: fixed a fatal database error for new installs.
* Added the .pot language file.

= 3.0.0 =
* A new major update.

= 2.7.6 =
* Fixed the unnecessary slashes appearing.

= 2.7.5 =
* Added the reverse alphabetically sort option.
* A few code updates and fixes.

= 2.7.4 =
* Removed potentially vulnerable own copy of PHPMailer library.

= 2.7.3 =
* Added shortcode parameter for the default country option.
* Added a dependency on jQuery for our scripts as it may be required for some WordPress configurations.

= 2.7.2 =
* BUG: the admin Install menu produced an error.

= 2.7.1 =
* Minor code updates.

= 2.7.0 =
* Added a setting if the street address for locations is required. If not, then you can leave just the city.

= 2.6.9 =
* Minor fixes in locations upload (Pro) and location name display functions.

= 2.6.8 =
* Added a configuration field to enter the Google Maps API key following the change in the Google Maps usage conditions.

= 2.6.7 =
* BUG: 404 error after certain WordPress search results
* Switched database engine to mysqli if it's available for compatibility with PHP 7

= 2.6.6 =
* BUG: Google maps API infobox URL fix

= 2.6.5 =
* BUG: the location search may have failed after settings update

= 2.6.4 =
* BUG: featured locations were not visually highlighted in the front end
* Added an option to sort locations by misc10 field [Pro]
* Minor code fixes

= 2.6.3 =
* Modified a bit the front end search form for a nicer view both in desktop and mobile.

= 2.6.2 =
* Added an option to set number of locations per page in the admin area.

= 2.6.1 =
* Allow longer entries for the website field (up to 300 characters), it was limited to 100 characters.

= 2.6.0 =
* Small fix for the stats module to prevent SQL error under some configurations.
* Modified a bit the admin edit location form to allow a bit more space for text inputs.
* Modified a bit the front end search form for a nicer view.
* Added a setting to open directions in a new window.
* Added an option for the admin to manually enter geo coordinates for a location.

= 2.5.9 =
* A little tweak to possibly share Google maps API file with other plugins.

= 2.5.8 =
* BUG: the address field format configuration was reset after updating the core settings.

= 2.5.7 =
* Added address display format configuration.
* BUG: directions link not working from the map after the infobox appeared after clicking on the locations list.

= 2.5.6 =
* Added options for labels before the search field and the radius selection.

= 2.5.5 =
* Added an option to configure which fields to show in the search results list and on the map.

= 2.5.4 =
* BUG: "Always Shown" locations were not really always displayed.

= 2.5.3 =
* BUG: If the matched locations title was set to blank, it still showed in the frontend.
* BUG: The matched locations count was wrong if the output group by option was set.
* Added an option to translate the Directions link label.
* Moved all localization/customization options for the front end together in the settings form.

= 2.5.2 =
* Skip locations with empty name and street address in the locations import file.
* BUG: If the locations import file contained special characters like umlauts then they were skipped.

= 2.5.1 =
* Added a setting to show the matched locations count in the front end.
* Skip empty lines in the locations import file.

= 2.5.0 =
* Added a setting to disable the scroll wheel in the map, it is useful when you don't want to automatically zoom the map when scrolling the page.

= 2.4.9 =
* The Pro version now can have up to 10 misc fields.

= 2.4.8 =
* A small fix to allow just "//" URLs, without protocol.

= 2.4.7 =
* A new option to group locations output by zip/postal code.

= 2.4.5 =
* Added an option to share the same database accross all sites of a multi-site network.

= 2.4.5 =
* A fix for the error in the print view for some search strings.

= 2.4.4 =
* Modified JavaScript to avoid conflicts with some themes.

= 2.4.3 =
* Print view link in the front end.

= 2.4.2 =
* A new option to group locations output by country, by country then by city, and by country then by state. It becomes active when countries are entered for your locations.

= 2.4.1 =
* When using products, now it searches for the exact product name. Before it might give wrong results because it searched for ANY word from the product name. For example, if you had two products "Dark Beer" and "Lager Beer", and searched for "Dark Beer", it also returned records with "Lager Beer" only because it contained the word "Beer".
* Product names are sorted in alphabetical order

= 2.4.0 =
* Now it can recognize shortcode options. Currently there are 2: "search" for the search address, and "search2" for the product option if you have any.
For example: [locatoraid search2="Pizza"]

= 2.3.9 =
* Added options to configure all other labels in the front end search form so now it can be easily translated into any language.

= 2.3.8 =
* Added an option to configure the search form label: the "Address or zip code" text.

= 2.3.7 =
* Loading Google maps JavaScript libraries with "//" rather than "http://" that will fix the error on https websites

= 2.3.6 =
* Fixed the empty label for website address in the admin panel

= 2.3.5 =
* Fixed compatibility issue with AutoChimp plugin
* Modified the CSV import code that may have failed then loading UTF-8 encoded CSV files (applies to the Pro version).

= 2.3.4 =
* Added a dropdown input to choose a country if you have locations in several countries
* Added a configuration for the location website label. If no label is given then the location's website URL is displayed. Applies to the Pro version.
* BUG: fatal error when Locatoraid front end was appeared on a post in the blog posts list rather on a page of its own.

= 2.3.3 =
* A fix for front end view for sites that implement page caching for example WPEngine

= 2.3.2 =
* BUG: when submitting the search by hitting the Enter button rather than a click, the auto-detect location input was appearing.

= 2.3.1 =
* Added an option to hide the user autodetect button
* Added an option to view locations in alphabetical order (in Settings > Group Output)
* BUG: the admin area in multi site installation was redirecting to the default site
* Added the data-id attribute in the location wrapping div (.lpr-location) in the front-end for a possible developer use

= 2.3.0 =
* Admin panel restyled for a closer match to the latest WordPress version.
* Front end JavaScript placed in a separate file to optimize loading.
* Cleaned and optimized many files thus greatly reducing the package size.
* The Pro version now features automatic updates option too.

= 2.2.2 =
* Redesigned the front end search form.
* Minor updates and fixes.

= 2.2.1 =
* Fixed a bug if you are using several instances (like locatoraid2.php and [locatoraid2] shortcode), it was showing the first instance for all the shortcodes.
* Added a wrapping CSS classes for location view in front end like .lpr-location-distance, .lpr-location-address, .lpr-location-name

= 2.2.0 =
* Added an option to set a limit on the number of locations that are shown following a search. For example, even though there may be 10 locations near AB10 C78, the locator only shows 3.

= 2.1.9 =
* Added a search form widget

= 2.1.8 =
* Making the plugin admin area accessible by only Editors or higher

= 2.1.7 =
* a small fix in the front end view when both "append search" and "start with all locations listing" options were enabled

= 2.1.6 =
* jQuery live() deprecated calls replaced

= 2.1.5 =
* When using auto search (auto detecting the current location), and switching the distance or the product selection, the search results were reverted back to the default search rather than current location.
* Language file fix

= 2.1.4 =
* Failed setup procedure in some WP configurations 

= 2.1.3 =
* Error in location count when prompting a next radius search
* Failed shortcode with some WP configurations 

= 2.1.2 =
* Enabled native languages interface

= 2.1.1 =
* Cleared jQuery dependency, making use of the built-in WP version

= 2.1.0 =
* Initial plugin version release


Thank You.

 
