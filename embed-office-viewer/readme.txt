=== Document Viewer - Embed and Display Office and PDF Files ===
Contributors: bplugins,shehabulislam, taninrahman, freemius
Tags: Embed document, easy document embedder, pdf embedder, document viewer
Requires at least: 6.2
Tested up to: 7.0
Stable tag: 2.4.1
Requires PHP: 7.4
Donate link: https://www.buymeacoffee.com/abuhayat
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

With the Document Viewer for Office plugin, you can view Microsoft Word, Excel, PowerPoint, and more files in WordPress using shortcode

== Description ==

If you have Office documents on your website or blog that you want your readers to view even if they don’t have Office installed? Would you rather view a document before downloading it? To give your audience a better experience, try the Office Web Viewer.

This WordPress plugin offers seamless integration of document viewing using two popular viewer types: Google Doc Viewer and Microsoft Online Viewer. Easily customize the layout of your documents by adjusting width and height settings to match your desired design.

Unlock additional features with the pro version, including the ability to select documents directly from Google Drive, OneDrive, or Dropbox. View documents using the Google Doc Viewer for enhanced compatibility and functionality. 

Control user experience by enabling or disabling pop-out options, displaying or hiding the file name at the top, and toggling the download button for easy access.

Enhance document security by enabling or disabling right-click functionality as needed. With these advanced features, managing and presenting documents on your WordPress site becomes more efficient and user-friendly.


