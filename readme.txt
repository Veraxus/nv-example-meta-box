=== NOUVEAU Meta Boxes Example ===
Contributors: Veraxus
Tags: admin, meta box, editor, example, developer, nouveau
Requires PHP: 5.3
Requires at least: 3.6
Tested up to: 4.3
Stable tag: 1.1
GitHub Plugin URI: veraxus/nv-example-meta-box

A simple, functional WordPress plugin that serves as an example for creating new meta boxes for use on admin editor screens.

== Description ==

This plugin serves as a simple, functional, documented example for creating meta boxes for WordPress's editor screens. If you are not a developer, this plugin won't do anything for you.

Meta boxes let you create custom UI for your admin's editor screens, and add tremendous power and flexibility to WordPress. This plugin is intended to help make the creation of new meta boxes extremely simple. If you need to develop a plugin that adds new meta boxes to WordPress, this is a great place to start.

Adding a custom admin can now be accomplished in just two steps…

1. Register your meta boxes using WordPress's add_meta_box() function.
2. Register your settings/form fields using this plugin's MetaBox::register_setting() function.

That's all there is to it. Form fields, saving, and loading are all handled for you magically. Detailed documentation can be found in the code comments.

This example is part of the NOUVEAU WordPress framework. For more great starting code, search for "Nouveau" in the WordPress.org's plugins or visit [www.nouveauframework.com]

== Installation ==

1. Install using your WordPress sites admin dashboard (under Plugins &rarr; Add New), or by uploading the plugin folder to your `/wp-content/plugins/` directory
2. Activate the plugin from your sites admin dashboard (under Plugins)

== Frequently Asked Questions ==



== Screenshots ==



== Changelog ==

= 1.1 (2015/06/10) =
* Added serialization support to MetaBox::register_setting()
* Added a serialization example to init.php
* Bug fix: Single checkboxes now save & load correctly

= 1.0 (2015/06/06) =
* New magic metabox API! Create custom admin meta boxes faster than ever!

= 0.2 (2015/04/23) =
* Corrected static functions

= 0.1 (2013/09/06) =
* Initial beta. Uploaded to github.