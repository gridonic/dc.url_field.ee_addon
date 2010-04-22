Overview
=========

After reading [this post](http://expressionengine.com/forums/viewthread/79701/) over on the Expressionengine forums we decided to give this a try! The idea was to create a [custom field](http://expressionengine.com/docs/cp/admin/weblog_administration/custom_weblog_fields.html) to facilitate the entering of external links in weblog posts.

The features of this extension are very simple and straight forward. You can add a custom weblog field with type "*URL Field*". This field is being displayed on the edit entry form as a textfield with a small icon on the right-hand side. Clicking on this small icon first checks if the URL is empty or if the *http://* protocol is missing and triggers a javascript error in that case. Furthermore, if the URL format is valid, it opens a new window with the entered URL so that editors can double-check it before posting.

The extension has now officially been accepted by the Expressionengine development team and featured in the [EE extension repository](http://expressionengine.com/downloads/details/url_field_extension/).

Installation
============

1.  Download URL Field Extension
2.  Upack the archive contents to your Desktop or to a location of your choice on your hard-drive
3.  Copy the `extensions/ext.dc_url_field.php` file to your `/system/extensions` directory
4.  Copy the `language/english/lang.dc_url_field.php` file to your `/system/language/english` directory
5.  Copy the `images/icons/link_go.png` file to your images `/images/icons` directory (you will probably have to create the icons directory).

Activation & Settings
=====================

This extension does not have any special activation requirements. Follow these steps to activate URL Field in your EE installation:

1.  Log in to your EE control panel
2.  Go to `System Administration > Utilities > Extensions Manager` and enable extensions if not enabled already
3.  Enable URL Field extension

Since version 1.0.1 you can use two settings for your URL Field extension:

1.  **URL Field Width**: sets the width of the field on the publish page. Valid values are either `px` or `%`, default value is `300px`
2.  **Path to Icon**: path to the small icon shown on the side of the URL field, has to be relative to your EE installation, default value is `/images/icons/link_go.png`. You can specify any custom icon you want here.

Feedback
========

This extension has been tested to work with Expressionengine 1.6.3 and should be compatible with EE 1.4.2 or greater and most modern browsers. If you find a bug or have another feature proposal for this, send us a note and we will be more than glad to fix or consider it.
