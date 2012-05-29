=== Replace Content Image Size ===
Contributors: blogestudio, pauiglesias
Tags: image, resize, content, size
Requires at least: 2.0.2
Tested up to: 3.3.2
Stable tag: 1.0
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

== Changelog ==

= 1.0 =
* First and tested released
