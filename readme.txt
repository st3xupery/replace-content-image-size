=== Replace Content Image Size ===
Contributors: blogestudio, pauiglesias
Tags: image, resize, content, size, sizes, media, image width, image sizes
Requires at least: 3.3.2
Tested up to: 4.2
Stable tag: 1.2.1
License: GPLv2 or later

Find images displayed in posts content and change the format size, very useful when you change the blog theme.

== Description ==

For each image uploaded Wordpress generates several versions with different sizes. These images will be inserted
in the post content using the visual editor selecting the right format size for the current theme, so that
images can be displayed correctly in the theme layout avoiding the overflow to the sidebars or another elements.

These images are now part of the content, and are harcoded in posts independently to any change in the design
or behavior of the blog.

Therefore, when switching your theme to another theme smaller, or the new theme is very big respect the old
layout, may be these images do not fit perfectly with the new design.

For these cases the purpose of this plugin is the searching in the post content of images with the old image sizes
(that result bad sizes for the current theme) and replace it for the right image format: thumbnail, medium, large, 
full or custom format size.

The process is simple and consists of three steps. The first step is a form to introduce the width to find, or the
period between two widths separated by an hyphen (-) with max 100 points of difference. Optionally you can enter
a custom post type, or leave blank for the 'post' post type.

In the next step present the coincidence or multiple coincidences for each post, with the old html code in grey color,
and in black the replacement, and also the new image is displayed. For each item there is a checkbox (by default checked)
that you can uncheck if the result is wrong or an exception.

Finally, at the end of the list, a submit button can perform these changes. Before this final step, is advisable to
backup your posts table.

== Installation ==

1. Unzip and upload replace-content-image-size folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to menú Tools > Replace Content Image Size and follow the steps.

== Frequently Asked Questions ==

= Is this plugin safe for the content of my posts? =

Each post that needs to be altered goes through a process of reconstruction that fragments in chunks the post content
(it is splitted based in newline character), replaces img elements and attributes width and height, and finally implodes
to join the fragments and update the posts table. It is possible that an strange error may occur, and for this reasons
we recommend to backup your posts table before launch de last step of the replacement process.

== Screenshots ==

1. The 2/3 step where you can confirm the changes to make in the posts content.

== Changelog ==

= 1.2.2 =
Release Date: July 15th, 2015

* Improved the method in which script parses HTML in posts for image tags.
* Resolves a bug that replaced a group of image tags without a new line delineation with just the first image of the group.

= 1.2.1 =
Release Date: July 15th, 2015

* Resolving a bug when replacing class attribute value
* This bug depended on the class attribute relative position
* The main symptom was lack of closing quotes in class attribute

= 1.2 =
Release Date: April 17th, 2015

* Change $wpdb->escape by esc_url function
* Success test with custom post types
* Fix bad scope internal function variable mod_sum
* Fix post_type hidden form var warning and 3th step
* Tested code for WordPress 4.2 version.

= 1.1 =
Release Date: March 28th, 2015

* Tested code for WordPress 4.1.1 version.
* Some warnings on undefined variables fixed.
* Defered load plugin textdomain to plugin admin section (improve performance).
* Added style to submit buttons.
* Fix PHP strict warnings and set properly scope of class methods.
* Fix class size-[alias] of html img code.
* Added clean_post_cache when update post.
* Change strpos by stripos when needed.

= 1.0 =
Release Date: May 29th, 2012

* First and tested released for WordPress 3.3.2

== Upgrade Notice ==

= 1.2.1 =
Fixed bug replacing images class names

= 1.2 =
Fixed some warnings for Wordpress 4.2 support

= 1.1 =
A maintenance release fixing bugs and updating the code to the latest WordPress version.

= 1.0 =
Initial Release.