**[See Live Demo](https://bplugins.com/products/document-viewer/#demos "Demo")** 
**[Buy The Pro](https://bplugins.com/products/document-viewer/pricing "Buy Pro version")** 

= Some Benefits =

- Works perfectly in computers, tablets, and mobile phones.
- You don’t need to convert Office files for the web (e.g., PDF, HTML).
- Anyone can view Office files from your website or blog, even if they don’t have Office.

### Document Viewer Free Features:

- 2 viewer types such as Google Doc Viewer or Microsoft Online Viewer
- Add width and height to customize the layout of the Document.


### Document Viewer Pro Features

- Choose Document From Google Drive
- Choose Document From OneDrive
- Choose Document From Dropbox
- View Document by Google Doc Viewer
- Disable/Enable Pop-out
- Show/Hide File Name in Top
- Show/Hide Download Button on Top
- Disable/Enable Right Click


### User Feedback

#### ⭐⭐⭐⭐⭐ [Impressively Stable](https://wordpress.org/support/topic/impressively-stable/)

❛❛***Nice wp plugin ever. I’ve use it for 4 years.***❜❜

***-[aircess](https://wordpress.org/support/users/aircess/)***


#### ⭐⭐⭐⭐⭐ [Does the job well.](https://wordpress.org/support/topic/does-the-job-well-131/)

❛❛***This plugin works fine***❜❜

***-[muhammadarooj](https://wordpress.org/support/users/muhammadarooj/)***


#### ⭐⭐⭐⭐⭐ [Exactly What I Need](https://wordpress.org/support/topic/exactly-what-i-need-161/)

❛❛***I am a theatre director and I use my website to communicate with my performers and technical crews.

I wanted to SHOW them documents in situ as well as enable them to easily download them and, after looking at other solutions, this plugin is PERFECT for this.

I am delighted with it and cannot recommend it highly enough.***❜❜

***-[jfortune](https://wordpress.org/support/users/jfortune/)***


#### - Did you like this plugin? Dislike it? Have a feature request? [Please share your feedback with us](mailto:support@bplugins.com 'Send feedback')



= How It Works = 

- Your Document will be embed in your site, blog via a iframe which is service of Office Web Viewer 

- This plugin use Office Web Viewer service of Microsoft [Read More About Office Web viewer](http://officewebviewer.com "Read More") 
- Terms Of Service : [Microsoft's Terms of service ](https://go.microsoft.com/fwlink/?LinkID=206977  "Terms") 
- Privacy Policy:  [Microsoft's Privacy Policy ](https://go.microsoft.com/fwlink/?LinkId=521839  "Privacy")

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-directory` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use shortcode in page, post or in widgets.
4. <?php echo do_shortcode('YOUR_SHORTCODE'); ?>` in your templates


== Frequently Asked Questions ==

= Which type of document can I embed using that plugin? =

Microsoft Word, Excel, And Powerpoint Document

= What should be the file extension to work perfectly with that plugin?  =

'.ppt' '.pptx' '.doc', '.docx', '.xls', '.xlsx'

= I Would like to support Your Project what is the best way to support?  =

Thanks for your interest. You can donate any amount by visiting  [Here](https://gum.co/wpdonate  "Donate Now")

= Where do I report security bugs found in this plugin? =

Please report security bugs found in the source code of the Document Viewer for Office plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/9e5fb774-c5c5-4253-bec9-fd5a4210d09b). The Patchstack team will assist you with verification, CVE assignment, and notify the developers of this plugin.


== Screenshots ==

1. Sidebar menu
2. Add new Doc / Configure Viewer
3. ShortCode
4. Output / Frontend preview
5. Frontend preview of a excel File


== Changelog ==

= 2.4.1 - 27 June 2026 =
* Update: Redesigned and integrated the new standardized help & demos admin dashboard.

= 2.4.0 - 3 June, 2026 =
* New: Added 4 dedicated, modular settings panels (DocumentSource, ViewerEngine, DisplayDimensions, SecurityRestrictions) to group Gutenberg settings.
* New: Added custom SVG icons inside the titles of the block settings panels.
* Update: Redesigned Gutenberg block settings field titles to use friendly, concise terms for non-technical users.
* Update: Consolidated admin assets enqueuing into a single class loader.
* Update: Standardized core classes under `includes/core/` and helper files under lowercase `includes/helper/` to ensure namespace consistency.
* Improvement: Integrated Composer autoloading to boot and resolve PHP dependencies dynamically.
* Improvement: Unified the legacy shortcode rendering pipeline to run through the Gutenberg block rendering engine via the native `render_block()` API.
* Improvement: Replaced the block settings TabPanel with separate, sequential settings panels.
* Improvement: Applied a premium aesthetic theme color (#f1fdfe) for settings panel backgrounds and matching blue (#226df5) for panel header icons.
* Improvement: Added detailed file format descriptions to simplify the document selector experience.
* Improvement: Added a fixed height and smooth fade-in transition for the Upload Documents editor UI to prevent layout jumping on tab change.
* Improvement: Reorganized metabox fields into two clean tabbed sections: "Document Settings" and "Security & Restrictions".
* Fix: Resolved fatal error during plugins_loaded hook by replacing query loops with safe get_posts() in ImportMeta class.
* Fix: Resolved cross-origin iframe console errors in block editor under content protection mode.
* Fix: Resolved pop-out overlay alignment and responsiveness by using CSS container queries.
* Fix: Resolved "Protect Content" text selection and keyboard shortcuts bypasses (Ctrl/Cmd + C, A, S, P).
* Fix: Resolved "Disable Full Screen" and pop-out overlay styles targeting mismatches.
* Fix: Resolved missing i18n translation wrappers on "Protect Content" toggle labels.


= 2.3.3 - 8 November, 2025 =
* Updated Block - Editor upload options 

= 2.3.2 - 10 October, 2025 =
* Add Modern Dashboard and improved. 

= 2.3.1 - 12 July, 2025 =
* Fixed issues

= 2.3.0 - 10 May, 2025 =
* Fixed textdomain issue & add powerfull Gutenberg block

= 2.2.10 - 29 Jan, 2025 =
* Update: WordPress SDK

= 2.2.9 - 04/07/2024 =
* Update: WordPress SDK

= 2.2.7 =
* Update Freemius WordPress SDK

= 2.2.5 =
* Update Freemius WordPress SDK

= 2.2.4 =
* Fixed Height issue

= 2.2.3 = 
* Fixed Security issue
* Imporved Performance

= 2.0.9 =
* PDF viewer support
* Improved performance

= 1.1 =
* Added doc management screen
* Improved performance.
* Gutenberg block support

= 1.0 =
* Initial Release


== Source Code ==

You can find the source code, report bugs, and contribute to the development of this plugin on our GitHub repository:
[**Embed Office Viewer on GitHub**](https://github.com/bPlugins/embed-office-viewer-wp)


== Third-Party Libraries ==

This plugin bundles the following third-party JavaScript/PHP libraries.

= Codestar Framework =
* **Source:** [http://codestarframework.com/](http://codestarframework.com/)
* **GitHub:** [https://github.com/Codestar/codestar-framework](https://github.com/Codestar/codestar-framework)
* **License:** GPLv2 or later – [https://github.com/Codestar/codestar-framework/blob/master/LICENSE.md](https://github.com/Codestar/codestar-framework/blob/master/LICENSE.md)
* **Purpose:** Provides the options framework for the plugin's settings and shortcode generator.

= Freemius SDK =
* **Source:** [https://freemius.com/](https://freemius.com/)
* **GitHub:** [https://github.com/Freemius/wordpress-sdk](https://github.com/Freemius/wordpress-sdk)
* **License:** GPLv3 – [https://github.com/Freemius/wordpress-sdk/blob/master/LICENSE.txt](https://github.com/Freemius/wordpress-sdk/blob/master/LICENSE.txt)
* **Purpose:** Provides opt-in usage tracking and analytics to help improve the plugin.

= bpl-tools =
* Source / GitHub: https://github.com/bPlugins/bpl-tools
* License: GPL-2.0-or-later – https://www.gnu.org/licenses/gpl-2.0.html
* Purpose: Shared utility library providing admin dashboard components and common Gutenberg editor controls.
* External Services: The library may connect to bPlugins, WordPress.org, and Freemius services for product data and checkout functionality. See full details: https://github.com/bPlugins/bpl-tools#external-requests--why-they-are-made




