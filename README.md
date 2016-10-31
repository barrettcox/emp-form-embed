# EMP for WordPress
Contributors: barrettcox
Tags: wordpress, plugin, template
Requires at least: 3.9
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description

Embeds EMP forms into your WordPress pages.

## EMP for WordPress Instructions

### Activating the Plugin

1. Download on clone this repository and upload it to your /plugins directory.
2. Go to “Plugins”, and locate the EMP for WordPress plugin.
3. If the plugin is not activated, click “Activate” to enable it for the site.
4. You should now now notice an item in the left-hand navigation labeled “EMP Forms”.

### Adding a New EMP Form to a Page

1. In the left-hand navigation go to “EMP Forms”.
2. Click “Add New’.
3. Give the form a title (can be anything descriptive).
4. Enter the API Key and the 3-digit Client ID for the form you would like to add (You can get these values from EMP).
5. Click “Publish” (or “Update”) to save the form.
6. After saving, you can now copy the shortcode value to the right of where it says “Shortcode”. The shortcode will look something like this:

`[empforwp pid=”{some integer}”]`

7. Past the shortcode into the editor on any page where you want the EMP form to appear.