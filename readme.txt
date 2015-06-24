=== Glome ===

Contributors: _ferenc
Tags: anonymity, privacy, linking, personalization, Glome
Requires at least: 4.2
Tested up to: 4.2.2
Stable tag: 4.2
License: Expat
License URI: http://www.gnu.org/licenses/license-list.html#Expat

This plugin improves user acquisition of Wordpress sites. It allows
visitors to act as registered users without filling in any forms.

== Description ==

This plugin integrates Wordpress sites with Glome.

Each visitor can get a “Soft Account” by a single click. Users can link
soft accounts across multiple devices.

A visitor with soft account can act as a registered user, in fact he or
she will receive a regular, but anonymous Wordpress account. There is no
need for going through the standard registration process by filling in
and submitting forms.

The plugin consists of a few widgets, a user page and a settings page
for Wordpress administrators.

Site admins can add the widgets to their Wordpress widget area. With
these widgets the site's visitors will be able to create fully
functional Wordpress accounts as mentioned above.

Two widgets will let these newly created anonymous, "soft accounts" to
be linked with other anonymous accounts. The same Wordpress site
accessed by the same person on multiple devices can be personalised
without ever asking for user names, email addresses or passwords.

This will make the Wordpress site stand out from the rest.

== Installation ==

Download and unpack the plugin to the Wordpress server's plugin
directory.

Login to Wordpress as an administrator.

Find the installed Glome plugin in the Plugins menu and activate it.

After activating the plugin please go to the Glome settings page under
the Settings menu. Click the "Request API Credentials" button and wait
until the page displays the acquired Glome API credentials.

Once the API credentials are shown you are ready to add the widgets.

== Frequently Asked Questions ==

= Do you have a demo site? =

Yes, we do. Please [click here for a working demo](http://wp.glome.me).

= What does the Glome API access mean? =

By clicking the button on the plugin's settings page Glome will grant
exclusive, non transferable, basic API access rights to your
Wordpress site.

For more details on API plans please visit [Glome's website](http://glome.me).

= What are the UI elements of this plugin? =

The plugin has only widgets that can be added to your user interface.
Please see the next question about adding the widgets.

= How do I add widgets? =

The widgets of the plugin can be added to the Widget area once the API
credentials are granted. Navigate to "Appearance -> Widgets" in the
Wordpress admin pages and drag and drop the following widgets from the
"Available Widgets" list to the "Widget Area":

* Glome One-time Login

  Enable the one click anonymous Wordpress account creation and login.

* Glome QR For Pairing

  Display a QR code for linking anonymous accounts on multiple devices.

* Glome Scanner Pairing

  Enable a web based QR scanner. This feature will only work on browsers
  that support WebRTC. Please see [this page](http://caniuse.com/#feat=rtcpeerconnection)
  for further details.

* Glome Notification Broker

  Enable a websocket based notification channel between the Glome API and
  the Wordpress website. Websockets support can be checked at
  [this website](http://caniuse.com/#feat=websockets).

Note! The title of these widgets can be customised.

= What are the Glome specific settings? =

The Glome settings page displays the following:

* API Base Path: address of the Glome API server.

* API UID: UID for this particular Wordpress site.

* API Key: API key for this particular Wordpress site.

* Activity Tracking: Glome registers the URLs the user has been
visiting on this Wordpress site if this option is enabled. By default
this option is disabled. Note! Individual users should also agree to
submit data to Glome on their behalf!

* Clone Name: paired accounts can have the same display name, if this
option is enabled. By default this option is disabled.

= Where did you get the JS QR scanner from? =

Kudos to LazarSoft for the amazing JS QR scanner [http://www.webqr.com/].

Please be aware that only a few browsers support the API needed by the
scanner.

= How can I get in touch with you? =

We would welcome all comments, questions, suggestions. Please send an
email to contact at glome dot me.

== Upgrade Notice ==

This is the first release, hence this section is irrelevant for now.

== Screenshots ==

1. The "One-Time Access" and the "Key for Glome Wallet" widgets are available to gain access to the Wordpress site.

2. After gaining access one will be able to pair other devices. It can be done by scanning a QR code, or opening the corresponding URL.
We also take advantage of modern browsers that can work with cameras directly (via WebRTC).

== Changelog ==

= 1.2 =
* Improved POST sanitizations.

= 1.1 =
* Fixing hard coded plugin path name. Proper input validation.

= 1.0 =
* Initial version for the Plugin Directory of [Wordpress](http://wordpress.org)
