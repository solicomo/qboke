<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-05
 * */

require_once __DIR__ . '/pomo/mo.php';

/**
 * Gets the current locale.
 *
 * @return string The locale of the blog or from the 'locale' hook.
 */
function get_locale() {
	global $g_locale;
	global $g_config;

	if( !isset( $g_locale ) ) {

		if( isset( $g_config ) and !empty( $g_config['lang'] ) ) {
			$g_locale = $g_config['lang'];
		} else {
			$g_locale = LANG;
		}
	}

	return $g_locale;
}

/**
 * Returns the Translations instance for a domain. If there isn't one,
 * returns empty Translations instance.
 *
 * @param string $domain
 * @return object A Translation instance
 */
function get_translations_for_domain( $domain ) {
	global $g_l10n;
	if ( !isset( $g_l10n[$domain] ) ) {
		return false;
	}
	return $g_l10n[$domain];
}

/**
 * Retrieves the translation of $text. If there is no translation, or
 * the domain isn't loaded, the original text is returned.
 *
 * @see __() Don't use translate() directly, use __()
 *
 * @param string $text Text to translate.
 * @param string $domain Domain to retrieve the translated text.
 * @return string Translated text
 */
function translate( $text, $domain = 'default' ) {
	$trans = get_translations_for_domain( $domain );
	if( $trans === false ) {
		return $text;
	}
	return $trans->translate( $text );
}

/**
 * Retrieves the translation of $text. If there is no translation, or
 * the domain isn't loaded, the original text is returned.
 *
 * @see translate() An alias of translate()
 *
 * @param string $text Text to translate
 * @param string $domain Optional. Domain to retrieve the translated text
 * @return string Translated text
 */
function __( $text, $domain = 'default' ) {
	return translate( $text, $domain );
}

/**
 * Displays the returned translated text from translate().
 *
 * @see translate() Echoes returned translate() string
 *
 * @param string $text Text to translate
 * @param string $domain Optional. Domain to retrieve the translated text
 */
function _e( $text, $domain = 'default' ) {
	echo translate( $text, $domain );
}

/**
 * Loads a MO file into the domain $domain.
 *
 * If the domain already exists, the translations will be merged. If both
 * sets have the same string, the translation from the original value will be taken.
 *
 * On success, the .mo file will be placed in the $l10n global by $domain
 * and will be a MO object.
 *
 * @uses $g_l10n Gets list of domain translated string objects
 *
 * @param string $domain Unique identifier for retrieving translated strings
 * @param string $mofile Path to the .mo file
 * @return bool True on success, false on failure
 */
function load_textdomain( $domain, $mofile ) {
	global $g_l10n;

	if ( !is_readable( $mofile ) ) return false;

	$mo = new MO();
	if ( !$mo->import_from_file( $mofile ) ) return false;

	if ( isset( $g_l10n[$domain] ) )
		$mo->merge_with( $g_l10n[$domain] );

	$g_l10n[$domain] = &$mo;

	return true;
}

/**
 * Loads default translated strings based on locale.
 *
 * Loads the .mo file in LANG_DIR constant path from root.
 * The translated (.mo) file is named based on the locale.
 */
function load_default_textdomain() {
	$locale = get_locale();

	load_textdomain( 'default', LANG_DIR . "/$locale.mo" );
}

/**
 * Loads the plugin's translated strings.
 *
 * The translated (.mo) file is named based on the locale.
 */
function load_plugin_textdomain( $plugin ) {
	$locale = get_locale();

	$mofile = PLUGINS_DIR . "/$plugin/lang/$locale.mo";
	load_textdomain( "plugin_$plugin", $mofile );
}

/**
 * Loads the theme's translated strings.
 *
 * The translated (.mo) file is named based on the locale.
 */
function load_theme_textdomain( $theme ) {
	$locale = get_locale();

	$mofile = THEMES_DIR . "/$theme/lang/$locale.mo";
	load_textdomain( "theme_$theme", $mofile );
}

/**
 * Loads the convertor's translated strings.
 *
 * The translated (.mo) file is named based on the locale.
 */
function load_convertor_textdomain( $convertor ) {
	$locale = get_locale();

	$mofile = CONVERTORS_DIR . "/$convertor/lang/$locale.mo";
	load_textdomain( "convertor_$convertor", $mofile );
}
