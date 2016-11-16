=== Project Panorama ===
Contributors: Ross Johnson
Tags: acf, wysiwyg, advanced custom fields, addons
Requires at least: 3.5.0
Tested up to: 4.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Improve the performance of Advanced Custom Fields (ACF) WYSIWYG editors.

== Description ==

If you use Advance Custom Fields and found yourself with multiple WYSIWYG editors on a single page, you've probably noticed how incredibly slow the browser runs.
This is because having multiple WYSIWYG fields loaded at the same time takes an incredible about of system resources.

Lite WYSIWYG fixes that by only loading an editor when the user wants to edit content. WYSIWYG fields are textboxes initially, when a user clicks one to edit content
a standard TinyMCE WYSIWYG editor is dynamically loaded in it's place allowing them to edit content to their hearts desire. Once completed, the WYSIWYG editor is removed
and the content put back into a textbox, keeping the use of resources down.

Supports ACF4 and ACF5, backend and front end forms.

== Installation ==

1. Upload 'acf-lite-wysiwyg' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You'll now have a Lite WYSIWYG field option in Advanced Custom Fields
4. All of the normal ACF WYSIWYG options are available, all functionality is maintained it just loads differently

== Changelog ==

1.0
* Initial release
