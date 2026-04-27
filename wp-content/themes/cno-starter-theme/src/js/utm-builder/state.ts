import { sanitizeMedium } from './utils';
import type { UtmRow } from './types';

/**
 * Fallback mediums used when the ACF repeater field provides no data.
 * These match the default channels used in most campaigns.
 */
const fallbackMediums = [ 'youtube', 'ott', 'sem', 'display', 'rgd', 'audio' ];

/**
 * The set of media channels (utm_medium values) available for row generation.
 * Built from the fallback list plus the `utm_mediums` ACF Repeater field
 * (via wp_localize_script). This guarantees the fallback defaults are always
 * present while still allowing ACF to add additional entries.
 * Users may add or remove entries at runtime.
 */
const localizedMediums = Array.isArray( window.utmBuilderData?.mediums )
	? window.utmBuilderData.mediums
			.map( ( medium ) => sanitizeMedium( String( medium || '' ) ) )
			.filter( Boolean )
	: [];

export const defaultMediums: string[] = Array.from(
	new Set( [ ...fallbackMediums, ...localizedMediums ] )
);

/** Mutable list of currently active mediums. */
export let mediums: string[] = [ ...defaultMediums ];

/** Mutable list of UTM rows currently displayed in the table. */
export let rows: UtmRow[] = [];

/** Tracks the source row index when a drag-and-drop reorder begins. */
export let dragSourceIndex: number | null = null;

/** Updates the dragSourceIndex during drag operations. */
export function setDragSourceIndex( index: number | null ): void {
	dragSourceIndex = index;
}

/** Replaces the entire rows array (used after generate / clear). */
export function setRows( next: UtmRow[] ): void {
	rows = next;
}

/** Replaces the entire mediums array (used after clear all). */
export function setMediums( next: string[] ): void {
	mediums = next;
}

/**
 * Typed references to every DOM element the builder interacts with.
 * Casting is safe here because the HTML template guarantees these elements exist.
 */
export const els = {
	siteUrl: document.getElementById( 'siteUrl' ) as HTMLInputElement,
	campaign: document.getElementById( 'campaign' ) as HTMLInputElement,
	vendor: document.getElementById( 'vendor' ) as HTMLInputElement,
	newMedium: document.getElementById( 'newMedium' ) as HTMLInputElement,
	mediumList: document.getElementById( 'mediumList' ) as HTMLElement,
	rowsBody: document.getElementById( 'rowsBody' ) as HTMLElement,
	status: document.getElementById( 'status' ) as HTMLElement,
	generateBtn: document.getElementById( 'generateBtn' ) as HTMLButtonElement,
	addMediumBtn: document.getElementById(
		'addMediumBtn'
	) as HTMLButtonElement,
	clearMediumsBtn: document.getElementById(
		'clearMediumsBtn'
	) as HTMLButtonElement,
	exportBtn: document.getElementById( 'exportBtn' ) as HTMLButtonElement,
	clearBtn: document.getElementById( 'clearBtn' ) as HTMLButtonElement,
};
