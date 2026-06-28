<?php
/**
 * In-admin help chatbot for Document Viewer.
 *
 * Offline, self-contained help widget (Fuse.js fuzzy search over a hardcoded
 * knowledge base — no external AI/API). Renders on all plugin admin screens.
 *
 * @package BPLEOV
 */

namespace BPLEOV\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\\BPLEOV_Chatbot' ) ) {

	class BPLEOV_Chatbot {

		/**
		 * Hook into WordPress.
		 */
		public function register() {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ], 20 );
			add_action( 'admin_footer', [ $this, 'render_html' ] );
		}

		/**
		 * Shared screen detection — based on the SCREEN OBJECT, not the $hook
		 * arg. On the CPT list/editor screens the $hook is edit.php / post.php /
		 * post-new.php and does NOT contain the post type, so checking $hook
		 * there fails and the widget renders unstyled. Using the screen object
		 * keeps enqueue() and render_html() in agreement.
		 */
		private function is_plugin_screen() {
			$screen = get_current_screen();
			if ( ! $screen ) {
				return false;
			}
			if ( ! empty( $screen->post_type ) && 'officeviewer' === $screen->post_type ) {
				return true;
			}
			return strpos( $screen->id, 'officeviewer' ) !== false
				|| strpos( $screen->id, 'embed-office-viewer' ) !== false;
		}

		/**
		 * Enqueue scripts, styles and the localized knowledge base.
		 */
		public function enqueue( $hook ) {
			if ( ! $this->is_plugin_screen() ) {
				return;
			}

			// Fuse.js (fuzzy search) local script.
			wp_enqueue_script(
				'fuse-js',
				BPLEOV_PLUGIN_DIR . 'assets/admin/js/fuse.min.js',
				[],
				'7.0.0',
				true
			);

			wp_enqueue_script(
				'bpleov-chatbot',
				BPLEOV_PLUGIN_DIR . 'assets/admin/js/bpleov-chatbot.js',
				[ 'fuse-js' ],
				BPLEOV_VERSION,
				true
			);

			wp_enqueue_style(
				'bpleov-chatbot',
				BPLEOV_PLUGIN_DIR . 'assets/admin/css/bpleov-chatbot.css',
				[],
				BPLEOV_VERSION
			);

			wp_localize_script(
				'bpleov-chatbot',
				'bpleovChatbot',
				[
					'pluginName'  => 'Document Viewer',
					'version'     => BPLEOV_VERSION,
					'docsUrl'     => 'https://office-viewer.bplugins.com/docs/',
					'pricingUrl'  => 'https://bplugins.com/products/document-viewer/pricing',
					'supportUrl'  => 'https://bplugins.com/support',
					'suggestions' => $this->get_suggestions(),
					'kb'          => $this->get_knowledge_base(),
				]
			);
		}

		/**
		 * Print the widget markup in the footer.
		 */
		public function render_html() {
			if ( ! $this->is_plugin_screen() ) {
				return;
			}
			?>
<div id="bpleov-chatbot-wrapper">
  <div id="bpleov-chatbot-window">
    <div id="bpleov-chatbot-header">
      <div id="bpleov-chatbot-header-info">
        <div>
          <div id="bpleov-chatbot-title">Document Viewer Assistant</div>
          <div id="bpleov-chatbot-subtitle">Ask me anything about the plugin</div>
        </div>
      </div>
      <button id="bpleov-chatbot-close" title="Close">✕</button>
    </div>
    <div id="bpleov-chatbot-messages"></div>
    <div id="bpleov-chatbot-suggestions"></div>
    <div id="bpleov-chatbot-input-area">
      <input type="text" id="bpleov-chatbot-input" placeholder="Type your question…" autocomplete="off" />
      <button id="bpleov-chatbot-send" title="Send">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      </button>
    </div>
  </div>
  <button id="bpleov-chatbot-toggle" title="Document Viewer Help">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  </button>
</div>
			<?php
		}

		/**
		 * Six short example questions shown as chips on open.
		 */
		private function get_suggestions() {
			return [
				'How do I embed a document?',
				'What file types are supported?',
				'How do I use the shortcode?',
				'How do I hide the download button?',
				'Can I load files from Google Drive?',
				'My document is not showing',
			];
		}

		/**
		 * The hardcoded knowledge base. Each item: q, tags, a, and an optional
		 * 'pro' => true flag for Pro-only features (the JS appends the upgrade
		 * link automatically — do not hardcode it here).
		 */
		private function get_knowledge_base() {
			return [
				// ---- What it is / overview ----
				[
					'q'    => 'What is Document Viewer?',
					'tags' => 'about what is this plugin overview purpose intro introduction office viewer embed display',
					'a'    => '<strong>Document Viewer</strong> lets you embed and display Microsoft Office files (Word, Excel, PowerPoint), PDFs and HTML files directly inside your WordPress posts, pages and widgets — your visitors can read them online without downloading or owning Office.',
				],
				[
					'q'    => 'What can I use this plugin for?',
					'tags' => 'use case usecase scenario why benefit good for example purpose when',
					'a'    => 'Common uses: showing brochures, reports, manuals, resumes, slide decks, spreadsheets, contracts and PDFs on your site so readers can preview them in the browser. No file conversion is needed and viewers do not need Microsoft Office installed.',
				],
				[
					'q'    => 'Does it work on mobile and tablets?',
					'tags' => 'mobile phone tablet responsive device cellphone smartphone make it work on phone small screen android ios',
					'a'    => 'Yes. The viewer is responsive and works in browsers on computers, tablets and mobile phones. For best results use percentage or smaller pixel widths so the viewer scales to the screen.',
				],
				[
					'q'    => 'Do my visitors need Microsoft Office to view files?',
					'tags' => 'need office installed software requirement visitor reader without office convert',
					'a'    => 'No. The document is rendered by the online viewer engine (Google or Microsoft), so anyone can read it in their browser even without Office installed. You also do not need to convert files to PDF or HTML first.',
				],

				// ---- Free features ----
				[
					'q'    => 'What are the free features?',
					'tags' => 'free features included list what do i get without pro',
					'a'    => 'The free version gives you: <strong>2 viewer engines</strong> (Google Doc Viewer and Microsoft Online Viewer), embedding via block or shortcode, support for Word, Excel, PowerPoint, PDF and HTML files, and adjustable viewer <strong>width and height</strong>.',
				],
				[
					'q'    => 'What viewer engines are available?',
					'tags' => 'viewer type engine google microsoft online doc which renderer service display option',
					'a'    => 'Two engines are available for free: <strong>Google Doc Viewer</strong> (great for PDFs) and <strong>Microsoft Online Viewer</strong> (best for Word, Excel and PowerPoint). Pick whichever renders your file best.',
				],
				[
					'q'    => 'Which viewer should I choose, Google or Microsoft?',
					'tags' => 'google vs microsoft difference best engine choose which better recommend compare',
					'a'    => 'Use the <strong>Microsoft Online Viewer</strong> for Word, Excel and PowerPoint files. Use the <strong>Google Doc Viewer</strong> for PDFs and HTML, or as a fallback if a file will not load in Microsoft.',
				],
				[
					'q'    => 'What file types and formats are supported?',
					'tags' => 'supported formats format extension file type filetype doc docx xls xlsx ppt pptx pdf html word excel powerpoint which files accepted what formats can i show display list allowed',
					'a'    => 'Supported formats: Word (<code>.doc</code>, <code>.docx</code>), Excel (<code>.xls</code>, <code>.xlsx</code>), PowerPoint (<code>.ppt</code>, <code>.pptx</code>), PDF (<code>.pdf</code>) and HTML (<code>.html</code>).',
				],

				// ---- Install / activate / update ----
				[
					'q'    => 'How do I install the plugin?',
					'tags' => 'install installation setup add plugin upload zip get started begin',
					'a'    => 'In WordPress admin go to <strong>Plugins → Add New</strong>, search for "Document Viewer", click <strong>Install Now</strong>, then <strong>Activate</strong>. You can also upload the ZIP via <strong>Plugins → Add New → Upload Plugin</strong>.',
				],
				[
					'q'    => 'How do I activate the plugin?',
					'tags' => 'activate enable turn on activation after install',
					'a'    => 'After installing, go to <strong>Plugins</strong>, find Document Viewer and click <strong>Activate</strong>. A new <strong>Office Viewer</strong> menu then appears in the admin sidebar.',
				],
				[
					'q'    => 'How do I update the plugin?',
					'tags' => 'update upgrade new version latest plugin updates keep current',
					'a'    => 'When an update is available WordPress shows it under <strong>Plugins</strong> and <strong>Dashboard → Updates</strong>. Click <strong>Update Now</strong>. Updating keeps your existing documents and settings.',
				],
				[
					'q'    => 'What are the requirements?',
					'tags' => 'requirements php version wordpress minimum require needs compatible required',
					'a'    => 'Document Viewer requires <strong>WordPress 6.2+</strong> and <strong>PHP 7.4+</strong>. It is tested up to recent WordPress releases.',
				],

				// ---- How to use ----
				[
					'q'    => 'How do I embed a document?',
					'tags' => 'embed add insert document create new how to use display show put word doc file onto into my page post page widget place add word doc to my page show document',
					'a'    => 'Two ways: (1) Go to <strong>Office Viewer → Add New</strong>, give it a title, upload or paste your file, save, and copy the generated shortcode into any post or page. (2) In the block editor add the <strong>Embed Office Viewer</strong> block and pick your file.',
				],
				[
					'q'    => 'How do I create a new document viewer?',
					'tags' => 'create new add document item entry make add new office viewer',
					'a'    => 'Go to <strong>Office Viewer → Add New</strong>, enter a title, choose a viewer engine, upload or paste your file URL, set width/height, then Save. A shortcode is generated for you to paste anywhere.',
				],
				[
					'q'    => 'How do I use the shortcode?',
					'tags' => 'shortcode short code office_doc id paste insert post page widget how copy',
					'a'    => 'Each saved document has a shortcode like <code>[office_doc id=\'123\']</code>. Copy it from the document edit screen or the Shortcode column on the list table and paste it into any post, page or text widget.',
				],
				[
					'q'    => 'Where do I find my shortcode?',
					'tags' => 'find shortcode copy where get id location list column office_doc',
					'a'    => 'Open the document under <strong>Office Viewer</strong> — the shortcode is shown above the editor and you can click it to copy. It also appears in the <strong>ShortCode</strong> column on the Office Viewer list page.',
				],
				[
					'q'    => 'How do I use the Gutenberg block?',
					'tags' => 'block gutenberg editor embed office viewer add block insert blocks',
					'a'    => 'In the block editor click the <strong>+</strong> add-block button and search for <strong>Embed Office Viewer</strong>. Add it, then choose your document file and adjust settings in the right-hand panel.',
				],
				[
					'q'    => 'Can I embed a document by URL or external link?',
					'tags' => 'external url link paste remote file href address load from web online link',
					'a'    => 'Yes. In the Document field you can choose a file from the Media Library or paste an external file link (starting with <code>http://</code> or <code>https://</code>) directly.',
				],
				[
					'q'    => 'How do I upload a file from the Media Library?',
					'tags' => 'upload media library choose file picker attach select document local',
					'a'    => 'In the document settings click <strong>Choose File</strong> to open the Media Library, then upload or pick an existing file. You can also paste an external link instead of uploading.',
				],

				// ---- Settings: dimensions ----
				[
					'q'    => 'How do I change the width and height?',
					'tags' => 'width height size dimension resize bigger smaller large small set adjust display viewer big tall',
					'a'    => 'Set the <strong>Viewer Width</strong> and <strong>Viewer Height</strong> fields under the <strong>Document Settings</strong> section (or the <strong>Display &amp; Dimensions</strong> panel in the block). Defaults are <code>640px</code> wide and <code>900px</code> tall. Leave blank to use the defaults.',
				],
				[
					'q'    => 'What is the default size of the viewer?',
					'tags' => 'default size width height pixel px standard dimension',
					'a'    => 'The default viewer width is <code>640px</code> and the default height is <code>900px</code>. You can change both in the document settings.',
				],
				[
					'q'    => 'Can I make the viewer full width or responsive?',
					'tags' => 'full width responsive percentage percent 100% fluid stretch fit container scale',
					'a'    => 'In the block you can set the width using a percentage unit (for example <code>100%</code>) so the viewer fills its container. With the shortcode use the width setting on the document; smaller pixel widths also scale down on small screens.',
				],

				// ---- Settings: viewer type ----
				[
					'q'    => 'How do I switch the viewer engine?',
					'tags' => 'switch change viewer engine type google microsoft select setting radio change renderer',
					'a'    => 'Open the <strong>Document Settings</strong> section and choose the <strong>Viewer Type</strong> (Google Doc Viewer or Microsoft Online Viewer). In the block, use the <strong>Viewer Engine</strong> panel.',
				],
				[
					'q'    => 'What is the Document Settings section?',
					'tags' => 'document settings section configuration metabox viewer configuration options panel where settings fields',
					'a'    => 'The <strong>Document Settings</strong> section (inside the <strong>Viewer Configuration</strong> box on the document screen) is where you choose the <strong>Viewer Type</strong>, pick the <strong>Document</strong> file, and set the <strong>Viewer Width</strong> and <strong>Viewer Height</strong>.',
				],
				[
					'q'    => 'What is the Security & Restrictions section?',
					'tags' => 'security restrictions section panel protect right click download fullscreen popout where options settings menu',
					'a'    => 'The <strong>Security &amp; Restrictions</strong> section is where you protect your documents — disable right-click, hide the download and fullscreen icons, and hide the pop-out button. These options are available in the Pro version.',
					'pro'  => true,
				],

				// ---- Pro features ----
				[
					'q'    => 'Can I load files from Google Drive?',
					'tags' => 'google drive gdrive cloud import source load from drive document storage',
					'a'    => 'Yes — Document Viewer can embed files directly from <strong>Google Drive</strong> as a document source. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'Can I load files from Dropbox?',
					'tags' => 'dropbox cloud import source load from dropbox document storage link',
					'a'    => 'Yes — you can embed documents straight from <strong>Dropbox</strong>. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'Can I load files from OneDrive?',
					'tags' => 'onedrive one drive microsoft cloud import source storage skydrive',
					'a'    => 'Yes — <strong>OneDrive</strong> is supported as a document source so you can embed files from your Microsoft cloud storage. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'Is there a faster PDF viewer?',
					'tags' => 'pdf viewer fast custom js viewer engine optimized native pdfjs speed quick render pdf better',
					'a'    => 'Yes. The Pro version includes a <strong>Fast Custom PDF Viewer</strong> (JS Viewer) engine that is highly optimized for PDF files and renders them without relying on Google or Microsoft. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I hide the download button?',
					'tags' => 'hide remove download button disable stop prevent downloading get rid of download remove download btn no download',
					'a'    => 'You can hide the native <strong>Download</strong> icon on Office/PDF files so visitors cannot download the document. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I show a download button on top?',
					'tags' => 'show display download button top header add custom download premium button enable',
					'a'    => 'Pro lets you display a custom <strong>Download button</strong> in a header above the document so visitors can download it intentionally. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I hide the file name?',
					'tags' => 'hide file name title top header show display filename document name remove name',
					'a'    => 'Pro adds a setting to <strong>show or hide the file name</strong> in a header at the top of the document viewer. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I disable right-click on the document?',
					'tags' => 'disable right click rightclick context menu protect content copy stop copying lock so people cant copy prevent theft security download protection',
					'a'    => 'In the <strong>Security &amp; Restrictions</strong> panel, Pro can <strong>disable right-click</strong> on the document to help prevent visitors from easily copying or saving your content. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I stop people from copying my document?',
					'tags' => 'protect content prevent copy copying theft steal secure lock restrict right click protection cant copy',
					'a'    => 'Use the Pro <strong>Security &amp; Restrictions</strong> options — disable right-click, hide the download icon and hide the pop-out button — to make it harder for visitors to copy or save your document. These are Pro features.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I hide the fullscreen button?',
					'tags' => 'hide remove fullscreen full screen icon button disable expand maximize office files',
					'a'    => 'Pro lets you <strong>hide the fullscreen icon</strong> on Office/PowerPoint files so the document cannot be expanded to full screen. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'How do I hide the pop-out button?',
					'tags' => 'hide pop-out popout pop out button disable secure direct url access open new tab prevent',
					'a'    => 'Pro can <strong>hide the pop-out button</strong>, which stops visitors from opening the document directly via its URL in a new tab — useful for keeping documents inside your site. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'What are the Pro features?',
					'tags' => 'pro premium features upgrade paid list what extra unlock buy difference free vs pro',
					'a'    => 'Pro adds: Google Drive / Dropbox / OneDrive sources, a Fast Custom PDF Viewer engine, disable right-click, hide download &amp; fullscreen icons, show/hide file name and custom download button, and hide the pop-out button. These are Pro features.',
					'pro'  => true,
				],
				[
					'q'    => 'How much does Pro cost and how do I upgrade?',
					'tags' => 'price pricing cost buy purchase upgrade pro premium how much subscription plan',
					'a'    => 'You can see current plans and upgrade on the pricing page. Pro unlocks cloud sources, the fast PDF viewer and all security/display options.',
					'pro'  => true,
				],

				// ---- Troubleshooting ----
				[
					'q'    => 'My document is not showing or is blank.',
					'tags' => 'not showing blank empty broken white not loading does not work nothing appears missing fix problem display issue not displaying not working',
					'a'    => 'Check that: the file URL is public (not behind a login), the file type is supported, and you are using the right engine (Microsoft for Office files, Google for PDFs). For local files make sure your site is reachable over the internet — online viewers cannot read files on localhost or password-protected sites.',
				],
				[
					'q'    => 'Why does my file work on localhost but not load?',
					'tags' => 'localhost local testing not loading offline not public staging behind login internal viewer cant reach',
					'a'    => 'The Google and Microsoft online viewers fetch your file from the public internet, so files on <strong>localhost</strong>, intranet or password-protected/staging sites cannot be loaded. Use a publicly accessible file URL on a live site.',
				],
				[
					'q'    => 'My PDF will not display.',
					'tags' => 'pdf not showing blank pdf does not load pdf broken pdf display issue pdf viewer problem',
					'a'    => 'For PDFs try the <strong>Google Doc Viewer</strong> engine, and make sure the PDF URL is public. The Pro Fast Custom PDF Viewer renders PDFs more reliably without depending on Google.',
				],
				[
					'q'    => 'The viewer shows a wrong or old document.',
					'tags' => 'cache cached old version stale wrong document outdated not updated refresh',
					'a'    => 'Online viewers cache files. After replacing a file, clear your site/page cache and reload. Using a new file name or URL also forces the viewer to fetch the latest version.',
				],
				[
					'q'    => 'The document viewer looks too small or cut off.',
					'tags' => 'too small cut off cropped scroll height short overflow viewer size clipped fit',
					'a'    => 'Increase the <strong>Viewer Height</strong> (default <code>900px</code>) and width in the document settings so the whole document is visible without clipping.',
				],
				[
					'q'    => 'Does it conflict with other plugins or themes?',
					'tags' => 'conflict compatibility compatible theme plugin breaks issue works with another plugin page builder',
					'a'    => 'Document Viewer uses a standard shortcode and Gutenberg block, so it works with most themes and page builders. If you see a conflict, try switching the viewer engine, or temporarily deactivate other plugins to find the source.',
				],
				[
					'q'    => 'Can I use it with Elementor or other page builders?',
					'tags' => 'elementor page builder divi wpbakery beaver builder shortcode widget compatibility insert',
					'a'    => 'Yes. Paste the <code>[office_doc id=\'123\']</code> shortcode into a Shortcode/Text widget in Elementor, Divi, WPBakery or any builder that accepts shortcodes.',
				],
				[
					'q'    => 'Can I embed more than one document on a page?',
					'tags' => 'multiple documents many several more than one page two viewers add multiple embed several',
					'a'    => 'Yes. Add multiple blocks, or paste several shortcodes with different IDs on the same post or page.',
				],

				// ---- Admin / housekeeping ----
				[
					'q'    => 'Where do I manage my documents?',
					'tags' => 'manage list edit delete documents where menu office viewer admin find all',
					'a'    => 'All your documents live under the <strong>Office Viewer</strong> menu in the WordPress admin sidebar, where you can add, edit, copy shortcodes and delete them.',
				],
				[
					'q'    => 'Is my data sent anywhere?',
					'tags' => 'privacy data security send external third party google microsoft tracking gdpr server',
					'a'    => 'When you use the Google or Microsoft engines, the file URL is sent to those services so they can render it (this requires a public URL). The Pro Fast Custom PDF Viewer renders PDFs locally without sending them to a third party.',
				],
				[
					'q'    => 'Does it work without an internet connection?',
					'tags' => 'offline internet connection required no internet local render js viewer dependency',
					'a'    => 'The Google and Microsoft engines need an internet connection because they render the file on their servers. The Pro Fast Custom PDF Viewer can render PDFs on your own site.',
				],
				[
					'q'    => 'Can I password protect a document?',
					'tags' => 'password protect restrict access private members only login hide secure gated',
					'a'    => 'There is no built-in password option. To restrict access, place the document/shortcode inside a WordPress password-protected post or a membership plugin. The Pro security options (disable right-click, hide download/pop-out) help limit casual copying.',
				],

				// ---- Support / docs ----
				[
					'q'    => 'Where is the documentation?',
					'tags' => 'docs documentation help guide manual tutorial read more learn how to instructions',
					'a'    => 'Full documentation is available online — see the Documentation link on the <strong>Office Viewer → Help &amp; Demos</strong> page, which covers every feature in detail.',
				],
				[
					'q'    => 'How do I get support?',
					'tags' => 'support help contact assistance team ticket question problem reach out email',
					'a'    => 'You can reach the support team from the <strong>Help &amp; Demos</strong> page in the plugin, or open a thread on the WordPress.org support forum for the free version.',
				],
				[
					'q'    => 'How do I report a bug or request a feature?',
					'tags' => 'bug report feature request issue problem suggestion feedback broken contact',
					'a'    => 'Report bugs or request features through the support team (<strong>Help &amp; Demos</strong> page) or the WordPress.org support forum. Please include your WordPress version, the file type, and steps to reproduce.',
				],
				[
					'q'    => 'How can I leave a review or rate the plugin?',
					'tags' => 'review rate rating stars feedback wordpress org 5 star support us like',
					'a'    => 'If Document Viewer helps you, please leave a rating on the WordPress.org plugin page — it really helps. You can find the link on the <strong>Help &amp; Demos</strong> page.',
				],
				[
					'q'    => 'Where are the demos?',
					'tags' => 'demo demos example live preview sample show me see how it looks',
					'a'    => 'Live demos are linked from the <strong>Office Viewer → Help &amp; Demos</strong> page and on the product website, so you can see each viewer engine in action.',
				],

				// ---- Misc behavior ----
				[
					'q'    => 'Can I disable the full screen option?',
					'tags' => 'disable fullscreen full screen turn off expand maximize remove icon office',
					'a'    => 'Yes — Pro includes options to disable/hide the fullscreen control on Office files so the document stays inline. This is a Pro feature.',
					'pro'  => true,
				],
				[
					'q'    => 'Does it support HTML files?',
					'tags' => 'html htm web page file support display render view',
					'a'    => 'Yes. HTML files (<code>.html</code>) are supported — the Google Doc Viewer engine works well for them.',
				],
				[
					'q'    => 'Does it support Excel spreadsheets and PowerPoint slides?',
					'tags' => 'excel spreadsheet xlsx powerpoint slides pptx presentation sheet display support',
					'a'    => 'Yes. Excel (<code>.xls</code>, <code>.xlsx</code>) and PowerPoint (<code>.ppt</code>, <code>.pptx</code>) files are supported. The <strong>Microsoft Online Viewer</strong> renders these best.',
				],
				[
					'q'    => 'Why is the publish button called Save?',
					'tags' => 'publish save button label rename document publishing different editor',
					'a'    => 'On the <strong>Office Viewer</strong> document screen the standard "Publish" button is relabeled <strong>Save</strong> because each entry just stores your viewer configuration rather than publishing a public post.',
				],
				[
					'q'    => 'Is Document Viewer free?',
					'tags' => 'free cost price money paid is it free freemium trial',
					'a'    => 'Yes, the core plugin is free and includes both viewer engines plus width/height controls. A Pro version adds cloud sources, a fast PDF viewer and security/display options.',
				],
			];
		}
	}

	// Instantiate and register at file end.
	( new BPLEOV_Chatbot() )->register();
}
