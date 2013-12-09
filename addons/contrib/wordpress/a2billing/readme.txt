=== A2Billing ===

Contributors: Belaid Arezqui
Donate link: http://www.asterisk2billing.org/cgi-bin/trac.cgi/wiki/Donate%20to%20A2Billing
Donate email : areski@gmail.com
Tags: A2Billing, admin, rates
Requires at least: 2.7
Tested with : 2.9.1
Tested up to: 2.9.1
Stable tag: 1.0

== Description ==

This plugin is to display rates from A2Billing.
It will allow you to have a nice displayed rate cards, with options to select prefix, currency
and the first letter of the country.


= Features =
* Displaying your rates

== Credits ==

Copyright 2010 -  Belaid Arezqui

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


== Installation ==

1. Upload the files to wp-content\plugins\a2billing
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure A2Billing by going to Admin -> Settings -> A2Billing
4. Go to your post/page and insert the tag according to the features you want to use :
	tag '[a2billing_rates callplan_id='1']' : To display rates from callplan with id 1
	
5. Alternatively you can insert `<?php a2billing_rates(); ?>` within your blog's templates by going to Admin -> Appearance -> Editor

== Screenshots ==

1. Admin Area
2. Rates with pagination


== Frequently Asked Questions ==

= Where can I find more information about A2Billing ? =

	You can ask questions on the forum : http://forum.asterisk2billing.org/index.php

	find documentation on our Wiki : http://trac.asterisk2billing.org/

	or directly contact the sales A2Billing team : sales@star2billing.com 


== Changelog ==

= 1.0 =

* First version released


== Upgrade Notice ==

= 1.0 =

* No upgrade notive, this is the first version released


