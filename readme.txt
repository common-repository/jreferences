=== Plugin Name ===
Contributors: janiko
Tags: footnotes, comments, reference, citation
Requires at least: 4.6
Tested up to: 5.0.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin adds references and citations functionalities (in a wikipedia-like style).

== Description ==

This plugin allows references and citations in your posts. It's only a beta version, so if you find issues or if you think it lacks some features, please contact me.

To use it, simply add your reference using a shortcode you can choose ('jref' by default), and a few attributes (always use lowercase):

* title (mandatory)
* author
* date
* url
* editor
* page
* lang
* readon

There are no specific controls on the content of those attributes, you may add any text you want. Only 'title' is mandatory. All attributes roles are easy to understand; 'page' is for a book (but can be used for any content), 'readon' is the date you last consult the reference.

Again, in this first release, there's no specific control or action triggered by attribute's content (like verifying the format, or linking to an archive site). Maybe in a future release...

A list of all references will be added at the end of the post. 

Here are some examples:

* [jref]url=http://geba.fr|date=janv 2017|title=The Title.|author=Janiko|lang=FR|editor=geba.fr|page=p.5[/jref]
* [jref name='numref']url=http://geba.fr|date=janv 2017|title=The Title.|author=Janiko|lang=FR|editor=geba.fr|page=p.5[/jref]
* [jref name='numref' /]

You can optionnaly add a name to the citation, so you can reuse it in your post. Important note: the displayed attributes will be the attributes of the FIRST reference with that name. Any other attribute will be ignored.

== Installation ==

Just get the plugin and activate it. You can choose in the admin section the text of the shortcode you'll use for your citations. By default it's 'jref'.

Remember that when you change it, all posts written with the old shortcode won't be parsed anymore.


== Frequently Asked Questions ==

Let me know if you have some. I will add them here!

An answer to that question.

== Screenshots ==

1. This is a very simple example with a reference used twice.

== Changelog ==

= 1.0.3a =
Tested with WordPress 5.0

= 1.0.3 =
Some formatting

= 1.0.1 =
Tagging

= 1.0 =
First decent version

= 0.9a =
Correct encoding issues + 1 bug

= 0.92 =
* Small CSS correction

= 0.91 =
* Correction of default value

= 0.9 =
* First public release
* Minimalist settings and features but fully functionnal (I hope)
