Glome plugin for Wordpress
==========================

Glome is a unique platform for user acquisition, personalisation and
communication by addressing strict privacy and transparency requirements.

This plugin integrates Wordpress sites with Glome.

## What does the plugin contain?

The plugin consists of a few widgets, a user page and a settings page
for Wordpress administrators.

## Why is this plugin good for?

Site admins can add the widgets to their Wordpress widget area. With
these widgets the site's visitors will be able to create fully functional
Wordpress accounts without the standard registration process. They will
not need to fill in any form, just click a single button.

Two other widgets will let these newly created anonymous accounts to be
linked with other anonymous accounts. The same Wordpress site accessed
by the same person on multiple devices can be personalized without ever
asking for usernames, email addresses or passwords.

This will make the Wordpress site to stand out from the rest.

## Installation

Download the zipped version from [https://github.com/glome/glome-wp/archive/master.zip]
and unpack it to the plugin directory of the Wordpress server.

Find the installed Glome plugin in the Plugins menu and enable it. This
can be done by logging in to Wordpress as an administrator.

The plugin will be uploaded to the Wordpress Plugin Directory
[https://wordpress.org/plugins/] in the near future.

## Configuration

Go to the Glome settings page under the Settings menu while being logged
in to Wordpress as an administrator. Click the "Request API Credentials"
button and wait until the page displays the acquired Glome API
credentials.

## How to add widgets?

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

The title of these widgets can be customized.

## Glome specific settings

The Glome settings page displays the following:

* API Base Path: address of the Glome API server.

* API UID: UID for this particualr Wordpress site.

* API Key: API key for this particular Wordpress site.

* Valid until: Glome API access is granted until this time.

* Activity Tracking: Glome registers the URLs the user has been
visiting on this Wordpress site if this option is enabled. By default
this option is disabled.

* Clone Name: paired accounts can have the same display name, if this
option is enabled. By default this option is disabled.

## Glome API access

Upon clicking the "Request API Credentials" button on the Glome settings
page Glome will grant full access to its APIs for a
__trial period of 1 month__.

Following that the site owners or administrators have to contact Glome
to extend the license period.

## Feedback and contact

Please send an email to contact at glome dot me.

## License

The "Glome plugin for Wordpress" is licensed under the MIT License.
A copy of the license from can be downloaded from [https://github.com/glome/glome-wp/raw/master/LICENSE].

## Kudos

* LazarSoft for the amazing JS QR scanner http://www.webqr.com/
