=== Plugin Name ===

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

== Demo ==

Working demo can be found at [http://wp.glome.me].

== Installation ==

Download and unpack the plugin to the plugin directory of the Wordpress
server.

Find the installed Glome plugin in the Plugins menu and enable it. This
can be done by logging in to Wordpress as an administrator.

== Configuration ==

Go to the Glome settings page under the Settings menu while being logged
in to Wordpress as an administrator. Click the "Request API Credentials"
button and wait until the page displays the acquired Glome API
credentials.

== How to add widgets? ==

The widgets of the plugin can be added to the Widget area once the
credentials are granted. Navigate to "Appearance -> Widgets" in the
Wordpress admin pages and drag and drop the following widgets from the
"Available Widgets" list to the "Widget Area":

* Glome One-time Login

Enable the one click anonymous Wordpress account creation and login.

* Glome QR For Pairing

Display a QR code for linking anonymous accounts on multiple devices.

* Glome Scanner Pairing

Enable a web based QR scanner. This feature will only work on browsers
that support WebRTC. Please see [http://caniuse.com/#feat=rtcpeerconnection]
for further details.

* Glome Notification Broker

Enable a websocket based notification channel between the Glome API and
the Wordpress website. Websockets support can be checked at
[http://caniuse.com/#feat=websockets].

The title of these widgets can be customised.

== Glome specific settings ==

The Glome settings page displays the following:

* API Base Path: address of the Glome API server.

* API UID: UID for this particular Wordpress site.

* API Key: API key for this particular Wordpress site.

* Activity Tracking: Glome registers the URLs the user has been
visiting on this Wordpress site if this option is enabled. By default
this option is disabled.

Note! Individual users should also agree to submit data to Glome on
their behalf!

* Clone Name: paired accounts can have the same display name, if this
option is enabled. By default this option is disabled.

== Glome API access ==

The Wordpress site is granted exclusive, non transferable, basic access
rights to the Glome API. For more details on API plans please visit
http://glome.me.

== Feedback and contact ==

Please send an email to contact at glome dot me.

== Frequently Asked Questions ==

== Changelog ==

= 1.0 =
* Initial version for Plugin Directory at wordpress.org
