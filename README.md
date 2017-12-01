# Shariff plugin

This plugin adds social media buttons to your web site (to the footer of each page or the sidebar) without compromising the privacy of website users. It implements shariff by heise ([Github](https://github.com/heiseonline/shariff), [article](http://ct.de/shariff)) in [Open Journal Systems](https://pkp.sfu.ca/ojs/).

## Features

Available social media services are:
- AddThis
- diaspora*
- facebook
- Flattr
- Google+
- LinkedIn
- Mail
- Pinterest
- Print
- Qzone
- reddit
- StumbleUpon
- Tencent Weibo
- Threema
- Tumblr
- Twitter
- Weibo
- WhatsApp
- XING


![Shariff buttons](https://raw.githubusercontent.com/lilients/img/master/shariff_buttons_footer.PNG)

An info button that links to the heise article can be added. You can select different themes, choose the orientation of the buttons (vertical or horizontal) and determine the position of the social media buttons on your website (footer, sidebar or article/book page). The plugins will be displayed in the language of the website.

You can add the Shariff PHP Backend to display the number of likes (see below).

## License

This plugin is licensed under the GNU General Public License v2. See the file LICENSE for the complete terms of this license.

## System Requirements

This plugin is compatible with OJS 3.x version.

## Installation

Clone this repo in your plugin folder (/plugins/generic) or download the code and tar.gz it and upload it via the gui (Website Settings > Plugins).

## Settings

The following settings are available:
- choose the services you would like to provide on the journal pages (see list above)
- choose a provided theme for the social media buttons (standard, white, grey)
- determine the position of the social media buttons on your journal web site (footer, sidebar or article/book page)
- choose the orientation of the social media buttins (vertical or horizontal)

![Shariff settings](https://raw.githubusercontent.com/lilients/img/master/shariff_settings.PNG)

## Display numbers

To display the numbers how often a page is shared in social media, you need to add the Shariff PHP Backend (https://github.com/heiseonline/shariff-backend-php) to you OJS installation. To set up the backend you need to have access to the code. Follow the steps:

1. Download the Shariff PHP Backend release zip file and unzip it.
2. Copy the folder to the top level of your installation, name it "shariff-backend" and make it writable.
3. Change the domain in index.php to your domain.
4. The installation registers the folder if is named "shariff-backend" and everything should be working.

## Contact/Support

Documentation, bug listings, and updates can be found on this plugin's homepage at <http://github.com/ojsde/shariff>.

## Version History

1.0 - Initial Release
2.0 - Adaption to OJS 3
