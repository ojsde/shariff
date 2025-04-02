# Shariff plugin

This plugin adds social media buttons to your web site (to the footer of each page, the article page or the sidebar) without compromising the privacy of website users. It implements shariff by Heise Medien GmbH ([Github](https://github.com/heiseonline/shariff), [article](http://ct.de/shariff), [npm](https://www.npmjs.com/package/shariff)) in [Open Journal Systems](https://pkp.sfu.ca/ojs/).

This plugin has been tested with OJS version 3.4.0-3. It should also be compatible with OMP 3.4 and OPS 3.4.

## Features

Available social media services (as of Shariff 3.3.0) are:

- Twitter
- Facebook
- LinkedIn
- Pinterest
- XING
- Whatsapp
- AddThis
- Tumblr
- Flattr
- diaspora*
- reddit
- StumbleUpon
- Threema
- Weibo
- Qzone
- Mail
- Print
- Buffer
- Flipboard
- Pocket
- Telegram
- vk
- Info

Additionally an info button that links to the heise article can be added. You can also add a mail icon. 

The plugin offers a selection of settings like themes, orientation and position of the social media buttons (see settings below) accessible via "Website -> Appearance". As of plugin version 3.3.1 WCAG conformant buttons are available and are enbaled by default.

The plugins will be displayed in the language of the website.

You can add the Shariff PHP Backend to display the number of likes (not tested, see below).

## License

This plugin is licensed under the GNU General Public License v3. See the file LICENSE for the complete terms of this license.

## System Requirements

This plugin is compatible with OJS version 3.4.0. It should also be compatible with OMP 3.4 and OPS 3.4.

## Installation

The pugin is available via the OJS plugin gallery. You can also clone this repo to your plugin folder (/plugins/generic) or download the code and tar.gz it and upload it via the gui (Website Settings > Plugins).

## Settings

The settings form can be found unter "Website -> Appearance -> Shariff Plugin".
The following settings are available:
- choose the services you would like to provide on the journal pages (see list above)
- choose a provided theme for the social media buttons (standard, white, grey)
- determine the position of the social media buttons on your journal web site (footer, sidebar or article/book page)
- choose the orientation of the social media buttins (vertical or horizontal)
- disable WCAG conformant social media buttons (enabled by default, this option is for backward compatibility only and might be removed in the future)

## Usage
Install the plugin as described above, activate it and choose the settings you prefer. If you choose the sidebar option, you need to enable the new Shariff-block under management/settings/website.

## Display numbers

To display the numbers how often a page is shared in social media, you need to add the Shariff PHP Backend (<https://github.com/heiseonline/shariff-backend-php/releases>) to you OJS installation. To set up the backend you need to have access to the code. Follow the steps:

1. Download the Shariff PHP Backend release zip file and unzip it.
2. Copy the folder to the top level of your installation, name it "shariff-backend" and make it writable.
3. Change the domain in index.php to your domain.
4. The installation registers the folder if is named "shariff-backend" and everything should be working.

## Contact/Support

Documentation, bug listings, and updates can be found on this plugin's homepage at <http://github.com/ojsde/shariff>.
Contact us via support@ojs-de.net. Find out more about the project [OJS-de.net](http://www.ojs-de.net/kontakt/index.html).

## Version History

* 3.4.4 Update Shariff 3.3.0 - thanks to lepidus
* 3.3.1, 3.4.2 - provides WCAG 2 conformant social media buttons - thanks to staff at University of Oklahoma Libraries 
* 3.4 - plugin version for OJS 3.4
* 3.3 - plugin version for OJS 3.3
* 3.2 - plugin version for OJS 3.2
* 3.0 - Adaption to OJS/OMP 3.1.2, update to Shariff Version 3.2.1
* 2.0 - Adaption to OJS/OMP 3
* 1.0 - Shariff plugin for OJS 2

## Technical Documentation

This plugin works for OJS and OMP 3 (the code is the same). The [shariff solution by heise online](https://github.com/heiseonline/shariff) (version v3.2.1) is included in the plugin code (MIT License). It adds the social media buttons using html, css and js. The plugin uses hooks to add content, no existing templates are being overwritten. No database access is needed.

### Hooks

The buttons are added via template hooks:
* Templates::Common::Footer::PageFooter
* Templates::Article::Details 
* Templates::Catalog::Book::Details
* Templates::Preprint::Details

To add the plugin to the sidebar, this plugin is also a [block plugin](https://github.com/ojsde/shariff/blob/master/ShariffBlockPlugin.inc.php).

### Attributes

The shariff solution offers different settings (see data attributes https://github.com/heiseonline/shariff). A selection of them are used in this plugin (some are filled automatically, some can be chosen by the user in the plugin setting):

* data-lang: The plugin reads the selected language of the GUI automatically and uses it to display the buttons in that language.
* data-services: The user can choose from a list of services in the plugin settings (see above).
* data-mail-url: The automatically set string "mailto" allows the usage of local mail clients.
* data-mail-body: The plugin enters the respective url automatically.
* data-backend-url: The backend url is read automatically if the folder exists. The folder has to be placed at the document root and named "shariff-backend".
* data-theme: The user can choose between different themes in the plugin settings (see above).
* data-orientation: The user can choose between two orientation in the plugin settings (see above).
* data-url: The plugin adds the respective url automatically.
