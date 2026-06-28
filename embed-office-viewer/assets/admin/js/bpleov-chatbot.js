/**
 * Document Viewer — in-admin help chatbot.
 * Offline fuzzy search (Fuse.js) over a hardcoded knowledge base. No external AI.
 */
( function () {
	'use strict';

	var data = window.bpleovChatbot || {};
	var kb = data.kb || [];
	var suggestions = data.suggestions || [];
	var docsUrl = data.docsUrl || '';
	var pricingUrl = data.pricingUrl || '';
	var pluginName = data.pluginName || 'this plugin';
	var wporgSupportUrl = 'https://wordpress.org/support/plugin/embed-office-viewer/';

	var STORAGE_KEY = 'bpleov_chatbot_open';
	var FEEDBACK_KEY = 'bpleov_chatbot_feedback';

	if ( typeof Fuse === 'undefined' || ! kb.length ) {
		return;
	}

	// ---- Fuzzy search setup -------------------------------------------------

	var fuse = new Fuse( kb, {
		keys: [
			{ name: 'q', weight: 0.6 },
			{ name: 'tags', weight: 0.35 },
			{ name: 'a', weight: 0.05 }
		],
		threshold: 0.5,
		distance: 400,
		ignoreLocation: true,
		minMatchCharLength: 2,
		includeScore: true
	} );

	// Stopwords — filler plus this plugin's own generic words.
	var STOPWORDS = {
		the: 1, a: 1, an: 1, how: 1, do: 1, does: 1, i: 1, my: 1, to: 1, is: 1, are: 1,
		can: 1, of: 1, in: 1, on: 1, at: 1, what: 1, where: 1, when: 1, which: 1, it: 1,
		for: 1, with: 1, and: 1, or: 1, this: 1, that: 1, you: 1, your: 1, me: 1, we: 1,
		please: 1, help: 1, want: 1, need: 1, get: 1, set: 1, use: 1,
		// plugin-generic words
		document: 1, doc: 1, docs: 1, viewer: 1, view: 1, office: 1, file: 1, files: 1, embed: 1
	};
	var WORD_MATCH_OK = 0.5;

	function findBestMatch( query ) {
		// 1. Direct full-query search.
		var direct = fuse.search( query );
		if ( direct.length && direct[ 0 ].score <= 0.45 ) {
			return direct[ 0 ].item;
		}

		// 2. Tokenize: lowercase, strip non-alphanumerics, keep words > 2 chars
		//    that are not stopwords.
		var tokens = query
			.toLowerCase()
			.replace( /[^a-z0-9\s]/g, ' ' )
			.split( /\s+/ )
			.filter( function ( w ) {
				return w.length > 2 && ! STOPWORDS[ w ];
			} );

		if ( ! tokens.length ) {
			return ( direct.length && direct[ 0 ].score <= 0.45 ) ? direct[ 0 ].item : null;
		}

		// 3. Per-keyword search; ignore weak (gibberish) matches.
		var agg = {};
		tokens.forEach( function ( word ) {
			var results = fuse.search( word );
			results.forEach( function ( r ) {
				if ( r.score > WORD_MATCH_OK ) {
					return;
				}
				var idx = r.refIndex;
				if ( ! agg[ idx ] ) {
					agg[ idx ] = { item: r.item, total: 0, hits: 0 };
				}
				agg[ idx ].total += ( 1 - r.score );
				agg[ idx ].hits += 1;
			} );
		} );

		// 4. Pick the highest-ranked item.
		var best = null;
		Object.keys( agg ).forEach( function ( idx ) {
			var entry = agg[ idx ];
			entry.rank = entry.total + entry.hits * 0.2;
			if ( ! best || entry.rank > best.rank ) {
				best = entry;
			}
		} );

		// 5. Accept on enough evidence.
		if ( best && ( best.hits >= 2 || ( best.hits === 1 && best.rank >= 0.85 ) ) ) {
			return best.item;
		}

		// 6. Last resort.
		return ( direct.length && direct[ 0 ].score <= 0.45 ) ? direct[ 0 ].item : null;
	}

	function getBotReply( query ) {
		var item = findBestMatch( query );

		if ( ! item ) {
			return {
				html: 'Sorry, I could not find an answer to that. 🤔 Try rephrasing, or check the ' +
					'<a href="' + docsUrl + '" target="_blank" rel="noopener">documentation</a> ' +
					'or our <a href="' + wporgSupportUrl + '" target="_blank" rel="noopener">support forum</a>.',
				pro: false
			};
		}

		var html = item.a;
		if ( item.pro ) {
			html += '<div class="bpleov-pro-cta">💎 This is a <strong>Pro</strong> feature. ' +
				'<a href="' + pricingUrl + '" target="_blank" rel="noopener">Upgrade to ' + pluginName + ' Pro →</a></div>';
		}
		return { html: html, pro: !! item.pro };
	}

	// ---- UI -----------------------------------------------------------------

	function escapeHtml( str ) {
		var div = document.createElement( 'div' );
		div.appendChild( document.createTextNode( str ) );
		return div.innerHTML;
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var wrapper = document.getElementById( 'bpleov-chatbot-wrapper' );
		if ( ! wrapper ) {
			return;
		}

		var windowEl = document.getElementById( 'bpleov-chatbot-window' );
		var toggle = document.getElementById( 'bpleov-chatbot-toggle' );
		var closeBtn = document.getElementById( 'bpleov-chatbot-close' );
		var messages = document.getElementById( 'bpleov-chatbot-messages' );
		var suggestionsEl = document.getElementById( 'bpleov-chatbot-suggestions' );
		var input = document.getElementById( 'bpleov-chatbot-input' );
		var sendBtn = document.getElementById( 'bpleov-chatbot-send' );

		var greeted = false;

		function renderSuggestions() {
			suggestionsEl.innerHTML = '';
			suggestions.forEach( function ( text ) {
				var chip = document.createElement( 'button' );
				chip.type = 'button';
				chip.className = 'bpleov-suggestion-chip';
				chip.textContent = text;
				chip.addEventListener( 'click', function () {
					handleSend( text );
				} );
				suggestionsEl.appendChild( chip );
			} );
			suggestionsEl.style.display = 'flex';
		}

		function saveFeedback( query, helpful ) {
			var list = [];
			try {
				list = JSON.parse( localStorage.getItem( FEEDBACK_KEY ) || '[]' );
			} catch ( e ) {
				list = [];
			}
			list.push( { q: query, helpful: helpful } );
			if ( list.length > 50 ) {
				list = list.slice( list.length - 50 );
			}
			try {
				localStorage.setItem( FEEDBACK_KEY, JSON.stringify( list ) );
			} catch ( e ) {}
		}

		function addMessage( html, sender, showFeedback, queryForFeedback ) {
			var msg = document.createElement( 'div' );
			msg.className = 'bpleov-msg bpleov-msg--' + sender;
			msg.innerHTML = html;

			if ( showFeedback ) {
				var fb = document.createElement( 'div' );
				fb.className = 'bpleov-feedback';

				var label = document.createElement( 'span' );
				label.textContent = 'Was this helpful?';
				fb.appendChild( label );

				var up = document.createElement( 'button' );
				up.type = 'button';
				up.className = 'bpleov-fb-btn';
				up.textContent = '👍';

				var down = document.createElement( 'button' );
				down.type = 'button';
				down.className = 'bpleov-fb-btn';
				down.textContent = '👎';

				function vote( helpful ) {
					saveFeedback( queryForFeedback || '', helpful );
					fb.innerHTML = '';
					var thanks = document.createElement( 'span' );
					thanks.className = 'bpleov-fb-thanks';
					thanks.textContent = 'Thanks for your feedback!';
					fb.appendChild( thanks );
				}

				up.addEventListener( 'click', function () { vote( true ); } );
				down.addEventListener( 'click', function () { vote( false ); } );

				fb.appendChild( up );
				fb.appendChild( down );
				msg.appendChild( fb );
			}

			messages.appendChild( msg );
			messages.scrollTop = messages.scrollHeight;
			return msg;
		}

		function showTyping() {
			var typing = document.createElement( 'div' );
			typing.className = 'bpleov-msg bpleov-msg--bot bpleov-typing';
			typing.innerHTML = '<span></span><span></span><span></span>';
			messages.appendChild( typing );
			messages.scrollTop = messages.scrollHeight;
			return typing;
		}

		function handleSend( forcedText ) {
			var text = ( typeof forcedText === 'string' ? forcedText : input.value ).trim();
			if ( ! text ) {
				return;
			}
			input.value = '';
			suggestionsEl.style.display = 'none';

			addMessage( escapeHtml( text ), 'user', false );

			var typing = showTyping();
			setTimeout( function () {
				if ( typing && typing.parentNode ) {
					typing.parentNode.removeChild( typing );
				}
				var reply = getBotReply( text );
				addMessage( reply.html, 'bot', true, text );
			}, 500 );
		}

		function openWindow() {
			windowEl.classList.add( 'bpleov-open' );
			toggle.classList.add( 'bpleov-active' );
			try { localStorage.setItem( STORAGE_KEY, '1' ); } catch ( e ) {}

			if ( ! greeted ) {
				greeted = true;
				addMessage(
					'Hi! 👋 I\'m your <strong>' + pluginName + '</strong> assistant.<br>Ask me anything about the plugin.',
					'bot',
					false
				);
				renderSuggestions();
			}
			input.focus();
		}

		function closeWindow() {
			windowEl.classList.remove( 'bpleov-open' );
			toggle.classList.remove( 'bpleov-active' );
			try { localStorage.setItem( STORAGE_KEY, '0' ); } catch ( e ) {}
		}

		toggle.addEventListener( 'click', function () {
			if ( windowEl.classList.contains( 'bpleov-open' ) ) {
				closeWindow();
			} else {
				openWindow();
			}
		} );

		closeBtn.addEventListener( 'click', closeWindow );
		sendBtn.addEventListener( 'click', function () { handleSend(); } );

		input.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Enter' ) {
				e.preventDefault();
				handleSend();
			}
		} );

		// Restore persisted open state.
		var wasOpen = '0';
		try { wasOpen = localStorage.getItem( STORAGE_KEY ); } catch ( e ) {}
		if ( wasOpen === '1' ) {
			openWindow();
		}
	} );
} )();
