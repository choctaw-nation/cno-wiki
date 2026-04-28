import { els } from './state';

// ---------------------------------------------------------------------------
// String normalization helpers
// ---------------------------------------------------------------------------

/**
 * Trims whitespace from a value and coerces it to a string.
 * Used as the base step before any further sanitization.
 */
export function normalizeValue( value: string ): string {
	return String( value || '' ).trim();
}

/**
 * Sanitizes a medium / channel name for use as a utm_medium value.
 * Lowercases the input and replaces internal whitespace with hyphens.
 */
export function sanitizeMedium( value: string ): string {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

/**
 * Sanitizes a vendor name for use as a utm_source value.
 * Lowercases the input and replaces internal whitespace with hyphens.
 */
export function sanitizeVendor( value: string ): string {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

/**
 * Sanitizes a campaign name for use as a utm_campaign value.
 * Lowercases the input and replaces internal whitespace with hyphens.
 */
export function sanitizeCampaign( value: string ): string {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

/**
 * Sanitizes a content name for use as a utm_content value.
 * Lowercases the input and replaces internal whitespace with hyphens.
 */
export function sanitizeContent( value: string ): string {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

// ---------------------------------------------------------------------------
// URL construction
// ---------------------------------------------------------------------------

/**
 * Builds a fully-qualified UTM-tagged URL from its component parts.
 * Returns an empty string when siteUrl is blank or invalid.
 *
 * @param siteUrl  - The base URL of the destination site.
 * @param vendor   - The advertising vendor (utm_source).
 * @param channel  - The media channel (utm_medium).
 * @param campaign - The campaign identifier (utm_campaign).
 * @param content  - The content / creative identifier (utm_content).
 */
export function buildLongUrl(
	siteUrl: string,
	vendor: string,
	channel: string,
	campaign: string,
	content: string
): string {
	const cleanSite = normalizeValue( siteUrl );
	if ( ! cleanSite ) return '';
	let url: URL;
	try {
		url = new URL( cleanSite );
	} catch {
		return '';
	}
	url.searchParams.set( 'utm_source', sanitizeVendor( vendor ) );
	url.searchParams.set( 'utm_medium', sanitizeMedium( channel ) );
	url.searchParams.set( 'utm_campaign', sanitizeCampaign( campaign ) );
	const sanitizedContent = sanitizeContent( content );
	if ( sanitizedContent ) {
		url.searchParams.set( 'utm_content', sanitizedContent );
	}
	return url.toString();
}

// ---------------------------------------------------------------------------
// CSV helpers
// ---------------------------------------------------------------------------

/**
 * Wraps a cell value in double-quotes and escapes internal quotes when the
 * value contains commas, newlines, or double-quote characters.
 * Follows RFC 4180 CSV quoting rules.
 */
export function csvEscape( value: unknown ): string {
	const stringValue = String( value == null ? '' : value );
	if (
		stringValue.includes( '"' ) ||
		stringValue.includes( ',' ) ||
		stringValue.includes( '\n' )
	) {
		return '"' + stringValue.replace( /"/g, '""' ) + '"';
	}
	return stringValue;
}

// ---------------------------------------------------------------------------
// UI feedback
// ---------------------------------------------------------------------------

/**
 * Updates the status bar element with a plain-text message.
 * Passing an empty string clears any previously displayed message.
 */
export function setStatus( message: string ): void {
	els.status.textContent = message || '';
}
