=== Silver Bullet ===
Contributors: webvitaly
Donate link: http://web-profile.com.ua/donate/
Tags: speedup, performance, info, sql, queries, query, time, memory
Requires at least: 4.0
Tested up to: 5.0
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html


"Silver Bullet" is the missing plugin for every WordPress website.

== Description ==

Plugin features:
* speedup website by not loading useless functions. Excludes: deprecated.php, pluggable-deprecated.php, bookmark.php and bookmark-template.php (It saves about 2% on memory which WordPress consumes on every page load)
* shows in the admin bar the number of SQL queries during the WordPress execution, the amount of time in seconds to generate the page and memory load


Your hosting will be happy.

== Frequently Asked Questions ==

= What to do if plugin caused errors: =

You should connect to website via ftp and manually edit file wp-settings.php and uncomment lines which contains these filenames: deprecated.php, pluggable-deprecated.php, bookmark.php and bookmark-template.php

= Incompatible with: =

* Multisite
* IIS server

== Changelog ==


= 1.0 - 2016-11-25 =
* initial release;
