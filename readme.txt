=== ImagePress ===
Contributors: butterflymedia
Tags: image, user, upload, gallery, album, category, profile
Requires at least: 4.7
Tested up to: 4.9.7
Stable tag: 7.9.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: imagepress

== Description ==

Create a user-powered image gallery or an image upload site, using nothing but WordPress custom posts. Moderate image submissions and integrate the plugin into any theme.

== Installation ==

1. Upload the `imagepress` folder to your `/wp-content/plugins/` directory
2. Activate the plugin via the Plugins menu in WordPress
3. A new ImagePress menu will appear in WordPress with options and general help

== Changelog ==

= 8.0.0 =
* TODO: Refactor notifications and merge with feed (this will break Noir UI)
* TODO: Refactor collections (CPT?)
* TODO: Refactor custom fields
* TODO/ROADMAP: Remove jQuery dependency
* TODO: Replace URL fields with page dropdowns

= 7.9.2 =
* UPDATE: Added option to disable image views
* UPDATE: Added option to delete image likes
* UPDATE: Added option to delete image views
* UPDATE: Added feature to limit uploads by user role
* UPDATE: Refactored more jQuery to JavaScript code

= 7.9.1 =
* UPDATE: Added image anchor class
* UPDATE: Admin content/body updates
* UPDATE: Removed jQuery UI (plus sortable, widget and mouse dependencies)
* UPDATE: Refactored image reordering using Sortable.js
* UPDATE: Changed profile editor page to use an ID instead of an absolute URL
* UPDATE: Removed unused styles

