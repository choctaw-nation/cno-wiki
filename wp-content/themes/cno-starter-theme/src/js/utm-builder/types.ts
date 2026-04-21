/**
 * Data localized from PHP via wp_localize_script( 'utmBuilder', 'utmBuilderData', ... ).
 * Available on window before the script executes.
 */
declare global {
	interface Window {
		utmBuilderData?: {
			/** Medium values sourced from the `utm_mediums` ACF Repeater field. */
			mediums: string[];
		};
	}
}

/**
 * Represents a single UTM-tagged URL row in the builder table.
 * Each row captures the inputs used to construct one long URL.
 */
export interface UtmRow {
	/** The base site URL (e.g. https://example.com/) */
	siteUrl: string;
	/** The advertising vendor (maps to utm_source) */
	vendor: string;
	/** The media channel / medium (maps to utm_medium) */
	channel: string;
	/** The campaign name (maps to utm_campaign) */
	campaign: string;
	/** The fully constructed UTM long URL */
	longUrl: string;
}
