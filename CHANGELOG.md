1.0.0
=====

* Initial release

1.0.1
=====

* Added settings for text-field width.
* Added settings for custom icon.

1.0.2
=====

* Code cleanup.

1.0.3
=====

* Fixed default icon path bug.
* Performance optimization, only one database call for all URL Fields. This probably won't have any
  big impact on the usual EE installation, but in installation where we have many (and we mean many)
  URL Fields, there should be a performance benefit.

1.0.4
=====

* Added a simple Regex validator instead of just checking the http:// at the begining of an URL.
* Fixed bug that was introduced with EE 1.6.5 where custom fields with formatting set to NULL
  would have 'xhtml' formatting by default. Fixed this by enabling the formatting menu when
  creating this custom url field.

1.0.5
=====

* Fixed bug where formatting setting for the field would be ignored for new entries.
  http://expressionengine.com/forums/viewthread/96291/