= 7.9.0 =
* FIX: Fixed responsive styles
* FIX: Fixed hardcoded non-translated English assumptions (it's a mouthful)
* UPDATE: Updated translations with missing strings
* UPDATE: Replaced social icons with FontAwesome
* UPDATE: Updated FontAwesome (5.0.13 -> 5.1.0)
* UPDATE: Removed image rel tag
* UPDATE: Code compliance updates
* UPDATE: Admin content/body updates
* UPDATE: Moved feed module to ImagePress Elements
* UPDATE: Removed custom notifications functionality
* UPDATE: Updated Gutenberg compatibility

= 7.8.2 =
* FIX: Fixed displayed text instead of returned
* FIX: Fixed several missing styles for collections
* UPDATE: FontAwesome is back, with a vengeance (5.0.13)
* UPDATE: Removed all deprecated SVG files and related CSS
* UPDATE: Removed "featured" images functionality

= 7.8.1 =
* FIX: Fixed category assignment for bulk uploads
* FIX: Fixed post slug when using Author Tools
* GDPR: Removed occupational field meta
* GDPR: Removed employer meta
* GDPR: Removed location meta
* GDPR: Removed status meta
* UPDATE: Added profile editor link and logout link to cinnamon-login

= 7.8.0 =
* FIX: Fixed user role argument for author cards
* UPDATE: Added custom post type filter (template override is not required anymore)
* UPDATE: UI tweaks
* UPDATE: Added title to image anchor links to help with lightbox titles
* UPDATE: Removed deprecated theme-dependent JavaScript
* UPDATE: Refactored bulk uploader (BREAKING)
* UPDATE: Refactored single image template (BREAKING)
* UPDATE: Updated RoarJS library to latest version (1.0.5)

= 7.7.8 =
* UPDATE: Replaced SweetAlert2 library with Roar (+speed)
* UPDATE: Removed all generic CSS styles (+compatibility)
* UPDATE: Restyled/reset all form elements

= 7.7.7 =
* FIX: Fixed issue with missing SQL variable
* FIX: Fixed issue with incorrect slug variable

= 7.7.6 =
* FIX: Fixed issue with JavaScript DOM loading detection
* FIX: Fixed plugin version inconsistencies

= 7.7.5 =
* FIX: Fixed issue with profile fields not being updated if empty
* FIX: Code quality fixes

= 7.7.4 =
* FIX: Renamed modal class to avoid conflicts with Bootstrap
* FIX: Use the same image grid structure everywhere (gallery, profile, collections)
* FIX: Fixed a countable issue in PHP 7.2
* FEATURE: Allow all file types for secondary uploads (PDFs, videos, etc.)

= 7.7.3 =
* FIX: Fixed image width for singular pages
* FIX: Removed image ordering capability on mobile devices
* UPDATE: Removed FontAwesome (+speed, -weight)
* UPDATE: Updated all icons and symbols (+speed, +compatibility)
* UPDATE: Updated sweetAlert2 library (+speed)
* UPDATE: Added default, native drag&drop upload (+speed)
* UPDATE: Removed EZDZ library (+speed, -weight)
* UPDATE: Updated WordPress compatibility
* UI: Removed several redundant icons

= 7.7.2 =
* FIX: Removed unused CSS stylesheets (-weight)
* UPDATE: Merged CSS reset stylesheet (+speed)
* UPDATE: Updated sweetAlert2 library (+speed)
* UPDATE: Removed a redundant condition (+speed)
* UPDATE: Removed getButterfly logo (-weight)
* UPDATE: Refactored settings page (+speed)
* UPDATE: Removed FontAwesome from Dashboard (+speed)
* UPDATE: Removed profile verification (-confusion)
* UI: Removed several icons

= 7.7.1 =
* FIX: Fixed collection maintenance action
* TODO: Gutenberg compatibility
* TODO: Merge uploaders (single and bulk)
* TODO: More functional approach in order to decrease code complexity

= 7.7.0 =
* FIX: Fixed URL strings
* FIX: Fixed (non)countable arrays
* FIX: Removed social hub stub
* UPDATE: Added most liked images shortcode
* UPDATE: Removed old (unused) functions
* UPDATE: Removed unused labels
* UPDATE: Removed hardcoded video post meta
* UPDATE: Removed hardcoded imagepress author post meta
* UPDATE: Removed hardcoded imagepress email post meta
* UPDATE: Refactored upload limits/restrictions
* UPDATE: Updated CoreJS library for better IE compatibility
* FEATURE: Added cleanup/migration script for ImagePress pre-7.7
* FEATURE: Converted browser confirmations to sweetAlert2 library

= 7.6.9 =
* FIX: Removed font icon pseudo elements for performance reasons

= 7.6.8 =
* FIX: Fixed missing FontAwesome icons
* UPDATE: Updated FontAwesome to latest version (5.0.8)

= 7.6.7 =
* FIX: Fixed bulk uploader
* UPDATE: Added collections to bulk updater
* UPDATE: Removed GitHub updater as it's not appropriate for Envato files
* UPDATE: Updated drag & drop uploader to latest version (0.6.1)

= 7.6.6 =
* FIX: Fixed missing file

= 7.6.5 =
* UPDATE: Fixed profile page when no profile is specified
* UPDATE: Updated FontAwesome to version 5

= 7.6.4 =
* UPDATE: Removed lazy loading as it affected several themes
* UPDATE: Updated WordPress compatibility

= 7.6.3 =
* FIX: Fixed SQL bug

= 7.6.2 =
* FIX: Fixed bug introduced in previous development version
* FIX: Fixed JS function being applied globally
* FIX: Fixed undefined shorthand jQuery
* FIX: Fixed registration not obeying WordPress native settings
* FIX: Code quality fixes
* FIX: Code performance fixes
* UPDATE: Updated documentation and copyright
* UPDATE: Moved login logic to main JS file for better performance
* UPDATE: Updated WordPress compatibility

= 7.5.8 =
* FIX: Fixed PHP notice
* FIX: Fixed documentation link
* FIX: Fixed incorrect check on image upload
* SECURITY: Sanitized MySQL queries

= 7.5.7 =
* FIX: Code quality fixes
* FIX: Removed unused code
* FIX: Fixed profile pages PHP notice
* FIX: Fixed updater filters globally impacting all `wp_remote_get` requests
* FIX: Fixed force-check parameter being ignored
* FIX: Fixed updater details link
* FIX: Fixed pagination issue with filters
* FIX: Fixed empty profiles when no user is provided
* FIX: Fixed empty collections when no collection is provided

= 7.5.6 =
* FIX: Fixed search query parameter with taxonomy filtering
* UI: Added media link to single image template
* UI: Fixed author tools layout (basic)
* UI: Added download icon to single image template
* UI: Added better mobile responsiveness for image grid
* UI: Removed icon labels for mobile screens (single page)

= 7.5.5 =
* FIX: Fixed author link in several places

= 7.5.4 =
* FIX: Fixed author link in several places
* FIX: Removed unused function make_clickable()
* FIX: Fixed display of the_content() on single pages to allow for filters
* UPDATE: Better contextual help for slug options
* UPDATE: Code quality updates
* UPDATE: Security updates

= 7.5.3 =
* FIX: Fixed author link in collections module
* FIX: Fixed server-specific request
* FIX: Fixed collections shortcode
* FIX: Fixed array operator
* FIX: Fixed unused function in image collections
* FIX: Fixed upload filter interfering with other mime types
* FIX: Fixed "load more" functionality on profile pages
* FIX: Fixed profile page to take into account usernames and nicknames
* UPDATE: Added REST support for image CPT and taxonomies
* UPDATE: Updated image quality filter
* UPDATE: Removed image resize option
* UPDATE: Code quality updates
* UPDATE: Security updates

= 7.5.2 =
* FIX: Fixed installation/upgrade routine
* FIX: Fixed user profile error
* UPDATE: Removed 4 unused (or troublesome) options
* UPDATE: [Breaking] Refactored the user profiles to work with any theme
* UPDATE: [Breaking] Introduced infinite lazy loading for profile page
* UPDATE: Image grid performance updates
* FEATURE: Set groundwork for data collection and tracking

= 7.4.3 =
* FIX: Added slug fallback

= 7.4.2 =
* FIX: Fixed deprecated author function
* FIX: Code analysis configuration
* UPDATE: General bug fixes and improvements

= 7.4 =
* UPDATE: General bug fixes and improvements
* UPDATE: Moved plugin options to array, making things faster

= 7.3 =
* FIX: Fixed wrong image ID variable in ImagePress widget shortcode
* FIX: Fixed colour reset affecting Noir UI theme
* FIX: Fixed bulk uploader UI not using the uploaded class
* UPDATE: Removed all notices and warnings and moved everything to the documentation
* UPDATE: Updated online documentation
* UPDATE: Added author nicename and link for collections (dashboard)
* FEATURE: Added new search field based on title and content

= 7.2.0 =
* BREAKING CHANGE: Replace all JS-powered loops with native WordPress loops
* UPDATED: Code refactoring
* FIX: Fixed image loop on user profile pages
* FIX: Fixed missing variable for native pagination
* FEATURE: Removed author.php template requirement
* FEATURE: Fixed and replaced user profiles behaviour (needs user action)

= 7.1.2 =
* FIX: Fixed custom image order for user profiles
* FIX: Fixed cinnamon-card shortcode to allow single authors
* TWEAK: Added a class to allow for a collection behaviour workaround
* FEATURE: Added extensions section

= 7.1.1 =
* FIX: Fixed contentEditable in image editor
* FIX: Fixed image box template and equalHeight template
* FIX: Switched include() with include_once() for functions.php to isolate an edge case
* FIX: Added missing collection option for the profile page tabs
* FIX: Fixed upload limit on user profile dashboard
* FEATURE: Added basic, default and masonry layouts for grid display
* FEATURE: Added new image template (overlaid text)
* UPDATE: Removed activity tab for performance reasons

= 7.1.0 =
* FIX: Fixed follow/unlinks links
* FIX: Fixed forced image download by not challenging mod_security (https://caniuse.com/#feat=download)
* FIX: Removed an unused file
* FEATURE: New shortcode to display user upload quota
* FEATURE: Added options to increase/decrease/set user upload quota
* FEATURE: Added jQuery Masonry
* UPDATE: Updated users screen to include quota
* UPDATE: Removed user upload limits per role (breaking)

= 7.0.6 =
* FIX: Fixed collections modal background colour
* FIX: Fixed collections modal layout
* FIX: Fixed image title rename

= 7.0.5 =
* FIX: Fixed multiple category filtering
* FIX: Fixed a JS function being called when no images were loaded
* FIX: Fixed collections to work with non-standard permalinks settings
* UPDATE: Updated grid/pagination library

= 7.0.4 =
* FEATURE: Added a new shortcode parameter

= 7.0.3 =
* FIX: Fixed author name missing link in single pages
* FIX: Removed preferred software user field
* FIX: Updated avatar filtering priority
* FEATURE: Added image title editing
* UPDATE: Updated image editor
* UPDATE: Removed Noir UI-specific upload button option
* UPDATE: Removed like action-specific labels

* NOIR UI FIX: Fixed untranslated strings
* NOIR UI UPDATE: Removed upload button from header
* NOIR UI UPDATE: Main menu UI changes and cleanup for better readability
* NOIR UI UPDATE: Relocated notifications and avatar to main menu bar
* NOIR UI UPDATE: Updated image sidebar and removed bloated content

= 7.0.2 =
* FIX: Optimized database queries for gallery display
* UPDATE: Updated FontAwesome library to latest version (4.7.0)

= 7.0.1 =
* FIX: Fixed several deprecated jQuery functions
* FIX: Fixed rogue console messages
* FIX: Fixed unclickable profile links

= 7.0.0 =
* FIX: Checked (and fixed) PHP 7 compatibility
* FIX: Fixed wrong label
* FIX: Fixed a string conversion function
* FIX: Fixed an author detection filter gone rogue
* UPDATE: Merged 2 lightbox rel options
* UPDATE: Retired keywords module as it was not used by the plugin or theme
* UPDATE: Removed posts from user profile
* UPDATE: No changes are now required for author.php page (it only needs to exist)
* UPDATE: Removed converting 100,000 to 100K (keep it professional)
* UPDATE: Removed configurable padding improving grid performance (it can be reconfigured using custom CSS)
* UPDATE: Updated grid library to latest version
* FEATURE: Added link to documentation
* FEATURE: Added custom field parameter for imagepress-show
* FEATURE: Added Thin UI CSS framework
* PERFORMANCE: Merged 3 admin_menu actions
* PERFORMANCE: Moved several database calls outside the main ImagePress loop

= 6.9.0 =
* FIX: Fixed image size selection
* FEATURE: Added image size option to control number of images per row - https://github.com/wolffe/imagepress/issues/28
* FEATURE: Made pending images visible to authors (profile and image editor only) - https://github.com/wolffe/imagepress/issues/32

= 6.8.2 =
* FIX: Fixed image order being reversed due to profile image ordering - https://github.com/wolffe/imagepress/issues/25

= 6.8.1 =
* FIX: Fixed sortable fields to allow for selection of shortcodes
* FIX: Check for empty image file before upload
* UPDATE: Ordered dropdowns (categories, tags, keywords) alphabetically
* UI: Profile UI tweaks
* FEATURE: Added drag and drop uploader

= 6.8.0 =
* FIX: Fixed a label being defined but not used
* CHANGE: Changed "Biographical information" to "About" in user profile editor

= 6.7.0 =
* FIX: Fixed portfolio options being displayed even if disabled
* FIX: Fixed collection options being displayed even if disabled
* FIX: Fixed bulk uploader displaying first filename for all input fields
* FIX: Translated two hardcoded fields
* FIX: Saved name and email (as custom fields) for non-registered users
* FIX: Fixed an optional statistics item on user dashboard

= 6.6.2 =
* UPDATE: Updated WordPress version requirement
* UPDATE: Removed Noir UI tab (all options moved to Noir UI theme)

= 6.6.1 =
* FIX: Fixed a PHP 5.4 "function return value in write context" fatal error
* UPDATE: Added theme ad and more consistent support callout
* UPDATE: Updated FontAwesome library to latest version

= 6.6.0 =
* FIX: Fixed upload limit not picking up the global limit on some configurations
* FEATURE: Added option to switch between basic profile header and fancy profile header
* FEATURE: Added embed code for Sketchfab
* FEATURE: Added embed code for Youtube
* FEATURE: Added embed code for Vimeo
* FEATURE: Added embed code for Google Maps (static map)
* FEATURE: Added embed code for Round.me
* UPDATE: Removed upload limit from the bulk uploader
* UPDATE: Added dropdown (custom field)

= 6.5.7 =
* UPDATE: Added confirmation when setting featured image in Author Tools

= 6.5.6 =
* UPDATE: Removed client specific Bitbucket path
* UPDATE: Updated compatibility version

= 6.5.5 =
* FIX: Fixed author tools by removing an unused setting
* FIX: (Breaking change) Removed hardcoded location field
* FIX: Fixed maximum upload size calculation for author tools
* FIX: Fixed current user detection for collections
* UPDATE: Updated FontAwesome library to latest version

= 6.5.3 =
* FIX: Fixed image upload limit not being numeric in some rare instances
* I18N: Added Dutch (nl_NL) translation

= 6.5.2 =
* FIX: Fixed collections not registering status on creation
* FIX: Cleaned up old code running wp_ajax checks

= 6.5.1 =
* FIX: Fixed a PHP notice
* FIX: Added upload filters removal for filetype compatibility

= 6.5.0 =
* FIX: Fixed front-end editor location field
* UPDATE: Removed lazy loading for images
* UPDATE: Updated front-end editor

= 6.4.0 =
* FIX: Fixed author base/slug being dependent on the wrong action
* FIX: Fixed avatar user not being picked up due to missing global variable
* FIX: Fixed MySQL version displayed for debugging purposes
* UPDATE: General clean-up
* UPDATE: (Breaking change) Removed hardcoded Behance field
* UPDATE: (Breaking change) Removed hardcoded purchase field
* UPDATE: (Breaking change) Removed hardcoded print field
* FEATURE: Added custom fields
* COMPATIBILITY: PHP 7 compatibility

= 6.3.1 =
* UPDATE: Added CSS class to "No images found!" error (`.imagepress-not-found`)
* UPDATE: Removed an old template and some old CSS code

= 6.3.0 =
* UPDATE: Updated sorting and pagination library
* FIX: Fixed quotes escaping on collections frontend
* FIX: Fixed iframe styling for Youtube videos
* UI: Updated user profile tab behaviour
* UI: Changed wording from "Remove filters" to "All" for category sorter

= 6.2.7 =
* FIX: Fixed the "last seen" date

= 6.2.6 =
* FIX: Cleaned up the profile page, removed an unused global variable, removed unused CSS
* FIX: Removed HTTPS detection and replaces it with // (let the browser do the job)
* UPDATE: Added support for jetpack Publicize and WordPress markdown
* UPDATE: Changed default image quality from 100 to 82, according to WordPress 4.5 recommendations
* UPDATE: Added better wording and more contextual help for the collections feature
* UPDATE: Added responsiveness to author cards
* UPDATE: UI responsiveness tweaks

= 6.2.5 =
* UPDATE: Added contextual help for the new filters feature
* UPDATE: Removed Noir UI colour options from ImagePress

= 6.2.4 =
* FIX: Removed 'featured' category from the taxonomy filter

= 6.2.3 =
* FIX: Fixed some shortcodes and widgets using deprecated functions
* FIX: Fixed image views not being correctly initialized
* UPDATE: Added better documentation
* UPDATE: Various UI tweaks and improvements
* FEATURE: Added category/taxonomy filtering

= 6.2.2 =
* FIX: Fixed profile options not being set up properly (About and Activity)
* FIX: Fixed Google Maps URL
* FIX: Fixed an incorrect function name

= 6.2.1 =
* FIX: Added (init) default Noir UI options
* FIX: Improved PrettyPhoto compatibility and added gallery capability
* FIX: Renamed several CSS classes for better theme compatibility
* FIX: Added location to author tools
* FIX: Fixed FontAwesome URL
* UPDATE: Updated grid library and fixed several conflicts and performance issues
* UPDATE: Removed number of images per row and made all images responsive and adaptive to screen width
* UPDATE: Replaced radiobox sorter with a dropdown element for better theme compatibility and better styling
* UPDATE: Removed a deprecated function to get current user details
* UPDATE: Updated the lazy loading library
* UPDATE: Removed unused code from JS functions
* UPDATE: Improved some functions
* FEATURE: Added custom logo URL option (Noir UI theme)

= 6.1.0 =
* FIX: Fixed another rare issue with notifications database
* FIX: Removed non-functional zoom icon on single images

= 6.0.4 =
* FIX: Fixed category being set up as integer instead of name
* FIX: Fixed readme.txt installation steps numbering
* FIX: Fixed a rare issue with notifications database

= 6.0.3 =
* FIX: Renamed several functions to prevent conflicts
* FIX: Removed an unused function
* FIX: Removed an unused shortcode
* FIX: Fixed a value being both string and integer
* UPDATE: Updated custom post types and taxonomies registration arguments/parameters
* UPDATE: Various UI fixes and improvements

= 6.0.2 =
* FEATURE: Added upload limit per user role and username with a global fallback
* UPDATE: Improved PrettyPhoto compatibility
* FIX: Fixed search box being excluded from sorter
* FIX: Switched option loading to automatic

= 6.0.1 =
* Skipped and merged into 6.0.2

= 6.0.0 =
* Skipped and merged into 6.0.1

= 5.9.3 =
* FIX: Overhauled login and registration routines
* FIX: Removed email notification override
* FIX: Removed unused Email tab
* FIX: Merged Compatibility options with Settings

= 5.9.2 =
* COMPATIBILITY: Added new WordPress 4.4 admin styles
* COMPATIBILITY: Added Noir UI theme options
* FEATURE: Added Terms Of Use hook
* FEATURE: Added required fields to the upload form
* FIX: Fixed author images size (shortcode)
* FIX: Fixed JS error for empty lists
* FIX: Fixed JS issue with variable naming
* FIX: Fixed colour options for the custom login page

= 5.9.1 =
* FEATURE: Removed deprecated 'count' parameter
* FEATURE: Added custom CSS editor instead of imagepress.css
* FIX: Remove empty images using jQuery (only used for certain configurations)
* UPDATE: Removed asynchronous display, effect was identical

= 5.9.0 =
* PERFORMANCE: Removed option autoloading (performance gain)
* DEV: Changed version format to x.y.z
* DEV: Removed hardcoded plugin version and switched to get_plugin_data()
* DEV: Renamed class files to adhere to Zend naming conventions
* DEV: Renamed main CSS file to adhere to ImagePress naming conventions
* FIX: Fixed user profile losing changes when updated both from frontend and from backend
* FIX: Fixed option removal (removed unused parameter)
* FEATURE: Added image upload limit [https://github.com/wolffe/imagepress/issues/10]
* FEATURE: Added new location field for images (optional)
* FEATURE: Added new terms and conditions restriction (optional)
* COMPATIBILITY: Added new WordPress 4.4 admin styles
* COMPATIBILITY: Changed all URL placeholders to `https://`
* UPDATE: Changed sorting to using a dropdown instead of fake radio options

= 5.8.2 =
* COMPATIBILITY: Added error/warning reporting option (should be disabled on production/live sites)
* FIX: Fixed settings tabs on WordPress 4.4
* FIX: Fixed email notification
* UPDATE: Cleaned up and updated the image uploader
* UPDATE: Admin style improvements (based on WordPress 4.4)
* UPDATE: Removed new installation pointers (as there was no real gain in using them)

= 5.8.1 =
* COMPATIBILITY: Added full PHP 5.3 compatibility by declaring options as variables before using them
* FIX: Added more translated strings
* FIX: Added more inline/contextual help
* UPDATE: Style tweaking and removal of some styles

= 5.7.2 =
* FIX: Fixed image size issue
* FIX: Fixed GitHub Updater issue when no GitHub repository is available

= 5.7.1 =
* FIX: Fixed indexAsync issue (to be replaced by https://github.com/javve/list.js/issues/230)
* FIX: Removed redundant parameter from imagepress-show shortcode
* UPDATE: Updated GitHub issue tracker URL
* SECURITY: Added external use/access check
* FEATURE: Moved asynchronous loading into its own file and made it optional (off by default)
* FEATURE: Added sort by date added
* FEATURE: Added extensions panel

= 5.7 =
* FEATURE: Added Dropbox upload
* FEATURE: Added collection clean-up feature
* UPDATE: Updated user notification override for WordPress 4.3
* UPDATE: Updated registration email message
* UPDATE: Updated FontAwesome library to 4.4.0
* IMPROVEMENT: Moved translation init to plugins_loaded
* IMPROVEMENT: Removed constant definition from plugin flow
* IMPROVEMENT: Removed unused functions (mod-trending.php)
* IMPROVEMENT: Removed small ImagePress thumbnails (no real performance gained and smaller footprint)
* IMPROVEMENT: Removed client-specific code from ImagePress core
* IMPROVEMENT: Added more conditions to WordPress init checks
* IMPROVEMENT: Added checks to image upload (performance increase)
* IMPROVEMENT: Added checks to term selection (tags, keywords, categories)
* FIX: Reverted to latest stable list.js due to sorting issues

= 5.6.3 =
* IMPROVEMENT: Switched check for imagepress.css to include child themes
* IMPROVEMENT: Added category filtering by adding the category name to the search field and waiting for ENTER key
* UPDATE: Updated FontAwesome library
* UPDATE: Added GitHub URL header
* REMOVE: Removed Disqus hack, as Disqus WordPress plugin is unmaintained

= 5.6.1 =
* IMPROVEMENT: Added option to enable/disable Avada lightbox
* IMPROVEMENT: Changed PHP template samples comments to /**/ in order to fix some copy/paste issues

= 5.6 =
* FEATURE: Added option to specify images per row
* ENHANCEMENT: Cleaned up and tweaked some of the grid style rules
* PERFORMANCE: Removed client-specific code (1)
* PERFORMANCE: Removed unused JS image width check
* PERFORMANCE: Removed event bubbling for several JS functions
* FIX: Changed JS comments to /**/ in order to fix some minification issues
* FIX: Removed GitHub Updater tags
* COMPATIBILITY: Added compatibility with Avada lightbox

= 5.5.10 =
* FEATURE: Testing asynchronous loading for images
* FIX: Fixed encoding issue (BOM)

= 5.5.8 =
* FIX: Fixed avatar selection when no custom avatar is available
* FIX: Fixed several duplicate styles and rearranged the single image CSS code

= 5.5.7 =
* FIX: Fixed avatar filtering
* FIX: Fixed reverse sorting

= 5.5.6 =
* IMPROVEMENT: Settings clean up

= 5.5.5 =
* IMPROVEMENT: Added empty upload check
* IMPROVEMENT: Removed redundant option to create users on image submission
* IMPROVEMENT: Added option to paginate authors (separate from images)
* FIX: Fixed upload form submission check before validity checks
* FIX: Fixed wrong link inside the collection tab

= 5.5.4.9 =
* FEATURE: Added back name and email address for unregistered users

= 5.5.4.8 =
* IMPROVEMENT: Removed hardcoded social sharing buttons and added a hook
* IMPROVEMENT: Merged spectrum.js with jquery.main.js
* IMPROVEMENT: Merged spectrum.css with ip.bootstrap.css
* FIX: Fixed missing AJAX callback

= 5.5.4.4 =
* FIX: Changed login cookie name from 'rememberme' to 'remember'
* FIX: Fixed wrong class name
* FEATURE: Added option to always include category into moderation queue

= 5.5.4 =
* ENHANCEMENT: Added github updater

= 5.5.3 =
* FIX: Fixed bulk image uploader
* FIX: Fixed missing JS variable
* FIX: Fixed missing labels for Awards taxonomy
* UPDATE: Minor styling updates
* UPDATE: Removed "cinnamon_profile_title" option
* UPDATE: Added more strings to translation file

= 5.5.2 =
* FIX: Renamed 4 functions to avoid conflict with TracPress
* FIX: Fixed misplaced redirection function on image upload
* FIX: Fixed missing values for redirection fields
* FIX: Fixed typo in 'ip_upload_success_title' option slug
* FIX: Fixed AJAX variable to avoid conflicts with third-party components
* FIX: Added fixes and overrides for many CSS declarations
* UPDATE: Updated pagination and filtering script and merged it with main JS engine
* IMPROVEMENT: Added CSS styles for smaller screens
* IMPROVEMENT: Added step 0.5 for image padding to allow for 1px image gap between images
* REFACTORING: Removed new update notification

= 5.5.1 =
* FEATURE: Added bulk uploader
* IMPROVEMENT: Removed the server-side file size client validation (the client-side one superseded it)

= 5.4.2 =
* FEATURE: Added new version notification for users missing the CodeCanyon notification
* FEATURE: Added "featured in collection" template tag
* UPDATE: Updated Spectrum to 1.7.0 (from 1.6.1)

= 5.4.1 =
* UPDATE: Added option to select collections page
* UPDATE: Added shortcode to the help section
* UPDATE: Updated single-image.php code to include collections
* FIX: Updated the "Add to collection" button to work for logged-in users only
* FIX: Fixed several bugs with the collections module
* FIX: Fixed several style overrides and added :empty declarations
* FIX: Removed some unremovable items
* FIX: Various CSS fixes and overrides

= 5.4 =
* FEATURE: Added collections module (BETA)
* FIX: Numerous bug fixes

= 5.3 =
* FIX: Fixed path checking for imagepress.css
* FIX: Added missing translatable strings to PO file
* IMPROVEMENT: Added new user default role check
* IMPROVEMENT: Removed all login cookie functionality and allow WordPress to handle it

= 5.2 =
* FIX: Fixed deprecated argument WP_User->id()
* FIX: Fixed deprecated get_postdata()
* FIX: Removed all console errors
* FIX: Fixed custom code for Critique/WIP icons
* IMPROVEMENT: Added "Image uploaded" and "Click here to view your image" as configurable labels
* IMPROVEMENT: Allowed imagepress.css to take precedence
* IMPROVEMENT: Added thumbnail for backend tables

= 5.1.2 =
* FEATURE: Added custom image sizes for portfolio themes
* IMPROVEMENT: Added better documentation and merged the author code
* IMPROVEMENT: Added better installation steps and amended the installation tab

= 5.1.1 =
* FEATURE: Overhauled profile editor (tabbed view)
* FEATURE: Added portfolio customization and themes
* FIX: Fixed/merged old functions
* FIX: Fixed image upload for profile editing
* IMPROVEMENT: Removed colour picker
* IMPROVEMENT: Removed colour options for top image

= 5.1 =
* IMPROVEMENT: New pagination system with live sorting and filtering
* IMPROVEMENT: New welcome box (with installation status and quick links)
* PERFORMANCE: Added item visibility features (only visible images load in browser)
* FIX: Fixed a PHP function not working in PHP 5.3
* I18N: Removed incomplete it_IT translation

= 5.0-beta4 =
* FIX: Multiple fixes and improvements

= 5.0-beta3 =
* IMPROVEMENT: Removed a link title from the users screen (images column)
* IMPROVEMENT: Removed an unused global variable ($wp_roles)
* IMPROVEMENT: Added image details and related posters as a function (see updated single-image.php)
* IMPROVEMENT: Removed 'cinnamon_card_hover' option
* IMPROVEMENT: Added more labels (notification related)
* FIX: Removed a deprecated call to "caller_get_posts"
* FIX: Added missing default labels for the voting module
* FIX: Added missing default labels for the notifications module
* FIX: Fixed image editing mode discarding current featured image
* FIX: Fixed new image addition notification
* FIX: Fixed duplicate notifications
* FEATURE: Added sorting and filtering for author cards (default onclick sorting is DESC)
* I18N: Added more translated strings

= 5.0-beta2 =
* FIX: Renamed the secondary upload function
* FIX: Fixed the PHP context error

= 5.0-beta1 =
* FIX: Check for slug and set a default value if empty
* FIX: Make slug field required
* FIX: Prevented event propagation from 'like' button
* CLEANUP: Removed lightbox script
* CLEANUP: Removed slider script
* IMPROVEMENT: Profile redesign

= 4.2 =
* I18N: More internationalized strings
* PERFORMANCE: Converted lightbox images to dataURIs
* FIX: Fixed option to enable Disqus integration (URL anchor append)

= 4.1 =
* FEATURE: Added option to make the description field mandatory
* FEATURE: Added option to override the WordPress default email notification
* FEATURE: Added option to enable Disqus integration (URL anchor append)

= 4.0-BETA2 =
* UPDATE: Updated profile page
* UPDATE: Cleaned up CSS file
* UPDATE: Updated single image view
* FIX: Fixed avatar size

= 4.0-BETA1 =
* FIX: Fixed empty title submission
* FEATURE: Added HTML5 filetype validation
* FEATURE: Added option to hide tags dropdown

= 4.0-RC3 =
* FIX: Added empty label check for author tools
* FIX: Added default styles for author tools (some themes were overriding them)

= 4.0-RC2 =
* FEATURE: Added installation steps
* FEATURE: Added upload fields customization
* UPDATE: Added contextual help
* UPDATE: Added missing shortcode to dashboard
* UPDATE: Overhauled profile page

= 4.0-RC1 =
* UPDATE: Merged Cinnamon Users
* UPDATE: Major overhaul

= 3.5 =
* UPDATE: Major update (lots of new features and rewritten functions)

= 3.2.1 =
* UPDATE: Code cleanup

= 3.2 =
* ADD: Added new image size (based on personal project)
* FIX: Fixed image link when integrated lightbox was active and attachment link was set
* FIX: Removed missing 2x images from the integrated lightbox
* UPDATE: Added more clarification to lightbox options
* UPDATE: Added more clarification to image size options
* UPDATE: Updated image URL text field as "url" field
* IMPROVEMENT: Added CSS3 box sizing for all ImagePress elements
* IMPROVEMENT: Moved all external lightbox to plugin folder
* IMPROVEMENT: Optimized all local images
* IMPROVEMENT: Optimized CSS3 masonry code with more browser-specific declarations
* IMPROVEMENT: Merged and minified CSS styles

= 3.1 =
* VERSION: Added WordPress 3.9 compatibility
* FIX: Fixed extra styles
* FIX: Added a missing shortcode and removed obsolete scripts from the dashboard page
* IMPROVEMENT: Removed Colorbox dependency and added custom lightbox based on Nivo

= 3.0 =
* FEATURE: Complete rewrite of plugin engine

= 2.7 =
* FIX: Removed the buggy frontend user table (administration is only available from the backend)
* FIX: Added correct placeholders for name and email fields
* FIX: Fixed configurator line breaks
* FEATURE: Added thumbnail size to the configurator
* FEATURE: Added maintenance options (reset all votes)
* IMPROVEMENT: Replaced image icons with FontAwesome icons
* IMPROVEMENT: Removed several unused/redundant CSS styles
* IMPROVEMENT: Removed 4 unused/redundant images
* IMPROVEMENT: Removed 2 unused/redundant JS files

= 2.6 =
* FIX: Sorting and filtering
* FIX: Count parameter
* FEATURE: Added filtering by user ID
* FEATURE: Added Configurator (enable/disable any line inside the image box)
* FEATURE: Added CSS transitions instead of jQuery (isAnimated Masonry parameter)
* REMOVE: Removed "url" parameter (use Configurator)
* REMOVE: Removed PressTrends tracking (better plugin performance)
* REMOVE: Removed Modernizr (better plugin performance)
* UPDATE: Combined 2 Javascript dependencies (better plugin performance)
* UPDATE: General code cleanup

= 2.5.2 =
* FIX: Allow multiple category shortcodes on the same page

= 2.5.1 =
* FIX: Fixed duplicated MP6 icon
* IMPROVEMENT: Moved settings menu to custom post menu
* IMPROVEMENT: All hardcoded submissions now use the category slug (please update)

= 2.5 =
* FIX: Removed styling for file input (100% mobile compatibility)
* FIX: Removed autofocus attribute as it was conflicting with theme features
* FIX: Added current selected user (filter)
* FIX: Removed author archive filtering
* FIX: Fixed a wrong label in plugin's settings
* IMPROVEMENT: Switched hardcoded category as a shortcode parameter instead of a global option
* IMPROVEMENT: Added updated code to single-user_images.php and documentation file
* IMPROVEMENT: File cleanup (removed 3 unused files)
* FEATURE: Added PressTrends tracking
* FEATURE: Added user gallery on click (click on username, just like Deviant Art)
* FEATURE: Caption is now a required field (HTML5 "required" attribute)
* UPDATE: Update translations (both plugin and single template file)
* REMOVE: Removed comments bubble as it did not count third-party comments and it was heavily dependent on cache
* REMOVE: Removed placeholder compatibility for IE (just uncomment the code in js/main.js if you want to use it)

= 2.4 =
* UI: Added dedicated MP6 dashboard icon (dashicon)
* UI: Merged dashboard with the settings area for easier access
* FEATURE: Image uploads now add authors as subscribers
* FEATURE: Added option to hardcode a category
* FEATURE: Added option to show or hide the category dropdown
* FEATURE: Added URL address field (as a shortcode parameter)
* FEATURE: New "Sort by author" dropdown function, now showing only users with images
* IMPROVEMENT: Removed Formalize plugin
* IMPROVEMENT: Removed hardcoded jQuery plugin

= 2.3.4 =
* FIX: Some servers add a paragraph break inside inline generated JS; it is now fixed

= 2.3.3 =
* FEATURE: Added author archive filtering
* FEATURE: Added author sorting

= 2.3.2 =
* FIX: Form accesibility improvements
* PERFORMANCE: Removed a useless/duplicate .js script

= 2.3.1 =
* FEATURE: Responsive top image (hall-of-fame) shortcode parameter

= 2.3 =
* FIX: Fixed registration condition
* FEATURE: Added top image (hall-of-fame) (based on views)
* FEATURE: Added top image (hall-of-fame) (based on votes)
* FEATURE: Added most viewed images widget
* FEATURE: Added most voted images widget
* FEATURE: Added time (in hours) before voting is possible again
* IMPROVEMENT: Moved form labels to option group instead of .po file
* IMPROVEMENT: Added IE placeholder fix
* DOCUMENTATION: Added custom post type template code sample

= 2.2.1 =
* FIX: Fixed a mispositioned curly brace

= 2.2 =
* IMPROVEMENT: Modernizr jQuery code now loads faster
* IMPROVEMENT: Fixed Lazy Load 0.5 plugin conflict (http://wordpress.org/plugins/lazy-load/) (thanks Jack Woodhams)

= 2.1.1 =
* IMPROVEMENT: Category sorter is now hierarchical

= 2.1 =
* FEATURE: Added image views counter
* FEATURE: Added image voting feature
* FEATURE: Added category sorter
* UI: Realigned image box bottom line
* UI: Backend tweaks
* BEHAVIOUR: Modified a shortcode to include another one (basically merged 2 shortcodes for better flexibility)

= 2.0.3 =
* FEATURE: Added option to set image link to either media or custom post type

= 2.0.2 =
* UI: Dashboard page tweaks
* UI: Icon tweaks
* UI: MP6 theme improvements
* UI: Added pagination CSS styles
* FIX: Fixed a reversed file_exists() function (imagepress.css)
* FIX: Fixed a script rendering error, blocking Masonry plugin
* FIX: Fixed official support link

= 2.0.1.1 =
* Added load_plugin_textdomain() function (thanks Andrea Cavaliero)
* Added it_IT translation (thanks Andrea Cavaliero)
* Added override stylesheet option
* Added image description
* Added single image template sample inside the documentation folder

= 2.0.1 =
* Added better image upload button (using jQuery)
* Added autofocus to image caption (using jQuery)
* Tweaked form UI
* Fixed an aggressive trim function
* Replaced an echo function with a return function
* Removed extra (useless) bootstrap styles (huge conflicts with some themes)

= 2.0 =
* Added notification email (on image upload, for administrator)
* Added notification email (on image approve/reject, for registered users)
* Added text colour option
* Fixed a missing menu slug
* Corrected several typos
* Corrected plugin license
* Renamed several backend menu slugs
* Small UI changes

= 1.0 =
* First public release
